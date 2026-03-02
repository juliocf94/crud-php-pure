# CRUD-PHP-Pure

API REST construida en PHP puro sin frameworks, aplicando principios de arquitectura limpia: separación de responsabilidades, middleware pipeline, auto-discovery de rutas y contenedor de dependencias manual.

---

## Requisitos

- PHP 8.1+
- Composer
- Docker (opcional)

---

## Instalación

**Proyecto nuevo desde cero**
```bash
composer init
```

**Proyecto clonado desde GitHub**
```bash
composer install
```

**Con Docker**
```bash
docker-compose up -d
```

Copiar el archivo de entorno y configurar variables:
```bash
cp .env.example .env
```

---

## Estructura del proyecto

```
crud-php-pure/
│
├── public/
│   └── index.php                    # Front Controller — punto de entrada único
│
├── bootstrap/
│   └── app.php                      # Ensambla la app: .env, middleware y rutas
│
├── app/
│   ├── Core/
│   │   ├── Application.php          # Orquestador principal
│   │   ├── Database.php             # Conexión PDO
│   │   ├── Router.php               # Enrutador HTTP
│   │   ├── Response.php             # Helper de respuestas JSON
│   │   ├── ExceptionHandler.php     # Manejador global de errores
│   │   │
│   │   ├── Exceptions/
│   │   │   ├── HttpException.php
│   │   │   ├── NotFoundException.php
│   │   │   ├── ValidationException.php
│   │   │   └── UnauthorizedException.php
│   │   │
│   │   └── Middleware/
│   │       ├── MiddlewareInterface.php
│   │       ├── MiddlewarePipeline.php
│   │       ├── CorsMiddleware.php
│   │       ├── JsonBodyMiddleware.php
│   │       └── AuthMiddleware.php
│   │
│   ├── Controllers/
│   │   └── ClientController.php
│   │
│   └── Models/
│       └── ClientRepository.php
│
├── config/
│   └── database.php                 # Configuración de base de datos
│
├── routes/
│   ├── priority/                    # Rutas de carga garantizada (health checks, etc.)
│   │   └── health.php
│   │
│   └── modules/                     # Auto-discovery: cada módulo en su archivo
│       └── clients.php
│
├── storage/
│   └── logs/                        # Logs de la aplicación
│
├── .env
├── composer.json
└── docker-compose.yml
```

---

## Flujo de la aplicación

```
public/index.php
      │
      ▼
bootstrap/app.php              ← carga .env, middleware y rutas
      │
      ├── routes/priority/     ← carga manual (orden garantizado)
      │   └── health.php
      │
      └── routes/modules/      ← auto-discovery (todos los .php)
          └── clients.php
      │
      ▼
App\Core\Application           ← orquesta todo
      │
      ▼
MiddlewarePipeline → Router::dispatch()
```

---

## Sistema de rutas

Las rutas están separadas en dos categorías para garantizar flexibilidad y orden de carga:

**`routes/priority/`** — Carga manual y ordenada. Para rutas que deben registrarse primero: health checks, status, autenticación.

**`routes/modules/`** — Auto-discovery. Cualquier archivo `.php` en esta carpeta se carga automáticamente. Para añadir un módulo nuevo basta con crear el archivo; no es necesario modificar ningún otro fichero.

```php
// routes/modules/clients.php
$router->get('/api/clients',          fn()    => $controller->index());
$router->post('/api/clients',         fn()    => $controller->store());
$router->get('/api/clients/{id}',     fn($id) => $controller->show($id));
$router->put('/api/clients/{id}',     fn($id) => $controller->update($id));
$router->delete('/api/clients/{id}',  fn($id) => $controller->destroy($id));
```

---

## Endpoints disponibles

### Clients

| Método   | Endpoint             | Descripción             |
|----------|----------------------|-------------------------|
| `GET`    | `/api/clients`       | Listar todos los clientes |
| `POST`   | `/api/clients`       | Crear un cliente        |
| `GET`    | `/api/clients/{id}`  | Obtener un cliente      |
| `PUT`    | `/api/clients/{id}`  | Actualizar un cliente   |
| `DELETE` | `/api/clients/{id}`  | Eliminar un cliente     |

### Sistema

| Método | Endpoint  | Descripción          |
|--------|-----------|----------------------|
| `GET`  | `/health` | Estado de la API     |

---

## Variables de entorno

```env
APP_ENV=local

DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=crud_php
DB_USERNAME=root
DB_PASSWORD=secret
```

---

## Añadir un nuevo módulo

1. Crear el controlador en `app/Controllers/`
2. Crear el repositorio en `app/Models/`
3. Crear el archivo de rutas en `routes/modules/nombremodulo.php`

`index.php` y `bootstrap/app.php` **no requieren ninguna modificación**.

---

## Middleware

| Middleware          | Descripción                                  |
|---------------------|----------------------------------------------|
| `CorsMiddleware`    | Cabeceras CORS para peticiones cross-origin  |
| `JsonBodyMiddleware`| Parsea el body JSON entrante                 |
| `AuthMiddleware`    | Validación de token (desactivado por defecto)|

Para activar `AuthMiddleware`, descomentar en `bootstrap/app.php`:
```php
$app->addMiddleware(new AuthMiddleware());
```
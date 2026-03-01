### CRUD-PHP-Pure
 
 Primer paso: 

 1 - Verificamos contar con composer
 2 - Si iniiciamos el proyecto desde cero debemos ejecutar composer init 
 3 - Si descargarmos el proyecto desde github debemos ejecutar composer install para descargar las dependencias

 src/
│
├── public/
│   └── index.php
│
├── src/
│   ├── Core/
│   │   ├── Database.php
│   │   ├── Router.php
│   │   └── Response.php
│   │
│   ├── Controllers/
│   │   └── ClientController.php
│   │
│   ├── Models/
│   │   └── ClientRepository.php
│
├── config/
│   └── database.php
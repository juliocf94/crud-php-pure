<?php

namespace Config;

final class AppConfig
{
    public static function database(): array
    {
        return require BASE_PATH . '/config/database.php';
    }
}

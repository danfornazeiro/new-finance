<?php
return [
    'app' => [
        'name' => 'Financeiro SAAS',
        'version' => '1.0.0',
        'environment' => getenv('APP_ENV') ?? 'development'
    ],
    
    'database' => [
        'host' => getenv('DB_HOST') ?? 'localhost',
        'port' => getenv('DB_PORT') ?? 3306,
        'name' => getenv('DB_NAME') ?? 'financeiro_db',
        'user' => getenv('DB_USER') ?? 'root',
        'password' => getenv('DB_PASSWORD') ?? '',
        'charset' => 'utf8mb4'
    ],
    
    'jwt' => [
        'secret' => getenv('JWT_SECRET') ?? 'seu_secret_key_muito_seguro_aqui_2024',
        'algorithm' => 'HS256',
        'expiration' => 86400 * 7 // 7 dias
    ],
    
    'security' => [
        'password_hash_algorithm' => PASSWORD_BCRYPT,
        'password_hash_options' => ['cost' => 12],
        'cors_origins' => ['http://localhost:4200', 'http://localhost:3000'],
        'cors_methods' => ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS'],
        'cors_headers' => ['Content-Type', 'Authorization']
    ],
    
    'api' => [
        'prefix' => '/api',
        'version' => 'v1'
    ]
];

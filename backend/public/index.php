<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

require_once __DIR__ . '/../config/config.php';

// Autoloader simples
spl_autoload_register(function ($class) {
    $file = __DIR__ . '/../' . str_replace('\\', '/', $class) . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});

use App\Utils\Database;
use App\Utils\JWT;
use App\Utils\Auth;
use App\Utils\Response;

$config = require __DIR__ . '/../config/config.php';

try {
    // Inicializar banco de dados
    $db = new Database($config['database']);
    
    // Inicializar JWT e Auth
    $jwt = new JWT($config['jwt']);
    $auth = new Auth($jwt);
    
    // Parsear URL
    $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $path = str_replace('/api', '', $path);
    $method = $_SERVER['REQUEST_METHOD'];
    
    // Extrair ID se existir
    $pathParts = explode('/', trim($path, '/'));
    
    // Verificar autenticação (exceto login e register)
    if ($path !== '/auth/login' && $path !== '/auth/register' && !empty($_SERVER['HTTP_AUTHORIZATION'])) {
        try {
            $token = $auth->getTokenFromRequest();
            $userData = $auth->validateToken($token);
            $userId = $userData['id'];
        } catch (Exception $e) {
            Response::error('Acesso não autorizado: ' . $e->getMessage(), 401);
        }
    }
    
    // Roteamento
    switch (true) {
        // Auth
        case $path === '/auth/register' && $method === 'POST':
            $controller = new \App\Controllers\AuthController(new \App\Services\AuthService(
                new \App\Models\User($db),
                $auth
            ));
            $controller->register();
            break;
            
        case $path === '/auth/login' && $method === 'POST':
            $controller = new \App\Controllers\AuthController(new \App\Services\AuthService(
                new \App\Models\User($db),
                $auth
            ));
            $controller->login();
            break;
        
        // Transactions
        case preg_match('#^/transactions/?$#', $path) && $method === 'GET':
            $controller = new \App\Controllers\TransactionController(new \App\Services\TransactionService(
                new \App\Models\Transaction($db),
                new \App\Models\TransactionType($db),
                new \App\Models\PaymentMethod($db)
            ));
            $controller->index($userId ?? null);
            break;
            
        case preg_match('#^/transactions/(\d+)/?$#', $path, $matches) && $method === 'GET':
            $id = $matches[1];
            $controller = new \App\Controllers\TransactionController(new \App\Services\TransactionService(
                new \App\Models\Transaction($db),
                new \App\Models\TransactionType($db),
                new \App\Models\PaymentMethod($db)
            ));
            $controller->show($id, $userId ?? null);
            break;
            
        case $path === '/transactions' && $method === 'POST':
            $controller = new \App\Controllers\TransactionController(new \App\Services\TransactionService(
                new \App\Models\Transaction($db),
                new \App\Models\TransactionType($db),
                new \App\Models\PaymentMethod($db)
            ));
            $controller->store($userId ?? null);
            break;
            
        case preg_match('#^/transactions/(\d+)/?$#', $path, $matches) && $method === 'PUT':
            $id = $matches[1];
            $controller = new \App\Controllers\TransactionController(new \App\Services\TransactionService(
                new \App\Models\Transaction($db),
                new \App\Models\TransactionType($db),
                new \App\Models\PaymentMethod($db)
            ));
            $controller->update($id, $userId ?? null);
            break;
            
        case preg_match('#^/transactions/(\d+)/?$#', $path, $matches) && $method === 'DELETE':
            $id = $matches[1];
            $controller = new \App\Controllers\TransactionController(new \App\Services\TransactionService(
                new \App\Models\Transaction($db),
                new \App\Models\TransactionType($db),
                new \App\Models\PaymentMethod($db)
            ));
            $controller->destroy($id, $userId ?? null);
            break;
        
        // Transaction Types
        case preg_match('#^/transaction-types/?$#', $path) && $method === 'GET':
            $controller = new \App\Controllers\TransactionTypeController(new \App\Services\TransactionTypeService(
                new \App\Models\TransactionType($db)
            ));
            $controller->index($userId ?? null);
            break;
            
        case $path === '/transaction-types' && $method === 'POST':
            $controller = new \App\Controllers\TransactionTypeController(new \App\Services\TransactionTypeService(
                new \App\Models\TransactionType($db)
            ));
            $controller->store($userId ?? null);
            break;
            
        case preg_match('#^/transaction-types/(\d+)/?$#', $path, $matches) && $method === 'PUT':
            $id = $matches[1];
            $controller = new \App\Controllers\TransactionTypeController(new \App\Services\TransactionTypeService(
                new \App\Models\TransactionType($db)
            ));
            $controller->update($id, $userId ?? null);
            break;
            
        case preg_match('#^/transaction-types/(\d+)/?$#', $path, $matches) && $method === 'DELETE':
            $id = $matches[1];
            $controller = new \App\Controllers\TransactionTypeController(new \App\Services\TransactionTypeService(
                new \App\Models\TransactionType($db)
            ));
            $controller->destroy($id, $userId ?? null);
            break;
        
        // Payment Methods
        case preg_match('#^/payment-methods/?$#', $path) && $method === 'GET':
            $controller = new \App\Controllers\PaymentMethodController(new \App\Services\PaymentMethodService(
                new \App\Models\PaymentMethod($db)
            ));
            $controller->index($userId ?? null);
            break;
            
        case $path === '/payment-methods' && $method === 'POST':
            $controller = new \App\Controllers\PaymentMethodController(new \App\Services\PaymentMethodService(
                new \App\Models\PaymentMethod($db)
            ));
            $controller->store($userId ?? null);
            break;
            
        case preg_match('#^/payment-methods/(\d+)/?$#', $path, $matches) && $method === 'PUT':
            $id = $matches[1];
            $controller = new \App\Controllers\PaymentMethodController(new \App\Services\PaymentMethodService(
                new \App\Models\PaymentMethod($db)
            ));
            $controller->update($id, $userId ?? null);
            break;
            
        case preg_match('#^/payment-methods/(\d+)/?$#', $path, $matches) && $method === 'DELETE':
            $id = $matches[1];
            $controller = new \App\Controllers\PaymentMethodController(new \App\Services\PaymentMethodService(
                new \App\Models\PaymentMethod($db)
            ));
            $controller->destroy($id, $userId ?? null);
            break;
        
        // Dashboard
        case preg_match('#^/dashboard/summary/?$#', $path) && $method === 'GET':
            $controller = new \App\Controllers\TransactionController(new \App\Services\TransactionService(
                new \App\Models\Transaction($db),
                new \App\Models\TransactionType($db),
                new \App\Models\PaymentMethod($db)
            ));
            $controller->dashboard($userId ?? null);
            break;
        
        default:
            Response::error('Rota não encontrada', 404);
    }
    
} catch (Exception $e) {
    Response::error('Erro interno do servidor: ' . $e->getMessage(), 500);
}

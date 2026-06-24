<?php
session_start();

require __DIR__ . '/../app/Core/helpers.php';
require __DIR__ . '/../app/Core/Router.php';
require __DIR__ . '/../app/Core/Database.php';
require __DIR__ . '/../app/Core/DuplicateRecordException.php';

require __DIR__ . '/../app/Repositories/EquipmentRepository.php';
require __DIR__ . '/../app/Repositories/RentalSlipRepository.php';

require __DIR__ . '/../app/Controllers/EquipmentController.php';
require __DIR__ . '/../app/Controllers/RentalSlipController.php';
require __DIR__ . '/../app/Controllers/HealthController.php';
require __DIR__ . '/../app/Controllers/HomeController.php';

$router = new Router();

$router->get('/', [HomeController::class, 'index']);
$router->get('/health', [HealthController::class, 'index']);

$router->get('/equipments', [EquipmentController::class, 'index']);
$router->get('/equipments/create', [EquipmentController::class, 'create']);
$router->post('/equipments/store', [EquipmentController::class, 'store']);
$router->get('/equipments/edit', [EquipmentController::class, 'edit']);
$router->post('/equipments/update', [EquipmentController::class, 'update']);
$router->post('/equipments/delete', [EquipmentController::class, 'delete']);

$router->get('/rentals', [RentalSlipController::class, 'index']);
$router->get('/rentals/create', [RentalSlipController::class, 'create']);
$router->post('/rentals/store', [RentalSlipController::class, 'store']);
$router->get('/rentals/edit', [RentalSlipController::class, 'edit']);
$router->post('/rentals/update', [RentalSlipController::class, 'update']);
$router->post('/rentals/delete', [RentalSlipController::class, 'delete']);

try {
    $router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
} catch (Throwable $e) {
    $logFile = __DIR__ . '/../storage/logs/app.log';
    $logMessage = "[" . date('Y-m-d H:i:s') . "] LỖI HỆ THỐNG: " . $e->getMessage() . " tại " . $e->getFile() . " dòng " . $e->getLine() . "\n";
    error_log($logMessage, 3, $logFile);

    http_response_code(500);
    view('errors/500');
}

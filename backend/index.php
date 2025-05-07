<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/dao/config.php';

// Enable CORS
Flight::before('start', function() {
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");
});

// Services
require 'services/userService.php';
require 'services/bookingService.php';
require 'services/destinationService.php';
require 'services/contactUsService.php';
require 'services/packageService.php';

// Register services
Flight::register('userService', 'userService');
Flight::register('bookingService', 'bookingService');
Flight::register('destinationService', 'destinationService');
Flight::register('contactUsService', 'contactUsService');
Flight::register('packageService', 'packageService');

// Routes
require_once 'routes/userRoutes.php';
require_once 'routes/bookingRoutes.php';
require_once 'routes/destinationRoutes.php';
require_once 'routes/contactUsRoutes.php';
require_once 'routes/packageRoutes.php';

Flight::route('GET /connection-test', function(){
    try {
        $db = Database::connect();
        Flight::json(['status' => 'success', 'message' => 'Connected to webApp']);
    } catch (Exception $e) {
        Flight::halt(500, json_encode([
            'status' => 'error',
            'message' => $e->getMessage(),
            'details' => [
                'host' => 'localhost',
                'port' => '8889',
                'dbname' => 'webApp',
                'socket' => '/Applications/MAMP/tmp/mysql/mysql.sock'
            ]
        ]));
    }
});

// OpenAPI docs
Flight::route('GET /docs', function() {
    header('Content-Type: application/yaml');
    readfile(__DIR__.'/docs/openapi.yaml');
});

// Base URL configuration for MAMP
Flight::set('flight.base_url', '/travelApp/backend/');

Flight::start();


Flight::route('GET /db-test', function() {
    try {
        require_once __DIR__ . '/dao/config.php';
        $db = Database::connect();
        
        // Test query
        $stmt = $db->query("SELECT 1 AS db_test, NOW() AS server_time");
        $result = $stmt->fetch();
        
        Flight::json([
            'status' => 'success',
            'database' => 'Connection successful',
            'result' => $result,
            'connection_method' => strpos($db->getAttribute(PDO::ATTR_CONNECTION_STATUS), 'socket') !== false 
                ? 'Unix socket' 
                : 'TCP/IP'
        ]);
        
    } catch (Exception $e) {
        Flight::halt(500, json_encode([
            'status' => 'error',
            'message' => $e->getMessage(),
            'config_used' => [
                'host' => Database::getHost(),
                'port' => Database::getPort(),
                'dbname' => Database::getDbName(),
                'socket' => Database::getSocket()
            ]
        ]));
    }
});
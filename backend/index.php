<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/dao/config.php';

// Use statements
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

// Services
require_once 'services/userService.php';
require_once 'services/bookingService.php';
require_once 'services/destinationService.php';
require_once 'services/contactUsService.php';
require_once 'services/packageService.php';
require_once 'services/AuthService.php';

// Middleware and roles
require_once 'middleware/AuthMiddleware.php';
require_once 'middleware/Roles.php';

// Register services
Flight::register('userService', 'userService');
Flight::register('bookingService', 'bookingService');
Flight::register('destinationService', 'destinationService');
Flight::register('contactUsService', 'contactUsService');
Flight::register('packageService', 'packageService');
Flight::register('auth_service', 'AuthService');
Flight::register('auth_middleware', 'AuthMiddleware');

// Enable CORS
Flight::before('start', function () {
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");
});

// Global Middleware: Token verification
Flight::route('/*', function () {
    $url = Flight::request()->url;

    if (
        strpos($url, '/auth/login') === 0 || strpos($url, '/auth/register') === 0
    ) {
        return true; // Allow login/register without auth
    }

    try {
        $token = Flight::request()->getHeader("Authorization");

        if (!Flight::auth_middleware()->verifyToken($token)) {
            Flight::halt(401, "Invalid or missing token.");
        }
    } catch (Exception $e) {
        Flight::halt(401, $e->getMessage());
    }
});

// Routes
require_once 'routes/userRoutes.php';
require_once 'routes/bookingRoutes.php';
require_once 'routes/destinationRoutes.php';
require_once 'routes/contactUsRoutes.php';
require_once 'routes/packageRoutes.php';
require_once 'routes/AuthRoutes.php';

// Example of route with role check
Flight::route('GET /restaurant', function () {
    Flight::auth_middleware()->authorizeRole(Roles::USER);
    $location = Flight::request()->query['location'] ?? null;
    Flight::json(Flight::restaurantService()->get_restaurants($location));
});

// OpenAPI docs
Flight::route('GET /docs', function () {
    header('Content-Type: application/yaml');
    readfile(__DIR__ . '/docs/openapi.yaml');
});

// MAMP base URL
Flight::set('flight.base_url', '/travelApp/backend/');

// DB connection test
Flight::route('GET /connection-test', function () {
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

// DB diagnostic route
Flight::route('GET /db-test', function () {
    try {
        $db = Database::connect();
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

// Start the app
Flight::start();

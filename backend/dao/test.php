<?php
require_once 'config.php'; // Ensure the correct path if it's in another folder

try {
    $db = Database::connect();
    echo "Database connection successful!";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
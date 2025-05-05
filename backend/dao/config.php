<?php
class Database {
    // Configuration 
    private static $host = 'localhost';
    private static $port = '8889';
    private static $dbName = 'webApp';
    private static $username = 'root';
    private static $password = 'rootroot';
    private static $connection = null;

    public static function connect() {
        if (self::$connection === null) {
            try {
                //standard connection first
                $dsn = "mysql:host=".self::$host.";port=".self::$port.";dbname=".self::$dbName;
                self::$connection = new PDO($dsn, self::$username, self::$password, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]);
            } catch (PDOException $e) {
                // Fallback to MAMP socket if standard fails
                try {
                    $dsn = "mysql:unix_socket=/Applications/MAMP/tmp/mysql/mysql.sock;dbname=".self::$dbName;
                    self::$connection = new PDO($dsn, self::$username, self::$password, [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
                    ]);
                } catch (PDOException $e) {
                    die("❌ Connection failed via all methods. Check:<br>
                        1. MAMP MySQL is running<br>
                        2. Password: 'rootroot'<br>
                        3. DB exists: 'webApp'<br>
                        Error: ".$e->getMessage());
                }
            }
        }
        return self::$connection;
    }
}
?>
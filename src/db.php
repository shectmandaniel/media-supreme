<?php

require_once '../index.php';

// Define a function to get a database connection
function getDbConnection() {
    // Declare $pdo as static so it persists between function calls
    static $pdo = null;

    // If $pdo is not yet set
    if ($pdo === null) {
        // Get the database host, name, username, and password from environment variables
        $host = getenv('DB_HOST');
        $db = getenv('DB_DATABASE');
        $user = getenv('DB_USERNAME'); 
        $pass = getenv('DB_PASSWORD');

        // Define the DSN for the MySQL connection
        $dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";
        // Define options for the PDO object
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Throw exceptions on errors
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Return rows as associative arrays
            PDO::ATTR_EMULATE_PREPARES   => false, // Use real prepared statements
        ];

        // Try to create a new PDO object
        try {
            $pdo = new PDO($dsn, $user, $pass, $options);
        } catch (PDOException $e) {
            // If an error occurs, dump the error message and stop execution
            var_dump($e->getMessage());die;
            // Then throw the exception again
            throw new PDOException($e->getMessage(), (int)$e->getCode());
        }
    }

    // Return the PDO object
    return $pdo;
}
?>
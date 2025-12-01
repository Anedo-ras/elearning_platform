<?php
// Database setup script

$host = getenv('DB_HOST');
$db   = getenv('DB_NAME');
$user = getenv('DB_USER');
$pass = getenv('DB_PASS');
$port = getenv('DB_PORT');

$conn = new mysqli($host, $user, $pass, $db, $port);
if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

// Use __DIR__ to get the directory of this script
$schema_file = __DIR__ . '/../database/schema.sql';

if (!file_exists($schema_file)) {
    die('Schema file not found: ' . $schema_file);
}

$schema = file_get_contents($schema_file);
$queries = array_filter(array_map('trim', explode(';', $schema)));

foreach ($queries as $query) {
    if (!empty($query)) {
        if (!$conn->query($query)) {
            echo "Error executing query: " . $conn->error . "<br>";
            echo "Query: " . $query . "<br><br>";
        }
    }
}

echo "Database setup completed!";
$conn->close();
?>

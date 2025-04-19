<?php
// Connect to database
$host     = "localhost";
$username = "root";
$password = "Hellcat24";
$dbname   = "mdd_productions";

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Create table if it doesn't exist
$conn->query("
    CREATE TABLE IF NOT EXISTS site_visits (
        id INT PRIMARY KEY AUTO_INCREMENT,
        count INT NOT NULL DEFAULT 1
    )
");

// Initialize visit count if empty
$conn->query("INSERT INTO site_visits (id, count) SELECT 1, 1 FROM DUAL WHERE NOT EXISTS (SELECT * FROM site_visits WHERE id = 1)");

// Increment the counter
$conn->query("UPDATE site_visits SET count = count + 1 WHERE id = 1");

$conn->close();
?>

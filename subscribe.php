<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = filter_var(trim($_POST['subscriber_email']), FILTER_SANITIZE_EMAIL);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email address.");
    }

    // Connect to database (MySQL)
    $host     = "localhost";     // Change to your DB host
    $username = "root"; // Change to your DB user
    $password = "Hellcat24"; // Change to your DB password
    $dbname   = "mdd_productions";        // Change to your DB name

    $conn = new mysqli($host, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Create table if it doesn't exist
    $conn->query("
        CREATE TABLE IF NOT EXISTS subscribers (
            id INT AUTO_INCREMENT PRIMARY KEY,
            email VARCHAR(255) UNIQUE NOT NULL,
            subscribed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ");

    // Insert subscriber
    $stmt = $conn->prepare("INSERT IGNORE INTO subscribers (email) VALUES (?)");
    $stmt->bind_param("s", $email);

    if ($stmt->execute()) {
        echo "<p style='color: green; text-align:center;'>Thank you for subscribing!</p>";
    } else {
        echo "<p style='color: red; text-align:center;'>There was an error. Please try again.</p>";
    }

    $stmt->close();
    $conn->close();
}
?>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $message = $_POST['message'];

    // Handle the form submission logic (e.g., save to database, send an email, etc.)
    mail("youremail@example.com", "New Contact Message from $name", $message, "From: $email");

    echo "Thank you for contacting us!";
}
?>

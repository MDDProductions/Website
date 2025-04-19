<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Sanitize input
    $name     = htmlspecialchars($_POST['name']);
    $email    = htmlspecialchars($_POST['email']);
    $message  = htmlspecialchars($_POST['message']);
    $division = htmlspecialchars($_POST['division']);

    // Define division emails
    $divisionEmails = [
        "monumental" => "monumentaldwellingsdynamics@mddproductions.tv",
        "mainstream" => "mainstreamdigitaldynamics@mddproductions.tv",
        "merchandise" => "merchandisedonedifferent@mddproductions.tv"
    ];  // <-- Added semicolon here

    // Check for valid division
    if (!array_key_exists($division, $divisionEmails)) {
        die("Invalid division selected.");
    }

    // Email details
    $to      = $divisionEmails[$division];
    $subject = "New Contact Form Submission - $division";
    $body    = "You have received a new message:\n\n"
             . "Name: $name\n"
             . "Email: $email\n"
             . "Division: $division\n\n"
             . "Message:\n$message\n";
    $headers = "From: $email\r\n";
    $headers .= "Reply-To: $email\r\n";

    // Send the email
    $mailSent = mail($to, $subject, $body, $headers);

    // Save to file (backup)
    $folderPath = __DIR__ . "/messages/$division";
    if (!is_dir($folderPath)) {
        mkdir($folderPath, 0755, true);
    }

    $timestamp = date("Y-m-d_H-i-s");
    $filename = "$folderPath/message_$timestamp.txt";
    $fileContent = "Name: $name\nEmail: $email\nDivision: $division\nMessage:\n$message\n";

    file_put_contents($filename, $fileContent);

    // Response to user
    if ($mailSent) {
        echo "<p style='color: green;'>Message sent and backed up successfully to the $division division.</p>";
    } else {
        echo "<p style='color: orange;'>Backup saved, but there was an error sending your message via email.</p>";
    }
}
?>

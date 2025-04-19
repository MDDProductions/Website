<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Sanitize input
    $name     = strip_tags(trim($_POST['name']));
    $email    = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $message  = strip_tags(trim($_POST['message']));
    $division = trim($_POST['division']);

    // Email addresses per division
    $divisionEmails = [
        "monumental"  => "monumentaldwellingsdynamics@mddproductions.tv",
        "mainstream"  => "mainstreamdigitaldynamics@mddproductions.tv",
        "merchandise" => "merchandisedonedifferent@mddproductions.tv"
    ];

    // Validate division
    if (!array_key_exists($division, $divisionEmails)) {
        http_response_code(400);
        echo "Invalid division selected.";
        exit;
    }

    // Prepare email
    $to      = $divisionEmails[$division];
    $subject = "New Contact Form Submission - $division";
    $body    = "You received a new message:\n\n"
             . "Name: $name\n"
             . "Email: $email\n"
             . "Division: $division\n\n"
             . "Message:\n$message\n";
    $headers = "From: no-reply@mddproductions.tv\r\n";
    $headers .= "Reply-To: $email\r\n";

    // Email and backup
    $mailSent = mail($to, $subject, $body, $headers);

    // Backup message in a file
    $folderPath = __DIR__ . "/messages/$division";
    if (!is_dir($folderPath)) {
        mkdir($folderPath, 0755, true);
    }

    $timestamp = date("Y-m-d_H-i-s");
    $filename = "$folderPath/message_$timestamp.txt";
    $fileContent = "Name: $name\nEmail: $email\nDivision: $division\nMessage:\n$message\n";
    file_put_contents($filename, $fileContent);

    // Show response
    if ($mailSent) {
        echo "<p style='color: green;'>Message sent and saved successfully!</p>";
    } else {
        echo "<p style='color: orange;'>Message saved, but email failed to send.</p>";
    }
} else {
    http_response_code(405);
    echo "Method not allowed. Please use POST.";
}
?>

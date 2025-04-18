<?php
$name     = $_POST['name'];
$email    = $_POST['email'];
$method   = $_POST['payment'];
$message  = $_POST['message'] ?? '';

$recipient = '';
switch ($method) {
    case 'paypal':
        $recipient = 'paypal@mddproductions.tv';
        break;
    case 'cashapp':
        $recipient = 'cashapp@mddproductions.tv';
        break;
    case 'venmo':
        $recipient = 'venmo@mddproductions.tv';
        break;
    case 'zelle':
        $recipient = 'zelle@mddproductions.tv';
        break;
    default:
        $recipient = 'support@mddproductions.tv';
}

// Email content
$subject = "Payment Inquiry - $method";
$body = "Name: $name\nEmail: $email\nPayment Method: $method\n\nMessage:\n$message";

// Send the email
mail($recipient, $subject, $body, "From: $email");

// Optional: Save a backup
$folder = "submissions/payment/";
if (!is_dir($folder)) {
    mkdir($folder, 0777, true);
}
$file = $folder . date('Y-m-d_H-i-s') . '_' . $method . '.txt';
file_put_contents($file, $body);

echo "Thank you! Your request has been submitted.";
?>

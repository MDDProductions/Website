<?php
// DB connection settings
$host = 'localhost';
$db   = 'mdd_productions';
$user = 'root'; // replace with your DB username
$pass = 'Hellcat24';     // replace with your DB password

// Connect to database
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $validPositions = [
        'real-estate-agent' => 'monumentaldwellingsdynamics@mddproductions.tv',
        'content-creator' => 'mainstreamdigitaldynamics@mddproductions.tv',
        'marketing-specialist' => 'merchandisedonedifferent@mddproductions.tv',
    ];

    $jobPosition = $_POST['job-position'] ?? '';
    $resume = $_FILES['resume'] ?? null;

    if (!array_key_exists($jobPosition, $validPositions)) {
        die("Invalid job position selected.");
    }

    $allowedExtensions = ['pdf', 'doc', 'docx'];
    $fileExtension = strtolower(pathinfo($resume['name'], PATHINFO_EXTENSION));

    if (!in_array($fileExtension, $allowedExtensions)) {
        die("Invalid file type. Only PDF, DOC, and DOCX are allowed.");
    }

    $safeFileName = preg_replace("/[^A-Za-z0-9_\-\.]/", "_", basename($resume["name"]));
    $targetDir = "uploads/";
    $targetFile = $targetDir . time() . "_" . $safeFileName;

    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0755, true);
    }

    if (move_uploaded_file($resume["tmp_name"], $targetFile)) {
        $emailTo = $validPositions[$jobPosition];

        $subject = "New Application for " . ucwords(str_replace('-', ' ', $jobPosition));
        $message = "A new application has been submitted for the $jobPosition position.\n\nResume file: $targetFile";
        $headers = "From: no-reply@company.com";

        $mailSent = mail($emailTo, $subject, $message, $headers);

        // Insert into database
        $stmt = $conn->prepare("INSERT INTO applications (job_position, file_name, file_path) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $jobPosition, $safeFileName, $targetFile);
        $stmt->execute();

        echo $mailSent 
            ? "Application submitted and saved successfully!"
            : "Application saved, but email failed to send.";
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}

$conn->close();
?>

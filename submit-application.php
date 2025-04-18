<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $jobPosition = $_POST['job-position'];
    $resume = $_FILES['resume'];

    // Store resume in a folder
    $targetDir = "uploads/";
    $targetFile = $targetDir . basename($resume["name"]);

    if (move_uploaded_file($resume["tmp_name"], $targetFile)) {
        // Email Configuration
        $emails = [
            'real-estate-agent' => 'monumentaldwellingsdynamics@mddproductions.tv',
            'content-creator' => 'mainstreamdigitaldynamics@mddproductions.tv',
            'marketing-specialist' => 'merchandisedonedifferent@mddproductions.tv',
        ];

        $emailTo = $emails[$jobPosition];

        // Send email (you need to configure mail settings in PHP)
        $subject = "New Application for $jobPosition";
        $message = "A new application has been submitted for the $jobPosition position. Please review the attached resume.";
        $headers = "From: no-reply@company.com";

        mail($emailTo, $subject, $message, $headers);

        echo "Application submitted successfully!";
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}
?>

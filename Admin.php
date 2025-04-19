<?php
// Simple authentication (hardcoded username/password)
$username = 'root';  // Set a username
$password = 'Hellcat24'; // Set a password

session_start();

// Authenticate user
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($_POST['username'] == $username && $_POST['password'] == $password) {
        $_SESSION['logged_in'] = true;
    } else {
        echo "Incorrect username or password.";
    }
}

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] != true) {
    echo '<form method="post">
            Username: <input type="text" name="username" required><br>
            Password: <input type="password" name="password" required><br>
            <button type="submit">Login</button>
          </form>';
    exit();
}

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

// Fetch all applications from the database
$query = "SELECT * FROM applications ORDER BY submitted_at DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - View Applications</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid #ccc;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #004ad4;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        a {
            color: #004ad4;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <h1>Admin - View Applications</h1>

    <?php if ($result->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Job Position</th>
                    <th>File Name</th>
                    <th>File Download</th>
                    <th>Date Submitted</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars(ucwords(str_replace('-', ' ', $row['job_position']))); ?></td>
                        <td><?php echo htmlspecialchars($row['file_name']); ?></td>
                        <td><a href="<?php echo $row['file_path']; ?>" download>Download</a></td>
                        <td><?php echo $row['submitted_at']; ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No applications yet.</p>
    <?php endif; ?>

    <?php $conn->close(); ?>
</body>
</html>

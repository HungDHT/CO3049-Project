<?php
// Start the session
session_start();

// Database connection parameters
include("connection.php");

// Function to sanitize input
function sanitizeInput($data) {
    return htmlspecialchars(trim($data));
}

// Validate and receive values from the login form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = sanitizeInput($_POST['email']); // Keep using email as the input
    $password = sanitizeInput($_POST['password']);

    // Basic validation on the server side
    if (empty($email) || empty($password)) {
        echo "<script>alert('Both fields are required.'); window.location.href='index.php?page=login';</script>";
        exit();
    }

    // Query to retrieve the user from the database
    $query = "SELECT id, username, password FROM users WHERE username = $1"; // 'username' column stores email
    $result = pg_query_params($conn, $query, array($email)); // Use email to query

    if (!$result) {
        echo "<script>alert('An error occurred while querying the database.'); window.location.href='index.php?page=login';</script>";
        exit();
    }

    // Fetch the user data
    $user = pg_fetch_assoc($result);

    if ($user) {
        // Compare the input password with the password stored in the database using password_verify
        if (password_verify($password, $user['password'])) {
            // Successful login
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['username']; // Store username (email) in session

            // Set a cookie for the user (for example, for 30 days)
            setcookie("user_login", $user['username'], time() + (30 * 24 * 60 * 60), "/"); // 30 days expiration

            header("Location: dashboard.php");
            exit();
        } else {
            // Login failed
            echo "<script>alert('Invalid email or password.'); window.location.href='index.php?page=login';</script>";
            exit();
        }
    } else {
        // User not found
        echo "<script>alert('Invalid email or password.'); window.location.href='index.php?page=login';</script>";
        exit();
    }
}

// Close the database connection
pg_close($conn);
?>
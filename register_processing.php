<?php
// register_processing.php

include("connection.php"); // Include your database connection file

// Check if form data is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form data
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare the SQL statement to include user_level
    $query = "INSERT INTO users (username, password, user_level) VALUES ($1, $2, $3)";
    $result = pg_prepare($conn, "register_user", $query);
    $result = pg_execute($conn, "register_user", array($email, $hashed_password, 1)); // Set user_level to 1

    // Check if the execution was successful
    if ($result) {
        echo "Registration successful!";
    } else {
        echo "Error during registration: " . pg_last_error($conn);
    }
} else {
    echo "Invalid request method.";
}
?>
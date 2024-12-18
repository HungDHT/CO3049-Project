<?php
// Database connection parameters
$host = "localhost"; // Database host
$port = "5432";      // Database port (default for PostgreSQL)
$dbname = "Web_Programming"; // Database name
$user = "postgres"; // Database username
$password = "101216"; // Database password

// Establish a connection to the PostgreSQL database
$conn_string = "host=$host port=$port dbname=$dbname user=$user password=$password";
$conn = pg_connect($conn_string);

if (!$conn) {
    die("Connection failed: " . pg_last_error());
}

// SQL query to create the 'users' table
$createUsersTable = "
CREATE TABLE IF NOT EXISTS users (
    id SERIAL PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    user_level INT NOT NULL
);
";

// SQL query to create the 'products' table
$createProductsTable = "
CREATE TABLE IF NOT EXISTS products (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    price NUMERIC(10, 2) NOT NULL
);
";

// Execute the query to create the 'users' table
$result = pg_query($conn, $createUsersTable);
if (!$result) {
    echo "An error occurred while creating the users table: " . pg_last_error();
}

// Execute the query to create the 'products' table
$result = pg_query($conn, $createProductsTable);
if (!$result) {
    echo "An error occurred while creating the products table: " . pg_last_error();
}

$insertUsers = "
INSERT INTO users (username, password, user_level) VALUES
('Mr.A', 'passwordA', 1),
('Mr.B', 'passwordB', 1),
('Mr.C', 'passwordC', 1);
";

$result = pg_query($conn, $insertUsers);
if (!$result) {
    echo "An error occurred while inserting users: " . pg_last_error();
}

// Update Mr.A's user level by 1
$updateUserLevel = "
UPDATE users SET user_level = user_level + 1 WHERE username = 'Mr.A';
";

$result = pg_query($conn, $updateUserLevel);
if (!$result) {
    echo "An error occurred while updating Mr.A's user level: " . pg_last_error();
}

// Delete Mr.C from the users table
$deleteUserC = "
DELETE FROM users WHERE username = 'Mr.C';
";

$result = pg_query($conn, $deleteUserC);
if (!$result) {
    echo "An error occurred while deleting Mr.C: " . pg_last_error();
}

// Close the database connection
pg_close($conn);
?>
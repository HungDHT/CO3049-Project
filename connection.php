<?php
$host = "localhost";
$port = "5432";
$dbname = "Web_Programming";
$user = "postgres";
$password = "101216";

// Establish a connection to the PostgreSQL database
$conn_string = "host=$host port=$port dbname=$dbname user=$user password=$password";
$conn = pg_connect($conn_string);

if (!$conn) {
    die("Connection failed: " . pg_last_error());
}
?>
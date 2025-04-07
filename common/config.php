<?php
//creditials for database connection
$host = "localhost";
$user = "root";
$password = "";
$database = "library_db";

// Create connection
$conn = mysqli_connect($host, $user, $password, $database);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

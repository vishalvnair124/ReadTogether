<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['useremail']) || !isset($_SESSION['user_id']) || !isset($_SESSION['user_name'])) {
    // Redirect to login page if session not set
    header("Location: ./login.php"); // Adjust path if needed
    exit;
}

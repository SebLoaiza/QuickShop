<?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "QuickShop_data";

    // First, connect to MySQL without selecting a database
    $conn = new mysqli($servername, $username, $password);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Create the database if it doesn't exist
    $sql = "CREATE DATABASE IF NOT EXISTS $dbname";
    if ($conn->query($sql) !== TRUE) {
        die("Error creating database: " . $conn->error);
    }

    // Select the database
    $conn->select_db($dbname);

    // Create the shopping_list table if it doesn't exist
    $sql = "CREATE TABLE IF NOT EXISTS shopping_list (
        shopping_id INT AUTO_INCREMENT PRIMARY KEY,
        price FLOAT NOT NULL,
        image_url VARCHAR(255) NOT NULL,
        name VARCHAR(255) NOT NULL,
        URL VARCHAR(255) NOT NULL,
        store VARCHAR(255) NOT NULL
    )";
    if ($conn->query($sql) !== TRUE) {
        die("Error creating table: " . $conn->error);
    }
    
?>

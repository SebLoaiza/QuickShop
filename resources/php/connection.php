<?php
    /*
        connection.php
        Establishes a connection to the MySQL database for QuickShop.

        Process:
        - Connects to the MySQL server using configured host, username, and password
        - Creates the QuickShop_data database if it does not already exist
        - Selects the QuickShop_data database for use
        - Creates the shopping_list table if it does not already

        Used by:
        - All PHP scripts that require database access
    */

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

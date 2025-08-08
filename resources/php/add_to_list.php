<?php
    /*
        addToList.php
        Handles adding a selected product to the shopping_list table in the QuickShop database.

        Process:
        - Receives product details via POST request (URL, name, image, price, store)
        - Inserts the product into the database
        - Returns a success or error message for display in the UI

        Called by:
        - AJAX requests from the search results page when the "Add To Cart" button is clicked
    */

    include('./connection.php');

    if (isset($_POST['url'], $_POST['name'], $_POST['image'], $_POST['price'], $_POST['store'])) {
        $productURL = htmlspecialchars($_POST['url']);
        $productName = htmlspecialchars($_POST['name']);
        $productPrice = (float) $_POST['price'];
        $productStore = htmlspecialchars($_POST['store']);
        $productImage = htmlspecialchars($_POST['image']);

        $sql = "INSERT INTO shopping_list (price, image_url, name, URL, store) VALUES (?, ?, ?, ?, ?)";

        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("dssss", $productPrice, $productImage, $productName, $productURL, $productStore);

            if ($stmt->execute()) {
                echo "Product added to cart successfully!";
            } else {
                echo "Error: " . $stmt->error;
            }

            $stmt->close();
        } else {
            echo "Error: " . $conn->error;
        }

        $conn->close();
    } else {
        if (!isset($_POST['url'])) echo "Error: Missing product details (url).<br>";
        if (!isset($_POST['name'])) echo "Error: Missing product details (name).<br>";
        if (!isset($_POST['price'])) echo "Error: Missing product details (price).<br>";
        if (!isset($_POST['store'])) echo "Error: Missing product details (store).<br>";
        if (!isset($_POST['image'])) echo "Error: Missing product details (image).<br>";
    }
?>

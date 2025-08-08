<?php
    /*
        remove_item.php
        Removes a specific product from the shopping_list table in the QuickShop database.

        Process:
        - Accepts POST parameter:
            id -> the shopping_id of the item to delete
        - Prepares and executes a DELETE query for the given ID
        - Returns success on completion or an error message if the operation fails

        Called by:
        - AJAX requests from the cart page when a user deletes an item
    */

    include ('./connection.php');

    if (isset($_POST['id'])) {
        $item_id = (int) $_POST['id'];

        $sql = "DELETE FROM shopping_list WHERE shopping_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $item_id);

        if ($stmt->execute()) {
            echo 'success';
        } else {
            echo 'error: ' . $stmt->error;
        }

        $stmt->close();
    } else {
        echo 'error: Missing item_id';
    }

    $conn->close();


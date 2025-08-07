<?php
    include('./connection.php');

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
?>

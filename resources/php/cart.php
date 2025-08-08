<?php
/*
    cart.php
    Renders the shopping cart page.

    Features:
    - Retrieves cart items from the database
    - Calculates the total cart value
    - Displays item details including image, store, price, and product link
    - Handles empty cart messaging
    - Integrates with cart.css for styling and cart.js for interactivity
*/

require_once './connection.php';

// Fetch cart items from database
$sql = "SELECT shopping_id, name, store, price, image_url, URL 
        FROM shopping_list";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();

$emptyCart = $result->num_rows === 0;
$cartItems = [];
$totalPrice = 0;

// Build array of cart items and calculate total
if (!$emptyCart) {
    while ($row = $result->fetch_assoc()) {
        $cartItems[] = $row;
        $totalPrice += (float) $row['price'];
    }
}

$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart - QuickShop</title>
    <link rel="stylesheet" href="../css/cart.css">
</head>
<body>
    <div class="container">
        <a href="../../index.html" class="back-btn">← Home</a>
        <h1>Shopping Cart</h1>

        <?php if ($emptyCart): ?>
            <!-- Empty cart message -->
            <div class="empty-cart">
                <h2>Your cart is empty</h2>
                <p>Add some items to your cart to see them here!</p>
            </div>
        <?php else: ?>
            <?php foreach ($cartItems as $item): ?>
                <div class="cart-item" data-id="<?= (int) $item['shopping_id'] ?>">
                    <img src="<?= htmlspecialchars($item['image_url']) ?>" 
                         alt="<?= htmlspecialchars($item['name']) ?>"
                         onerror="this.src='https://via.placeholder.com/80x80?text=No+Image'">

                    <div class="item-details">
                        <a class="item-name" href="<?= htmlspecialchars($item['URL']) ?>" target="_blank">
                            <?= htmlspecialchars($item['name']) ?>
                        </a>
                        <div class="item-store">Store: <?= htmlspecialchars($item['store']) ?></div>
                        <div class="item-price">$<?= number_format($item['price'], 2) ?></div>
                    </div>

                    <button class="remove-btn">Remove</button>
                </div>
            <?php endforeach; ?>

            <div class="total-section">
                <div class="total-price">
                    Total: $<?= number_format($totalPrice, 2) ?>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../js/cart.js"></script>
</body>
</html>

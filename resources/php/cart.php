<?php
include('./connection.php');

// Fetch all cart items from the database 
$sql = "SELECT * FROM shopping_list";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    $emptyCart = true;
} else {
    $emptyCart = false;
    $cartItems = [];
    $totalPrice = 0;

    while ($row = $result->fetch_assoc()) {
        $cartItems[] = $row;
        $totalPrice += $row['price'];
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
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .cart-item {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .cart-item img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 4px;
            cursor: pointer;
            transition: opacity 0.2s;
        }
        .cart-item img:hover {
            opacity: 0.8;
        }
        .item-details {
            flex-grow: 1;
        }
        .item-name {
            font-weight: bold;
            margin-bottom: 5px;
            cursor: pointer;
            color: #2c5aa0;
            text-decoration: none;
        }
        .item-name:hover {
            text-decoration: underline;
        }
        .item-store {
            color: #666;
            margin-bottom: 5px;
        }
        .item-price {
            font-size: 18px;
            font-weight: bold;
            color: #2c5aa0;
        }
        .total-section {
            border-top: 2px solid #ddd;
            padding-top: 20px;
            margin-top: 20px;
            text-align: right;
        }
        .total-price {
            font-size: 24px;
            font-weight: bold;
            color: #2c5aa0;
        }
        .empty-cart {
            text-align: center;
            padding: 40px;
            color: #666;
        }
        .remove-btn {
            background-color: #dc3545;
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 4px;
            cursor: pointer;
        }
        .remove-btn:hover {
            background-color: #c82333;
        }
        .back-btn {
            background-color: #6c757d;
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 4px;
            display: inline-block;
            margin-bottom: 20px;
        }
        .back-btn:hover {
            background-color: #5a6268;
            text-decoration: none;
            color: white;
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="../../index.html" class="back-btn">← Home</a>
        <h1>Shopping Cart</h1>
        
        <?php if ($emptyCart): ?>
            <div class="empty-cart">
                <h2>Your cart is empty</h2>
                <p>Add some items to your cart to see them here!</p>
            </div>
        <?php else: ?>
            <?php foreach ($cartItems as $item): ?>
                <div class="cart-item">
                    <img src="<?php echo htmlspecialchars($item['image_url']); ?>" 
                         alt="<?php echo htmlspecialchars($item['name']); ?>"
                         onerror="this.src='https://via.placeholder.com/80x80?text=No+Image'"
                         onclick="window.open('<?php echo htmlspecialchars($item['URL']); ?>', '_blank')">
                    
                    <div class="item-details">
                        <div class="item-name" onclick="window.open('<?php echo htmlspecialchars($item['URL']); ?>', '_blank')">
                            <?php echo htmlspecialchars($item['name']); ?>
                        </div>
                        <div class="item-store">Store: <?php echo htmlspecialchars($item['store']); ?></div>
                        <div class="item-price">$<?php echo number_format($item['price'], 2); ?></div>
                    </div>
                    
                    <button class="remove-btn" onclick="removeItem(<?php echo $item['shopping_id']; ?>)">
                        Remove
                    </button>
                </div>
            <?php endforeach; ?>
            
            <div class="total-section">
                <div class="total-price">
                    Total: $<?php echo number_format($totalPrice, 2); ?>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function removeItem(itemId) {
            if (confirm('Are you sure you want to remove this item from your cart?')) {
                $.ajax({
                    url: 'remove_item.php',
                    type: 'POST',
                    data: { id: itemId },
                    success: function(response) {
                        location.reload(); // Refresh the page to show updated cart
                    },
                    error: function(xhr, status, error) {
                        alert('Error removing item from cart.');
                        console.error('AJAX error:', error);
                    }
                });
            }
        }
    </script>
</body>
</html>

/*
    cart.js
    Handles shopping cart page interactivity.

    Features:
    - Listens for clicks on "Remove" buttons
    - Confirms removal with the user
    - Sends an AJAX POST request to delete the item from the cart
    - Reloads the page to update cart contents
    - Alerts the user on failure
*/

$(document).ready(function () {
    $('.remove-btn').on('click', function () {
        const itemId = $(this).closest('.cart-item').data('id');

        if (confirm('Are you sure you want to remove this item from your cart?')) {
            $.ajax({
                url: 'remove_item.php',
                type: 'POST',
                data: { id: itemId },
                success: function () {
                    location.reload();
                },
                error: function (xhr, status, error) {
                    alert('Error removing item from cart.');
                    console.error('AJAX error:', error);
                }
            });
        }
    });
});

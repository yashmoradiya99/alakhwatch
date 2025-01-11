<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart</title>
    <link rel="stylesheet" href="good.css">
</head>
<body>

<div class="container">
    <h1>Your Cart</h1>

    <?php if (!empty($_SESSION['cart'])): ?>
        <table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($_SESSION['cart'] as $id => $product): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($product['name']); ?></td>
                        <td>$<?php echo htmlspecialchars($product['price']); ?></td>
                        <td><?php echo htmlspecialchars($product['quantity']); ?></td>
                        <td>$<?php echo $product['price'] * $product['quantity']; ?></td>
                        <td>
                            <form action="update_cart.php" method="POST" style="display:inline;">
                                <input type="hidden" name="product_id" value="<?php echo $id; ?>">
                                <button type="submit" name="action" value="remove">Remove</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

    <?php else: ?>
        <p>Your cart is empty.</p>
    <?php endif; ?>

    <!-- Back to Cart Button -->
    <a href="index.php" class="back-button">Back to Shopping</a>

</div>

</body>
</html>

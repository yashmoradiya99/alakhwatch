<?php
// Include database connection
include('db.php');



// Fetch products from the database
$sql = "SELECT * FROM products";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Watch Store</title>
    <link rel="stylesheet" href="good.css"> <!-- Ensure correct CSS path -->
</head>
<body>

<div class="container">
    <h1>Welcome to Alakh Watches</h1>
    <a href="cart.php" class="cart-link">View Cart</a>

    <div class="product-list">
        <?php while ($row = $result->fetch_assoc()): ?>
        <div class="product-box">
            
            <img src="../Images/<?php echo htmlspecialchars($row['image']); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>" class="product-image">
            <h3 class="product-title"><?php echo htmlspecialchars($row['name']); ?></h3>
            <p class="product-price">$<?php echo htmlspecialchars($row['price']); ?></p>
            <p class="product-description"><?php echo htmlspecialchars($row['description']); ?></p>
            
            <a 
                href="https://wa.me/91XXXXXXXXXX?text=I'm%20interested%20in%20the%20product:%20<?php echo urlencode($row['name']); ?>" 
                target="_blank" 
                class="whatsapp-link">
                Contact on WhatsApp
            </a>
            <form action="add_to_cart.php" method="POST">
                <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($row['id']); ?>">
                <button type="submit" class="add-to-cart">Add to Cart</button>
            </form>
        </div>
        <?php endwhile; ?>
    </div>
</div>

</body>
</html>

<?php $conn->close(); ?>

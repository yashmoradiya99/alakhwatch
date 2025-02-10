<?php
include './database/db.php'; 

// Fetch products from the database
$sql = "SELECT * FROM products";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Watch Store</title>
    <link rel="stylesheet" href="./css/styles.css">
</head>
<body>
    <header>
        <h1>Welcome Alakh Watches</h1>
        <a href="add_product.php">Add New Product</a>

    </header>

    <div class="container">
    <?php if ($result->num_rows > 0) { ?>
        <?php while ($row = $result->fetch_assoc()) { ?>
            <div class="product">
                <img src="./images/<?php echo $row['image']; ?>" alt="<?php echo $row['name']; ?>">
                <h3><?php echo $row['name']; ?></h3>
                <p class="price">₹ <?php echo $row['price']; ?></p>
                <p><?php echo $row['description']; ?></p>
                <a 
                    href="https://wa.me/91XXXXXXXXXX?text=I'm%20interested%20in%20the%20product:%20<?php echo urlencode($row['name']); ?>" 
                    target="_blank" 
                    class="whatsapp-link">
                    Contact on WhatsApp
                </a>
                <div class="action-buttons">
                <a href="update_product.php?id=<?php echo $row['id']; ?>" class="update-btn">Update</a>

                    <a href="delete_product.php?id=<?php echo $row['id']; ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this product?');">Delete</a>
                </div>
            </div>
        <?php } ?>
    <?php } else { ?>
        <p>No products available.</p>
    <?php } ?>
</div>




</body>
</html>

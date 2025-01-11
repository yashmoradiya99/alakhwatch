<?php
include './database/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $price = trim($_POST['price']);
    $description = trim($_POST['description']);
    $image = $_FILES['image'];

    if (empty($name) || !is_numeric($price) || $price <= 0 || empty($description)) {
        $error = "All fields are required and price must be a positive number.";
    } else {
        $imageName = basename($image['name']);
        $targetFile = './images/' . $imageName;

        if (move_uploaded_file($image['tmp_name'], $targetFile)) {
            $sql = "INSERT INTO products (name, price, description, image) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('sdss', $name, $price, $description, $imageName);
            $stmt->execute();
            $success = "Product added successfully!";
        } else {
            $error = "Failed to upload image.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <link rel="stylesheet" href="./css/form_styles.css">
</head>
<body>
<div class="container">
    <h2>Add Product</h2>
    <?php if (isset($success)) echo "<div class='success-message'>$success</div>"; ?>
    <?php if (isset($error)) echo "<div class='error-message'>$error</div>"; ?>
    <form action="add_product.php" method="POST" enctype="multipart/form-data">
        <label for="name">Product Name:</label>
        <input type="text" id="name" name="name" required>

        <label for="price">Price:</label>
        <input type="number" id="price" name="price" required step="0.01" min="0.01">

        <label for="description">Description:</label>
        <textarea id="description" name="description" required></textarea>

        <label for="image">Product Image:</label>
        <input type="file" id="image" name="image" required>

        <button type="submit">Add Product</button>
        <!-- Back Button -->
        <button type="button" onclick="window.location.href='index.php';" class="back-btn">Back to Home</button>
        
    </form>
</div>
</body>
</html>

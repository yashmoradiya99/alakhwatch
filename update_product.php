<?php
include './database/db.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Product ID is missing or invalid.");
}



$productId = intval($_GET['id']);
$sql = "SELECT * FROM products WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $productId);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

if (!$product) {
    die("Product not found.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $price = trim($_POST['price']);
    $description = trim($_POST['description']);
    $image = $_FILES['image'];

    if (empty($name) || !is_numeric($price) || $price <= 0 || empty($description)) {
        $error = "All fields are required and price must be a positive number.";
    } else {
        $imageName = $product['image'];
        if ($image['error'] === UPLOAD_ERR_OK) {
            $oldImage = './images/' . $imageName;
            if (file_exists($oldImage)) unlink($oldImage);

            $imageName = basename($image['name']);
            move_uploaded_file($image['tmp_name'], './images/' . $imageName);
        }

        $sql = "UPDATE products SET name = ?, price = ?, description = ?, image = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sdssi', $name, $price, $description, $imageName, $productId);
        $stmt->execute();
        $success = "Product updated successfully!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Product</title>
    <link rel="stylesheet" href="./css/form_styles.css">
</head>
<body>
<div class="container">
    <h2>Update Product</h2>
    <?php if (isset($success)) echo "<div class='success-message'>$success</div>"; ?>
    <?php if (isset($error)) echo "<div class='error-message'>$error</div>"; ?>
    <form action="update_product.php?id=<?php echo $product['id']; ?>" method="POST" enctype="multipart/form-data">
        <label for="name">Product Name:</label>
        <input type="text" id="name" name="name" value="<?php echo $product['name']; ?>" required>

        <label for="price">Price:</label>
        <input type="number" id="price" name="price" value="<?php echo $product['price']; ?>" required step="0.01" min="0.01">

        <label for="description">Description:</label>
        <textarea id="description" name="description" required><?php echo $product['description']; ?></textarea>

        <label for="image">Product Image (Leave empty if no change):</label>
        <input type="file" id="image" name="image">
        <p>Current Image:</p>
        <img src="./images/<?php echo $product['image']; ?>" alt="Current Image" width="200">

        <button type="submit">Update Product</button>
        <button type="button" onclick="window.location.href='index.php';" class="back-btn">Back to Home</button>
        
    </form>
</div>
</body>
</html>

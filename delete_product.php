<?php
include './database/db.php'; // Database connection

if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Get product ID and sanitize
    $sql = "DELETE FROM products WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        echo "Product deleted successfully.";
        header('Location: index.php'); // Redirect back to the product list
        exit;
    } else {
        echo "Error deleting product: " . $conn->error;
    }
} else {
    echo "Invalid request.";
}
?>

<?php
include './database/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['croppedImage']) && isset($_POST['id'])) {
    $productId = intval($_POST['id']);

    // Define upload directory
    $uploadDir = "./images/";
    $newFileName = "product_" . time() . ".jpg";
    $uploadPath = $uploadDir . $newFileName;

    // Save the uploaded cropped image
    if (move_uploaded_file($_FILES['croppedImage']['tmp_name'], $uploadPath)) {
        // Update image path in database
        $sql = "UPDATE products SET image = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $newFileName, $productId);
        if ($stmt->execute()) {
            echo json_encode(["success" => true, "image" => $newFileName]);
        } else {
            echo json_encode(["success" => false, "error" => "Database update failed"]);
        }
    } else {
        echo json_encode(["success" => false, "error" => "Failed to save image"]);
    }
} else {
    echo json_encode(["success" => false, "error" => "Invalid request"]);
}
?>

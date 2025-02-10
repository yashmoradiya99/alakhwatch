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

    if (empty($name) || !is_numeric($price) || $price <= 0 || empty($description)) {
        $error = "All fields are required and price must be a positive number.";
    } else {
        $sql = "UPDATE products SET name = ?, price = ?, description = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sdsi', $name, $price, $description, $productId);
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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet">
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

        <p>Current Image:</p>
        <img id="currentImage" src="./images/<?php echo $product['image']; ?>" alt="Current Image" width="200">

        <label for="image">Product Image:</label>
    <div class="image-wrapper">
    <button type="button" id="adjustImageBtn" class="adjust-btn">✂️ Adjust</button>
    </div>

        <button type="submit" >Update</button>
        <button type="button" onclick="window.location.href='index.php';" class="back-btn">Back to Home</button>
        
    </form>
</div>

<!-- Image Modal -->
<div id="imageModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h3>Adjust Image</h3>
        <div class="image-container">
            <img id="cropImage" src="./images/<?php echo $product['image']; ?>" alt="Adjust Image">
        </div>
        <button id="saveCroppedImage">Submit</button>
    </div>
</div>

<style>
.modal {
    display: none;
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background: white;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0,0,0,0.3);
    z-index: 1000;
}
.modal-content {
    text-align: center;
    width: 300px;
}
.close {
    position: absolute;
    top: 10px;
    right: 15px;
    font-size: 20px;
    cursor: pointer;
}
.image-container {
    width: 250px;
    height: 250px;
    overflow: hidden;
}
</style>

<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    let cropper;
    const modal = document.getElementById("imageModal");
    const image = document.getElementById("cropImage");
    const btnOpen = document.getElementById("adjustImageBtn");
    const btnClose = document.querySelector(".close");
    const btnSave = document.getElementById("saveCroppedImage");
    const currentImage = document.getElementById("currentImage");

    // Open modal and initialize cropper
    btnOpen.addEventListener("click", function () {
        modal.style.display = "block";
        if (!cropper) {
            cropper = new Cropper(image, {
                aspectRatio: 1,
                viewMode: 2,
                movable: true,
                zoomable: true,
                scalable: true,
                cropBoxMovable: true,
                cropBoxResizable: true
            });
        }
    });

    // Close modal
    btnClose.addEventListener("click", function () {
        modal.style.display = "none";
        if (cropper) {
            cropper.destroy();
            cropper = null;
        }
    });

    // Save cropped image
    btnSave.addEventListener("click", function () {
        const canvas = cropper.getCroppedCanvas();
        if (canvas) {
            canvas.toBlob(function (blob) {
                const formData = new FormData();
                formData.append("croppedImage", blob, "cropped.jpg");
                formData.append("id", <?php echo $productId; ?>); // Pass the product ID

                fetch("save_cropped_image.php", {
                    method: "POST",
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert("Image updated successfully!");
                        currentImage.src = "./images/" + data.image; // Update image preview
                        modal.style.display = "none"; // Close modal
                    } else {
                        alert("Error: " + data.error);
                    }
                })
                .catch(error => console.error("Error:", error));
            }, "image/jpeg");
        }
    });
});

</script>

</body>
</html>

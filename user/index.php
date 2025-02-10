<?php
// Include database connection
include('db.php');

// Fetch all products from the database
$sql = "SELECT * FROM products";
$result = $conn->query($sql);
$products = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Alakh Watches</title>
    <link rel="stylesheet" href="good.css"> <!-- Ensure correct CSS path -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.3/html2pdf.bundle.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
            color: #333;
        }

        .container {
            width: 90%;
            max-width: 1400px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        h1 {
            color: #444;
            margin-bottom: 20px;
        }

        .pdf-container {
            width: 100%;
            
        }

        .pdf-page {
            display: grid;
            grid-template-columns: repeat(2, 1fr); /* Two columns */
            gap: 30px;
            justify-content: center;
            align-items: center;
            padding: 20px;
            page-break-after: always;
        }

        .product-box {
            background-color: #fafafa;
            border: 2px solid #ddd;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            transition: transform 0.2s, box-shadow 0.2s;
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);
            width: 300px;
            height: 400px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            
        }

        .product-box:hover {
            transform: scale(1.05);
            box-shadow: 4px 4px 12px rgba(0, 0, 0, 0.2);
        }

        .product-image {
            width: 250px;
            height: 250px;
            object-fit: cover;
            border-radius: 10px;
            margin-bottom: 15px;
        }

        .product-title {
            font-size: 20px;
            font-weight: bold;
            color: #333;
            margin-bottom: 8px;
        }

        .product-price {
            font-size: 18px;
            color: #28a745;
            margin-bottom: 8px;
        }

        .product-description {
            font-size: 16px;
            color: #555;
        }
        
        #downloadPDF {
            background-color: #ff5733;
            color: white;
            padding: 12px 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: 0.3s;
            font-size: 16px;
            margin-bottom: 20px;
            border: none;
        }

        #downloadPDF:hover {
            background-color: #c70039;
        }

        @media (max-width: 768px) {
            .pdf-page {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Welcome to Alakh Watches</h1>
    <button id="downloadPDF">Download Products as PDF</button>

    <div id="contentToDownload" class="pdf-container">
        <?php 
        for ($i = 0; $i < count($products); $i += 4): 
        ?>
            <div class="pdf-page"> 
                <?php for ($j = $i; $j < $i + 4 && $j < count($products); $j++): ?>
                    <div class="product-box">
                        <img src="http://localhost/watch/Images/<?php echo htmlspecialchars($products[$j]['image']); ?>" 
                             alt="<?php echo htmlspecialchars($products[$j]['name']); ?>" class="product-image">
                        <h3 class="product-title"><?php echo htmlspecialchars($products[$j]['name']); ?></h3>
                        <p class="product-price">₹ <?php echo htmlspecialchars($products[$j]['price']); ?></p>
                        <p class="product-description"><?php echo htmlspecialchars($products[$j]['description']); ?></p>
                    </div>
                <?php endfor; ?>
            </div>
        <?php endfor; ?>
    </div>
</div>

<script>
    document.getElementById("downloadPDF").addEventListener("click", function () {
        const content = document.getElementById("contentToDownload");
        const options = {
            margin: 1,
            filename: 'products.pdf',
            image: { type: 'jpeg', quality: 0.98 },
            html2canvas: { scale: 2 },
            jsPDF: { unit: 'cm', format: 'a4', orientation: 'portrait' },
            pagebreak: { mode: ['avoid-all', 'css', 'legacy'] }
        };
        html2pdf().from(content).set(options).save();
    });
</script>

</body>
</html>

<?php $conn->close(); ?>

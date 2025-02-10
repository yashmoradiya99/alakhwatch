<?php
require_once('tcpdf/tcpdf.php'); // Include TCPDF Library
include('db.php'); // Include database connection

// Fetch all products from the database
$sql = "SELECT * FROM products";
$result = $conn->query($sql);

// Create new PDF instance
$pdf = new TCPDF();
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Alakh Watches');
$pdf->SetTitle('Product Catalog');
$pdf->SetMargins(10, 10, 10);
$pdf->SetAutoPageBreak(TRUE, 15);
$pdf->AddPage();

// Set default font
$pdf->SetFont('helvetica', '', 10);

// Counter for product layout (4 per page)
$count = 0;
$total = $result->num_rows;

$html = '<h1 style="text-align:center;">Alakh Watches - Product Catalog</h1>';

while ($row = $result->fetch_assoc()) {
    if ($count % 4 == 0) { // Start a new table for every 4 products
        if ($count > 0) { 
            $html .= '</table>'; // Close previous table
            $pdf->writeHTML($html, true, false, true, false, '');
            $pdf->AddPage(); // Add a new page
        }
        $html = '<table border="1" cellpadding="5" cellspacing="0" width="100%">
                    <tr>
                        <th width="30%">Image</th>
                        <th width="20%">Name</th>
                        <th width="20%">Price</th>
                        <th width="30%">Description</th>
                    </tr>';
    }

    $imagePath = 'http://localhost/watch/Images/' . $row['image']; // Set the image path

    // Create square image (150x150 px)
    $imageHTML = '<img src="' . $imagePath . '" width="150" height="150" style="object-fit: cover; display: block; margin: auto;">';

    $html .= '<tr>
                <td style="text-align:center;">' . $imageHTML . '</td>
                <td>' . htmlspecialchars($row['name']) . '</td>
                <td>$' . htmlspecialchars($row['price']) . '</td>
                <td>' . htmlspecialchars($row['description']) . '</td>
              </tr>';

    $count++;
}

// Close the table
$html .= '</table>';

// Write HTML content to PDF
$pdf->writeHTML($html, true, false, true, false, '');

// Output the PDF
$pdf->Output('products.pdf', 'D'); // Forces download

$conn->close();
?>

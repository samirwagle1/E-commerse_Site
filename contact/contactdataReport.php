<?php
require('../fpdf/fpdf.php'); // Include the FPDF library

// Function to establish a database connection
function connectToDatabase() {
    $db_host = 'localhost';
    $db_username = 'root';
    $db_password = '';
    $db_name = 'db_grocery';

    $conn = mysqli_connect($db_host, $db_username, $db_password, $db_name);

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    return $conn;
}

// Create a new PDF document
$pdf = new FPDF('L');
$pdf->AddPage();

// Set font
$pdf->SetFont('Arial', 'B', 12);

// Add a title to the PDF
$pdf->Cell(0, 10, 'Contact Message Report', 0, 1, 'C');

// Establish a database connection
$conn = connectToDatabase();

// Fetch contact data from the database
$select = mysqli_query($conn, "SELECT * FROM contact_tbl") or die('Query failed');

if (mysqli_num_rows($select) > 0) {
    // Calculate the X-coordinate to center-align the table
    $pageWidth = $pdf->GetPageWidth();
    $tableWidth = 250; // Adjust the width as needed
    $tableX = ($pageWidth - $tableWidth) / 2;
    $pdf->SetX($tableX);
    // Create a table header
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(20, 20, 'ID', 1);
    $pdf->Cell(40, 20, 'Name', 1);
    $pdf->Cell(60, 20, 'Email', 1);
    $pdf->Cell(30, 20, 'Phone', 1);
    $pdf->Cell(40, 20, 'Subject', 1);
    $pdf->Cell(60, 20, 'Message', 1);
    $pdf->Ln(); // Move to the next row

    // Loop through the contact data and add it to the table
    while ($row = mysqli_fetch_assoc($select)) {
        $pdf->SetX($tableX);
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(20, 20, $row['contact_id'], 1);
        $pdf->Cell(40, 20, $row['name'], 1);
        $pdf->Cell(60, 20, $row['email'], 1);
        $pdf->Cell(30, 20, $row['number'], 1);
        $pdf->Cell(40, 20, $row['subject'], 1);
        $pdf->Cell(60, 20, $row['message'], 1);
        $pdf->Ln(); // Move to the next row
    }
}
else {
    $pdf->Cell(0, 10, 'No products found.', 1, 1, 'C');
}
    // Output the PDF to the browser or save it to a file
    $pdf->Output('contact_message_report.pdf', 'D'); // 'D' for download

    // Close the database connection
    mysqli_close($conn);
    ?>
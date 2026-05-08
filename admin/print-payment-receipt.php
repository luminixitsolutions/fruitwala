<?php
include_once 'config.php';

$id = $_GET['id']; // payment id
$sql = "SELECT * FROM tbl_general_ledger WHERE id='$id'";
$res = $conn->query($sql);
$row = $res->fetch_assoc();

$CompanyName = "Fruit Wala Breakfast";
$CompanyAddress = "Chaya complex near DTDC courier anmol nagar wathoda nagpur 440024";
$CompanyPhone = "+91 8812925014";
$CompanyEmail = "fruitwalabreakfast@gmail.com";
$CompanyWebsite = "www.fruitwalabreakfast.com";
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Payment Receipt - <?php echo $CompanyName; ?></title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
<style>
body {
    font-family: 'Poppins', sans-serif;
    background: #f5f7fa;
    margin: 0;
    padding: 20px;
    color: #333;
}
.receipt-container {
    max-width: 800px;
    margin: auto;
    background: #fff;
    border-radius: 15px;
    box-shadow: 0 0 15px rgba(0,0,0,0.1);
    padding: 40px;
}
.header {
    text-align: center;
    border-bottom: 2px solid #ffd369;
    padding-bottom: 15px;
}
.header img {
    width: 90px;
    margin-bottom: 10px;
}
.header h2 {
    color: #333;
    margin: 0;
    font-size: 26px;
}
.header p {
    color: #666;
    font-size: 14px;
    margin: 3px 0;
}
.title {
    text-align: center;
    font-weight: 600;
    margin-top: 25px;
    font-size: 20px;
    color: #222;
    letter-spacing: 1px;
}
.details-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 25px;
}
.details-table td {
    padding: 10px 8px;
    font-size: 15px;
}
.details-table tr:nth-child(even) {
    background: #f9f9f9;
}
.details-table strong {
    color: #222;
}
.amount-box {
    margin-top: 25px;
    border: 2px solid #ffd369;
    border-radius: 10px;
    text-align: center;
    padding: 15px;
    background: #fff8e1;
}
.amount-box h3 {
    margin: 0;
    color: #222;
}
.footer {
    text-align: center;
    margin-top: 30px;
    border-top: 2px solid #ffd369;
    padding-top: 15px;
    font-size: 14px;
    color: #555;
}
.print-btn {
    display: block;
    width: 200px;
    margin: 25px auto;
    background: #ffb703;
    color: #fff;
    border: none;
    padding: 10px 0;
    border-radius: 25px;
    cursor: pointer;
    font-size: 16px;
    transition: all 0.3s;
}
.print-btn:hover {
    background: #fb8500;
}

/* ✅ PRINT SETTINGS */
@media print {
    body {
        -webkit-print-color-adjust: exact !important;
        color-adjust: exact !important;
        print-color-adjust: exact !important;
        background: #fff !important;
    }

    .print-btn {
        display: none !important;
    }

    @page {
        margin: 0;
        size: auto; /* Remove browser headers/footers */
    }

    html, body {
        margin: 0 !important;
        padding: 0 !important;
        width: 100%;
        height: 100%;
    }

    .receipt-container {
        box-shadow: none !important;
        border: none !important;
        margin: 0 auto !important;
        width: 95% !important;
    }
}
</style>
</head>
<body>

<div class="receipt-container">

    <div class="header">
        <img src="logo.png" alt="Logo">
        <h2><?php echo $CompanyName; ?></h2>
        <p><?php echo $CompanyAddress; ?></p>
        <p>📞 <?php echo $CompanyPhone; ?> | ✉️ <?php echo $CompanyEmail; ?></p>
        <p><a href="<?php echo $CompanyWebsite; ?>" style="color:#fb8500;text-decoration:none;"><?php echo $CompanyWebsite; ?></a></p>
    </div>

    <div class="title">Payment Receipt</div>

    <table class="details-table">
        <tr>
            <td><strong>Receipt No:</strong></td>
            <td><?php echo $row['Code']; ?></td>
            <td><strong>Date:</strong></td>
            <td><?php echo date("d/m/Y", strtotime($row['PaymentDate'])); ?></td>
        </tr>
        <tr>
            <td><strong>Customer Name:</strong></td>
            <td><?php echo $row['AccountName']; ?></td>
            <td><strong>Payment Mode:</strong></td>
            <td><?php echo $row['PayMode']; ?></td>
        </tr>
        <tr>
            <td><strong>Reference / Remarks:</strong></td>
            <td colspan="3"><?php echo $row['Narration'] ?: 'N/A'; ?></td>
        </tr>
    </table>

    <div class="amount-box">
        <h3>Received Amount: ₹<?php echo number_format($row['Amount'],2); ?></h3>
    </div>

    <div class="footer">
        <p>Thank you for your payment!</p>
        <p>For any queries, contact us at <a href="mailto:<?php echo $CompanyEmail; ?>" style="color:#fb8500;"><?php echo $CompanyEmail; ?></a></p>
        <p><strong><?php echo $CompanyName; ?></strong> – Freshness You Deserve 🍎</p>
    </div>

</div>

<button class="print-btn" onclick="window.print()">🖨 Print Receipt</button>

</body>
</html>

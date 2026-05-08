<?php
session_start();
require_once 'config.php';

header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$response = [];

// Validate request
if (empty($_POST['id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid order ID']);
    exit;
}

$orderId = intval($_POST['id']);
$UpdatedBy = $_SESSION['User']['id'] ?? 0;
$today = date('Y-m-d');

// Check if already delivered today
$checkQuery = "SELECT COUNT(*) as cnt FROM tbl_order_status_log 
               WHERE UserId='$orderId' AND Status='Delivered' AND DATE(CreatedDate)='$today'";
$result = $conn->query($checkQuery);
$row = $result->fetch_assoc();
$alreadyDelivered = $row['cnt'] ?? 0;

if ($alreadyDelivered > 0) {
    echo json_encode([
        'status' => 'info',
        'message' => 'This order is already marked as delivered today.'
    ]);
    exit;
}

// Insert log entry
$insertQuery = "INSERT INTO tbl_order_status_log (UserId, Status, UpdatedBy, Remarks) 
                VALUES ('$orderId', 'Delivered', '$UpdatedBy', 'Order marked as delivered')";

if ($conn->query($insertQuery)) {
    $response = [
        'status' => 'success',
        'message' => 'Order marked as delivered successfully.'
    ];
} else {
    $response = [
        'status' => 'error',
        'message' => 'Failed to insert delivery record. Please try again.'
    ];
}

echo json_encode($response);
?>

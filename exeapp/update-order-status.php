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

// Receive modal form fields
$deliveredDate = $_POST['deliveredDate'] ?? $today;
$boxQty = isset($_POST['boxQty']) ? intval($_POST['boxQty']) : 1;
$remark = trim($_POST['remark'] ?? '');

// ✅ Step 1: Prevent multiple delivery entries for same day
$checkQuery = "
    SELECT COUNT(*) as cnt 
    FROM tbl_order_status_log 
    WHERE UserId='$orderId' 
      AND Status='Delivered' 
      AND DATE(CreatedDate)='$today'
";
$result = $conn->query($checkQuery);
$row = $result ? $result->fetch_assoc() : ['cnt' => 0];
$alreadyDelivered = $row['cnt'] ?? 0;

if ($alreadyDelivered > 0) {
    echo json_encode([
        'status' => 'info',
        'message' => 'This order is already marked as delivered today.'
    ]);
    exit;
}

// ✅ Step 2: Insert delivery log
$insertQuery = "
    INSERT INTO tbl_order_status_log 
        (UserId, Status, DeliveredDate, BoxQty, Remark, UpdatedBy, CreatedDate)
    VALUES 
        ('$orderId', 'Delivered', '$deliveredDate', '$boxQty', '$remark', '$UpdatedBy', NOW())
";

if ($conn->query($insertQuery)) {
    // Record only THIS delivery date in tbl_order_delivery_boxes (one green box per delivery)
    ensureBoxesTableExists($conn);
    $deliveryDateOnly = date('Y-m-d', strtotime($deliveredDate));
    $insertBox = $conn->prepare("INSERT INTO tbl_order_delivery_boxes (order_id, delivery_date, status) VALUES (?, ?, 'delivered') ON DUPLICATE KEY UPDATE status = 'delivered'");
    $insertBox->bind_param("is", $orderId, $deliveryDateOnly);
    $insertBox->execute();
    $insertBox->close();

    $response = [
        'status' => 'success',
        'message' => 'Order marked as delivered successfully.'
    ];
} else {
    $response = [
        'status' => 'error',
        'message' => 'Database error: ' . $conn->error
    ];
}

echo json_encode($response);

function ensureBoxesTableExists($conn) {
    $conn->query("CREATE TABLE IF NOT EXISTS tbl_order_delivery_boxes (
        id INT AUTO_INCREMENT PRIMARY KEY,
        order_id INT NOT NULL,
        delivery_date DATE NOT NULL,
        status ENUM('delivered','pending') DEFAULT 'pending',
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        UNIQUE KEY uq_order_delivery (order_id, delivery_date)
    )");
}
?>

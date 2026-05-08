<?php
session_start();
require_once 'config.php';

// Set content type to JSON
header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['User']['id'])) {
    echo json_encode([
        'success' => false,
        'message' => 'User not logged in'
    ]);
    exit;
}

$user_id = $_SESSION['User']['id'];
$response = ['success' => false, 'message' => ''];

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $action = $_POST['action'] ?? '';
        
        if ($action === 'punch_in') {
            $punch_date = $_POST['punch_date'] ?? date('Y-m-d');
            $reason = trim($_POST['reason'] ?? '');
            
            // Validate date format
            if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $punch_date)) {
                $response['message'] = 'Invalid date format';
                echo json_encode($response);
                exit;
            }
            
            // Check if date is not in the past (except today)
            $selected_date = new DateTime($punch_date);
            $today = new DateTime(date('Y-m-d'));
            
            if ($selected_date < $today) {
                $response['message'] = 'Cannot punch in for past dates';
                echo json_encode($response);
                exit;
            }
            
            // Check if already punched in for this date
            $check_sql = "SELECT id FROM tbl_week_off_punch WHERE user_id = ? AND punch_date = ? AND status = 'active'";
            $check_stmt = $conn->prepare($check_sql);
            $check_stmt->bind_param("is", $user_id, $punch_date);
            $check_stmt->execute();
            $check_result = $check_stmt->get_result();
            
            if ($check_result->num_rows > 0) {
                $response['message'] = 'You have already punched in for this date';
                echo json_encode($response);
                exit;
            }
            
            // Insert punch record
            $punch_time = date('H:i:s');
            $punch_datetime = date('Y-m-d H:i:s');
            
            $insert_sql = "INSERT INTO tbl_week_off_punch (user_id, punch_date, punch_time, punch_datetime, reason, status) VALUES (?, ?, ?, ?, ?, 'active')";
            $insert_stmt = $conn->prepare($insert_sql);
            $insert_stmt->bind_param("issss", $user_id, $punch_date, $punch_time, $punch_datetime, $reason);
            
            if ($insert_stmt->execute()) {
                $response['success'] = true;
                $response['message'] = 'Week off punch recorded successfully!';
                $response['data'] = [
                    'punch_date' => $punch_date,
                    'punch_time' => $punch_time,
                    'reason' => $reason
                ];
            } else {
                $response['message'] = 'Failed to record punch: ' . $conn->error;
            }
            
        } elseif ($action === 'cancel_punch') {
            $punch_id = $_POST['punch_id'] ?? 0;
            
            // Validate punch belongs to user
            $validate_sql = "SELECT id FROM tbl_week_off_punch WHERE id = ? AND user_id = ? AND status = 'active'";
            $validate_stmt = $conn->prepare($validate_sql);
            $validate_stmt->bind_param("ii", $punch_id, $user_id);
            $validate_stmt->execute();
            $validate_result = $validate_stmt->get_result();
            
            if ($validate_result->num_rows === 0) {
                $response['message'] = 'Invalid punch record or already cancelled';
                echo json_encode($response);
                exit;
            }
            
            // Update status to cancelled
            $cancel_sql = "UPDATE tbl_week_off_punch SET status = 'cancelled' WHERE id = ? AND user_id = ?";
            $cancel_stmt = $conn->prepare($cancel_sql);
            $cancel_stmt->bind_param("ii", $punch_id, $user_id);
            
            if ($cancel_stmt->execute()) {
                $response['success'] = true;
                $response['message'] = 'Punch record cancelled successfully!';
            } else {
                $response['message'] = 'Failed to cancel punch: ' . $conn->error;
            }
        }
        
    } elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $action = $_GET['action'] ?? '';
        
        if ($action === 'get_punches') {
            // Get user's punch records
            $sql = "SELECT * FROM tbl_week_off_punch WHERE user_id = ? ORDER BY punch_date DESC, punch_time DESC";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            
            $punches = [];
            while ($row = $result->fetch_assoc()) {
                $punches[] = $row;
            }
            
            $response['success'] = true;
            $response['data'] = $punches;
        }
    }
    
} catch (Exception $e) {
    $response['message'] = 'Server error: ' . $e->getMessage();
}

echo json_encode($response);
?>
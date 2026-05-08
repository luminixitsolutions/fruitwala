<?php
session_start();
require_once 'config.php';

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET');
header('Access-Control-Allow-Headers: Content-Type');

$response = ['status' => 'error', 'message' => 'Invalid request'];

try {
    $executiveId = $_SESSION['User']['id'] ?? 0;
    if (!$executiveId) {
        echo json_encode(['status' => 'error', 'message' => 'Session expired. Please login again.']);
        exit;
    }

    ensureHoldDatesTableExists($conn);

    $action = $_POST['action'] ?? $_GET['action'] ?? '';

    if ($action === 'get_dates') {
        $orderId = isset($_REQUEST['order_id']) ? intval($_REQUEST['order_id']) : 0;
        if (!$orderId) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid order ID.']);
            exit;
        }
        $dates = getExistingHoldDates($conn, $orderId);
        echo json_encode(['status' => 'success', 'dates' => $dates]);
        exit;
    }

    if ($action === 'save') {
        $orderId = isset($_POST['order_id']) ? intval($_POST['order_id']) : 0;
        $holdDatesRaw = $_POST['hold_dates'] ?? [];
        if (!$orderId) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid order ID.']);
            exit;
        }
        if (!is_array($holdDatesRaw)) {
            $holdDatesRaw = $holdDatesRaw ? [trim($holdDatesRaw)] : [];
        }
        $holdDates = array_values(array_filter(array_map('trim', $holdDatesRaw)));
        $today = date('Y-m-d');

        $errors = [];
        $validDates = [];
        foreach ($holdDates as $d) {
            if ($d === '') continue;
            if ($d < $today) {
                $errors[] = "Date $d cannot be in the past.";
                continue;
            }
            $dayOfWeek = date('N', strtotime($d));
            if ($dayOfWeek == 7) {
                $errors[] = "Sunday ($d) is already a delivery off day.";
                continue;
            }
            if (in_array($d, $validDates)) {
                $errors[] = "Duplicate date: $d.";
                continue;
            }
            $validDates[] = $d;
        }

        if (!empty($errors)) {
            echo json_encode(['status' => 'error', 'message' => implode(' ', $errors)]);
            exit;
        }

        $inserted = 0;
        foreach ($validDates as $holdDate) {
            if (isDuplicateHoldDate($conn, $orderId, $holdDate)) {
                continue;
            }
            $stmt = $conn->prepare("INSERT INTO tbl_order_hold_dates (order_id, hold_date, created_by) VALUES (?, ?, ?)");
            $stmt->bind_param("isi", $orderId, $holdDate, $executiveId);
            if ($stmt->execute()) {
                $inserted++;
            }
            $stmt->close();
        }

        $message = $inserted > 0
            ? ($inserted === 1 ? '1 hold date saved.' : $inserted . ' hold dates saved.')
            : 'No new dates to save (all may already exist).';
        echo json_encode(['status' => 'success', 'message' => $message, 'inserted' => $inserted]);
        exit;
    }

    echo json_encode($response);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Server error. Please try again.']);
}

function ensureHoldDatesTableExists($conn) {
    $sql = "CREATE TABLE IF NOT EXISTS tbl_order_hold_dates (
        id INT AUTO_INCREMENT PRIMARY KEY,
        order_id INT NOT NULL,
        hold_date DATE NOT NULL,
        created_by INT NOT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        UNIQUE KEY uq_order_hold (order_id, hold_date)
    )";
    $conn->query($sql);
}

function getExistingHoldDates($conn, $orderId) {
    $orderId = intval($orderId);
    $today = date('Y-m-d');
    $futureDates = [];
    $pastDates = [];
    
    $stmt = $conn->prepare("SELECT hold_date FROM tbl_order_hold_dates WHERE order_id = ? ORDER BY hold_date ASC");
    $stmt->bind_param("i", $orderId);
    $stmt->execute();
    $res = $stmt->get_result();
    while ($row = $res->fetch_assoc()) {
        if ($row['hold_date'] >= $today) {
            $futureDates[] = $row['hold_date'];
        } else {
            $pastDates[] = $row['hold_date'];
        }
    }
    $stmt->close();
    return ['future' => $futureDates, 'past' => $pastDates];
}

function isDuplicateHoldDate($conn, $orderId, $holdDate) {
    $stmt = $conn->prepare("SELECT 1 FROM tbl_order_hold_dates WHERE order_id = ? AND hold_date = ? LIMIT 1");
    $stmt->bind_param("is", $orderId, $holdDate);
    $stmt->execute();
    $res = $stmt->get_result();
    $exists = $res->num_rows > 0;
    $stmt->close();
    return $exists;
}

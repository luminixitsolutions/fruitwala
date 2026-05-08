<?php
session_start();
include_once 'config.php';
include_once 'auth.php';

header('Content-Type: application/json; charset=utf-8');
date_default_timezone_set('Asia/Kolkata');

$response = ['status' => 'error', 'message' => 'Invalid request'];

try {
    $action = $_POST['action'] ?? $_GET['action'] ?? '';

    // Ensure tables exist
    $conn->query("CREATE TABLE IF NOT EXISTS tbl_order_hold_dates (
        id INT AUTO_INCREMENT PRIMARY KEY,
        order_id INT NOT NULL,
        hold_date DATE NOT NULL,
        created_by INT NOT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        UNIQUE KEY uq_order_hold (order_id, hold_date)
    )");
    
    $conn->query("CREATE TABLE IF NOT EXISTS tbl_order_delivery_boxes (
        id INT AUTO_INCREMENT PRIMARY KEY,
        order_id INT NOT NULL,
        delivery_date DATE NOT NULL,
        status ENUM('delivered','pending') DEFAULT 'pending',
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        UNIQUE KEY uq_order_delivery (order_id, delivery_date)
    )");

    if ($action === 'get_boxes') {
        $customerId = isset($_REQUEST['customer_id']) ? intval($_REQUEST['customer_id']) : 0;
        if (!$customerId) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid customer ID.']);
            exit;
        }
        $data = getBoxesData($conn, $customerId);
        if ($data === null) {
            echo json_encode(['status' => 'error', 'message' => 'Customer not found or missing package dates.']);
            exit;
        }
        echo json_encode(['status' => 'success', 'data' => $data]);
        exit;
    }

    if ($action === 'get_hold_dates') {
        $customerId = isset($_REQUEST['customer_id']) ? intval($_REQUEST['customer_id']) : 0;
        if (!$customerId) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid customer ID.']);
            exit;
        }
        $dates = getHoldDatesList($conn, $customerId);
        echo json_encode(['status' => 'success', 'dates' => $dates]);
        exit;
    }

    if ($action === 'add_hold_date') {
        $customerId = isset($_POST['customer_id']) ? intval($_POST['customer_id']) : 0;
        $holdDate = trim($_POST['hold_date'] ?? '');
        $adminId = $_SESSION['Admin']['id'] ?? 0;
        
        if (!$customerId || !$holdDate) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid data.']);
            exit;
        }
        
        $today = date('Y-m-d');
        if ($holdDate < $today) {
            echo json_encode(['status' => 'error', 'message' => 'Date cannot be in the past.']);
            exit;
        }
        
        $dayOfWeek = date('N', strtotime($holdDate));
        if ($dayOfWeek == 7) {
            echo json_encode(['status' => 'error', 'message' => 'Sunday is already a delivery off day. Please select another date.']);
            exit;
        }
        
        $stmt = $conn->prepare("INSERT INTO tbl_order_hold_dates (order_id, hold_date, created_by) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE created_by = VALUES(created_by)");
        $stmt->bind_param("isi", $customerId, $holdDate, $adminId);
        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                echo json_encode(['status' => 'success', 'message' => 'Hold date added successfully.']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Date already exists.']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to add hold date.']);
        }
        $stmt->close();
        exit;
    }

    if ($action === 'delete_hold_date') {
        $customerId = isset($_POST['customer_id']) ? intval($_POST['customer_id']) : 0;
        $holdDate = trim($_POST['hold_date'] ?? '');
        
        if (!$customerId || !$holdDate) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid data.']);
            exit;
        }
        
        $stmt = $conn->prepare("DELETE FROM tbl_order_hold_dates WHERE order_id = ? AND hold_date = ?");
        $stmt->bind_param("is", $customerId, $holdDate);
        if ($stmt->execute() && $stmt->affected_rows > 0) {
            echo json_encode(['status' => 'success', 'message' => 'Hold date deleted.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Could not delete.']);
        }
        $stmt->close();
        exit;
    }

    echo json_encode($response);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Server error.']);
}

function getHoldDatesList($conn, $customerId) {
    $today = date('Y-m-d');
    $futureDates = [];
    $pastDates = [];
    
    $stmt = $conn->prepare("SELECT hold_date FROM tbl_order_hold_dates WHERE order_id = ? ORDER BY hold_date ASC");
    $stmt->bind_param("i", $customerId);
    $stmt->execute();
    $res = $stmt->get_result();
    while ($r = $res->fetch_assoc()) {
        if ($r['hold_date'] >= $today) {
            $futureDates[] = $r['hold_date'];
        } else {
            $pastDates[] = $r['hold_date'];
        }
    }
    $stmt->close();
    return ['future' => $futureDates, 'past' => $pastDates];
}

function getHoldDates($conn, $orderId, $startDate, $endDate) {
    $orderId = intval($orderId);
    $holdMap = [];
    $stmt = $conn->prepare("SELECT hold_date FROM tbl_order_hold_dates WHERE order_id = ? AND hold_date >= ? AND hold_date <= ?");
    $stmt->bind_param("iss", $orderId, $startDate, $endDate);
    $stmt->execute();
    $res = $stmt->get_result();
    while ($r = $res->fetch_assoc()) {
        $holdMap[$r['hold_date']] = true;
    }
    $stmt->close();
    return $holdMap;
}

function syncDeliveriesFromLog($conn, $orderId, $packageDates) {
    if (empty($packageDates)) return;
    $orderId = intval($orderId);
    $startDate = min($packageDates);
    $endDate = max($packageDates);
    
    // Check both DeliveredDate and CreatedDate columns for delivered entries
    $stmt = $conn->prepare("
        SELECT DISTINCT 
            COALESCE(DATE(DeliveredDate), DATE(CreatedDate)) as del_date 
        FROM tbl_order_status_log 
        WHERE UserId = ? 
          AND Status = 'Delivered' 
          AND (
              (DeliveredDate IS NOT NULL AND DATE(DeliveredDate) >= ? AND DATE(DeliveredDate) <= ?)
              OR (DeliveredDate IS NULL AND DATE(CreatedDate) >= ? AND DATE(CreatedDate) <= ?)
          )
    ");
    $stmt->bind_param("issss", $orderId, $startDate, $endDate, $startDate, $endDate);
    $stmt->execute();
    $res = $stmt->get_result();
    $deliveredDates = [];
    while ($r = $res->fetch_assoc()) {
        if (!empty($r['del_date'])) $deliveredDates[] = $r['del_date'];
    }
    $stmt->close();
    
    if (!empty($deliveredDates)) {
        $insertStmt = $conn->prepare("INSERT INTO tbl_order_delivery_boxes (order_id, delivery_date, status) VALUES (?, ?, 'delivered') ON DUPLICATE KEY UPDATE status = 'delivered'");
        foreach ($deliveredDates as $delDate) {
            $insertStmt->bind_param("is", $orderId, $delDate);
            $insertStmt->execute();
        }
        $insertStmt->close();
    }
}

function getBoxesData($conn, $orderId) {
    $orderId = intval($orderId);
    $stmt = $conn->prepare("SELECT PkgDate, Validity FROM tbl_users WHERE id = ? AND Roll = 5 LIMIT 1");
    $stmt->bind_param("i", $orderId);
    $stmt->execute();
    $res = $stmt->get_result();
    $row = $res->fetch_assoc();
    $stmt->close();
    
    if (!$row || empty($row['PkgDate'])) return null;
    
    $start = $row['PkgDate'];
    $originalEnd = !empty($row['Validity']) ? $row['Validity'] : $row['PkgDate'];
    if ($originalEnd < $start) $originalEnd = $start;

    $holdDatesInOriginal = getHoldDates($conn, $orderId, $start, $originalEnd);
    $holdCount = count($holdDatesInOriginal);
    
    $startDt = new DateTime($start);
    $endDt = new DateTime($originalEnd);
    
    if ($holdCount > 0) {
        $daysToAdd = $holdCount;
        while ($daysToAdd > 0) {
            $endDt->modify('+1 day');
            if ($endDt->format('N') != 7) $daysToAdd--;
        }
    }
    $extendedEnd = $endDt->format('Y-m-d');

    $holdDates = getHoldDates($conn, $orderId, $start, $extendedEnd);
    
    $allDates = [];
    $workingDates = [];
    $sundayDates = [];
    $current = clone $startDt;
    while ($current <= $endDt) {
        $d = $current->format('Y-m-d');
        $allDates[] = $d;
        if ($current->format('N') == 7) {
            $sundayDates[$d] = true;
        } else {
            $workingDates[] = $d;
        }
        $current->modify('+1 day');
    }

    $syncDates = array_diff($workingDates, array_keys($holdDates));
    syncDeliveriesFromLog($conn, $orderId, $syncDates);

    $deliveredMap = [];
    if (!empty($workingDates)) {
        $placeholders = implode(',', array_fill(0, count($workingDates), '?'));
        $stmt = $conn->prepare("SELECT delivery_date FROM tbl_order_delivery_boxes WHERE order_id = ? AND delivery_date IN ($placeholders) AND status = 'delivered'");
        $types = 'i' . str_repeat('s', count($workingDates));
        $params = array_merge([$orderId], $workingDates);
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $res = $stmt->get_result();
        while ($r = $res->fetch_assoc()) {
            $deliveredMap[$r['delivery_date']] = true;
        }
        $stmt->close();
    }

    $boxes = [];
    foreach ($allDates as $d) {
        $isSunday = isset($sundayDates[$d]);
        $isHold = isset($holdDates[$d]);
        $dayOfMonth = (int) date('j', strtotime($d));
        
        if ($isSunday) {
            $boxes[] = ['date' => $d, 'type' => 'sunday', 'label' => 'S'];
        } elseif ($isHold) {
            $boxes[] = ['date' => $d, 'type' => 'hold', 'label' => 'H'];
        } elseif (isset($deliveredMap[$d])) {
            $boxes[] = ['date' => $d, 'type' => 'delivered', 'label' => $dayOfMonth];
        } else {
            $boxes[] = ['date' => $d, 'type' => 'pending', 'label' => $dayOfMonth];
        }
    }

    $totalWorkingDays = count($workingDates) - count($holdDates);
    $deliveredCount = 0;
    foreach ($deliveredMap as $dd => $v) {
        if (!isset($holdDates[$dd])) $deliveredCount++;
    }

    return [
        'pkg_start_date' => $start,
        'pkg_end_date' => $originalEnd,
        'extended_end_date' => $extendedEnd,
        'total_days' => $totalWorkingDays,
        'delivered_count' => $deliveredCount,
        'hold_count' => count($holdDates),
        'sunday_count' => count($sundayDates),
        'boxes' => $boxes
    ];
}

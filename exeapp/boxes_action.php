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

    ensureBoxesTableExists($conn);

    $action = $_POST['action'] ?? $_GET['action'] ?? '';

    if ($action === 'get') {
        $orderId = isset($_REQUEST['order_id']) ? intval($_REQUEST['order_id']) : 0;
        if (!$orderId) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid order ID.']);
            exit;
        }
        $data = getBoxesData($conn, $orderId);
        if ($data === null) {
            echo json_encode(['status' => 'error', 'message' => 'Order not found or missing package dates.']);
            exit;
        }
        echo json_encode(['status' => 'success', 'data' => $data]);
        exit;
    }

    echo json_encode($response);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Server error. Please try again.']);
}

function ensureBoxesTableExists($conn) {
    $sql = "CREATE TABLE IF NOT EXISTS tbl_order_delivery_boxes (
        id INT AUTO_INCREMENT PRIMARY KEY,
        order_id INT NOT NULL,
        delivery_date DATE NOT NULL,
        status ENUM('delivered','pending') DEFAULT 'pending',
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        UNIQUE KEY uq_order_delivery (order_id, delivery_date)
    )";
    $conn->query($sql);
}

/**
 * Get package dates from tbl_users and box status from tbl_order_delivery_boxes.
 * Syncs existing deliveries from tbl_order_status_log into tbl_order_delivery_boxes first.
 * Green box only if delivery_date exists in tbl_order_delivery_boxes with status='delivered'.
 */
function getBoxesData($conn, $orderId) {
    $orderId = intval($orderId);
    $stmt = $conn->prepare("SELECT PkgDate, Validity FROM tbl_users WHERE id = ? AND Roll = 5 LIMIT 1");
    $stmt->bind_param("i", $orderId);
    $stmt->execute();
    $res = $stmt->get_result();
    $row = $res->fetch_assoc();
    $stmt->close();
    if (!$row || empty($row['PkgDate'])) {
        return null;
    }
    $start = $row['PkgDate'];
    $originalEnd = !empty($row['Validity']) ? $row['Validity'] : $row['PkgDate'];
    if ($originalEnd < $start) {
        $originalEnd = $start;
    }

    // First pass: count hold days within original package range to calculate extension
    $holdDatesInOriginal = getHoldDates($conn, $orderId, $start, $originalEnd);
    $holdCount = count($holdDatesInOriginal);
    
    // Extend end date by number of hold days (excluding Sundays in extension)
    $startDt = new DateTime($start);
    $endDt = new DateTime($originalEnd);
    
    // Add hold days to extend the package
    if ($holdCount > 0) {
        $daysToAdd = $holdCount;
        while ($daysToAdd > 0) {
            $endDt->modify('+1 day');
            // Only count non-Sunday days for extension
            if ($endDt->format('N') != 7) {
                $daysToAdd--;
            }
        }
    }
    $extendedEnd = $endDt->format('Y-m-d');

    // Now get ALL hold dates including any in the extended range
    $holdDates = getHoldDates($conn, $orderId, $start, $extendedEnd);
    
    // Build all dates array from start to extended end (including Sundays for display)
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

    // Sync existing deliveries from tbl_order_status_log into tbl_order_delivery_boxes (only working days, not hold/sunday)
    $syncDates = array_diff($workingDates, array_keys($holdDates));
    syncDeliveriesFromLog($conn, $orderId, $syncDates);

    // Get delivered dates
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

    // Build boxes with type: sunday, hold, delivered, pending
    // Label shows day of month (e.g., 11, 12, 13... for Feb 11, Feb 12, etc.)
    $boxes = [];
    foreach ($allDates as $d) {
        $isSunday = isset($sundayDates[$d]);
        $isHold = isset($holdDates[$d]);
        $dayOfMonth = (int) date('j', strtotime($d)); // Day of month without leading zero
        
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

    // Total working days = excluding Sundays and Hold dates
    $totalWorkingDays = count($workingDates) - count($holdDates);
    // Delivered count = only actual deliveries (not hold, not sunday)
    $deliveredCount = 0;
    foreach ($deliveredMap as $dd => $v) {
        if (!isset($holdDates[$dd])) {
            $deliveredCount++;
        }
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

/**
 * Sync delivery dates from tbl_order_status_log into tbl_order_delivery_boxes.
 * This ensures boxes show green for deliveries that were recorded before the boxes feature.
 */
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
        if (!empty($r['del_date'])) {
            $deliveredDates[] = $r['del_date'];
        }
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

/**
 * Get hold dates for an order from tbl_order_hold_dates.
 */
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

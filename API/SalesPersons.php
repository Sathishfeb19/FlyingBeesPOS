<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: POST, GET, DELETE, PUT, OPTIONS");
header("Access-Control-Allow-Headers: Access-Control-Allow-Headers, Origin, Accept, X-Requested-With, Content-Type, Access-Control-Request-Method, Access-Control-Request-Headers");

// Handle preflight
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

include 'Config.php';

$resp = [];

// Retrieve POST form data (sent via FormData)
$FROMDATE = $_POST['FROMDATE'] ?? '';
$TODATE = $_POST['TODATE'] ?? '';

// Validate input
if (empty($FROMDATE) || empty($TODATE)) {
    echo json_encode([
        "status" => "error",
        "message" => "FROMDATE and TODATE are required"
    ]);
    exit;
}

// Use prepared statement
$stmt = $DbCon->prepare("
    SELECT 
        U.USER_NAME, 
        SUM(S.TOTAL_AMT) AS TOTAL_AMT,
        SUM(S.NO_ITEMS) AS NO_ITEMS
    FROM 
        stk_transaction S
    JOIN 
        mas_users U ON S.SALES_PERSON = U.USER_ID
    WHERE 
        S.INVOICE_DATE BETWEEN ? AND ?
    GROUP BY 
        U.USER_ID
    ORDER BY 
        U.USER_ID DESC
");

$stmt->bind_param("ss", $FROMDATE, $TODATE);

if ($stmt->execute()) {
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $resp[] = array_merge(["status" => "success"], $row);
    }
} else {
    $resp = [
        "status" => "error",
        "message" => "Query failed: " . $stmt->error
    ];
}

echo json_encode($resp);
$stmt->close();
$DbCon->close();

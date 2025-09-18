<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header('Access-Control-Allow-Methods: POST, GET, DELETE, PUT');
header("Access-Control-Allow-Headers: Origin, Accept, X-Requested-With, Content-Type");

include 'Config.php';

$resp = [];
$search = $_POST['SEARCH'] ?? '';
$WH = $_POST['WH'] ?? '';

// Check if WH is 'all' and modify query accordingly
if ($WH === 'all') {
    $query = "SELECT S.*, U.USER_NAME, W.WH_NAME 
              FROM STK_TRANSACTION S
              JOIN MAS_USERS U ON S.CREATED_BY = U.USER_ID
              JOIN MAS_WAREHOUSE W ON W.WH_ID = S.WH_FROMID
              WHERE S.TRN_STATUS != 'DELETED' 
              ORDER BY S.TRN_ID DESC;";
} else {
    $query = "SELECT S.*, U.USER_NAME, W.WH_NAME 
              FROM STK_TRANSACTION S
              JOIN MAS_USERS U ON S.CREATED_BY = U.USER_ID
              JOIN MAS_WAREHOUSE W ON W.WH_ID = S.WH_FROMID
              WHERE S.TRN_STATUS != 'DELETED' AND S.WH_FROMID = '$WH'
              ORDER BY S.TRN_ID DESC;";
}

$result = $DbCon->query($query);

while ($row = $result->fetch_assoc()) {
    $resp[] = [
        'status' => 'success',
        'USER_NAME' => $row['USER_NAME'],
        'WH_NAME' => $row['WH_NAME'],
        'INVOICE_DATE' => $row['CREATED_DATE'],
        'INVOICE_NO' => $row['TRN_CODE'],
        'CUSTOMER_NAME' => $row['CUSTOMER_NAME'],
        'CUSTOMER_MOBILE' => $row['CUSTOMER_MOBILE'],
        'TOTAL_AMT' => $row['TOTAL_AMT'],
        'TRN_CODE' => $row['TRN_CODE'],
        'TRN_ID' => encryptData($row['TRN_ID']),
    ];
}

echo json_encode($resp);
mysqli_close($DbCon);
?>

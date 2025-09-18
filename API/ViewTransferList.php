<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header('Access-Control-Allow-Methods: POST, GET, DELETE, PUT');
header("Access-Control-Allow-Headers: Origin, Accept, X-Requested-With, Content-Type");

include 'Config.php';

$WH = $_POST['WH'] ?? '';
if (empty($WH)) exit(json_encode(['status' => 'error', 'message' => 'Warehouse ID (WH) is required']));

$query = "SELECT S.TRN_ID, S.TRN_CODE, S.CREATED_DATE, S.CUSTOMER_NAME, S.CUSTOMER_MOBILE, S.TOTAL_AMT, 
                 U.USER_NAME, W.WH_NAME, W.WH_DESC ,W.WH_MOBILE
          FROM STK_TRANSACTION S
          JOIN MAS_USERS U ON S.CREATED_BY = U.USER_ID
          JOIN MAS_WAREHOUSE W ON W.WH_ID = S.WH_TOID
          WHERE S.TRN_STATUS != 'DELETED' " . ($WH !== 'all' ? "AND S.WH_TOID = ?" : "") . " 
          GROUP BY S.TRN_ID ORDER BY S.TRN_ID DESC";

if ($stmt = $DbCon->prepare($query)) {
    if ($WH !== 'all') $stmt->bind_param("s", $WH);
    $stmt->execute();
    $result = $stmt->get_result();
    $resp = [];

    while ($row = $result->fetch_assoc()) {
        $resp[] = [
            'status' => 'success',
            'USER_NAME' => $row['USER_NAME'],
            'INVOICE_DATE' => $row['CREATED_DATE'],
            'INVOICE_NO' => $row['TRN_CODE'],
            'WH_NAME' => $row['WH_NAME'],
            'WH_DESC' => $row['WH_DESC'],
            'WH_MOBILE' => $row['WH_MOBILE'],
            'TOTAL_AMT' => $row['TOTAL_AMT'],
            'TRN_CODE' => $row['TRN_CODE'],
            'TRN_ID' => encryptData($row['TRN_ID']),
        ];
    }
    $stmt->close();
    echo json_encode($resp);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Query preparation failed']);
}

mysqli_close($DbCon);
?>

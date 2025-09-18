<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: POST, GET, DELETE, PUT");
header("Access-Control-Allow-Headers: Access-Control-Allow-Headers, Origin, Accept, X-Requested-With, Content-Type, Access-Control-Request-Method, Access-Control-Request-Headers");

include 'Config.php';

$WH = $_POST['WH'];
$resp = [];
if ($WH == "all") {
    $query = "SELECT S.QR_CODE, 
       S.NAME, 
       SUM(S.TRN_QTY) AS QTY 
FROM STK_TRANSDETAILS S ,STK_TRANSACTION T
WHERE S.TRN_STATUS = 'A' AND T.TRN_ID =S.TRNS_ID AND T.WH_FROMID ='all'
  AND DATE(STR_TO_DATE(S.CREATED_DATE, '%d-%m-%Y %H:%i:%s')) >= CURDATE() - INTERVAL 60 DAY 
GROUP BY S.QR_CODE 
ORDER BY QTY DESC 
LIMIT 20;
";
} else {
    $query = "SELECT S.QR_CODE, 
       S.NAME, 
       SUM(S.TRN_QTY) AS QTY 
FROM STK_TRANSDETAILS S ,STK_TRANSACTION T
WHERE S.TRN_STATUS = 'A' AND T.TRN_ID =S.TRNS_ID AND T.WH_FROMID ='$WH'
  AND DATE(STR_TO_DATE(S.CREATED_DATE, '%d-%m-%Y %H:%i:%s')) >= CURDATE() - INTERVAL 60 DAY 
GROUP BY S.QR_CODE 
ORDER BY QTY DESC 
LIMIT 20;
";
}

if ($check = $DbCon->query($query)) {
    while ($result = $check->fetch_assoc()) {
        $resp[] = array_merge(["status" => "success", "QR_CODE" => $result['QR_CODE']], $result);
    }
} else {
    $resp = ["status" => "error", "message" => "Query failed: " . $DbCon->error];
}

echo json_encode($resp);
mysqli_close($DbCon);

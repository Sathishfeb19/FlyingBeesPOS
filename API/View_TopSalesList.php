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
FROM STK_TRANSDETAILS S 
WHERE S.TRN_STATUS = 'A' 
  AND DATE(STR_TO_DATE(S.CREATED_DATE, '%d-%m-%Y %H:%i:%s')) >= CURDATE() - INTERVAL 7 DAY 
GROUP BY S.QR_CODE 
ORDER BY QR_CODE DESC  
LIMIT 20;
";
} else {
    $query = "SELECT S.QR_CODE, 
       S.NAME, 
       SUM(S.TRN_QTY) AS QTY 
FROM STK_TRANSDETAILS S 
WHERE S.TRN_STATUS = 'A' 
  AND DATE(STR_TO_DATE(S.CREATED_DATE, '%d-%m-%Y %H:%i:%s')) >= CURDATE() - INTERVAL 7 DAY 
GROUP BY S.QR_CODE 
ORDER BY QR_CODE DESC 
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

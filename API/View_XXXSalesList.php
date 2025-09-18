<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: POST, GET, DELETE, PUT");
header("Access-Control-Allow-Headers: Access-Control-Allow-Headers, Origin, Accept, X-Requested-With, Content-Type, Access-Control-Request-Method, Access-Control-Request-Headers");

include 'Config.php';

$WH = $_POST['WH'];
$resp = [];
if ($WH == "all") {

    $query = "SELECT 
    W.WH_NAME AS LOCATION, 
     SUM(T.SUB_TOTAL) AS SAL_VALUE, 
      SUM(T.TRN_TAX) AS SAL_TAX, 
    SUM(T.TOTAL_AMT) AS SAL_AMOUNT, 
    COUNT(*) AS TOTAL_COUNT 
FROM STK_TRANSACTION T
JOIN MAS_WAREHOUSE W ON T.WH_FROMID = W.WH_ID
WHERE 
    TRN_STATUS = 'A'
    AND DATE(STR_TO_DATE(T.CREATED_DATE, '%d-%m-%Y %H:%i:%s')) >= CURDATE() - INTERVAL 30 DAY
GROUP BY T.WH_FROMID;";
} else {
    $query = "SELECT 
    W.WH_NAME AS LOCATION, 
     SUM(T.SUB_TOTAL) AS SAL_VALUE, 
      SUM(T.TRN_TAX) AS SAL_TAX, 
    SUM(T.TOTAL_AMT) AS SAL_AMOUNT, 
    COUNT(*) AS TOTAL_COUNT 
FROM STK_TRANSACTION T
JOIN MAS_WAREHOUSE W ON T.WH_FROMID = W.WH_ID
WHERE 
    TRN_STATUS = 'A' AND T.WH_FROMID='$WH'
    AND DATE(STR_TO_DATE(T.CREATED_DATE, '%d-%m-%Y %H:%i:%s')) >= CURDATE() - INTERVAL 30 DAY
GROUP BY T.WH_FROMID;";
}

if ($check = $DbCon->query($query)) {
    while ($result = $check->fetch_assoc()) {
        $resp[] = array_merge(["status" => "success", "LOCATION" => $result['LOCATION']], $result);
    }
} else {
    $resp = ["status" => "error", "message" => "Query failed: " . $DbCon->error];
}

echo json_encode($resp);
mysqli_close($DbCon);

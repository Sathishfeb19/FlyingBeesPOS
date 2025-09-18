<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: POST, GET, DELETE, PUT");
header("Access-Control-Allow-Headers: Access-Control-Allow-Headers, Origin, Accept, X-Requested-With, Content-Type, Access-Control-Request-Method, Access-Control-Request-Headers");

include 'Config.php';

$resp = [];
$search = $_POST['SEARCH'];
$WH = $_POST['WH'];
if ($WH == "all") {
    $query = "SELECT 
 S.NAME,K.S_SUPPLIER,K.S_SALERATE
  S.NAME,
  SUM(CASE 
        WHEN DATE(STR_TO_DATE(S.CREATED_DATE, '%d-%m-%Y %H:%i:%s')) >= CURDATE() - INTERVAL 7 DAY 
             THEN S.TRN_QTY 
        ELSE 0 
      END) AS QTY_7_DAYS,
       SUM(CASE 
        WHEN DATE(STR_TO_DATE(S.CREATED_DATE, '%d-%m-%Y %H:%i:%s')) >= CURDATE() - INTERVAL 15 DAY 
             THEN S.TRN_QTY 
        ELSE 0 
      END) AS QTY_15_DAYS,
      
  SUM(CASE 
        WHEN DATE(STR_TO_DATE(S.CREATED_DATE, '%d-%m-%Y %H:%i:%s')) >= CURDATE() - INTERVAL 30 DAY 
             THEN S.TRN_QTY 
        ELSE 0 
      END) AS QTY_30_DAYS,
       SUM(CASE 
        WHEN DATE(STR_TO_DATE(S.CREATED_DATE, '%d-%m-%Y %H:%i:%s')) >= CURDATE() - INTERVAL 45 DAY 
             THEN S.TRN_QTY 
        ELSE 0 
      END) AS QTY_45_DAYS,
  SUM(CASE 
        WHEN DATE(STR_TO_DATE(S.CREATED_DATE, '%d-%m-%Y %H:%i:%s')) >= CURDATE() - INTERVAL 90 DAY 
             THEN S.TRN_QTY 
        ELSE 0 
      END) AS QTY_90_DAYS
FROM STK_TRANSDETAILS S
JOIN STK_TRANSACTION T ON T.TRN_ID = S.TRNS_ID JOIN TRN_WHSTOCK K ON K.S_BARCODE = S.QR_CODE
WHERE S.TRN_STATUS = 'A'
  AND T.WH_FROMID = 'all'
GROUP BY S.QR_CODE, S.NAME  
ORDER BY `QTY_7_DAYS` DESC
";
} else {
    $query = "SELECT 
  S.QR_CODE,
  S.NAME,K.S_SUPPLIER,K.S_SALERATE
  SUM(CASE 
        WHEN DATE(STR_TO_DATE(S.CREATED_DATE, '%d-%m-%Y %H:%i:%s')) >= CURDATE() - INTERVAL 7 DAY 
             THEN S.TRN_QTY 
        ELSE 0 
      END) AS QTY_7_DAYS,
       SUM(CASE 
        WHEN DATE(STR_TO_DATE(S.CREATED_DATE, '%d-%m-%Y %H:%i:%s')) >= CURDATE() - INTERVAL 15 DAY 
             THEN S.TRN_QTY 
        ELSE 0 
      END) AS QTY_15_DAYS,
      
  SUM(CASE 
        WHEN DATE(STR_TO_DATE(S.CREATED_DATE, '%d-%m-%Y %H:%i:%s')) >= CURDATE() - INTERVAL 30 DAY 
             THEN S.TRN_QTY 
        ELSE 0 
      END) AS QTY_30_DAYS,
       SUM(CASE 
        WHEN DATE(STR_TO_DATE(S.CREATED_DATE, '%d-%m-%Y %H:%i:%s')) >= CURDATE() - INTERVAL 45 DAY 
             THEN S.TRN_QTY 
        ELSE 0 
      END) AS QTY_45_DAYS,
  SUM(CASE 
        WHEN DATE(STR_TO_DATE(S.CREATED_DATE, '%d-%m-%Y %H:%i:%s')) >= CURDATE() - INTERVAL 90 DAY 
             THEN S.TRN_QTY 
        ELSE 0 
      END) AS QTY_90_DAYS
FROM STK_TRANSDETAILS S
JOIN STK_TRANSACTION T ON T.TRN_ID = S.TRNS_ID JOIN TRN_WHSTOCK K ON K.S_BARCODE = S.QR_CODE

WHERE S.TRN_STATUS = 'A'
  AND T.WH_FROMID = '$WH'
GROUP BY S.QR_CODE, S.NAME  
ORDER BY `QTY_7_DAYS` DESC
";
}

if ($check = $DbCon->query($query)) {
    while ($result = $check->fetch_assoc()) {
        $resp[] = array_merge(["status" => "success"], $result);
    }
} else {
    $resp = ["status" => "error", "message" => "Query failed: " . $DbCon->error];
}

echo json_encode($resp);
mysqli_close($DbCon);


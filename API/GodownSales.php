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
  DATE_FORMAT(STR_TO_DATE(T.CREATED_DATE, '%d-%m-%Y %H:%i:%s'), '%d/%m/%Y') AS TRANSACTION_DATE,
  FORMAT(SUM(CASE WHEN W.WH_ID = '1' THEN T.TOTAL_AMT ELSE 0 END), 2) AS Warehouse1,
  FORMAT(SUM(CASE WHEN W.WH_ID = '2' THEN T.TOTAL_AMT ELSE 0 END), 2) AS Warehouse2,
  FORMAT(SUM(CASE WHEN W.WH_ID = '3' THEN T.TOTAL_AMT ELSE 0 END), 2) AS Warehouse3,
  FORMAT(SUM(T.TOTAL_AMT), 2) AS NETTOTAL
FROM 
  STK_TRANSACTION T
JOIN 
  MAS_WAREHOUSE W ON T.WH_FROMID = W.WH_ID
WHERE 
  T.WH_FROMID != 'ALL'
GROUP BY 
  DATE(STR_TO_DATE(T.CREATED_DATE, '%d-%m-%Y %H:%i:%s'))
ORDER BY 
  DATE(STR_TO_DATE(T.CREATED_DATE, '%d-%m-%Y %H:%i:%s')) DESC;

";
} else {
    $query = "SELECT 
  DATE_FORMAT(STR_TO_DATE(T.CREATED_DATE, '%d-%m-%Y %H:%i:%s'), '%d/%m/%Y') AS TRANSACTION_DATE,
  FORMAT(SUM(CASE WHEN W.WH_ID = '1' THEN T.TOTAL_AMT ELSE 0 END), 2) AS Warehouse1,
  FORMAT(SUM(CASE WHEN W.WH_ID = '2' THEN T.TOTAL_AMT ELSE 0 END), 2) AS Warehouse2,
  FORMAT(SUM(CASE WHEN W.WH_ID = '3' THEN T.TOTAL_AMT ELSE 0 END), 2) AS Warehouse3,
  FORMAT(SUM(T.TOTAL_AMT), 2) AS NETTOTAL
FROM 
  STK_TRANSACTION T
JOIN 
  MAS_WAREHOUSE W ON T.WH_FROMID = W.WH_ID
WHERE 
  T.WH_FROMID != 'ALL' and  T.WH_FROMID = '$WH'
GROUP BY 
  DATE(STR_TO_DATE(T.CREATED_DATE, '%d-%m-%Y %H:%i:%s'))
ORDER BY 
  DATE(STR_TO_DATE(T.CREATED_DATE, '%d-%m-%Y %H:%i:%s')) DESC;

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


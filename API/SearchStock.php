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
    $query = "SELECT *, 
       IFNULL(DATEDIFF(CURDATE(), STR_TO_DATE(CREATED_DATE, '%d-%m-%Y %H:%i:%s')), 0) AS AGEDAYS
FROM TRN_MAINSTOCK 
WHERE (S_BARCODE LIKE '%$search%' 
       OR S_PRODUCTNAME LIKE '%$search%')
  AND S_QTY > 0
ORDER BY STOCK_ID DESC;


";
} else {
    $query = "SELECT *, 
       IFNULL(DATEDIFF(CURDATE(), STR_TO_DATE(CREATED_DATE, '%d-%m-%Y %H:%i:%s')), 0) AS AGEDAYS
FROM TRN_WHSTOCK
WHERE WH_ID = '$WH'
  AND S_QTY > 0
  AND (S_BARCODE LIKE '%$search%' 
       OR S_PRODUCTNAME LIKE '%$search%')
ORDER BY STOCK_ID DESC;
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


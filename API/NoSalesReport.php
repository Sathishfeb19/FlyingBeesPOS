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
    $query = "SELECT M.WH_NAME,
  IFNULL(DATEDIFF(CURDATE(), STR_TO_DATE(W.CREATED_DATE, '%d-%m-%Y %H:%i:%s')), 0) AS AGEDAYS,
  W.*, (W.S_QTY * W.S_SALERATE) AS NETVAL
FROM TRN_WHSTOCK W , MAS_WAREHOUSE M 
WHERE W.MAX_QTY = W.S_QTY AND M.WH_ID =W.WH_ID;
";
} else {
    $query = "SELECT M.WH_NAME,
  IFNULL(DATEDIFF(CURDATE(), STR_TO_DATE(W.CREATED_DATE, '%d-%m-%Y %H:%i:%s')), 0) AS AGEDAYS,
  W.*,(W.S_QTY * W.S_SALERATE) AS NETVAL
FROM TRN_WHSTOCK W , MAS_WAREHOUSE M 
WHERE W.MAX_QTY = W.S_QTY AND M.WH_ID = W.WH_ID AND W.WH_ID='$WH'
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




<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: POST, GET, DELETE, PUT");
header("Access-Control-Allow-Headers: Access-Control-Allow-Headers, Origin, Accept, X-Requested-With, Content-Type, Access-Control-Request-Method, Access-Control-Request-Headers");

include 'Config.php';

$resp = [];
$WH = $_POST['WH_ID'];
if ($WH == "all") {
    $query = "SELECT * FROM MAS_COMPANY 
          WHERE COMP_ID = '1';";
} else {
    $query = "SELECT * FROM MAS_WAREHOUSE
          WHERE WH_ID = '$WH';";
}  

if ($check = $DbCon->query($query)) {
    $result = $check->fetch_object();
    $resp = array_merge(["status" => "success"], (array)$result);
} else {
    $resp = ["status" => "error", "message" => "Query failed: " . $DbCon->error];
}

echo json_encode($resp);
mysqli_close($DbCon);

<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: POST, GET, DELETE, PUT");
header("Access-Control-Allow-Headers: Access-Control-Allow-Headers, Origin, Accept, X-Requested-With, Content-Type, Access-Control-Request-Method, Access-Control-Request-Headers");

include 'Config.php';

$resp = ["status" => "error", "message" => "No Data; "];
$id = $_POST['id'];
$WH = $_POST['WH'];
if ($WH == "all") {
    $query = "SELECT * FROM TRN_MAINSTOCK 
          WHERE STOCK_ID= '$id';";
} else {
    $query = "SELECT * FROM TRN_WHSTOCK 
          WHERE WH_ID='$WH' AND 
          STOCK_ID= '$id';";
}

if ($check = $DbCon->query($query)) {
    while ($result = $check->fetch_assoc()) {
        $resp = array_merge(["status" => "success"], $result);
    }
} else {
    $resp = ["status" => "error", "message" => "Query failed: " . $DbCon->error];
}

echo json_encode($resp);
mysqli_close($DbCon);

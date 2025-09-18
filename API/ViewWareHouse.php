<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: POST, GET, DELETE, PUT");
header("Access-Control-Allow-Headers: Access-Control-Allow-Headers, Origin, Accept, X-Requested-With, Content-Type, Access-Control-Request-Method, Access-Control-Request-Headers");

include 'Config.php';

$resp = [];
$query = "SELECT S.*,U.USER_NAME FROM MAS_WAREHOUSE S, MAS_USERS U WHERE U.USER_ID =S.CREATED_BY  ORDER BY WH_ID DESC;;";  

if ($check = $DbCon->query($query)) {
    while ($result = $check->fetch_assoc()) {
        $resp[] = array_merge(["status" => "success", "WH_CODE" => $result['WH_CODE']], $result);
    }
} else {
    $resp = ["status" => "error", "message" => "Query failed: " . $DbCon->error];
}

echo json_encode($resp);
mysqli_close($DbCon);
?>
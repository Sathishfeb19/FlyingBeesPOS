<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: POST, GET, DELETE, PUT");
header("Access-Control-Allow-Headers: Access-Control-Allow-Headers, Origin, Accept, X-Requested-With, Content-Type, Access-Control-Request-Method, Access-Control-Request-Headers");

include 'Config.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

$INSERTED_URL = $_POST['id'] ?? null;
$resp_status = new stdClass();

if (empty($INSERTED_URL) || $INSERTED_URL == 'undefined') { 
    $resp_status->status = 'error1';
} else {
    $check = $DbCon->query("SELECT S.* FROM MAS_SUPPLIER S WHERE S.INSERTED_URL = '$INSERTED_URL' AND SUPPLIER_STS !='DELETE';");

    if ($check && $check->num_rows > 0) {
        $result = $check->fetch_assoc();
        $resp_status = array_merge(["status" => "ok", "SUPPLIER_CODE" => $result['SUPPLIER_CODE']], $result);
    } else {
        $resp_status->status = 'error';
        $resp_status->message = 'No records found.';
    }
}

mysqli_close($DbCon);
echo json_encode($resp_status, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
?>
<?php
header("Access-Control-Allow-Origin:*");
header("Access-Control-Allow-Credentials:true");
header('Access-Control-Allow-Methods: POST, GET, DELETE, PUT');
header("Access-Control-Allow-Headers:Access-Control-Allow-Headers, Origin,Accept, X-Requested-With, Content-Type, Access-Control-Request-Method, Access-Control-Request-Headers");

include 'Config.php';

$resp = [];
$WH = $_POST['WH'];
$check = $DbCon->query("SELECT * FROM MAS_USERS P WHERE P.USER_STATUS ='A' 
AND P.BRANCH_CODE='$WH'
AND P.USER_TYPE=4 ORDER BY P.USER_ID DESC;");

for ($i = 0; $i < $check->num_rows; $i++) {
    $result = mysqli_fetch_assoc($check);
    $resp_status = new stdClass;

    $resp_status->status = 'success';
    $resp_status->ID = $result['USER_ID'];
    $resp_status->NAME  = $result['USER_NAME'];
    $resp_status->STATUS  = $result['USER_STATUS'];

    $resp[] = $resp_status;
}
echo json_encode($resp);
mysqli_close($DbCon);

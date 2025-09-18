<?php
header("Access-Control-Allow-Origin:*");
header("Access-Control-Allow-Credentials:true");
header('Access-Control-Allow-Methods: POST, GET, DELETE, PUT');
header("Access-Control-Allow-Headers:Access-Control-Allow-Headers, Origin,Accept, X-Requested-With, Content-Type, Access-Control-Request-Method, Access-Control-Request-Headers");

include 'Config.php';

$resp = [];
$check = $DbCon->query("SELECT * FROM MAS_USERTYPE P WHERE P.TYPE_STATUS ='A' AND P.USER_TYPEID !='1' AND P.USER_TYPEID !='5'  AND P.USER_TYPEID !='2' ;");

for ($i = 0; $i < $check->num_rows; $i++) {
    $result = mysqli_fetch_assoc($check);
    $resp_status = new stdClass;

    $resp_status->status = 'success';
    $resp_status->TYPE_NAME = $result['TYPE_NAME'];
    $resp_status->USER_TYPEID  = $result['USER_TYPEID'];
   
    $resp[] = $resp_status;
}

echo json_encode($resp);
mysqli_close($DbCon);
?>

<?php
header("Access-Control-Allow-Origin:*");
header("Access-Control-Allow-Credentials:true");
header('Access-Control-Allow-Methods: POST, GET, DELETE, PUT');
header("Access-Control-Allow-Headers:Access-Control-Allow-Headers, Origin,Accept, X-Requested-With, Content-Type, Access-Control-Request-Method, Access-Control-Request-Headers");

include 'Config.php';

$resp = [];
$check = $DbCon->query("SELECT * FROM MAS_WAREHOUSE P WHERE P.WH_STATUS ='A' ORDER BY WH_ID DESC;"); 

for ($i = 0; $i < $check->num_rows; $i++) {
    $result = mysqli_fetch_assoc($check);
    $resp_status = new stdClass;

    $resp_status->status = 'success';
    $resp_status->WH_ID = $result['WH_ID'];
    $resp_status->WH_NAME  = $result['WH_NAME'];
    $resp_status->WH_STATUS  = $result['WH_STATUS'];
   
    $resp[] = $resp_status;
}
echo json_encode($resp);
mysqli_close($DbCon);
?>

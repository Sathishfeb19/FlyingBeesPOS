<?php
header("Access-Control-Allow-Origin:*");
header("Access-Control-Allow-Credentials:true");
header('Access-Control-Allow-Methods: POST, GET, DELETE, PUT');
header("Access-Control-Allow-Headers:Access-Control-Allow-Headers, Origin,Accept, X-Requested-With, Content-Type, Access-Control-Request-Method, Access-Control-Request-Headers");

include 'Config.php';

$resp = [];
$check = $DbCon->query("SELECT T.TYPE_NAME,U.*,W.WH_NAME,W.WH_CODE FROM MAS_USERS U, MAS_USERTYPE T,MAS_WAREHOUSE W WHERE W.WH_ID =U.BRANCH_CODE AND T.USER_TYPEID =U.USER_TYPE AND U.USER_ID!='1' ORDER BY U.USER_ID DESC ; ");

for ($i = 0; $i < $check->num_rows; $i++) {
    $result = mysqli_fetch_assoc($check);
    $resp_status = new stdClass;

    $resp_status->status = 'success';
    $resp_status->USER_CODE = $result['USER_CODE'];
    $resp_status->USER_NAME = $result['USER_NAME'];
    $resp_status->USER_MOBILE = $result['USER_MOBILE'];
    $resp_status->USER_DOJ = $result['USER_DOJ']; 
    $resp_status->USER_TYPE = $result['TYPE_NAME'];
    $resp_status->CREATED_DATE = $result['CREATED_DATE'];
    $resp_status->CREATED_BY = $result['USER_ID'];
    $resp_status->USER_STATUS = $result['USER_STATUS'];
    $resp_status->WH_NAME = $result['WH_NAME'];
    $resp_status->WH_CODE = $result['WH_CODE'];
    $resp_status->LAST_LOGIN = $result['LAST_LOGIN'];

    $resp_status->INSERTED_URL = $result['INSERTED_URL']; 
    $resp[] = $resp_status;
}

echo json_encode($resp);
mysqli_close($DbCon);
?>

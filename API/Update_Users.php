<?php
header("Access-Control-Allow-Origin:*");
header("Access-Control-Allow-Credentials:true");
header('Access-Control-Allow-Methods: POST, GET, DELETE, PUT');
header("Access-Control-Allow-Headers:Access-Control-Allow-Headers, Origin,Accept, X-Requested-With, Content-Type, Access-Control-Request-Method, Access-Control-Request-Headers");

include 'Config.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

$INSERTED_URL = $_POST['id'] ?? null;
$resp_status = new stdClass();

if (empty($INSERTED_URL) || $INSERTED_URL == 'undefined') {
    $resp_status->status = 'error1';
} else {
    $check = $DbCon->query("SELECT E.*,S.WH_NAME FROM MAS_USERS E, MAS_USERTYPE U,MAS_WAREHOUSE S WHERE S.WH_ID =E.BRANCH_CODE AND U.USER_TYPEID=E.USER_TYPE AND E.INSERTED_URL='$INSERTED_URL';");

    if ($check && $check->num_rows > 0) {
        $result = mysqli_fetch_assoc($check);
        $resp_status->status = 'ok';
        $resp_status->USER_NAME = $result['USER_NAME'];
        $resp_status->USER_ID = $result['USER_ID'];
        $resp_status->USER_EMAIL = $result['USER_EMAIL'];
        $resp_status->USER_MOBILE = $result['USER_MOBILE'];
        $resp_status->USER_DOJ = $result['USER_DOJ']; 
        $resp_status->BRANCH_CODE = $result['BRANCH_CODE']; 
        $resp_status->USER_DOB = $result['USER_DOB'];
        $resp_status->USER_STATUS = $result['USER_STATUS'];
        $resp_status->USER_TYPE = $result['USER_TYPE'];
        $resp_status->USER_AADHAR = $result['USER_AADHAR'];
        $resp_status->USER_EMGMOBILE = $result['USER_EMGMOBILE'];
        $resp_status->USER_VEHICLE = $result['USER_VEHICLE'];
        $resp_status->INSERTED_URL = $result['INSERTED_URL'];
    } else {
        $resp_status->status = 'error';
        $resp_status->message = 'No records found.';
    }
}

mysqli_close($DbCon);
echo json_encode($resp_status, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
?>

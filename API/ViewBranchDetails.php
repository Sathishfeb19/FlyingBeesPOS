<?php
header("Access-Control-Allow-Origin:*");
header("Access-Control-Allow-Credentials:true");
header('Access-Control-Allow-Methods: POST, GET, DELETE, PUT');
header("Access-Control-Allow-Headers: Access-Control-Allow-Headers, Origin, Accept, X-Requested-With, Content-Type, Access-Control-Request-Method, Access-Control-Request-Headers");

include 'Config.php';

$resp = [];
$token = $_POST["token"];

$userQuery = $DbCon->query("SELECT USER_ID FROM MAS_USERS WHERE USER_TOKEN = '$token';");
if ($userQuery->num_rows > 0) {
    $user = mysqli_fetch_assoc($userQuery);
    $userId = $user['USER_ID'];

    $check = $DbCon->query("SELECT W.*,U.USER_NAME,C.* FROM MAS_WAREHOUSE W, MAS_USERS U,MAS_COMPANY C WHERE C.COMP_ID =W.COMPANY_CODE AND U.BRANCH_CODE =W.WH_ID AND U.USER_ID ='$userId';");
    while ($result = mysqli_fetch_assoc($check)) {
        $resp_status = new stdClass;
        $resp_status->status = 'success';
        $resp_status->WH_NAME = $result['WH_NAME'];
        $resp_status->WH_ADDRESS = $result['WH_ADDRESS'];
        $resp_status->WH_ADDRESS1 = $result['WH_ADDRESS1'];
        $resp_status->WH_CITY = $result['WH_CITY'];
        $resp_status->WH_STATE = $result['WH_STATE'];
        $resp_status->WH_PINCODE = $result['WH_PINCODE'];
        $resp_status->WH_GSTNO = $result['WH_GSTNO'];
        $resp_status->COMPANY_NAME = $result['COMPANY_NAME'];
        $resp_status->COMP_EMAIL = $result['COMP_EMAIL'];
        $resp_status->COMP_WEBSITE = $result['COMP_WEBSITE'];
        $resp_status->COMP_REGNO = $result['COMP_REGNO'];
        $resp_status->COMP_MOBILE = $result['COMP_MOBILE'];

        $resp[] = $resp_status;
    }

} else {
    $resp[] = ['status' => 'error', 'message' => 'User not found'];
}

echo json_encode($resp);
mysqli_close($DbCon);
?>

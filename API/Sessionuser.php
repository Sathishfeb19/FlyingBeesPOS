<?php
header("Access-Control-Allow-Origin:*");
header("Access-Control-Allow-Credentials:true");
header('Access-Control-Allow-Methods: POST, GET, DELETE, PUT');
header("Access-Control-Allow-Headers:Access-Control-Allow-Headers, Origin,Accept, X-Requested-With, Content-Type, Access-Control-Request-Method, Access-Control-Request-Headers");


if($_POST['token']!=''){
include 'Config.php';

$resp_status = new stdClass;

$token=$_POST['token'];

$check=$DbCon->query("SELECT * from MAS_USERS U, MAS_USERTYPE T where T.USER_TYPEID =U.USER_TYPE AND USER_TOKEN='$token';");
if($check->num_rows>0){
    $result=mysqli_fetch_assoc($check);

    $resp_status->status='ok';
    $resp_status->USERNAME=$result['USER_NAME'];
    $resp_status->USER_TYPE=$result['USER_TYPE'];
    $resp_status->TYPE_NAME=$result['TYPE_NAME'];
    $resp_status->INSERTED_URL=$result['INSERTED_URL'];
    echo json_encode($resp_status);
}

else{
    $resp_status->status = 'error';
	$resp_status->errorcode = 'missingparameter';
	$resp_status->message = 'invalid request';
	$resp_status->debuginfo = 'kindly check request parameters';
    echo json_encode($resp_status);
}
// echo $testing;
mysqli_close($DbCon);
}
?>
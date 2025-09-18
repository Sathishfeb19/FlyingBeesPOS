<?php
header("Access-Control-Allow-Origin:*");
header("Access-Control-Allow-Credentials:true");
header('Access-Control-Allow-Methods: POST, GET, DELETE, PUT');
header("Access-Control-Allow-Headers:Access-Control-Allow-Headers, Origin,Accept, X-Requested-With, Content-Type, Access-Control-Request-Method, Access-Control-Request-Headers");

// $check=file_get_contents('php://input');
if($_POST['token']!=''){
include 'Config.php';
//$check=json_decode($check);
$resp_status = new stdClass;

$token=$_POST['token'];

$check=$DbCon->query("SELECT * from MAS_USERS where USER_TOKEN='$token';");

if($check->num_rows>0){
    $result=mysqli_fetch_assoc($check);

    $resp_status->status='ok';

    echo json_encode($resp_status);
}

else{
    $resp_status->status = 'error';
	$resp_status->errorcode = 'missingparameter';
	$resp_status->message = 'invalid request';
	$resp_status->debuginfo = 'kindly check request parameters';
    echo json_encode($resp_status);
}
mysqli_close($DbCon);
}
?>
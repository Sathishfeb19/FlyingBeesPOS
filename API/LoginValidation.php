<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header('Access-Control-Allow-Methods: POST, GET, DELETE, PUT');
header("Access-Control-Allow-Headers: Access-Control-Allow-Headers, Origin, Accept, X-Requested-With, Content-Type, Access-Control-Request-Method, Access-Control-Request-Headers");

if (isset($_POST['email']) && isset($_POST['password']) && !empty($_POST['email']) && !empty($_POST['password'])) {
    include 'Config.php';

    date_default_timezone_set('Asia/Kolkata');
    $date = date('d-m-Y H:i:s');
    $ipaddress = $_SERVER['REMOTE_ADDR'];

    $resp_status = new stdClass;

    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $DbCon->prepare("SELECT X.*,Y.TYPE_NAME,Y.PERMISSIONS,Y.HOMEPAGE
     FROM MAS_USERS X, MAS_USERTYPE Y 
     WHERE X.USER_STATUS ='A' AND X.USER_TYPE=Y.USER_TYPEID AND (X.USER_EMAIL = ? OR X.USER_MOBILE = ?) AND X.USER_PASSWORD = ?");
    $stmt->bind_param("sss", $email, $email, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        $resp_status->status = 'Success';
        $resp_status->email = $user['INSERTED_URL'];
        $token = hash('sha512', $user['INSERTED_URL'] . date('Y-m-d'));
        $resp_status->token = $token;
        $resp_status->PERMISSIONS = $user['PERMISSIONS'];
        $resp_status->HOMEPAGE = $user['HOMEPAGE'];
        $resp_status->TYPE_NAME = $user['TYPE_NAME'];
        $resp_status->USER_NAME = $user['USER_NAME'];
        $resp_status->USER_TYPE = $user['USER_TYPE'];
        $resp_status->INSERTED_URL = $user['INSERTED_URL'];
        $resp_status->WH_ID = $user['BRANCH_CODE'];
        echo json_encode($resp_status);

        $stmt = $DbCon->prepare("UPDATE MAS_USERS SET USER_TOKEN = ?, USER_IP = ?, LAST_LOGIN = ? WHERE USER_ID = ?");
        $stmt->bind_param("ssss", $token, $ipaddress, $date, $user['USER_ID']);
        $stmt->execute();
    } else {
        $resp_status->status = 'Error';
        $resp_status->errorcode = 'Invalid Credentials';
        $resp_status->message = 'Invalid email or password';
        echo json_encode($resp_status);
    }
    $stmt->close();
    mysqli_close($DbCon);
} else {
    // Handle missing parameters
    $resp_status = new stdClass;
    $resp_status->status = 'Error';
    $resp_status->errorcode = 'Missing Parameter';
    $resp_status->message = 'Email or password missing';
    echo json_encode($resp_status);
}

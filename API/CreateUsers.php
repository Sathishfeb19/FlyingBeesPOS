<?php
header("Access-Control-Allow-Origin:*");
header("Access-Control-Allow-Credentials:true");
header('Access-Control-Allow-Methods: POST, GET, DELETE, PUT');
header("Access-Control-Allow-Headers:Access-Control-Allow-Headers, Origin,Accept, X-Requested-With, Content-Type, Access-Control-Request-Method, Access-Control-Request-Headers");

include 'Config.php';
date_default_timezone_set('Asia/Kolkata');
$DATE = date('d-m-Y H:i:s');

$resp_status = new stdClass();

if (isset($_POST['USER_NAME'])) {
    $INSERTED_URL = isset($_POST['INSERTED_URL']) ? $_POST['INSERTED_URL'] : null;
    $USER_MOBILE = $_POST["USER_MOBILE"];

    //check if mobile number already there
    $check1 = $DbCon->query("SELECT USER_ID FROM MAS_USERS WHERE USER_MOBILE = '$USER_MOBILE' AND INSERTED_URL !='$INSERTED_URL';");
    if ($check1->num_rows == 0) {
        $token = $_POST["token"];
        $USER_NAME = $_POST["USER_NAME"];
        $USER_TYPE = $_POST["USER_TYPE"]; 
        $BRANCH_CODE = $_POST["BRANCH_CODE"];
        $USER_DOJ = $_POST["USER_DOJ"];
        $USER_DOB = $_POST["USER_DOB"];
        $USER_EMGMOBILE = $_POST["USER_EMGMOBILE"];
        $USER_EMAIL = $_POST["USER_EMAIL"];
        $USER_AADHAR = $_POST["USER_AADHAR"];
        $USER_VEHICLE = $_POST["USER_VEHICLE"];
        $USER_STATUS = isset($_POST['USER_STATUS']) ? $_POST['USER_STATUS'] : null;
        // Get creator ID
        $user = $DbCon->query("SELECT USER_ID FROM MAS_USERS WHERE USER_TOKEN = '$token'");
        $CREATED = ($user->num_rows > 0) ? mysqli_fetch_assoc($user)['USER_ID'] : "UNKNOWN";
        // Check if mobile number exists
        $check = $DbCon->query("SELECT USER_ID FROM MAS_USERS WHERE INSERTED_URL='$INSERTED_URL';");

        if ($check->num_rows > 0) {
            $existingUser = $check->fetch_assoc();
            $existingUserId = $existingUser['USER_ID'];

            // If user exists, only allow updates for the same record
            $updateQuery = "UPDATE MAS_USERS SET 
                USER_NAME = '$USER_NAME', 
                USER_EMAIL = '$USER_EMAIL', 
                USER_MOBILE = '$USER_MOBILE', 
                USER_DOB = '$USER_DOB', 
                USER_DOJ = '$USER_DOJ', 
                USER_TYPE = '$USER_TYPE', 
                BRANCH_CODE = '$BRANCH_CODE', 
                USER_AADHAR = '$USER_AADHAR', 
                USER_STATUS ='$USER_STATUS',
                USER_EMGMOBILE = '$USER_EMGMOBILE', 
                USER_VEHICLE = '$USER_VEHICLE', 
                UPDATED_BY = '$CREATED', 
                UPDATED_DATE = '$DATE'
                WHERE USER_ID = '$existingUserId'";

            if ($DbCon->query($updateQuery) === TRUE) {
                $resp_status->status = 'Success';
                $resp_status->message = 'User details updated successfully';
            } else {
                $resp_status->status = 'error';
                $resp_status->message = 'Failed to update user details';
            }
        } else {
            // Generate USER_CODE
            $result = $DbCon->query("SELECT MAX(USER_CODE) AS max_code FROM MAS_USERS");
            $row = $result->fetch_assoc();
            $USER_CODE = $row['max_code'] ? 'USER' . str_pad((int)substr($row['max_code'], 4) + 1, 4, '0', STR_PAD_LEFT) : 'USER0001';

            // Insert new user
            $INSERTED_URL = hash('crc32b', $USER_MOBILE . date('Y-m-d'));
            $insertQuery = "INSERT INTO MAS_USERS (USER_CODE, USER_NAME, USER_EMAIL, USER_PASSWORD, USER_DOB, USER_DOJ, USER_STATUS, CREATED_BY, CREATED_DATE, USER_TYPE,BRANCH_CODE, USER_MOBILE, USER_AADHAR, USER_EMGMOBILE, USER_VEHICLE, INSERTED_URL) 
                            VALUES ('$USER_CODE', '$USER_NAME', '$USER_EMAIL', '$USER_MOBILE', '$USER_DOB', '$USER_DOJ', '$USER_STATUS', '$CREATED', '$DATE', '$USER_TYPE','$BRANCH_CODE', '$USER_MOBILE', '$USER_AADHAR', '$USER_EMGMOBILE', '$USER_VEHICLE', '$INSERTED_URL')";

            if ($DbCon->query($insertQuery) === TRUE) {
                $resp_status->status = 'Success';
                $resp_status->message = 'User registered successfully';
            } else {
                $resp_status->status = 'error';
                $resp_status->message = 'Failed to register user';
            }
        }
    } else {
        $resp_status->status = 'error';
        $resp_status->message = 'Mobile Number already Registered';
    }

    echo json_encode($resp_status);
    mysqli_close($DbCon);
}

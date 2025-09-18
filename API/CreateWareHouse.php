<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header('Access-Control-Allow-Methods: POST, GET, DELETE, PUT');
header("Access-Control-Allow-Headers: Access-Control-Allow-Headers, Origin,Accept, X-Requested-With, Content-Type, Access-Control-Request-Method, Access-Control-Request-Headers");

include 'Config.php';
date_default_timezone_set('Asia/Kolkata');
$DATE = date('d-m-Y H:i:s');

$resp_status = new stdClass();

if (isset($_POST['WH_NAME'])) {
    $WH_NAME = $_POST["WH_NAME"];
    $WH_TYPE = $_POST["WH_TYPE"];
    $INSERTED_URL = $_POST['INSERTED_URL'] ?? null;

    // Check if WH_NAME already exists
    $checkName = $DbCon->prepare("SELECT WH_ID FROM MAS_WAREHOUSE WHERE WH_NAME = ? AND WH_TYPE = ?");
    $checkName->bind_param("ss", $WH_NAME, $WH_TYPE);
    $checkName->execute();
    $checkName->store_result();

    if ($checkName->num_rows > 0) {
        $resp_status->status = 'error';
        $resp_status->message = 'Warehouse Name already exists!';
    } else {
        // Proceed with insert/update
        $WH_CODE = $_POST["WH_CODE"] ?? null;
        $WH_ADDRESS = $_POST["WH_ADDRESS"];
        $WH_ADDRESS1 = $_POST["WH_ADDRESS1"];
        $WH_CITY = $_POST["WH_CITY"];
        $WH_MOBILE = $_POST["WH_MOBILE"];
        $WH_STATE = $_POST["WH_STATE"];
        $WH_PINCODE = $_POST["WH_PINCODE"];
        $WH_PERSON = $_POST["WH_PERSON"];
        $WH_STATUS = $_POST["WH_STATUS"];
        $WH_DESC = $_POST["WH_DESC"];
        $token = $_POST["token"];

        // Get creator ID from token
        $user = $DbCon->prepare("SELECT USER_ID FROM MAS_USERS WHERE USER_TOKEN = ?");
        $user->bind_param("s", $token);
        $user->execute();
        $userResult = $user->get_result();
        $CREATED = ($userResult->num_rows > 0) ? $userResult->fetch_assoc()['USER_ID'] : "UNKNOWN";

        // Check if warehouse exists by INSERTED_URL
        $check = $DbCon->prepare("SELECT WH_ID FROM MAS_WAREHOUSE WHERE INSERTED_URL = ?");
        $check->bind_param("s", $INSERTED_URL);
        $check->execute();
        $checkResult = $check->get_result();

        if ($checkResult->num_rows > 0) {
            $existingWH = $checkResult->fetch_assoc();
            $existingWHId = $existingWH['WH_ID'];

            // Update warehouse details
            $updateQuery = "UPDATE MAS_WAREHOUSE SET 
                WH_NAME = ?, WH_ADDRESS = ?, WH_ADDRESS1 = ?, WH_CITY = ?, WH_STATE = ?, 
                WH_PINCODE = ?, WH_PERSON = ?, WH_MOBILE = ?, WH_TYPE = ?, 
                WH_STATUS = ?, WH_DESC = ?, UPDATED_BY = ?, UPDATED_DATE = ? 
                WHERE WH_ID = ?";

            $stmt = $DbCon->prepare($updateQuery);
            $stmt->bind_param("sssssssssssssi", 
                $WH_NAME, $WH_ADDRESS, $WH_ADDRESS1, $WH_CITY, $WH_STATE, 
                $WH_PINCODE, $WH_PERSON, $WH_MOBILE, $WH_TYPE, 
                $WH_STATUS, $WH_DESC, $CREATED, $DATE, $existingWHId
            );

            if ($stmt->execute()) {
                $resp_status->status = 'Success';
                $resp_status->message = 'Warehouse details updated successfully';
            } else {
                $resp_status->status = 'error';
                $resp_status->message = 'Failed to update warehouse details';
            }
        } else {
            // Generate WH_CODE if not provided
            if (!$WH_CODE) {
                $result = $DbCon->query("SELECT MAX(WH_CODE) AS max_code FROM MAS_WAREHOUSE");
                $row = $result->fetch_assoc();
                $WH_CODE = $row['max_code'] ? 'WH' . str_pad((int)substr($row['max_code'], 2) + 1, 5, '0', STR_PAD_LEFT) : 'WH00001';
            }

            // Generate INSERTED_URL
            $INSERTED_URL = hash('crc32b', $WH_NAME . date('Y-m-d'));

            // Insert new warehouse
            $insertQuery = "INSERT INTO MAS_WAREHOUSE 
                (WH_CODE, WH_NAME, WH_ADDRESS, WH_ADDRESS1, WH_CITY, WH_STATE, WH_PINCODE, WH_PERSON, 
                WH_MOBILE, WH_TYPE, WH_STATUS, WH_DESC, CREATED_BY, CREATED_DATE, INSERTED_URL) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

            $stmt = $DbCon->prepare($insertQuery);
            $stmt->bind_param("sssssssssssssss", 
                $WH_CODE, $WH_NAME, $WH_ADDRESS, $WH_ADDRESS1, $WH_CITY, 
                $WH_STATE, $WH_PINCODE, $WH_PERSON, $WH_MOBILE, 
                $WH_TYPE, $WH_STATUS, $WH_DESC, $CREATED, $DATE, $INSERTED_URL
            );

            if ($stmt->execute()) {
                $resp_status->status = 'Success';
                $resp_status->message = 'Warehouse registered successfully';
            } else {
                $resp_status->status = 'error';
                $resp_status->message = 'Failed to register warehouse';
            }
        }
    }

    echo json_encode($resp_status);
    $DbCon->close();
}
?>

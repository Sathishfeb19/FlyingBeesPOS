<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: POST, GET, DELETE, PUT");
header("Access-Control-Allow-Headers: Origin, Accept, X-Requested-With, Content-Type, Access-Control-Request-Method, Access-Control-Request-Headers");

include 'Config.php';
date_default_timezone_set('Asia/Kolkata');
$date = date('d-m-Y H:i:s');

$token = $_POST["token"] ?? null;
$PROD_NAME = trim($_POST["PROD_NAME"] ?? '');
$PROD_CODE = $_POST["PROD_CODE"] ?? null;
$PROD_STATUS = $_POST["PROD_STATUS"] ?? 'Inactive';
$PROD_DESC = $_POST["PROD_DESC"] ?? '';
$INSERTED_URL = $_POST["id"] ?? null;

$resp_status = new stdClass();

// ✅ Get User ID from Token
$userQuery = $DbCon->prepare("SELECT USER_ID FROM MAS_USERS WHERE USER_TOKEN = ?");
$userQuery->bind_param("s", $token);
$userQuery->execute();
$userResult = $userQuery->get_result();
$created = ($userResult->num_rows > 0) ? $userResult->fetch_assoc()['USER_ID'] : "UNKNOWN";

error_log("Received Data: PROD_NAME=$PROD_NAME, INSERTED_URL=$INSERTED_URL");

if (empty($INSERTED_URL) || $INSERTED_URL === 'undefined') {

    $check = $DbCon->prepare("SELECT ID FROM MAS_PRODUCT WHERE PROD_NAME = ?");
    $check->bind_param("s", $PROD_NAME);
    $check->execute();
    if ($check->get_result()->num_rows > 0) {
        exit(json_encode(["status" => "error", "message" => "Product already exists"]));
    }

    if (!$PROD_CODE) {
        $codeQuery = $DbCon->query("SELECT MAX(CAST(SUBSTRING(PROD_CODE, 5, 4) AS UNSIGNED)) AS max_code FROM MAS_PRODUCT WHERE PROD_CODE LIKE 'PROD%'");
        $next_code = ($codeQuery->fetch_assoc()['max_code'] ?? 0) + 1;
        $PROD_CODE = 'PROD' . str_pad($next_code, 4, '0', STR_PAD_LEFT);
    }


    $INSERTED_URL = hash('sha512', $PROD_NAME . $date);

    $stmt = $DbCon->prepare("INSERT INTO MAS_PRODUCT (PROD_NAME, PROD_CODE, PROD_DESC, PROD_STATUS, CREATED_DATE, CREATED_BY, INSERTED_URL) 
                             VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $PROD_NAME, $PROD_CODE, $PROD_DESC, $PROD_STATUS, $date, $created, $INSERTED_URL);
} else {
    $check = $DbCon->prepare("SELECT ID FROM MAS_PRODUCT WHERE INSERTED_URL = ?");
    $check->bind_param("s", $INSERTED_URL);
    $check->execute();
    if ($check->get_result()->num_rows == 0) {
        exit(json_encode(["status" => "error", "message" => "Product not found with the provided INSERTED_URL."]));
    }

    $stmt = $DbCon->prepare("UPDATE MAS_PRODUCT SET PROD_NAME=?, PROD_CODE=?, PROD_DESC=?, PROD_STATUS=?, UPDATED_BY=?, UPDATED_DATE=? WHERE INSERTED_URL=?");
    $stmt->bind_param("sssssss", $PROD_NAME, $PROD_CODE, $PROD_DESC, $PROD_STATUS, $created, $date, $INSERTED_URL);
}

if ($stmt->execute()) {
    $resp_status->status = "Success";
    $resp_status->message = empty($_POST["id"]) ? "Product registered successfully" : "Product updated successfully";
} else {
    error_log("SQL Error: " . $stmt->error);
    $resp_status->status = "error";
    $resp_status->message = "Database error";
}

$DbCon->close();
echo json_encode($resp_status);
?>

<?php
header("Access-Control-Allow-Origin:*");
header("Access-Control-Allow-Credentials:true");
header('Access-Control-Allow-Methods: POST, GET, DELETE, PUT');
header("Access-Control-Allow-Headers: Origin, Accept, X-Requested-With, Content-Type, Access-Control-Request-Method, Access-Control-Request-Headers");

include 'Config.php';
date_default_timezone_set('Asia/Kolkata');
$date = date('Y-m-d H:i:s');
$token = $_POST['token'];
$WH_ID = $_POST['WH_ID'];
$O_BARCODE = $_POST['O_BARCODE'];
$QTY = $_POST['QTY'];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['INSERTED_URL'])) {

    $INSERTED_URL = trim($_POST["INSERTED_URL"]);
    $resp_status = new stdClass();

    // Ensure Database Connection is Available
    if (!$DbCon) {
        $resp_status->status = 'error';
        $resp_status->message = 'Database connection failed!';
        echo json_encode($resp_status);
        exit;
    }

    // Check if INSERTED_URL exists in TRN_RETURNS
    $stmt = $DbCon->prepare("SELECT * FROM TRN_RETURNS WHERE INSERTED_URL = ?");
    $stmt->bind_param("s", $INSERTED_URL);
    $stmt->execute();
    $check = $stmt->get_result();

    if ($check->num_rows > 0) {
        // Fetch USER_ID using token
        $stmt = $DbCon->prepare("SELECT USER_ID FROM MAS_USERS WHERE USER_TOKEN = ?");
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $user = $stmt->get_result();

        if ($user->num_rows > 0) {
            $curruser = $user->fetch_assoc();
            $USER_ID = $curruser['USER_ID'];
        } else {
            $USER_ID = "BEES"; // Default user if not found
        }

        // Update TRN_RETURNS table
        $stmt = $DbCon->prepare("UPDATE TRN_RETURNS SET RETURN_STATUS = 'A', UPDATED_BY = ?, UPDATED_DATE = ? WHERE INSERTED_URL = ?");

        $query2 = "SELECT * FROM TRN_WHSTOCK  WHERE WH_ID = '$WH_ID' AND 
        S_BARCODE='$O_BARCODE';";
        $check2 = $DbCon->query($query2);
        if ($check2->num_rows) {
            $query3 = "UPDATE TRN_WHSTOCK SET S_QTY=S_QTY + $QTY, UPDATED_BY = '$USER_ID', UPDATED_DATE = '$date' WHERE WH_ID = '$WH_ID' AND 
        S_BARCODE='$O_BARCODE';";
            if (!$DbCon->query($query3)) {
                throw new Exception("Error updating in STOCK table: " . $DbCon->error);
            }
        }

        $stmt1 = $DbCon->prepare("UPDATE TRN_WHSTOCK SET S_QTY=S_QTY + $QTY, UPDATED_BY = ?, UPDATED_DATE = ? WHERE WH_ID = '$WH_ID' AND 
        S_BARCODE='$O_BARCODE';");
        $stmt->bind_param("sss", $USER_ID, $date, $INSERTED_URL);

        if ($stmt->execute()) {
            $resp_status->status = 'Success';
        } else {
            $resp_status->status = 'error';
            $resp_status->message = 'Update failed: ' . $stmt->error;
        }
    } else {
        // If INSERTED_URL is not found
        $resp_status->status = 'error';
        $resp_status->errorcode = 'not_found';
        $resp_status->message = 'INSERTED_URL not found in TRN_RETURNS';
    }

    echo json_encode($resp_status);
    $DbCon->close();
} else {
    // Invalid request method or missing parameters
    $resp_status = [
        'status' => 'error',
        'message' => 'Invalid request or missing parameters'
    ];
    echo json_encode($resp_status);
}

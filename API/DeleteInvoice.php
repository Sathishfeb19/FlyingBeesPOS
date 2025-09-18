<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: POST, GET, DELETE, PUT");
header("Access-Control-Allow-Headers: Access-Control-Allow-Headers, Origin, Accept, X-Requested-With, Content-Type, Access-Control-Request-Method, Access-Control-Request-Headers");

include 'Config.php';

$resp = ["status" => "error", "message" => "failed to Update;"];
$id = $_POST['id'];
$wh_id = 'all';

// Get creator ID from token
$token = $_POST['token'];
$user = $DbCon->prepare("SELECT USER_ID FROM MAS_USERS WHERE USER_TOKEN = ?");
$user->bind_param("s", $token);
$user->execute();
$userResult = $user->get_result();
$CREATED = ($userResult->num_rows > 0) ? $userResult->fetch_assoc()['USER_ID'] : "UNKNOWN";
$DATE = date('d-m-Y H:i:s');

// **Begin Transaction**
$DbCon->begin_transaction();

try {
    $query = "UPDATE STK_TRANSACTION SET TRN_STATUS='I',UPDATED_BY = '$CREATED', UPDATED_DATE = '$DATE' WHERE TRN_ID = '$id';";
    if (!$DbCon->query($query)) {
        throw new Exception("Error updating in STOCK table: " . $DbCon->error);
    }
    $query = "UPDATE STK_TRANSDETAILS SET TRN_STATUS='I',UPDATED_BY = '$CREATED', UPDATED_DATE = '$DATE' WHERE TRNS_ID = '$id';";
    if (!$DbCon->query($query)) {
        throw new Exception("Error updating in STOCK table: " . $DbCon->error);
    }
    $query = "SELECT WH_FROMID FROM STK_TRANSACTION WHERE TRN_ID = '$id';";
    if ($check = $DbCon->query($query)) {
        while ($result = $check->fetch_assoc()) {
            $wh_id = $result['WH_FROMID'];
        }
    }
    $query = "SELECT QR_CODE,TRN_QTY FROM STK_TRANSDETAILS WHERE TRNS_ID = '$id';";
    if ($check = $DbCon->query($query)) {
        while ($result = $check->fetch_assoc()) {
            if ($wh_id == 'all') {
                $query = "UPDATE TRN_MAINSTOCK SET S_QTY=S_QTY + $result[TRN_QTY],UPDATED_BY = '$CREATED', UPDATED_DATE = '$DATE' WHERE S_BARCODE = '$result[QR_CODE]';";
            } else {
                $query = "UPDATE TRN_WHSTOCK SET S_QTY=S_QTY + $result[TRN_QTY],UPDATED_BY = '$CREATED', UPDATED_DATE = '$DATE' WHERE S_BARCODE = '$result[QR_CODE]'
                AND WH_ID='$wh_id';";
            }
            if (!$DbCon->query($query)) {
                throw new Exception("Error updating in STOCK table: " . $DbCon->error);
            }
        }
    }
    // **Commit transaction if all queries succeed**
    $DbCon->commit();
    $resp = ["status" => "success", "message" => "Transaction completed successfully."];
} catch (Exception $e) {
    // **Rollback transaction if any query fails**
    $DbCon->rollback();
    $resp = ["status" => "error", "message" => $e->getMessage()];
}

echo json_encode($resp);
mysqli_close($DbCon);

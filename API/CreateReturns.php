<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: POST, GET, DELETE, PUT");
header("Access-Control-Allow-Headers: Origin, Accept, X-Requested-With, Content-Type");

include 'Config.php';
date_default_timezone_set('Asia/Kolkata');
$DATE = date('d-m-Y H:i:s');
$resp_status = new stdClass();

if (isset($_POST['O_BARCODE'])) {
    $INSERTED_URL = $_POST['INSERTED_URL'] ?? null;
    $N_INVOICENO = $_POST['N_INVOICENO'];

    $invoiceCheck = $DbCon->query("SELECT N_INVOICENO FROM TRN_RETURNS WHERE N_INVOICENO = '$N_INVOICENO' AND INSERTED_URL != '$INSERTED_URL'");
    if ($invoiceCheck->num_rows == 0) {
        $token = $_POST['token'];
        $userRow = $DbCon->query("SELECT USER_ID, BRANCH_CODE FROM MAS_USERS WHERE USER_TOKEN = '$token'")->fetch_assoc() ?? [];
        $CREATED = $userRow['USER_ID'] ?? 'UNKNOWN';
        $BRANCH_CODE = $userRow['BRANCH_CODE'] ?? 'UNKNOWN';

        $check = $DbCon->query("SELECT USER_ID, BRANCH_CODE FROM MAS_USERS WHERE INSERTED_URL = '$INSERTED_URL'");
        if ($check->num_rows > 0) {
            $row = $check->fetch_assoc();
            $existingUserId = $row['USER_ID'];
            $BRANCH_CODE = $row['BRANCH_CODE'];
            $updateQuery = "UPDATE TRN_RETURNS SET 
                O_BARCODE='{$_POST['O_BARCODE']}', VALUE_PAID='{$_POST['VALUE_PAID']}', N_INVOICEDATE='{$_POST['N_INVOICEDATE']}',
                N_INVOICENO='$N_INVOICENO', O_INVOICENO='{$_POST['O_INVOICENO']}', O_INVOICEDATE='{$_POST['O_INVOICEDATE']}',
                REASON_RETURN='{$_POST['REASON_RETURN']}', N_BARCODE='{$_POST['N_BARCODE']}',
                INSERTED_URL='$INSERTED_URL', UPDATED_BY='$CREATED', UPDATED_DATE='$DATE'
                WHERE USER_ID='$existingUserId'";
            $resp_status->status = $DbCon->query($updateQuery) ? 'Success' : 'error';
            $resp_status->message = $resp_status->status === 'Success' ? 'User details updated successfully' : 'Failed to update user details';
        } else {
            $result = $DbCon->query("SELECT MAX(RETURN_CODE) AS max_code FROM TRN_RETURNS");
            $maxCode = $result->fetch_assoc()['max_code'] ?? null;
            $nextCode = $maxCode ? (int)substr($maxCode, 4) + 1 : 1;
            $RETURN_CODE = 'RETN' . str_pad($nextCode, 4, '0', STR_PAD_LEFT);
            $INSERTED_URL = hash('crc32b', $N_INVOICENO . date('Y-m-d'));

            $insertQuery = "INSERT INTO TRN_RETURNS (
                RETURN_CODE, WH_ID, O_BARCODE, VALUE_PAID, N_INVOICEDATE, N_INVOICENO, CREATED_BY, CREATED_DATE,
                O_INVOICENO, O_INVOICEDATE, REASON_RETURN, N_BARCODE, RETURN_STATUS, INSERTED_URL
            ) VALUES (
                '$RETURN_CODE', '$BRANCH_CODE', '{$_POST['O_BARCODE']}', '{$_POST['VALUE_PAID']}', '{$_POST['N_INVOICEDATE']}', 
                '$N_INVOICENO', '$CREATED', '$DATE', '{$_POST['O_INVOICENO']}', '{$_POST['O_INVOICEDATE']}', 
                '{$_POST['REASON_RETURN']}', '{$_POST['N_BARCODE']}', 'N', '$INSERTED_URL'
            )";
            $resp_status->status = $DbCon->query($insertQuery) ? 'Success' : 'error';
            $resp_status->message = $resp_status->status === 'Success' ? ' registered successfully' : 'Failed to register';
        }
    } else {
        $resp_status->status = 'error';
        $resp_status->message = 'This Invoice No Already Returned';
    }

    echo json_encode($resp_status);
    $DbCon->close();
}

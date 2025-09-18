<?php
header("Access-Control-Allow-Origin:*");
header("Access-Control-Allow-Credentials:true");
header('Access-Control-Allow-Methods: POST, GET, DELETE, PUT');
header("Access-Control-Allow-Headers:Access-Control-Allow-Headers, Origin,Accept, X-Requested-With, Content-Type, Access-Control-Request-Method, Access-Control-Request-Headers");

include 'Config.php';
date_default_timezone_set('Asia/Kolkata');
$DATE = date('d-m-Y H:i:s');

$resp_status = new stdClass();

if (isset($_POST['SUPPLIER_NAME'])) {
    $INSERTED_URL = isset($_POST['INSERTED_URL']) ? $_POST['INSERTED_URL'] : null;
    $SUPPLIER_MBL1 = $_POST["SUPPLIER_MBL1"];

    // Check if mobile number already exists
    $check1 = $DbCon->query("SELECT SUPPLIER_ID FROM mas_supplier WHERE SUPPLIER_MBL1 = '$SUPPLIER_MBL1' AND INSERTED_URL != '$INSERTED_URL';");
    if ($check1->num_rows == 0) {
        $token = $_POST["token"];
        $SUPPLIER_NAME = $_POST["SUPPLIER_NAME"];
        $SUPPLIER_CODE = $_POST["SUPPLIER_CODE"];
        $SUPPLIER_ADD1 = $_POST["SUPPLIER_ADD1"];
        $SUPPLIER_ADD2 = $_POST["SUPPLIER_ADD2"];
        $SUPPLIER_CITY = $_POST["SUPPLIER_CITY"];
        $SUPPLIER_STATE = $_POST["SUPPLIER_STATE"];
        $SUPPLIER_PINCODE = $_POST["SUPPLIER_PINCODE"];
        $SUPPLIER_EMAIL = $_POST["SUPPLIER_EMAIL"];
        $SUPPLIER_MBL2 = $_POST["SUPPLIER_MBL2"];
        $SUPPLIER_GST = $_POST["SUPPLIER_GST"];
        $SUPPLIER_ACCNO = $_POST["SUPPLIER_ACCNO"];
        $SUPPLIER_IFSC = $_POST["SUPPLIER_IFSC"];
        $SUPPLIER_STS = isset($_POST['SUPPLIER_STS']) ? $_POST['SUPPLIER_STS'] : null;
        $SUPPLIER_DESC = $_POST["SUPPLIER_DESC"];

        // Get creator ID
        $user = $DbCon->query("SELECT USER_ID FROM MAS_USERS WHERE USER_TOKEN = '$token'");
        $CREATED = ($user->num_rows > 0) ? mysqli_fetch_assoc($user)['USER_ID'] : "UNKNOWN";

        // Check if supplier already exists
        $check = $DbCon->query("SELECT SUPPLIER_ID FROM mas_supplier WHERE INSERTED_URL='$INSERTED_URL';");

        if ($check->num_rows > 0) {
            $existingSupplier = $check->fetch_assoc();
            $existingSupplierId = $existingSupplier['SUPPLIER_ID'];

            // Update existing supplier
            $updateQuery = "UPDATE mas_supplier SET 
                SUPPLIER_NAME = '$SUPPLIER_NAME', 
                SUPPLIER_CODE = '$SUPPLIER_CODE',
                SUPPLIER_ADD1 = '$SUPPLIER_ADD1', 
                SUPPLIER_ADD2 = '$SUPPLIER_ADD2', 
                SUPPLIER_CITY = '$SUPPLIER_CITY', 
                SUPPLIER_STATE = '$SUPPLIER_STATE', 
                SUPPLIER_PINCODE = '$SUPPLIER_PINCODE', 
                SUPPLIER_EMAIL = '$SUPPLIER_EMAIL', 
                SUPPLIER_MBL1 = '$SUPPLIER_MBL1', 
                SUPPLIER_MBL2 = '$SUPPLIER_MBL2', 
                SUPPLIER_GST = '$SUPPLIER_GST', 
                SUPPLIER_ACCNO = '$SUPPLIER_ACCNO', 
                SUPPLIER_IFSC = '$SUPPLIER_IFSC', 
                SUPPLIER_STS = '$SUPPLIER_STS', 
                SUPPLIER_DESC = '$SUPPLIER_DESC',
                UPDATED_BY = '$CREATED', 
                UPDATED_DATE = '$DATE'
                WHERE SUPPLIER_ID = '$existingSupplierId'";

            if ($DbCon->query($updateQuery) === TRUE) {
                $resp_status->status = 'Success';
                $resp_status->message = 'Supplier details updated successfully';
            } else {
                $resp_status->status = 'error';
                $resp_status->message = 'Failed to update supplier details';
            }
        } else {
            // Generate SUPPLIER_CODE
            $result = $DbCon->query("SELECT MAX(SUPPLIER_CODE) AS max_code FROM mas_supplier");
            $row = $result->fetch_assoc();
            $SUPPLIER_CODE = $row['max_code'] ? 'SUP' . str_pad((int)substr($row['max_code'], 4) + 1, 5, '0', STR_PAD_LEFT) : 'SUP00001';

            // Insert new supplier
            $INSERTED_URL = hash('crc32b', $SUPPLIER_MBL1 . date('Y-m-d'));
            $insertQuery = "INSERT INTO mas_supplier (SUPPLIER_CODE, SUPPLIER_NAME, SUPPLIER_ADD1, SUPPLIER_ADD2, SUPPLIER_CITY, SUPPLIER_STATE, SUPPLIER_PINCODE, SUPPLIER_EMAIL, SUPPLIER_MBL1, SUPPLIER_MBL2, SUPPLIER_GST, SUPPLIER_ACCNO, SUPPLIER_IFSC, SUPPLIER_STS, SUPPLIER_DESC, CREATED_BY, CREATED_DATE, INSERTED_URL) 
                            VALUES ('$SUPPLIER_CODE', '$SUPPLIER_NAME', '$SUPPLIER_ADD1', '$SUPPLIER_ADD2', '$SUPPLIER_CITY', '$SUPPLIER_STATE', '$SUPPLIER_PINCODE', '$SUPPLIER_EMAIL', '$SUPPLIER_MBL1', '$SUPPLIER_MBL2', '$SUPPLIER_GST', '$SUPPLIER_ACCNO', '$SUPPLIER_IFSC', '$SUPPLIER_STS', '$SUPPLIER_DESC', '$CREATED', '$DATE', '$INSERTED_URL')";

            if ($DbCon->query($insertQuery) === TRUE) {
                $resp_status->status = 'Success';
                $resp_status->message = 'Supplier registered successfully';
            } else {
                $resp_status->status = 'error';
                $resp_status->message = 'Failed to register supplier';
            }
        }
    } else {
        $resp_status->status = 'error';
        $resp_status->message = 'Mobile Number already Registered';
    }

    echo json_encode($resp_status);
    mysqli_close($DbCon);
}
?>

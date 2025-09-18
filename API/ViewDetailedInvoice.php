<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: POST, GET, DELETE, PUT");
header("Access-Control-Allow-Headers: Access-Control-Allow-Headers, Origin, Accept, X-Requested-With, Content-Type, Access-Control-Request-Method, Access-Control-Request-Headers");

include 'Config.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

$id = $_POST['id'] ?? null;
$resp_status = new stdClass();
$productRows = [];
$wareHouse = [];
$wareHouse2 = [];

if (empty($id) || $id == 'undefined') {
    $resp_status->status = 'error';
    $resp_status->message = 'No records found.';
} else {
    $id = decryptData($id);
    $check1 = $DbCon->query("SELECT * FROM STK_TRANSDETAILS WHERE TRNS_ID ='$id';");
    if ($check1->num_rows) {
        while ($product = $check1->fetch_assoc()) {
            $productRows[] = $product;
        }
    }
    $check = $DbCon->query("SELECT I.*, U.USER_NAME FROM 
    STK_TRANSACTION I
    JOIN MAS_USERS U ON U.USER_ID = I.CREATED_BY
    WHERE TRN_ID ='$id';");
    if ($check->num_rows) {
        $result = $check->fetch_assoc() ?? [];
        if ($result['WH_FROMID'] == 'all') {
            $check2 = $DbCon->query("SELECT 
            COMPANY_NAME as WH_NAME,
            WH_ADDRESS1 as WH_ADDRESS,
            WH_ADDRESS2 as WH_ADDRESS1,
            WH_CITY,WH_STATE,WH_PINCODE
             FROM MAS_COMPANY WHERE COMP_ID ='1';");
            if ($check2->num_rows) {
                $wareHouse = $check2->fetch_assoc() ?? [];
            }
        } else {
            $check2 = $DbCon->query("SELECT 
            WH_NAME,
            WH_ADDRESS,WH_ADDRESS1,
            WH_CITY,WH_STATE,WH_PINCODE
             FROM MAS_WAREHOUSE WHERE WH_ID ='$result[WH_FROMID]';");
            if ($check2->num_rows) {
                $wareHouse = $check2->fetch_assoc() ?? [];
            }
        }
        if ($result['WH_TOID'] == 'all') {
            $wareHouse2 = [
                "T_WH_NAME" => $result['CUSTOMER_NAME'],
                "T_WH_ADDRESS" => $result['CUSTOMER_MOBILE'],
            ];
        } else {
            $check2 = $DbCon->query("SELECT 
            WH_NAME as T_WH_NAME,
            WH_ADDRESS as T_WH_ADDRESS,WH_ADDRESS1 as T_WH_ADDRESS1,
            WH_CITY as T_WH_CITY,WH_STATE as T_WH_STATE,WH_PINCODE as T_WH_PINCODE
             FROM MAS_WAREHOUSE WHERE WH_ID ='$result[WH_TOID]';");
            if ($check2->num_rows) {
                $wareHouse2 = $check2->fetch_assoc() ?? [];
            }
        }
        $dateTime = DateTime::createFromFormat("d-m-Y H:i:s", $result['CREATED_DATE']);
        $result['INV_DATE'] = $dateTime->format("d-m-Y");
        $result['INV_TIME'] = $dateTime->format("h:iA");
        $resp_status = array_merge(["status" => "ok", "productRows" => $productRows], $result, $wareHouse, $wareHouse2);
    } else {
        $resp_status->status = 'error';
        $resp_status->message = 'No records found.';
    }
}

mysqli_close($DbCon);
echo json_encode($resp_status, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

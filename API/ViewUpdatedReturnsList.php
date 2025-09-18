<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: POST, GET, DELETE, PUT");
header("Access-Control-Allow-Headers: Origin, Accept, X-Requested-With, Content-Type");

include 'Config.php';

$WH = $_POST['WH'] ?? '';
$query = ($WH === 'all') ?
    "SELECT  U.USER_NAME, W.WH_NAME,R.* FROM TRN_RETURNS R JOIN MAS_USERS U ON U.USER_ID = R.CREATED_BY JOIN MAS_WAREHOUSE W ON W.WH_ID = U.BRANCH_CODE WHERE R.RETURN_STATUS = 'A' ORDER BY RETURN_ID DESC" :
    "SELECT U.USER_NAME, W.WH_NAME,R.* FROM TRN_RETURNS R JOIN MAS_USERS U ON U.USER_ID = R.CREATED_BY JOIN MAS_WAREHOUSE W ON W.WH_ID = U.BRANCH_CODE WHERE U.BRANCH_CODE = '$WH' AND R.RETURN_STATUS = 'A' ORDER BY RETURN_ID DESC";

$result = $DbCon->query($query);
$resp = [];
while ($row = $result->fetch_assoc()) {
    $resp[] = [
        'status' => 'success',
        'RETURN_CODE' => $row['RETURN_CODE'],
        'O_INVOICENO' => $row['O_INVOICENO'],
        'O_INVOICEDATE' => $row['O_INVOICEDATE'], 
        'O_BARCODE' => $row['O_BARCODE'],
        'N_INVOICENO' => $row['N_INVOICENO'],
        'N_INVOICEDATE' => $row['N_INVOICEDATE'],
        'N_BARCODE' => $row['N_BARCODE'],
        'VALUE_PAID' => $row['VALUE_PAID'],
        'WH_NAME' => $row['WH_NAME'],
        'RETURN_STATUS' => $row['RETURN_STATUS'],
        'INSERTED_URL' => $row['INSERTED_URL'],
        'USER_NAME' => $row['USER_NAME'],
    ];
}

echo json_encode($resp);
$DbCon->close();

<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: POST, GET, DELETE, PUT");
header("Access-Control-Allow-Headers: Access-Control-Allow-Headers, Origin, Accept, X-Requested-With, Content-Type, Access-Control-Request-Method, Access-Control-Request-Headers");

include 'Config.php';

$resp = ["status" => "error", "message" => "failed to Update;"];
$data = isset($_POST['data']) ? json_decode($_POST['data'], true) : [];

$productRows = $data['productRows'];
unset($data['productRows']);

// Get creator ID from token
$token = $_POST['token'];
$user = $DbCon->prepare("SELECT USER_ID FROM MAS_USERS WHERE USER_TOKEN = ?");
$user->bind_param("s", $token);
$user->execute();
$userResult = $user->get_result();
$CREATED = ($userResult->num_rows > 0) ? $userResult->fetch_assoc()['USER_ID'] : "UNKNOWN";
$DATE = date('d-m-Y H:i:s');
$DATE2 = date('Y-m-d');
$data['CREATED_DATE'] = $DATE;
$data['INVOICE_DATE'] = $DATE2;
$data['CREATED_BY'] = $CREATED;

// Invoice Code
$year = date('Y');
$month = date('m');
if ($month < 4) {
    $year = $year - 1;
}
$check1 = $DbCon->query("SELECT * from STK_TRANSACTION where TRN_CODE like '%$year%';");
$inv_no = str_pad($check1->num_rows + 1, 4, '0', STR_PAD_LEFT) . '/' . $year . '-' . (substr($year, -2) + 1);

// **Begin Transaction**
$DbCon->begin_transaction();

try {
    // Insert into STK_TRANSACTION
    $columns = implode(", ", array_keys($data));
    $values = "'" . implode("', '", array_values($data)) . "'";
    $sql = "INSERT INTO STK_TRANSACTION (TRN_CODE,$columns) VALUES ('GRC$inv_no', $values);";

    if (!$DbCon->query($sql)) {
        throw new Exception("Error inserting into STK_TRANSACTION: " . $DbCon->error);
    }

    $last_id = $DbCon->insert_id;

    // Insert into STK_TRANSDETAILS
    foreach ($productRows as $row) {
        $row['CREATED_DATE'] = $DATE;
        
        $row['CREATED_BY'] = $CREATED;
        $load_columns = implode(", ", array_keys($row));
        $load_values = "'" . implode("', '", array_values($row)) . "'";
        $load_sql = "INSERT INTO STK_TRANSDETAILS (TRNS_ID, $load_columns) VALUES ($last_id, $load_values);";

        if (!$DbCon->query($load_sql)) {
            throw new Exception("Error inserting into STK_TRANSDETAILS: " . $DbCon->error);
        }
        if ($data['WH_FROMID'] == 'all') {
            $query = "UPDATE TRN_MAINSTOCK SET S_QTY=S_QTY - $row[TRN_QTY],UPDATED_BY = '$CREATED', UPDATED_DATE = '$DATE' WHERE STOCK_ID = '$row[STOCK_ID]';";
            $query2 = "SELECT * FROM TRN_MAINSTOCK WHERE STOCK_ID = '$row[STOCK_ID]';";
        } else {
            $query = "UPDATE TRN_WHSTOCK SET S_QTY=S_QTY - $row[TRN_QTY],UPDATED_BY = '$CREATED', UPDATED_DATE = '$DATE' WHERE STOCK_ID = '$row[STOCK_ID]';";
            $query2 = "SELECT * FROM TRN_WHSTOCK WHERE STOCK_ID = '$row[STOCK_ID]';";
        }
        // echo "query " . $query . "\n";
        if (!$DbCon->query($query)) {
            throw new Exception("Error updating in STOCK table: " . $DbCon->error);
        }
        if ($data['WH_TOID'] == 'all') {
            // $query = "UPDATE TRN_MAINSTOCK SET S_QTY=S_QTY + $row[TRN_QTY],UPDATED_BY = '$CREATED', UPDATED_DATE = '$DATE' WHERE STOCK_ID = '$row[STOCK_ID]';";
        } else {
            $check2 = $DbCon->query($query2);
            $check3 = $DbCon->query("SELECT * FROM TRN_WHSTOCK WHERE S_BARCODE = '$row[QR_CODE]' AND WH_ID='$data[WH_TOID]';");
            if ($check3->num_rows) {
                $query = "UPDATE TRN_WHSTOCK SET S_QTY=S_QTY + $row[TRN_QTY],MAX_QTY=MAX_QTY + $row[TRN_QTY], UPDATED_BY = '$CREATED', UPDATED_DATE = '$DATE' WHERE S_BARCODE = '$row[QR_CODE]' AND WH_ID='$data[WH_TOID]';";
                if (!$DbCon->query($query)) {
                    throw new Exception("Error updating in STOCK table: " . $DbCon->error);
                }
            } else {
                if ($check2->num_rows) {
                    $prevData = (array) $check2->fetch_object();
                    unset($prevData['STOCK_ID']);
                    unset($prevData['UPDATED_BY']);
                    unset($prevData['UPDATED_DATE']);
                    $prevData['WH_ID'] = $data['WH_TOID'];
                    $prevData['S_QTY'] = $row['TRN_QTY'];
                    $prevData['MAX_QTY'] = $row['TRN_QTY'];
                    $prevData['CREATED_DATE'] = $DATE;
                    $prevData['CREATED_BY'] = $CREATED;
                    $load_columns = implode(", ", array_keys($prevData));
                    $load_values = "'" . implode("', '", array_values($prevData)) . "'";
                    $load_sql = "INSERT INTO TRN_WHSTOCK ($load_columns) VALUES ($load_values);";
                    // echo "load_sql " . $load_sql . "\n";
                    if (!$DbCon->query($load_sql)) {
                        throw new Exception("Error inserting in STOCK table: " . $DbCon->error);
                    }
                }
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

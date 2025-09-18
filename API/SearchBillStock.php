<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: POST, GET, DELETE, PUT");
header("Access-Control-Allow-Headers: Access-Control-Allow-Headers, Origin, Accept, X-Requested-With, Content-Type, Access-Control-Request-Method, Access-Control-Request-Headers");

include 'Config.php';

$resp = [];
$search = $_POST['SEARCH'];
$WH = $_POST['WH'];
$selected = isset($_POST['selected']) ? json_decode($_POST['selected'], true) : [];
// print_r($selected);
if ($WH == "all") {
    $query = "SELECT * FROM TRN_MAINSTOCK 
          WHERE S_QTY>0 AND 
          (S_BARCODE LIKE '%$search%' 
          OR S_PRODUCTNAME LIKE '%$search%')";
    if (!empty($selected)) {
        $selectedIds = implode(',', array_map('intval', $selected));
        $query .= " AND STOCK_ID NOT IN ($selectedIds)";
    }
    $query .= " ORDER BY STOCK_ID DESC;";
} else {
    $query = "SELECT * FROM TRN_WHSTOCK 
          WHERE WH_ID='$WH' AND S_QTY>0 AND
          (S_BARCODE LIKE '%$search%' 
          OR S_PRODUCTNAME LIKE '%$search%')";
    if (!empty($selected)) {
        $selectedIds = implode(',', array_map('intval', $selected));
        $query .= " AND STOCK_ID NOT IN ($selectedIds)";
    }
    $query .= " ORDER BY STOCK_ID DESC;";
}

if ($check = $DbCon->query($query)) {
    while ($result = $check->fetch_assoc()) {
        $output = [
            "id" => $result['STOCK_ID'],
            "barcode" => $result['S_BARCODE'],
            "size" => $result['S_SIZE'],
            "name" => trim($result['S_PRODUCTNAME']),
            "price" => $result['S_COSTPRICE'],
            "rate" => $result['S_SALERATE'],
            "tax" => $result['S_SALETAXPER'],
            "maxQty" => $result['S_QTY'],
        ];
        $resp[] = array_merge(["status" => "success"], $output);
    }
} else {
    $resp = ["status" => "error", "message" => "Query failed: " . $DbCon->error];
}

echo json_encode($resp);
mysqli_close($DbCon);

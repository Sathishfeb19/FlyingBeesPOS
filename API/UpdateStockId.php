<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: POST, GET, DELETE, PUT");
header("Access-Control-Allow-Headers: Access-Control-Allow-Headers, Origin, Accept, X-Requested-With, Content-Type, Access-Control-Request-Method, Access-Control-Request-Headers");

include 'Config.php';

$resp = ["status" => "error", "message" => "Failed to update."];

// Get data from request
$data = isset($_POST['data']) ? json_decode($_POST['data'], true) : [];
$WH = $_POST['WH'];
$token = $_POST['token'];

// Validate data
if (!isset($data['STOCK_ID'])) {
    echo json_encode(["status" => "error", "message" => "Missing STOCK_ID"]);
    exit;
}

$id = $data['STOCK_ID'];
unset($data['STOCK_ID'], $data['INDEX']);

$set_values = [];
foreach ($data as $column => $value) {
    $set_values[] = "$column = '$value'";
}
$set_clause = implode(", ", $set_values);

// Get creator ID and USER_TYPE from token
$user = $DbCon->prepare("SELECT USER_ID, USER_TYPE FROM MAS_USERS WHERE USER_TOKEN = ?");
$user->bind_param("s", $token);
$user->execute();
$userResult = $user->get_result();
$userData = ($userResult->num_rows > 0) ? $userResult->fetch_assoc() : null;

if (!$userData) {
    echo json_encode(["status" => "error", "message" => "Invalid user token"]);
    exit;
}

$CREATED = $userData['USER_ID'];
$USER_TYPE = $userData['USER_TYPE'];
$DATE = date('Y-m-d H:i:s'); // Use MySQL-friendly format

// Check if user has permission to update
if ($USER_TYPE != '1') {
    echo json_encode(["status" => "error", "message" => "WARNING : You do not have permission to update. Please Contact Head Office!"]);
    exit;
}

// Update query
$table = ($WH == "all") ? "TRN_MAINSTOCK" : "TRN_WHSTOCK";
$query = "UPDATE $table SET $set_clause, UPDATED_BY = '$CREATED', UPDATED_DATE = '$DATE' WHERE STOCK_ID = '$id'";

if ($DbCon->query($query)) {
    $resp = ["status" => "success", "message" => "Updated successfully"];
} else {
    $resp = ["status" => "error", "message" => "Query failed: " . $DbCon->error];
}

echo json_encode($resp);
mysqli_close($DbCon);
?>

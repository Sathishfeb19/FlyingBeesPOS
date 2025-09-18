<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: POST, GET, DELETE, PUT");
header("Access-Control-Allow-Headers: Access-Control-Allow-Headers, Origin, Accept, X-Requested-With, Content-Type, Access-Control-Request-Method, Access-Control-Request-Headers");

date_default_timezone_set('Asia/Kolkata');
include 'Config.php';
include './ReadXlsx/SimpleXLSX.php'; // For reading .xlsx files
include './ReadXlsx/SimpleXLS.php'; // For reading .xls files

use Tharma\SimpleXLSX;
use Tharma\SimpleXLS;

$token = $_POST["token"] ?? null;
$date = date('d-m-Y H:i:s');
// ✅ Get User ID from Token
$userQuery = $DbCon->prepare("SELECT USER_ID FROM MAS_USERS WHERE USER_TOKEN = ?");
$userQuery->bind_param("s", $token);
$userQuery->execute();
$userResult = $userQuery->get_result();
$created = ($userResult->num_rows > 0) ? $userResult->fetch_assoc()['USER_ID'] : "UNKNOWN";


$target_dir = 'uploads/';
$file_name = basename($_FILES["file"]["name"]);
$target_file = $target_dir . $file_name;
$file_extension = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

$allowed_extensions = ['xlsx', 'xls', 'csv'];

if (!in_array($file_extension, $allowed_extensions)) {
    echo json_encode(["status" => "error", "error" => "Invalid file type. Only XLSX, XLS, and CSV are allowed."]);
    mysqli_close($DbCon);
    exit;
}

if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {

    if ($file_extension === "xlsx") {
        readXlsxFile($target_file, $DbCon, $date, $created);
    } elseif ($file_extension === "xls") {
        readXlsFile($target_file, $DbCon, $date, $created);
    } elseif ($file_extension === "csv") {
        readCsvFile($target_file, $DbCon, $date, $created);
    }
} else {
    echo json_encode(["status" => "error", "error" => "Failed to upload file"]);
}

mysqli_close($DbCon);

/**
 * Read .xlsx file and insert data into the database
 */
function readXlsxFile($filePath, $DbCon, $date, $created)
{
    if ($xlsx = SimpleXLSX::parse($filePath)) {
        // Get all rows but skip the first row (header)
        $dataRows = array_slice($xlsx->rows(), 1);
        $barcodeList = array_column($dataRows, 3); // Index 3 (0-based index)
        $selectedIds = implode(',', array_map('intval', $barcodeList));
        $query = "SELECT STOCK_ID FROM TRN_MAINSTOCK 
          WHERE S_BARCODE IN ($selectedIds)";
        $check = $DbCon->query($query);
        if ($check->num_rows) {
            echo json_encode(["status" => "error", "message" => "Please Check BarCode Already Present"]);
        } else {
            $columns = implode(",", array_fill(0, 23, "?")); // Creates placeholders (?, ?, ..., ?)
            $stmt = $DbCon->prepare("INSERT INTO TRN_MAINSTOCK VALUES ($columns)");
            foreach ($dataRows as $row) {
                if (count($row) === 18) {
                    $params = array_merge([null], $row, [$date, $created, '', '']);
                    $stmt->bind_param(str_repeat('s', 23), ...$params);
                    $stmt->execute();
                }
            }
            echo json_encode(["status" => "Success", "message" => "XLSX STOCK UPDATED SUCCESSFULLY"]);
        }
    } else {
        echo json_encode(["status" => "error", "error" => "Failed to read XLSX file"]);
    }
}

/**
 * Read .xls file and insert data into the database
 */
function readXlsFile($filePath, $DbCon, $date, $created)
{
    if ($xls = SimpleXLS::parse($filePath)) {
        // Get all rows but skip the first row (header)
        $dataRows = array_slice($xls->rows(), 1);
        $barcodeList = array_column($dataRows, 3); // Index 3 (0-based index)
        $selectedIds = implode(',', array_map('intval', $barcodeList));
        $query = "SELECT STOCK_ID FROM TRN_MAINSTOCK 
          WHERE S_BARCODE IN ($selectedIds)";
        $check = $DbCon->query($query);
        if ($check->num_rows) {
            echo json_encode(["status" => "error", "message" => "Already Imported Barcode Ids Present"]);
        } else {
            $columns = implode(",", array_fill(0, 23, "?")); // Creates placeholders (?, ?, ..., ?)
            $stmt = $DbCon->prepare("INSERT INTO TRN_MAINSTOCK VALUES ($columns)");
            foreach ($dataRows as $row) {
                if (count($row) === 18) {
                    $params = array_merge([null], $row, [$date, $created, '', '']);
                    $stmt->bind_param(str_repeat('s', 23), ...$params);
                    $stmt->execute();
                }
            }
            echo json_encode(["status" => "Success", "message" => "XLS STOCK UPDATED SUCCESSFULLY"]);
        }
    } else {
        echo json_encode(["status" => "error", "error" => "Failed to read XLS file"]);
    }
}

/**
 * Read CSV file and insert data into the database
 */
function readCsvFile($filePath, $DbCon, $date, $created)
{
    $handle = fopen($filePath, "r");
    if ($handle !== FALSE) {
        while (($row = fgetcsv($handle, 1000, ",")) !== FALSE) {
            $col1 = mysqli_real_escape_string($DbCon, $row[0]);
            $col2 = mysqli_real_escape_string($DbCon, $row[1]);

            // $query = "INSERT INTO TRN_MAINSTOCK (`name`, place) VALUES ('$col1', '$col2')";
            // mysqli_query($DbCon, $query);
        }
        fclose($handle);
        echo json_encode(["status" => "Success", "message" => "CSV data inserted statusfully"]);
    } else {
        echo json_encode(["status" => "error", "error" => "Failed to read CSV file"]);
    }
}

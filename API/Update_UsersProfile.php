<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

require_once "Config.php";

$response = new stdClass(); // Use an object instead of an array

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $UserId = $_POST["id"] ?? null;
    $oldPassword = $_POST["OLD_PASSWORD"] ?? null;
    $newPassword = $_POST["NEWPASSWORD"] ?? null;
    $confirmPassword = $_POST["CNEWPASSWORD"] ?? null;

    if (!$UserId || !$oldPassword || !$newPassword || !$confirmPassword) {
        $response->status = "Error";
        $response->message = "All fields are required.";
        exit(json_encode($response));
    }
    if ($newPassword !== $confirmPassword) {
        $response->status = "Error";
        $response->message = "New password and confirm password do not match.";
        exit(json_encode($response));
    }

    $stmt = $DbCon->prepare("SELECT USER_PASSWORD FROM MAS_USERS WHERE INSERTED_URL = ?");
    $stmt->bind_param("s", $UserId);
    $stmt->execute();
    $stmt->bind_result($hashedPassword);
    
    if (!$stmt->fetch()) {
        $response->status = "Error";
        $response->message = "User not found.";
        exit(json_encode($response));
    }
    $stmt->close();

    if ($oldPassword !== $hashedPassword) {
        $response->status = "Error";
        $response->message = "Incorrect old password.";
        exit(json_encode($response));
    }

    $updateStmt = $DbCon->prepare("UPDATE MAS_USERS SET USER_PASSWORD = ? WHERE INSERTED_URL = ?");
    $updateStmt->bind_param("ss", $newPassword, $UserId);
    
    if ($updateStmt->execute()) {
        $response->status = "Success";
        $response->message = "Password updated successfully.";
    } else {
        $response->status = "Error";
        $response->message = "Failed to update password.";
    }
    $updateStmt->close();
}

echo json_encode($response);

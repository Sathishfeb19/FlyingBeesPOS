<?php
header('Content-Type: application/json');
date_default_timezone_set('Asia/Kolkata');

$ENCKEY = "the-great";
$ENCIV = "1234567812345678"; // 16-byte IV (for AES-256-CBC)

function encryptData($data)
{
    global $ENCKEY;
    global $ENCIV;
    return openssl_encrypt($data, 'AES-256-CBC', $ENCKEY, 0, $ENCIV);
}

function decryptData($encryptedData)
{
    global $ENCKEY;
    global $ENCIV;
    return openssl_decrypt($encryptedData, 'AES-256-CBC', $ENCKEY, 0, $ENCIV);
}


$host = "localhost";
$username = "root";
$password = '';
//$password = '';
$dbname = "bees_gracetextailes";
//$dbname = "udumalaipettai";
$DbCon = mysqli_connect($host, $username, $password, $dbname);

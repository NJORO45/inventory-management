<?php
// Disable direct error output
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL); // Use E_ALL during development

// // error logging
// ini_set('log_errors', 1);
// ini_set('error_log', __DIR__ . '/php-error.log'); // Set to a valid writable path

// Allow CORS (for JavaScript fetch requests)
header("Access-Control-Allow-Origin: *");

// Set response to JSON
header("Content-Type: application/json");
// include php files
include("dbConn.php");
// // Output JSON
// echo json_encode(["success" => true]);
//get data fron database

$stmt = $conn->prepare("SELECT * FROM `stocktable`");
$stmt->execute();
$arrayData = array();
$results = $stmt->get_result();
while($data = $results->fetch_assoc()){
    $arrayData[] = $data;
}
echo json_encode(["success"=>true,"message"=>$arrayData]);
?>
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
//get expired dates of stoke for like 2-3 weeks to expiry add to stockExpiryLogs
$today = date('Y-m-d');
//adjust the days to ones needs
$threeWeeksFromNow = date('Y-m-d', strtotime('+14 days'));
$expiryStmt = $conn->prepare("SELECT * FROM stocktable WHERE expiryDate IS NOT NULL AND expiryDate BETWEEN ? AND ?  AND `status` = 'in stock'");
$expiryStmt->bind_param("ss",$today,$threeWeeksFromNow);
$expiryStmt->execute();
$results = $expiryStmt->get_result();
while($data =$results->fetch_assoc()){
   //check before inserting alert
   $check = $conn->prepare("SELECT * FROM stockExpiryLogs WHERE batchNumber = ? AND productUnid = ?");
   $check->bind_param("ss",$data['batchNumber'], $data['productUnid']);
   $check->execute();
   $checkResult = $check->get_result();
   if($checkResult->num_rows==0){
     //insert alert in stockExpiryLogs
    $sentAlert = $conn->prepare("INSERT stockExpiryLogs( `batchNumber`, `productUnid`, `expiryDate`)VALUES(?,?,?)");
    $sentAlert->bind_param("sss",$data['batchNumber'],$data['productUnid'],$data['expiryDate']);
    $sentAlert->execute();
   }
}
 //get logs
    $stmt = $conn->prepare("SELECT stockExpiryLogs.*,productsname.productName FROM `stockExpiryLogs` JOIN productsname ON stockExpiryLogs.productUnid = productsname.productunid");
    $stmt->execute();
    $arrayData = array();
    $results = $stmt->get_result();
    while($data = $results->fetch_assoc()){
        $arrayData[] = $data;
    }
echo json_encode(["success"=>true,"message"=>$arrayData]);
?>
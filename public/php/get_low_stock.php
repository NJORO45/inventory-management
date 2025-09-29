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
$itemsdata = []; 
$results = $stmt->get_result();
while($items = $results->fetch_assoc()){
    
    $minimumQuantity =($items['totalQuantity'] * 10)/100;//if stock is less than 10%
    if($items['instock'] < $minimumQuantity){
        //check if the alert already exists
        $checkAlert = $conn->prepare("SELECT * FROM alertlog WHERE batchNumber =? AND  productUnid = ? AND alertType = 'lowstock'");
        $checkAlert->bind_param("ss",$items['batchNumber'],$items['productUnid']);
        $checkAlert->execute();
        $existingAlert = $checkAlert->get_result();
        if($existingAlert->num_rows ==0){
            //insert alert into alertLog
            $alertLog = $conn->prepare("INSERT INTO alertlog (batchNumber, productUnid, alertType) VALUES(?,?,'lowstock')");
            $alertLog->bind_param("ss",$items['batchNumber'],$items['productUnid']);
            $alertLog->execute();
             //get that row( product unid name batchnumber remaining stock)
        $itemsdata[] = [
                'batchNumber' =>$items['batchNumber'],
                'productUnid' =>$items['productUnid'],
                'productName' =>$items['productName'],
                'instock' =>$items['instock'],
            ];
        }
    }
     
}

echo json_encode(["success"=>true,"message"=>$itemsdata]);
?>
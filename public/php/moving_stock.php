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

$stmt = $conn->prepare("SELECT productunid, productName FROM `productsname`");
$stmt->execute();
$allProducts = [];
$slowMoving = []; 
$fastMoving = []; 
$soldProducts = [];
$results = $stmt->get_result();
//get all products
while($items = $results->fetch_assoc()){
    $allProducts[$items['productunid']]=$items['productName'];   
}
//get salesfor products
$salesStmt = $conn->prepare("
   SELECT productUnid ,SUM(productQuantity) AS totalSold
   FROM  sales
   WHERE dateOfSales >=CURDATE() - interval 7 DAY
   GROUP BY  productUnid ASC
");
$salesStmt->execute();
$salesData = $salesStmt->get_result();

while($row = $salesData->fetch_assoc()){
    $soldProducts[$row['productUnid']] = $row['totalSold'];
}
foreach($allProducts as $productUnid =>$productName){
    $soldQty = $soldProducts[$productUnid] ?? 0;
    if($soldQty>=10){
        $fastMoving[] = ["productUnid"=>$productUnid, "productName"=>$productName,"sales"=>$soldQty];
    }else{
        $slowMoving[] =["productUnid"=>$productUnid, "productName"=>$productName,"sales"=>$soldQty];
    }
}
echo json_encode(["success"=>true,"fastmoving"=>$fastMoving,"slowmoving"=>$slowMoving]);
?>
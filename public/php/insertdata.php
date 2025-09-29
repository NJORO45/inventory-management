<?php
// Disable direct error output

use BcMath\Number;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL); // Use E_ALL during development
// Allow CORS (for JavaScript fetch requests)
header("Access-Control-Allow-Origin: *");

header('Content-Type:application/json');
include("dbConn.php");
include("functions.php");
$data = json_decode(file_get_contents("php://input"), true);

if (!$data) {
    echo json_encode(["success" => false, "message" => "Invalid JSON"]);
    exit;
}

if(isset($data['addStock']) && $data['addStock']== true){
    //get data initated by addstock event
    $productId = mysqli_real_escape_string($conn,$data['productId']);
    
    $totalBuyingPrice = mysqli_real_escape_string($conn,$data['totalBuyingPrice']);
    $quantity = mysqli_real_escape_string($conn,$data['quantity']);
    $msprice = mysqli_real_escape_string($conn,$data['msprice']);
    $expiryDate='';
    if($data['expiryDate']==""){
        $expiryDate = NULL;
    }else{
        $expiryDate  = mysqli_real_escape_string($conn,$data['expiryDate']);
    }
    //generate batch number and product unid
    //$batchNumber ="B";
    $batchNumber="B".random_num(5);
    //get product name 
    $nameQuery = $conn->prepare("SELECT * FROM productsname WHERE productunid='$productId'");
    $nameQuery->execute();
    $nameResult =$nameQuery->get_result();
    $namedata = $nameResult->fetch_assoc();
    $productName =$namedata['ProductName'];
    $ppPrice = floor($totalBuyingPrice/$quantity);
    $stmt = $conn->prepare("INSERT INTO stocktable (`productUnid`, `ProductName`, `batchNumber`,`instock`, `totalQuantity`, `tbPrice`,`ppPiece`, `msPrice`,`expiryDate`)VALUES(?,?,?,?,?,?,?,?,?)");
    $stmt->bind_param("sssssssss",$productId,$productName,$batchNumber,$quantity,$quantity,$totalBuyingPrice,$ppPrice,$msprice,$expiryDate);
    if($stmt->execute()){
        //success
         echo json_encode(["success"=>true,"message"=>" updating to database.$expiryDate"]);
    }else{
        echo json_encode(["success"=>false,"message"=>"error updating to database"]);
    }
   
}
if(isset($data['addProductName']) && $data['addProductName']==true){
    $productName = mysqli_real_escape_string($conn,$data['productName']);
    $productId = random_num(6);
    $stmt = $conn->prepare("INSERT productsname (`productunid`, `productName`)VALUES(?,?)");
    $stmt->bind_param("ss",$productId,$productName);
    
    if($stmt->execute()){
        echo json_encode(["success"=>true,"message"=>"Success"]);
    }else{
        echo json_encode(["success"=>false,"message"=>"error"]);
    }
    $stmt->close();
}
if(isset($data['updateProductName']) && $data['updateProductName']){
    $productName = mysqli_real_escape_string($conn,$data['EditproductName']);
    $EditproductId = mysqli_real_escape_string($conn,$data['EditproductId']);

    $stmt = $conn->prepare("UPDATE productsname SET productName = ? WHERE productunid = ?");
    $stmt->bind_param("ss",$productName,$EditproductId);
    if($stmt->execute()){
        echo json_encode(["success"=>true,"message"=>"success"]);
    }else{
        echo json_encode(["success"=>false,"message"=>"failed"]);
    }
}
if(isset($data['fetchBatchNumber']) && $data['fetchBatchNumber']){
    $productId = mysqli_real_escape_string($conn,$data['productId']);
    $nameQuery = $conn->prepare("SELECT * FROM stocktable WHERE productUnid= ? AND `status`='in stock'  ORDER BY arrivalDate ASC LIMIT 1");
    $nameQuery->bind_param("s", $productId);
    $nameQuery->execute();
    $nameResult =$nameQuery->get_result();
    $arrayData=array();
    while($namedata = $nameResult->fetch_assoc()){
        $arrayData[]=$namedata;
    }
    echo json_encode(["success"=>true,"message"=>$arrayData]);
    
}
if(isset($data['fetchBatchDetails']) && $data['fetchBatchDetails']){
    $ProductsBatchNumber = mysqli_real_escape_string($conn,$data['ProductsBatchNumber']);
    $nameQuery = $conn->prepare("SELECT * FROM stocktable WHERE batchNumber='$ProductsBatchNumber'");
    $nameQuery->execute();
    $nameResult =$nameQuery->get_result();
    $arrayData=array();
    while($namedata = $nameResult->fetch_assoc()){
        $arrayData[]=$namedata;
    }
    echo json_encode(["success"=>true,"message"=>$arrayData]);
    
}
if(isset($data['fetchNewBatchDetails']) && $data['fetchNewBatchDetails']){
    $productId = mysqli_real_escape_string($conn,$data['productId']);
    $sellingPrice = mysqli_real_escape_string($conn,$data['sellingPrice']);
    $requstedBalanceQuantity = mysqli_real_escape_string($conn,$data['requstedBalanceQuantity']);//quantity client is requesting
    $selectedBatchNuber = mysqli_real_escape_string($conn,$data['selectedBatchNuber']);//batch that doesnt have enough stock
    //fetch all batches for the product excluding the one that isnt enough
    $stmt = $conn->prepare(
        "SELECT batchNumber,
            instock,
            msPrice
            FROM stocktable
            WHERE productUnid = ? -- skip this batch
            AND batchNumber <> ?
            AND instock > 0
            ORDER BY arrivalDate ASC  -- FIFO; use DESC if you prefer newest first
            " 
    );
    $stmt->bind_param("ss",$productId,$selectedBatchNuber);
    $stmt->execute();
    $rs = $stmt->get_result();

    //loop throug all available batches
    $allocations = [];      // keeps a record of everything we take
    $remaining = $requstedBalanceQuantity;//how many pieces we still need
    $highestPrice = 0;//track the max price used
    $finalSelingPrice='';
    $selected=array();
    $priceList = [];  
    while($row = $rs->fetch_assoc()){

        $selected[] =$row;
        $priceList[] =(float) $row['msPrice'];//put the batch maPrice in a n array to cmpare the heighrst later
    }
    
        $n=0;
        foreach($selected as $row){
            $n++;
            $batchPrice = (float) $row['msPrice'];
            //check the data if the required stock is ehough 
            //skip any batch if the stock is 0
            if(empty($row['instock'])){
                continue;
            }
            
            
            //find how many we can take
            $take = min($remaining, (int) $row['instock']);
            if ($take === 0) continue; 
            //recorde the deducted batches
            $allocations[] = [
                'batchNumber' =>$row['batchNumber'],
                'qntyTaken'=>$take,
                'remaining units'=>$remaining,
                'instock'=> $row['instock'],
                'requstedBalanceQuantity'=>$requstedBalanceQuantity
            ];

            $remaining -=$take;
            //update counters
            if($remaining<=0){
                break; //order satistied
            }
        
        }
    $priceList[]      = $sellingPrice;        // append the first selected batch price to the other batches
    $finalSelingPrice =max($priceList);  //will all the batches in one arra compare the heighest price FIFO
    if($remaining>0){
        echo json_encode([
            'success'=>false,
            'stock'       => 'not enough',
            'neededMore'  => $remaining,
            'batchesSeen' => $n,
        ]);
    }else{
        echo json_encode([
            'success'=>true,
            'allocations'=>$allocations,
            'batchesSeen'=>$n,
            'finalSelingPrice'=>$finalSelingPrice,
            'priceList'=>$priceList
    ]);
}



    
}
if(isset($data['addSalesStatus']) && $data['addSalesStatus']){
    $salesUnid = random_num(7);
    $productId = mysqli_real_escape_string($conn,$data['ProductsNameValue']);
    $productName = mysqli_real_escape_string($conn,$data['ProductsNameText']);
    $ProductsBatchNumberValue = mysqli_real_escape_string($conn,$data['ProductsBatchNumberValue']);
    $availableStockValue = mysqli_real_escape_string($conn,$data['availableStockValue']);
    $sellingPriceValue = mysqli_real_escape_string($conn,$data['sellingPriceValue']);
    $quantitySaleValue = mysqli_real_escape_string($conn,$data['quantitySaleValue']);
    $grantTotalPrice = mysqli_real_escape_string($conn,$data['grantTotalPrice']);
    $paymentSelector = mysqli_real_escape_string($conn,$data['paymentSelecto']);
    $mpesaPayment = mysqli_real_escape_string($conn,$data['mpesaPaymen']);
    $cashPayment = mysqli_real_escape_string($conn,$data['cashPaymen']);
   
    if($quantitySaleValue>$availableStockValue){
        // $BatchData = mysqli_real_escape_string($conn,$data['batchData']);
        //extract the bacthdata
        $allGood = true;   // flag to track success
        $stmt = $conn->prepare("INSERT sales ( `salesUnid`, `productUnid`, `ProductName`, `productQuantity`, `productPrice`, `paymentMethod`, `mpesaPayment`, `cashPayment`, `grandTotal`)
                               VALUES(?,?,?,?,?,?,?,?,?)");
        $status='in stock';//sold out
        $stmt->bind_param("sssssssss",$salesUnid,$productId,$productName,$quantitySaleValue,$sellingPriceValue,$paymentSelector,$mpesaPayment,$cashPayment,$grantTotalPrice);
        if($stmt->execute()){
            
            //get  soldstock curent values
            $soldStmt = $conn->prepare("SELECT * FROM stocktable WHERE batchNumber=? LIMIT 1");
            $soldStmt->bind_param("s",$ProductsBatchNumberValue);
            $soldStmt->execute();
            $soldData=$soldStmt->get_result();

            if($row = $soldData->fetch_assoc()){
                //fetch stock records
                $soldStockData=$row['soldStock'];
                $totalStock=$row['totalQuantity'];
                if($soldStockData=='' || $soldStockData =='0' || $soldStockData=='undefined' || $soldStockData =='NaN'){
                    $soldStockData=$availableStockValue;
                }else{
                    $soldStockData = $soldStockData + $availableStockValue;
                }
                $inStock = floatval($totalStock) - floatval($soldStockData);
                
                if($quantitySaleValue>=$availableStockValue){//outos stock
                    $status='sold out';
                }
                //update stock
                $stockUpdate = $conn->prepare("UPDATE stocktable SET `instocK`=?, `soldStock`=?, `status`=?  WHERE batchNumber=?");
                $stockUpdate ->bind_param("ssss",$inStock,$soldStockData,$status,$ProductsBatchNumberValue);
                if($stockUpdate->execute()){
                    //update batch sales
                    $totalPrice = (float)$sellingPriceValue * (float)$availableStockValue;
                    $batchSales = $conn->prepare("INSERT INTO salesbatches (`salesUnid`, `batchNumber`, `qnySold`, `sellingPrice`, `grandTotal`)
                                                 VALUES(?,?,?,?,?)");
                    $batchSales->bind_param("sssss",$salesUnid,$ProductsBatchNumberValue,$availableStockValue ,$sellingPriceValue,$totalPrice);
                    if($batchSales->execute()){
                        //echo json_encode(["success"=>true,"message"=>"sales updated succesfully"]);
                        //add batch loop though the batch take each batch with the quantity reduced from the stock and add it in the sales batch table
                        foreach($data['batchData'] as $data){
                            //update sales batch and their stock
                                                    
                            //get  soldstock curent values
                            $soldStmt = $conn->prepare("SELECT * FROM stocktable WHERE batchNumber=? LIMIT 1");
                            $soldStmt->bind_param("s",$data['batchNumber']);
                            $soldStmt->execute();
                            $soldData=$soldStmt->get_result();

                            if($row = $soldData->fetch_assoc()){
                                //fetch stock records
                                $soldStockData=$row['soldStock'];
                                $totalStock=$row['totalQuantity'];
                                if($soldStockData=='' || $soldStockData =='0' || $soldStockData=='undefined' || $soldStockData =='NaN'){
                                    $soldStockData=$data['qntyTaken'];
                                }else{
                                    $soldStockData = $soldStockData + $data['qntyTaken'];
                                }
                                $inStock = floatval($totalStock) - floatval($soldStockData);
                                
                                if($totalStock==$data['qntyTaken']){//outos stock
                                    $status='sold out';
                                }
                                 $totalPrice = (float)$sellingPriceValue * (float)$data['qntyTaken'];
                                 $stockUpdate = $conn->prepare("UPDATE stocktable SET `instocK`=?, `soldStock`=?, `status`=?  WHERE batchNumber=?");
                                $stockUpdate ->bind_param("ssss",$inStock,$soldStockData,$status,$data['batchNumber']);
                                if($stockUpdate->execute()){
                                    //update batch sales
                                    $batchSales = $conn->prepare("INSERT INTO salesbatches (`salesUnid`, `batchNumber`, `qnySold`, `sellingPrice`, `grandTotal`)
                                                                VALUES(?,?,?,?,?)");
                                    $batchSales->bind_param("sssss",$salesUnid,$data['batchNumber'],$data['qntyTaken'],$sellingPriceValue,$totalPrice);
                                    if($batchSales->execute()){
                                        $allGood =true;
                                    }else{
                                        $allGood =false;
                                    }
                                }
                            }
                        }
                        if($allGood =true){
                            echo json_encode(["success"=>true,"message"=>"sales updated succesfully"]);
                        }else{
                            echo json_encode(["success"=>false,"message"=>"error while updating batch"]);
                        }
                        
                         
                    }else{
                        echo json_encode(["success"=>false,"message"=>"error while updating batch"]);
                    }
                    
                }else{
                    echo json_encode(["success"=>false,"message"=>"error while updating batch stock"]);
                }


            }else{
                echo json_encode(["success"=>false,"message"=>"batch number not found"]);
            }
            
        }else{
            echo json_encode(["success"=>false,"message"=>"error while updating sales"]);
        }

    }
    else{
        $stmt = $conn->prepare("INSERT sales ( `salesUnid`, `productUnid`, `ProductName`, `productQuantity`, `productPrice`, `paymentMethod`, `mpesaPayment`, `cashPayment`, `grandTotal`)
                               VALUES(?,?,?,?,?,?,?,?,?)");
        $status='in stock';//sold out
        $stmt->bind_param("sssssssss",$salesUnid,$productId,$productName,$quantitySaleValue,$sellingPriceValue,$paymentSelector,$mpesaPayment,$cashPayment,$grantTotalPrice);
        if($stmt->execute()){
            //get  soldstock curent values
            $soldStmt = $conn->prepare("SELECT * FROM stocktable WHERE batchNumber=? LIMIT 1");
            $soldStmt->bind_param("s",$ProductsBatchNumberValue);
            $soldStmt->execute();
            $soldData=$soldStmt->get_result();

            if($row = $soldData->fetch_assoc()){
                //fetch stock records
                $soldStockData=$row['soldStock'];
                $totalStock=$row['totalQuantity'];
                if($soldStockData=='' || $soldStockData =='0' || $soldStockData=='undefined' || $soldStockData =='NaN'){
                    $soldStockData=$quantitySaleValue;
                }else{
                    $soldStockData = $soldStockData + $quantitySaleValue;
                }
                $inStock = floatval($totalStock) - floatval($soldStockData);
                
                if($quantitySaleValue==$availableStockValue){//outos stock
                    $status='sold out';
                }
                //update stock
                $stockUpdate = $conn->prepare("UPDATE stocktable SET `instocK`=?, `soldStock`=?, `status`=?  WHERE batchNumber=?");
                $stockUpdate ->bind_param("ssss",$inStock,$soldStockData,$status,$ProductsBatchNumberValue);
                if($stockUpdate->execute()){
                    //update batch sales
                    $batchSales = $conn->prepare("INSERT INTO salesbatches (`salesUnid`, `batchNumber`, `qnySold`, `sellingPrice`, `grandTotal`)
                                                 VALUES(?,?,?,?,?)");
                    $batchSales->bind_param("sssss",$salesUnid,$ProductsBatchNumberValue,$quantitySaleValue,$sellingPriceValue,$grantTotalPrice);
                    if($batchSales->execute()){
                        echo json_encode(["success"=>true,"message"=>"sales updated succesfully"]);
                    }else{
                        echo json_encode(["success"=>false,"message"=>"error while updating batch"]);
                    }
                    
                }else{
                    echo json_encode(["success"=>false,"message"=>"error while updating stock"]);
                }
            }else{
                echo json_encode(["success"=>false,"message"=>"batch number not found"]);
            }
            
        }else{
            echo json_encode(["success"=>false,"message"=>"error while updating sales"]);
        }

    }

    
   
    
}
if(isset($data['fetchFilterTableData']) && $data['fetchFilterTableData']){
    $fromDate = mysqli_real_escape_string($conn,$data['fromDate']);
    $toDate = mysqli_real_escape_string($conn,$data['toDate']);

    $stmt = $conn->prepare("SELECT * FROM `sales` WHERE `dateOfSales` BETWEEN ? AND ? ORDER BY `dateOfSales` DESC");
    $stmt->bind_param("ss",$fromDate,$toDate);
    $stmt->execute();
    $arrayData = array();
    $results = $stmt->get_result();
    while($data = $results->fetch_assoc()){
        $arrayData[] = $data;
    }
    echo json_encode(["success"=>true,"message"=>$arrayData]);
}

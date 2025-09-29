<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../main.css">
    <!--favicon -->
    <link rel="shortcut icon" href="img/favicon.png" type="image/x-icon"> 
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.2.0/remixicon.min.css"  />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.2/xlsx.full.min.js"></script>
</head>
<body  class="min-h-screen flex flex-col">
    <!--alert-->
    <section id="alertMessage" class="hidden fixed  w-max top-0 transform -translate-x-1/2 left-1/2 z-50  mt-4">
        <div id="alertContainer" class="max-w-sm mx-auto flex items-center justify-between gap-4 border border-red-400 text-red-700 py-3 px-4 rounded-md">
            <div class="flex items-center gap-2">
                <span id="alertHeader" class="font-bold"></span>
                <p id="alertBody"></p>
            </div>
             <button class="text-red-500 hover:text-red-700 font-bold">&times;</button>
        </div>
    </section>
   <!--navbar-->
   <section class="fixed z-40 w-screen h-20 bg-blue-500 flex flex-row justify-between p-4 items-center text-slate-200">
      <div class="text-xl font-bold">
        <a href="../index.html">Inventory Management</a>
      </div>
      <div id="humbarger">
        <i id="humbargerIcon" class="ri-menu-4-line text-2xl font-bold cursor-pointer"></i>
      </div>
   </section>
   <!--side navbar-->
   <section id="humbargerMenu" class="fixed z-40 mt-20 w-[200px] left-[-200px] bg-blue-400 h-screen  transition-all duration-500">
    <div class="w-full h-full flex flex-col ">
        <a href="../index.html" class="text-center px-3 py-2 hover:bg-blue-500 hover:text-slate-100 hover:text-md">Add stock</a>
        <a href="products.php" class="text-center px-3 py-2 hover:bg-blue-500 hover:text-slate-100 hover:text-md">Products</a>
        <a href="#" class="text-center px-3 py-2 bg-blue-500 text-slate-100 text-md">New Sale</a>
        <a href="analytics.php" class="text-center px-3 py-2 hover:bg-blue-500 hover:text-slate-100 hover:text-md">Analytics</a>
    </div>
   </section>
   <!--main container-->
   <section class="w-screen pt-20 bg-white flex flex-col flex-grow ">
    <div class="w-full flex justify-between px-1 md:px-4 mt-2">
        <div class="flex my-auto w-auto h-auto ">
            <div class=" px-1 md:px-4 my-auto gap-2 space-y-1">
                <input id="startDate" type="date" class="border-2 border-gray-200 px-2 py-1 rounded-md cursor-pointer ">
                <input id="endDate" type="date" class="border-2 border-gray-200 px-2 py-1 rounded-md cursor-pointer ">
            </div>
            <div class="w-max mx-auto my-auto">
                <button id="exportSales" class="bg-blue-600 text-white rounded-xl w-max p-2 text-xs md:text-sm hover:scale-105" >Export</button>
            </div>
        </div>
        <a id="modalParent" class="w-max h-max p-2 text-xs mx-2 md:mx-1 md:text-sm bg-blue-600 hover:scale-105 cursor-pointer text-white rounded-xl">Sell</a>
    </div>
    <div class="mx-auto w-full px-4">
        <p class="text-center text-xl font-bold underline ">Sales</p>
        <div class=" overflow-x-auto max-h-screen py-4">
            <table class="min-w-full table-fixed border-collapse ">
                <thead>
                    <tr >
                        <th class="bg-slate-100 sticky left-0 z-20 w-40 px-4 py-2 border-r border-slate-300 text-left">salesUnid</th>
                        <th class="bg-slate-100 px-4 py-2 text-left ">productUnid</th>
                        <th class="bg-slate-100 px-4 py-2 text-left">ProductName</th>
                        <th class="bg-slate-100 px-4 py-2 text-left">productQuantity</th>
                        <th class="bg-slate-100 px-4 py-2 text-left">productPrice</th>
                        <th class="bg-slate-100 px-4 py-2 text-left">grandTotal</th>
                        <th class="bg-slate-100 px-4 py-2 text-left">dateOfSales</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="hover:bg-slate-50">
                        <td colspan="7" class="px-4 py-2 text-center">Loading data</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
   </section>
   <!--form-->
   <section id="popupModal" class=" hidden fixed  w-screen h-screen p-2 z-40 bg-black bg-opacity-50  flex justify-center items-center ">
    <div class="relative max-w-xl w-full bg-slate-100 space-y-4 px-8 py-4 rounded-xl h-full overflow-y-auto  border-2 border-blue-500">
    <div id="modalClose" class="sticky flex justify-end  right-4 top-2">
            <i class="ri-close-large-fill cursor-pointer text-lg font-bold text-blue-500 hover:scale-105"></i>
        </div>    
    <div class="flex flex-col gap-2">
            <label for="product">Product Name</label>
            <select name="" id="ProductsName" class="rounded-xl py-2 px-3 outline-none">
                
            </select>
        </div>
<!--Populate with older stock that is in stock when it is finished populate with nw stock-->
        <div class="flex flex-col gap-2">
            <label for="product">Batch number</label>
            <select name="" id="ProductsBatchNumber" class="rounded-xl py-2 px-3 outline-none">
                
            </select>
        </div>
        <div class="flex flex-col gap-2">
            <label for="product">Available Stock</label>
            <input id="availableStock" type="text" class="rounded-xl py-2 px-3 outline-none" readonly>
        </div>
        <div class="flex flex-col gap-2">
            <label for="product">Quantity Sale</label>
            <input id="quantitySale" type="text" class="rounded-xl py-2 px-3 outline-none">
        </div>
        <!--populae with neew batch stock when old stock is no enough and compore price with the high-->
        <div class="flex flex-col gap-2">
            <label for="product">New Batch Number</label>
            <input id="newBatchNumber" type="text" class="hidden rounded-xl py-2 px-3 outline-none" readonly value="">
            
            
            <div class="text-blue-500 font-bold flex gap-2">
                <p id="stockLevel"></p>
                <p id="stocknumber"></p>
            </div>
            <p id="stockAlert"></p>
        </div>
        <div class="flex flex-col gap-2">
            <label for="product">Bst price</label>
            <input id="bestPrice" type="text" class="rounded-xl py-2 px-3 outline-none" readonly>
        </div>
        <div class="flex flex-col gap-2">
            <label for="product">Selling price</label>
            <input id="sellingPrice" type="text" class="rounded-xl py-2 px-3 outline-none">
            <p id="sellingPriceError"></p>
        </div>
        <div class="flex flex-col gap-2">
            <label for="product">Total Selling price</label>
            <input id="TotalsellingPrice" type="text" class="rounded-xl py-2 px-3 outline-none " readonly>
            
        </div>
        <div class="flex flex-col gap-2">
            <label for="">Payment Method</label>
            <div>
                <select name="" id="paymentSelector" class="w-full px-3 py-2 border border-gray-100 rouned-xl focus:outline-none focus:ring-2 focus:ring-gray-100 focus:border-gray-100 ">
                    <option value="" default>--select--</option>
                    <option value="mpesa" class="border-none outline-none">Mpesa</option>
                    <option value="cash" >Cash</option>
                    <option value="both" >Both</option>
                </select>
            </div>
        </div>
        <div class="flex flex-col w-full  gap-2">
            <div class="flex flex-col  md:flex-row w-full  gap-2">
                <!--mpesa-->
                <input type="text" id="mpesaPayment" value="0" placeholder="mpesa" class="hidden border-none px-2 py-2 rounded-lg outline-none">
                <!--cash-->
                <input type="text" id="cashPayment" value="0" placeholder="cash" class="hidden border-none px-2 py-2 rounded-lg outline-none">
            </div>
            <p id="paymentError" class="px-2"></p>
        </div>
        <div class="w-full text-center">
            <button id="AddSalesBtn" class="bg-blue-500 py-2 px-3 text-white rounded-xl hover:scale-105">New sales</button>
        </div>
    </div>
   </section>
   <!--footer-->
   <section class="w-full bg-slate-100 py-2">
    <div class="w-full text-center">
        <p>&copy; 2025 powerd by  waveton solutions</p>
    </div>
   </section>
</body>
<script src="../js/main.js"></script>
<script src="../js/addSales.js"></script>
</html>
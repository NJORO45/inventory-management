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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

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
        <a href="sales.php" class="text-center px-3 py-2 hover:bg-blue-500 hover:text-slate-100 hover:text-md">New Sale</a>
        <a href="#" class="text-center px-3 py-2 bg-blue-500 hover:text-slate-100 ">Analytics</a>
    </div>
   </section>
   <!--main container-->
   <section class="w-full pt-20 bg-white flex flex-col flex-grow ">
     <h4 class="mx-auto text-xl font-bold pt-2">Analytics</h4>
     <!--fast & slow movers-->
     <div class="grid grid-cols-1 sm:grid-cols-2 mx-auto gap-4 p-4 ">
        <div class="shadow-xl px-2 py-1 rounded-lg max-h-80 bg-gray-100">
            <h3 class="text-sm">⚡ Top 10 Fast-Moving Products</h3>
            <div class="w-full overflow-auto max-h-60">
              <table  class="w-full mt-2">
                <thead>
                    <tr>
                        <th class="bg-gray-200 text-xs text-center rounded-tl-lg p-2 text-slate-500">No.</th>
                        <th class="bg-gray-200 text-xs text-center p-2 text-slate-500" >product name</th>
                        <th class="bg-gray-200 text-xs text-center rounded-tr-lg p-2 text-slate-500">sales</th>
                    </tr>
                </thead>
                <tbody id="fastMovingBody">
                    <tr>
                        <td class="text-sm text-center">1.</td>
                        <td class="text-sm text-center text-wrap">raspbery pi zero 2W raspbery pi zero 2W</td>
                        <td class="text-sm text-center">7</td>
                    </tr>
                    <tr class="hover:bg-gray-100">
                        <td class="text-sm text-center">2.</td>
                        <td class="text-sm text-center text-nowrap">MQ-5</td>
                        <td class="text-sm text-center">10</td>
                    </tr>
                    <tr>
                        <td class="text-sm text-center">3.</td>
                        <td class="text-sm text-center text-nowrap">Arduino uno</td>
                        <td class="text-sm text-center">32</td>
                    </tr>
                </tbody>
               </table>
            </div>
        </div>
        <div class="px-2 py-1 rounded-lg shadow-xl  bg-gray-100 max-h-80 ">
            <h3 class="text-sm">🐢 Slow-Moving Products</h3>
            <div class="w-full overflow-auto  max-h-60 ">
              <table class="w-full mt-2 ">
                  <thead class="">
                      <tr >
                          <th scope="col" class="text-xs text-gray-500 text-center bg-gray-200 rounded-tl-lg p-2">No.</th>
                          <th class="text-xs text-gray-500 text-center bg-gray-200  p-2">product name</th>
                          <th class="text-xs text-gray-500 text-center bg-gray-200 rounded-tr-lg p-2">sales</th>
                      </tr>
                  </thead>
                  <tbody id="slowMovingBody">
                      <tr>
                          <td class="text-sm text-center">1.</td>
                          <td class="text-sm text-center text-wrap">raspbery pi zero 2W raspbery pi zero 2W</td>
                          <td class="text-sm text-center">7</td>
                      </tr>
                      <tr class="hover:bg-gray-100">
                          <td class="text-sm text-center">2.</td>
                          <td class="text-sm text-center text-nowrap">MQ-5</td>
                          <td class="text-sm text-center">10</td>
                      </tr>
                      <tr>
                          <td class="text-sm text-center">3.</td>
                          <td class="text-sm text-center text-nowrap">Arduino uno</td>
                          <td class="text-sm text-center">32</td>
                      </tr>
                  </tbody>
              </table>
            </div>
        </div>
     </div>
     <!--sales trend chart -->
     <div class="w-full flex flex-col items-center justify-center">
        <h3 class="text-center">📅 Sales Trend</h3>
        <div class="w-full md:max-w-2xl overflow-x-auto flex justify-center">
          <canvas class="w-full h-64 sm:h-80 md:h-96 min-w-[400px]" id="salesChart"></canvas>
          
        </div>
        <p id="slaes_alert" class="text-center"></p>
     </div>
     <!-- 🔔 Stock Alert Table -->
<div class="w-full  my-4">
  <h3 class="text-red-700 font-bold text-center mb-2">🔔 Low Stock Alerts</h3>
  <div class="w-full px-2 overflow-auto">
  <table class=" w-full md:max-w-xl mx-auto shadow-lg rounded-lg ">
    <thead>
      <tr>
        <th class="bg-gray-200 text-xs text-center text-gray-600 p-2 rounded-tl-lg">No.</th>
        <th class="bg-gray-200 text-xs text-center text-gray-600 p-2">Product</th>
        <th class="bg-gray-200 text-xs text-center text-gray-600 p-2">Batch</th>
        <th class="bg-gray-200 text-xs text-center text-gray-600 p-2">Remaining</th>
        <th class="bg-gray-200 text-xs text-center text-gray-600 p-2 rounded-tr-lg">Recommendation</th>
      </tr>
    </thead>
    <tbody id="alertStockBody">
      <!-- JS will populate this -->
       <tr>
          <td colspan="5" class="whitespace-nowrap text-sm text-center p-2">Loading alerts</td>
        </tr>
    </tbody>
  </table>
  </div>
</div>
     <!-- 🔔 Stock expiry Alert Table -->
<div class="w-full  my-4">
  <h3 class="text-red-700 font-bold text-center mb-2">🔔 Stock Expiry Alerts</h3>
  <div class="w-full px-2 overflow-auto">
  <table class=" w-full md:max-w-xl mx-auto shadow-lg rounded-lg ">
    <thead>
      <tr>
        <th class="bg-gray-200 text-xs text-center text-gray-600 p-2 rounded-tl-lg">No.</th>
        <th class="bg-gray-200 text-xs text-center text-gray-600 p-2">Product</th>
        <th class="bg-gray-200 text-xs text-center text-gray-600 p-2">Batch</th>
        <th class="bg-gray-200 text-xs text-center text-gray-600 p-2">Expiry Date</th>
        <th class="bg-gray-200 text-xs text-center text-gray-600 p-2 rounded-tr-lg">Recommendation</th>
      </tr>
    </thead>
    <tbody id="alertStockExpiryBody">
      <!-- JS will populate this -->
       <tr>
          <td colspan="5" class="whitespace-nowrap text-sm text-center p-2">Loading alerts</td>
        </tr>
    </tbody>
  </table>
  </div>
</div>

     <!--alert logschart -->
     <div class="w-full my-2">
        <h3 class="text-center mb-2 font-bold text-red-800">🕒 Alert History</h3>
       <div class="w-full max-h-[300px] px-2 overflow-auto">
        <table class=" w-full mx-auto  shadow-lg rounded-lg " id="alertLogTable">
            <thead class="">
                <tr>
                    <th class="text-gray-500 text-center bg-gray-200 rounded-tl-lg text-sm p-2">no.</th>
                    <th class="text-gray-500 text-center bg-gray-200 text-sm p-2">product name </th>
                    <th class="text-gray-500 text-center bg-gray-200 text-sm p-2">batch number</th>
                    <th class="text-gray-500 text-center bg-gray-200 text-sm p-2">alert Type</th>
                    <th class="text-gray-500 text-center bg-gray-200 rounded-tr-lg text-sm p-2">date</th>
                </tr>
            </thead>
            <tbody id="alertHistory" class="">
                <tr>
                    <td colspan="5" class="whitespace-nowrap text-sm text-center p-2">loading alerts</td>
                </tr>
            </tbody>
        </table>
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
<script src="../js/analytics.js"></script>


</html>
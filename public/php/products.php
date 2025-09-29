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
   <section class="fixed z-30 w-screen h-20 bg-blue-500 flex flex-row justify-between p-4 items-center text-slate-200">
      <div class="text-xl font-bold">
        <a href="../index.html">Inventory Management</a>
      </div>
      <div id="humbarger">
        <i id="humbargerIcon" class="ri-menu-4-line text-2xl font-bold cursor-pointer"></i>
      </div>
   </section>
   <!--side navbar-->
   <section id="humbargerMenu" class=" fixed z-30 mt-20 w-[200px] left-[-200px] bg-blue-400 h-screen transition-all duration-500">
    <div class="w-full h-full flex flex-col ">
        <a href="../index.html" class="text-center px-3 py-2 hover:bg-blue-500 hover:text-slate-100 hover:text-md">Add stock</a>
        <a href="#" class="text-center px-3 py-2 bg-blue-500 text-slate-100 text-md">Products</a>
        <a href="sales.php" class="text-center px-3 py-2 hover:bg-blue-500 hover:text-slate-100 hover:text-md">New Sale</a>
        <a href="analytics.php" class="text-center px-3 py-2 hover:bg-blue-500 hover:text-slate-100 hover:text-md">Analytics</a>
    </div>
   </section>
   <!--main container-->
   <section class="w-screen pt-20 bg-white flex flex-col flex-grow ">
    <div class="w-full flex justify-end px-4 mt-2">
       <i id="modalParent" class="ri-add-circle-fill p-2 text-2xl text-blue-600 hover:scale-105 cursor-pointer"></i>
    </div>
    <div class="mx-auto w-full px-4">
        <p class="text-center text-xl font-bold underline ">Products</p>
        <div class=" overflow-x-auto max-h-screen py-4">
            <table class="min-w-full table-fixed border-collapse ">
                <thead>
                    <tr >
                        <th class="bg-slate-100 sticky left-0 z-20 w-40 px-4 py-2 border-r border-slate-300 text-left">Product name</th>
                        <th class="bg-slate-100 px-4 py-2 text-left ">Unid</th>
                        <th class="bg-slate-100 px-4 py-2 text-left ">Date Added</th>
                        <th class="bg-slate-100 px-4 py-2 text-left "></th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="hover:bg-slate-50 ">
                         <td class="px-4 py-2  text-center" colspan="6">Loading data</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
   </section>
   <!--form-->
   <section id="popupModal" class="hidden fixed  w-screen h-screen z-40 bg-black bg-opacity-50  flex justify-center items-center ">
    <div class="max-w-xl w-full bg-slate-100 space-y-4 px-8 py-4 rounded-xl border border-2 border-blue-500">
        <div id="modalClose" class="flex justify-end">
            <i class="ri-close-large-fill cursor-pointer text-lg font-bold text-blue-500 hover:scale-105"></i>
        </div>
        <div class="flex flex-col gap-2">
            <label for="product">Product Name</label>
            <input id="productName" type="text" class="rounded-xl py-2 px-3 outline-none">
            <p id="productNameError"></p>
        </div>
        <div class="w-full text-center">
            <button id="addProductName" class="bg-blue-500 py-2 px-3 text-white rounded-xl hover:scale-105">Add Product</button>
        </div>
    </div>
   </section>
<!--edit product name-->
 <section id="editpopupModal" class="hidden fixed  w-screen h-screen z-40 bg-black bg-opacity-50  flex justify-center items-center ">
    <div class="max-w-xl w-full bg-slate-100 space-y-4 px-8 py-4 rounded-xl border border-2 border-blue-500">
        <div id="EditmodalClose" class="flex justify-end">
            <i class="ri-close-large-fill cursor-pointer text-lg font-bold text-blue-500 hover:scale-105"></i>
        </div>
        <div class="flex flex-col gap-2">
            <label for="product">Product Name</label>
            <input id="EditproductName" type="text" class="rounded-xl py-2 px-3 outline-none">
            <input id="EditproductId" type="text" hidden value="">
            <p id="editProductNameError"></p>
        </div>
        <div class="w-full text-center">
            <button id="saveProductName" class="bg-blue-500 py-2 px-3 text-white rounded-xl hover:scale-105">Save changes</button>
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
<script src="../js/addProducts.js"></script>
</html>
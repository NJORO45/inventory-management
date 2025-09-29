<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="./main.css">
    <!--favicon -->
    <link rel="shortcut icon" href="img/favicon.png" type="image/x-icon"> 
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.2.0/remixicon.min.css"  />
</head>
<body  class="min-h-screen flex flex-col">
    <!--alert-->
    <section class="fixed  w-max top-0 transform -translate-x-1/2 left-1/2 z-50  mt-4">
        <div class="max-w-sm mx-auto flex items-center justify-between gap-4 bg-red-100 border border-red-400 text-red-700 py-3 px-4 rounded-md">
            <div class="flex items-center gap-2">
                <span class="font-bold">⚠️ Alert!</span>
                <p>AN error occured</p>
            </div>
             <button class="text-red-500 hover:text-red-700 font-bold">&times;</button>
        </div>
    </section>
   <!--navbar-->
   <section class="fixed z-40 w-screen h-20 bg-blue-500 flex flex-row justify-between p-4 items-center text-slate-200">
      <div class="text-xl font-bold">
        <a href="">Inventory Management</a>
      </div>
      <div>
        <i class="ri-menu-4-line text-2xl font-bold cursor-pointer"></i>
      </div>
   </section>
   <!--side navbar-->
   <section class="hidden fixed z-40 mt-20 w-[200px] bg-blue-400 h-screen ">
    <div class="w-full h-full flex flex-col ">
        <a href="#" class="text-center px-3 py-2 hover:bg-blue-500 hover:text-slate-100 hover:text-md">Add stock</a>
        <a href="#" class="text-center px-3 py-2 bg-blue-500 text-slate-100 text-md">Products</a>
        <a href="#" class="text-center px-3 py-2 hover:bg-blue-500 hover:text-slate-100 hover:text-md">New Sale</a>
        <a href="#" class="text-center px-3 py-2 hover:bg-blue-500 hover:text-slate-100 hover:text-md">Analytics</a>
    </div>
   </section>
   <!--main container-->
   <section class="w-screen pt-20 bg-white flex flex-col flex-grow overflow-hidden">
    <div class="w-full flex justify-end px-4 mt-2 justify-around">
       <input type="date" name="start_date" class="text-sm border border-slate-200 rounded-xl md:px-3 py-2 outline-none">
        <input type="date" name="end_date" class="text-sm border border-slate-200 rounded-xl md:px-3 py-2 outline-none">
        <select name="product_id" class="text-sm border border-slate-200 rounded-xl md:px-3 py-2 outline-none">
            <option value="">All Products</option>
            <!-- dynamically load product options -->
        </select>
        <button type="submit" class="text-sm text-white bg-blue-500 rounded-xl px-1">Generate Report</button>
    </div>
    <div class="mx-auto w-full px-4">
        <p class="text-center text-xl font-bold underline ">Reports</p>
        <div class=" overflow-x-auto max-h-screen py-4">
            <table class="min-w-full table-fixed border-collapse ">
                <thead>
                    <tr >
                        <th class="bg-slate-100 sticky left-0 z-20 w-40 px-4 py-2 border-r border-slate-300 text-left">Product name</th>
                        <th class="bg-slate-100 px-4 py-2 text-left ">Date</th>
                        <th class="bg-slate-100 px-4 py-2 text-left ">Batch</th>
                        <th class="bg-slate-100 px-4 py-2 text-left ">Qnty sold</th>
                        <th class="bg-slate-100 px-4 py-2 text-left ">Selling price</th>
                        <th class="bg-slate-100 px-4 py-2 text-left ">Revenue</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="hover:bg-slate-50">
                         <td class="bg-white sticky left-0 z-10 w-40 px-4 py-2 border-r border-slate-200 whitespace-nowrap">Pro mini 5V 16 MHZ</td>
                        
                        <td class="px-4 py-2 text-left">B7534</td>
                        <td class="px-4 py-2 text-left">14/04/2025</td>
                        <td class="px-4 py-2 text-left" colspan="2">
                            <button class="bg-blue-500 text-white p-2 rounded-xl">Edit</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
   </section>
   <!--form-->
   <section class="hidden fixed  w-screen h-screen z-50 bg-black bg-opacity-50  flex justify-center items-center ">
    <div class="max-w-xl w-full bg-slate-100 space-y-4 px-8 py-4 rounded-xl border border-2 border-blue-500">
        
        <div class="flex flex-col gap-2">
            <label for="product">Product Name</label>
            <input type="text" class="rounded-xl py-2 px-3 outline-none">
        </div>
        <div class="w-full text-center">
            <button class="bg-blue-500 py-2 px-3 text-white rounded-xl hover:scale-105">Add Product</button>
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
</html>
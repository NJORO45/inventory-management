let arrayData = '';
let newBatchData='';
let exportData='';
function sanitize(input) {
    if (typeof input !== "string") return input;

    return input
        .trim()
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#39;");
}
async function getProductName() {
   try{
    const response = await fetch ('fetchProducts.php',{
        method:"GET",
        headers:{
            'Content-Type':'application/json'
        }
    });
    const text = await response.text(); // First get raw text
    console.log(text)
    try {
      const result = JSON.parse(text); // Try parsing it
      return result;
    } catch (jsonErr) {
        throw new Error("Invalid JSON from server: " + text);
    }
    
   }catch(error){
    console.log("session expired/error" + error.message);
    return {success:false, error:error.message};
   }
}
async function fetchFilterTableData(fromDate,toDate) {
    const tbody = document.querySelector("tbody");
    const postData={
        fetchFilterTableData:true,
        fromDate:fromDate.trim(),
        toDate:toDate.trim()
    };
    try{
        const response = await fetch('insertdata.php',{
            method:"POST",
            headers:{
                'Content-Type':'application/json'
            },
            body:JSON.stringify(postData)
        });
        const text = await response.text();
        try{
            const results = JSON.parse(text);
            console.log(results);
            exportData=results.message;
            if(results.success){
                const mapedData = results.message.map((items)=>{
                    return `
                    <tr>
                        <td class="bg-white sticky left-0 z-10 w-40 px-4 py-2 border-r border-slate-200 whitespace-nowrap">${items.salesUnid}</td>
                        <td class="px-4 py-2 text-left">${items.productUnid}</td>
                        <td class="px-4 py-2 text-left">${items.ProductName}</td>
                        <td class="px-4 py-2 text-left">${items.productQuantity}</td>
                        <td class="px-4 py-2 text-left">${items.productPrice}</td>
                        <td class="px-4 py-2 text-left">${items.grandTotal}</td>
                        <td class="px-4 py-2 text-left">${items.dateOfSales}</td>
                        </tr>
                    `;
                }).join(" ");
                //console.log(mapedData);
                tbody.innerHTML = mapedData;
            }
        }catch(jsonErr){
            throw new Error("invalid json from server" + text);
        }
    }catch(error){
        console.log("session expired/error" + error.message);
    return {success:false, error:error.message};
    }
}

async function fetchTableData() {
    const tbody = document.querySelector("tbody");
    try{
        const response = await fetch('fetchSalesData.php',{
            method:"GET",
            headers:{
                'Content-Type':'application/json'
            }
        });
        const text = await response.text();
        try{
            const results = JSON.parse(text);
            if(results.success){
                const mapedData = results.message.map((items)=>{
                    return `
                    <tr>
                        <td class="bg-white sticky left-0 z-10 w-40 px-4 py-2 border-r border-slate-200 whitespace-nowrap">${items.salesUnid}</td>
                        <td class="px-4 py-2 text-left">${items.productUnid}</td>
                        <td class="px-4 py-2 text-left">${items.ProductName}</td>
                        <td class="px-4 py-2 text-left">${items.productQuantity}</td>
                        <td class="px-4 py-2 text-left">${items.productPrice}</td>
                        <td class="px-4 py-2 text-left">${items.grandTotal}</td>
                        <td class="px-4 py-2 text-left">${items.dateOfSales}</td>
                        </tr>
                    `;
                }).join(" ");
                //console.log(mapedData);
                tbody.innerHTML = mapedData;
            }
        }catch(jsonErr){
            throw new Error("invalid json from server" + text);
        }
    }catch(error){
        console.log("session expired/error" + error.message);
    return {success:false, error:error.message};
    }
}

function checkDates(endDateStatus,startDateStatus,startDate,endDate){
    const fromDate = `${startDate.value} 00:00`;
    const toDate = `${endDate.value} 23:59`;
        if(endDateStatus && startDateStatus){
        //check id date is okay
        console.log("start date: ");
        console.log(fromDate);
        console.log("end date: ");
        console.log(toDate);
        if(toDate > fromDate){
           //fetch sales data for this range
           startDate.classList.add("border-gray-200");
            startDate.classList.remove("border-red-400");
            //get th data
            fetchFilterTableData(fromDate,toDate);
        }else{
            startDate.classList.remove("border-gray-200");
            startDate.classList.add("border-red-400");
        }
    }else{
        console.log("status error;")
    }
}
addEventListener("DOMContentLoaded",()=>{
    const ProductsName = document.querySelector("#ProductsName");
    const ProductsBatchNumber = document.querySelector("#ProductsBatchNumber");
    const availableStock = document.querySelector("#availableStock");
    const bestPrice = document.querySelector("#bestPrice");
    const sellingPrice = document.querySelector("#sellingPrice");
    const TotalsellingPrice = document.querySelector("#TotalsellingPrice");
    const quantitySale = document.querySelector("#quantitySale");
    const newBatchNumber = document.querySelector("#newBatchNumber");
    const stockAlert = document.querySelector("#stockAlert");
    const sellingPriceError = document.querySelector("#sellingPriceError");
    const stockLevel = document.querySelector("#stockLevel");
    const stocknumber = document.querySelector("#stocknumber");
    const AddSalesBtn = document.querySelector("#AddSalesBtn");
    const popupModal = document.querySelector("#popupModal");

    const startDate = document.querySelector("#startDate");
    const endDate = document.querySelector("#endDate");
    const exportSales = document.querySelector("#exportSales");

    const alertHeader = document.querySelector("#alertHeader");
    const alertBody = document.querySelector("#alertBody");
    const alertContainer = document.querySelector("#alertContainer");
    const alertMessage = document.querySelector('#alertMessage');
    //payment method paymentSelector
    const paymentSelector = document.querySelector('#paymentSelector');
    const mpesaPayment = document.querySelector('#mpesaPayment');
    const cashPayment = document.querySelector('#cashPayment');
    const paymentError = document.querySelector('#paymentError');
    let sellingPriceStatus=false;
    let quantitySaleStatus=false;
    let startDateStatus=false;
    let endDateStatus=false;
    let paymentStatus=false;
    let results = getProductName();
    paymentSelector.addEventListener("change",()=>{
        if(paymentSelector.value=="mpesa"){
            mpesaPayment.classList.remove("hidden");
            cashPayment.classList.add("hidden");
            mpesaPayment.value='';
             paymentStatus=false;
            mpesaPayment.addEventListener("input",()=>{
                if(mpesaPayment.value!=TotalsellingPrice.value){
                    paymentError.classList.add("text-red-600","font-medium");
                       paymentError.innerHTML="Money at hand not matching";
                      paymentStatus=false;
                }else{
                    paymentError.classList.remove("text-red-600","font-medium");
                       paymentError.innerHTML=" ";
                       paymentStatus=true;
                }
            });
        }
        if(paymentSelector.value=="cash"){
            mpesaPayment.classList.add("hidden");
            cashPayment.classList.remove("hidden");
            cashPayment.value='';
             paymentStatus=false;
            cashPayment.addEventListener("input",()=>{
                if(cashPayment.value!=TotalsellingPrice.value){
                    paymentError.classList.add("text-red-600","font-medium");
                       paymentError.innerHTML="Money at hand not matching";
                      paymentStatus=false;
                }else{
                    paymentError.classList.remove("text-red-600","font-medium");
                       paymentError.innerHTML=" ";
                       paymentStatus=true
                }
            });

        }
        if(paymentSelector.value=="both"){
            mpesaPayment.classList.remove("hidden");
            cashPayment.classList.remove("hidden");
            mpesaPayment.value='';
            cashPayment.value='';
             paymentStatus=false;
            function updateTotalMoneyAtHand(){
                const mpesa = parseFloat(mpesaPayment.value) || 0;
                const cash = parseFloat(cashPayment.value) || 0;
                const  TotalMoneyAtHand = mpesa + cash;
                //compare oney at hand with total selling price
                const expectedTotal = parseFloat(TotalsellingPrice.value);
                if(TotalMoneyAtHand!=expectedTotal){
                    paymentError.classList.add("text-red-600", "font-medium");
                    paymentError.innerHTML = "Money at hand not matching";
                    paymentStatus=false;
                }else{
                    paymentError.classList.remove("text-red-600", "font-medium");
                    paymentError.innerHTML = "";
                    paymentStatus=true;
                }
                console.log("Total Money At Hand:", TotalMoneyAtHand);
            }
        //add input listemers
        mpesaPayment.addEventListener("input",updateTotalMoneyAtHand);
        cashPayment.addEventListener("input",updateTotalMoneyAtHand);
    }
});

    results.then(result=>{
        if(result.success==true){
            let mapedData = `<option value="" selected>--select--</option>`;
            mapedData += result.message.map(items=>{
                return `
                <option value="${items.productunid}" >${items.ProductName}</option>
                `;
            }).join("");
            //console.log(mapedData);
            ProductsName.innerHTML=mapedData;
        }
    }).catch(error=>{
        console.log("error occured", error.message);
    });
    fetchTableData();
    //filter sales data by date 
    endDate.addEventListener("change",()=>{
        endDateStatus=true;
        checkDates(endDateStatus,startDateStatus,startDate,endDate);
    });
     startDate.addEventListener("change",()=>{
        console.log("startDate change");
        startDateStatus=true;
       
        checkDates(endDateStatus,startDateStatus,startDate,endDate);
    });
//add export event listener
exportSales.addEventListener("click",()=>{
    if(endDate.value>startDate.value){
         // Create a new Excel workbook
				 //console.log(data);
				  // Convert JSON to Excel using SheetJS
				  //const jsonData = JSON.parse(data);  // Parse JSON if necessary (sometimes the response is already an object)

				  // Create a new Excel workbook
				  const wb = XLSX.utils.book_new();   // Create a new workbook
				 
				  // Convert the JSON data into a worksheet
				  const ws = XLSX.utils.json_to_sheet(exportData);
				  
				  // Append worksheet to the workbook
				  XLSX.utils.book_append_sheet(wb, ws, "Exported Data");

                  //generate filaname with current date and time 
                  const now = new Date();
                  const datestr = now.toISOString().slice(0,10); //yy-mm-dd
                  const timestr = now.toTimeString().slice(0,8).replace("-",":"); //hh-mm
                  const filename = `sales_data_${datestr} ${timestr}.xlsx`;
				  // Trigger download of the Excel file
				  XLSX.writeFile(wb, filename);

    }else{

    }
})
//fetchBatch.php
    ProductsName.addEventListener("change",()=>{
        const ProductsBatchNumber = document.querySelector("#ProductsBatchNumber");
        //ge the value and search
        console.log(ProductsName.value);
         async function submitStockData() {
            
            const ProductsNameValue = sanitize(ProductsName.value);
            //combine to on object
            const postData = {
                fetchBatchNumber:true,
                productId: ProductsNameValue
            };

            try{
                const response = await fetch('insertdata.php',{
                    method:"POST",
                    headers:{
                        'Content-Type':'application/json'
                    },
                    body:JSON.stringify(postData)
                });
                const text = await response.text();
                console.log(text);
                try{
                    const results = JSON.parse(text);
                    if(results.success){
                       let mapedData = `<option value="" selected>--select--</option>`;
                        mapedData += results.message.map(items=>{
                            return `
                            <option value="${items.batchNumber}" >${items.batchNumber}</option>
                            `;
                        }).join("");
                        console.log(mapedData);
                        ProductsBatchNumber.innerHTML=mapedData;
                    }else{
                        console.log("data error updated");
                    }
                }catch(jsonErr){
                    throw new Error("Invalid json from server:" + text)
                }
            }catch(error){
                console.log("error message: " + error.message);
                return{success:false, error:error.message};
            }
            }
            submitStockData();
    });
    //get batch data
      ProductsBatchNumber.addEventListener("change",()=>{
        //ge the value and search
        console.log(ProductsBatchNumber.value);
         async function submitStockData() {
            
            const ProductsBatchNumbervalue = sanitize(ProductsBatchNumber.value);
            //combine to on object
            const postData = {
                fetchBatchDetails:true,
                ProductsBatchNumber: ProductsBatchNumbervalue
            };

            try{
                const response = await fetch('insertdata.php',{
                    method:"POST",
                    headers:{
                        'Content-Type':'application/json'
                    },
                    body:JSON.stringify(postData)
                });
                const text = await response.text();
                console.log(text);
                try{
                    const results = JSON.parse(text);
                    if(results.success){
                        //availableStock
                        arrayData=results.message;
                       console.log(results.message);
                       availableStock.value=results.message[0].instock;
                       bestPrice.value=results.message[0].msPrice;
                    }else{
                        console.log("data error updated");
                    }
                }catch(jsonErr){
                    throw new Error("Invalid json from server:" + text)
                }
            }catch(error){
                console.log("error message: " + error.message);
                return{success:false, error:error.message};
            }
            }
            submitStockData();
    });
    //chck sales quantity
    quantitySale.addEventListener("input",()=>{
        //compare the sock available for older sock if it is not enought show next stock
        const quantitySaleValue = parseFloat(quantitySale.value);
        const totalQuantity = parseFloat(arrayData[0].instock);
        const ProductsBatchNumbervalue = sanitize(ProductsBatchNumber.value);
        console.log(arrayData);
        if(quantitySaleValue > totalQuantity){

            //expose the new stock also check the price diference and pick the grate one
           // console.log("stock isn enough");
            async function submitStockData() {
            var balanceQuantityRequsted = Number(quantitySaleValue) - Number(totalQuantity);
            const ProductsNameValue = sanitize(ProductsName.value);
            const sellingPrice = arrayData[0].msPrice;
            //combine to on object
            const postData = {
                fetchNewBatchDetails:true,
                productId: ProductsNameValue,
                requstedBalanceQuantity:balanceQuantityRequsted,
                selectedBatchNuber:ProductsBatchNumbervalue,
                sellingPrice:sellingPrice
            };

            try{
                const response = await fetch('insertdata.php',{
                    method:"POST",
                    headers:{
                        'Content-Type':'application/json'
                    },
                    body:JSON.stringify(postData)
                });
                const text = await response.text();
               
                console.log(text);
                try{
                    const results = JSON.parse(text);
                    newBatchData=results;
                    console.log(results);
                    if(results.success){
                        bestPrice.value="";
                        //availableStock FOR NEW BATCH
                       console.log(results.allocations.length);
                       newBatchNumber.classList.remove("hidden");
                       bestPrice.value=results.finalSelingPrice;

                       if(results.allocations.length>1){
                        
                        let mapedAllocation = results.allocations.map((items)=>{
                        return items.batchNumber;
                       }).join("||");
                       newBatchNumber.value= mapedAllocation;
                       console.log("merge",mapedAllocation);
                       stockAlert.innerHTML="";
                       }else{
                        console.log("no merge");
                        newBatchNumber.value= results.allocations[0].batchNumber ;
                        stockAlert.innerHTML="";
                       }
                       
                       
                       stockAlert.innerHTML=" ";
                       quantitySaleStatus=true;
                    }else{
                        
                       stockAlert.classList.add("text-red-600","font-medium");
                       stockAlert.innerHTML="Stock is not enough"+ " " + "-"+ newBatchData.neededMore;
                       quantitySaleStatus=false;
                       
                    }
                }catch(jsonErr){
                    throw new Error("Invalid json from server:" + text)
                }
            }catch(error){
                console.log("error message: " + error.message);
                return{success:false, error:error.message};
            }
            }
            submitStockData();

        }else{
            const oldBatchPrice = parseFloat(arrayData[0].msPrice);
            stockAlert.innerHTML="";
            newBatchNumber.value='';
            newBatchNumber.classList.add("hidden");
            bestPrice.value=oldBatchPrice;
            stockLevel.innerHTML='';
            stocknumber.innerHTML='';
            quantitySaleStatus=true;
        }
    });
    sellingPrice.addEventListener("input",()=>{
        const bestPrice = document.querySelector("#bestPrice");
        const sellingPrice = document.querySelector("#sellingPrice");
        const quantitySale = document.querySelector("#quantitySale").value;
        const bestPriceValue = parseFloat(bestPrice.value);
        const sellingPriceValue = parseFloat(sellingPrice.value);
        TotalsellingPrice.value=Number(sellingPriceValue) * Number(quantitySale);
        if(sellingPriceValue<bestPriceValue){
            sellingPriceError.classList.add("text-red-600","font-medium");
            sellingPriceError.innerHTML="Amount is low than stated above";
            sellingPriceStatus=false;
        }else{
            sellingPriceError.innerHTML="";
            sellingPriceStatus=true;
        }
    });

    AddSalesBtn.addEventListener("click",()=>{
        
        if(sellingPriceStatus==true &&quantitySaleStatus==true && quantitySale.value!='' && ProductsName.value!='' && ProductsBatchNumber.value!='' && paymentStatus==true){
             
         async function addSales() {
            const ProductsNameValue = sanitize(ProductsName.value);
            const ProductsNameText = sanitize(ProductsName.options[ProductsName.selectedIndex].text);
            const ProductsBatchNumberValue = sanitize(ProductsBatchNumber.value);
            const availableStockValue = sanitize(availableStock.value);
            const sellingPriceValue = sanitize(sellingPrice.value);
            const quantitySaleValue = sanitize(quantitySale.value);
            const grantTotalPrice = sanitize(TotalsellingPrice.value);
            const paymentSelecto = sanitize(paymentSelector.value);
            const mpesaPaymen = sanitize(mpesaPayment.value);
            const cashPaymen = sanitize(cashPayment.value);
            const BatchData = newBatchData.allocations;

            const postData = {
                addSalesStatus:true,
                ProductsNameValue,
                ProductsNameText,
                ProductsBatchNumberValue,
                availableStockValue,
                sellingPriceValue,
                quantitySaleValue,
                batchData:BatchData,
                paymentSelecto,
                mpesaPaymen,
                cashPaymen,
                grantTotalPrice
            };
           console.log(postData);
            try{
                const response =await fetch('insertdata.php',{
                    method:"POST",
                    headers:{"Content-Type":"application/json"},
                    body:JSON.stringify(postData)
                });
                const text = await response.text();
                console.log(text);
                try{
                    const results = JSON.parse(text);
                    if(results.success){
                        //clear al the data in the inputs
                        alertMessage.classList.remove('hidden');
                        alertContainer.classList.add("bg-lime-300","ring-2","ring-lime-200","text-gray-800","border-none");
                        alertHeader.innerHTML='⚠️ Alert!';
                        alertBody.innerHTML='Sales updated';
                        setTimeout(()=>{
                            alertMessage.classList.add('hidden');
                            
                        },2000);
                        setTimeout(()=>{
                            //productName.parentElement.parentElement.parentElement.classList.add("hidden");
                            
                            ProductsName.value="";
                            ProductsBatchNumber.value="";
                            bestPrice.value="";
                            sellingPrice.value="";
                            TotalsellingPrice.value="";
                            quantitySale.value="";
                            newBatchNumber.value="";
                            stockAlert.innerHTML="";
                            sellingPriceError.innerHTML="";
                            stockLevel.innerHTML="";
                            stocknumber.innerHTML="";
                            availableStock.value="";
                            paymentSelector.value='';
                            mpesaPaymen.value='';
                            cashPayment.value='';
                            paymentError.innerHTML='';
                            popupModal.classList.add('hidden');

                        },2500);
                    }else{
                        alertMessage.classList.remove('hidden');
                        alertContainer.classList.add("bg-red-100");
                        alertHeader.innerHTML='⚠️ Alert!';
                        alertBody.innerHTML='Error making sales';
                        setTimeout(()=>{
                            alertMessage.classList.add('hidden');
                            
                        },2000);
                    }
                }catch(jsonErr){
                    throw new Error("Invalid JSON from server: " + text);
                }
            }catch(error){
                    console.log("session expired/error" + error.message);
                    return {success:false, error:error.message};
                }
            
            //console.log(postData);
         };
         addSales();
        }else{
            console.log(sellingPriceStatus)
            console.log(quantitySaleStatus)
            console.log( quantitySale.value)
            console.log(ProductsName.value)
            console.log(ProductsBatchNumber.value)
            console.log(paymentStatus)
            console.log('check form');
            alertMessage.classList.remove('hidden');
                        alertContainer.classList.add("bg-red-100");
                        alertHeader.innerHTML='⚠️ Alert!';
                        alertBody.innerHTML='check form inputs';
                        setTimeout(()=>{
                            alertMessage.classList.add('hidden');
                            
                        },2000);
        }
    });
});
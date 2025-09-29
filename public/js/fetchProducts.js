async function getProductName() {
   try{
    const response = await fetch ('php/fetchProducts.php',{
        method:"GET",
        headers:{
            'Content-Type':'application/json'
        }
    });
    const text = await response.text(); // First get raw text
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
async function fetchStock() {
    const tbody = document.querySelector('tbody');
   try{
    const response = await fetch ('php/fetchStockList.php',{
        method:"GET",
        headers:{
            'Content-Type':'application/json'
        }
    });
    const text = await response.text(); // First get raw text
    try {
      const result = JSON.parse(text); // Try parsing it
     if(result.success){
        console.log(result.message.length);
        if(result.message.length==0){
            
            tbody.innerHTML= `
                <tr class="hover:bg-slate-50 ">
                    <td class="px-4 py-2  text-center" colspan="6">No available stock yet</td>
                </tr>
                `;
        }else{
            let mapedData = result.message.map(items=>{
                return `
                <tr class="hover:bg-slate-50">
                    <td class="bg-white sticky left-0 z-10 w-40 px-4 py-2 border-r border-slate-200 whitespace-nowrap">${items['productName']}</td>
                    <td class="px-4 py-2 text-left">${items['batchNumber']}</td>
                    <td class="px-4 py-2 text-left">${items['totalQuantity']}</td>
                    <td class="px-4 py-2 text-left">${items['tbPrice']}</td>
                    <td class="px-4 py-2 text-left">${items['ppPiece']}</td>
                    <td class="px-4 py-2 text-left">${items['msPrice']}</td>
                    <td class="px-4 py-2 text-left">${items['arrivalDate']}</td>
                </tr>
                `;
            }).join("");
            tbody.innerHTML=mapedData;
        }
     }
    } catch (jsonErr) {
        throw new Error("Invalid JSON from server: " + text);
    }
    
   }catch(error){
    console.log("session expired/error" + error.message);
    return {success:false, error:error.message};
   }
}
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

addEventListener("DOMContentLoaded",()=>{
    
    const selectOption = document.querySelector('#selectOption');
    const addStock = document.querySelector('#addStock');
    const alertMessage = document.querySelector('#alertMessage');

    const tquantity = document.querySelector('#tquantity');
    const tbprice = document.querySelector('#tbprice');
    const msprice = document.querySelector('#msprice');
    const cppiece = document.querySelector('#cppiece');
    
    const alertHeader = document.querySelector("#alertHeader");
    const alertBody = document.querySelector("#alertBody");
    const alertContainer = document.querySelector("#alertContainer");
    const popupModal = document.querySelector("#popupModal");

    const tquantityError = document.querySelector('#tquantityError');
    const tbpriceError = document.querySelector('#tbpriceError');
    const mspriceError = document.querySelector('#mspriceError');
    const selectOptionError = document.querySelector('#selectOptionError');

    const expiryRaw = document.getElementById('expiryDate');
    const expiryDateError = document.getElementById('expiryDateError');
    //get promise
    setInterval(()=>{
        fetchStock();
    },2000);
    let results = getProductName();
    results.then(result=>{
        if(result.success==true){
            let mapedData = `<option value="" selected>--select--</option>`;
            mapedData += result.message.map(items=>{
                return `
                <option value="${items.productunid}" >${items.productName}</option>
                `;
            }).join("");
            //console.log(mapedData);
            selectOption.innerHTML=mapedData;
        }
    }).catch(error=>{
        console.log("error occured", error.message);
    });
    tquantity.addEventListener("input",()=>{
        tquantity.value = tquantity.value.replace(/[^0-9]/g, '');//only allow digits 0-9
    });
    tbprice.addEventListener("input",()=>{
        tbprice.value = tbprice.value.replace(/[^0-9]/g, '');//only allow digits 0-9
    });
    //calculate the minimum price per piece
   tbprice.addEventListener("blur",()=>{
    
        cppiece.value=Math.floor(Number(tbprice.value)/Number(tquantity.value));
    });
    msprice.addEventListener("input",()=>{
        console.log(cppiece.value);
        //it should not be ledd than ccpIece
        const msPriceValue = parseFloat(msprice.value);
        const cppieceValue = parseFloat(cppiece.value);
        if(!isNaN(msprice.value) && !isNaN(cppiece.value)){
            if(msPriceValue<=cppieceValue || msPriceValue <= 1){
            console.log("Risk of losses alert");
            mspriceError.classList.add("text-red-600","font-medium");
            mspriceError.innerHTML="Risk of losses alert";
            }else{
                mspriceError.innerHTML="";
            }
        }else{
           mspriceError.innerHTML=""; 
        }
    });
      expiryRaw.addEventListener("change",()=>{
            const selected = new Date(expiryRaw.value);

           
            const now = new Date();
            
            if(selected <  now){
                expiryDateError.textContent ="Expiry date cannot be in the past"
            }else{
                expiryDateError.textContent = "";
            }
        });
    addStock.addEventListener("click",()=>{
        if(tquantity.value==''){
            tquantityError.classList.add("text-red-600","font-medium");
            tquantityError.innerHTML=" Quantity is required";
        }else{
            tquantityError.classList.add("text-red-600","font-medium");
            tquantityError.innerHTML="";
        }
        if(tbprice.value==''){
            tbpriceError.classList.add("text-red-600","font-medium");
            tbpriceError.innerHTML="T.B Price is required";
        }else{
            tbpriceError.innerHTML=" ";
        }
       
        if(selectOption.value==""){
            selectOptionError.classList.add("text-red-600","font-medium");
            selectOptionError.innerHTML="Select an option";
        }else{
            selectOptionError.innerHTML="";
        }
      
        if(!tquantity.value=='' && !tbprice.value==''  &&!selectOption.value=='' && !msprice.value==''){
            tquantityError.classList.add("text-red-600","font-medium");
            tquantityError.innerHTML=" ";
            tbpriceError.innerHTML=" ";
            //sanitize and send the data
            const selected = new Date(expiryRaw.value);
            console.log(expiryRaw.value);
            let dateformatted='';
            if(expiryRaw.value ===""){
                dateformatted="";
            }else{
                 //fomart to MM-DD-YYYY 23:59:59
            dateformatted = sanitize( `${selected.getFullYear()}-` +`${String(selected.getMonth() + 1).padStart(2, '0')}-`+
                              `${String(selected.getDate()).padStart(2, '0')}`+ ` ` +
                               `23:59:59`);
            }
           
            async function submitStockData() {
            const selectOptionValue = sanitize(selectOption.value);
            const tbpriceValue = sanitize(tbprice.value);
            const tquantityValue = sanitize(tquantity.value);
            const msPriceValue = sanitize(msprice.value);

            //combine to on object
            const postData = {
                addStock:true,
                productId: selectOptionValue,
                totalBuyingPrice: tbpriceValue,
                quantity: tquantityValue,
                msprice:msPriceValue,
                expiryDate : dateformatted
            };

            try{
                const response = await fetch('php/insertdata.php',{
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
                        console.log("data updated");
                        //clear al the data in the inputs  border-red-400 text-red-700 
                        alertMessage.classList.remove('hidden');
                        alertContainer.classList.add("bg-lime-300","ring-2","ring-lime-200","text-gray-800","border-none");
                        
                        alertHeader.innerHTML='Success!';
                        alertBody.innerHTML='Stock Updated';
                        setTimeout(()=>{
                            alertMessage.classList.add('hidden');
                            popupModal.classList.add('hidden');
                        },2000);
                        //clear inpus
                        tbprice.value="";
                        tquantity.value="";
                        selectOption.value="";
                        msprice.value="";
                        fetchStock();
                    }else{
                        console.log("data error updated");
                        alertMessage.classList.remove('hidden');
                        alertContainer.classList.add("bg-red-600","ring-2","ring-red-400","text-white");
                        
                        alertHeader.innerHTML='⚠️ Alert!';
                        alertBody.innerHTML='Error while updating Stock ';
                        setTimeout(()=>{
                            alertMessage.classList.add('hidden');
                            popupModal.classList.add('hidden');
                        },2000);
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
        }
    });
});
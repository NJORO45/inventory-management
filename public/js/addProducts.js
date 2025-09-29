let productPrevName='';
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
async function getProductsName() {
    const tbody = document.querySelector('tbody');
    const editpopupModal = document.querySelector('#editpopupModal');
    const EditproductName = document.querySelector('#EditproductName');
    const EditproductId = document.querySelector('#EditproductId');
    const saveProductName = document.querySelector('#saveProductName');
    try{
        const response = await fetch('fetchProductsName.php',{
            method:"GET",
            headers:{"Content-Type":"application/json"}
        });
        const text = await response.text();
        try{
            const result = JSON.parse(text);
            if(result.success){
                if(result.message.length==0){
            
            tbody.innerHTML= `
                <tr class="hover:bg-slate-50 ">
                    <td class="px-4 py-2  text-center" colspan="6">No available Products yet</td>
                </tr>
                `;
                }else{
                    let mapedData = result.message.map(items=>{
                        return `
                        <tr class="hover:bg-slate-50">
                            <td class="bg-white sticky left-0 z-10 w-40 px-4 py-2 border-r border-slate-200 whitespace-nowrap">${items['ProductName']}</td>
                            <td class="px-4 py-2 text-left">${items['productunid']}</td>
                            <td class="px-4 py-2 text-left">${items['dateAdded']}</td>

                            <td class="px-4 py-2 text-left" colspan="2">
                                 <button id="editProductName" class="bg-blue-500 text-white p-2 rounded-xl">Edit</button>
                             </td>
                        </tr>
                        `;
                    }).join("");
                    tbody.innerHTML=mapedData;
                    const editProductName = document.querySelectorAll("#editProductName");
                    editProductName.forEach(btn=>{
                        btn.addEventListener("click",e=>{
                            const element = e.currentTarget.closest("tr");
                            //get the values
                            const productName = element.children[0].textContent.trim();
                            const productunid = element.children[1].textContent.trim();
                            productPrevName=productName;
                            EditproductName.value=productName;
                            EditproductId.value=productunid;
                             editpopupModal.classList.remove('hidden');
                             console.log(productPrevName);
                        });
                    });
                }
            }
        }catch(jsonErr){
            throw new Error("Invalid JSON from server: " + text);
        }
    }catch(error){
        console.log("session expired/error" + error.message);
        return {success:false, error:error.message};
    }
}

addEventListener("DOMContentLoaded",()=>{
    

    const EditmodalClose = document.querySelector("#EditmodalClose");

    const addProductName = document.querySelector("#addProductName");
    const productName = document.querySelector("#productName");
    const productNameError = document.querySelector("#productNameError");

    const saveProductName = document.querySelector("#saveProductName");
    const EditproductName = document.querySelector("#EditproductName");
    const EditproductId = document.querySelector("#EditproductId");
    const editProductNameError = document.querySelector("#editProductNameError");

    const alertHeader = document.querySelector("#alertHeader");
    const alertBody = document.querySelector("#alertBody");
    const alertContainer = document.querySelector("#alertContainer");
    const alertMessage = document.querySelector('#alertMessage');

    setInterval(()=>{
        getProductsName(productPrevName);
    },2000);
    EditmodalClose.addEventListener("click",()=>{
        editpopupModal.classList.add('hidden');
    });
    addProductName.addEventListener("click",()=>{
        
        if(productName.value==""){
            productNameError.classList.add("text-red-100","font-medium");
            productNameError.innerHTML="product name is required";
        }else{
            productNameError.innerHTML="";
        }
        if(!productName.value==""){
            async function addProductName() {
                const productNameValue = sanitize(productName.value);
                const postData = {
                    addProductName:true,
                    productName: productNameValue
                 };
                try{
                    const response = await fetch('insertdata.php',{
                    method:"POST",
                    headers:{"Content-Type":"application/json"},
                    body:JSON.stringify(postData)
                });
                const text = await response.text();
                try{
                    const result = JSON.parse(text);
                    if(result.success){
                        //clear al the data in the inputs
                        alertMessage.classList.remove('hidden');
                        alertContainer.classList.add("bg-red-100");
                        alertHeader.innerHTML='⚠️ Alert!';
                        alertBody.innerHTML='Stock Updated';
                        setTimeout(()=>{
                            alertMessage.classList.add('hidden');
                            
                        },2000);
                        setTimeout(()=>{
                            productName.parentElement.parentElement.parentElement.classList.add("hidden");
                        },2500);
                        //clear inpus
                       productName.value='';
                        
                    }else{

                    }

                }catch(jsonErr){
                     throw new Error("Invalid JSON from server: " + text);
                }
                }catch(error){
                    console.log("session expired/error" + error.message);
                    return {success:false, error:error.message};
                }
                

            }
            addProductName();
        }
    });
    //save changes
    saveProductName.addEventListener("click",()=>{
        console.log(productPrevName);
        if(EditproductName.value==""){
            editProductNameError.classList.add("text-red-700","font-medium");
            editProductNameError.innerHTML="Product name is required";
        }
        if(EditproductName.value==productPrevName){
            editProductNameError.classList.add("text-red-700","font-medium");
            editProductNameError.innerHTML="No changes made";
        }
        if(EditproductName.value!=productPrevName && !EditproductName.value==""){
        editProductNameError.innerHTML="";
            async function updateProductName() {
                const productNameValue = sanitize(EditproductName.value);
                const EditproductIdValue = sanitize(EditproductId.value);
                const postData = {
                    updateProductName:true,
                    EditproductName: productNameValue,
                    EditproductId: EditproductIdValue
                 };
                try{
                    const response = await fetch('insertdata.php',{
                    method:"POST",
                    headers:{"Content-Type":"application/json"},
                    body:JSON.stringify(postData)
                });
                const text = await response.text();
                try{
                    const result = JSON.parse(text);
                    if(result.success){
                        //clear al the data in the inputs
                        alertMessage.classList.remove('hidden');
                        alertContainer.classList.add("bg-red-100");
                        alertHeader.innerHTML='⚠️ Alert!';
                        alertBody.innerHTML='Stock Updated';
                        setTimeout(()=>{
                            alertMessage.classList.add('hidden');
                            
                        },2000);
                        setTimeout(()=>{
                            EditproductName.parentElement.parentElement.parentElement.classList.add("hidden");
                        },2500);
                        //clear inpus
                       EditproductName.value='';
                        
                    }else{

                    }

                }catch(jsonErr){
                     throw new Error("Invalid JSON from server: " + text);
                }
                }catch(error){
                    console.log("session expired/error" + error.message);
                    return {success:false, error:error.message};
                }
                

            }
            updateProductName();
        }
    });   
});
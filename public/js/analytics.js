//alertStockExpiryBody
async function stock_expiry_alert() {
    const alertStockBody = document.querySelector("#alertStockExpiryBody");
    try{
        const response = await fetch('stock_expiry.php',{
            method:"GET",
            header:{"Content-Type":"application/json"}
    });
    const text = await response.text();
    try{
        const results = JSON.parse(text);
        console.log(results);
        if(results.success){
            if(results.message.length>0){
                const mapedData = results.message.map((items, index)=>{
                return `
                <tr>
                    <td class="whitespace-nowrap text-sm text-center p-2">${index +1}</td>
                    <td class="whitespace-nowrap text-sm text-center p-2">${items['productName']}</td>
                    <td class="whitespace-nowrap text-sm text-center p-2">${items['batchNumber']}</td>
                    <td class="whitespace-nowrap text-sm text-center p-2">${items['expiryDate']}</td>
                    <td class="whitespace-nowrap text-sm text-center p-2">sale priority</td>
                </tr>
                `;
            }).join(" ");
            alertStockBody.innerHTML = mapedData;
            }
            else{
                alertStockBody.innerHTML = `<tr>
          <td colspan="5" class="whitespace-nowrap text-sm text-center p-2">No alert </td>
        </tr>`;
            }
        }
    }catch(jsonError){
         throw new Error("Invalid JSON from server: " + text);
    }
    }
    catch(error){
        console.log("session expired/error" + error.message);
                    return {success:false, error:error.message};
    }
}
//  fast moving products &slow moving products
async function moving_stock() {
    const fastMovingBody = document.querySelector("#fastMovingBody");
    const slowMovingBody = document.querySelector("#slowMovingBody");
    try{
        const response = await fetch('moving_stock.php',{
            method:"GET",
            header:{"Content-Type":"application/json"}
    });
    const text = await response.text();
    try{
        const results = JSON.parse(text);
        console.log(results);
        if(results.success){
            console.log(results.fastmoving.length);
            if(results.fastmoving.length>0){
                const mapedData = results.fastmoving.map((items, index)=>{
                return `
                <tr>
                    <td class="whitespace-nowrap text-sm text-center p-2">${index +1}</td>
                    <td class="whitespace-nowrap text-sm text-center p-2">${items['productName']}</td>
                    <td class="whitespace-nowrap text-sm text-center p-2">${items['sales']}</td>
                </tr>
                `;
            }).join(" ");

            fastMovingBody.innerHTML = mapedData;
            }
            else{
                fastMovingBody.innerHTML = `<tr>
          <td colspan="5" class="whitespace-nowrap text-sm text-center p-2">No sales </td>
        </tr>`;
            }

            if(results.slowmoving.length>0){
                const mapedData = results.slowmoving.map((items, index)=>{
                return `
                <tr>
                    <td class="whitespace-nowrap text-sm text-center p-2">${index +1}</td>
                    <td class="whitespace-nowrap text-sm text-center p-2">${items['productName']}</td>
                    <td class="whitespace-nowrap text-sm text-center p-2">${items['sales']}</td>
                </tr>
                `;
            }).join(" ");

            slowMovingBody.innerHTML = mapedData;
            }
            else{
                slowMovingBody.innerHTML = `<tr>
          <td colspan="5" class="whitespace-nowrap text-sm text-center p-2">No sales </td>
        </tr>`;
            }
             
        }
    }catch(jsonError){
         throw new Error("Invalid JSON from server: " + text);
    }
    }
    catch(error){
        console.log("session expired/error" + error.message);
                    return {success:false, error:error.message};
    }
}
//  sales trend
async function sales_trend() {
    const graph = document.getElementById("salesChart");
   // const slaesalert = document.querySelector("#slaes_alert");
    const ctx = document.getElementById("salesChart").getContext("2d");
    try{
        const response = await fetch('sales_trend.php',{
            method:"GET",
            header:{"Content-Type":"application/json"}
    });
    const text = await response.text();
    try{
        const results = JSON.parse(text);
       // console.log(results.message.length);
        //extrect data 
        const sales= results.message;
        const labels = sales.map((item)=>{
            const date = new Date(item.saleDate);
            return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' }); // e.g., "Jul 8";
        });
        const values = sales.map(item=>item.totalSales);
        if(results.success){
           // console.log(results.message.length);
             if(results.message.length>0){
                // console.log("reach");
                //graph.classList.remove("hidden");
                const salesChart = new Chart(ctx, {
                    type: "line", // or 'bar'
                    data: {
                    labels: labels,
                    datasets: [{
                        label: "Daily Sales",
                        data: values,
                        fill: true,
                        borderColor: "#3b82f6",
                        backgroundColor: "rgba(59, 130, 246, 0.1)",
                        tension: 0.4,
                        pointRadius: 5,
                        pointHoverRadius: 7
                    }]
                    },
                    options: {
                    responsive: true,
                    plugins: {
                        legend: {
                        labels: {
                            color: "#1e3a8a"
                        }
                        },
                        tooltip: {
                        mode: 'index',
                        intersect: false,
                        },
                    },
                    scales: {
                        x: {
                        title: {
                            display: true,
                            text: 'Date',
                            color: "#1e3a8a"
                        },
                        ticks: {
                            color: "#334155"
                        }
                        },
                        y: {
                        title: {
                            display: true,
                            text: 'Sales (KES)',
                            color: "#1e3a8a"
                        },
                        ticks: {
                            color: "#334155"
                        },
                        beginAtZero: true
                        }
                    }
                    }
                });
             }else{
                console.log("rennach");
               // graph.classList.add("hidden");
                slaesalert.innerHTML="nr sales data available";
             }

        }
    }catch(jsonError){
         throw new Error("Invalid JSON from server: " + text);
    }
    }
    catch(error){
        console.log("session expired/error" + error.message);
                    return {success:false, error:error.message};
    }
}
//  low stock alert
async function low_stock_alert() {
    const alertStockBody = document.querySelector("#alertStockBody");
    try{
        const response = await fetch('get_low_stock.php',{
            method:"GET",
            header:{"Content-Type":"application/json"}
    });
    const text = await response.text();
    try{
        const results = JSON.parse(text);
        //console.log(results);
        if(results.success){
            if(results.message.length>0){
                const mapedData = results.message.map((items, index)=>{
                return `
                <tr>
                    <td class="whitespace-nowrap text-sm text-center p-2">${index +1}</td>
                    <td class="whitespace-nowrap text-sm text-center p-2">${items['productName']}</td>
                    <td class="whitespace-nowrap text-sm text-center p-2">${items['batchNumber']}</td>
                    <td class="whitespace-nowrap text-sm text-center p-2">Restock</td>
                </tr>
                `;
            }).join(" ");
            alertStockBody.innerHTML = mapedData;
            }
            else{
                alertStockBody.innerHTML = `<tr>
          <td colspan="5" class="whitespace-nowrap text-sm text-center p-2">No alert </td>
        </tr>`;
            }
        }
    }catch(jsonError){
         throw new Error("Invalid JSON from server: " + text);
    }
    }
    catch(error){
        console.log("session expired/error" + error.message);
                    return {success:false, error:error.message};
    }
}
//  alert history
async function alert_history() {
    const alertHistory = document.querySelector("#alertHistory");
    try{
        const response = await fetch('get_alert_history.php',{
            method:"GET",
            header:{"Content-Type":"application/json"}
    });
    const text = await response.text();
    try{
        const results = JSON.parse(text);
        //console.log(results);
        if(results.success){
            if(results.message.length>0){
                const mapedData = results.message.map((items, index)=>{
                return `
                <tr>
                    <td class="whitespace-nowrap text-sm text-center p-2">${index +1}</td>
                    <td class="whitespace-nowrap text-sm text-center p-2">${items['productName']}</td>
                    <td class="whitespace-nowrap text-sm text-center p-2">${items['batchNumber']}</td>
                    <td class="whitespace-nowrap text-sm text-center p-2">${items['alertType']}</td>
                    <td class="whitespace-nowrap text-sm text-center p-2">${items['created_at']}</td>
                </tr>
                `;
            }).join(" ");
            alertHistory.innerHTML = mapedData;
            }
            else{
                alertHistory.innerHTML = `<tr>
          <td colspan="5" class="whitespace-nowrap text-sm text-center p-2">No alert history </td>
        </tr>`;
            }
        }
    }catch(jsonError){
         throw new Error("Invalid JSON from server: " + text);
    }
    }
    catch(error){
        console.log("session expired/error" + error.message);
                    return {success:false, error:error.message};
    }
}
addEventListener("DOMContentLoaded",()=>{
    low_stock_alert();
    alert_history();
    sales_trend();
    moving_stock();
    stock_expiry_alert();
setInterval(()=>{
    low_stock_alert();
    alert_history();
    stock_expiry_alert();
},120000);
});
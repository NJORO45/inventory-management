addEventListener("DOMContentLoaded",()=>{
    const humbarger = document.querySelector("#humbarger");
    const humbargerIcon = document.querySelector("#humbargerIcon");
    const humbargerMenu = document.querySelector("#humbargerMenu");
    const popupModal = document.querySelector("#popupModal");
    const modalClose = document.querySelector("#modalClose");
    const modalParent = document.querySelector("#modalParent");
    humbarger.addEventListener("click",()=>{
        if(humbargerIcon.classList.contains('ri-menu-4-line')){
            humbargerIcon.classList.remove('ri-menu-4-line');
            humbargerIcon.classList.add('ri-close-large-fill');
        }else{
             humbargerIcon.classList.remove('ri-close-large-fill');
             humbargerIcon.classList.add('ri-menu-4-line');
           
        }
        if(humbargerMenu.classList.contains('left-[-200px]')){
            humbargerMenu.classList.remove('left-[-200px]');
            humbargerMenu.classList.add('left-0');
        }else{
            humbargerMenu.classList.remove('left-0');
            humbargerMenu.classList.add('left-[-200px]');
        }
    });
    modalParent.addEventListener("click",()=>{
        if(popupModal.classList.contains('hidden')){
            popupModal.classList.remove('hidden');
        }
    });
    modalClose.addEventListener("click",()=>{
        popupModal.classList.add('hidden');
        
    })
})
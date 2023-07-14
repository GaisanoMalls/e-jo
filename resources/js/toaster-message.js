document.addEventListener('DOMContentLoaded', () => {
    const toasterMessage = document.getElementById('toasterMessage');
    
    setTimeout(() => {
        var bsToast = new bootstrap.Toast(toasterMessage);
        bsToast.hide();
    }, 15000);
});
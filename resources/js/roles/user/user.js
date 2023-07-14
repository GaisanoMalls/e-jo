const axios = require('axios').default;

const uploadNewPhoto = document.getElementById('uploadNewPhoto');
const uploadedNewPhoto = document.getElementById('uploadedNewPhoto');
const inputFileErrorMsg = document.getElementById('inputFileErrorMsg');

if (uploadNewPhoto) {
    uploadNewPhoto.addEventListener('change', (e) => {
        const file = e.target.files[0];
        const fReader = new FileReader();

        fReader.onload = (event) => {
            const img = new Image();
            img.src = event.target.result;

            img.addEventListener('load', () => {
                uploadedNewPhoto.src = img.src;
                inputFileErrorMsg.textContent = "";
                inputFileErrorMsg.style.display = "none";
            });

            img.addEventListener('error', () => {
                uploadedNewPhoto.src = "";
                inputFileErrorMsg.style.display = "block";
                inputFileErrorMsg.textContent = "File type is not accepted. Please select an image file.";
            });
        };

        if (file) {
            fReader.readAsDataURL(file);
        }
    });
}

document.addEventListener('DOMContentLoaded', () => {
    const toasterMessage = document.getElementById('toasterMessage');

    setTimeout(() => {
        var bsToast = new bootstrap.Toast(toasterMessage);
        bsToast.hide();
    }, 8000);
});

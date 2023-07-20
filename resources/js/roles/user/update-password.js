// Working

const axios = require('axios');

const successMessage = document.getElementById('successMessage');
const updatePasswordForm = document.querySelector('#updatePasswordForm');

const submitForm = async (e) => {
    e.preventDefault();

    const formActionURL = updatePasswordForm.getAttribute('action');
    const formData = new FormData(updatePasswordForm);

    axios.post(formActionURL, formData)
        .then((response) => {
            const errorElements = updatePasswordForm.getElementsByClassName('custom__field__message');

            for (let i = 0; i < errorElements.length; i++) {
                errorElements[i].textContent = '';
            }

            if (response.data.message) {
                successMessage.style.display = 'block';
                successMessage.textContent = response.data.message;
            }
            resetForm();
        })
        .catch((error) => {
            if (error.response) {

                const errors = error.response.data.errors;
                Object.keys(errors).forEach(field => {
                    const errorElement = document.getElementById(`${field}Error`);
                    if (errorElement) {
                        errorElement.textContent = errors[field][0];
                        successMessage.style.display = 'none';
                    }
                });

                resetForm();
            }
        })

    // try {
    //     const response = await axios.post(formActionURL, formData);
    //     resetForm();

    //     const errorElements = updatePasswordForm.getElementsByClassName('custom__field__message');
    //     for (let i = 0; i < errorElements.length; i++) {
    //         errorElements[i].textContent = '';
    //     }

    //     successMessage.style.display = 'block';
    //     successMessage.textContent = response.data.message;

    // } catch (error) {
    //     if (error.response) {
    //         const errors = error.response.data.errors;

    //         Object.keys(errors).forEach(field => {
    //             const errorElement = document.getElementById(`${field}Error`);
    //             if (errorElement) {
    //                 errorElement.textContent = errors[field][0];
    //             }
    //         });

    //         resetForm();
    //     }
    // }
}
updatePasswordForm.addEventListener('submit', submitForm);

const resetForm = () => {
    updatePasswordForm.reset();
}

const updateProfileForm = document.querySelector('#updateProfileForm');

const submitForm = async (e) => {
    e.preventDefault();
    
    const formActionURL = updateProfileForm.getAttribute('action');
    const formData = new FormData(updateProfileForm);
}
updateProfileForm.addEventListener('submit', submitForm);
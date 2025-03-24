document.addEventListener("DOMContentLoaded", function () {
    // Toggle sidebar and header
    const toggleMenuButton = document.getElementById('toggle__more__menu');
    const sidebar = document.querySelector('.sidebar');
    const header = document.getElementById('page__main__header');

    toggleMenuButton.addEventListener('click', function () {
        sidebar.classList.toggle('close');
        header.classList.toggle('close');
    });

    // Clear modal input fields when modal is closed
    const modalForm = document.getElementById('modalForm');
    if (modalForm) {
        modalForm.addEventListener('hidden.bs.modal', function () {
            const form = modalForm.querySelector('form');
            if (form) {
                form.reset();
            }
        });
    }
});

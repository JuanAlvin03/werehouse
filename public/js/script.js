// For toast notifications

function showToast(type = 'info', title = '', message = '', duration = 4000) {
    const container = document.getElementById('toast-container');
    // Create toast element
    const toast = document.createElement('div');
    toast.className = `toast ${type}`;
    toast.style.setProperty('--duration', `${duration}ms`);
    toast.innerHTML = `
        <div class="toast-content">
            <div class="toast-title">${title}</div>
            <div class="toast-message">${message}</div>
        </div>
        <button class="toast-close" aria-label="Close">&times;</button>
        <div class="toast-progress"></div>
    `;
    // Remove helper with exit animation
    const removeToast = () => {
        if (toast.classList.contains('hide')) return;
        toast.classList.add('hide');
        toast.addEventListener('animationend', () => toast.remove(), { once: true });
    };
    // Close button event
    toast.querySelector('.toast-close').addEventListener('click', removeToast);
    // Auto-dismiss timer
    const timer = setTimeout(removeToast, duration);
    // Append to container
    container.appendChild(toast);
}

// For form submission confirmation modal
document.addEventListener('DOMContentLoaded', function () {
    // const form = document.getElementById('myForm');
    const forms = document.querySelectorAll('.need-confirm');
    const modal = document.getElementById('confirmModal');
    const cancelBtn = document.getElementById('cancelBtn');
    const confirmBtn = document.getElementById('confirmBtn');
    let isConfirmed = false;
    let selectedForm = null;
    forms.forEach(form => {
        form.addEventListener('submit', function (e) {
            if (!isConfirmed) {
                e.preventDefault(); // Stop the form from submitting immediately
                selectedForm = form;
                modal.classList.remove('hidden'); // Show your custom modal
            }
        });
    });
    cancelBtn.addEventListener('click', function () {
        modal.classList.add('hidden'); // Hide modal
    });
    confirmBtn.addEventListener('click', function () {
        isConfirmed = true; // Flag that confirmation passed
        modal.classList.add('hidden');
        selectedForm.submit(); // Trigger actual form submission
    });
});

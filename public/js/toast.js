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

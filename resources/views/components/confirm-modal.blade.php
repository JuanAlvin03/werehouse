<button id="deleteBtn">Delete Item</button>

<dialog id="confirmModal">
    <form method="dialog">
        <p id="modalMessage">Are you sure you want to proceed?</p>
        <button value="false">Cancel</button>
        <button value="true" autofocus>Confirm</button>
    </form>
</dialog>

<script>
    const modal = document.getElementById('confirmModal');
    const modalMessage = document.getElementById('modalMessage');
        
    function customConfirm(message) {
        modalMessage.textContent = message;
        modal.showModal(); // Opens as a modal with a backdrop
        
        return new Promise((resolve) => {
            modal.addEventListener('close', () => {
                // modal.returnValue equals the 'value' attribute of the clicked button
                resolve(modal.returnValue === 'true');
            }, { once: true });
        });
    }
    
    // Usage Example
    document.getElementById('deleteBtn').addEventListener('click', async () => {
        const isConfirmed = await customConfirm('Delete this record permanently?');
        if (isConfirmed) {
            console.log('Item deleted.');
        } else {
            console.log('Action canceled.');
        }
    });
</script>
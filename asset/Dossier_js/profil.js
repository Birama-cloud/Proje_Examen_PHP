document.addEventListener("DOMContentLoaded", function() {
    setTimeout(function() {
        var successMsg = document.getElementById('success-message');
        if (successMsg) {
            successMsg.style.display = 'none';
        }
    }, 3000);
});
document.addEventListener("DOMContentLoaded", function () {
    let modal = document.getElementById("modal");
    let modalText = document.getElementById("modalText");
    let closeModal = document.querySelector(".close");

    document.getElementById("adminBtn").addEventListener("click", function () {
        modalText.textContent = "Bienvenue sur le Tableau de Bord Administrateur.";
        modal.style.display = "block";
    });

    document.getElementById("clientBtn").addEventListener("click", function () {
        modalText.textContent = "Bienvenue sur le Tableau de Bord Client.";
        modal.style.display = "block";
    });

    closeModal.addEventListener("click", function () {
        modal.style.display = "none";
    });

    window.addEventListener("click", function (event) {
        if (event.target === modal) {
            modal.style.display = "none";
        }
    });
});
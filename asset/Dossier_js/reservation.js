document.getElementById("reservation-form").addEventListener("submit", function(event) {
    event.preventDefault(); 

    const dateDebut = document.getElementById("date_debut").value;
    const dateFin = document.getElementById("date_fin").value;

    const errorMessage = document.querySelector(".error-message");

    if (errorMessage) {
        errorMessage.remove();
    }

    if (new Date(dateDebut) > new Date(dateFin)) {
        const errorDiv = document.createElement("div");
        errorDiv.classList.add("error-message");
        errorDiv.textContent = "La date de début ne peut pas être après la date de fin.";
        document.querySelector("form").insertBefore(errorDiv, document.querySelector("button"));
        return;
    }

    alert("Réservation réussie !");
    this.submit();
});

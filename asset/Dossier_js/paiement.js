document.getElementById('paymentForm').addEventListener('submit', function (event) {
    event.preventDefault(); 
  
    const cardNumber = document.getElementById('card_number').value.trim();
    const expiryDate = document.getElementById('expiry_date').value.trim();
    const cvv = document.getElementById('cvv').value.trim();
    const cardHolder = document.getElementById('card_holder').value.trim();
  
    const messageDiv = document.getElementById('message');
  
    if (!cardNumber || !expiryDate || !cvv || !cardHolder) {
      messageDiv.textContent = "Tous les champs sont obligatoires.";
      messageDiv.className = "error";
      return;
    }

    const cardNumberRegex = /^\d{16}$/;
    if (!cardNumberRegex.test(cardNumber.replace(/\s/g, ''))) {
      messageDiv.textContent = "Le numéro de carte doit contenir 16 chiffres.";
      messageDiv.className = "error";
      return;
    }
  
    const expiryDateRegex = /^(0[1-9]|1[0-2])\/\d{2}$/;
    if (!expiryDateRegex.test(expiryDate)) {
      messageDiv.textContent = "La date d'expiration doit être au format MM/AA.";
      messageDiv.className = "error";
      return;
    }
  
    const cvvRegex = /^\d{3}$/;
    if (!cvvRegex.test(cvv)) {
      messageDiv.textContent = "Le CVV doit contenir 3 chiffres.";
      messageDiv.className = "error";
      return;
    }
 
    messageDiv.textContent = "Paiement en cours de traitement...";
    messageDiv.className = "success";
  
    // Envoi du formulaire (décommentez cette ligne pour soumettre le formulaire)
    // this.submit();
  });
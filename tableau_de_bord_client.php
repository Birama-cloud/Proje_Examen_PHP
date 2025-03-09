<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord Client</title>
    <link rel="stylesheet" href="asset/Dossier_css/tab_de_bord_admin.css">
</head>
<body>

    <div class="container">
        <h1>BIENVENUE SUR VOTRE TABLEAU DE BORD</h1>
        <h2 class="client">CLIENT</h2>

        <div class="box">
            <a href="http://localhost/Examen%20PHP/reservation.php" class="box-header">Réservation</a>
            <ul>
                <li>Rechercher des voitures disponibles par date.</li>
                <li>Effectuer une réservation.</li>
                <li>Annuler ou modifier une réservation.</li>
            </ul>
        </div>

        <div class="box">
            <a href="http://localhost/Examen%20PHP/paiement.php" class="box-header">Paiement</a>
            <ul>
                <li>Payer en ligne (intégration d'une API de paiement comme Stripe ou PayPal).</li>
                <li>Voir les détails des paiements.</li>
            </ul>
        </div>

        <div class="box">
            <a href="http://localhost/Examen%20PHP/profil.php" class="box-header">Gestion du profil</a>
            <ul>
                <li>Mettre à jour les informations personnelles.</li>
            </ul>
        </div>

        <div class="box">
            <a href="http://localhost/Examen%20PHP/notification.php" class="box-header">notifications</a>
            <ul>
                <li>Recevoir des e-mails de confirmation de réservation, des rappels et des reçus.</li>
                
            </ul>
        </div>
    </div>

    <script src="asset/Dossier_js/tab_de_bord_admin.js"></script>
</body>
</html>
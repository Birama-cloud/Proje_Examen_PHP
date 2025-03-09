<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord Administrateur</title>
    <link rel="stylesheet" href="asset/Dossier_css/tab_de_bord_admin.css">
</head>
<body>

    <div class="container">
        <h1>BIENVENUE SUR VOTRE TABLEAU DE BORD</h1>
        <h2 class="admin">ADMINISTRATEUR</h2>

        <div class="box">
            <a href="http://localhost/Examen%20PHP/gestion_de_voiture.php" class="box-header">Gestion des véhicules</a>
            <ul>
                <li>Ajouter, modifier, supprimer des voitures.</li>
                <li>Mettre à jour le statut des voitures (disponible, louée, en maintenance).</li>
            </ul>
        </div>

        <div class="box">
            <a href="http://localhost/Examen%20PHP/gestion_reservation.php" class="box-header">Gestion des réservations</a>
            <ul>
                <li>Valider ou annuler les réservations.</li>
                <li>Voir les détails des réservations (client, voiture, dates).</li>
            </ul>
        </div>

        <div class="box">
            <a href="http://localhost/Examen%20PHP/gestion_paiement.php" class="box-header">Gestion des paiements</a>
            <ul>
                <li>Voir les paiements associés aux réservations.</li>
                <li>Générer des reçus.</li>
            </ul>
        </div>

        <div class="box">
            <a href="http://localhost/Examen%20PHP/rapport.php" class="box-header">Rapports</a>
            <ul>
                <li>Générer des rapports sur l'utilisation des véhicules.</li>
                <li>Analyser les revenus par période, mode de paiement, etc.</li>
                <li>Voir les statistiques des réservations (confirmées, annulées, en attente).</li>
            </ul>
        </div>
    </div>

    <script src="asset/Dossier_js/tab_de_bord_admin.js"></script>
</body>
</html>
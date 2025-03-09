<?php
session_start();
require 'config.php';

$sql = "SELECT p.id_paiement, p.date_paiement, p.montant, p.mode_paiement, 
               r.id_reservation, r.date_debut, r.date_fin, 
               c.nom_client AS client_nom, c.email AS client_email, 
               v.marque, v.modele, v.plaque_immatriculation
        FROM paiement p
        JOIN reservations r ON p.id_reservation = r.id_reservation
        JOIN client c ON r.id_client = c.id_client
        JOIN voitures v ON r.id_voitures = v.id_voitures
        ORDER BY p.date_paiement DESC";

$stmt = $pdo->query($sql);
$paiement = $stmt->fetchAll();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["ajouter_paiement"])) {
    $id_reservation = $_POST["id_reservation"];
    $montant = $_POST["montant"];
    $mode_paiement = $_POST["mode_paiement"];
    $date_paiement = date("Y-m-d");

    $sql = "INSERT INTO paiement (id_reservation, montant, mode_paiement, date_paiement) 
            VALUES (?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    if ($stmt->execute([$id_reservation, $montant, $mode_paiement, $date_paiement])) {
        echo "Paiement enregistré avec succès.";
    } else {
        echo "Erreur lors de l'enregistrement du paiement.";
    }
}

if (isset($_GET["supprimer"])) {
    $id_paiement = $_GET["supprimer"];
    $sql = "DELETE FROM paiement WHERE id_paiement = ?";
    $stmt = $pdo->prepare($sql);
    if ($stmt->execute([$id_paiement])) {
        header("Location: gestion_paiements.php");
        exit();
    } else {
        echo "Erreur lors de la suppression.";
    }
}

$sql_reservations = "SELECT r.id_reservation, nom_client AS client_nom, v.marque, v.modele 
                     FROM reservations r
                     JOIN client c ON r.id_client = c.id_client
                     JOIN voitures v ON r.id_voitures = v.id_voitures
                     WHERE r.statut = 'confirmée'";

$stmt = $pdo->query($sql_reservations);
$reservations = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="asset/Dossier_css/g_paiement.css">
    <title>Gestion des Paiements 💰</title>
</head>
<body>
    <h2>Gestion des Paiements 💰</h2>
    <h3>Historique des paiements</h3>
    <table>
        <tr>
            <th>ID</th>
            <th>Client</th>
            <th>Véhicule</th>
            <th>Date Début</th>
            <th>Date Fin</th>
            <th>Montant</th>
            <th>Mode de Paiement</th>
            <th>Date Paiement</th>
            <th>Action</th>
        </tr>
        <?php foreach ($paiement as $p): ?>
            <tr>
                <td><?= htmlspecialchars($p["id_paiement"]); ?></td>
                <td><?= htmlspecialchars($p["client_nom"]); ?></td>
                <td><?= htmlspecialchars($p["marque"]) . " " . htmlspecialchars($p["modele"]); ?></td>
                <td><?= htmlspecialchars($p["date_debut"]); ?></td>
                <td><?= htmlspecialchars($p["date_fin"]); ?></td>
                <td><?= htmlspecialchars($p["montant"]); ?> €</td>
                <td><?= htmlspecialchars($p["mode_paiement"]); ?></td>
                <td><?= htmlspecialchars($p["date_paiement"]); ?></td>
                <td>
                    <a href="gestion_paiements.php?supprimer=<?= $p["id_paiement"]; ?>" class="delete" onclick="return confirm('Voulez-vous vraiment supprimer ce paiement ?');">❌ Supprimer</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
    
    <h3>Enregistrer un paiement</h3>
    <form method="POST">
        <label>Réservation :</label>
        <select name="id_reservation" required>
            <?php foreach ($reservations as $res): ?>
                <option value="<?= $res["id_reservation"]; ?>">
                    <?= htmlspecialchars($res["client_nom"]) . " - " . htmlspecialchars($res["marque"]) . " " . htmlspecialchars($res["modele"]); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <br><hr>
        <label>Montant (€) :</label>
        <input type="number" name="montant" step="0.01" required><br><hr>
        <label>Mode de paiement :</label>
        <select name="mode_paiement" required>
            <option value="carte">Carte bancaire</option>
            <option value="virement">Virement</option>
            <option value="espèces">Espèces</option>
        </select>
        <br><br>
        <button type="submit" name="ajouter_paiement">Enregistrer</button>
    </form>
    <br> <hr>
    <a href="http://localhost/Examen%20PHP/tableau_de_bord_admin.php" class="button">⬅️ Retour au Tableau de Bord</a>
</body>
</html>

<?php
session_start();
require 'config.php';

$stmt = $pdo->prepare("SELECT * FROM voitures WHERE statut = 'disponible'");
$stmt->execute();
$voitures = $stmt->fetchAll();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_voiture = $_POST["id_voitures"];
    $date_debut = $_POST["date_debut"];
    $date_fin = $_POST["date_fin"];

    $date_actuelle = date("Y-m-d");

    if (strtotime($date_debut) >= strtotime($date_fin)) {
        echo "<p style='color:red;'>Erreur : La date de début doit être avant la date de fin.</p>";
    } elseif ($date_debut < $date_actuelle) {
        echo "<p style='color:red;'>Erreur : La date de début ne peut pas être dans le passé.</p>";
    } else {
        $sql = "INSERT INTO reservations (id_client, id_voitures, date_debut, date_fin, statut) 
                VALUES (?, ?, ?, ?, 'en attente')";
        $stmt = $pdo->prepare($sql);
    
        $id_client = filter_input(INPUT_POST, 'id_client', FILTER_SANITIZE_NUMBER_INT);
        $id_voitures = filter_input(INPUT_POST, 'id_voitures', FILTER_SANITIZE_NUMBER_INT);

        if ($stmt->execute([$id_client, $id_voitures, $date_debut, $date_fin])) {
            $update_stmt = $pdo->prepare("UPDATE voitures SET statut = 'louée' WHERE id_voitures = ?");
            if ($update_stmt->execute([$id_voitures])) {
                echo "<p style='color:green;'>Réservation effectuée avec succès ! </p>";
            } else {
                echo "<p style='color:red;'>Erreur lors de la mise à jour du statut de la voiture.</p>";
            }
        } else {
            echo "<p style='color:red;'>Erreur lors de la réservation.</p>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="asset/Dossier_css/reservation.css">
    <title>Reserver une voiture</title>
</head>
<body>

    <form method="POST">
        <label>Choisissez une voiture :</label>
        <select name="id_voitures" required>
            <?php foreach ($voitures as $voiture): ?>
                <option value="<?= htmlspecialchars($voiture["id_voitures"]); ?>">
                    <?= htmlspecialchars($voiture["marque"]) . " " . htmlspecialchars($voiture["modele"]) . " - " . htmlspecialchars($voiture["plaque_immatriculation"]); ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label>Date de début :</label>
        <input type="date" name="date_debut" required>

        <label>Date de fin :</label>
        <input type="date" name="date_fin" required>

        <button type="submit">Reserver</button>
    </form>

    <script src="asset/Dossier_js/reservation.js"></script>

</body>
</html>
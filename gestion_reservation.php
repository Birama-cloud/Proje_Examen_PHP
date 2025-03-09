<?php
session_start();
require 'config.php';

$sql = "SELECT * FROM reservations"; 
$stmt = $pdo->query($sql);



try {
    $stmt = $pdo->query($sql);
    $reservations = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Erreur SQL : " . $e->getMessage());
}




$sql = "SELECT r.id_reservation, r.date_debut, r.date_fin, r.statut, 
               nom_client AS client_nom, c.email AS client_email, 
               v.marque, v.modele, v.plaque_immatriculation
        FROM reservations r
        JOIN client c ON r.id_client = c.id_client
        JOIN voitures v ON r.id_voitures = v.id_voitures  
        ORDER BY r.date_debut DESC";

$stmt = $pdo->query($sql);
$reservations = $stmt->fetchAll();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["modifier"])) {
    $id_reservation = filter_input(INPUT_POST, "id_reservation", FILTER_VALIDATE_INT);
    $statut = filter_input(INPUT_POST, "statut", FILTER_SANITIZE_STRING);

    if ($id_reservation && in_array($statut, ["en attente", "confirmée", "annulée"])) {
        $sql = "UPDATE reservations SET statut = ? WHERE id_reservation = ?";
        $stmt = $pdo->prepare($sql);
        if ($stmt->execute([$statut, $id_reservation])) {
            $_SESSION['message'] = 'Réservation mise à jour avec succès.';
            header("Location: gestion_reservation.php");
            exit();
        } else {
            echo "<p style='color:red;'>Erreur lors de la modification de la réservation.</p>";
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["supprimer"])) {
    $id_reservation = filter_input(INPUT_GET, "supprimer", FILTER_VALIDATE_INT);

    if ($id_reservation) {
        $stmt = $pdo->prepare("SELECT id_reservation FROM reservations WHERE id_reservation = ?");
        $stmt->execute([$id_reservation]);

        if ($stmt->fetch()) {
            $sql = "DELETE FROM reservations WHERE id_reservation = ?";
            $stmt = $pdo->prepare($sql);
            if ($stmt->execute([$id_reservation])) {
                $_SESSION['message'] = 'Réservation supprimée avec succès.';
                header("Location: gestion_reservations.php");
                exit();
            } else {
                echo "<p style='color:red;'>Erreur lors de la suppression.</p>";
            }
        } else {
            echo "<p style='color:red;'>Réservation introuvable.</p>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="asset/Dossier_css/g_reservation.css">
    <title>Gestion des Réservations</title>
</head>
<body>
    <h2>Gestion des Réservations 📅</h2>

    <?php if (isset($_SESSION['message'])): ?>
        <p style="color: green;"><?= $_SESSION['message']; unset($_SESSION['message']); ?></p>
    <?php endif; ?>

    <h3>Liste des réservations</h3>
    <table>
        <tr>
            <th>ID</th>
            <th>Client</th>
            <th>Email</th>
            <th>Véhicule</th>
            <th>Plaque</th>
            <th>Date début</th>
            <th>Date fin</th>
            <th>Statut</th>
            <th>Action</th>
        </tr>
        <?php foreach ($reservations as $res): ?>
            <tr>
                <td><?= htmlspecialchars($res["id_reservation"]); ?></td>
                <td><?= htmlspecialchars($res["client_nom"]); ?></td>
                <td><?= htmlspecialchars($res["client_email"]); ?></td>
                <td><?= htmlspecialchars($res["marque"]) . " " . htmlspecialchars($res["modele"]); ?></td>
                <td><?= htmlspecialchars($res["plaque_immatriculation"]); ?></td>
                <td><?= htmlspecialchars($res["date_debut"]); ?></td>
                <td><?= htmlspecialchars($res["date_fin"]); ?></td>
                <td>
                    <form method="POST">
                        <input type="hidden" name="id_reservation" value="<?= htmlspecialchars($res["id_reservation"]); ?>">
                        <select name="statut">
                            <option value="en attente" <?= $res["statut"] == "en attente" ? "selected" : ""; ?>>En attente</option>
                            <option value="confirmée" <?= $res["statut"] == "confirmée" ? "selected" : ""; ?>>Confirmée</option>
                            <option value="annulée" <?= $res["statut"] == "annulée" ? "selected" : ""; ?>>Annulée</option>
                        </select>
                        <button type="submit" name="modifier">Modifier</button>
                    </form>
                </td>
                <td>
                    <a href="gestion_reservations.php?supprimer=<?= htmlspecialchars($res["id_reservation"]); ?>" 
                       onclick="return confirm('Voulez-vous vraiment supprimer cette réservation ?');">❌ Supprimer</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

    <br> <hr>
    <a href="http://localhost/Examen%20PHP/tableau_de_bord_admin.php"> ⬅️ Retour au Tableau de Bord</a>
</body>
</html>

<?php
session_start();
require 'config.php';

$sql = "SELECT * FROM voitures ORDER BY statut DESC";
$stmt = $pdo->query($sql);
$voitures = $stmt->fetchAll();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["ajouter"])) {
    $marque = $_POST["marque"];
    $modele = $_POST["modele"];
    $annee_fabrication = $_POST["annee_fabrication"];
    $plaque = $_POST["plaque"];
    $statut = $_POST["statut"];

    $sql = "INSERT INTO voitures (marque, modele, annee_fabrication, plaque_immatriculation, statut) 
            VALUES (?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    if ($stmt->execute([$marque, $modele, $annee_fabrication, $plaque, $statut])) {
        header("Location: gestion_de_voiture.php");
        exit();
    } else {
        echo "Erreur lors de l'ajout du véhicule.";
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["modifier"])) {
    $id_voiture = $_POST["id_voitures"];
    $statut = $_POST["statut"];

    $sql = "UPDATE voitures SET statut = ? WHERE id_voitures = ?";
    $stmt = $pdo->prepare($sql);
    if ($stmt->execute([$statut, $id_voiture])) {
        header("Location: gestion_de_voiture.php");
        exit();
    } else {
        echo "Erreur lors de la modification du véhicule.";
    }
}

if (isset($_GET["supprimer"])) {
    $id_voiture = $_GET["supprimer"];
    $sql = "DELETE FROM voitures WHERE id_voitures = ?";
    $stmt = $pdo->prepare($sql);
    if ($stmt->execute([$id_voiture])) {
        header("Location: gestion_de_voiture.php");
        exit();
    } else {
        echo "Erreur lors de la suppression.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="asset/Dossier_css/g_voiture.css">
    <title>Gestion des Véhicules</title>
</head>
<body>

    <h2>Gestion des Véhicules 🚗</h2>

    <h3>Ajouter un véhicule</h3>
    <form method="POST">
        <input type="text" name="marque" placeholder="Marque" required>
        <input type="text" name="modele" placeholder="Modèle" required>
        <input type="number" name="annee_fabrication" placeholder="Année" required>
        <input type="text" name="plaque" placeholder="Plaque d'immatriculation" required>
        <select name="statut">
            <option value="disponible">Disponible</option>
            <option value="louee">Louée</option>
            <option value="maintenance">En maintenance</option>
        </select>
        <button type="submit" name="ajouter">Ajouter</button>
    </form>

    <h3>Liste des véhicules</h3>
    <table>
        <tr>
            <th>ID</th>
            <th>Marque</th>
            <th>Modèle</th>
            <th>Année</th>
            <th>Plaque</th>
            <th>Statut</th>
            <th>Action</th>
        </tr>
        <?php foreach ($voitures as $voiture): ?>
            <tr>
                <td><?= htmlspecialchars($voiture["id_voitures"]); ?></td>
                <td><?= htmlspecialchars($voiture["marque"]); ?></td>
                <td><?= htmlspecialchars($voiture["modele"]); ?></td>
                <td><?= htmlspecialchars($voiture["annee_fabrication"]); ?></td>
                <td><?= htmlspecialchars($voiture["plaque_immatriculation"]); ?></td>
                <td>
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="id_voitures" value="<?= htmlspecialchars($voiture["id_voitures"]); ?>">
                        <select name="statut">
                            <option value="disponible" <?= $voiture["statut"] == "disponible" ? "selected" : ""; ?>>Disponible</option>
                            <option value="louee" <?= $voiture["statut"] == "louee" ? "selected" : ""; ?>>Louée</option>
                            <option value="maintenance" <?= $voiture["statut"] == "maintenance" ? "selected" : ""; ?>>En maintenance</option>
                        </select>
                        <button type="submit" name="modifier" class="edit">Modifier</button>
                    </form>
                </td>
                <td>
                    <a href="gestion_de_voiture.php?supprimer=<?= htmlspecialchars($voiture["id_voitures"]); ?>" 
                       class="action-btn delete" 
                       onclick="return confirm('Voulez-vous vraiment supprimer ce véhicule ?');">
                       ❌ Supprimer
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

    <br><hr>
    <a href="http://localhost/Examen%20PHP/tableau_de_bord_admin.php" style="text-decoration: none; font-weight: bold; color: #3498db;">
        ⬅️ Retour au Tableau de Bord
    </a>

</body>
</html>
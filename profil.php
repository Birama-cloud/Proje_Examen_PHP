<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
require 'config.php'; 


if (!isset($_SESSION['id_client'])) {
    header("Location: connection.php"); 
    exit;
}

$id_client = $_SESSION['id_client'];

try {
    $pdo = new PDO('mysql:host=localhost;dbname=gestion_voiture', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}


$sql_client = "SELECT * FROM client WHERE id_client = ?";
$stmt = $pdo->prepare($sql_client);
$stmt->execute([$id_client]);
$client = $stmt->fetch(PDO::FETCH_ASSOC);


if (!$client) {
    die("Erreur : Client introuvable dans la base de données.");
}

$successMessage = '';


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $nom_client     = htmlspecialchars($_POST['nom_client'] ?? '');
    $prenom_client  = htmlspecialchars($_POST['prenom_client'] ?? '');
    $email          = htmlspecialchars($_POST['email'] ?? '');
    $date_naissance = htmlspecialchars($_POST['date_naissance'] ?? '');
    $adresse        = htmlspecialchars($_POST['adresse'] ?? '');
    $numero_permis  = htmlspecialchars($_POST['numero_permis'] ?? '');

    $sql_update = "UPDATE client 
                   SET nom_client = ?, 
                       prenom_client = ?, 
                       email = ?, 
                       date_naissance = ?, 
                       adresse = ?, 
                       numero_permis = ?
                   WHERE id_client = ?";
    $stmt_upd = $pdo->prepare($sql_update);
    $stmt_upd->execute([
        $nom_client,
        $prenom_client,
        $email,
        $date_naissance,
        $adresse,
        $numero_permis,
        $id_utilisateur
    ]);

    $successMessage = "Mise à jour réussie.";

    
    $stmt = $pdo->prepare($sql_client);
    $stmt->execute([$id_client]);
    $client = $stmt->fetch(PDO::FETCH_ASSOC);
}


$sql_reserv = "SELECT r.id_reservation, r.date_debut, r.date_fin, r.statut, 
                      COALESCE(p.montant, 'Non payé') AS paiement
               FROM reservations r
               LEFT JOIN paiement p ON r.id_reservation = p.id_reservation
               WHERE r.id_client = ?";
$stmt_res = $pdo->prepare($sql_reserv);
$stmt_res->execute([$id_client]);
$reservations = $stmt_res->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Profil Client</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="asset/Dossier_css/profil.css">
</head>
<body>
<div class="container">
    <h1>Mon Profil</h1>

    <?php if (!empty($successMessage)): ?>
        <div class="success" id="success-message">
            <?php echo htmlspecialchars($successMessage); ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="">
        <label for="nom_client">Nom</label>
        <input type="text" id="nom_client" name="nom_client" value="<?php echo htmlspecialchars($client['nom_client'] ?? ''); ?>" required>

        <label for="prenom_client">Prénom</label>
        <input type="text" id="prenom_client" name="prenom_client" value="<?php echo htmlspecialchars($client['prenom_client'] ?? ''); ?>" required>

        <label for="email">Email</label>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($client['email'] ?? ''); ?>" required>

        <label for="date_naissance">Date de naissance</label>
        <input type="date" id="date_naissance" name="date_naissance" value="<?php echo htmlspecialchars($client['date_naissance'] ?? ''); ?>" required>

        <label for="adresse">Adresse</label>
        <input type="text" id="adresse" name="adresse" value="<?php echo htmlspecialchars($client['adresse'] ?? ''); ?>" required>

        <label for="numero_permis">Numéro de permis</label>
        <input type="text" id="numero_permis" name="numero_permis" value="<?php echo htmlspecialchars($client['numero_permis'] ?? ''); ?>" required>

        <button type="submit" name="update_profile">Mettre à jour</button>
    </form>

    <h2>Historique des Réservations</h2>
    <?php if (!empty($reservations)): ?>
        <table>
            <tr>
                <th>Date Début</th>
                <th>Date Fin</th>
                <th>Statut</th>
                <th>Paiement</th>
            </tr>
            <?php foreach ($reservations as $res): ?>
                <tr>
                    <td><?php echo htmlspecialchars($res['date_debut']); ?></td>
                    <td><?php echo htmlspecialchars($res['date_fin']); ?></td>
                    <td><?php echo htmlspecialchars($res['statut']); ?></td>
                    <td><?php echo htmlspecialchars($res['paiement']); ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>Aucune réservation trouvée.</p>
    <?php endif; ?>
</div>

<script src="asset/Dossier_js/profil.js"></script>
</body>
</html>

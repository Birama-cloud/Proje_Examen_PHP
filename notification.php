<?php
session_start();
require 'config.php';

$id_client = $_SESSION["id_client"];

$sql = "SELECT n.message, n.date_notif 
        FROM notification n
        WHERE n.id_client = ?
        ORDER BY n.date_notif DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id_client]);
$notification = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications</title>
    <link rel="stylesheet" href="asset/Dossier_css/notification.css">
</head>
<body>

<div class="container">
    <h2>📢 Vos Notifications</h2>

    <?php if (count($notification) > 0): ?>
        <?php foreach ($notification as $notif): ?>
            <div class="notification">
                <i class="fas fa-bell notif-icon"></i>
                <div class="notif-text">
                    <p class="message"><?= htmlspecialchars($notif["message"]); ?></p>
                    <span class="date"><?= htmlspecialchars($notif["date_notif"]); ?></span>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Aucune notification pour l'instant.</p>
    <?php endif; ?>

    <a href="http://localhost/Examen%20PHP/tableau_de_bord_client.php" class="button">⬅️ Retour au Tableau de Bord</a>
</div>

</body>
</html>

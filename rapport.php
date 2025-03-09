<?php
session_start();
require 'config.php';


$sql_total = "SELECT COUNT(*) AS total_paiements, SUM(montant) AS total_revenus FROM paiement";
$stmt = $pdo->query($sql_total);
$stats = $stmt->fetch();

$sql_mois = "SELECT DATE_FORMAT(date_paiement, '%Y-%m') AS mois, SUM(montant) AS revenus 
             FROM paiement 
             GROUP BY mois 
             ORDER BY mois DESC";
$stmt = $pdo->query($sql_mois);
$revenus_mensuels = $stmt->fetchAll();

$sql_moyenne = "SELECT AVG(montant) AS moyenne_paiement FROM paiement";
$stmt = $pdo->query($sql_moyenne);
$moyenne_paiement = $stmt->fetch()["moyenne_paiement"];

$sql_modes = "SELECT mode_paiement, COUNT(*) AS nombre, SUM(montant) AS total 
              FROM paiement 
              GROUP BY mode_paiement";
$stmt = $pdo->query($sql_modes);
$modes_paiement = $stmt->fetchAll();
?>



<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="asset/Dossier_css/rapport.css">
    <title>Rapports Financiers</title>
</head>
<body>
    <div class="container">
        <h2>📊 Rapports Financiers</h2>
        <div class="card">
            <h3>📈 Statistiques générales</h3>
            <p>Total des paiements : <strong><?= $stats["total_paiements"]; ?></strong></p>
            <p>Total des revenus : <strong><?= number_format($stats["total_revenus"], 2); ?> FCFA</strong></p>
            <p>Moyenne d'un paiement : <strong><?= number_format($moyenne_paiement, 2); ?> FCFA</strong></p>
        </div>

        <div class="card">
            <h3>💰 Revenus par mois</h3>
            <table>
                <tr>
                    <th>Mois</th>
                    <th>Revenus (FCFA)</th>
                </tr>
                <?php foreach ($revenus_mensuels as $mois): ?>
                    <tr>
                        <td><?= htmlspecialchars($mois["mois"]); ?></td>
                        <td><?= number_format($mois["revenus"], 2); ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>

        <div class="card">
            <h3>💳 Répartition des paiements</h3>
            <table>
                <tr>
                    <th>Mode de paiement</th>
                    <th>Nombre</th>
                    <th>Total (FCFA)</th>
                </tr>
                <?php foreach ($modes_paiement as $mode): ?>
                    <tr>
                        <td><?= htmlspecialchars($mode["mode_paiement"]); ?></td>
                        <td><?= $mode["nombre"]; ?></td>
                        <td><?= number_format($mode["total"], 2); ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>

        <div class="card">
            <canvas id="revenusChart"></canvas>
        </div>

        <a href="http://localhost/Examen%20PHP/tableau_de_bord_admin.php">Retour au Tableau de Bord</a>
    </div>

    <script>
        var ctx = document.getElementById('revenusChart').getContext('2d');
        var chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: [<?php foreach ($revenus_mensuels as $mois) { echo "'" . $mois["mois"] . "',"; } ?>],
                datasets: [{
                    label: 'Revenus mensuels (€)',
                    data: [<?php foreach ($revenus_mensuels as $mois) { echo $mois["revenus"] . ","; } ?>],
                    backgroundColor: 'rgba(75, 192, 192, 0.7)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1,
                    borderRadius: 5
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    </script>
</body>
</html>

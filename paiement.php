<?php
$host = 'localhost';
$dbname = 'gestion_voiture';
$username = 'root';
$password = '';

require 'config.php'; 

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("<div class='message error'>Erreur de connexion à la base de données : " . $e->getMessage() . "</div>");
}

$message = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_POST['id_reservation'], $_POST['montant'], $_POST['date_reservation'], $_POST['mode_paiement'])) {
        $message = "<div class='message error'>Tous les champs sont obligatoires.</div>";
    } else {
        $id_reservation = intval($_POST['id_reservation']);
        $montant = floatval($_POST['montant']);
        $date_reservation = $_POST['date_reservation']; 
        $mode_paiement = trim($_POST['mode_paiement']);
        $date_paiement = date('Y-m-d'); 

        
        $modes_valides = ['Carte', 'Espèces', 'Chèque'];
        if (!in_array($mode_paiement, $modes_valides)) {
            $message = "<div class='message error'>Mode de paiement invalide.</div>";
        } else {
            try {
                $stmt = $pdo->prepare('INSERT INTO paiement (id_reservation, montant, date_paiement, mode_paiement) VALUES (?, ?, ?, ?)');
                $stmt->execute([$id_reservation, $montant, $date_paiement, $mode_paiement]);
                $message = "<div class='message success'>Paiement enregistré avec succès pour la réservation du <strong>$date_reservation</strong>. Mode de paiement: <strong>$mode_paiement</strong>.</div>";
            } catch (PDOException $e) {
                $message = "<div class='message error'>Erreur SQL : " . $e->getMessage() . "</div>";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="asset/Dossier_css/paiement.css">
    <title>Formulaire de Paiement</title>
</head>
<body>
    <div class="payment-container">
        <h2>Paiement de Réservation</h2>
        <?= $message ?>
        <form id="payment-form" method="POST">
            <div class="form-group">
                <label for="id_reservation">ID Réservation :</label>
                <input type="number" id="id_reservation" name="id_reservation" required>
            </div>
            <div class="form-group">
                <label for="montant">Montant :</label>
                <input type="number" id="montant" name="montant" required step="0.01" placeholder="Montant à payer">
            </div>
            <div class="form-group">
                <label for="date_reservation">Date de Réservation :</label>
                <input type="date" id="date_reservation" name="date_reservation" required>
            </div>
            <div class="form-group">
                <label for="mode_paiement">Mode de Paiement :</label>
                <select id="mode_paiement" name="mode_paiement" required>
                    <option value="Carte">Carte de Crédit</option>
                    <option value="Espèces">Espèces</option>
                    <option value="Chèque">Chèque</option>
                </select>
            </div>
            <div class="form-group">
                <button type="submit" id="submit-button">Payer</button>
            </div>
            <div id="payment-error" class="message error" style="display: none;"></div>
        </form>
    </div>
    <script>
        document.getElementById("payment-form").addEventListener("submit", function (e) {
            let id_reservation = document.getElementById("id_reservation").value;
            let montant = document.getElementById("montant").value;
            let date_reservation = document.getElementById("date_reservation").value;
            let mode_paiement = document.getElementById("mode_paiement").value;
            let errorDiv = document.getElementById("payment-error");

            errorDiv.style.display = "none";
            errorDiv.innerHTML = "";

            if (!id_reservation || !montant || !date_reservation || !mode_paiement) {
                errorDiv.innerHTML = "Tous les champs sont obligatoires.";
                errorDiv.style.display = "block";
                e.preventDefault();
                return;
            }

            let modesValides = ["Carte", "Espèces", "Chèque"];
            if (!modesValides.includes(mode_paiement)) {
                errorDiv.innerHTML = "Mode de paiement invalide.";
                errorDiv.style.display = "block";
                e.preventDefault();
                return;
            }
        });
    </script>
</body>
</html>
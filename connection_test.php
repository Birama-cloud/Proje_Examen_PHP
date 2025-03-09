<?php
session_start();
require 'config.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
    $mot_de_passe = $_POST["mot_de_passe"];

    $stmt = $pdo->prepare("SELECT * FROM client WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($mot_de_passe, $user["mot_de_passe"])) {
        $_SESSION["id_client"] = $user["id_client"];
        $_SESSION["nom"] = $user["nom"];
        header("Location: http://localhost/Examen%20PHP/tableau_de_bord_client.php"); 
        exit();
    } else {
        $error = "Email ou mot de passe incorrect.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="asset/Dossier_css/connection_test.css">
    <title>Connexion Client</title>
</head>
<body>

    <div class="container">
        <h2>Connexion</h2>

        <?php if (isset($error)) : ?>
            <p class="error"><?= $error; ?></p>
        <?php endif; ?>

        <form method="POST">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="mot_de_passe" placeholder="Mot de passe" required>
            <button type="submit">Se connecter</button>
        </form>
    </div>

</body>
</html>

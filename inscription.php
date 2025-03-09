<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);


require 'config.php';


$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $nom_client     = trim($_POST['nom_client']);
    $prenom_client  = trim($_POST['prenom_client']);
    $email          = trim($_POST['email']);
    $mot_de_passe   = password_hash($_POST['mot_de_passe'], PASSWORD_BCRYPT);
    $date_naissance = $_POST['date_naissance'];
    $adresse        = trim($_POST['adresse']);
    $numero_permis  = trim($_POST['numero_permis']);

   
    $checkEmail = $pdo->prepare("SELECT * FROM client WHERE email = ?");
    $checkEmail->execute([$email]);
    if ($checkEmail->rowCount() > 0) {
        $message = "Cet email est déjà utilisé.";
        $message_class = 'error';
    } else {
      
        $stmt = $pdo->prepare("INSERT INTO client (nom_client, prenom_client, email, mot_de_passe, date_naissance, adresse, numero_permis) VALUES (?, ?, ?, ?, ?, ?, ?)");
        
      
        if ($stmt->execute([$nom_client, $prenom_client, $email, $mot_de_passe, $date_naissance, $adresse, $numero_permis])) {
            $message = "Inscription réussie ! Vous pouvez maintenant vous connecter.";
            $message_class = 'success';
        } else {
            
            print_r($stmt->errorInfo());
            $message = "Erreur lors de l'inscription. Veuillez réessayer.";
            $message_class = 'error';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="asset/Dossier_css/inscription.css">
    <title>Inscription</title>
</head>
<body>
    
<form id="inscriptionForm" method="POST">
      <label for="nom_client">Nom :</label>
      <input type="text" id="nom_client" name="nom_client" required>
      
      <label for="prenom_client">Prénom :</label>
      <input type="text" id="prenom_client" name="prenom_client" required>
      
      <label for="email">Email :</label>
      <input type="email" id="email" name="email" required>
      
      <label for="mot_de_passe">Mot de passe :</label>
      <input type="password" id="mot_de_passe" name="mot_de_passe" required>
      
      <label for="date_naissance">Date de naissance :</label>
      <input type="date" id="date_naissance" name="date_naissance" required>
      
      <label for="adresse">Adresse :</label>
      <input type="text" id="adresse" name="adresse" required>
      
      <label for="numero_permis">Numéro de permis :</label>
      <input type="text" id="numero_permis" name="numero_permis" required>
      
      <button type="submit">S'inscrire</button>
    </form>
 
    <div id="message" class="message <?php echo isset($message_class) ? $message_class : ''; ?>" style="display: <?php echo !empty($message) ? 'block' : 'none'; ?>;">
      <?php echo $message; ?>
    </div>
</body>
</html>


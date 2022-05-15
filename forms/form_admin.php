<?php

// Permet d'appeler la fonction de connexion à la BD
require('../db/connexion.php');

// Démarrage d'une session
session_start();
$_SESSION['form_inscription'] = false;
$_SESSION['form_connexion'] = false;

// Connexion à la BD
$co = connexionBdd();

if (isset($_POST['connexion'])){

    $email = $_POST['email'];
    $password = $_POST['mdp'];
    $password = password_hash($_POST['mdp'], PASSWORD_ARGON2I)

    // Préparation de la requête
    $query = $co->prepare('SELECT * FROM utilisateur WHERE email_user=:user and password_user=:mdp');

    // Association des paramètres aux variables/valeurs
    $query->bindParam(':user', $email);
    $query->bindParam(':mdp', $password);

    // Execution de la requête
    $query->execute();    

    // Récupération dans la variable $result de toutes les lignes que retourne la requête
    $result = $query->fetchall();

    // On compte le nombre de lignes résultats de la requête
    $rows = $query->rowCount();
    if($rows){
        // on démarre une session avec email_user
        $_SESSION['email_user'] = $email;
        // on redirige l'utilisateur
        header("Location: ../pages/backoffice");
    } else {
        $message =' e-mail ou mot de passe incorrect';
        echo $message;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1." />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://kit.fontawesome.com/4f1414e4a5.js" crossorigin="anonymous"></script>
    <title>Connectez-vous</title>
    <link rel="stylesheet" href="./cssforms/conn.css" />
    <link rel="stylesheet" href="../css/style_navbar_footer.css">
</head>
    <body class="bgformadmin">
    <?php 
        require('../require/navbar.php');
    ?>
        <section class="formconnbody">
            <div class="contact">
                <h1>Connectez vous en tant qu'admin </h1>
                <form action="" method="post">
                    <div>
                        <label for="nom">Adresse Mail</label>
                        <input type="email" id="email" name="email" placeholder="E-mail" required>
                    </div>
                    <div>
                        <label for="mdp">Mot de passe</label>
                        <input type="text" id="mdp" name="mdp" placeholder="Mot de passe" required>
                    </div>
                    <div>
                        <input type="submit" id='submit' name="connexion" value='CONNEXION' class="btnForm1">
                    </div>
                </form>
            </div>
        </section>
        <?php 
        require('../require/footer.php');
        ?>
    </body>
</html>
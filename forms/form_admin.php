<?php

// Permet d'appeler la fonction de connexion à la BD
require('../DB/connexion.php');

// Démarrage d'une session
session_start();
$_SESSION['form_inscription'] = false;

// Connexion à la BD
$co = connexionBdd();

if (isset($_POST['connexion'])){

    $email = $_POST['email'];
    $password = $_POST['mdp'];


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
        header("Location:../testbo.php");
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/4f1414e4a5.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>Connectez-vous</title>
    <link rel="stylesheet" href="./cssform/connn.css" />
</head>
    <body class="bgformadmin">
    <?php 
        require('../require/navbarinsc.php');
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
        require('../require/footerconn.php');
        ?>
    </body>
</html>
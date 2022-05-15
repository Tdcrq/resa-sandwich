<?php

// Permet d'appeler la fonction de connexion à la BD
require('../db/connexion.php');

// Démarrage d'une session
session_start();
$_SESSION['form_inscription'] = true;
$_SESSION['form_connexion'] = true;

// Connexion à la BD
$co = connexionBdd();

if (isset($_POST['connexion'])){

    $email = $_POST['email'];
    $password = $_POST['mdp'];

    $stmt = $co->prepare("SELECT password_user FROM utilisateur WHERE email_user = ?"); 
    // on execute la requete
    $stmt->execute(array($email));
    // on va chercher récuperer les resultats
    $user = $stmt->fetch(); 

    if(password_verify($password, $user['password_user'])){ 
        // on recupere l'id de lutilisateur connecté
        $query = $co->prepare("SELECT `id_user` FROM `utilisateur` WHERE `email_user` = :email");
        $query->bindParam('email', $email);
        $query->execute();
        $idUser = $query->fetch();
        $idUser = $idUser[0];

        $query = $co->prepare("SELECT `nom_user` FROM `utilisateur` WHERE `email_user` = :email");
        $query->bindParam('email', $email);
        $query->execute();
        $nameUser = $query->fetch();
        $nameUser = $nameUser[0];
        // on démarre une session avec email_user , idUser ,nameUser
        $_SESSION['email_user'] = $email;
        $_SESSION['id_user'] = $idUser;
        $_SESSION['name_user'] = $nameUser;

        
        // on redirige l'utilisateur
        header("Location: http://localhost/git/resa-sandwich/pages/reservation");
    }else{
        // Si la requête ne retourne rien, alors l'utilisateur ou mdp n'existe pas dans la BD, on lui
        // affiche un message d'erreur
        $message = "email ou mot de passe incorrect.";
        echo $message;
    }

    // On compte le nombre de lignes résultats de la requête
    $rows = $query->rowCount();
    if($rows){
        // on démarre une session avec email_user
        $_SESSION['email_user'] = $email;
        // recup du role de l'utilisateur
        $reqDroit = $co->prepare('SELECT role_user FROM utilisateur WHERE email_user = :email');
        $reqDroit->bindParam('email', $email);
        $reqDroit->execute();
        $droit = $reqDroit->fetchAll();
        foreach($droit as $user_droit)
        {
            if($user_droit['role_user'] == 'a')
            {
                $verif = true;
            }
        }

        if($verif == true)
        {
            // on redirige l'utilisateur
            header("Location: ../pages/backoffice");
        }else{ echo "<h1 class='fraude'> VOUS N'ÊTES PAS UN ELEVE </h1>"; }
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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://kit.fontawesome.com/4f1414e4a5.js" crossorigin="anonymous"></script>
    <title>Connectez-vous</title>
    <link rel="stylesheet" href="./cssforms/conn.css" />
    <link rel="stylesheet" href="../css/style_navbar_footer.css">
    <!-- font -->
    <link rel="stylesheet" href="../css/style_font.css">
</head>
    <body class="bgformadmin">
        <header>
            <?php 
                require('../require/navbar.php');
            ?>
        </header>
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
    </body>
</html>
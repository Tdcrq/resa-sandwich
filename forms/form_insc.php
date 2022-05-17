<?php
    session_start();
    // Permet d'appeler la fonction de connexion à la BD
    require('../db/connexion.php');

    // Cas où le formulaire est validé
    if (isset($_POST['inscription'])){
        // Tests si les 4 champs ont été remplis
        if (isset($_POST['nom'], $_POST['prenom'], $_POST['email'], $_POST['mdp'])){	
            // Récupèration les 4 saisies du formulaire
            $prenom = $_POST['prenom'];
            $nom = $_POST['nom'];
            $email = $_POST['email'];
            $mdp = password_hash($_POST['mdp'], PASSWORD_ARGON2I);
            
            // Connexion à la BD
            $co = connexionBdd();

            // Préparation de la requête
            $query = $co->prepare("INSERT into utilisateur (prenom_user, nom_user, email_user, password_user, role_user, active_user) VALUES (:prenom, :nom, :email, :mdp, 'b', 1)");
            // Association des paramètres aux variables/valeurs
            $query->bindParam(':prenom', $prenom);
            $query->bindParam(':nom', $nom);
            $query->bindParam(':email', $email);
            $query->bindParam(':mdp', $mdp);

            $stmt = $co->prepare("SELECT * FROM utilisateur WHERE email_user=:email");
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            $user = $stmt->fetch();
            $valid = true;
            if ($user["email_user"] == $email) {
                // email existe
                $messageC = 'adresse e-mail déjà utilisée.';
                $valid = false;
            }
            // vérification des conditions
            if (preg_match("#[0-9]+#", $_POST['mdp'])){
                /// Exécution de la requête 
                if($valid == true){
                    $messageC = "<div class='sucess'>
                            <h3>Vous êtes inscrit avec succès.</h3>
                            <p>Cliquez ici pour vous <a href='form_conn.php'>connecter</a></p>
                            </div>";
                    $query->execute();
                    // recup id pour le $_SESSION
                    $query = $co->prepare("SELECT `id_user` FROM `utilisateur` WHERE `email_user` = :email");
                    $query->bindParam('email', $email);
                    $query->execute();
                    $idUser = $query->fetch();
                    $_SESSION['id_user'] = $idUser[0];
                    $id = $_SESSION['id_user'];

                    if ($query) {	
                        // Ajout d'un filtre par défaut
                        $annee = date("Y");
                        if(date('m') >= '09')
                        {
                            $annee += 1;
                        }else{ 
                            $annee -= 1;
                        }
                        $dateMin = $annee .'-09-01';
                        $dateMax = $annee+1 .'-07-15';

                        $dto = new datetime();
                        $timezone = new DateTimeZone('Europe/Paris');
                        $dto->setTimezone($timezone);
                        $aujourdhui = $dto->format('Y-m-d H:i:s');

                        $reqfiltre = $co->prepare("INSERT INTO historique (dateDebut_hist, dateFin_hist, dateInsertion_hist, fk_user_id) VALUES (:dateDebut, :dateFin, :dto, :id_user)");
                        $reqfiltre->bindParam('dateDebut', $dateMin);
                        $reqfiltre->bindParam('dateFin', $dateMax);
                        $reqfiltre->bindParam('dto', $aujourdhui);
                        $reqfiltre->bindParam('id_user', $id);
                        $reqfiltre->execute();
                    }else {
                        echo ' le mot de passe doit contenir au moins 8 caractères, au moins 1 chiffre et au moins 1 caractère spécial ';
                    }	
                    $_SESSION = array();			
                }
            }
        }
    }
?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1." />
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <script src="https://kit.fontawesome.com/4f1414e4a5.js" crossorigin="anonymous"></script>
        <title>Inscrivez-vous</title>
        <link rel="stylesheet" href="./cssforms/conn.css" />
        <link rel="stylesheet" href="../css/style_navbar_footer.css" />
        <!-- font -->
        <link rel="stylesheet" href="../css/style_font.css">
    </head>
    <body class="bgforminsc">
        <header>
            <?php 
                require('../require/navbar.php');
            ?>
        </header>
        <section class="formconnbody">
            <div class="contact">
                <h1>Inscrivez-vous</h1>
                <form action="" method="post">
                    <div>
                        <label for="nom">Nom</label>
                        <input type="nom" id="nom" name="nom" placeholder="Votre nom" required>
                    </div>
                    <div>
                        <label for="prenom">Prénom</label>
                        <input type="prenom" id="prenom" name="prenom" placeholder="Votre prénom" required>
                    </div>
                    <div>
                        <label for="email">Adresse Mail</label>
                        <input type="email" id="email" name="email" placeholder="E-mail" required>
                    </div>
                    <div>
                        <label for="mdp">Mot de passe</label>
                        <input type="text" id="mdp" name="mdp" placeholder="Mot de passe" required>
                    </div>
                    <div>
                        <input type="submit" id='submit' name="inscription" value='INSCRIPTION' class="btnForm1" >
                    </div>
                </form>
                <p><?php echo $messageC;?></p>
            </div>
        </section>
        <!--footer-->
        <footer>
            <?php
                require('../require/footer.php');
            ?>
        </footer>
    </body>
</html>
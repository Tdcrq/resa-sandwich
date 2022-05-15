<?php
// Permet d'appeler la fonction de connexion à la BD
require('../DB/connexion.php');

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

        $stmt = $co->prepare("SELECT * FROM utilisateur WHERE email_user=?");
        $stmt->execute([$email]); 
        $user = $stmt->fetch();
        if ($user) {
            // email existe
            echo 'adresse e-mail déjà utilisée.';
        } 
    // vérification des conditions
        if (preg_match("#[0-9]+#", $_POST['mdp'])){
                /// Exécution de la requête 
                $query->execute(); 
                if ($query) {
                    echo "<div class='sucess'>
                        <h3>Vous êtes inscrit avec succès.</h3>
                        <p>Cliquez ici pour vous <a href='form_conn.php'>connecter</a></p>
                        </div>";
                }
        } else {
            echo ' le mot de passe doit contenir au moins 8 caractères, au moins 1 chiffre et au moins 1 caractère spécial ';
        }

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
    <title>Inscrivez-vous</title>
    <link rel="stylesheet" href="./cssform/connn.css" />
</head>
<body class="bgforminsc">
    <?php 
        require('../require/navbarinsc.php');
    ?>
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
            </div>
        </section>
    <?php
        require('../require/footer.php');
    ?>
</body>
</html>
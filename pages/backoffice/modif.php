<?php
    //connexion bdd
    require("../../db/connexion.php");
    $conn = connexionBdd();

    session_start();// recup des var de session
    if(!isset($_SESSION['id_user']))//verification que l'utilisateur est bien connecté
    {
        header('Location: http://localhost/resa-sandwich/forms/form_conn.php');
        exit();
    }else{
        $nameUser = $_SESSION['name_user'];//recuperation du nameUser
        $idUser = $_SESSION['id_user'];//recuperation de l'idUser
    }

    if(isset($_POST["update"]))
    {
        $id = $_GET['id'];
        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];
        $eMail = $_POST['eMail'];

        $query = $conn-> prepare('UPDATE utilisateur SET nom_user = :nom, prenom_user = :prenom, email_user = :eMail WHERE id_user = :id');
        $query->bindParam(':id' , $id);
        $query->bindParam(':nom' , $nom);
        $query->bindParam(':prenom' , $prenom);
        $query->bindParam(':eMail' , $eMail);
        $query->execute();

        header('Location: index.php');
    }

    if(isset($_POST["cancel"])){
        header('Location: index.php');
    }
?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <script src="https://kit.fontawesome.com/4f1414e4a5.js" crossorigin="anonymous"></script>
        <link href="//db.onlinewebfonts.com/c/827d075b1538829cc0db75696e4d5fa2?family=Speedee" rel="stylesheet" type="text/css"/>
        <link rel="stylesheet" href="../../css/style_navbar_footer.css">
        <link rel="stylesheet" href="style/style.css">
        <!-- font -->
        <link rel="stylesheet" href="../../css/style_font.css">
        <script src="activer.js"></script>
        <link rel="icon" type="image/png" href="../../css/image/logo.png" />
        <title>Modifier l'utilisateur</title>
    </head>
    <body>
        <header>
            <?php 
                require('../../require/navbar_admin.php');
            ?>
        </header>

        <div id="text_modif">
            <div>
                <h5>Vous modifiez l'utilisateur 
                    <b>
                        <?php 
                            $id = $_GET['id'];
                            $query = $conn-> prepare('SELECT `nom_user`, `prenom_user`, email_user FROM `utilisateur` WHERE id_user = :id');
                            $query->bindParam(':id' , $id);
                            $query->execute();
                            $result = $query->fetch();
                            echo $result[1] ." ". $result[0];
                        ?>
                    </b>
                </h5>
            </div>
            <form method="post" name="formulaire">
                <div id="formFiltre">
                    <input type="text" name="eMail" value="<?php echo $result[2]?>" ><br>
                    <input type="text" name="nom" value="<?php echo $result[0]?>" ><br>
                    <input type="text" name="prenom" value="<?php echo $result[1]?>" >
                </div>
                <button type="submit" id="update" name="update">
                    Modifier
                </button>
                <button type="submit" id="cancel" name="cancel">
                    Annuler
                </button>
            </form>
        </div>

        <!-- Footer -->
        <footer>
                <?php 
                    require ("../../require/footer.php");
                ?>
        </footer>
    </body>
</html>
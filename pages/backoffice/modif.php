<?php
    require("../../db/connexion.php");

    $conn = connexionBdd();

    session_start();// recup des var de session
    if(!isset($_SESSION['id_user']))//verification que l'utilisateur est bien connecté
    {
        header('Location: http://localhost/git/resa-sandwich-accueil/forms/form_conn.php');
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="style/style.css">
    <!-- font -->
    <link rel="stylesheet" href="../../css/style_font.css">
    <script src="activer.js"></script>
    <title>modif</title>
</head>
<body>
    <header>
        <?php 
            require('../../require/navbar.php');
        ?>
    </header>

    <div id="text_modif">
        <div>
            <h5>Vous modifier l'utilisateur 
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

    <?php
        require "../../require/footer.php";
    ?>
</body>
</html>
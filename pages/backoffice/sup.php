<?php
    //connexion bdd
    require "../../db/connexion.php";
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

    if(isset($_POST["non_sup"]))
    {
        header('Location: ./index.php');
    }

    if(isset($_POST["oui_sup"]))
    {
        $id = $_GET['id'];
        $query = $conn->prepare('SELECT COUNT(*) FROM commande WHERE fk_user_id = :id');
        $query->bindParam('id' , $id);
        $query->execute();
        $result = $query->fetchColumn();

        if($result > 0)
        {
            header('Location: desa.php?id='.$_GET['id'].'');
        }
        else
        {
            $id = $_GET['id'];
            // suppression de la dépendance de la table historique
            $query = $conn->prepare('DELETE FROM historique WHERE fk_user_id = :id');
            $query->bindParam('id' , $id);
            $query->execute();
            $query = $conn->prepare('DELETE FROM utilisateur WHERE id_user = :id');
            $query->bindParam('id' , $id);
            $query->execute();
            header('Location: index.php');
        }
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
        <link rel="stylesheet" href="style/style.css">
        <!-- font -->
        <link rel="stylesheet" href="../../css/style_font.css">
        <!-- Lien CSS -->
        <link rel="stylesheet" href="../../css/style_navbar_footer.css">
        <script src="activer.js"></script>
        <link rel="icon" type="image/png" href="../../css/image/logo.png" />
        <title>Supprimer l'utilisateur</title>
    </head>
    <body>
        <header>
            <?php 
                require('../../require/navbar_admin.php');
            ?>
        </header>

        <div id="sup">
            <h5>
                Voulez-vous supprimer cet utilisateur ?
            </h5>
            <form method="post">
                <button type="submit" id="oui" name="oui_sup">
                    oui
                </button>
                <button type="submit" id="non" name="non_sup">
                    non
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
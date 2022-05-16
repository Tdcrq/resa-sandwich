<?php
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
        $query->bindParam(':id' , $id);
        $query->execute();
        $result = $query->fetch();

        if($result[0] != 0)
        {
            header('Location: desa.php?id='.$_GET['id'].'');
        }
        else
        {
            $id = $_GET['id'];

            $query = $conn->prepare('DELETE FROM utilisateur WHERE id_user = :id');
            $query->bindParam(':id' , $id);
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="style/style.css">
    <!-- font -->
    <link rel="stylesheet" href="../../css/style_font.css">
    <script src="activer.js"></script>
    <title>sup</title>
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

    <?php
        require "../../require/footer.php";
    ?>
</body>
</html>
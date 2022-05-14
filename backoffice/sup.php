<?php
    require("connexion.php");

    session_start();

    $conn = connexionBD();

    if(isset($_POST["non_sup"]))
    {
        header('Location: index.php');
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="form/style.css">
    <script src="test.js"></script>
    <title>sup</title>
</head>
<body>
    <?php
        require "form/navbar.php";
    ?>

    <div id="sup">
        <h5>
            Voulez-vous suprimer cet l'utilisateur ?
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
        require "form/footer.php";
    ?>
</body>
</html>
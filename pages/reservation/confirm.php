<?php 
    //démarrage session & connexion bdd
    session_start();
    require('../../db/connexion.php');
    $co = connexionBdd();
    //récupération des variables 
    $sandwich = $_SESSION["sandwich"];
    $boisson= $_SESSION["boisson"] ;
    $dessert = $_SESSION["dessert"];
    $chips = $_SESSION["chips"] ;
    $heure = $_SESSION["heure"];
    $date = $_SESSION["jdl"];
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Commande confirmé</title>
        <!-- styles -->
        <link rel="stylesheet" href="styleconfirm.css">
        <!-- font -->
        <link rel="stylesheet" href="../../css/style_font.css">
    </head>
    <body>
        <!--récapitulatif de commande-->
        <div class = "con">
            <div class ="headercon">
                <H1>Commande confirmée</H1>
            </div>
            <div class = "bodycon">
                <p>Votre sandwich : 
                    <?php
                        $query = $co->prepare("SELECT nom_sandwich FROM sandwich WHERE id_sandwich = $sandwich");
                        $query->execute();
                        $res = $query->fetchAll();
                        foreach ( $res as $row ) {
                            echo $row['nom_sandwich'];
                        };
                    ?>
                </p>
                <p>Votre boisson : 
                    <?php
                        $query = $co->prepare("SELECT nom_boisson FROM boisson WHERE id_boisson = $boisson");
                        $query->execute();
                        $res = $query->fetchAll();
                        foreach ( $res as $row ) {
                            echo $row['nom_boisson'];
                        };
                    ?>
                </p>
                <p>Votre dessert : 
                    <?php
                        $query = $co->prepare("SELECT nom_dessert FROM dessert WHERE id_dessert = $dessert");
                        $query->execute();
                        $res = $query->fetchAll();
                        foreach ( $res as $row ) {
                            echo $row['nom_dessert'];
                        };
                    ?>
                </p>
                <p>Chips : 
                    <?php 
                        if ($_SESSION["chips"] == 1){
                            echo "Oui";
                        }else if ($_SESSION["chips"] == 0){
                            echo "Non";
                        }; 
                    ?>
                </p>
                <p>Jour de livraison : <?php echo $_SESSION["jdl"]; ?></p>
                <p>Heure de livraison : <?php echo $_SESSION["heure"]; ?></p>
            </div>
            <div>
                <button class="btnStyle" onclick="window.location.href='index.php';" >revenir à votre compte</button>
            </div>
        </div>


    </body>
</html>


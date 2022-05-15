<?php
    $_SESSION['form_inscription'] = true;
    $_SESSION['form_connexion'] = true;
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
    <title>Projet FA</title>
    <link rel="stylesheet" href="./css/style_navbar_footer.css">
    <link rel="stylesheet" href="./css/style.css" />
    <!-- font -->
    <link rel="stylesheet" href="./css/style_font.css">
</head>

<body>
    <header>
        <?php 
            require('./require/navbar.php');
        ?>
    </header>
    <section class="body">
        <div class="PAtitre">
            <h1><strong>BIENVENUE</strong></h1>
        </div>
    <!-- texte affiché grâce au back office administrateur -->
        <div class="texte_accueil">
            <h4>
                <?php
                    require("./db/connexion.php");
                    $aff = "SELECT texte_accueil FROM accueil";
                    $co = connexionBdd();
                    $acc = $co->prepare($aff);
                    $acc->execute(); 
                    while($row = $acc->fetch()){
                        echo $row['texte_accueil'];
                    }
                ?>
            </h4>
    <!-- menu affiché grâce au back office administrateur -->
            <?php
                // $aff = "SELECT lien_pdf FROM accueil";
                $co = connexionBdd();
                $acc = $co->prepare("SELECT lien_pdf FROM accueil");
                $acc->execute(); 
                while($row = $acc->fetch()){
                    echo "<img src='".$row["lien_pdf"]."'";
                }
            ?>
        </div>    
        </section>
    <?php 
        require('./require/footer.php');
    ?>
</body>
</html>
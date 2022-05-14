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
    <title>Projet FA</title>
    <link rel="stylesheet" href="css/styless.css" />
</head>

<body>
    <?php 
        require('./projetFA/require/navbar.php');
    ?>
    <section class="body">
        <div class="PAtitre">
            <h1><strong>BIENVENUE</strong></h1>
        </div>
    <!-- texte affiché grâce au back office administrateur -->
        <div class="texte_accueil">
            <h4>
                <?php
                    require("./DB/connexion.php");
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
                $aff = "SELECT lien_pdf FROM accueil";
                $co = connexionBdd();
                $acc = $co->prepare($aff);
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
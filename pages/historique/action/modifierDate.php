<?php
    require "../../../db/connexion.php";
    $co = connexionBdd();

    // récupérer la commande
    $id_com = $_GET['id'];
    $reqCommande = $co->prepare('SELECT *
    FROM commande C, sandwich S, boisson B, dessert D
    WHERE C.id_com = :id
    AND C.fk_sandwich_id = S.id_sandwich
    AND C.fk_boisson_id = B.id_boisson
    AND C.fk_dessert_id = D.id_dessert');
    $reqCommande->bindParam('id', $id_com);
    $reqCommande->execute();
    $commande = $reqCommande->fetchAll();

    // Déclaration des variables
    $sandwich = $dessert = $boisson = $chips = $heure = $date = $statutCommande = " ";
    $sandwichErreur = $dessertErreur = $boissonErreur = $timeErreur = " ";
    $valid = false;
    $heureLimite =  date("H:i",mktime(9, 30, 0, 0, 0, 0));
    $dto = new datetime();
    $timezone = new DateTimeZone('Europe/Paris');
    $dto->setTimezone($timezone);
    session_start();
?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Modifier date de livraison</title>
        <link rel="stylesheet" href="./style/style.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    </head>
    <body>
        <!-- Header -->
        <?php require "../../../require/header.php"; ?>

        <div class = "formCon">
            <form method="post" id="sandForm" role=form action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <div class = "sbcCon" id="test">
                    <div class = "denreeCon">
                        <div class = "imgCon">
                            <img src="../../reservation/finpro/Images/sandwich.png" alt="" srcset="">
                            <p class = "nomDenree">Sandwich</p>
                        </div>
                        <select class ="selectStyle" name="sandwich" id="">
                            <!--Select sandwich-->
                            <?php
                                foreach($commande as $row)
                                {
                                    echo "<option value='".$row['id_sandwich']."'>".$row['nom_sandwich']."</option>";
                                }
                            ?>
                        </select>
                        <p class ="comments">
                            <!--Message indisponibilité sandwich-->
                            <?php 
                                echo $sandwichErreur;
                                $query = $co->prepare('SELECT * FROM sandwich WHERE dispo_sandwich = 0');
                                $query->execute();
                                $commandeDispo = $query->fetchall();
                                foreach ($commandeDispo as $row)
                                {
                                    echo ($row['nom_sandwich']." non disponible aujourd'hui.<br>");                                
                                }
                            ?>
                        </p>
                    </div>
                    <div class = "denreeCon">
                        <div class = "imgCon">
                            <img src="../../reservation/finpro/Images/coca.png" alt="" srcset="">
                            <p class = "nomDenree">Boisson</p>
                        </div>
                        <select class ="selectStyle" name = "boisson" id="">
                            <?php
                                foreach($commande as $row)
                                {
                                    echo "<option value='".$row['id_boisson']."'>".$row['nom_boisson']."</option>";                                
                                } 
                            ?>
                        </select>
                        <!--Message indisponibilité boisson-->
                        <p class ="comments">
                            <?php 
                                echo $boissonErreur; 
                                $query = $co->prepare('SELECT * FROM boisson WHERE dispo_boisson = 0');
                                $query->execute();
                                $commandeDispo = $query->fetchall();
                                foreach ($commandeDispo as $row)
                                {
                                    echo ($row['nom_boisson']." non disponible aujourd'hui.<br>");                                
                                }
                            ?>
                        </p>
                    </div>
                    <div class = "denreeCon">
                        <div class = "imgCon">
                            <img src="../../reservation/finpro/Images/dessert.png" alt="" srcset="">
                            <p class = "nomDenree">Dessert</p>
                        </div>
                        <select class ="selectStyle" name="dessert" id="">
                            <?php
                                foreach ($commande as $row)
                                {
                                    echo "<option value='".$row['id_dessert']."'>".$row['nom_dessert']."</option>";                                
                                }
                            ?>
                        </select>
                        <!--Message indisponibilité dessert-->
                        <p class ="comments">
                            <?php
                            echo $dessertErreur; 
                            $query = $co->prepare('SELECT * FROM dessert WHERE dispo_dessert = 0');
                                $query->execute();
                                $commandeDispo = $query->fetchall();
                                foreach ($commandeDispo as $row)
                                {
                                    echo ($row["nom_dessert"]." non disponible aujourd'hui.<br>");                                
                                }
                            ?>
                        </p>
                    </div>
                </div>
                <div class = "chipsheureCon">
                    <div class = "denreeCon">
                        <div class = "imgCon">
                            <img src="../../reservation/finpro/Images/chips.png" alt="" srcset="">
                            <p class = "nomDenree">Chips</p>
                        </div>
                        <div class = "chipsSelectCon">
                            <?php 
                            foreach($commande as $commande_chips)
                            {
                                $chips_checked = $commande_chips['chips_com'];
                            }
                            ?>
                            <div>
                                <input type="radio" value = "1" name ="chipsR" id="chipsO" 
                                <?php 
                                    if($chips_checked == '1')
                                    {
                                        echo 'checked';
                                    } else{ echo 'disabled';}
                                ?>>
                                <label for="chipsO">Oui ?</label>
                            </div>
                            <div>
                                <input type="radio" value = "0" name ="chipsR" id="chipsN" 
                                <?php 
                                    if($chips_checked == '0')
                                    {
                                        echo 'checked';
                                    } else{ echo 'disabled';}
                                ?>>
                                <label for="chipsN">Non.</label>
                            </div>
                        </div>
                    </div>
                    <div class = "heureCon">
                        <?php
                        foreach($commande as $row)
                        {
                            $date_livraison = $row['date_heure_livraison_com'];
                            $date_livraison = explode(' ', $date_livraison);
                        }
                        ?>
                        <h3>Date et heure de livraison :</h3>
                        <input type="date" value="<?php echo $date_livraison[0]; ?>" min="<?php echo($dto->format('Y-m-d'));?>" name = "date">
                        <input type="time" min="12:30" max="14:30" value ="<?php echo $date_livraison[1]; ?>" name = "heure">
                        <p class ="comments"><?php echo $timeErreur; ?></p>
                        <input type="submit" name = "submit" id = "blue" class = "selectStyle" value = "Modifier">
                        <p class = "comments"><?php echo($statutCommande);?></p>
                    </div>
                </div>
            </form>
        </div>
    </body>
</html>

<?php
?>
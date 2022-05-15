<?php
    require "../../../db/connexion.php";
    $co = connexionBdd();

    // récupérer la commande
    if(isset($_GET['id']))
    {
        $id_com = $_GET['id'];
    }
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
    $valid = true;
    $heureLimite =  date("H:i",mktime(20, 30, 0, 0, 0, 0));
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
            <form method="post" id="sandForm">
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
        <?php
            if (isset($_POST['submit']))
            {
                //vérification que les variables ne sont pas vide et affichage des messages erreur
                if (empty($_POST["date"]) || empty($_POST["heure"]))
                {
                    $valid = false;
                    $timeErreur = "N'oubliez pas de renseigner la date de livraison";
                }
                //vérification que l'Utilisateur ne commande pas après 9h30.
                if ($dto->format('H:i') > $heureLimite){
                    $valid = false;
                    $statutCommande = "Heure limite pour commander : 9h30";
                }
                if (empty($_POST["date"]) || empty($_POST["heure"]) || empty($_POST["sandwich"]) || empty($_POST["dessert"]) || empty($_POST["boisson"])){
                    $statutCommande = 'Veillez à bien selectionner tout les champs';
                }
                $time = $_POST["heure"];
                $date_livraison = $_POST["date"];
                $heureLimiteL =  date("H:i",mktime(12, 30, 0, 0, 0, 0));
                $heureLimiteL2 =  date("H:i",mktime(14, 30, 0, 0, 0, 0));
                //vérification que le jour de livraison ne soit pas passé.
                if ($date_livraison < $dto->format('Y-m-d'))
                {
                    $valid = false;
                    $statutCommande = 'Vous ne pouvez pas commander pour un jour anterieure';
                }
                //vérification que l'heure de livraison est comprise entre 12h30 et 14h30.
                if ($time < $heureLimiteL || $time > $heureLimiteL2){
                    $valid = false;
                    $statutCommande = 'Vous pouvez pas vous faire livrer qu\'entre 12h30 et 14H30 ';
                }
                if($valid == true)
                {
                    //récupération saisie Utilisateur
                    $heure = ($_POST["heure"]); 
                    $timestampJour = strtotime($date_livraison);
                    $jourDeLivrasion = date("w", $timestampJour);
                    //vérification que le jour de livraison ne soit pas samedi ou dimanche.
                    if ($jourDeLivrasion == 6 || $jourDeLivrasion == 0){
                        $statutCommande = "vous ne pouvez pas reserver pour le samedi et dimanche.";
                    } else if ($jourDeLivrasion != 6 || $jourDeLivrasion != 0){
                        //insertion de la commande en bdd
                        $date_time = $date_livraison ." ". $time;
                        $req_date = $co->prepare("UPDATE commande SET date_heure_livraison_com = :dateLivraison WHERE id_com = :id");
                        $req_date->bindParam('dateLivraison', $date_time);
                        $req_date->bindParam('id', $id_com);
                        $exe_date = $req_date->execute();
                    }
                }
                echo "<script> location.replace('../index.php'); </script>";
            }
        ?>
    </body>
</html>

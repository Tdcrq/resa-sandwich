<?php
    //connexion bdd
    require('../../db/connexion.php');
    $co = connexionBdd();
    // recup des vars de session
    session_start();
    if(!isset($_SESSION['id_user']))//verification que l'utilisateur est bien connecté
    {
        header('Location: http://localhost/resa-sandwich/forms/form_conn.php');
        exit();
    }else{
        $nameUser = $_SESSION['name_user'];//recuperation du nameUser
        $idUser = $_SESSION['id_user'];//recuperation de l'idUser
    }
    //déclaration des variables
    $sandwich = $dessert = $boisson = $chips = $heure = $date = $statutCommande = " ";
    $sandwichErreur = $dessertErreur = $boissonErreur = $timeErreur = " ";
    $valid = false;
    $heureLimite =  date("H:i",mktime(9, 30, 0, 0, 0, 0));
    $dto = new datetime();
    $timezone = new DateTimeZone('Europe/Paris');
    $dto->setTimezone($timezone);

    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        //vérification que les variables ne sont pas vide et affichage des messages d'erreurs si elles le sont.
        $valid = true;
        if (empty($_POST["sandwich"])){
            $valid = false;
            $sandwichErreur = "N'oubliez pas le sandwich !";
        }
        if (empty($_POST["dessert"])){
            $valid = false;
            $dessertErreur = "N'oubliez pas le dessert !";
        }
        if (empty($_POST["boisson"])){
            $valid = false;
            $boissonErreur = "N'oubliez pas la boisson !";
        }
        if (empty($_POST["date"]) || empty($_POST["heure"]))
        {
            $valid = false;
            $timeErreur = "N'oubliez pas de renseigner la date de livraison";
        }
        if (empty($_POST["date"]) || empty($_POST["heure"]) || empty($_POST["sandwich"]) || empty($_POST["dessert"]) || empty($_POST["boisson"])){
            $statutCommande = 'Veillez à bien selectionner tout les champs';
        }
        
        $time = $_POST["heure"];
        $date = $_POST["date"];
        //vérification que l'Utilisateur ne commande pas après 9h30 si cest le meme jour.
        if($date == $dto->format('Y-m-d')){
            if ($dto->format('H:i') > $heureLimite){
                $valid = false;
                $statutCommande = "Heure limite pour commander aujourd'hui: 9h30";
            }
        }
        //fonction qui limite l'injection sql dans la value des selects
        function verifyInput($var)
        {
            $var = trim($var);
            $var = stripslashes($var);
            $var = htmlspecialchars($var);
            if ($var >= 0 && $var <=6){
                return true;
            }else{
                return false;
            }
        }
        //création d'heure pour la condition suivante.
        $heureLimiteL =  date("H:i",mktime(12, 30, 0, 0, 0, 0));
        $heureLimiteL2 =  date("H:i",mktime(14, 30, 0, 0, 0, 0));
        //vérification que le jour de livraison ne soit pas passé.
        if ($date < $dto->format('Y-m-d'))
        {
            $valid = false;
            $statutCommande = 'Vous ne pouvez pas commander pour un jour anterieure';
        }
        //vérification que l'heure de livraison est comprise entre 12h30 et 14h30.
        if ($time < $heureLimiteL || $time > $heureLimiteL2){
            $valid = false;
            $statutCommande = 'Vous pouvez pas vous faire livrer qu\'entre 12h30 et 14H30 ';
        }
    }

    if($valid == true)
    {
        //récupération saisie Utilisateur
        $userC = $_SESSION['id_user'];
        $sandwich = ($_POST["sandwich"]);
        $dessert = ($_POST["dessert"]);
        $boisson = ($_POST["boisson"]);
        $chips = ($_POST["chipsR"]);
        $heure = ($_POST["heure"]); 
        $annuleCom = 0;
        $timestampJour = strtotime($date);
        $jourDeLivrasion = date("w", $timestampJour);
        $dateR = "$date $heure";
        $validF = true;
        if (verifyInput($boisson) == false || verifyInput($chips) == false || verifyInput($sandwich) == false || verifyInput($dessert) == false){
            $validF = false;
            $statutCommande = "Erreur";
        }
        //vérification que le jour de livraison ne soit pas samedi ou dimanche.
        if ($jourDeLivrasion == 6 || $jourDeLivrasion == 0){
            $statutCommande = "vous ne pouvez pas reserver pour le samedi et dimanche.";
            // si le jour de livraison est bon et que la fonction verify input return true 
        } else if (($jourDeLivrasion != 6 || $jourDeLivrasion != 0) && $validF == true){
            //insertion de la commande en bdd
            $query = $co->prepare("INSERT INTO commande(fk_user_id,fk_sandwich_id,fk_boisson_id,fk_dessert_id,chips_com,date_heure_livraison_com,annule_com) VALUES (:userC, :sandwich ,:boisson ,:dessert ,:chips ,:dateC, :annule)");
            $query->bindParam('userC', $userC);
            $query->bindParam('sandwich', $sandwich);
            $query->bindParam('boisson', $boisson);
            $query->bindParam('dessert', $dessert);
            $query->bindParam('chips', $chips);
            $query->bindParam('dateC', $dateR);
            $query->bindParam('annule', $annuleCom);
            $query->execute();
            //redirection vers la page de confirmation
            header('Location: http://localhost/resa-sandwich/pages/reservation/confirm.php');
            //passage des vars de commande grace a la session 
            $_SESSION["sandwich"] = $sandwich;
            $_SESSION["boisson"] = $boisson;
            $_SESSION["dessert"] = $dessert;
            $_SESSION["chips"] = $chips;
            $_SESSION["heure"] = $heure;
            $_SESSION["jdl"] = $date;
        }
    }
?>

<!-- HTML -->
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <!-- Lien Bootstrap -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://kit.fontawesome.com/4f1414e4a5.js" crossorigin="anonymous"></script>
    <link href="//db.onlinewebfonts.com/c/827d075b1538829cc0db75696e4d5fa2?family=Speedee" rel="stylesheet" type="text/css"/>
    <!-- styles -->
    <link rel="stylesheet" href="./style.css">
    <link rel="stylesheet" href="../../css/style_navbar_footer.css">
    <!-- font -->
    <link rel="stylesheet" href="../../css/style_font.css">
</head>
<body>
    <header>
        <?php 
            require('../../require/navbar.php');
        ?>
    </header>
    <section class = "formSec">
        <H2 id = "phraseCommande"><span id = "blueN"><?php $name = $nameUser ; echo " $name</span>, voulez-vous passer une commande aujourd'hui ?</H2>";?>
        <div class = "formCon">
            <form method="post" id="sandForm" role=form action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <div class = "sbcCon" id="test">
                    <div class = "denreeCon">
                        <div class = "imgCon">
                            <img src="Images/sandwich.png" alt="" srcset="">
                            <p class = "nomDenree">Sandwich</p>
                        </div>
                        <select class ="selectStyle" name="sandwich" id="">
                            <option value="" disabled selected >Choisir</option>
                            <!--Select sandwich-->
                            <?php
                                $query = $co->prepare('SELECT * FROM sandwich WHERE dispo_sandwich = 1');
                                $query->execute();
                                $result = $query->fetchall();
                                foreach ($result as $row)
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
                                $result = $query->fetchall();
                                foreach ($result as $row)
                                {
                                    echo ($row['nom_sandwich']." non disponible aujourd'hui.<br>");                                
                                }
                            ?>
                        </p>
                    </div>
                    <div class = "denreeCon">
                        <div class = "imgCon">
                            <img src="Images/coca.png" alt="" srcset="">
                            <p class = "nomDenree">Boisson</p>
                        </div>
                        <select class ="selectStyle" name = "boisson" id="">
                            <!--Select boisson-->
                            <option selected="selected" value = "" name = "disable" disabled>Choisir</option>
                            <?php
                                
                                $query = $co->prepare('SELECT * FROM boisson WHERE dispo_boisson = 1');
                                $query->execute();
                                $result = $query->fetchall();
                                foreach ($result as $row)
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
                                $result = $query->fetchall();
                                foreach ($result as $row)
                                {
                                    echo ($row['nom_boisson']." non disponible aujourd'hui.<br>");                                
                                }
                            ?>
                        </p>
                    </div>
                    <div class = "denreeCon">
                        <div class = "imgCon">
                            <img src="Images/dessert.png" alt="" srcset="">
                            <p class = "nomDenree">Dessert</p>
                        </div>
                        <select class ="selectStyle" name="dessert" id="">
                            <!--Select dessert-->
                            <option selected="selected" value = "" disabled>Choisir</option>
                            <?php
                                $query = $co->prepare('SELECT * FROM dessert WHERE dispo_dessert = 1');
                                $query->execute();
                                $result = $query->fetchall();
                                foreach ($result as $row)
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
                                $result = $query->fetchall();
                                foreach ($result as $row)
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
                            <img src="Images/chips.png" alt="" srcset="">
                            <p class = "nomDenree">Chips</p>
                        </div>
                        <div class = "chipsSelectCon">
                            <div>
                                <input type="radio" value = "1" name ="chipsR" id="chipsO"checked>
                                <label for="chipsO">Oui ?</label>
                            </div>
                            <div>
                                <input type="radio" value = "0" name ="chipsR" id="chipsN">
                                <label for="chipsN">Non.</label>
                            </div>
                        </div>
                    </div>
                    <div class = "heureCon">
                        <h3>Date et heure de livraison :</h3>
                        <input type="date" value="<?php echo($dto->format('Y-m-d'));?>" min="<?php echo($dto->format('Y-m-d'));?>" name = "date">
                        <input type="time" min="12:30" max="14:30" value ="12:30" name = "heure">
                        <p class ="comments"><?php echo $timeErreur; ?></p>
                        <input type="submit" name = "submit" id = "blue" class = "selectStyle" value = "Commander">
                        <p class = "comments"><?php echo($statutCommande);?></p>
                    </div>
                </div>
            </form>
        </div>
    </section>
    <footer>
        <!-- FOOTER -->
        <?php require "../../require/footer.php" ?>
    </footer>
</body>
</html>

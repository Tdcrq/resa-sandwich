<?php
    //connexion bdd
    require "../../db/connexion.php";
    $co = connexionBdd();

    session_start();
    if(!isset($_SESSION['id_user']))//verification que l'utilisateur est bien connecté
    {
        header('Location: http://localhost/resa-sandwich/forms/form_conn.php');
        exit();
    }
    $id = $_SESSION['id_user'];
    $_SESSION['form_inscription'] = false;

    
    $reqInfo = $co->prepare("SELECT * FROM utilisateur WHERE id_user = :id");
    $reqInfo->bindParam('id', $id);
    $reqInfo->execute();
    $info = $reqInfo->fetchAll();
    foreach($info as $info_user)
    {
        $nom_user = $info_user['nom_user'];
        $prenom_user = $info_user['prenom_user'];
    }

    $annee = date("Y");
    if(date('m') >= '09')
    {
        $annee += 1;
    }

    if(isset($_POST['submit']))
    {
        $dateDebut = date($_POST['dateDebut']);
        $dateFin = date($_POST['dateFin']);
        $erreurFiltre = false;
        if($dateFin >= $dateDebut)
        {
            //  Mise à jour du filtre
            $query = $co->prepare("UPDATE `historique` SET dateDebut_hist = :dateDebut, dateFin_hist = :dateFin, dateInsertion_hist = :dateInsertion WHERE fk_user_id = :id");                
            $query->bindParam('dateDebut', $dateDebut);
            $query->bindParam('dateFin', $dateFin);
            $query->bindParam('dateInsertion', $date);
            $query->bindParam('id', $id);
            $query->execute();
        } else {
            echo "<p class='invalide'> Veuillez saisir une période cohérente </p>";
            $erreurFiltre = true;
        }
        header('Location: ./index.php');
    }

    // Supprimer le filtre
    if(isset($_POST['ecrase']))
    {
        $dateDebut = strval($annee -1) ."-09-01";
        $dateFin = strval($annee) ."-07-15";
        //  Requête pour écraser le filtre déjà en base
        $query = $co->prepare("UPDATE historique SET dateDebut_hist = :dateDebut, dateFin_hist = :dateFin WHERE fk_user_id = :id");                
        $query->bindParam('dateDebut', $dateDebut);
        $query->bindParam('dateFin', $dateFin);
        $query->bindParam('id', $id);
        $query->execute();
        header('Location: ./index.php');
    }

    // Récupération de l'historique de l'utilisateur
    $dto = new datetime();
    $timezone = new DateTimeZone('Europe/Paris');
    $dto->setTimezone($timezone);

    $reqHistorique = $co->prepare("SELECT * FROM historique WHERE fk_user_id = :id");
    $reqHistorique->bindParam('id', $id);
    $reqHistorique->execute();
    $historique = $reqHistorique->fetchAll();
    foreach($historique as $filtre)
    {
        $date_livraison = $filtre['dateDebut_hist'];
        $debutHist = explode(' ', $date_livraison);
        $date_livraison = $filtre['dateFin_hist'];
        $finHist = explode(' ', $date_livraison);
    }

    // Récupération des commandes de l'utilisateur
    $reqAfficher = $co->prepare("
                            SELECT *
                            FROM commande C, sandwich S, boisson B, dessert D
                            WHERE C.fk_user_id = :id
                            AND C.fk_sandwich_id = S.id_sandwich
                            AND C.fk_boisson_id = B.id_boisson
                            AND C.fk_dessert_id = D.id_dessert
                            AND C.date_heure_livraison_com >= :debutFiltre AND C.date_heure_livraison_com <= :finFiltre
                            ORDER BY C.date_heure_livraison_com");
    $reqAfficher->bindParam('id', $id);
    $reqAfficher->bindParam('debutFiltre', $debutHist[0]);
    $reqAfficher->bindParam('finFiltre'  , $finHist[0]);
    $reqAfficher->execute();
    $afficher = $reqAfficher->fetchAll();

    // Récupération du nombre de livraison pour la date du jour
    $dto = new datetime();
    $timezone = new DateTimeZone('Europe/Paris');
    $dto->setTimezone($timezone);
    $heure_min = $dto->format('Y-m-d') . ' 00:00:01';
    $heure_max = $dto->format('Y-m-d') . ' 23:59:59';
    // requete pour récup le nb d'occurrence
    $reqOccurrence = $co->prepare("SELECT COUNT(*) FROM commande WHERE fk_user_id = :id AND date_heure_livraison_com >= :heure_min AND date_heure_livraison_com <= :heure_max");
    $reqOccurrence->bindParam('id', $id);
    $reqOccurrence->bindParam('heure_min', $heure_min);
    $reqOccurrence->bindParam('heure_max', $heure_max);
    $reqOccurrence->execute();
    $occurrence = $reqOccurrence->fetchColumn();
?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="icon" type="image/png" href="../../css/image/logo.png" />
        <title>Historique</title>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        <!-- Lien Bootstrap -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <script src="https://kit.fontawesome.com/4f1414e4a5.js" crossorigin="anonymous"></script>
        <!-- Lien CSS -->
        <link rel="stylesheet" href="./style/style.css">
        <link rel="stylesheet" href="../../css/style_navbar_footer.css">
        <!-- font -->
        <link rel="stylesheet" href="../../css/style_font.css">
    </head>
    <body>
        <!-- HEADER -->
        <header>
            <?php 
                require('../../require/navbar.php');
                
            ?>
        </header>
        <section class="accueil textAlign">
            <h1 class="titre"> Les commandes de <?php echo $nom_user ." ". $prenom_user; ?> </h1>
            <p class="description">
                Toutes vos commandes qui sont invalidées par la cuisine seront <span class="invalide">rouges et en gras.</span>
            </p>
            <p class="nbCommande">
                Nombres de commandes à récupérer aujourd'hui : <span class="compteurCommande"> <?php echo $occurrence; ?> </span>
            </p>
        </section>

        <section class="textAlign" id="filtre">
            <?php 
                $reqFiltre = $co->prepare("SELECT * FROM historique WHERE fk_user_id = :id");
                $reqFiltre->bindParam('id', $id);
                $reqFiltre->execute();
                $reqFiltre = $reqFiltre->fetch();
            ?>
            <form action="" method="post" name="formFiltre">
                <div class="infoCommande">
                    <label for="dateDebut">Période du </label>
                    <input type="date" max="<?php echo $annee;?>-07-15" min="<?php echo $annee -1;?>-09-01" name="dateDebut" class="saisieFiltre" value="<?php echo $reqFiltre['dateDebut_hist'];?>">
                    <label for="dateFin">au </label>
                    <input type="date" max="<?php echo $annee;?>-07-15" min="<?php echo $annee -1;?>-01-01" name="dateFin" class="saisieFiltre" value="<?php echo $reqFiltre['dateFin_hist'];?>">
                </div>
                <input class="btnForm1 textAlign" type="submit" name="submit" value="Appliquer le filtre">
                <input class="btnEcrase textAlign" type="submit" name="ecrase" value="Supprimer le filtre">
            </form>
        </section>

        <section class="affichageIndex">
            <table>
                <?php
                    if(sizeof($afficher) == 0)
                    {
                        echo "<h4> Vous n'avez aucune commande prévu entre le ". $reqFiltre['dateDebut_hist'] ." et le ". $reqFiltre['dateFin_hist'] .".</h4>";
                    } else {
                        echo "
                        <tr>
                            <th class='th textAlign'> Sandwich </th>
                            <th class='th textAlign'> Boisson </th>
                            <th class='th textAlign'> Dessert </th>
                            <th class='th textAlign'> Chips </th>
                            <th class='th textAlign'> Date commande </th>
                            <th class='th textAlign'> Date livraison </th>
                            <th class='th textAlign'> Commande annulée </th>   
                            <th class='th textAlign'> Actions </th>                
                        </tr>";
                        foreach ($afficher as $resultat)
                        {
                            if($resultat['chips_com'] == 1)
                            {
                                $chips = "oui";
                            }else{ 
                                $chips = "non";
                            }
                            if($resultat['annule_com'] == 1)
                            {
                                $annule = "oui";
                            }else{ 
                                $annule = "non";
                            }
                            echo "<tr>";
                                echo "<td class='tableau'>". $resultat['nom_sandwich'] ."</td>";
                                echo "<td class='tableau'>". $resultat['nom_boisson'] ."</td>";
                                echo "<td class='tableau'>". $resultat['nom_dessert'] ."</td>";
                                echo "<td class='tableau'>". $chips ."</td>";
                                echo "<td class='tableau'>". $resultat['date_heure_com'] ."</td>";
                                echo "<td class='tableau'>". $resultat['date_heure_livraison_com'] ."</td>";
                                echo "<td class='tableau'>". $annule ."</td>";
                                $passe = "";
                                if($resultat['date_heure_livraison_com'] < $dto->format('Y-m-d h:i:s') || $annule == 'oui')
                                {
                                    $passe = 'disabled';
                                }
                                echo "<td class='tableau'>
                                    <a class='btnForm1 $passe' href='./action/modifierDate.php?id=".$resultat['id_com']. "' >Modifier </a>
                                    <a class='btnForm1 $passe' href='./action/annulerCommande.php?id=".$resultat['id_com']. "' >Annuler </a>"."</td>
                                </td>";
                            echo "</tr>";
                        }
                    }
                ?>
            </table>
        </section>
        <!-- Footer -->
        <footer>
            <?php 
                require "../../require/footer.php";
            ?>
        </footer>
    </body>
</html>
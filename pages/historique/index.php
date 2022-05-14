<?php 
    require "../../db/connexion.php";
    $co = connexionBdd();

    session_start();
    $_SESSION['id'] = 1;
    $id = $_SESSION['id'];

    $annee = date("Y");
    if(date('m')){
        $annee += 1;
    }

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
        $date_time_filtre_min = explode(' ', $date_livraison);
        $date_livraison = $filtre['dateFin_hist'];
        $date_time_filtre_max = explode(' ', $date_livraison);
    }
?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Resa-Sandwich</title>
        <!-- Lien CSS -->
        <link rel="stylesheet" href="./style/style.css">
        <!-- Lien Bootstrap -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    </head>
    <body>
        <!-- HEADER -->
        <?php require "../../require/header.php" ?>
        <section class="accueil textAlign">
            <?php
                $reqInfo = $co->prepare("SELECT * FROM utilisateur WHERE id_user = :id");
                $reqInfo->bindParam('id', $id);
                $reqInfo->execute();
                $info = $reqInfo->fetchAll();
                foreach($info as $info_user)
                {
                    $nom_user = $info_user['nom_user'];
                    $prenom_user = $info_user['prenom_user'];
                }
            ?>
            <h1 class="titre"> Les commandes de <?php echo $nom_user ." ". $prenom_user; ?> </h1>
            <p class="description">
                Toutes vos comandes qui sont invalidées par la cuisine seront <span class="invalide">rouge et en gras.</span>
            </p>
            <p class="nbCommande">
                Nombre de commande effectuée aujourd'hui : <span class="compteurCommande"> 0 </span>
            </p>
        </section>

        <section class="textAlign" id="filtre">
            <?php 
                $reqFiltre = $co->prepare("SELECT * FROM historique WHERE fk_user_id = :id");                
                $reqFiltre->bindParam('id', $_SESSION['id']);
                $reqFiltre->execute();
                $reqFiltre = $reqFiltre->fetch();
            ?>
            <form action="" method="post" name="formFiltre">
                <div class="infoCommande">
                    <label for="dateDebut">Période du </label>
                    <input type="date" max="<?php echo $annee;?>-07-15" min="<?php echo $annee -2;?>-09-01" name="dateDebut" class="saisieFiltre" value="<?php echo $reqFiltre['dateDebut_hist'];?>">
                    <label for="dateFin">au </label>
                    <input type="date" max="<?php echo $annee;?>-07-15" min="<?php echo $annee -1;?>-01-01" name="dateFin" class="saisieFiltre" value="<?php echo $reqFiltre['dateFin_hist'];?>">
                </div>
                <input class="btnForm1 textAlign" type="submit" name="submit" value="Appliquer le filtre">
            </form>
            <?php
                $filtre = "";
                if(isset($_POST['submit']))
                {
                    $dateDebut = date($_POST['dateDebut']);
                    $dateFin = date($_POST['dateFin']);
                    $erreurFiltre = true;
                    if($dateFin >= $dateDebut)
                    {
                        //  Mise à jour du filtre
                        $query = $co->prepare("UPDATE `historique` SET dateDebut_hist = :dateDebut, dateFin_hist = :dateFin, dateInsertion_hist = :dateInsertion, fk_user_id = :id");                
                        $query->bindParam('dateDebut', $dateDebut);
                        $query->bindParam('dateFin', $dateFin);
                        $query->bindParam('dateInsertion', $date);
                        $query->bindParam('id', $_SESSION['id']);
                        $query->execute();
                        $filtre = "AND C.date_heure_livraison_com >= '$dateDebut' AND C.date_heure_livraison_com <= '$dateFin'";
                    } else {
                        echo "<p class='invalide'> Veuillez saisir une période cohérente </p>";
                        $erreurFiltre = false;
                    }
                }
            ?>
        </section>

        <section class="affichageIndex">
            <table>
                <?php
                    if(!isset($_POST['submit']) or !$erreurFiltre)
                    {
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
                        // Select nom sandwich 
                        $reqAfficher = $co->prepare("
                            SELECT *
                            FROM commande C, sandwich S, boisson B, dessert D
                            WHERE C.fk_user_id = 1
                            AND C.fk_sandwich_id = S.id_sandwich
                            AND C.fk_boisson_id = B.id_boisson
                            AND C.fk_dessert_id = D.id_dessert
                            AND C.date_heure_livraison_com >= :debutFiltre AND date_heure_livraison_com <= :finFiltre
                            ORDER BY C.date_heure_livraison_com");
                        $reqAfficher->bindParam('debutFiltre', $date_time_filtre_min[0]);
                        $reqAfficher->bindParam('finFiltre'  , $date_time_filtre_max[0]);
                        $reqAfficher->execute();
                        $afficher = $reqAfficher->fetchAll();
                        if(sizeof($afficher) == 0)
                        {
                            echo "<h4> Vous n'avez aucune commande prévu entre le ". $dateDebut ." et le ". $dateFin .".</h4>";
                        } else {
                            foreach ($afficher as $resultat)
                            {
                                if($resultat['chips_com'] == 1)
                                {
                                    $chips = "oui";
                                }else{ $chips = "non";}
                                if($resultat['annule_com'] == 1)
                                {
                                    $annule = "oui";
                                }else{ $annule = "non";}
                                echo "<tr>";
                                    echo "<td class='tableau'>". $resultat['nom_sandwich'] ."</td>";
                                    echo "<td class='tableau'>". $resultat['nom_boisson'] ."</td>";
                                    echo "<td class='tableau'>". $resultat['nom_dessert'] ."</td>";
                                    echo "<td class='tableau'>". $chips ."</td>";
                                    echo "<td class='tableau'>". $resultat['date_heure_com'] ."</td>";
                                    echo "<td class='tableau'>". $resultat['date_heure_livraison_com'] ."</td>";
                                    echo "<td class='tableau'>". $annule ."</td>";
                                    $passe = "";
                                    $date_livraison = $resultat['date_heure_livraison_com'];
                                    $date_livraison = explode(' ', $date_livraison);
                                    if($date_livraison[0] < $dto->format('Y-m-d'))
                                    {
                                        $passe = 'disabled';
                                    }
                                    echo "<td class='tableau'>
                                        <a class='btnForm1 $passe' href='./action/modifierDAte.php?id=".$resultat['id_com']. "' >Modifier </a>
                                        <a class='btnForm1' href='./action/annulerCommande.php?id=".$resultat['id_com']. "' >Annuler </a>"."</td>";
                                    echo "</td>";
                                echo "</tr>";
                            }
                        }
                    }
                    if(isset($_POST['submit']) and $erreurFiltre)
                    {
                        
                        // Select nom sandwich 
                        $reqAfficher = $co->prepare("
                            SELECT *
                            FROM commande C, sandwich S, boisson B, dessert D
                            WHERE C.fk_user_id = 1
                            AND C.fk_sandwich_id = S.id_sandwich
                            AND C.fk_boisson_id = B.id_boisson
                            AND C.fk_dessert_id = D.id_dessert
                            AND C.date_heure_livraison_com >= :debutFiltre AND date_heure_livraison_com <= :finFiltre
                            ORDER BY C.date_heure_livraison_com");
                        $reqAfficher->bindParam('debutFiltre', $date_time_filtre_min[0]);
                        $reqAfficher->bindParam('finFiltre'  , $date_time_filtre_max[0]);
                        $reqAfficher->execute();
                        $afficher = $reqAfficher->fetchAll();

                        if(sizeof($afficher) == 0)
                        {
                            echo "<h4> Vous n'avez aucune commande prévu entre le ". $dateDebut ." et le ". $dateFin .".</h4>";
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
                                }else{ $chips = "non";}
                                if($resultat['annule_com'] == 1)
                                {
                                    $annule = "oui";
                                }else{ $annule = "non";}
                                echo "<tr>";
                                    echo "<td class='tableau'>". $resultat['nom_sandwich'] ."</td>";
                                    echo "<td class='tableau'>". $resultat['nom_boisson'] ."</td>";
                                    echo "<td class='tableau'>". $resultat['nom_dessert'] ."</td>";
                                    echo "<td class='tableau'>". $chips ."</td>";
                                    echo "<td class='tableau'>". $resultat['date_heure_com'] ."</td>";
                                    echo "<td class='tableau'>". $resultat['date_heure_livraison_com'] ."</td>";
                                    echo "<td class='tableau'>". $annule ."</td>";
                                    echo "<td class='tableau'>
                                        <a class='btnForm1' href='./action/modifierDAte.php?id=".$resultat['id_com']. "' >Modifier </a>
                                        <a class='btnForm1' href='./action/annulerCommande.php?id=".$resultat['id_com']. "' >Annuler </a>"."</td>
                                    </td>";
                                echo "</tr>";
                            }
                        }
                    }
                ?>
            </table>
        </section>

        <!-- FOOTER -->
        <?php require "../../require/footer.php" ?>
    </body>
</html>


<?php 
    require "../../db/connexion.php";
    $co = connexionBdd();

    session_start();
    $_SESSION['id'] = 1;

    $date = date('Y-m-d');
    $mois = date("n");
    if ($mois <= 7)
    {
        $annee = date("Y");
    } else{
        $annee = date("Y")-1;
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
    <?php require "./view/navbar.php" ?>
    <section class="accueil textAlign">
        <h1 class="titre"> Vos commandes </h1>
        <p class="description">
            Toutes vos comandes qui sont invalidées par la cuisine seront <span class="invalide">rouge et en gras.</span>
        </p>
        <p class="nbCommande">
            Nombre de commande effectuée aujourd'hui : <span class="compteurCommande"> 0 </span>
        </p>
    </section>

    <section class="textAlign" id="filtre">
        <form action="" method="post" name="formFiltre">
            <div class="infoCommande">
                <label for="dateDebut">Période du </label>
                <input type="date" max="<?php echo $annee;?>-07-15" min="2021-09-01" name="dateDebut" class="saisieFiltre">
                <label for="dateFin">au </label>
                <input type="date" max="2022-07-15" min="2021-01-01" name="dateFin" class="saisieFiltre">
            </div>
            <div class="infoOrdre">
                <label for="ordre">Ordre : </label>
                <select name="ordre" id="ordre" class="">
                    <option value="-1" selected="selected"></option>
                    <option value="0"> Croissant </option>
                    <option value="1"> Décroissant </option>
                </select>
            </div>
            <input class="btnForm1 textAlign" type="submit" name="submit" value="Appliquer le filtre">
        </form>
        <?php
            if(isset($_POST['submit']))
            {
                $dateDebut = date($_POST['dateDebut']);
                $dateFin = date($_POST['dateFin']);
                $id = 1;
                $query = $co->prepare("UPDATE `historique` SET dateDebut_hist = :dateDebut, dateFin_hist = :dateFin, dateInsertion_hist = :dateInsertion, fk_user_id = :id");                
                $query->bindParam('dateDebut', $dateDebut);
                $query->bindParam('dateFin', $dateFin);
                $query->bindParam('dateInsertion', $date);
                $query->bindParam('id', $_SESSION['id']);
                $query->execute();
                // echo var_dump(date($_POST['dateDebut']));
            }
        ?>
    </section>

    <section class="affichage">
        <?php
            $ordre = 'ORDER BY 8';
            $filtre = '';
            if(isset($_POST['submit']))
            {
                $filtreDateDebut = $_POST['dateDebut'];
                $filtreDateFin = $_POST['dateFin'];
                if($_POST['ordre'] == 1){
                    $ordre = ' ORDER BY 8 DESC';
                }
                $filtre = " AND date_heure_livraison_com >= '$filtreDateDebut' OR date_heure_livraison_com <= '$filtreDateFin'";
            }
            $filtre = $filtre . $ordre;
            $query = $co->prepare("SELECT C.id_com, U.nom_user, S.nom_sandwich, B.nom_boisson, D.nom_dessert, C.chips_com, C.date_heure_com, C.date_heure_livraison_com, C.annule_com
                FROM utilisateur U, commande C, sandwich S, boisson B, dessert D
                WHERE U.id_user = " . $_SESSION['id'] . "
                AND C.fk_sandwich_id = S.id_sandwich
                AND C.fk_boisson_id = B.id_boisson
                AND C.fk_dessert_id = D.id_dessert $filtre");
            $query->execute();
            $result = $query->fetchall();
        ?>

        <table>
            <tr>
                <th class="th textAlign"> Numéro de commande</th>
                <th class="th textAlign"> Nom </th>
                <th class="th textAlign"> Sandwich </th>
                <th class="th textAlign"> Boisson </th>
                <th class="th textAlign"> Dessert </th>
                <th class="th textAlign"> Chips </th>
                <th class="th textAlign"> Date commande </th>
                <th class="th textAlign"> Date livraison </th>
                <th class="th textAlign"> Commande annulée </th>   
                <th class="th textAlign"> Actions </th>                
            </tr>
            <?php
                foreach ($result as $row)
                {
                    echo "<tr>";
                        echo "<td class='tableau'>" . $row["id_com"] . "</td>";
                        echo "<td class='tableau'>" . $row["nom_user"] . "</td>"; 
                        echo "<td class='tableau'>" . $row["nom_sandwich"] . "</td>";  
                        echo "<td class='tableau'>" . $row["nom_boisson"] . "</td>"; 
                        echo "<td class='tableau'>" . $row["nom_dessert"] . "</td>";  
                        if($row["chips_com"] == 1)
                        {
                            echo "<td class='tableau'> Oui </td>";
                        }else{ echo "<td class='tableau'> Non </td>"; }
                        echo "<td class='tableau'>" . $row["date_heure_com"] . "</td>";
                        echo "<td class='tableau'>" . $row["date_heure_livraison_com"] . "</td>"; 
                        if($row["annule_com"] == 0)
                        {
                            echo "<td class='tableau'> Non </td>";
                        }else{ echo "<td class='tableau'> Oui </td>"; }
                        echo "<td class='tableau'> 
                        <input class='btnForm1 textAlign' value='Modifier' name='modifier' type='submit'>
                        <input class='btnForm1 textAlign' value='Annuler' name='annuler' type='submit'>
                        </td>";
                    echo "</tr>";
                }
            ?>
        </table>
    </section>

    <!-- FOOTER -->
    <?php require "./view/footer.php" ?>
</body>
</html>
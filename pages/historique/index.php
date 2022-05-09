<?php 
    require "../../db/connexion.php";
    $co = connexionBdd();

    session_start();
    $_SESSION['id'] = 1;

    $annee = date("Y");
    if(date('m')){
        $annee += 1;
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
                    <input type="date" max="<?php echo $annee;?>-07-15" min="<?php echo $annee -1;?>-09-01" name="dateDebut" class="saisieFiltre">
                    <label for="dateFin">au </label>
                    <input type="date" max="<?php echo $annee;?>-07-15" min="<?php echo $annee -1;?>-01-01" name="dateFin" class="saisieFiltre">
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
            <table>
                <tr>
                    <th class="th textAlign"> Sandwich </th>
                    <th class="th textAlign"> Boisson </th>
                    <th class="th textAlign"> Dessert </th>
                    <th class="th textAlign"> Chips </th>
                    <!-- <th class="th textAlign"> Date commande </th>
                    <th class="th textAlign"> Date livraison </th>
                    <th class="th textAlign"> Commande annulée </th>   
                    <th class="th textAlign"> Actions </th>                 -->
                </tr>
                <?php
                    // Select nom sandwich 
                    $reqSandwich = $co->prepare("SELECT * FROM sandwich INNER JOIN commande on sandwich.id_sandwich = commande.fk_sandwich_id");
                    $reqSandwich->execute();
                    $sandwich = $reqSandwich->fetch();

                    $reqBoisson = $co->prepare("SELECT * FROM boisson INNER JOIN commande on boisson.id_boisson = commande.fk_boisson_id");
                    $reqBoisson->execute();
                    $boisson = $reqBoisson->fetch();

                    $reqDessert = $co->prepare("SELECT * FROM dessert INNER JOIN commande on dessert.id_dessert = commande.fk_dessert_id");
                    $reqDessert->execute();
                    $dessert = $reqDessert->fetch();

                    $reqAfficher = $co->prepare("SELECT * FROM commande WHERE id_com = 4");
                    $reqAfficher->execute();
                    $afficher = $reqAfficher->fetch();

                    foreach ($afficher as $resultat)
                    {
                        echo "<tr>";
                            echo "<td class='tableau'>". $sandwich['nom_sandwich'] ."</td>";
                            echo "<td class='tableau'>". $boisson['nom_boisson'] ."</td>";
                            echo "<td class='tableau'>". $dessert['nom_dessert'] ."</td>";
                        echo "</tr>";
                    }
                    // while ($row = $reqAfficher->fetch())
                    // {
                    //     echo "<tr>";
                    //         echo "<td class='tableau'>". $sandwich['nom_sandwich'] ."</td>";
                    //         echo "<td class='tableau'>". $boisson['nom_boisson'] ."</td>";
                    //         echo "<td class='tableau'>". $dessert['nom_dessert'] ."</td>";
                    //         // echo "<td class='tableau'>". $['nom_'] ."</td>";
                    //     echo "</tr>";
                    // }
                ?>
            </table>
        </section>

        <!-- FOOTER -->
        <?php require "../../require/footer.php" ?>
    </body>
</html>
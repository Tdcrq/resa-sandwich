<?php
    require "../../../db/connexion.php";
    $co = connexionBdd();
?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Modifier date de livraison</title>
        <link rel="stylesheet" href="../style/style.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    </head>
    <body>
        <!-- Header -->
        <?php require "../../../require/header.php" ?>

        <section name='affichage'>
            <table>
                <tr>
                    <th class='th textAlign'> Sandwich </th>
                    <th class='th textAlign'> Boisson </th>
                    <th class='th textAlign'> Dessert </th>
                    <th class='th textAlign'> Chips </th>
                    <th class='th textAlign'> Date commande </th>
                    <th class='th textAlign'> Date livraison </th>
                    <th class='th textAlign'> Commande annulée </th>   
                </tr>

                <?php
                    $reqAfficher = $co->prepare("
                    SELECT C.id_com, S.nom_sandwich, B.nom_boisson, D.nom_dessert, C.chips_com, C.date_heure_com, C.date_heure_livraison_com, C.annule_com
                    FROM commande C, sandwich S, boisson B, dessert D
                    WHERE C.id_com = :id_com
                    AND C.fk_sandwich_id = S.id_sandwich
                    AND C.fk_boisson_id = B.id_boisson
                    AND C.fk_dessert_id = D.id_dessert");
                    $reqAfficher->bindParam('id_com', $_GET['id']);
                    $reqAfficher->execute();
                    $afficher = $reqAfficher->fetchAll();

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
                        echo "</tr>";
                    }
                ?>

                <!--affichage des commandes utilisateur -->
                <?php
                ?>
            </table>
        </section>
        <section name='sectionFormulaireModifier'>
            <form method="post">
                <label for="reponse"> Voulez-vous annuler la commande ?</label>
                <input type="submit" class="btnForm1" value="Oui" name="reponse">
                <input type="submit" class="btnForm1" value="Non" name="reponse">
            </form>
        </section>
        <?php
            if(isset($_POST['reponse']))
            {
                $reponse = '0';
                if($_POST['reponse'] == 'Oui')
                {
                    $reponse = '1';
                    $id_commande = $_GET['id'];
                    $reqAnnuler = $co->prepare("UPDATE commande SET annule_com = :reponse WHERE id_com = :id");
                    $reqAnnuler->bindParam('id', $id_commande);
                    $reqAnnuler->bindParam('reponse', $reponse);
                    $reqAnnuler->execute();
                }
                header('Location: ../');
            }
        ?>

        <!-- Footer -->
        <?php require "../../../require/footer.php" ?>
    </body>
</html>
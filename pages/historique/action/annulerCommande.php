<?php
    //connexion bdd
    require "../../../db/connexion.php";
    $co = connexionBdd();
?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="icon" type="image/png" href="../../../css/image/logo.png" />
        <title>Annuler la commande</title>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        <!-- Lien Bootstrap -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <script src="https://kit.fontawesome.com/4f1414e4a5.js" crossorigin="anonymous"></script>
        <!-- Lien CSS -->
        <link rel="stylesheet" href="../style/style.css">
        <link rel="stylesheet" href="../../../css/style_navbar_footer.css">
        <!-- font -->
        <link rel="stylesheet" href="../../../css/style_font.css">
    </head>
    <body>
        <!-- Header -->
        <header>
            <?php 
                require('../../../require/navbar.php');
            ?>
        </header>

        <section class="affichage">
            <!--affichage des commandes utilisateur-->
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
                        echo "</tr>";
                    }
                ?>
            </table>
        </section>
        <section class='sectionFormulaireModifier'>
            <form method="post">
                <label for="reponse"> <h3> Voulez-vous annuler la commande ? </h3></label>
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
        <footer>
            <?php 
                require ("../../../require/footer.php");
            ?>
        </footer>
    </body>
</html>
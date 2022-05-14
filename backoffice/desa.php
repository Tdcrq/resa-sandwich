<?php
    require("connexion.php");

    session_start();

    $conn = connexionBD();

    if(isset($_POST["oui"]))
    {
        $id = $_GET['id'];
        $actif = "0";

        $query = $conn-> prepare('UPDATE utilisateur SET active_user= :actif WHERE id_user = :id');
        $query->bindParam(':id' , $id);
        $query->bindParam(':actif', $actif);
        $query->execute();
        header('Location: index.php');
    }

    if(isset($_POST["non"]))
    {
        header('Location: index.php');
    }
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="form/style.css">
    <script src="test.js"></script>
    <title>desa</title>
</head>
<body>
    <?php
        require "form/navbar.php";
    ?>

    <div id="tab_desa">
        <h5>Les utilisateurs ayant déjà passé des commandes ne peuvent pas être supprimés!</h5>
        <div>
            <!--tableaux affichage des commandes utilisateur -->
            <table>
                <tr>
                    <th>
                        n° de commande
                    </th>
                    <th>
                        date de commande
                    </td>
                    <th>
                        date de livraison
                    </th>
                    <th>
                        sandwich
                    </th>
                    <th>
                        boisson
                    </th>
                    <th>
                        dessert
                    </th>
                    <th>
                        chips
                    </th>
                </tr>

                <!--affichage des commandes utilisateur -->
                <?php
                    $id = $_GET['id'];

                    $query = $conn->prepare('SELECT * FROM `commande` WHERE `fk_user_id` = :id');
                    $query->bindParam(':id' , $id);
                    $query->execute();
                    foreach($query as $row)
                    {
                        echo "<tr>";
                        echo"<td>".$row['id_com']."</td>";
                        echo "<td>".$row['date_heure_com']."</td>";
                        echo "<td>".$row['date_heure_livraison_com']."</td>";


                        $id = $_GET['id'];

                        $query = $conn->prepare('SELECT * FROM `sandwich` INNER JOIN commande WHERE commande.fk_sandwich_id = sandwich.id_sandwich');
                        $query->bindParam(':id' , $id);
                        $query->execute();
                        $row1 = $query->fetch();
                        echo "<td>".$row1['nom_sandwich'];


                        $id = $_GET['id'];

                        $query = $conn->prepare('SELECT * FROM `boisson` INNER JOIN commande WHERE commande.fk_boisson_id = boisson.id_boisson');
                        $query->bindParam(':id' , $id);
                        $query->execute();
                        $row2 = $query->fetch();
                        echo "<td>".$row2['nom_boisson']."</td>";


                        $id = $_GET['id'];

                        $query = $conn->prepare('SELECT * FROM `dessert` INNER JOIN commande WHERE commande.fk_dessert_id = dessert.id_dessert');
                        $query->bindParam(':id' , $id);
                        $query->execute();
                        $row3 = $query->fetch();
                        echo "<td>".$row3['nom_dessert']."</td>";

                        echo "<td>".$row['chips_com']."</td>";
                        echo "</tr>";
                    }
                ?>
            </table>
        </div>
        <div>
            <h5>
                Voulez vous desactiver le compte de l'utilisateur?
                <form method="post" id="form_desa">
                    <button type="submit" id="oui" name="oui">
                        oui
                    </button>
                    <button type="submit" id="non" name="non" data-bs-dismiss="modal">
                        non
                    </button>
                </form>
            </h5>
        </div>
    </div>
    
     <?php
        require "form/footer.php";
    ?>
</body>
</html>
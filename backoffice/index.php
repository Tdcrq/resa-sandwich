<?php
    require("connexion.php");

    $conn = connexionBD();

    session_start();// recup des var de session
    if(!isset($_SESSION['id_user']))//verification que l'utilisateur est bien connecté
    {
        header('Location: http://localhost/resa-sandwich-accueil/backoffice/form_conn.php');
        exit();
    }else{
        $nameUser = $_SESSION['name_user'];//recuperation du nameUser
        $idUser = $_SESSION['id_user'];//recuperation de l'idUser
    }
    
    <?php
    session_start(); // recuperation des vars de session
    require('../db/connexion.php');
    $co = connexionBdd();

    if(isset($_GET['id']))// recuperation de l'id pour l'update
    {
        $id = $_GET['id']; // id dans la var
        $query = $co->prepare('SELECT * from project WHERE id_project=:id');// recover information of the project with the id
        $query->bindParam(':id', $id);
        $query->execute();
        $result = $query->fetch();// resultat dans un tableau
    }

    if(isset($_POST['yes']))
    {
        $query = $co->prepare('DELETE FROM project WHERE id_project=:id'); // prepare to delete the project yes btn is clicked
        $query->bindParam(':id', $id);
        $query->execute();
        header('Location: admin.php'); // redirection vers la page index du backoffice
    }

    if(isset($_POST['no']))
    {
        header('Location: admin.php'); // redirection vers la page index du backoffice
    }

?>

    if(isset($_POST["delete"]))
    {
        header('Location: sup.php');
    }

    if(isset($_POST["update"]))
    {
        header('Location: modif.php');
    }

    if(isset($_POST["send"]))
    {
        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];
        $eMail = $_POST['eMail'];
        $mdp = password_hash($_POST['mdp'], PASSWORD_ARGON2I);
        $categ = "u";
        $actif = "1";

        $query = $conn-> prepare('INSERT INTO utilisateur(role_user, email_user, password_user, nom_user, prenom_user, active_user) VALUES (:u ,:eMail ,:mdp ,:nom ,:prenom , :actif)');
        $query->bindParam(':u', $categ);
        $query->bindParam(':nom' , $nom);
        $query->bindParam(':prenom' , $prenom);
        $query->bindParam(':eMail' , $eMail);
        $query->bindParam(':mdp', $mdp);
        $query->bindParam(':actif', $actif);
        $query->execute();
    }
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="test.js"></script>
    <link rel="stylesheet" href="form/style.css">
    <title>documztn</title>
</head>
<body>
    <?php
        require "form/navbar.php";
    ?>
    <div id="flex">
        <div id="flexG">
            <!--affichage de tout les utilisateur pour modif supresion -->
            <section id="tab_utili">
                <table id="tab">
                    <tr>
                        <th>
                            N° compte
                        </th>
                        <th>
                            e-mail utilisateur
                        </th>
                        <th>
                            prenom utilisateur
                        </th>
                        <th>
                            nom utilisateur
                        </th>
                        <th>
                            statut utilisateur
                        </th>
                        <th>
                            action
                        </th>
                    </tr>

                    <?php
                        $query = $conn->prepare('SELECT * from utilisateur');
                        $query->execute();
                        while($row = $query->fetch())
                        {
                            echo "<tr>";
                            echo"<td>".$row['id_user']."</td>";
                            echo"<td>".$row['email_user']."</td>";
                            echo"<td>".$row['nom_user']."</td>";
                            echo"<td>".$row['prenom_user']."</td>";
                            echo"<td>".$row['active_user']."</td>";
                            echo "<td><form method='get' name='formulaire_delete/update'>
                                        <a class='modif_a' name='delete' href='sup.php?id=".$row['id_user']. "' >suprimer </a> 
                                        <a class='modif_a' name='update' href='modif.php?id=".$row['id_user']. "' >modifier </a>
                                       </form>
                                  </td>";
                            echo"</tr>";
                        }
                    ?>
                </table>
            </section> 
        </div>
        <div id="flexD">
            <!--filtre d'action--> 
            <section>
                <div id="filtre">
                    <form method="post">
                        <div id="">
                            <h5>Voulez-vous ajouter un utilisateur ?</h5>
                            <div>
                                <input type="radio" id="add_Oui" name="add" onclick="groupeForm('');">
                                <label for="add_Oui">oui</label>
                            </div>
                            <div>
                                <input type="radio" id="add_Non" name="add" checked onclick="groupeForm('');">
                                <label for="add_Non">non</label>
                            </div>
                        </div>
                    </form>
                    <form method="post" name="formulaire">
                        <fieldset id="groupeForm" disabled="disabled">
                            <div id="formFiltre">
                                <input type="text" name="eMail" placeholder="e-mail"><br>
                                <input type="text" name="nom" placeholder="nom"><br>
                                <input type="text" name="prenom" placeholder="prenom">
                                <input type="text" name="mdp" placeholder="mot de passe"><br>
                            </div>
                            <button type="submit" id="send" name="send">
                                ajouter
                            </button>
                        </fieldset>
                    </form>
                </div>
            </section>
        </div>
    </div>
    <?php
        require "form/footer.php";
    ?>
</body>
</html>
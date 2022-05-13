<?php
session_start();
    echo 'Vous êtes connecté en tant que ' . $_SESSION['email_user']; 
    require('./DB/connexion.php');
    $co = connexionBdd();

    if(isset($_POST['deconnexion']))
    {
    // Détruit toutes les variables de session --> fermeture de session
    $_SESSION = array();
	
    // Redirige vers la page de connexion
    header("Location:index.php");
    }

    if(!isset($_SESSION["email_user"]))
        {
            header("Location:./forms/form_conn.php"); 
            exit();  
        }
        //modification/ajout du texte sur la page d'accueil
    if(isset($_POST['modif'])){
        $txt = $_POST['textaccueil'];
        $query = $co->prepare('SELECT * FROM accueil');
        $query->execute();
        $result = $query->fetchall();
        $rows = $query->rowCount();

        if($rows==0){
            $insert = "INSERT into accueil (texte_accueil) VALUES (:txt)";
            $ins = $co->prepare($insert);
            $ins->bindValue(':txt', $_POST['textaccueil']);  
            $ins->execute();

        } elseif($rows==1){
            $modif = "UPDATE accueil SET texte_accueil=:modiftxt WHERE id_accueil = '".$_POST['modif']."'"; 
            $stmt = $co->prepare($modif);
            $stmt->bindValue(':modiftxt', $_POST['textaccueil']);
            $stmt->execute();
            }        
    }
    // affichage du menu
    if(isset($_POST['sendpdf'])){
        $img = $_POST['menupdf'];
        $query = $co->prepare('SELECT * FROM accueil');
        $query->execute();
        $result = $query->fetchall();
        $rows = $query->rowCount();

        if($rows==0){
            $insert = "INSERT into accueil (lien_pdf) VALUES (:pdf)";
            $ins = $co->prepare($insert);
            $ins->bindValue(':pdf', $_POST['menupdf']);  
            $ins->execute();

        } else{
            $modif = "UPDATE accueil SET lien_pdf=? WHERE id_accueil = ?"; 
            $stmt = $co->prepare($modif);
            $stmt->execute(array($_POST['menupdf'],$_POST['sendpdf']));   
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>backoffice administrateur</title>
    <link rel="stylesheet" href="./css/styless.css" />
</head>
<body class="bo">
    <!-- affichage du texte sur la page d'accueil -->
    <div class="afftxtflex">
        <div class="afftxt">
            <form method="post">
                <h2>Texte d'accueil :</h2>
                <textarea type="text" name="textaccueil">
                <?php
                    $aff = "SELECT texte_accueil FROM accueil";
                    $co = connexionBdd();
                    $acc = $co->prepare($aff);
                    $acc->execute(); 
                    while($row = $acc->fetch()){
                        echo $row['texte_accueil'];
                    }
                ?>
                </textarea>          
                <div>
                <button type="submit" name="modif" class="btnForm1">Ajout/Modification</button>
                </div>
            </form>
        </div>
    </div>
    <!-- affichage de l'image sur la page d'accueil -->
    <div class="affimgflex">
        <div class="affimg">
            <form method="post">
                <h2>Affichage du menu en format pdf :</h2><input type="text" name="menupdf"></input>
                <div>
                    <button type="submit" name="sendpdf" value="0" class="btnForm1">Ajout/modification</button>      
                </div>
                <?php
                    $aff = "SELECT lien_pdf FROM accueil";
                    $co = connexionBdd();
                    $acc = $co->prepare($aff);
                    $acc->execute(); 
                    while($row = $acc->fetch()){
                        echo "<img src='".$row["lien_pdf"]."'>";
                    }
                ?>
            </form>            
        </div>
    </div>
    <!-- bouton de déconnexion -->
    <div class="deco">     
        <form method="post">
            <button type=submit name='deconnexion' class="btnForm1">DECONNEXION</button>
        </form>
    </div>
</body>
</html>
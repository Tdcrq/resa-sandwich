<?php
    //recuperation des vars de session
    session_start();
    $email = $_SESSION["email_user"];
    $id = $_SESSION['id_user'];
    $_SESSION = array();
    $_SESSION['email_user'] = $email;
    $_SESSION['id_user'] = $id;
    
    //connexion bdd
    require('./db/connexion.php');
    $co = connexionBdd();

    if(isset($_POST['deconnexion']))
    {
        // Détruit toutes les variables de session --> fermeture de session
        $_SESSION = array();
        // Redirige vers la page de connexion
        header("Location: http://localhost/resa-sandwich/");
    }

    if(!isset($_SESSION["email_user"]))
    {
        header("Location: http://localhost/resa-sandwich/"); 
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
            $ins->bindParam('txt', $_POST['textaccueil']);  
            $ins->execute();
        } elseif($rows==1) {
            $modif = "UPDATE accueil SET texte_accueil=:modiftxt WHERE id_accueil = '".$_POST['modif']."'"; 
            $stmt = $co->prepare($modif);
            $stmt->bindValue('modiftxt', $_POST['textaccueil']);
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

        } else {
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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://kit.fontawesome.com/4f1414e4a5.js" crossorigin="anonymous"></script>
    <title>backoffice administrateur</title>
    <!-- Styles -->
    <link rel="stylesheet" href="./css/style.css" />
    <link rel="stylesheet" href="./css/style_navbar_footer.css">
    <!-- font -->
    <link rel="stylesheet" href="./css/style_font.css">
</head>
<body class="bo">
    <header>
        <?php 
            require('./require/navbar_admin.php');
        ?>
    </header>
    <div class="authentification">
        <?php
            echo 'Vous êtes connecté en tant que ' . $_SESSION['email_user']; 
        ?>
    </div>
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
                <button type="submit" name="modif" value="1" class="btnForm1">Ajout/Modification</button>
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
                    <button type="submit" name="sendpdf" value="1" class="btnForm1">Ajout/modification</button>      
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
    <!-- Footer -->
    <footer>
            <?php 
                require "./require/footer.php";
            ?>
    </footer>
</body>
</html>
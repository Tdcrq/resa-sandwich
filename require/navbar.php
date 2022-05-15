<nav class="navbar navbar-expand-lg navbar-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="#"><img src="http://localhost/git/resa-sandwich/css/image/logo.png" alt="logo"></a>
        <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbar">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbar">
            <ul class="navbar-nav">
                <?php
                    if(!isset($_SESSION['bo'])  && !isset($_SESSION['email_user']))
                    { 
                ?>
                        <li class="nav-item">
                            <a class="nav-link" href="http://localhost/git/resa-sandwich/">Accueil</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="http://localhost/git/resa-sandwich/pages/reservation/">Commander</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="http://localhost/git/resa-sandwich/pages/historique">Historique</a>
                        </li>
                    <?php 
                    }else{ ?>
                        <li class="nav-item">
                            <a class="nav-link" href="http://localhost/git/resa-sandwich/boadmin.php">Gestion accueil</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="http://localhost/git/resa-sandwich/pages/backoffice/">Gestion utilisateurs</a>
                        </li>
                    <?php }
                    ?>
                
            </ul>
            <ul class="nav nav-pills ml-auto">
                <?php
                    if(!isset($_SESSION['id_user']))
                    {
                        if($_SESSION['form_connexion'] == true)
                        {
                            echo "<li class='nav-item'>
                                <a class='nav-link' href='http://localhost/git/resa-sandwich/forms/form_conn.php'> 
                                    <i class='fa-solid fa-right-to-bracket'></i> Se connecter
                                </a>
                            </li>";
                        }
                        
                    }
                    if(isset($_SESSION['id_user']))
                    {
                        echo "<li class='nav-item'>
                            <a class='nav-link' href='http://localhost/git/resa-sandwich/require/logout.php'> 
                                <i class='fa-solid fa-right-to-bracket'></i> Déconnexion
                            </a>
                        </li>";
                    }
                    if($_SESSION['form_inscription'] == true && !isset($_SESSION['id_user']))
                    {
                        echo "<li class='nav-item'>
                            <a class='nav-link' href='http://localhost/git/resa-sandwich/forms/form_insc.php'> 
                                <i class='fa-solid fa-right-to-bracket'></i> S'inscrire
                            </a>
                        </li>";
                    }
                ?>
            </ul>
        </div>
    </div>
</nav>
<?php
	// On récupère les variables de session
	session_start();
	
    // Détruit toutes les variables de session --> fermeture de session
    $_SESSION = array();
    $_SESSION['form_connexion'] = false;
	
    // Redirige vers la page de connexion
    header("Location: http://localhost/resa-sandwich/index.php");
?>
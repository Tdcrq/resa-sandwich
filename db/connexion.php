<?php
	#Fonction � appeler pour se connecter � la base de donn�es
	function connexionBdd() {
		// Permet d'utiliser les variables d'identification pour la connexion
		require('config.php');
		
		// Tentative de connexion � la base de donn�es MySQL 
		try{
            // chaine de connexion avec API PDO
			$co = new PDO("mysql:host=" . $server .";dbname=" . $dbName . ";charset=utf8", $user, $pass);
			//On d�finit le mode d'erreur de PDO sur Exception
			$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}		
		// En cas de probl�me dans la tentative connexion on termine le script php et on affichera le message d'erreur
		catch(PDOException $e){
			die('Erreur : ' . $e->getMessage());
		}
        return $co;
	}	
?>
<?php
/*
    Auteur : Oceane21
    Version : 1.0.0
    Projet : Scientia Vulgaria Project
    Description :
        classe usine gérant les connexions à Mysql.
*/
    
class PDOFactory{
    /*
        Attributs
    */

        /*
            Constantes
        */
    
    /*
        Méthodes générales
    */
        /*
            Getters
        */
            public static function getMysqlConnexion($app='frontend'){
				$host="localhost";
				$login="root";
				$password="rj7@kAv;8d7_e(E6:m4-w&";
				$dbname="";
				try{
					switch( strtolower($app) ){
						case "frontend":
							$dbname="scientiavulgaria";
						break;
						case "forum":
							$dbname="forum";
						break;
						default :
							$dbname="scientiavulgaria";
						break;
					}
					$bdd = new PDO('mysql:host='.$host.';dbname='.$dbname, $login, $password,   array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
					$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
				}catch(PDOException $e){
					$log=new Log();
					$log->log('Mysql', 'connexion', $e->getMessage());
				}
                return $bdd;
            }
        /*
            Setters
        */
    
    /*
        Autres méthodes
    */
} 

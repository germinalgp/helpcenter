<?php
class Conectar{
	
    public static function connection(){
		$dbhost="localhost"; //Host del mysql
		$dbuser="id6032899_admintfg"; //Usuario del Mysql
		$dbpass="H3LPC3NT3RTFG!"; //Password del mysql
		$db="id6032899_dbhelpcentertfg"; //Base de datos donde se creará la tabla users
		if(!isset($_SESSION)) 
		{ 
			session_start(); 
        $connection=mysqli_connect($dbhost, $dbuser, $dbpass, $db);
        mysqli_query($connection, "SET NAMES 'utf8'");
        return $connection;
		}
	}
}
?>
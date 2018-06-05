<?php
//Comenzamos la sesion
if(!isset($_SESSION)) 
    { 
        session_start(); 
    } 
//Conexion.php V1.1
//Script de conexion, gracias a este script podemos cambiar los atributos de conexion sin tener que tocar todos los .php.

$dbhost="localhost"; //Host del mysql
$dbuser="id6032899_admintfg"; //Usuario del Mysql
$dbpass="H3LPC3NT3RTFG!"; //Password del mysql
$db="id6032899_dbhelpcentertfg"; //Base de datos donde se creará la tabla users

//Conectamos y seleccionamos base de datos

$connection=mysqli_connect($dbhost, $dbuser, $dbpass, $db);

//Si falla la conexión muestro el error
if ($connection === false)
{
	echo 'Ha habido un error <br>'.mysqli_connect_error();
}



mysqli_query($connection, "SET NAMES 'utf8'");


?>
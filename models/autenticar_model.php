<?php
	/**
	* En nuestro MVC es el modelo que representa la funcionalidad de autenticar usuarios
	* @author Germinal GARRIDO PUYANA
	* @version v1.1(0218)
	*/
	
require_once("../db/db.php");
class autenticar_model{
    private $db;
    private $error;
 
    public function __construct(){
        $this->db=Conectar::connection();
    }
    public function autenticar(){
		
				if (($_POST['nick'] == '') and ($_POST['pass'] == '')) //Comprobamos que las variables enviadas por el form de index.php tienen contenido
				{
					$ahora = getdate(); //Obtiene un array con los datos de la fecha y hora actual
					$fecha = $ahora["year"]."-".$ahora["mon"]."-".$ahora["mday"]." ".$ahora["hours"].":".$ahora["minutes"].":".$ahora["seconds"]; //Obtiene el formato adecuado de fecha hora para insertar en la BBDD
					$IP = $_SERVER['REMOTE_ADDR'];
					$pagina = $_SERVER['PHP_SELF'];
					$sql_insert = "INSERT INTO intrusos (IP,tipo,descripcion,fecha) values ('".$IP."',4,'".$pagina."','".$fecha."') ";
					$this->db->query($sql_insert);
					$this->error = 98; //VARIABLES VACIAS
					return $this->error;
				}
				elseif ((ctype_digit($_POST['nick']) == false) or (strlen($_POST['nick']) > 8) or (strlen($_POST['nick'] < 8)))
				{
					$this->error = 97; //USUARIOS NO VALIDOS
					return $this->error;
				}
				else
				{ 
					//Comprobamos en la Base de datos si existe ese nick con esa pass
					$sql_select = "SELECT * FROM users WHERE nick = '".$_POST['nick']."' and pass = '".$_POST['pass']."' and active = 1";
					$query_select = $this->db->query($sql_select);
					$user_ok = mysqli_fetch_array($query_select); //Obtenemos el usuario en user_ok
					if ($user_ok && $user_ok["intentos"] < 3){
						$sql_update = "UPDATE users SET intentos = 0 WHERE nick = '".$_POST['nick']."'";
						$this->db->query($sql_update);//Actualizamos el numero de intentos
						
						//Damos valores a las variables de la sesion
						$_SESSION['usuario'] = $user_ok["nick"]; //damos el nick a la variable usuario
						$_SESSION['level'] = $user_ok["level"]; //damos el level del user a la variable level
						$_SESSION['email'] = $user_ok["email"];
						$_SESSION['telephone'] = $user_ok["telephone"];
						$_SESSION['block'] = 0; //damos el level del user a la variable level
						$this->error = 1; 
						return $this->error;
					}
					else
					{
						$sql_select = "SELECT * FROM users WHERE nick = '".$_POST['nick']."'";
						$query_select = $this->db->query($sql_select);
						$numrows=mysqli_num_rows($query_select); //Numero de filas de la sentencia anterior
						if ($numrows == 0){ //No existe ese usuario en la tabla de usuarios por lo tanto ser usuario de level 4*/
							$IP = $_SERVER['REMOTE_ADDR'];
							$ahora = getdate(); //Obtiene un array con los datos de la fecha y hora actual
							$fecha = $ahora["year"]."-".$ahora["mon"]."-".$ahora["mday"]." ".$ahora["hours"].":".$ahora["minutes"].":".$ahora["seconds"]; //Obtiene el formato adecuado de fecha hora para insertar en la BBDD
							$descripcion = "NICK/PASS incorrectos";
							$sql_insert = "INSERT INTO intrusos (nick,pass,IP,tipo,descripcion,fecha) values ('".$_POST['nick']."','".$_POST['pass']."','".$IP."',3,'".$descripcion."','".$fecha."') ";
							$this->db->query($sql_insert);
							$this->error = 96; //NO EXISTE EL USUARIO EN LA BBDD
							return $this->error;
						}
						else //USUARIO QUE NO SE HA LOGUEADO CON LA CLAVE CORRECTA
						{
							$row=mysqli_fetch_row($query_select);
							$intentos = $row[9];
							$IP = $_SERVER['REMOTE_ADDR'];
							if ($intentos < 2) { //Al tercer intento fallido se bloquea
								$intentos++; //Aumentamos los intentos
								/////Incluimos el intento de intrusion
								$ahora = getdate(); //Obtiene un array con los datos de la fecha y hora actual
								$fecha = $ahora["year"]."-".$ahora["mon"]."-".$ahora["mday"]." ".$ahora["hours"].":".$ahora["minutes"].":".$ahora["seconds"]; //Obtiene el formato adecuado de fecha hora para insertar en la BBDD
								$descripcion = "Intento de intrusion: ".$intentos."";
								$sql_insert = "INSERT INTO intrusos (nick,pass,IP,tipo,descripcion,fecha) values ('".$_POST['nick']."','".$_POST['pass']."','".$IP."',1,'".$descripcion."','".$fecha."') ";
								$this->db->query($sql_insert);
								
								/////Actualizamos el numero de intentos
								$sql_update = "UPDATE users SET intentos = ".$intentos." WHERE nick = '".$_POST['nick']."'";
								$this->db->query($sql_update);
								$this->error = 90; 
								$this->error = $this->error.$intentos; //GENERARA 91,92 o 93
								return $this->error;
								
							}else { //CUENTA BLOQUEADA
								$intentos++;
								$ahora = getdate(); //Obtiene un array con los datos de la fecha y hora actual
								$fecha = $ahora["year"]."-".$ahora["mon"]."-".$ahora["mday"]." ".$ahora["hours"].":".$ahora["minutes"].":".$ahora["seconds"]; //Obtiene el formato adecuado de fecha hora para insertar en la BBDD
								$descripcion = "Cuenta bloqueada";
								$sql_insert = "INSERT INTO intrusos (nick,pass,IP,tipo,descripcion,fecha) values ('".$_POST['nick']."','".$_POST['pass']."','".$IP."',2,'".$descripcion."','".$fecha."') ";
								$this->db->query($sql_insert);
								
								/////Actualizamos el numero de intentos
								$sql_update = "UPDATE users SET intentos = ".$intentos." WHERE nick = '".$_POST['nick']."'";
								$this->db->query($sql_update);
								$this->error = 94; //CUENTA BLOQUEADA
								return $this->error;
							}
						}	
					} 
				} 
		
	}
}
?>


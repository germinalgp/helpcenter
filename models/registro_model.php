<?php
require_once("../db/db.php");
class registro_model{
    private $db;
	private $error;
 
    public function __construct(){
        $this->db=Conectar::connection();
    }
	
	/**
	* Funcion que realiza una comprobacion del formato correcto de una direccion de email
	* @author Germinal GARRIDO PUYANA
	* @param string $email cadena de texto conteniendo la direccion email a verificar
	* @return boolean true si es correcta la verificacion y false en caso contrario 
	*/
	public function verify_email_format ($email)
	{
		// El formato correcto de e-mail debe ser del tipo nombre_usuario@dominio.dom
		// Por lo tanto, buscamos la posicion de los caracteres "@" y "." en $email
		$email_at = strpos($email, "@");
		//$email_dot = strpos ($email, ".");
		$email_dot = strrpos ($email, ".");
		
				
		// Se devuelve error si en el e-mail no existe "@" o ".", o son el primer caracter,
		// o el "." se encuentra antes que la "@", o el "." es el penultimo o ultimo caracter
		if (!$email_at || !$email_dot || $email_at == 0 || $email_dot == 0 || $email_dot <= $email_at + 1 || $email_dot >= strlen($email) - 1) { return(false); }
		else { return(true); }
	}
	
    public function gestionar_registro(){
		if ( isset ( $_POST['enviar_peticion']) && $_POST['enviar_peticion'] == 1){
		//Comprobamos que los campos nick, pass y pass1 se han rellenado en el form de reg.php. sino volvemos al form
			$error = 0; $errorNick = 2; $errorPass = 2; $errorPass2 = 2; $errorNombre = 2; $errorEmail = 2; $errorTelephone = 2; $errorLevel = 2; $errorDepartamento = 2;
			if ($_POST['nick'] == ''){
				$errorNick = 1;
			}
			
			if ($_POST['pass'] == ''){
				$errorPass = 1;
			}
			
			if ($_POST['pass2'] == ''){
				$errorPass2 = 1;
			}
			
			if ($_POST['nombre'] == ''){
				$errorNombre = 1;
			}
			
			if ($_POST['email'] == ''){
				$errorEmail = 1;
			}
			
			if ($_POST['telephone'] == ''){
				$errorTelephone = 1;
			}
			
			if ($_POST['level'] == "-1"){
				$errorLevel = 1;
			}
		
			if ($_POST['departamento'] == "-1"){
				$errorDepartamento = 1;
			}
		
			//QUITAMOS CODIGO MALICIOSO
			$email = stripslashes($_POST['email']);
			$email = strip_tags($email);
			$telephone = stripslashes($_POST['telephone']);
			$telephone = strip_tags($telephone);
				

			$special_chars_num = array(" ", ".", "-", "_", "(", ")","+","*","/");	
			$special_chars_email = array(" ", "(", ")","+","*","/");				
					
		 
			str_replace($special_chars_num, "", $telephone);
			str_replace($special_chars_email, "", $email);
		
		
			if (($errorNick == 1) || ($errorPass == 1) || ($errorPass2 == 1) || ($errorNombre == 1) || ($errorEmail == 1) || ($errorTelephone == 1) || ($errorLevel == 1) || ($errorDepartamento == 1)){
				$error = $errorNick.$errorPass.$errorPass2.$errorNombre.$errorEmail.$errorTelephone.$errorLevel.$errorDepartamento;
			}else if((ctype_digit($_POST['nick']) == false) or (strlen($_POST['nick']) != 8)){
				$error = 2;
			}else if ($_POST['pass'] != $_POST['pass2']){
				$error = 3; //PASS ES DISTINTA DE LA CONFIRMACION
			}else if ((ctype_digit($telephone) == false) or (strlen($telephone) < 9)){
				$error = 4; //PASS ES DISTINTA DE LA CONFIRMACION
			}else if (!$this->verify_email_format($email)) { 
				$error = 5; 
			}else{

				$user = stripslashes($_POST['nick']);
				$user = strip_tags($user);
				$pass = stripslashes($_POST['pass']);
				$pass = strip_tags($pass);
			
				//Comprobamos que el usuario no existe en la BBDD
				
				$sql_query = "SELECT nick FROM users WHERE nick = '".$user."'";
				$query = $this->db->query($sql_query);
				$numrows = mysqli_num_rows($query);
				
				if ($numrows != 0){
					$error = 6; //USUARIO YA REGISTRADO
					$query->free_result();				
				}else{
					//Quitamos todo el codigo malicioso de las demas variables del form de registro
					$nombre = stripslashes($_POST['nombre']);
					$nombre = strip_tags($nombre);
					$pass2 = stripslashes($_POST['pass2']);
					$pass2 = strip_tags($pass2);
					$level = stripslashes($_POST['level']);
					$level = strip_tags($level);
					$departamento = stripslashes($_POST['departamento']);
					$departamento = strip_tags($departamento);
					
					$ahora = getdate(); //Obtiene un array con los datos de la fecha y hora actual
					$fecha = $ahora["year"]."-".$ahora["mon"]."-".$ahora["mday"]." ".$ahora["hours"].":".$ahora["minutes"].":".$ahora["seconds"]; //Obtiene el formato adecuado de fecha hora para insertar en la BBDD
					//Introducimos el nuevo registro en la tabla users
					$sql_insert = "INSERT INTO users (nick,pass,nombre,fecha,level,departamento, registrador, reseteador, active) values ('".$user."','".$pass."','".$nombre."','".$fecha."','".$level."','".$departamento."','".$_SESSION['usuario']."','', '1') ";
					$this->db->query($sql_insert);
				}

			}
			$this->error = $error;
			return $this->error;

		}else if ( isset ( $_POST['enviar_peticion']) && $_POST['enviar_peticion'] == 2){ //ACTIVAR USUARIOS QUE HAN PEDIDO EL REGISTRO
			$userid = $_POST['ID'];
			$sql_update = "UPDATE users SET active = '1' WHERE nick LIKE '".$userid."'";
			$this->db->query($sql_update);
	
		}else if ( isset ( $_POST['enviar_peticion']) && $_POST['enviar_peticion'] == 3){ //ELIMINAR USUARIOS
			$userid = $_POST['ESTADO'];
			$ahora = getdate(); //Obtiene un array con los datos de la fecha y hora actual
			$fecha = $ahora["year"]."-".$ahora["mon"]."-".$ahora["mday"]." ".$ahora["hours"].":".$ahora["minutes"].":".$ahora["seconds"]; //Obtiene el formato adecuado de fecha hora para insertar en la BBDD
			$sql_insert = "INSERT INTO delete_users SELECT nick, nombre, email, telephone, '".$fecha."', level, departamento, cod_departamento, registrador, '".$_SESSION['usuario']."' FROM users WHERE nick LIKE '".$userid."'";
			$sql_delete = "DELETE FROM users WHERE nick LIKE '".$userid."'";
			$this->db->query($sql_insert);
			$this->db->query($sql_delete);
	
		}
		
	}
	
}
?>
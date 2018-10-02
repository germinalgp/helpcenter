<?php
require_once("../db/db.php");
class cambiar_pass_model{
    private $db;
    private $mensaje;
 
    public function __construct(){
        $this->db=Conectar::connection();
		$this->mensaje = "";
    }
    public function cambiar_pass(){
		if ($_SESSION['level'] == 9 || $_SESSION['level'] == 4 || $_SESSION['level'] == 3 || $_SESSION['level'] == 2 || $_SESSION['level'] == 1) {
			if ( isset ( $_POST['enviar_peticion']) && $_POST['enviar_peticion'] == 1){ //SE TRATA DE UN CAMBIO DE PASS PROPIO
				if (($_POST['pass'] == '') or ($_POST['pass2'] == ''))
				{
					$this->mensaje = 2;
					return $this->mensaje; //Enviamos al form de reseteo con el mensaje
				}else{
					$sql_busqueda = "SELECT * FROM users WHERE nick LIKE '".$_SESSION['usuario']."'";
					$consulta=$this->db->query($sql_busqueda);
					
					$row=mysqli_fetch_row($consulta);
					$pass=$row[1];
					//Comprobamos que la pass y pass1 son iguales, sino volvemos a reseteo con el mensaje
					if (($_POST['pass'] != $_POST['pass2']) || ($_POST['antigua'] != $pass)){
						$this->mensaje = 2;
						return $this->mensaje; //Enviamos al form de reseteo con el mensaje
					}else{
					//Quitamos el codigo malicioso de $_POST[nick] y $_POST['pass']
					$pass = stripslashes($_POST['pass']);
					$pass = strip_tags($pass);
					$reseteador = $_SESSION['usuario'];
					$sql_update = "UPDATE users SET pass = '".$pass."', intentos = 0, reseteador ='".$reseteador."' WHERE nick LIKE '".$_SESSION['usuario']."'";
					$this->db->query($sql_update);
					$this->mensaje = 1;
					return $this->mensaje; //Enviamos al form de reseteo con el mensaje
					}
				}
			}else if ( isset ( $_POST['enviar_peticion']) && $_POST['enviar_peticion'] == 2){ //SE TRATA DE UN CAMBIO DE PASS DE OTRO USUARIO
				if (($_POST['pass'] == '') or ($_POST['pass2'] == '')){
					$this->mensaje = 2;
					return $this->mensaje; //Enviamos al form de reseteo con el mensaje
				}else{
					$sql_busqueda = "SELECT * FROM users WHERE nick LIKE '".$_POST['nick']."'";
					$consulta=$this->db->query($sql_busqueda);
					$row=mysqli_fetch_row($consulta);
					$pass=$row[1];
					//Comprobamos que la pass y pass1 son iguales, sino volvemos a reseteo con el mensaje
					if ($_POST['pass'] != $_POST['pass2']){
						$this->mensaje = 2;
						return $this->mensaje; //Enviamos al form de reseteo con el mensaje
					}else{
						//Quitamos el codigo malicioso de $_POST['pass']
						$pass = stripslashes($_POST['pass']);
						$pass = strip_tags($pass);
						$reseteador = $_SESSION['usuario'];
						$sql_update = "UPDATE users SET pass = '".$pass."', intentos = 0, reseteador ='".$reseteador."' WHERE nick LIKE '".$_POST['nick']."'";
						$this->db->query($sql_update);
						$this->mensaje = 1;
						return $this->mensaje; //Enviamos al form de reseteo con el mensaje
					}
				}
			}else if ( isset ( $_POST['enviar_peticion']) && $_POST['enviar_peticion'] == 3){ //SE TRATA DE UN RESETEO DE PASS
				$reseteador = $_SESSION['usuario'];
				$sql_update = "UPDATE users SET intentos = 0, reseteador ='".$reseteador."' WHERE nick LIKE '".$_POST['nick2']."'";
				$this->db->query($sql_update);
				$this->mensaje = 3;
				return $this->mensaje; //Enviamos al form de reseteo con el mensaje
			
		
			}	
		}
	}
}	
?>
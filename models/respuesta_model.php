<?php
	/**
	* En nuestro MVC es el modelo que representa la funcionalidad de gestionar las incidencias
	* @author Germinal GARRIDO PUYANA
	* @version v1.1(0218)
	*/
	
require_once("../db/db.php");
class respuesta_model{
    private $db;
	private $error;
 
    public function __construct(){
        $this->db=Conectar::connection();
    }
    public function responder(){
		if ($_SESSION['level'] == 4 || $_SESSION['level'] == 3 || $_SESSION['level'] == 2 || $_SESSION['level'] == 1){ //SI SESSION Y NIVEL ADECUADO
			// Obtener la fecha de realizacion de la peticion de incidencia
			$fecha_peticion = getdate ();
			$fecha_peticion = $fecha_peticion['year']."-".$fecha_peticion['mon']."-".$fecha_peticion['mday']." ".$fecha_peticion['hours'].":".$fecha_peticion['minutes'].":".$fecha_peticion['seconds'];
			
			/**
			 * TIPOS DE COMENTARIOS
			 * 0- ESCRITURA DE TEXTO
			 * 1- CAMBIO ESTADO: ABIERTA
			 * 2- CAMBIO ESTADO: EN TRAMITE
			 * 3- CAMBIO ESTADO: CERRADA
			 * 4- CAMBIO COMPETENCIA: COORDINACION
			 * 5- CAMBIO COMPETENCIA: ADMINISTRACION
			 * 6- CAMBIO COMPETENCIA: TECNICA
			 */
			
			$comentario = "";
			if ( isset ( $_POST['ck_comentario'] ) ){
				$comentario = $_POST['ck_comentario'];
			}
			
			
			
			if ($comentario==''){ //Si el comentario esta en blanco pero lo ponemos a tramite o lo cerramos 
				$sql_estado_actual = "SELECT STATE FROM peticiones WHERE ID = ".$_POST['ID']."";
				$query_estado_actual = $this->db->query($sql_estado_actual);
				$row_estado_actual = mysqli_fetch_row ($query_estado_actual); //TENEMOS EL ESTADO ACTUAL		

				//COMPROBAMOS SI HEMOS CAMBIADO DE ESTADO
				if ($row_estado_actual[0]!=$_POST['ESTADO']){ //SE HA CAMBIADO DE ESTADO
					//ESTAMOS HACIENDO UN CAMBIO DE ESTADO
					$nuevo_estado = $_POST['ESTADO'];
					switch ($nuevo_estado){
						case 0 : $tipo = 1;
								//$comentario = "CAMBIO ESTADO: ABIERTA";
							break;
						case 1 : $tipo = 2;
								 //$comentario = "CAMBIO ESTADO: EN TRAMITE";
							break;
						case 2 : $tipo = 3; 
								 //$comentario = "CAMBIO ESTADO: CERRADA";
							break;
					}
					$fecha_peticion = getdate ();
					$fecha_peticion = $fecha_peticion['year']."-".$fecha_peticion['mon']."-".$fecha_peticion['mday']." ".$fecha_peticion['hours'].":".$fecha_peticion['minutes'].":".$fecha_peticion['seconds'];
					if ($nuevo_estado == 2){ //SI CERRAMOS LA INCIDENCIA
						$sql_update = "UPDATE peticiones SET STATE = ".$_POST['ESTADO'].", DATE_CLOSE = '".$fecha_peticion."', USER_CLOSE = '".$_SESSION['usuario']."', LAST_USER_MODIFY = '".$_SESSION['usuario']."', LAST_DATE_MODIFY = '".$fecha_peticion."' WHERE ID = ".$_POST['ID']."";
						$this->db->query($sql_update); //Ponemos Estado al nuevo estado	
					}else{
						$sql_update = "UPDATE peticiones SET STATE = ".$_POST['ESTADO'].", LAST_USER_MODIFY = '".$_SESSION['usuario']."', LAST_DATE_MODIFY = '".$fecha_peticion."' WHERE ID = ".$_POST['ID']."";
						$this->db->query($sql_update); //Ponemos Estado al nuevo estado	
					}
					$sql_insert = "INSERT INTO historial (id_issue, author, tipo, date) values ('".$_POST['ID']."','".$_SESSION['usuario']."','".$tipo."','".$fecha_peticion."')";
					$this->db->query($sql_insert);
				}	
			}else { //SI HA ESCRITO ALGO
				$sql_estado_actual = "SELECT STATE FROM peticiones WHERE ID = ".$_POST['ID']."";
				$query_estado_actual = $this->db->query($sql_estado_actual);
				$row_estado_actual = mysqli_fetch_row ($query_estado_actual); //TENEMOS EL ESTADO ACTUAL	
				
				//COMPROBAMOS SI HEMOS CAMBIADO DE ESTADO
				if ($row_estado_actual[0]!=$_POST['ESTADO']){ //SE HA CAMBIADO DE ESTADO
					//ESTAMOS HACIENDO UN CAMBIO DE ESTADO
					$nuevo_estado = $_POST['ESTADO'];
					switch ($nuevo_estado){
						case 0 : $tipo = 1;
								 //$comentario = "CAMBIO ESTADO: ABIERTA";
							break;
						case 1 : $tipo = 2;
								 //$comentario = "CAMBIO ESTADO: EN TRAMITE";
							break;
						case 2 : $tipo = 3; 
								 //$comentario = "CAMBIO ESTADO: CERRADA";
							break;
					}
					$fecha_peticion = getdate ();
					$fecha_peticion = $fecha_peticion['year']."-".$fecha_peticion['mon']."-".$fecha_peticion['mday']." ".$fecha_peticion['hours'].":".$fecha_peticion['minutes'].":".$fecha_peticion['seconds'];
					if ($nuevo_estado == 2){ //SI CERRAMOS LA INCIDENCIA
						$sql_update = "UPDATE peticiones SET STATE = ".$_POST['ESTADO'].", DATE_CLOSE = '".$fecha_peticion."', USER_CLOSE = '".$_SESSION['usuario']."', LAST_USER_MODIFY = '".$_SESSION['usuario']."', LAST_DATE_MODIFY = '".$fecha_peticion."' WHERE ID = ".$_POST['ID']."";
						$this->db->query($sql_update); //Ponemos Estado al nuevo estado	
					}else{
						$sql_update = "UPDATE peticiones SET STATE = ".$_POST['ESTADO'].", LAST_USER_MODIFY = '".$_SESSION['usuario']."', LAST_DATE_MODIFY = '".$fecha_peticion."' WHERE ID = ".$_POST['ID']."";
						$this->db->query($sql_update); //Ponemos Estado al nuevo estado	
					}
					$sql_insert_comentarios = "INSERT INTO comentarios (id_issue, author, comments, date) values ('".$_POST['ID']."','".$_SESSION['usuario']."','".$comentario."','".$fecha_peticion."')";
					$sql_insert_historial = "INSERT INTO historial (id_issue, author, tipo, date) values ('".$_POST['ID']."','".$_SESSION['usuario']."','".$tipo."','".$fecha_peticion."')";
					$this->db->query($sql_insert_comentarios); //Ponemos Estado al nuevo estado	
					$this->db->query($sql_insert_historial); //Ponemos Estado al nuevo estado	
				}else{
					$fecha_peticion = getdate ();
					$fecha_peticion = $fecha_peticion['year']."-".$fecha_peticion['mon']."-".$fecha_peticion['mday']." ".$fecha_peticion['hours'].":".$fecha_peticion['minutes'].":".$fecha_peticion['seconds'];
					if ($nuevo_estado == 2){ //SI CERRAMOS LA INCIDENCIA
						$sql_update = "UPDATE peticiones SET STATE = ".$_POST['ESTADO'].", DATE_CLOSE = '".$fecha_peticion."', USER_CLOSE = '".$_SESSION['usuario']."', LAST_USER_MODIFY = '".$_SESSION['usuario']."', LAST_DATE_MODIFY = '".$fecha_peticion."' WHERE ID = ".$_POST['ID']."";
						$this->db->query($sql_update); //Ponemos Estado al nuevo estado	
					}else{
						$sql_update = "UPDATE peticiones SET STATE = ".$_POST['ESTADO'].", LAST_USER_MODIFY = '".$_SESSION['usuario']."', LAST_DATE_MODIFY = '".$fecha_peticion."' WHERE ID = ".$_POST['ID']."";
						$this->db->query($sql_update); //Ponemos Estado al nuevo estado	
					}
					$sql_insert = "INSERT INTO comentarios (id_issue, author, comments, date) values ('".$_POST['ID']."','".$_SESSION['usuario']."','".$comentario."','".$fecha_peticion."')";
					$this->db->query($sql_insert);
				}
			}
			$this->error = 1;
			$this->error = $this->error.$_POST['ID'];
			return $this->error;
		}else{ //GRABAMOS
			$ahora = getdate(); //Obtiene un array con los datos de la fecha y hora actual
			$fecha = $ahora["year"]."-".$ahora["mon"]."-".$ahora["mday"]." ".$ahora["hours"].":".$ahora["minutes"].":".$ahora["seconds"]; //Obtiene el formato adecuado de fecha hora para insertar en la BBDD
			$IP = $_SERVER['REMOTE_ADDR'];
			$pagina = $_SERVER['PHP_SELF'];
			$sql_insert = "INSERT INTO intrusos (IP,tipo,descripcion,fecha) values ('".$IP."',4,'".$pagina."','".$fecha."') ";
			$this->db->query($sql_insert);
			$this->error = 99;
			return $this->error;
			
		}
	}
}
?>
<?php
require_once("../db/db.php");
class peticion_model{
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
	
    public function peticion(){
		if ($_SESSION['level'] == 9){
			$this->error = 21;
			$header = "";
			
			$combo_issue_type = "";
			$combo_category_type = "";
			$combo_product_type = "";
			$post_fecha_compra = "";
			$post_id_anterior = "";
			$post_n_serie = "";
			$post_comentario = "";
			
			if ( isset ( $_POST['combo_issue_type'] ) ){
				$combo_issue_type = $_POST['combo_issue_type'];
			}
			if ( isset ( $_POST['combo_category_type'] ) ){
				$combo_category_type = $_POST['combo_category_type'];
			}
			if ( isset ( $_POST['combo_product_type'] ) ){
				$combo_product_type = $_POST['combo_product_type'];
			}
			if ( isset ( $_POST['fecha_compra'] ) ){
				$post_fecha_compra = $_POST['fecha_compra'];
			}
			if ( isset ( $_POST['id_anterior'] ) ){
				$post_id_anterior = $_POST['id_anterior']; //RECOGEMOS EL TIPO DE INCIDENCIA SELECCIONADA
			}
			if ( isset ( $_POST['n_serie'] ) ){
				$post_n_serie = $_POST['n_serie'];
			}
			if ( isset ( $_POST['comentario'] ) ){
				$post_comentario = $_POST['comentario'];
			}

			if (($combo_issue_type=="0001") || ($combo_issue_type=="0002")){ //SOFTWARE / HARDWARE
				if ($post_n_serie=="" || $post_fecha_compra=="" || $post_comentario==""){
					$this->error = 22; //Comprobacion de campos vacios
				}
			}else if ($combo_issue_type=="0006"){ //REITEROS
				
				if ($post_id_anterior == "" || $post_comentario==""){
					$this->error = 22;
				}else{
					//COMPROBACION DE QUE DICHA ID ESTE CERRADA o en TRAMITE y QUE EL TIEMPO SOLICITADO ES CORRECTO
					$sql_select = "SELECT ISSUE_TYPE, TIME_TO_SEC(TIMEDIFF(NOW(),DATE)) FROM peticiones WHERE ID = $post_id_anterior AND USER_OPEN = '".$_SESSION['usuario']."'";
					$query = $this->db->query($sql_select);
					$numrows = mysqli_num_rows($query);
					
					if ($numrows == 0){
						$this->error = 29;
					}else{
						$fila_verificacion = mysqli_fetch_row ($query);	 
						switch ($fila_verificacion[0]){
							case "0001": //SOFTWARE
								$combo_issue_type = "0006";
								if ($fila_verificacion[1]<345600){ //1 DIA
									$this->error = 27;
								} 
								break;
							case "0002": //HARDWARE
								$combo_issue_type = "0004";
								if ($fila_verificacion[1]<345600){ //4 DIAS
									$this->error = 27;
								}  
								break;
							case "0004": //REITERO--- NO SE PUEDEN REALIZAR REITEROS DE REITEROS (SIEMPRE REITERAR SOBRE EL ORIGEN)
								$this->error = 28;
								break;
							case "0005": //REITERO--- NO SE PUEDEN REALIZAR REITEROS DE REITEROS (SIEMPRE REITERAR SOBRE EL ORIGEN)
								$this->error = 28;
								break;
							case "0006": //REITERO--- NO SE PUEDEN REALIZAR REITEROS DE REITEROS (SIEMPRE REITERAR SOBRE EL ORIGEN)
								$this->error = 28;
								break;
							case "0003": //OTRA INCIDENCIA
								$combo_issue_type = "0005";
								if ($fila_verificacion[1]<604800){ //7 DIAS
									$this->error = 27;
								}  
								break;
						}
					}
					
					
				}
			}else if ($combo_issue_type=="0003"){
				
				if ($post_comentario == ""){
					$this->error = 22;
				}
			}

			

			// Si se produce error tipo 21 (no hay errores), se procesan los datos introducidos
			if ($this->error == 21)
			{
				// Insertamos en la BB.DD. la peticion de incidencia exitosa
				
				// Obtener la fecha de realizacion de la peticion de incidencia
				$fecha_peticion = getdate ();
				$fecha_peticion2 = $fecha_peticion['year']."-".$fecha_peticion['mon']."-".$fecha_peticion['mday']." ".$fecha_peticion['hours'].":".$fecha_peticion['minutes'].":".$fecha_peticion['seconds'];
				
				//OBTENER COMPETENCIA INICIAL
				$sql_select_competencia = "SELECT TIPO FROM tipos_combos WHERE ID_COMBO = '".$combo_issue_type."'";
				$query_competencia = $this->db->query($sql_select_competencia);
				$row_competencia = mysqli_fetch_row ($query_competencia);	 
				
				// Actualizar la tabla "peticiones" con los datos de la peticion
				$sql_insert = "INSERT INTO peticiones (user_open, telephone, email, issue_type, category_type, product_type, fecha_compra, id_anterior, n_serie, state, block, date, competencia)
							 values('".$_SESSION['usuario']."','".$_SESSION['telephone']."','".$_SESSION['email']."', '".$combo_issue_type."','".$combo_category_type."','".$combo_product_type."','".$post_fecha_compra."','".$post_id_anterior."','".$post_n_serie."','0','0', '".$fecha_peticion2."','".$row_competencia[0]."')";
				$this->db->query($sql_insert);
				
				$sql_select_last_issue = "SELECT MAX(id) FROM peticiones";
				$query_last_issue = $this->db->query($sql_select_last_issue);
				$row = mysqli_fetch_row ($query_last_issue);			
				
				// Actualizar la tabla "comentarios" con los datos de la peticion
				$sql_insert2 = "INSERT INTO comentarios (id_issue, author, comments, date) values ('".$row[0]."','".$_SESSION['usuario']."','".$post_comentario."','".$fecha_peticion2."')";
				$this->db->query($sql_insert2);
				return $this->error; 
				
				
				//$header = $header . "&numero=" . $row[0];
				//Header("Location: index.php?mensaje=".$error."" . $header);
			}else{ 

				// El tipo de error se manda por POST mediante "mensaje" y "descripcion" para ser posteriomente devuelto por pantalla.
				// Asimismo, el numero de incidencia se manda por POST mediante "numero" para que el usuario lo utilice en futuras referencias
				return $this->error; 
				
				//$header = $header . "&numero=" . $row[0];
				//Header("Location: peticion.php?mensaje=".$error."" . $header);
	
			}
			
			
		}else{
			$ahora = getdate(); //Obtiene un array con los datos de la fecha y hora actual
			$fecha = $ahora["year"]."-".$ahora["mon"]."-".$ahora["mday"]." ".$ahora["hours"].":".$ahora["minutes"].":".$ahora["seconds"]; //Obtiene el formato adecuado de fecha hora para insertar en la BBDD
			$IP = $_SERVER['REMOTE_ADDR'];
			$pagina = $_SERVER['PHP_SELF'];
			$sql_insert = "INSERT INTO intrusos (IP,tipo,descripcion,fecha) values ('".$IP."',4,'".$pagina."','".$fecha."') ";
			$this->db->query($sql_insert);
			$this->error = 1;
			return $this->error; 
		}
		
	}
	
}
?>
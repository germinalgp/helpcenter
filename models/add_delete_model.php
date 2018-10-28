<?php
require_once("../db/db.php");
class add_delete_model{
    private $db;
    private $error;
 
    public function __construct(){
        $this->db=Conectar::connection();
    }
    public function add_delete(){
		if ($_SESSION['level'] == 1){
			if ($_POST['enviar_peticion'] == 1){
				if ($_POST['combo_product_type'] != -1){ //HEMOS SELECCIONADO UN PRODUCTO
					$combo_product_type = $_POST['combo_product_type'];
					$sql_delete = "DELETE FROM tipos_combos WHERE ID_COMBO = '".$combo_product_type."'";
					$this->db->query($sql_delete);
					$this->error = 16;
				}else if ($_POST['combo_category_type']!= -1){ //HEMOS SELECCIONADO UNA CATEGORIA
					$combo_category_type = $_POST['combo_category_type'];
					$sql_delete = "DELETE FROM tipos_combos WHERE ID_COMBO = '".$combo_category_type."' OR ID_PADRE = '".$combo_category_type."'";
					$this->db->query($sql_delete);
					$this->error = 17;
				}else {//NO HEMOS SELECCIONADO NADA --- ERROR
					$this->error = 7;
				}
			}else if ($_POST['enviar_peticion'] == 2){
				if ($_POST['combo_issue_type'] == -1){
					$this->error = 8;
				}else if ($_POST['new_category'] == ""){
					$this->error = 9;
				}else{
					$combo_issue_type = $_POST['combo_issue_type'];
					$new_category = $_POST['new_category'];
					//COMPROBAMOS QUE NO EXISTA
					$sql_select = "SELECT ID_COMBO FROM tipos_combos WHERE DESCRIPCION = '".$new_category."' AND ID_PADRE = '".$combo_issue_type."'";
					$query = $this->db->query($sql_select);
					$numrows = mysqli_num_rows($query);
					
					if ($numrows == 0){
						$sql_select_2 = "SELECT MAX(id_combo) FROM tipos_combos";
						$query_2 = $this->db->query($sql_select_2);
						$row_max_id=mysqli_fetch_row($query_2);
						$arreglo = (int)$row_max_id[0]+1;
						$arreglo = str_pad($arreglo, 4, "0", STR_PAD_LEFT); //YA TENEMOS EL SIGUIENTE ID A ASIGNAR
						$sql_insert = "INSERT INTO tipos_combos (ID_COMBO, ID_PADRE, DESCRIPCION, TIPO, ORDEN) VALUES ('".$arreglo."', '".$combo_issue_type."', '".$new_category."', 'CATEGORIA', '0')";
						$this->db->query($sql_insert);
						$this->error = 15;
					}else{
						$this->error = 10;
					}
					
				}
			}else if ($_POST['enviar_peticion'] == 3){
			if ($_POST['combo_category2_type'] == -1){
					$this->error = 11;
				}else if ($_POST['new_product'] == ""){
					$this->error = 12;
				}else{
					$combo_category2_type = $_POST['combo_category2_type'];
					$new_product = $_POST['new_product'];
					//COMPROBAMOS QUE NO EXISTA
					$sql_select = "SELECT ID_COMBO FROM tipos_combos WHERE DESCRIPCION = '".$new_product."'";
					$query = $this->db->query($sql_select);
					$numrows = mysqli_num_rows($query);
					if ($numrows == 0){
						$sql_select_2 = "SELECT MAX(id_combo) FROM tipos_combos";
						$query_2 = $this->db->query($sql_select_2);
						$row_max_id=mysqli_fetch_row($query_2);
						$arreglo = (int)$row_max_id[0]+1;
						$arreglo = str_pad($arreglo, 4, "0", STR_PAD_LEFT); //YA TENEMOS EL SIGUIENTE ID A ASIGNAR
						$sql_insert = "INSERT INTO tipos_combos (ID_COMBO, ID_PADRE, DESCRIPCION, TIPO, ORDEN) VALUES ('".$arreglo."', '".$combo_category2_type."', '".$new_product."', 'PRODUCTO', '0')";
						$this->db->query($sql_insert);
						$this->error = 14;
					}else{
						$this->error = 13;
						
					}
				}
				
			
			}
			return $this->error;
		}else{
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
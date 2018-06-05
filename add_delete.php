<?php
require('conexion.php'); //Incluimos el conexion.php que contiene los datos de la conexion a la base de datos
include('menu.php');

if ($_SESSION['level'] == 1){


	if (!isset ( $_POST['enviar_peticion'])|| (($_POST['enviar_peticion'] != 1) && ($_POST['enviar_peticion'] != 2) && ($_POST['enviar_peticion'] != 3))){
		echo '<html>
		  <head>
		 		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
				<title>HELPCENTER</title>
				<link href="styles.css" rel="stylesheet" type="text/css" />
				<script type="text/javascript" src="tinybox.js"></script>
				<script type="text/javascript">
   					function Valida (formulario, opc, confirmar){
   						if (confirmar == 1){
   							if (confirm(\'Esta seguro de eliminar la categoria o producto?. Una vez borrado no se podrá volver atrás. \n(NOTA: en caso de Categoria, borrará todos los productos asociados)\')){
   								formulario.enviar_peticion.value=opc;
   								formulario.submit();
   							}
   						}else{
   								formulario.enviar_peticion.value=opc;
   								formulario.submit();
   						}
   					}
   				</script>		
		 </head>';

	   $mensaje = "";
	
		if ( isset ( $_GET['mensaje'] ) ){
			$mensaje = $_GET['mensaje'];
		}	

		if ($mensaje != ''){
			echo '<body style="font: 13px/20px sans-serif;" link="#0000ff" vlink="#0000ff" onload="TINY.box.show({url:\'message.php?mensaje='.$mensaje.'\',width:320,height:100})">';
		}else{
			echo '<body style="font: 13px/20px sans-serif;" link="#0000ff" vlink="#0000ff">';
		}
	    echo '<p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p>';
	
		menu_int(0, 1, 0, 0, 0, 0);
		
		$combo_category_type ="";
		if ( isset ( $_POST['combo_category_type'] ) ){
			$combo_category_type = $_POST['combo_category_type'];
		}
		
		
		//INICIO PRIMER FORMULARIO ---- DELETE CATEGORIA PRODUCTO
		echo '<form id="searchformPeticion" name="deletecat_pro" method="post" action="add_delete.php">
					<fieldset>
					<legend>Borrar Categoria / Producto</legend>
					<input type="hidden" name="enviar_peticion" value="0" size="1"></input>';

				if ($combo_category_type != ""){
				echo '<label id="label2" for="combo_category_type">
						<select name="combo_category_type" tabindex="5" id="combo_category_type" onChange="searchformPeticion.submit();">';
						$tabla_categorias = mysqli_query($connection, "SELECT id_combo, descripcion FROM tipos_combos WHERE tipo = 'CATEGORIA' ORDER BY descripcion ASC"); //Sentencia para buscarlo en la base de datos
						while($row_tabla_categorias=mysqli_fetch_array($tabla_categorias))
						{	
							if ($combo_category_type==$row_tabla_categorias[0]){
							echo '<option value="'.$row_tabla_categorias[0].'" selected="selected">'.$row_tabla_categorias[1].'</option>';
							}else {
							echo '<option value="'.$row_tabla_categorias[0].'">'.$row_tabla_categorias[1].'</option>';
							}
						}
						echo '</select>Categoria: <b class="error">(*)</b>
							</label>
							<label id="label2" for="combo_product_type">
						<select name="combo_product_type" tabindex="5" id="combo_product_type">';
						$tabla_productos = mysqli_query($connection, "SELECT id_combo, descripcion FROM tipos_combos WHERE id_padre = '".$combo_category_type."' ORDER BY descripcion ASC"); //Sentencia para buscarlo en la base de datos
					echo '<option value = "-1" selected="selected">Elija una opci&#243;n:</option>';	
						while($row_tabla_productos=mysqli_fetch_array($tabla_productos))
						{	
							echo '<option value="'.$row_tabla_productos[0].'">'.$row_tabla_productos[1].'</option>';
						}
					echo '</select>Producto: <b class="error">(*)</b>
					</label>
					</fieldset></form>';
				}else{
				echo '<label id="label2" for="combo_category_type">
						<select name="combo_category_type" tabindex="5" id="combo_category_type" onChange="searchformPeticion.submit();">';
						$tabla_categorias = mysqli_query($connection, "SELECT id_combo, descripcion FROM tipos_combos WHERE tipo = 'CATEGORIA' ORDER BY descripcion ASC"); //Sentencia para buscarlo en la base de datos
						echo '<option value = "-1" selected="selected">Elija una opci&#243;n:</option>';
						while($row_tabla_categorias=mysqli_fetch_array($tabla_categorias))
						{	
							echo '<option value="'.$row_tabla_categorias[0].'">'.$row_tabla_categorias[1].'</option>';
						}
						echo '</select>Categoria: <b class="error">(*)</b>
							</label>
							
					  <label id="label2" for="combo_product_type">
						<select name="combo_product_type" tabindex="5" id="combo_product_type">';
						echo '<option value = "-1" selected="selected">Elija una opci&#243;n:</option>';
					echo '</select>Producto: <b class="error">(*)</b>
					</label>
					</fieldset></form>';
				}

				echo '<form id="formSubmit" onClick="Valida(deletecat_pro, 1,1);" action="">
				<label id="label4" for="Submit">
					<input name="submit" type="button" id="submit" tabindex="12" value="Enviar"></input>
				</label></form>
				<hr width="550" align="left" />';
				
				///////////////////////FIN PRIMER FORMULARIO --- DELETE CATEGORIA PRODUCTO
				
				///////////////////////INICIO SEGUNDO FORMULARIO ---- ADD CATEGORIA
			echo '<form id="searchformPeticion" name= "add_category" method="post" action="add_delete.php">
					<fieldset>
					<legend>Añadir categoría</legend>
					<input type="hidden" name="enviar_peticion" value="0" size="1"></input>
					<label id="label2" for="combo_issue_type">
						<select name="combo_issue_type" tabindex="5" id="combo_issue_type">';
						$tabla_incidencias = mysqli_query($connection, "SELECT id_combo, descripcion FROM tipos_combos WHERE descripcion = 'SOFTWARE' OR descripcion = 'HARDWARE' ORDER BY descripcion ASC"); //Sentencia para buscarlo en la base de datos
						echo '<option value = "-1" selected="selected">Elija una opci&#243;n:</option>';
						while($row_tabla_incidencias=mysqli_fetch_array($tabla_incidencias))
						{	
							echo '<option value="'.$row_tabla_incidencias[0].'">'.$row_tabla_incidencias[1].'</option>';
						}
						echo '</select>Tipo: <b class="error">(*)</b>
					</label>
					<label id="label2" for="new_category">
						<input class = "blanco" type="text" name="new_category" tabindex="3" id="new_category"></input>Nueva categoría: <b class="error">(*)</b>
					 </label>
							
					
					</fieldset></form>';
				

				echo '<form id="formSubmit" onClick="Valida(add_category, 2,0);" action="">
				<label id="label4" for="Submit">
					<input name="submit" type="button" id="submit" tabindex="12" value="Enviar"></input>
				</label></form>
				<hr width="550" align="left" />';
				///////////////////////FIN SEGUNDO FORMULARIO ---- ADD CATEGORIA
				
				///////////////////////INICIO TERCER FORMULARIO ---- ADD PRODUCTO
			echo '<form id="searchformPeticion" name= "add_product" method="post" action="add_delete.php">
					<fieldset>
					<legend>Añadir producto</legend>
					<input type="hidden" name="enviar_peticion" value="0" size="1"></input>
					<label id="label2" for="combo_category2_type">
						<select name="combo_category2_type" tabindex="5" id="combo_category2_type">';
						$tabla_categorias = mysqli_query($connection, "SELECT id_combo, descripcion FROM tipos_combos WHERE tipo = 'CATEGORIA' ORDER BY descripcion ASC"); //Sentencia para buscarlo en la base de datos
						echo '<option value = "-1" selected="selected">Elija una opci&#243;n:</option>';
						while($row_tabla_categorias=mysqli_fetch_array($tabla_categorias))
						{	
							echo '<option value="'.$row_tabla_categorias[0].'">'.$row_tabla_categorias[1].'</option>';
						}
						echo '</select>Categoria: <b class="error">(*)</b>
					</label>	
					<label id="label2" for="new_product">
						<input class = "blanco" type="text" name="new_product" tabindex="3" id="new_product"></input>Nuevo producto: <b class="error">(*)</b>
					 </label>
							
					
					</fieldset></form>';
				

				echo '<form id="formSubmit" onClick="Valida(add_product, 3,0);" action="">
				<label id="label4" for="Submit">
					<input name="submit" type="button" id="submit" tabindex="12" value="Enviar"></input>
				</label></form>
				</body></html>';

	}else if ($_POST['enviar_peticion'] == 1){
		if ($_POST['combo_product_type'] != -1){ //HEMOS SELECCIONADO UN PRODUCTO
			$combo_product_type = $_POST['combo_product_type'];
			mysqli_query($connection, "DELETE FROM tipos_combos WHERE ID_COMBO = '".$combo_product_type."'");
			$error = 16;
			Header("Location:add_delete.php?mensaje=".$error.""); 
		}else if ($_POST['combo_category_type']!= -1){ //HEMOS SELECCIONADO UNA CATEGORIA
			$combo_category_type = $_POST['combo_category_type'];
			mysqli_query($connection, "DELETE FROM tipos_combos WHERE ID_COMBO = '".$combo_category_type."' OR ID_PADRE = '".$combo_category_type."'");
			$error = 17;
			Header("Location:add_delete.php?mensaje=".$error.""); 
		}else {//NO HEMOS SELECCIONADO NADA --- ERROR
			$error = 7;
			Header("Location:add_delete.php?mensaje=".$error."");
		}
	}else if ($_POST['enviar_peticion'] == 2){
		if ($_POST['combo_issue_type'] == -1){
			$error = 8;
			Header("Location:add_delete.php?mensaje=".$error.""); 
		}else if ($_POST['new_category'] == ""){
			$error = 9;
			Header("Location:add_delete.php?mensaje=".$error."");
		}else{
			$combo_issue_type = $_POST['combo_issue_type'];
			$new_category = $_POST['new_category'];
			//COMPROBAMOS QUE NO EXISTA
			$existe = mysqli_query($connection, "SELECT ID_COMBO FROM tipos_combos WHERE DESCRIPCION = '".$new_category."' AND ID_PADRE = '".$combo_issue_type."'"); //Sentencia para buscarlo en la base de datos
			$numrows=mysqli_num_rows($existe);
			if ($numrows == 0){
				$max_id = mysqli_query($connection, "SELECT MAX(id_combo) FROM tipos_combos"); //Sentencia para buscarlo en la base de datos
				$row_max_id=mysqli_fetch_row($max_id);
				$arreglo = (int)$row_max_id[0]+1;
				$arreglo = str_pad($arreglo, 4, "0", STR_PAD_LEFT); //YA TENEMOS EL SIGUIENTE ID A ASIGNAR
				mysqli_query($connection, "INSERT INTO tipos_combos (ID_COMBO, ID_PADRE, DESCRIPCION, TIPO, ORDEN) VALUES ('".$arreglo."', '".$combo_issue_type."', '".$new_category."', 'CATEGORIA', '0');");
				$error = 15;
				Header("Location:add_delete.php?mensaje=".$error.""); 
			}else{
				$error = 10;
				Header("Location:add_delete.php?mensaje=".$error.""); 
			}
			
		}
	}else if ($_POST['enviar_peticion'] == 3){
	if ($_POST['combo_category2_type'] == -1){
			$error = 11;
			Header("Location:add_delete.php?mensaje=".$error.""); 
		}else if ($_POST['new_product'] == ""){
			$error = 12;
			Header("Location:add_delete.php?mensaje=".$error.""); 
		}else{
			$combo_category2_type = $_POST['combo_category2_type'];
			$new_product = $_POST['new_product'];
			//COMPROBAMOS QUE NO EXISTA
			$existe = mysqli_query($connection, "SELECT ID_COMBO FROM tipos_combos WHERE DESCRIPCION = '".$new_product."'"); //Sentencia para buscarlo en la base de datos
			$numrows=mysqli_num_rows($existe);
			if ($numrows == 0){
				$max_id = mysqli_query($connection, "SELECT MAX(id_combo) FROM tipos_combos"); //Sentencia para buscarlo en la base de datos
				$row_max_id=mysqli_fetch_row($max_id);
				$arreglo = (int)$row_max_id[0]+1;
				$arreglo = str_pad($arreglo, 4, "0", STR_PAD_LEFT); //YA TENEMOS EL SIGUIENTE ID A ASIGNAR
				mysqli_query($connection, "INSERT INTO tipos_combos (ID_COMBO, ID_PADRE, DESCRIPCION, TIPO, ORDEN) VALUES ('".$arreglo."', '".$combo_category2_type."', '".$new_product."', 'PRODUCTO', '0');");
				$error = 14;
				Header("Location:add_delete.php?mensaje=".$error.""); 
			}else{
				$error = 13;
				Header("Location:add_delete.php?mensaje=".$error."");
			}
		}
		
	
	}
	
}else{
	$ahora = getdate(); //Obtiene un array con los datos de la fecha y hora actual
	$fecha = $ahora["year"]."-".$ahora["mon"]."-".$ahora["mday"]." ".$ahora["hours"].":".$ahora["minutes"].":".$ahora["seconds"]; //Obtiene el formato adecuado de fecha hora para insertar en la BBDD
	$IP = $_SERVER['REMOTE_ADDR'];
	$pagina = $_SERVER['PHP_SELF'];
	mysqli_query($connection, "INSERT INTO intrusos (IP,tipo,descripcion,fecha) values ('".$IP."',4,'".$pagina."','".$fecha."') ");
	Header("Location: index.php?intrusion=1");

}









?>
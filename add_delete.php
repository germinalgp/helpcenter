<?php
	/**
	* PHP crear o borrar categorias/productos.
	* Opcion accesible para ciertos usuarios
	* @author Germinal GARRIDO PUYANA
	* @version v1.1(0218)
	*/
	
	require('conexion.php'); //Incluimos el conexion.php que contiene los datos de la conexion a la base de datos
	include('menu.php');

	if ($_SESSION['level'] == 1){
		if (strpos($_SERVER['PHP_SELF'],'controller') != false){
				$ruta = '../';
		}
		
		
			echo '<html>
			  <head>
					<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
					<title>HELPCENTER</title>
					<link href="'.$ruta.'styles.css" rel="stylesheet" type="text/css" />
					<script type="text/javascript" src="'.$ruta.'tinybox.js"></script>
					<script type="text/javascript">
						function Valida (formulario, opc, confirmar){
							if (confirmar == 1){
								if (confirm(String.fromCharCode(191)+\'Esta seguro de eliminar la categor\xeda o producto?. Una vez borrado no se podr\xe1 volver atr\xe1s. \n(NOTA: en caso de Categor\xeda, borrar\xe1 todos los productos asociados)\')){
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


			if ($mensaje != ''){
				echo '<body style="font: 13px/20px sans-serif;" link="#0000ff" vlink="#0000ff" onload="TINY.box.show({url:\''.$ruta.'message.php?mensaje='.$mensaje.'\',width:320,height:100})">';
			}else{
				echo '<body style="font: 13px/20px sans-serif;" link="#0000ff" vlink="#0000ff">';
			}
			echo '<p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p>';
		
			menu_int(0, 1, 0, 0, 0, 0);
			
			
			if ( isset ( $_POST['combo_category_type'] ) ){
				$combo_category_type = $_POST['combo_category_type'];
			}
			
			
			
			//INICIO PRIMER FORMULARIO ---- DELETE CATEGORIA PRODUCTO
			echo '<form id="searchformPeticion" name="deletecat_pro" method="post" action="'.$ruta.'controller/c_add_delete.php">
						<fieldset>
						<legend>Borrar Categoria / Producto</legend>
						<input type="hidden" name="enviar_peticion" value="0" size="1"></input>';

					if ($combo_category_type != ""){
					echo '<label id="label2" for="combo_category_type">
							<select name="combo_category_type" tabindex="5" id="combo_category_type" onchange="this.form.submit();return false;">';
							$tabla_categorias = mysqli_query($connection, "SELECT id_combo, descripcion FROM tipos_combos WHERE tipo = 'CATEGORIA' ORDER BY descripcion ASC"); //Sentencia para buscarlo en la base de datos
							echo '<option value = "-1" selected="selected">Elija una opci&#243;n:</option>';
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
							<select name="combo_category_type" tabindex="5" id="combo_category_type" onchange="this.form.submit();return false;">';
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
				echo '<form id="searchformPeticion" name= "add_category" method="post" action="'.$ruta.'controller/c_add_delete.php">
						<fieldset>
						<legend>A&ntilde;adir categor&iacute;a</legend>
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
							<input class = "blanco" type="text" name="new_category" tabindex="3" id="new_category"></input>Nueva categor&iacute;a: <b class="error">(*)</b>
						 </label>
								
						
						</fieldset></form>';
					

					echo '<form id="formSubmit" onClick="Valida(add_category, 2,0);" action="">
					<label id="label4" for="Submit">
						<input name="submit" type="button" id="submit" tabindex="12" value="Enviar"></input>
					</label></form>
					<hr width="550" align="left" />';
					///////////////////////FIN SEGUNDO FORMULARIO ---- ADD CATEGORIA
					
					///////////////////////INICIO TERCER FORMULARIO ---- ADD PRODUCTO
				echo '<form id="searchformPeticion" name= "add_product" method="post" action="'.$ruta.'controller/c_add_delete.php">
						<fieldset>
						<legend>A&ntilde;adir producto</legend>
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

		
		
	}else{
		$ahora = getdate(); //Obtiene un array con los datos de la fecha y hora actual
		$fecha = $ahora["year"]."-".$ahora["mon"]."-".$ahora["mday"]." ".$ahora["hours"].":".$ahora["minutes"].":".$ahora["seconds"]; //Obtiene el formato adecuado de fecha hora para insertar en la BBDD
		$IP = $_SERVER['REMOTE_ADDR'];
		$pagina = $_SERVER['PHP_SELF'];
		mysqli_query($connection, "INSERT INTO intrusos (IP,tipo,descripcion,fecha) values ('".$IP."',4,'".$pagina."','".$fecha."') ");
		Header("Location: index.php?intrusion=1");

	}
?>
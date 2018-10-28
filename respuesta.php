<?php

	/**
	* PHP para visualizar la respuesta a una incidencia en caso de nivel 9 y para gestionar la misma en caso de nivel administracion
	* @author Germinal GARRIDO PUYANA
	*/
	require('conexion.php'); //Incluimos el conexion.php que contiene los datos de la conexion a la base de datos
	include ('menu.php');
	
	

	/**
	* Funcion para obtener una lista de ficheros de una determinada ruta
	* @author Germinal GARRIDO PUYANA
	* @param string $ruta La ruta donde obtener los ficheros
	* @param boolean $recursivo TRUE Si queremos que busque recursivamente en subdirectorios, FALSE en caso contrario
	* @return array La lista de ficheros
	*/
	function getFileList($ruta, $recursivo=false){ 
	   // abrir un directorio y listarlo recursivo 
	   $lista = array();
	   if (is_dir($ruta)) { 
		  if ($dh = opendir($ruta)) { 
			 while (($file = readdir($dh)) !== false) { 
				if (!is_dir($ruta . $file)) {
				   $fileinfo = array();
				   $fileinfo["name"] = $file;
				   $fileinfo["size"] = filesize($ruta . $file);
				   $fileinfo["type"] = filetype($ruta . $file);
				   $lista[] = $fileinfo;
				} else if ($recursivo && $file!="." && $file!=".."){
				   $lista2 = listar_directorios_ruta($ruta . $file . "/"); 
				   for ($i = 0; $i < count($lista2); $i++)
					  $lista[] = $lista2[$i];
					  unset($lista2);
				} 
			 } 
		  closedir($dh); 
		  } 
	   }

	   return $lista;   
	} 


	if ($_SESSION['level'] == 1 || $_SESSION['level'] == 2 || $_SESSION['level'] == 3 || $_SESSION['level'] == 4 || $_SESSION['level'] == 9) {

		echo '<html>
			<head>
					<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
					<title>INCIDENCIAS</title>
					<script type="text/javascript" language="javascript" src="js/jquery/jquery.min.js"></script>
					<script type="text/javascript" language="javascript" src="js/jquery/plugins/ax-multiuploader/ajaxupload.js"></script>
					<script src="js/ckeditor/ckeditor.js"></script>
					<script>
						function agregar(formulario){
						  str = \'<p style="text-align:justify">\' + formulario.coment_predefinido.value + \'</p>\';
							CKEDITOR.instances[\'ck_comentario\'].insertHtml(str);
						}
					</script>
					<link href="styles.css" rel="stylesheet" type="text/css" />
					<link href="../styles.css" rel="stylesheet" type="text/css" /> 	
					<link rel="stylesheet" href="style/ax-multiuploader/style.css" type="text/css" media="all" />
					
			 </head>
			 <body style="font: 13px/20px sans-serif;" link="#0000ff" vlink="#0000ff">
			  <p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p>';
			  
			  //Si se ha logueado, mostramos el nick y la opcion de desloguearse
			  //Este sera el menu que saldria a la gente que esta logueada, se puede modificar y aadir cosas
		
		if (strpos($_SERVER['PHP_SELF'],'controller') != false){
			$ruta = '../';
		}
		
		$mensaje = "";
		if ( isset ( $_GET['mensaje'] ) ){
			$mensaje = $_GET['mensaje'];
		}
		$colorFila="filaBlanca";
		
		if ($_SESSION['level'] == 9){   
			
			menu_ext(0,0,0);

			$sql = "SELECT p.ISSUE_TYPE, c.DESCRIPCION, p.COMPETENCIA, e.STATE, p.USER_OPEN, p.TELEPHONE, p.EMAIL, p.FECHA_COMPRA, p.ID_ANTERIOR, p.N_SERIE FROM peticiones p, tipo_estados e, tipos_combos c WHERE USER_OPEN = ".$_SESSION['usuario']." AND ID = ".$_POST['ID']." AND p.ISSUE_TYPE = c.ID_COMBO AND p.STATE = e.ID_STATE";
			
			$resultado=mysqli_query($connection, $sql);
			$numrows=@mysqli_num_rows($resultado);
			if ($numrows == 0){ //SI NO TENEMOS RESULTADOS
				echo 'NO HAY RESULTADOS';
			}else{
				$row=mysqli_fetch_array($resultado);
			
				echo '&nbsp;ID: <b>'.$_POST['ID'].'</b><br/>
					  &nbsp;Tipo de incidencia: <b>'.$row[1].'</b><br/>
					  &nbsp;Competencia: <b>'.$row[2].'</b><br/>';
					  if ($row[3]=='CERRADA'){
						echo '&nbsp;Estado: <span style="background-color: #FF0000"><b>'.$row[3].'</b></span><br/>';
					  }else if ($row[3]=='ABIERTA'){
						echo '&nbsp;Estado: <span style="background-color: #00FF00"><b>'.$row[3].'</b></span><br/>';
					  }else if ($row[3]=='EN TRAMITE'){
						echo '&nbsp;Estado: <span style="background-color: #FFFF00"><b>'.$row[3].'</b></span><br/>';
					  }
					echo '<table width="600">
						<tr>
							<td>Tel&eacute;fono de Contacto:</td>
							<td>
							<input type="text" name="telefono" value="'.$row[5].'" disabled="disabled"></input>
							</td>
							<td width="30"></td>
							<td>E-mail de Contacto:</td>
							<td>
							<input type="text" name="email" value="'.$row[6].'" disabled="disabled"></input>
							</td>
						</tr>';
				if (($row[0] == "0001") || ($row[0] == "0002")){
					//OBTENEMOS CATEGORIA Y PRODUCTOS
					//CATEGORIA
					//----------
					$sql_cat_pro = "SELECT c.DESCRIPCION, d.DESCRIPCION FROM peticiones p, tipos_combos c, tipos_combos d WHERE p.ID = ".$_POST['ID']."  AND p.CATEGORY_TYPE = c.ID_COMBO AND p.PRODUCT_TYPE = d.ID_COMBO";
					$res_cat_pro=mysqli_query($connection, $sql_cat_pro);
					$row_cat_pro=mysqli_fetch_array($res_cat_pro);
					echo '<tr>
							<td>CATEGORIA:</td>
							<td>
								<input type="text" name="categoria" value="'.$row_cat_pro[0].'" disabled="disabled"></input>
							</td>
							<td></td>
							<td>PRODUCTO:</td>
							<td>
								<input type="text" name="producto" value="'.$row_cat_pro[1].'" disabled="disabled"></input>
							</td>
						</tr>
						<tr>
							<td>NUM. SERIE:</td>
							<td>
								<input type="text" name="n_serie" value="'.$row[9].'" disabled="disabled"></input>
							</td>
							<td></td>
							<td>FECHA DE COMPRA:</td>
							<td>
								<input type="text" name="fecha_compra" value="'.$row[7].'" disabled="disabled"></input>
							</td>
						</tr>
						</table>';		
				}else if (($row[0] == "0004")||($row[0] == "0005")||($row[0] == "0006")){
					echo '<tr>
							<td>ID anterior:</td>
							<td>
								<input type="text" name="id_anterior" value="'.$row[8].'" disabled="disabled"></input>
							</td>
							<td></td>
							<td></td>
							<td></td>
						</tr>
						</table>';
				}else if ($row[0] == "0003"){
					echo '</table>';
				}
				echo '&nbsp;Comentarios:<br>';
				$sql="SELECT * FROM comentarios WHERE ID_ISSUE = ".$_POST['ID']."";
				$resultado=mysqli_query($connection, $sql);
				while ($datos=mysqli_fetch_array($resultado)){
					//if ($datos["TIPO_COMENTARIO"]==0){
					
					echo '<form id="formLarge">
							<fieldset>';
							if ($datos["AUTHOR"]==$_SESSION['usuario']){
								echo '<legend><b>['.$datos["DATE"].']</b> '.$datos["AUTHOR"].' ha escrito: </legend>'.$datos["COMMENTS"];
							}else{
								echo '<legend><b>['.$datos["DATE"].']</b> Administrador ha escrito: </legend>'.$datos["COMMENTS"];
							}
						echo '</fieldset></form>';
					//}
				}
			}//FIN DEL ELSE POR SI NO HAY RESULTADO
		}else if ($_SESSION['level'] == 4 || $_SESSION['level'] == 3 || $_SESSION['level'] == 2 || $_SESSION['level'] == 1){   //NIVEL 3: ES EL NIVEL PARA LOS EMPLEADO DE SITEL QUE SE ENCARGAN DE RESOLVER LAS PETICIONES (SERVICIO DE ADMINISTRACION)
			
			menu_int(0,0,0,0,0,0);		
			
			$sql = "SELECT p.ISSUE_TYPE, c.DESCRIPCION, p.COMPETENCIA, e.STATE, p.USER_OPEN, p.TELEPHONE, p.EMAIL, p.FECHA_COMPRA, p.ID_ANTERIOR, p.N_SERIE, p.DATE, p.TRACING FROM peticiones p, tipo_estados e, tipos_combos c WHERE ID = ".$_POST['ID']." AND p.ISSUE_TYPE = c.ID_COMBO AND p.STATE = e.ID_STATE";
			
			if ($_SESSION['block'] > 0){
				mysqli_query($connection, "UPDATE peticiones SET BLOCK = 0 WHERE ID = ".$_SESSION['block'].""); //DESBLOQUEAMOS
				$_SESSION['block'] = 0;
			}

			$_SESSION['block']=$_POST['ID'];
			$resultado=mysqli_query($connection, $sql);
			$numrows=@mysqli_num_rows($resultado);
			if ($numrows == 0){ //SI NO TENEMOS RESULTADOS
				echo 'NO HAY RESULTADOS';
			}else{
				$row=mysqli_fetch_array($resultado);
				
				if ($row[3]!='CERRADA'){//SI ESTA ABIERTA O EN TRAMITE
					//BLOQUEAMOS
					mysqli_query($connection, "UPDATE peticiones SET BLOCK = 1 WHERE ID = ".$_POST['ID'].""); //BLOQUEAMOS

				echo '<form method="post" action="'.$ruta.'controller/c_cambiar_competencia.php">
					<input type="hidden" name="ID" value="'.$_POST['ID'].'" size="1"></input>&nbsp;Competencia: <b>'.$row[2].'</b>
					 <select class="competencia" name="competencia" id="competencia">';
						$tabla_competencias = mysqli_query ($connection, "SELECT DISTINCT TIPO FROM tipos_combos WHERE TIPO <> 'CATEGORIA' AND TIPO <> 'PRODUCTO'");
						while ($row_tabla_competencias = mysqli_fetch_array($tabla_competencias))
						{
							if ($row[2]!=$row_tabla_competencias[0]){
								echo '<option value="'.$row_tabla_competencias[0].'">'.$row_tabla_competencias[0].'</option>';
							}
						}
						echo '</select>
						<input class="submit" name="submit" type="submit" id="submit" tabindex="7" value="Cambiar"></input>
					</form>';			
				}else{
					echo ' &nbsp;Competencia: <b>'.$row[2].'</b><br/>';
				}
				echo '&nbsp;ID: <b>'.$_POST['ID'].'</b><br/>
					&nbsp;Tipo de incidencia: <b>'.$row[1].'</b><br/>';
					
	  
				if (($row[3]=='CERRADA')&&($_SESSION['level'] == 3 || $_SESSION['level'] == 2 || $_SESSION['level'] == 1)){ //CIERTOS NIVELES PUEDEN MODIFICAR EL ESTADO
					echo '<form method="post" action="'.$ruta.'controller/c_cambiar_estado.php">
					<input type="hidden" name="ID" value="'.$_POST['ID'].'" size="1"></input>&nbsp;Estado: <span style="background-color: #FF0000"><b>CERRADA</b></span>
					<select class="estado" name="estado" id="estado">
						<option class="estado_verde" value="0">ABIERTA</option>
						<option class="estado_amarillo" value="1">EN TRAMITE</option>
					</select>
					<input class="submit" name="submit" type="submit" id="submit" tabindex="7" value="Cambiar"></input>
					</form>';
				}else{
					switch ($row[3]){
						case "CERRADA": echo '&nbsp;Estado: <span style="background-color: #FF0000"><b>'.$row[3].'</b></span><br/>';
										break;
						case "ABIERTA": echo '&nbsp;Estado: <span style="background-color: #00FF00"><b>'.$row[3].'</b></span><br/>';
										break;
						case "EN TRAMITE": echo '&nbsp;Estado: <span style="background-color: #FFFF00"><b>'.$row[3].'</b></span><br/>';
										break;
					}
						
				}
			 
				echo '&nbsp;Usuario: <b>'.$row[4].'</b>
					<table width="600">
						<tr>
							<td>Tel&eacute;fono de Contacto:</td>
							<td>
							<input type="text" name="telefono" value="'.$row[5].'" disabled="disabled"></input>
							</td>
							<td width="30"></td>
							<td>E-mail de Contacto:</td>
							<td>
							<input type="text" name="email" value="'.$row[6].'" disabled="disabled"></input>
							</td>
						</tr>';
				if (($row[0] == "0001") || ($row[0] == "0002")){
					//OBTENEMOS CATEGORIA Y PRODUCTOS
					//CATEGORIA
					//----------
					$sql_cat_pro = "SELECT c.DESCRIPCION, d.DESCRIPCION FROM peticiones p, tipos_combos c, tipos_combos d WHERE p.ID = ".$_POST['ID']."  AND p.CATEGORY_TYPE = c.ID_COMBO AND p.PRODUCT_TYPE = d.ID_COMBO";
					$res_cat_pro=mysqli_query($connection, $sql_cat_pro);
					$row_cat_pro=mysqli_fetch_array($res_cat_pro);
					echo '<tr>
							<td>CATEGORIA:</td>
							<td>
								<input type="text" name="categoria" value="'.$row_cat_pro[0].'" disabled="disabled"></input>
							</td>
							<td></td>
							<td>PRODUCTO:</td>
							<td>
								<input type="text" name="producto" value="'.$row_cat_pro[1].'" disabled="disabled"></input>
							</td>
						</tr>
						<tr>
							<td>NUM. SERIE:</td>
							<td>
								<input type="text" name="n_serie" value="'.$row[9].'" disabled="disabled"></input>
							</td>
							<td></td>
							<td>FECHA DE COMPRA:</td>
							<td>
								<input type="text" name="fecha_compra" value="'.$row[7].'" disabled="disabled"></input>
							</td>
						</tr>
						</table>';		
				}else if (($row[0] == "0004")||($row[0] == "0005")||($row[0] == "0006")){
					echo '<tr>
							<td>ID anterior:</td>
							<td>
								<input type="text" name="id_anterior" value="'.$row[8].'" disabled="disabled"></input>
							</td>
							<form method="post" action="'.$ruta.'controller/c_respuesta.php">
							<td>
									<input type="hidden" name="buscarXid" value="1" size="1"></input>
									<input type="hidden" name="ID" value="'.$row[8].'"></input>
									<input align="center" type="image" src="images/buscar.jpg" width="20" title="VER ID"></input>
								
							</td>
							</form>
							<td></td>
							<td></td>
						</tr>
						</table>';
				}else if ($row[0] == "0003"){
					echo '</table>';
				}
				
				$fecha_peticion = date_parse($row[10]);
				
				$uploadPath	= "smb/incidencias/".$fecha_peticion["year"]."/".str_pad($fecha_peticion["month"], 2, "0", STR_PAD_LEFT)."/".str_pad($fecha_peticion["day"], 2, "0", STR_PAD_LEFT)."/".$_POST['ID']."/";
				
				$ficheros = getFileList($uploadPath);
				
				if (count($ficheros)>0) {
				   echo '<form id="formLarge"><fieldset>
						   <legend> Ficheros adjuntos</legend>';
						   for ($i = 0; $i < count($ficheros); $i++) {
							  echo "<a href='".$ruta."controller/c_download.php?ID=".$_POST['ID']."&download_file=".urlencode($ficheros[$i]["name"])."' target='_new'>".$ficheros[$i]["name"]."</a>&nbsp;(".number_format($ficheros[$i]["size"]/(1024*1024), 2, '.', ' ')."&nbsp;Mb)<br/>\n";
						   }

						
				   echo '</fieldset></form>';
				}			
				
				
				echo '&nbsp;Comentarios:<br/>';
				$sql="SELECT * FROM comentarios WHERE ID_ISSUE = ".$_POST['ID']."";
				$resultado2=mysqli_query($connection, $sql);
				while ($datos2=mysqli_fetch_array($resultado2)){
					//if ($datos2['TIPO_COMENTARIO']==0){
						echo '<form id="formLarge">
								<fieldset>
								<legend><b>['.$datos2["DATE"].']</b> '.$datos2["AUTHOR"].' ha escrito: </legend>'.$datos2["COMMENTS"];
						echo '</fieldset></form>';
					//}
				}
				
				
				
				if (($row[3]!='CERRADA') && ( !isset ( $_POST['buscarXid']) || $_POST['buscarXid'] != 1)){ //SI NO ESTA CERRADA Y NO HEMOS BUSCADO POR ID
					
					
						echo '<form id="formLarge" method="post" action="'.$ruta.'controller/c_respuesta.php">
								<fieldset>
								<legend>Nuevo comentario</legend>
								<input type="hidden" name="enviar_peticion" value="1" size="1"></input>
								<input type="hidden" name="ID" value="'.$_POST['ID'].'" size="1"></input>
								<textarea name="ck_comentario" id="ck_comentario" rows="10" cols="80"></textarea>
									  <script type="text/javascript" >
								CKEDITOR.replace( \'ck_comentario\' );
								</script>
								<label id="label2" for="estado">
									<select name="ESTADO" tabindex="12" id="estado">';
										if ($row[3]=='ABIERTA'){
										 echo '<option value="1">EN TRAMITE</option>
											<option value="2">CERRADA</option>';
										}else if ($row[3]=='EN TRAMITE'){
										 echo '<option value="0">ABIERTA</option>
											<option value="1">EN TRAMITE</option>
											<option value="2">CERRADA</option>';
										}
								echo '</select> ESTADO:
								</label>
								<label id="label2" for="Submit">
									<input name="Submit" type="submit" id="submit" tabindex="13" value="Enviar"></input>
								</label>
							</fieldset>	
						</form>'; 
				}
				
				if ($_SESSION['level'] == 4 || $_SESSION['level'] == 3 || $_SESSION['level'] == 2 || $_SESSION['level'] == 1){ //VER HISTORIAL
				echo '
				<table>
				
				<form name="form_seguimiento" action="'.$ruta.'controller/c_tracing.php" method="post">
				<input type="hidden" name="ID" value="'.$_POST['ID'].'" size="1">
				<tr>
					<td colspan="2"></td>
					<td style="font-size:10px; background-color:#E0E0E0; text-align:center">Especial seguimiento</td>
				</tr>
				<tr>
					<td colspan="2"></td>
					<td>';
					if ($row[11]==0){//DESACTIVADO
						echo 'Activado<input name="tracing" type="radio" id="tracing" value="1" onchange="this.form.submit()"/>
									Desactivado<input name="tracing" type="radio" id="tracing" value="0" checked onchange="this.form.submit()"/>';
					}else{
						echo 'Activado<input name="tracing" type="radio" id="tracing" value="1" checked onchange="this.form.submit()"/>
									Desactivado<input name="tracing" type="radio" id="tracing" value="0" onchange="this.form.submit()"/>';
					}
		echo '</td>
				</tr>
				</form>
				
				<tr>
				<td colspan="3"></td>
				</tr>
				<tr>
					<td style="font-size:10px; background-color:#E0E0E0; text-align:center">Mostrar Historial</td>
					<td width="70"></td>
					<td style="font-size:10px; background-color:#E0E0E0; text-align:center">Agregar Comentario Predefinido</td>
				</tr>
				<tr>
					<td>';
					if ($_SESSION['level'] == 4){
						echo '<form>
							<input class="submit" name="submit" type="submit" id="submit" value="No disponible" disabled="disabled"/>
						</form>';
					}else{
						
						echo '<form method="post" action="'.$ruta.'ver_historial.php" target="_blank">
						<input type="hidden" name="ID" value="'.$_POST['ID'].'" size="1">
						<input class="submit" name="submit" type="submit" id="submit" value="Ver..." />
						</form>';
					}
					echo '</td>
					<td width="65"></td>
					<td>
				<form id="coment_predefinidos">
						<select type="text" name="coment_predefinido" id="coment_predefinido">';
						$tabla_coment_predefinidos = mysqli_query ($connection, "SELECT id, resumen, comentario FROM coment_predefinidos ORDER BY id ASC");
						while ($row_tabla_coment_predefinidos=mysqli_fetch_array($tabla_coment_predefinidos)) 
						{
							echo '<option title = "'.$row_tabla_coment_predefinidos[2].'" value="'.$row_tabla_coment_predefinidos[2].'">'.$row_tabla_coment_predefinidos[1].'</option>';
						}
						echo '</select>
						<input class="submit" type="button" value="Agregar..." onClick="agregar(coment_predefinidos);"/>
				</form>
				</td>
				</table>';
				
				
				echo '<form method="post" id="openform" action="'.$ruta.'controller/c_respuesta.php">
				 <input type="hidden" name="ID" id="openid" value="'.$_POST['ID'].'">		
				</form>
							<div id="demo1" style="width:600px"></div>
							*Pueden ser cargardos simult&aacute;neamente varios ficheros (Tama&ntilde;o m&aacute;ximo por fichero 8Mb)
				<script type="text/javascript">
				$(\'#demo1\').ajaxupload({
					url:\'upload.php?ID='.$_POST['ID'].'\',
					finish: function(arrFiles){ $("#openform").submit();},
					maxFileSize:\'8M\'
				});

				</script>';			
				
			}
				
				
			}//FIN DEL ELSE POR SI EL ID CONSULTADO NO EXISTE
			
		}
			
		
		echo '</body></html>';
	
	}else{
		$ahora = getdate(); //Obtiene un array con los datos de la fecha y hora actual
		$fecha = $ahora["year"]."-".$ahora["mon"]."-".$ahora["mday"]." ".$ahora["hours"].":".$ahora["minutes"].":".$ahora["seconds"]; //Obtiene el formato adecuado de fecha hora para insertar en la BBDD
		$IP = $_SERVER['REMOTE_ADDR'];
		$pagina = $_SERVER['PHP_SELF'];
		mysqli_query($connection, "INSERT INTO intrusos (IP,tipo,descripcion,fecha) values ('".$IP."',4,'".$pagina."','".$fecha."') ");
		Header("Location: index.php?intrusion=1");
	}
?>

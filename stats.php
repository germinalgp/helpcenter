<?php
	/**
	* PHP para visualizar las estadisticas de incidencias de forma global. Esta pagina tiene varias opciones para visualizar estadisticas
	* mas concretas
	* Solo accesible a determinados niveles
	* @author Germinal GARRIDO PUYANA
	*/
	require('conexion.php'); //Incluimos el conexion.php que contiene los datos de la conexion a la base de datos
	include('menu.php');


	require_once('maxChart.class.php');

	if ($_SESSION['level'] == 1 || $_SESSION['level'] == 2) {
		if ($_SESSION['block'] > 0){
				mysql_query("UPDATE peticiones SET BLOCK = 0 WHERE ID = ".$_SESSION['block'].""); //DESBLOQUEAMOS
				$_SESSION['block'] = 0;
		}
		echo '<html>
			  <head>
					<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
					<title>ESTADISTICAS</title>
					<link href="style/style.css" rel="stylesheet" type="text/css" />
					<link href="styles.css" rel="stylesheet" type="text/css" />
				
				<script type="text/javascript">
				<!--
				function nueva(pagina)
				{
					open(pagina, \'\', \'width=900,height=850,scrollbars=no,toolbar=no\')
				}
				//-->
				</script>	
						
			</head>
			
			
			  <body style="font: 13px/20px sans-serif;" link="#0000ff" vlink="#0000ff">
			
				<p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p>';
			 
			menu_int(0, 0, 0, 0, 1, 0);
			
			
			
			$ahora = getdate();
			
			$anio = "";
		
			if ( isset ( $_POST['anio'] ) ){
				$anio = $_POST['anio'];
			}	
			
			
			
			if ($anio==''){
				$anio=$ahora["year"];
			}
			/////////////////////////////////////////CONSULTAS DE ESTADISTICAS DE LA PAGINA PRINCIPAL//////////////////////////////////////////
						
			//INCIDENCIAS TOTALES POR MESES
			$tEneroIncidencias = "SELECT COUNT(*) FROM peticiones p WHERE p.DATE >= '".$anio."-01-01 00:00:00' AND p.DATE < '".$anio."-02-01 00:00:00'";
			$tFebreroIncidencias = "SELECT COUNT(*) FROM peticiones p WHERE p.DATE >= '".$anio."-02-01 00:00:00' AND p.DATE < '".$anio."-03-01 00:00:00'";
			$tMarzoIncidencias = "SELECT COUNT(*) FROM peticiones p WHERE p.DATE >= '".$anio."-03-01 00:00:00' AND p.DATE < '".$anio."-04-01 00:00:00'";
			$tAbrilIncidencias = "SELECT COUNT(*) FROM peticiones p WHERE p.DATE >= '".$anio."-04-01 00:00:00' AND p.DATE < '".$anio."-05-01 00:00:00'";
			$tMayoIncidencias = "SELECT COUNT(*) FROM peticiones p WHERE p.DATE >= '".$anio."-05-01 00:00:00' AND p.DATE < '".$anio."-06-01 00:00:00'";
			$tJunioIncidencias = "SELECT COUNT(*) FROM peticiones p WHERE p.DATE >= '".$anio."-06-01 00:00:00' AND p.DATE < '".$anio."-07-01 00:00:00'";
			$tJulioIncidencias = "SELECT COUNT(*) FROM peticiones p WHERE p.DATE >= '".$anio."-07-01 00:00:00' AND p.DATE < '".$anio."-08-01 00:00:00'";
			$tAgostoIncidencias = "SELECT COUNT(*) FROM peticiones p WHERE p.DATE >= '".$anio."-08-01 00:00:00' AND p.DATE < '".$anio."-09-01 00:00:00'";
			$tSeptiembreIncidencias = "SELECT COUNT(*) FROM peticiones p WHERE p.DATE >= '".$anio."-09-01 00:00:00' AND p.DATE < '".$anio."-10-01 00:00:00'";
			$tOctubreIncidencias = "SELECT COUNT(*) FROM peticiones p WHERE p.DATE >= '".$anio."-10-01 00:00:00' AND p.DATE < '".$anio."-11-01 00:00:00'";
			$tNoviembreIncidencias = "SELECT COUNT(*) FROM peticiones p WHERE p.DATE >= '".$anio."-11-01 00:00:00' AND p.DATE < '".$anio."-12-01 00:00:00'";
			$tDiciembreIncidencias = "SELECT COUNT(*) FROM peticiones p WHERE p.DATE >= '".$anio."-12-01 00:00:00' AND p.DATE <= '".$anio."-12-31 23:59:59'";
			
			//TOTAL DE INCIDENCIAS EN UN AÑO DETERMINADO
			
			//////////////////////RESULTADOS///////////////////////////
			$tIncidencias = mysqli_query ($connection, "SELECT COUNT(*) FROM peticiones");
			
			$tIncidenciasAnio = mysqli_query ($connection, "SELECT COUNT(*) FROM peticiones WHERE DATE >= '".$anio."-01-01 00:00:00' AND DATE <= '".$anio."-12-31 23:59:59'");
			
			$res_tEneroIncidencias = mysqli_query ($connection, $tEneroIncidencias);
			$res_tFebreroIncidencias = mysqli_query ($connection, $tFebreroIncidencias);
			$res_tMarzoIncidencias = mysqli_query ($connection, $tMarzoIncidencias);
			$res_tAbrilIncidencias = mysqli_query ($connection, $tAbrilIncidencias);
			$res_tMayoIncidencias = mysqli_query ($connection, $tMayoIncidencias);
			$res_tJunioIncidencias = mysqli_query ($connection, $tJunioIncidencias);
			$res_tJulioIncidencias = mysqli_query ($connection, $tJulioIncidencias);
			$res_tAgostoIncidencias = mysqli_query ($connection, $tAgostoIncidencias);
			$res_tSeptiembreIncidencias = mysqli_query ($connection, $tSeptiembreIncidencias);
			$res_tOctubreIncidencias = mysqli_query ($connection, $tOctubreIncidencias);
			$res_tNoviembreIncidencias = mysqli_query ($connection, $tNoviembreIncidencias);
			$res_tDiciembreIncidencias = mysqli_query ($connection, $tDiciembreIncidencias);
			
			
			echo '<p>&nbsp;</p>';
			echo '<div id="main">';
			
						//ENERO
						$datos_tEneroIncidencias = mysqli_fetch_array($res_tEneroIncidencias);
						if ($datos_tEneroIncidencias[0] == 0){
							$data0['ENE'] = 0;
						}else{
							$data0['ENE'] = $datos_tEneroIncidencias[0];
						}
						
						//FEBRERO
						$datos_tFebreroIncidencias = mysqli_fetch_array($res_tFebreroIncidencias);
						if ($datos_tFebreroIncidencias[0] == 0){
							$data0['FEB'] = 0;
						}else{
							$data0['FEB'] = $datos_tFebreroIncidencias[0];
						}
						
						//MARZO
						$datos_tMarzoIncidencias = mysqli_fetch_array($res_tMarzoIncidencias);
						if ($datos_tMarzoIncidencias[0] == 0){
							$data0['MAR'] = 0;
						}else{
							$data0['MAR'] = $datos_tMarzoIncidencias[0];
						}
						
						//ABRIL
						$datos_tAbrilIncidencias = mysqli_fetch_array($res_tAbrilIncidencias);
						if ($datos_tAbrilIncidencias[0] == 0){
							$data0['ABR'] = 0;
						}else{
							$data0['ABR'] = $datos_tAbrilIncidencias[0];
						}
						
						//MAYO
						$datos_tMayoIncidencias = mysqli_fetch_array($res_tMayoIncidencias);
						if ($datos_tMayoIncidencias[0] == 0){
							$data0['MAY'] = 0;
						}else{
							$data0['MAY'] = $datos_tMayoIncidencias[0];
						}
						
						//JUNIO
						$datos_tJunioIncidencias = mysqli_fetch_array($res_tJunioIncidencias);
						if ($datos_tJunioIncidencias[0] == 0){
							$data0['JUN'] = 0;
						}else{
							$data0['JUN'] = $datos_tJunioIncidencias[0];
						}
						
						//JULIO
						$datos_tJulioIncidencias = mysqli_fetch_array($res_tJulioIncidencias);
						if ($datos_tJulioIncidencias[0] == 0){
							$data0['JUL'] = 0;
						}else{
							$data0['JUL'] = $datos_tJulioIncidencias[0];
						}
						
						//AGOSTO
						$datos_tAgostoIncidencias = mysqli_fetch_array($res_tAgostoIncidencias);
						if ($datos_tAgostoIncidencias[0] == 0){
							$data0['AGO'] = 0;
						}else{
							$data0['AGO'] = $datos_tAgostoIncidencias[0];
						}
						
						//ENERO
						$datos_tSeptiembreIncidencias = mysqli_fetch_array($res_tSeptiembreIncidencias);
						if ($datos_tSeptiembreIncidencias[0] == 0){
							$data0['SEP'] = 0;
						}else{
							$data0['SEP'] = $datos_tSeptiembreIncidencias[0];
						}
						
						//OCTUBRE
						$datos_tOctubreIncidencias = mysqli_fetch_array($res_tOctubreIncidencias);
						if ($datos_tOctubreIncidencias[0] == 0){
							$data0['OCT'] = 0;
						}else{
							$data0['OCT'] = $datos_tOctubreIncidencias[0];
						}
						
						//NOVIEMBRE
						$datos_tNoviembreIncidencias = mysqli_fetch_array($res_tNoviembreIncidencias);
						if ($datos_tNoviembreIncidencias[0] == 0){
							$data0['NOV'] = 0;
						}else{
							$data0['NOV'] = $datos_tNoviembreIncidencias[0];
						}
						
						//DICIEMBRE
						$datos_tDiciembreIncidencias = mysqli_fetch_array($res_tDiciembreIncidencias);
						if ($datos_tDiciembreIncidencias[0] == 0){
							$data0['DIC'] = 0;
						}else{
							$data0['DIC'] = $datos_tDiciembreIncidencias[0];
						}
						
						
						$res_tIncidenciasAnio = mysqli_fetch_row($tIncidenciasAnio);
						//GRAFICA 3	
					
						//COMBOBOX PARA SELECCIONA EL AÑO
						//NECESITAMOS LA FECHA MINIMA PARA OBTENER TODOS LOS AÑOS HASTA EL ACTUAL
						$cDateMinimo = mysqli_query ($connection, "SELECT MIN(DATE) FROM peticiones");
						$res_DateMinimo = mysqli_fetch_row($cDateMinimo);
						$DateMinimo = $res_DateMinimo[0];
						$anioMinimo = substr($DateMinimo, 0, 4);
						$anioMaximo = $ahora["year"];
						echo '<blockquote><table><tr><td>
						<center><form id="formShort" method="post" action="stats.php">
						<fieldset>
						<legend>Selecci&#243;n de a&#241;o: </legend>
							<label id="label2" for="anio">
							<select name="anio" tabindex="1" id="anio">';
								for ($i=$anioMaximo; $i >= $anioMinimo; $i--){
									if ($i == $anio){
										echo '<option value="'.$i.'" selected="selected">'.$i.'</option>';
									}else{
										echo '<option value="'.$i.'">'.$i.'</option>';
									}
								}
								
							echo '</select>A&#209;O:
							</label>
							<label id="label2" for="submit">
								<input name="Submit" type="submit" id="submit" value="Enviar" />
							</label>	
						</fieldset>
						</form></center>

						</td></tr><tr><td>';
						$mcIncidenciasxMes = new maxChart($data0);
						$mcIncidenciasxMes->displayChart('Incidencias por mes '.$anio.' (TOTAL = '.$res_tIncidenciasAnio[0].')',1,800,150,true);
						
						echo '</td></tr>
						</table></blockquote>';
						
		 
					  echo '</div>
					  <div id="footer">';
																							  
					  echo '</div>';
					$res_tIncidencias=mysqli_fetch_row($tIncidencias);
					echo '<blockquote>';
					echo '<h3>INCIDENCIAS TOTALES (ACUMULADO DE TODOS LOS A&#209;OS): <font color="red" size="5">'.$res_tIncidencias[0].'</font></h3>';
					
					echo '</table>';
					echo '</blockquote>';
					
					echo '<p>&nbsp;</p>
					<hr width="1000" align="left" />
					<table width="1000">
						<tr>
							<td colspan = "5"><center><h2>OTRAS ESTADISTICAS</h2></center></td>
						</tr>
						<tr>
							<td class = "morestats"><a href="" title="ESTADISTICA POR XXXX (EN CONSTRUCCION)"><center><img border="0" src="images/stats_target_type.jpg" alt="NO IMAGEN"></img></center></a></td>
							<td class = "morestats"><a href="javascript:nueva(\'stats_issue_type.php\')" title="ESTADISTICA POR TIPO DE INCIDENCIA"><center><img border="0" src="images/stats_issue_type.jpg" alt="NO IMAGEN"></img></center></a></td>
							<td class = "morestats"><a href="" title="ESTADISTICA POR TIEMPO (EN CONSTRUCCION)"><center><img border="0" src="images/stats_time.jpg" alt="NO IMAGEN"></img></center></a></td>
							<td class = "morestats"><a href="javascript:nueva(\'stats_user_open.php\')" title="ESTADISTICA POR USUARIO QUE ABRE LA INCIDENCIA"><center><img border="0" src="images/stats_cp_open.jpg" alt="NO IMAGEN"></img></center></a></td>
							<td class = "morestats"><a href="javascript:nueva(\'stats_user_close.php\')" title="ESTADISTICA POR USUARIO QUE RESUELVE LA INCIDENCIA"><center><img border="0" src="images/stats_cp_close.jpg" alt="NO IMAGEN"></img></center></a></td>
						</tr>
					 </table>';
			
			echo '<p>&nbsp;</p>
			</body>
			</html>';
	} else {
		$ahora = getdate(); //Obtiene un array con los datos de la fecha y hora actual
		$fecha = $ahora["year"]."-".$ahora["mon"]."-".$ahora["mday"]." ".$ahora["hours"].":".$ahora["minutes"].":".$ahora["seconds"]; //Obtiene el formato adecuado de fecha hora para insertar en la BBDD
		$IP = $_SERVER['REMOTE_ADDR'];
		$pagina = $_SERVER['PHP_SELF'];
		mysql_query("INSERT INTO intrusos (IP,tipo,descripcion,fecha) values ('".$IP."',4,'".$pagina."','".$fecha."') ");
		Header("Location: index.php?intrusion=1");
	}


?>
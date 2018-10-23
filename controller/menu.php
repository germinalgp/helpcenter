<?php
	/**
	* PHP que muestra el menu de usuario en funcion del level, pudiendo tener mas o menos opciones
	* @author Germinal GARRIDO PUYANA
	*/
	/**
	* Funcion para mostrar el menu de usuarios administradores (los que no tienen level 9)
	* @author Germinal GARRIDO PUYANA
	* @param int $btn_home Si es 1 activa el boton si es 0 no lo activa
	* @param int $btn_gestionar Si es 1 activa el boton si es 0 no lo activa
	* @param int $btn_registro Si es 1 activa el boton si es 0 no lo activa
	* @param int $btn_pass Si es 1 activa el boton si es 0 no lo activa
	* @param int $btn_stats Si es 1 activa el boton si es 0 no lo activa
	* @param int $btn_intrusos Si es 1 activa el boton si es 0 no lo activa
	*/
	function menu_int($btn_home, $btn_gestionar, $btn_registro, $btn_pass, $btn_stats, $btn_intrusos){
		require("../conexion.php");
		date_default_timezone_set('Europe/Madrid');
		if ($btn_home){
			$img_home = '<div class="centrar-imagen"><img title="OPCI&#211;N ACTUAL" border="0" src="../images/home_on.jpg" alt="NO IMAGEN"></img></div>'; 
		}else{
			$img_home = '<div class="centrar-imagen"><img title="HOME" border="0" src="../images/home.jpg" alt="NO IMAGEN"></img></div>'; 
		}
		
		if ($btn_registro){
			$img_registro = '<div class="centrar-imagen"><img title="OPCI&#211;N ACTUAL" border="0" src="../images/registro_on.jpg" alt="NO IMAGEN"></img></div>';  
		}else{
			$img_registro = '<div class="centrar-imagen"><img title="REGISTRO USUARIOS" border="0" src="../images/registro.jpg" alt="NO IMAGEN"></img></div>'; 
		}
		
		if ($btn_pass){
			$img_pass = '<div class="centrar-imagen"><img title="OPCI&#211;N ACTUAL" border="0" src="../images/password_on.jpg" alt="NO IMAGEN"></img></div>'; 
		}else{
			$img_pass = '<div class="centrar-imagen"><img title="GESTI&#211;N PASSWORD" border="0" src="../images/password.jpg" alt="NO IMAGEN"></img></div>'; 
		}
		if ($btn_stats){
			$img_stats = '<div class="centrar-imagen"><img title="OPCI&#211;N ACTUAL" border="0" src="../images/stats_on.jpg" alt="NO IMAGEN"></img></div>'; 
		}else{
			$img_stats = '<div class="centrar-imagen"><img title="ESTADISTICAS" border="0" src="../images/stats.jpg" alt="NO IMAGEN"></img></div>';  
		}
		if ($btn_gestionar){
			$img_gestionar = '<div class="centrar-imagen"><img title="OPCI&#211;N ACTUAL" border="0" src="../images/gestionar_on.jpg" alt="NO IMAGEN"></img></div>'; 
		}else{
			$img_gestionar = '<div class="centrar-imagen"><img title="GESTIONAR PRODUCTOS/CATEGORIAS" border="0" src="../images/gestionar.jpg" alt="NO IMAGEN"></img></div>';  
		}
		
		
		
		echo '<form method="post" action="../respuesta.php">
		<table width="650">
				<tr>
					<td width="10"></td>
					<td style="font-size:10px; background-color:#B9D6ED; text-align:center" width="48">HOME</td>
					<td style="font-size:10px; background-color:#B9D6ED; text-align:center" width="48">GESTIONAR PROD/CAT</td>
					<td style="font-size:10px; background-color:#B9D6ED; text-align:center" width="48">REGISTRO USUARIOS</td>
					<td style="font-size:10px; background-color:#B9D6ED; text-align:center" width="48">GESTION PASSWORD</td>
					<td style="font-size:10px; background-color:#B9D6ED; text-align:center" width="48">ESTADISTICAS</td>';
					if ($_SESSION['level']==1){
						echo '<td style="font-size:10px; background-color:#B9D6ED; text-align:center" width="48">INTRUSIONES</td>';
					}
					echo '<td style="font-size:10px; background-color:#B9D6ED; text-align:center" width="48">SALIR DE APLICACION</td>
					<td width="300"></td>
					<td colspan = "2"></td>
					
				</tr>
				<tr>
				<td></td>
				<td width="48"><a href="../index.php" title="HOME">'.$img_home.'</a></td>';
				if ($_SESSION['level'] == 1){ //NIVELES 1 
					echo'<td width="48"><a href="../add_delete.php" title="GESTIONAR PROD/CAT">'.$img_gestionar.'</a></td>';
				}else{ //NIVELES 2 y 4 NO PUEDEN REGISTRAR
					echo'<td width="48"><div class="centrar-imagen"><img title="OPCION NO DISPONIBLE" border="0" src="../images/gestionar_off.jpg" alt="NO IMAGEN"></img></div></td>';
				}
				if ($_SESSION['level'] == 1 || $_SESSION['level'] == 3){ //NIVELES 1 y 3 PUEDEN REGISTRAR
					echo'<td width="48"><a href="../registro.php" title="REGISTRO DE USUARIOS">'.$img_registro.'</a></td>';
				}else{ //NIVELES 2 y 4 NO PUEDEN REGISTRAR
					echo'<td width="48"><div class="centrar-imagen"><img align="center" title="OPCION NO DISPONIBLE" border="0" src="../images/registro_off.jpg" alt="NO IMAGEN"></img></div></td>';
				}
				echo'<td width="48"><a href="../cambio_pass.php" title="GESTION PASSWORD">'.$img_pass.'</a></td>';
				if ($_SESSION['level'] == 4 || $_SESSION['level'] == 3){ //NIVELES 3 y 4 NO PUEDEN VER ESTADISTICAS
					echo'<td width="48"><div class="centrar-imagen"><img title="OPCION NO DISPONIBLE" border="0" src="../images/stats_off.jpg" alt="NO IMAGEN"></img></div></td>';
				}else{ //NIVELES 1 y 2 VEN ESTADISTICAS
					echo '<td width="48"><a href="../stats.php" title="ESTADISTICAS">'.$img_stats.'</a></td>';
				}
				
				//OBTENER INTRUSIONES
				
				$intrusiones = mysqli_query($connection, "SELECT COUNT(*) FROM intrusos WHERE revisado = 0");
				$res_intrusiones = mysqli_fetch_row($intrusiones); 
				
				if ($_SESSION['level'] == 1){
					if ($btn_intrusos){
						if ($res_intrusiones[0]==0){
							echo'<td width="48"><a href="../intrusos.php" title="NO HAY INTRUSIONES"><div class="centrar-imagen"><img width="48" border="0" src="../images/intrusos_no_on.gif" alt="NO IMAGEN"></img></div></a></td>';
						}else if ($res_intrusiones[0]==1){
							echo'<td width="48"><a href="../intrusos.php" title="'.$res_intrusiones[0].' INTRUSION"><div class="centrar-imagen"><img width="48" border="0" src="../images/intrusos_si_on.gif" alt="NO IMAGEN"></img></div></a></td>';
						}else{
							echo'<td width="48"><a href="../intrusos.php" title="'.$res_intrusiones[0].' INTRUSIONES"><div class="centrar-imagen"><img width="48" border="0" src="../images/intrusos_si_on.gif" alt="NO IMAGEN"></img></div></a></td>';
						}
					}else{
						if ($res_intrusiones[0]==0){
							echo'<td width="48"><a href="../intrusos.php" title="NO HAY INTRUSIONES"><div class="centrar-imagen"><img width="48" border="0" src="../images/intrusos_no.gif" alt="NO IMAGEN"></img></div></a></td>';
						}else if ($res_intrusiones[0]==1){
							echo'<td width="48"><a href="../intrusos.php" title="'.$res_intrusiones[0].' INTRUSION"><div class="centrar-imagen"><img width="48" border="0" src="../images/intrusos_si.gif" alt="NO IMAGEN"></img></div></a></td>';
						}else{
							echo'<td width="48"><a href="../intrusos.php" title="'.$res_intrusiones[0].' INTRUSIONES"><div class="centrar-imagen"><img width="48" border="0" src="../images/intrusos_si.gif" alt="NO IMAGEN"></img></div></a></td>';
						}
					}
				}	
				
				echo '<td width="48"><a href="../logout.php" title="SALIR DE LA APLICACI&#211;N"><div class="centrar-imagen"><img border="0" src="../images/salir.jpg" alt="NO IMAGEN"></img></div></a></td>
					<td width="300"></td>
					<td style="font-size:10px; text-align:center">
						Buscar incidencia por ID
						<input type="hidden" name="buscarXid" value="1" size="1"></input>
						<input type="text" name="ID"></input>
					</td>
					<td width="20">
						<input type="image" src="../images/buscar.jpg" title="BUSCAR INCIDENCIA"></input>
					</td>
						
				</tr></table></form>';
		
			//TABLA DE DATOS USUARIO
			$usuarios = mysqli_query($connection, "SELECT u.nombre,u.departamento,l.avatar, l.descripcion FROM users u, tipo_level l WHERE u.nick = '".$_SESSION['usuario']."' AND l.level = u.level");
			$user = mysqli_fetch_array($usuarios); //Obtenemos el usuario en user_ok
			echo '<blockquote>
				<table>
				<tr>
				 <td width="100">';
					if (file_exists("../images/usuarios/".$_SESSION['usuario'].".jpg")){
						echo '<img border="0" src="../images/usuarios/'.$_SESSION['usuario'].'.jpg" alt="NO FOTO" width="100"></img>';
					}else{
						echo '<img border="0" src="../images/usuarios/nofoto.jpg" alt="NO FOTO" width="100"></img>';
					}
				 echo '</td>
				 <td width="25">
				 </td>
				 <td width="600">
					<b>'.$user["nombre"].'</b>
					<br /><b>Usuario:</b> '.$_SESSION['usuario'].'
					<br /><b>Departamento:</b> '.$user["departamento"].'
					<br /><b>Nivel:&nbsp;&nbsp;&nbsp;</b><img border="0" src="../'.$user["avatar"].'" height="30" align="middle" title="'.$user["descripcion"].'" alt="NO FOTO"></img>  
				 </td>
				</tr>
				</table>
				</blockquote>';
		
			echo '<hr width="1000" align="left" />';
	}

	/**
	* Funcion para mostrar el menu de usuarios NO ADMINISTRADORES, es decir, los que crean las incidencias (level 9)
	* @author Germinal GARRIDO PUYANA
	* @param int $btn_home Si es 1 activa el boton si es 0 no lo activa
	* @param int $btn_crear Si es 1 activa el boton si es 0 no lo activa
	* @param int $btn_pass Si es 1 activa el boton si es 0 no lo activa
	*/
	function menu_ext($btn_home, $btn_crear, $btn_pass){
		date_default_timezone_set('Europe/Madrid');
		require('../conexion.php');
		if ($btn_home){
			$img_home = '<div class="centrar-imagen"><img title="OPCI&#211;N ACTUAL" border="0" src="../images/home_on.jpg" alt="NO IMAGEN"></img></div>'; 
		}else{
			$img_home = '<div class="centrar-imagen"><img title="HOME" border="0" src="../../images/home.jpg" alt="NO IMAGEN"></img></div>'; 
		}
		
		if ($btn_crear){
			$img_crear = '<div class="centrar-imagen"><img title="OPCI&#211;N ACTUAL" border="0" src="../../images/incidencia_add_on.jpg" alt="NO IMAGEN"></img></div>'; 
		}else{
			$img_crear = '<div class="centrar-imagen"><img title="A&#209;ADIR INCIDENCIA" border="0" src="../../images/incidencia_add.jpg" alt="NO IMAGEN"></img></div>'; 
		}
		
		if ($btn_pass){
			$img_pass = '<div class="centrar-imagen"><img title="OPCI&#211;N ACTUAL" border="0" src="../../images/password_on.jpg" alt="NO IMAGEN"></img></div>'; 
		}else{
			$img_pass = '<div class="centrar-imagen"><img title="GESTI&#211;N PASSWORD" border="0" src="../images/password.jpg" alt="NO IMAGEN"></img></div>'; 
		}
		
		echo '<form method="post" action="../respuesta.php">
		<table width="600">
				<tr>
					<td colspan="2"></td>
					<td style="font-size:10px; background-color:#B9D6ED; text-align:center" width="48">HOME</td>
					<td style="font-size:10px; background-color:#B9D6ED; text-align:center" width="48">A&#209;ADIR INCIDENCIA</td>
					<td style="font-size:10px; background-color:#B9D6ED; text-align:center" width="48">GESTION PASSWORD</td>
					<td style="font-size:10px; background-color:#B9D6ED; text-align:center" width="48">SALIR DE APLICACION</td>
					<td width="200"></td>
					<td colspan = "2"></td>
					
				</tr>
				<tr>
				<td width="48"><div class="centrar-imagen"><img border="0" src="../images/blanco.jpg" alt="NO IMAGEN"></img></div></td>
				<td width="50"></td>
				<td width="48"><a href="../index.php" title="HOME">'.$img_home.'</a></td>
				<td width="48"><a href="../peticion.php" title="CREAR INCIDENCIA">'.$img_crear.'</a></td>
				<td width="48"><a href="../cambio_pass.php" title="GESTION PASSWORD">'.$img_pass.'</a></td>
				<td width="48"><a href="../logout.php" title="SALIR DE LA APLICACI&#211;N"><div class="centrar-imagen"><img border="0" src="../images/salir.jpg" alt="NO IMAGEN"></img></div></a></td>
				<td width="200"></td>
				<td style="font-size:10px; text-align:center">
						Buscar incidencia por ID
						<input type="hidden" name="buscarXid" value="1" size="1"></input>
						<input type="text" name="ID"></input>
				</td>
				<td width="20" >
						<input type="image" src="../images/buscar.jpg" title="BUSCAR INCIDENCIA"></input>
				</td>
				
					
				</tr></table></form>';

			$usuarios = mysqli_query($connection, "SELECT u.nombre,u.departamento,l.avatar, l.descripcion FROM users u, tipo_level l WHERE u.nick = '".$_SESSION['usuario']."' AND l.level = u.level");
			$user = mysqli_fetch_array($usuarios); //Obtenemos el usuario en user_ok
			//TABLA DE DATOS USUARIO
			echo '<blockquote>
			<table>
				<tr>
				 <td width="100">
					<img border="0" src="../images/usuarios/nofoto.jpg" width="100" alt="NO FOTO"></img>
				 </td>
				 <td width="25">
				 </td>
				 <td width="600">
					<b>'.$user["nombre"].'</b>  
					<br/><b>Usuario:</b> '.$_SESSION['usuario'].'
					<br/><b>Nivel:&nbsp;&nbsp;&nbsp;</b><img border="0" src="'.$user["avatar"].'" height="30" align="middle" title="'.$user["descripcion"].'" alt="NO FOTO"></img>  
				</td>
				</tr>
				</table>
				</blockquote>';
		
			echo '<hr width="1000" align="left" />';
	}
?>
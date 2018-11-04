<?php
	/**
	* PHP que posibilita la subida de archivos a las incidencias para una gestion mas eficiente
	* Solo para usuarios con level administrador. Los archivos son para gestion interna, no pueden verlos los niveles 9.
	* @author Germinal GARRIDO PUYANA
	* @version v1.1(0218)
	*/
	require('conexion.php'); 

	/**
	 * Reemplaza todos los acentos por sus equivalentes sin ellos
	 *
	 * @param $string
	 *  string la cadena a sanear
	 *
	 * @return $string
	 *  string saneada
	 */

	function sanear_string($string)
	{

		$string = trim($string);

		$string = str_replace(
			array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'),
			array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'),
			$string
		);

		$string = str_replace(
			array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'),
			array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'),
			$string
		);

		$string = str_replace(
			array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'),
			array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'),
			$string
		);

		$string = str_replace(
			array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'),
			array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'),
			$string
		);

		$string = str_replace(
			array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'),
			array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'),
			$string
		);

		$string = str_replace(
			array('ñ', 'Ñ', 'ç', 'Ç'),
			array('n', 'N', 'c', 'C',),
			$string
		);

		//Esta parte se encarga de eliminar cualquier caracter extraño
		$string = str_replace(
			array("\\", "¨", "º", "-", "~",
				 "#", "@", "|", "!", "\"",
				 "·", "$", "%", "&", "/",
				 "(", ")", "?", "'", "¡",
				 "¿", "[", "^", "`", "]",
				 "+", "}", "{", "¨", "´",
				 ">", "<", ";", ",", ":",
				 " "),
			'',
			$string
		);


		return $string;
	}

	if ($_SESSION['level'] == 4 || $_SESSION['level'] == 3 || $_SESSION['level'] == 2 || $_SESSION['level'] == 1){ //SI SESSION Y NIVEL ADECUADO
	/**
	 * PHP Real Ajax Uploader
	 * Copyright @Alban Xhaferllari
	 * albanx@gmail.com
	 * www.albanx.com
	 */
	 
	 if (strpos($_SERVER['PHP_SELF'],'controller') != false){
				$ruta = '../';
	 }
	 
	$id = $_GET['ID'];
	error_reporting(E_ALL ^ E_NOTICE);//remove notice for json invalidation
	$sql = "SELECT date_format(DATE, '%Y-%m-%d %H:%i:%s' ) as DATE  FROM peticiones WHERE ID = ".$id;
	$resultado=mysqli_fetch_array(mysqli_query($connection, $sql));
	$fecha_peticion = date_parse($resultado[0]);

	$uploadPath	= $ruta."smb/incidencias/".$fecha_peticion["year"]."/".str_pad($fecha_peticion["month"], 2, "0", STR_PAD_LEFT)."/".str_pad($fecha_peticion["day"], 2, "0", STR_PAD_LEFT)."/".$id."/";
	$fileName	= sanear_string(urldecode($_REQUEST['ax-file-name']));
	$currByte	= $_REQUEST['ax-start-byte'];
	$maxFileSize= $_REQUEST['ax-maxFileSize'];
	$html5fsize	= $_REQUEST['ax-fileSize'];
	$isLast		= $_REQUEST['isLast'];

	//if set generates thumbs only on images type files
	$thumbHeight = $_REQUEST['ax-thumbHeight'];
	$thumbWidth	 = $_REQUEST['ax-thumbWidth'];
	$thumbPostfix= $_REQUEST['ax-thumbPostfix'];
	$thumbPath	 = $_REQUEST['ax-thumbPath'];
	$thumbFormat = $_REQUEST['ax-thumbFormat'];

	$allowExt    = (empty($_REQUEST['ax-allow-ext']))?array():explode('|', $_REQUEST['ax-allow-ext']);
	$uploadPath	.= (!in_array(substr($uploadPath, -1), array('\\','/') ) )?DIRECTORY_SEPARATOR:'';//normalize path

	if(!file_exists($uploadPath) && !empty($uploadPath))
	{
		mkdir($uploadPath, 0777, true);
	}

	if(!file_exists($thumbPath) && !empty($thumbPath))
	{
		mkdir($thumbPath, 0777, true);
	}

	//with gd library
	function createThumbGD($filepath, $thumbPath, $postfix, $maxwidth, $maxheight, $format='jpg', $quality=75)
	{	
		if($maxwidth<=0 && $maxheight<=0)
		{
			return 'No valid width and height given';
		}
		
		$gd_formats	= array('jpg','jpeg','png','gif');//web formats
		$file_name	= pathinfo($filepath);
		if(empty($format)) $format = $file_name['extension'];
		
		if(!in_array(strtolower($file_name['extension']), $gd_formats))
		{
			return false;
		}
		
		$thumb_name	= $file_name['filename'].$postfix.'.'.$format;
		
		if(empty($thumbPath))
		{
			$thumbPath=$file_name['dirname'];	
		}
		$thumbPath.= (!in_array(substr($thumbPath, -1), array('\\','/') ) )?DIRECTORY_SEPARATOR:'';//normalize path
		
		// Get new dimensions
		list($width_orig, $height_orig) = getimagesize($filepath);
		if($width_orig>0 && $height_orig>0)
		{
			$ratioX	= $maxwidth/$width_orig;
			$ratioY	= $maxheight/$height_orig;
			$ratio 	= min($ratioX, $ratioY);
			$ratio	= ($ratio==0)?max($ratioX, $ratioY):$ratio;
			$newW	= $width_orig*$ratio;
			$newH	= $height_orig*$ratio;
				
			// Resample
			$thumb = imagecreatetruecolor($newW, $newH);
			$image = imagecreatefromstring(file_get_contents($filepath));
				
			imagecopyresampled($thumb, $image, 0, 0, 0, 0, $newW, $newH, $width_orig, $height_orig);
			
			// Output
			switch (strtolower($format)) {
				case 'png':
					imagepng($thumb, $thumbPath.$thumb_name, 9);
				break;
				
				case 'gif':
					imagegif($thumb, $thumbPath.$thumb_name);
				break;
				
				default:
					imagejpeg($thumb, $thumbPath.$thumb_name, $quality);;
				break;
			}
			imagedestroy($image);
			imagedestroy($thumb);
		}
		else 
		{
			return false;
		}
	}


	//for image magick
	function createThumbIM($filepath, $thumbPath, $postfix, $maxwidth, $maxheight, $format)
	{
		$file_name	= pathinfo($filepath);
		$thumb_name	= $file_name['filename'].$postfix.'.'.$format;
		
		if(empty($thumbPath))
		{
			$thumbPath=$file_name['dirname'];	
		}
		$thumbPath.= (!in_array(substr($thumbPath, -1), array('\\','/') ) )?DIRECTORY_SEPARATOR:'';//normalize path
		
		$image = new Imagick($filepath);
		$image->thumbnailImage($maxwidth, $maxheight);
		$images->writeImages($thumbPath.$thumb_name);
	}


	function checkFilename($fileName, $size, $newName = '')
	{
		global $allowExt, $uploadPath, $maxFileSize;
		
		//------------------max file size check from js
		$maxsize_regex = preg_match("/^(?'size'[\\d]+)(?'rang'[a-z]{0,1})$/i", $maxFileSize, $match);
		$maxSize=4*1024*1024;//default 4 M
		if($maxsize_regex && is_numeric($match['size']))
		{
			switch (strtoupper($match['rang']))//1024 or 1000??
			{
				case 'K': $maxSize = $match[1]*1024; break;
				case 'M': $maxSize = $match[1]*1024*1024; break;
				case 'G': $maxSize = $match[1]*1024*1024*1024; break;
				case 'T': $maxSize = $match[1]*1024*1024*1024*1024; break;
				default: $maxSize = $match[1];//default 4 M
			}
		}

		if(!empty($maxFileSize) && $size>$maxSize)
		{
			echo json_encode(array('name'=>$fileName, 'size'=>$size, 'status'=>'error', 'info'=>'Tama&ntilde;o de fichero no permitido'));
			return false;
		}
		//-----------------End max file size check
		
		
		//comment if not using windows web server
		$windowsReserved	= array('CON', 'PRN', 'AUX', 'NUL','COM1', 'COM2', 'COM3', 'COM4', 'COM5', 'COM6', 'COM7', 'COM8', 'COM9',
								'LPT1', 'LPT2', 'LPT3', 'LPT4', 'LPT5', 'LPT6', 'LPT7', 'LPT8', 'LPT9');    
		$badWinChars		= array_merge(array_map('chr', range(0,31)), array("<", ">", ":", '"', "/", "\\", "|", "?", "*"));

		$fileName	= str_replace($badWinChars, '', $fileName);
		$fileInfo	= pathinfo($fileName);
		$fileExt	= $fileInfo['extension'];
		$fileBase	= $fileInfo['filename'];
		
		//check if legal windows file name
		if(in_array($fileName, $windowsReserved))
		{
			echo json_encode(array('name'=>$fileName, 'size'=>0, 'status'=>'error', 'info'=>'Nombre de fichero no permitido. Reservado de Windows.'));	
			return false;
		}
		
		//check if is allowed extension
		if(!in_array($fileExt, $allowExt) && count($allowExt))
		{
			echo json_encode(array('name'=>$fileName, 'size'=>0, 'status'=>'error', 'info'=>"Extension [$fileExt] no permitida."));	
			return false;
		}
		
		$fullPath = $uploadPath.$fileName;
		$c=0;
		while(file_exists($fullPath))
		{
			$c++;
			$fileName	= $fileBase."($c).".$fileExt;
			$fullPath 	= $uploadPath.$fileName;
		}
		return $fullPath;
	}

	if(isset($_FILES['ax-files'])) 
	{
		//for eahc theorically runs only 1 time, since i upload i file per time
		foreach ($_FILES['ax-files']['error'] as $key => $error) {
			if ($error == UPLOAD_ERR_OK) {
				$newName = !empty($fileName)? $fileName:$_FILES['ax-files']['name'][$key];
				$fullPath = checkFilename($newName, $_FILES['ax-files']['size'][$key]);
				
				if($fullPath)
				{
					move_uploaded_file($_FILES['ax-files']['tmp_name'][$key], $fullPath);
					mysqli_query ($connection,  "INSERT INTO historial_ficheros (ID_ISSUE, AUTHOR, DATE, FILENAME) values ('".$id."','".$_SESSION["usuario"]."',now(),'$fileName')");
					if(!empty($thumbWidth) || !empty($thumbHeight))
						createThumbGD($fullPath, $thumbPath, $thumbPostfix, $thumbWidth, $thumbHeight, $thumbFormat);
						
					echo json_encode(array('name'=>basename($fullPath), 'size'=>filesize($fullPath), 'status'=>'uploaded', 'info'=>'Fichero subido'));
				}
			} else {
				switch ($error) {
					 case UPLOAD_ERR_INI_SIZE :
						  $mensaje = "Tama&ntilde;o de fichero no permitido";
						  break;
					 case UPLOAD_ERR_FORM_SIZE:
						  $mensaje = "El archivo subido excede la directiva MAX_FILE_SIZE";
						  break;
					 case UPLOAD_ERR_PARTIAL:
						  $mensaje = "El archivo subido fue solo parcialmente cargado.";
						  break;
					 case UPLOAD_ERR_NO_FILE:
						  $mensaje = "Ning&uacute;n archivo fue subido.";
						  break; 
					 case UPLOAD_ERR_NO_TMP_DIR:
						  $mensaje = "Falta la carpeta temporal.";
						  break;
					 case UPLOAD_ERR_CANT_WRITE:
						  $mensaje = "No se pudo escribir el archivo en el disco";
						  break; 
					 case UPLOAD_ERR_EXTENSION:
						  $mensaje = "Una extensi&oacute;n de PHP detuvo la carga de archivos";
						  break;
					 default:
						  $mensaje = "Error desconocido";
				}

				echo json_encode(array('name'=>basename($_FILES['ax-files']['name'][$key]), 'size'=>$_FILES['ax-files']['size'][$key], 'status'=>'error', 'info'=>$mensaje));
			}
		}
	} else if(isset($_REQUEST['ax-file-name']) && $_REQUEST['ax-file-name'] != "") {
		//check only the first peice
		$fullPath = ($currByte!=0) ? $uploadPath.$fileName:checkFilename($fileName, $html5fsize);
		
		if($fullPath)
		{
			$putdata = fopen('php://input', 'r');
			$fp = fopen($fullPath, 'a');
			while ($data=fread($putdata, 1024))
			   fwrite($fp,$data);
			fclose($putdata);
			fclose($fp);

			if($isLast=='true')
			{
				mysqli_query ($connection, "INSERT INTO historial_ficheros (ID_ISSUE, AUTHOR, DATE, FILENAME) values ('".$id."','".$_SESSION["usuario"]."',now(),'$fileName')");
				createThumbGD($fullPath, $thumbPath, $thumbPostfix, $thumbWidth, $thumbHeight, $thumbFormat);
			}
			echo json_encode(array('name'=>basename($fullPath), 'size'=>$currByte, 'status'=>'uploaded', 'info'=>'Fichero/pieza subido'));
		}
	} else {
	echo json_encode(array('name'=>"", 'size'=>"0", 'status'=>'error', 'info'=>'Ocurri&oacute; un error. Posible tama&ntilde;o no permitido'));
	}
	}else{ //GRABAMOS
		$ahora = getdate(); //Obtiene un array con los datos de la fecha y hora actual
		$fecha = $ahora["year"]."-".$ahora["mon"]."-".$ahora["mday"]." ".$ahora["hours"].":".$ahora["minutes"].":".$ahora["seconds"]; //Obtiene el formato adecuado de fecha hora para insertar en la BBDD
		$IP = $_SERVER['REMOTE_ADDR'];
		$pagina = $_SERVER['PHP_SELF'];
		mysqli_query ($connection, "INSERT INTO intrusos (IP,tipo,descripcion,fecha) values ('".$IP."',4,'".$pagina."','".$fecha."') ");
		Header("Location: index.php?intrusion=1");
	}
?>
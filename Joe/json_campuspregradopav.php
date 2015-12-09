<?php
header('content-type: text/plain; charset: utf-8');
//$server = '69.64.43.123';
//$server = '199.217.115.232';
//$user = 'uplataforma';
//$pwd = 'pPl@tform15';
$server = '54.242.106.177';
$user = 'elio1';
$pwd = 'pelio1';
$database = 'db_sigav';
$link = mysqli_connect($server,$user,$pwd,$database);

// $link = mysqli_connect($server,$user,$pwd,$database) or die("Error " . mysqli_error($link)); 
// Check connection
if (mysqli_connect_errno())
  {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }

if($link)
{

//echo "...Logro conexion ...paso... " ; 


	if(isset($_GET['action']))
	{
		$action = trim($_GET['action']);	
		switch($action)
		{
			case 'json_usuario';
				if(isset($_GET['username']) && isset($_GET['password']))
				{
					$username = trim($_GET['username']);
					$password = trim($_GET['password']);
					
					if($username != "" && $password != "")
					{
						$sql = "SELECT t1.part_username,t1.claveplano,
							t1.clavemd5,t1.rol_participante,t1.nombre,
							t1.apepat,t1.apemat,t1.apellido,MIN(CAST(t1.fk_codigo AS UNSIGNED)) AS fk_codigo,
							(
								SELECT a1.meta_value 
								FROM t_meta_participante a1, t_participante a2
								WHERE a1.fk_part_username = a2.part_username AND 
								a2.part_username = '".$username."' AND a2.claveplano = '".$password."' 
								AND a1.meta_field = 'MOODLE_FOTO_F1' LIMIT 0,1
							) MOODLE_FOTO_URL,t1.email,t1.correo1,
							t1.descripcion,t1.telefono1
							FROM t_participante t1,t_meta_participante a1 
							WHERE t1.part_username = '".$username."' AND t1.claveplano = '".$password."'
							AND t1.part_username=a1.fk_part_username
							LIMIT 0,1;";
						$sql = mysqli_query($link,$sql);
							
						if(mysqli_num_rows($sql) > 0)
						{
							$r = mysqli_fetch_array($sql);
							if($r[0]!=""){
								$string = '{
									"json_cursos":
									{
										"usuario":{
											"part_username": "'.$r[0].'",
											"password_plano": "'.$r[1].'",
											"password_md5": "'.$r[2].'",
											"rol_participante": "'.$r[3].'",
											"nombre": "'.utf8_encode($r[4]).'",
											"apellido_paterno": "'.utf8_encode($r[5]).'",
											"apellido_materno": "'.utf8_encode($r[6]).'",
											"apellidos": "'.utf8_encode($r[7]).'",
											"facultad": "'.$r[8].'",
											"moodle_foto_f1_url": "'.$r[9].'",
											"email": "'.$r[10].'",
											"correo1": "'.$r[11].'",
											"descripcion": "'.htmlentities($r[12]).'",
											"telefono1": "'.$r[13].'"
										}
									}
								}';
								echo $string;
								exit();
								}else
						{
							echo "LOGIN_FAIL";
							exit();
						}
						}
						else
						{
							echo "LOGIN_FAIL";
							exit();
						}
						mysqli_free_result($sql);
					}
					else
					{
							echo "LOGIN_FAIL";
							exit();
					}
					
				}
				else
				{
					echo "";
					exit();
				}
				
			break;

			case 'pav':
				if(isset($_GET['username'])){
						//$sql = "CALL sp_mob_uno('".$username."','22371456')";
						$sql = "CALL sp_mob_uno('V15143427','22371456')";
						$sql = mysqli_query($link,$sql);
						echo "lines: ".mysqli_num_rows($sql);
						while($row = mysqli_fetch_array($sql)){
							echo "r1".$row[0];
						}

						/*$sql = "CALL sp_mob_dos('19122015143427')";
						$sql = mysqli_query($link,$sql);
						echo "lines: ".mysqli_num_rows($sql)."\n";
						while($row = mysqli_fetch_array($sql)){
							echo "r1".$row[0]." ".$row[1]." ".$row[2]."\n";
						}*/

				}
				break;
			
			case 'json_cursos':
				if(isset($_GET['username']))
				{
					$username = trim($_GET['username']);
					$facu = trim($_GET['facu']);
					
					if($username != "")
					{
						$sql = "CALL dos('".$username."','".$facu."')";
						$sql = mysqli_query($link,$sql);
						if(mysqli_num_rows($sql) > 0)
						{
							$string = '{
								"json_cursos":{
									"curso":[';
									$tmp_string = '';
									while($r = mysqli_fetch_array($sql))									
										$tmp_string.= '{
											"instanceid": "'.$r[0].'",
											"fullname": "'.utf8_encode($r[1]).'",
											"NAME": "'.utf8_encode($r[2]).'",
											"db": "'.$r[3].'",
											"facultad": "'.$r[4].'",
											"iddoc": "'.$r[5].'"},';
									$tmp_string = trim($tmp_string,",");
									$string.= $tmp_string;
							$string.= ']}}';
							echo $string;
							exit();
						}
						else
						{
							echo "EMPTY_COURSES";
							exit();
						}
						mysqli_free_result($sql);					
					}
					else
					{
						echo "EMPTY_COURSES";
						exit();					
					}						
					
				}
				else
				{
					echo "";
					exit();
				}
			break;
			
			case 'getPaises':
				$sql = "SELECT country_code2, country_name FROM ipdatabase GROUP BY country_code2 ORDER BY country_name";
				$query = mysqli_query($link,$sql);
				$tmp_string = "";
				if(mysqli_num_rows($query)>0)
				{
					$tmp_string = '{
						"json_paises":{
							"paises":[';
					while($r = mysqli_fetch_array($query))
						$tmp_string.= '{ "codPais": "'.$r[0].'", "nomPais": "'.$r[1].'"},';
					$tmp_string = trim($tmp_string,",");
					$string.= $tmp_string;
					$tmp_string.= ']}}';
				}
				echo $tmp_string;
				exit();
			break;
			
			
			case 'saveProfile':
			
			$str = "";
			
			$postUsername		= trim($_POST['postUsername']);
			$postcorreo1		= trim($_POST['postcorreo1']);
			$posttelefono1 		= trim($_POST['posttelefono1']);
			$postDescripcion 	= trim($_POST['postDescripcion']);
			
			if($postUsername != "")
			{
								
				$sql = "UPDATE t_participante SET 
						correo1 = '".$postcorreo1."',
						telefono1 = '".$posttelefono1 ."',
						descripcion = '".$postDescripcion ."'
					WHERE part_username = '".$postUsername."'";
					
				$str.= $postUsername." - ".$postPais." - ".$postCiudad." - ".$postDescripcion;				
					
				if(mysqli_query($link,$sql))
					echo $str;
				else
					echo "";			
			}
			else
			{
				echo "";
			}
				
			break;
			
			case 'salvarSugerencia':
				$fNombre = trim($_POST['fNombre']);
				$fNombre = str_replace("'","",$fNombre);
				
				$fSugerencia = trim($_POST['fSugerencia']);
				$fSugerencia = str_replace("'","",$fSugerencia);
				$fecha = time();
				
				$sql = "INSERT INTO uva_sugerencia (id,nombre,sugerencia,fecha) 
					VALUES ('','".$fNombre."','".$fSugerencia."','".$fecha."')";
					
				$destino = "usmpmobile@usmpvirtual.edu.pe";
				$asunto = "Nueva sugerencia - UVA Mobile";
				$subject = $fSugerencia."<br />De: El $fNombre <br />El d&iacute;a: ".$fecha;

				$cabeceras  = 'MIME-Version: 1.0' . "\r\n";
				$cabeceras .= 'Content-type: text/html; charset=UTF-8' . "\r\n";

				$cabeceras .= 'To: USMPMobile <'.$destino.'>' . "\r\n";
				$cabeceras .= 'From: Usuario <usmpvirtual@usmp.pe>' . "\r\n";

				mail($destino,$asunto,$subject,$cabeceras);

				if(mysqli_query($link,$sql))
					echo "1";
				else
					echo "";
				
			break;

			case 'admisionResults':

				$table_name = "resultados_admision";

				$codepos 	= "COD_POS";
				$ape_pat 	= "APE_PAT";
				$ape_mat 	= "APE_MAT";
				$nom 		= "NOM";
				$cod_sede 	= "COD_SED";
				$desc_sede 	= "DES_SED";
				$sem_ape 	= "SEM_APE";
				$cod_mod 	= "COD_MOD";
				$des_mod 	= "DES_MOD";
				$cod_esp 	= "COD_ESP";
				$des_esp 	= "DES_ESP";
				$pun_fin 	= "PUN_FIN";
				$ord_mer 	= "ORD_MER";
				$est_post 	= "EST_POST";
				$resultado 	= "RESULTADO";
				$fecha 		= "FECHA_MAT";
				$hora 		= "HORA";
				$lugar_matricula = "LUGAR_MAT";
				$foto 			= "FOTO";

				if(isset($_GET['username']))
				{
					$username = trim($_GET['username']);
					if($username != "")
					{

						$sql = "SELECT 
								$codepos, $ape_pat, $ape_mat, $nom,
								$cod_sede, $desc_sede, $sem_ape, $cod_mod,
								$des_mod, $cod_esp, $des_esp, $pun_fin, $ord_mer,
								$est_post, $resultado, $fecha, $hora, $lugar_matricula, $foto
								FROM $table_name WHERE $codepos = '$username' LIMIT 0,1";

						/*
						$result = mysqli_query($link, "SHOW COLUMNS FROM $table_name");
						if (!$result) {
						    echo 'Could not run query: ' . mysql_error();
						    exit;
						}
						if (mysqli_num_rows($result) > 0) {
						    while ($row = mysqli_fetch_assoc($result)) {
						        print_r($row);
						    }
						}
						exit();
						*/

						// echo $sql;
					
						$sql = mysqli_query($link,$sql);

						if( mysqli_num_rows($sql) > 0)
						{
							$r = mysqli_fetch_array($sql);

							$s_matricula = strip_tags( $r[17] );
							$s_matricula = trim( $s_matricula );
							$s_matricula = str_replace( "\n", "", $s_matricula );
							$s_matricula = str_replace( "\t", "", $s_matricula );

							$string = '
								{
									"admision":{
										"'.$codepos.'": "'.$r[0].'",
										"'.$ape_pat.'": "'.$r[1].'",
										"'.$ape_mat.'": "'.$r[2].'",
										"'.$nom.'": "'.$r[3].'",
										"'.$cod_sede.'": "'.$r[4].'",
										"'.$desc_sede.'": "'.$r[5].'",
										"'.$sem_ape.'": "'.$r[6].'",
										"'.$cod_mod.'": "'.$r[7].'",
										"'.$des_mod.'": "'.$r[8].'",
										"'.$cod_esp.'": "'.$r[9].'",
										"'.$des_esp.'": "'.$r[10].'",
										"'.$pun_fin.'": "'.$r[11].'",
										"'.$ord_mer.'": "'.$r[12].'",
										"'.$est_post.'": "'.$r[13].'",
										"'.$resultado.'": "'.$r[14].'",
										"'.$fecha.'": "'.$r[15].'",
										"'.$hora.'": "'.$r[16].'",
										"'.$lugar_matricula.'": "'.$s_matricula .'",
										"'.$foto.'": "'.$r[18].'"
								}
							}';
							echo $string;
							exit();
						}
						else
						{
							echo "ERROR_GETTING_ADMISION_RESULTS";
							exit();
						}
						mysqli_free_result($sql);					
					
					}
					else
					{
						echo "ERROR_GETTING_ADMISION_RESULTS";
						exit();
					}
				}
				else
				{
					echo "ERROR_GETTING_ADMISION_RESULTS";
					exit();
				}


			break;
			
			case 'uploadPhoto':
			
				$username = trim($_POST['username']);
				
				if($username != "")
				{

					$document_root = $_SERVER['DOCUMENT_ROOT'];
					$len_root = strlen($document_root) - 1;
					$last_char = substr($document_root, $len_root, $len_root);
					
					if( $last_char != "/" )
						$document_root = $document_root . "/";


					$file_name  = $_FILES["avatar"]["name"];
					$type_file  = $_FILES["avatar"]["type"];
					$tmp_file   = $_FILES["avatar"]["tmp_name"];
					$base_path  = $document_root."campus/_uploads/fotos/";
									
					$image_name = $username.".jpg";
					$path_foto  = $base_path . $image_name;	
					$foto_md    = "http://campusvirtual.usmp.edu.pe/_uploads/fotos/".$image_name;
					$foto_wd    = "campusvirtual.usmp.edu.pe/_uploads/fotos/".$image_name;

					//echo $file_name . "\n" . $type_file . "\n". $tmp_file . "\n" . $base_path . "\n" . $image_name . "\n" . $path_foto . "\n" .$foto_md . "\n" . $foto_wd;
					
echo "aqui llego";
die();
					
					if($file_name!=""){					



						if(file_exists($path_foto))
							unlink($path_foto);
						$msg = move_uploaded_file($tmp_file, $path_foto);
						update_foto($username,$foto_md,$foto_wd,$path_foto);
						
						if($msg != '1'){
							$msg = 400;
						}else
							$msg = 200;
						
						
					}
					else
					{
						if(file_exists($path_foto)){
							update_foto($username,$foto_md,$foto_wd,$path_foto);	
						}
					}
					/*echo $file_name;*/
					echo "Message name: " . $msg;
				}
				else
				{
				
					echo "null";
				}
			break;
			
			default:
				echo "null";
		}
	}
	else
	{
		echo "";
	}
	
}
else
{
	echo "";
}

function update_foto($username = "",$mdl_url = "", $wd_url = "", $path_foto)
{	
	//$server = '69.64.43.123';
	$server = '199.217.115.232';
	$user = 'uplataforma';
	$pwd = 'pPl@tform15';
	$link = mysqli_connect($server,$user,$pwd,"dbsgav");
	
		
	if($link == null)
		return;
				
	$q1 = "SELECT meta_value FROM t_meta_participante 
		WHERE fk_part_username = '".$username."' AND meta_field = 'MOODLE_FOTO_F1'";
	$q1 = mysqli_query($link,$q1);
	$query_mdl = "";
	if(mysqli_num_rows($q1) > 0)
	{
		$query_mdl = "UPDATE t_meta_participante SET meta_value = '".$mdl_url."' 
				WHERE fk_part_username = '".$username."' AND meta_field = 'MOODLE_FOTO_F1'";
	}
	else
	{
		$query_mdl = "INSERT INTO t_meta_participante 
			(fk_part_username,fk_codigo,meta_field,meta_value) 
			VALUES ('".$username."','0','MOODLE_FOTO_F1','".$mdl_url."')";
	}
		
	mysqli_query($link, $query_mdl);	
	mysqli_free_result($q1);
		
	mysqli_close($link);
	$link = null;	
	$link = mysqli_connect($server,$user,$pwd,"webdocente");
	
	
	$q2 = "SELECT id_docente FROM usuario WHERE usuario = '".$username."' LIMIT 0,1";
	
	
	$q2 = mysqli_query($link, $q2);
	if(mysqli_num_rows($q2) > 0)
	{
		$data = mysqli_fetch_row($q2);
		$id_docente = $data[0];
		$query_wd = "UPDATE docente SET nom_imagen = '".$wd_url."' 
			WHERE id_docente = '".$id_docente."';";
		mysqli_query($link, $query_wd);
	}
	mysqli_free_result($q2);

}

mysqli_close($link);
$link = null;

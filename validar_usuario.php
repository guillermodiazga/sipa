<?php
session_start();
//datos para establecer la conexion con la base de mysql.
include 'conexion.php';

function quitar($mensaje)
{
	$nopermitidos = array("'",'\\','<','>',"\"");
	$mensaje = str_replace($nopermitidos, "", $mensaje);
	return $mensaje;
}
if(trim($HTTP_POST_VARS["id"]) != "" && trim($HTTP_POST_VARS["password"]) != "")
{
	// Puedes utilizar la funcion para eliminar algun caracter en especifico
	//$usuario = strtolower(quitar($HTTP_POST_VARS["usuario"]));
	//$password = $HTTP_POST_VARS["password"];
	// o puedes convertir los a su entidad HTML aplicable con htmlentities
	$usuario = strtolower(htmlentities($HTTP_POST_VARS["id"], ENT_QUOTES));
	$password = $HTTP_POST_VARS["password"];
	$result = @mysql_query('SELECT password, bitactivo, id FROM usuarios WHERE id=\''.$id.'\'');
	if($row = @mysql_fetch_array($result)){
	
		if(($row["password"]) == $password){
		
		if($row["bitactivo"]==1){
			$_SESSION["k_username"] = $row['id'];
			//echo 'Has sido logueado correctamente '.$_SESSION['k_username'].' <p>';
			include ('cabeza.php');
			echo("Ingreso exitoso, ahora sera dirigido a la pagina principal.
			<SCRIPT LANGUAGE='javascript'>
			location.href = 'index.php';	
			</SCRIPT>");
			}else { echo "<script>alert('Usuario Bloqueado, Comuniquese con el Administrador')</script>"."<SCRIPT LANGUAGE='javascript'>
			location.href = 'index.php';</SCRIPT>";}
		}else{
		
		 include ('cabeza.php');
		 	 
			echo "</tr><td><script>alert('Password incorrecto')</script></td>";
		}
	}else{
	 include ('cabeza.php');
		echo "</tr><td><script>alert('Usuario no existente en la base de datos')</script></td>";
	}
	@mysql_free_result($result);
}else{
 include ('cabeza.php');
	echo "</tr><td><script>alert('Debe especificar un usuario y password')</script></td>";
}
@mysql_close();
?>
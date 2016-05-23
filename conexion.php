<?php
/*header('Access-Control-Allow-Origin: *');
//header('Access-Control-Allow-Methods: GET, POST'); 
*/

$servidor = "localhost";
$usuario="root";
$clave="admin";

/*

$servidor = "mysql.hostinger.co";
$usuario="u321971772_read";
$clave="sistemas123$$";
*/
$con = mysql_connect($servidor, $usuario, $clave);
mysql_set_charset('utf8');
$db= "sipa";
//$db= "u321971772_sipa";

If($con){
	echo "";
}else{
	echo "Fallo la Conexión a la Base de Datos";
}

mysql_select_db($db, $con);


?>
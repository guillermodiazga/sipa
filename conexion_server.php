<?php


/*
$servidor = "mysql.webcindario.com" ;
$usuario="sipa";
$clave="861224";


$servidor = "d200232347" ;
$usuario="Regular";
$clave="123";
*/

/*
$servidor = "127.0.0.1" ;
$usuario="root";
$clave="123";
*/

$servidor = "Localhost" ;
$usuario="tiamima";
$clave="GFJ(KZgTddbV";
//$clave="%n)J#Mz~s&dW";


$con = mysql_connect($servidor, $usuario, $clave);
$db= "tiamima_pedidos";
//$db= "sipa";
If($con){
echo "";}
else { echo "Fallo la Conexión a la Base de Datos";}

mysql_select_db($db, $con);


?>

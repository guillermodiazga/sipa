<?php session_start();
if (isset($_SESSION['sipa_username'])) {
include 'cabeza.php';
include 'conexion.php';
include 'funciones.php';

if($ideditar!='')
{
//Consulta de UPdate
$nombre=($_POST['nombre']);
$password=($_POST['password']);

if ($activo==on)
	$activo=1;
	else
	$activo=0;

 $sql=("UPDATE `usuario` SET `nombre` = '$nombre',movil='$celular',telefono='$telefono',oficina='$oficina',`password` = '$password',`oficina` = '$oficina',`idsecretaria` = '$dependencia',`mail` = '$correo' WHERE `usuario`.`id` ='$ideditar' ");
@mysql_query($sql);

echo ("<script> alert('Usuario ".($nombre)." modificado con exito!');javascript:history.go(-2)</script>");	



}else{

 $sql=("SELECT * FROM `usuario` WHERE `id` ='$idedit'");
 $result = @mysql_query($sql);
$row = @mysql_num_rows($result);

  while ($row = @mysql_fetch_array($result)){

$nombre=(($row["nombre"]));
$dependencia=($row["idsecretaria"]);
$password=(($row["password"]));
$email=($row["mail"]);
$oficina=($row["oficina"]);
$telefono=($row["telefono"]);
$celular=($row["movil"]);
$bitadmin=($row["bitadmin"]);
$bitactivo=($row["bitactivo"]);
$fecha=($row["fchcreacion"]);
}


?>
<table align='center' border='0' >
<tr bgcolor='#95b0c1'><tH align='center' colspan='4'><font face='Arial' size='4'>Modificar Usuario</th></tr>
<tr><td valign="top">
<font face='arial'>

<form name='log' action="alteruser.php" method="post" onsubmit="return validarformuser()">
<tr><td >C&eacute;dula: </td><td>
  <input name="idedit" size="10" disabled maxlength="11" value='<?echo number_format($idedit, 0, '', '.')?>'><br /></td></tr>
  <input type='hidden'name="ideditar" size="10" maxlength="11"  value='<?echo $idedit?>'><br /></td></tr>
  <tr><td >Nombre: </td><td>
  <input type="text" name="nombre" size="35" maxlength="30" value='<?echo $nombre?>'><br /></td></tr>
<?if($_SESSION["sipa_sec_username"]!=1000)
{?> 
 <tr><td >Secretaria: </td><td>
  <select name="dependencia">
  <?
  //consultar dependencias
 $sql=("SELECT * FROM `secretaria` WHERE `id`='$dependencia'");
  $result = @mysql_query($sql);
$rows = @mysql_num_rows($result);

  while ($rows = @mysql_fetch_array($result)){
 echo "<option value='".$dep=($rows['id'])."'>".($dep=($rows['secretaria']))."</option>";
 }
 //Demas dep
  $sq=("SELECT * FROM `secretaria`  WHERE bitactivo=1 ");
  $resulta = @mysql_query($sq);
$rows = @mysql_num_rows($resulta);

  while ($rows = @mysql_fetch_array($resulta)){
 echo "<option value='".$dep=($rows['id'])."'>".$rows['id']."-".($dep=($rows['secretaria']))."</option>";
}

?>
   
  </select><br /></td></tr>
  
  <tr><td >Oficina: </td><td>
  <input type="text" name="oficina" size="10" maxlength="30" value='<?echo $oficina?>'><br /></td></tr>
  <tr><td >Tel&eacute;fono: </td><td>
  <input type="text" name="telefono" size="10" maxlength="30" value='<?echo $telefono?>'><br /></td></tr>
  <tr><td >Celular: </td><td>
  <input type="text" name="celular" size="10" maxlength="30" value='<?echo $celular?>'><br /></td></tr>
  <?}else{?>
  <input type=hidden value=1000 name=dependencia>
   <?}//fin ocultar para tia mima?> 
  
<tr><td >Contrase&ntilde;a:</td><td>
<input type="password" name="password" size="10" maxlength="10" value=<?echo $password?>></td></tr>
<tr><td >Repita Contrase&ntilde;a: </td><td><input type="password" name="password2" size="10" maxlength="10" value=<?echo $password?>><br /></td></tr>
<tr><td >Email: </td><td>
<input type="text" name="correo" size="35" maxlength="40"  value=<?echo $email?>><br /></td></tr>
<tr><td >Fecha Creaci&oacute;n: </td><td>
<input type="text" name="fecha" size="20" maxlength="40" value='<?echo fch_mysql_php($fecha)?>' disabled	><br /></td></tr>


<input type='hidden' name='accion' value='Modificar Usuario'>
<tr><td></td><td><input type="submit" value="Guardar" ACCESSKEY="G" ><button onclick="javascript:history.back(1)"> Cancelar</button></td></tr>
</form>
</td></tr>

</table>
<?}
?>


<script> 

function index()
{
 location.href = 'index.php';	

}

//validar campos

</script>

<?php

include 'desconexion.php';
}else echo"No Autorizado'<SCRIPT LANGUAGE='javascript'>location.href = 'index.php';</SCRIPT>";
?>



<? session_start();
if (isset($_SESSION['sipa_username'])) {
include 'cabeza.php';
include 'funciones.php';
include 'conexion.php';

if($grabar)
{

$log=$_SESSION['sipa_id_username'];
$fecha=date('Y-m-d');
$nombre=($nombre);
$sql="SELECT * FROM `usuario` WHERE `id`=$idedit";
$result = @mysql_query($sql);
$row = @mysql_num_rows($result);
if($row==0){
$sql = "INSERT INTO `usuario` (
`id` ,
`nombre` ,
`password` ,
`idsecretaria` ,
`mail` ,
`log` ,
`oficina` ,
`telefono` ,
`movil` ,
`fchcreacion` ,
`bitactivo` 
)
 VALUES ('$idedit', '$nombre', '$password', '$dependencia', '$correo', '$log','$oficina' ,'$telefono' ,'$celular','$fecha', '1');"; 
mysql_query($sql) or die ('Error query'.$sql);
echo ("<script> alert('Usuario ".$nombre." Creado con exito!'); window.location='index.php';</script>");	
}else
{echo "<script> alert('Usuario ya existe')</script>";}
}

?>



<table align='center' border='1' rules=none>
<tr bgcolor='#95b0c1'><tH align='center' colspan='4'><font face='Arial' size='4'>Crear Usuario</th></tr>
<tr><td valign="top">
<font face='arial'>

<form name='log' action="newuser.php" method="post" onsubmit="return validarformusernew()">

<tr><td >C&eacute;dula: </td><td>
  <input name="idedit" size="10"  maxlength="11" ><br /></td></tr>
  <input type='hidden'name="ideditar" size="10" maxlength="11"><br /></td></tr>
  <tr><td >Nombre: </td><td>
  <input type="text" name="nombre" size="35" maxlength="30" ><br /></td></tr>
  <tr><td >Secretaria: </td><td>
  <select name="dependencia">
  <?
  //consultar secretarias
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
  
<tr><td >Contrase&ntilde;a:</td><td>
<input type="password" name="password" size="10" maxlength="10" value=<?echo $password?>></td></tr>
<tr><td >Repita Contrase&ntilde;a: </td><td><input type="password" name="password2" size="10" maxlength="10" value=<?echo $password?>><br /></td></tr>
<tr><td >Email: </td><td>
<input type="text" name="correo" size="35" maxlength="40"  value=<?echo $email?>><br /></td></tr>
<tr><td >Fecha Creaci&oacute;n: </td><td>
<input type="text" name="fecha" size="20" maxlength="40" value='<?echo $fecha=(date('d/m/Y'))?>' disabled	><br /></td></tr>


<input type='hidden' name='accion' value='Modificar Usuario'>
<tr><td></td><td><input type="submit" value="Guardar" name=grabar ACCESSKEY="G" ><input type='reset'  value='Cancelar' ACCESSKEY="C"></td></tr>
</form>
</td></tr>

</table>

<?
include 'desconexion.php';
}else{?>
<SCRIPT LANGUAGE="javascript"> location.href = "index.php";</SCRIPT>
<?}?>
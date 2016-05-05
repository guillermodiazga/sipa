<?session_start();
include "cabeza.php";
if (isset($_SESSION['sipa_username'])) {



include "conexion.php";
include "funciones.php";


?>

<body onLoad="muestraGranDiv()">
<div id="cargando" style="width: 357px; height: 100px; position: absolute; padding-top:20px; text-align:center; left: 2px;">
<div align="center"><br><br><br><br><br><br><strong>Por favor espere un momento... <img src='images/cargando.gif'></strong></div>
</div>
<div id="granDiv" style="visibility:hidden;">


<table valign='top' align='center' border='1'>

<tr bgcolor='#95b0c1'><tH align='' colspan='4'><font face='Arial' size='4'>Administrar Datos Maestros</th></tr>
<tr><td><br><?include "crearpptopersona.php";?></td></tr>
<td><br><?include "crearppto.php";?>
<table align= border=0>
<tr><td><br></td></tr>
<form action='creardatos.php' method=post>
<input type='hidden' name='accion'value='Crear Datos'>

<tr><th bgcolor='#d3dce3' colspan=4>Administrar Secretar&iacute;a</th></tr>
<tr><td>Id Secretar&iacute;a:</td><td><input name=id_secretaria></td>
<tr><td>Nombre :</td><td><input name=secretaria></td><td><input type=submit value=Grabar name=grabarsecretaria></td>
</form>
<td align=''><form action='creardatos.php' method='POST'>
<input type='hidden' name='accion'value='Crear Datos'>

<input size='10' type='submit' src='images/b_browse.png' height='<?echo $tamano ?>'width='<?echo $tamano ?>'  alt='Listar Todo' name='Listarsec' value='Ver Todo' /></td></form></tr>

<?
if($Listarsec){
?>
<td colspan=4><table>
<tr bgcolor='#e5e5e5'><th>Cod</th><th>Nombre</th><th>Activo</th><th>Acci&oacute;n</th></tr>
<?
$sql = 'SELECT * FROM `secretaria';
$result=@mysql_query($sql);
$row=@mysql_num_rows($result);

while($row=@mysql_fetch_array($result))
{?>
 <tr onmouseover="this.style.backgroundColor='<?echo  $colorOnMouseOver?>'" onmouseout="this.style.backgroundColor='eeeeee'"><td><?echo number_format($id=($row['id']), 0, '', '.');?></td><td><?echo ($row['secretaria']);?></td><td align=center><?if( $row['bitactivo']==1){echo 'Si';}else{echo 'No';};?></td>
 
 <td align=center>
<?if( $row['bitactivo']==1){?>
 
<form action=creardatos.php method='POST'> 
<input type=hidden value='Crear Datos' name='accion'> 
<input type=hidden value='Secretaria' name='tipo'> 
<input type=hidden name=idborrar value='<?echo $id ?>'> 
<input type=image src='images/Flag_redHS.png' alt='Inactivar'></form>
 
<?}else{?>
 
<form action=creardatos.php method='POST'> 
<input type=hidden value='Crear Datos' name='accion'>
<input type=hidden value='Secretaria' name='tipo'> 
 <input type=hidden name=idactivar value=<?echo $id ?>>
 <input type=image src='images/Flag_greenHS.png' alt='Activar'></form>
 
<?};?>
 </td>
 
 </tr>

 <?}?>
 <?}?>
 </table>
<table>
 <tr><td><br></td></tr>
<tr><td><br><?include "crearProducto.php";?></td></tr>
 <th colspan=2>Subir Imagen Productos</th>
 <tr><td><?include("subirImagen.php");?></td></tr>

</table>

<?


//Grabar Secretaria
if($grabarsecretaria and $secretaria!=''and $id_secretaria!='')
{
 $sql = "SELECT * FROM `secretaria` WHERE `id` = '$id_secretaria' "; 
$result=@mysql_query($sql);
$row=@mysql_num_rows($result);

if($row==0){
$sql="INSERT INTO `secretaria` (`id` ,`secretaria`)VALUES ($id_secretaria , '".($secretaria)."');";
mysql_query($sql);

echo "Se creo con exito la Secretar&iacute;a: ".$secretaria;
}else{
 $consulta="UPDATE `secretaria` SET `secretaria` = '".($secretaria)."' WHERE `secretaria`.`id` =$id_secretaria";
mysql_query($consulta);
 echo "<script>alert('Se Actualizo regitro $id_secretaria');</script>";
}
}

//borrar datos

if($idborrar!=''){
 if($tipo=="Secretaria")
{
$consulta="UPDATE `secretaria` SET `bitactivo` = '0' WHERE `secretaria`.`id` =$idborrar";
@mysql_query($consulta);
}
echo "<script>alert('$tipo inactivado')</script>";

}

//activar datos

if($idactivar!=''){
if($tipo=="Secretaria")
{
$consulta="UPDATE `secretaria` SET `bitactivo` = '1' WHERE `secretaria`.`id` =$idactivar";
@mysql_query($consulta);
}
echo "<script>alert('$tipo activado')</script>";
}


include "desconexion.php";
?>

<?}else echo"No Autorizado'<SCRIPT LANGUAGE='javascript'>location.href = 'index.php';</SCRIPT>";
?>



  </div>
</body> 
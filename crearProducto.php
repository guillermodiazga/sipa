<script>
var ar_datosProd = new Array();
</script>

<table border=0>
<form action='creardatos.php' method=post>
<input type='hidden' name='accion'value='Crear Datos'>

<tr><th bgcolor='#d3dce3' colspan=5>Crear Producto</th></tr>
<tr><td rowspan=8><input name=foto  disabled type=image src='images/alimentos/0.jpg' border=2 width=200></td></tr>
<tr><td>Cod:</td><td><input name=id_alimento   onchange="cargaProducto(document.getElementById('id_alimento').value);" size=3 maxlength=3 ></td>
<tr><td>Nombre:</td><td><input name=Producto size=50 maxlength=50></td></tr>
<tr><td>Descripcion:</td><td><textarea name=descripcion  rows='7' cols='40'></textarea></td></tr>
<td>Tipo:</td><td><input name=idtalimento read only value=1 size=1></td>
<tr><td>IVA:</td><td><input name=iva size=2 maxlength=2 ></td><tr>
<td >Valor:</td><td ><input name=valorProd size=6 maxlength=6 ></td></tr>

<tr><td></td><td></td><td><table><tr><td><input type=submit value=Grabar name=grabarProducto></td>

</form>
<td align=''><form action='creardatos.php' method='POST'>
<input type='hidden' name='accion'value='Crear Datos'>

<input size='10' type='submit' src='images/b_browse.png' height='<?echo $tamano ?>'width='<?echo $tamano ?>'  alt='Listar Todo' name='ListarProducto' value='Ver Todo'  />
</td></form></tr>
</table></td></table>
<table>
<?
if($ListarProducto){
?>
<td colspan=4><table>
<tr bgcolor='#e5e5e5'><th>Cod</th><th>Nombre</th><th>Activo</th><th>Acci&oacute;n</th></tr>
<?
$sql = 'SELECT * FROM `alimento';
$result=@mysql_query($sql);
$row=@mysql_num_rows($result);
?>

<?
while($row=@mysql_fetch_array($result))
{
//llevar vector 
?>
<script>
//declarar subvector
ar_datosProd[<?echo $row['id']?>] =new Array();

//asignar datos a vector
ar_datosProd[<?echo $row['id']?>]["Producto"]="<?echo ($row['nombre']);?>";
ar_datosProd[<?echo $row['id']?>]["descripcion"]="<?echo ($row['descripcion']);?>";
ar_datosProd[<?echo $row['id']?>]["idtalimento"]="<?echo ($row['idtalimento']);?>";
ar_datosProd[<?echo $row['id']?>]["valorProd"]="<?echo $row['valor'];?>";
ar_datosProd[<?echo $row['id']?>]["iva"]="<?echo ($row['iva']);?>";
</script>
<?

?>
 <tr onclick="cargaProducto(<?echo $row['id']?>);" onmouseover="this.style.backgroundColor='<?echo  $colorOnMouseOver?>'" onmouseout="this.style.backgroundColor='eeeeee'"><td><?echo number_format($id=($row['id']), 0, '', '.');?><img src='images/alimentos/<?echo $id?>.jpg' width=60></td><td><?echo ($row['nombre']);?></td><td align=center><?if( $row['bitactivo']==1){echo 'Si';}else{echo 'No';};?></td>
 
 <td align=center>
<?if( $row['bitactivo']==1){?>
 
<form action=creardatos.php method='POST'> 
<input type=hidden value='Crear Datos' name='accion'> 
<input type=hidden value='Producto' name='tipo'> 
<input type=hidden name=idborrar value='<?echo $id ?>'> 
<input type=image src='images/Flag_redHS.png' alt='Inactivar'></form>
 
<?}else{?>
 
<form action=creardatos.php method='POST'> 
<input type=hidden value='Crear Datos' name='accion'>
<input type=hidden value='Producto' name='tipo'> 
 <input type=hidden name=idactivar value=<?echo $id ?>>
 <input type=image src='images/Flag_greenHS.png' alt='Activar'></form>
 
<?};?>
 </td>
 
 </tr>

 <?}?>
 <?}?>
<tr><td><br></td></tr>
</table>

<?

//Grabar 
if($grabarProducto and $Producto!=''and $id_alimento!='')
{
 $sql = "SELECT * 
FROM alimento
WHERE id = $id_alimento"; 
$result=@mysql_query($sql);
$row=@mysql_num_rows($result);

if($row==0){
 echo $sql="INSERT INTO  `alimento` (

`id` ,
`nombre` ,
`descripcion` ,
`idtalimento` ,
`valor` ,
`iva` ,
`bitactivo`
)($id_alimento , '".($Producto)."','".($descripcion)."','$idtalimento',$valor,$iva,null);";
mysql_query($sql) or die ('Error query');

echo "Se creo con exito la Secretar&iacute;a: ".$Producto;
}else{
  $consulta="
 UPDATE  `alimento` SET  `nombre` =  '".($Producto)."',
`descripcion` =  '".($descripcion)."',
`idtalimento` =  '$idtalimento',
`valor` =  '$valorProd',
`iva` =  '$iva' WHERE  `alimento`.`id` =$id_alimento
 ";
mysql_query($consulta) or die (`Error query`);
 echo "<script>alert('Se Actualizo regitro $id_alimento');</script>";
}
}

//borrar datos

if($idborrar!=''){
 if($tipo=="Producto")
{
 $consulta="UPDATE  `tiamima_pedidos`.`alimento` SET  `bitactivo` =  '0' WHERE  `alimento`.`id` =$idborrar";
@mysql_query($consulta) or die ('Error query');
}
echo "<script>alert('$tipo inactivado')</script>";

}

//activar datos

if($idactivar!=''){
if($tipo=="Producto")
{
 $consulta="UPDATE  `tiamima_pedidos`.`alimento` SET  `bitactivo` =  '1' WHERE  `alimento`.`id` =$idactivar";
@mysql_query($consulta)or die ('Error query');
}
echo "<script>alert('$tipo activado')</script>";
}?>

<script>
function cargaProducto(id){
if(id!='' && ar_datosProd && id<ar_datosProd.length){
document.getElementById('id_alimento').value =id;
document.getElementById('Producto').value =ar_datosProd[id]["Producto"];
document.getElementById('descripcion').value =ar_datosProd[id]["descripcion"];
document.getElementById('idtalimento').value =ar_datosProd[id]["idtalimento"];
document.getElementById('valorProd').value =ar_datosProd[id]["valorProd"];
document.getElementById('iva').value =ar_datosProd[id]["iva"];
document.getElementById('foto').src='images/alimentos/'+id+'.jpg'
}else if (ar_datosProd && id>=ar_datosProd.length){
document.getElementById('id_alimento').value ="";
document.getElementById('Producto').value ="";
document.getElementById('descripcion').value ="";
document.getElementById('idtalimento').value ="";
document.getElementById('valorProd').value ="";
document.getElementById('iva').value ="";
document.getElementById('foto').src='images/alimentos/0.jpg'
}
}
</script>
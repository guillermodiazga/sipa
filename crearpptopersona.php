 <table border=0 > <form action=creardatos.php onsubmit='return validarperppto()'>
<tr><th colspan=2>Asignar Ppto a Usuario</th></tr>
<tr><th>Usuario</th><th>Presupuesto</th></tr>
<tr><td></td><td></td><td></td><td></td><td></td><td></td></tr>
<td><Select name=us>
<?
  $sq=("SELECT id, nombre, idsecretaria FROM `usuario`  WHERE bitactivo=1 and idsecretaria!=1000 order by idsecretaria,id");
  $resulta = @mysql_query($sq);
$rows = @mysql_num_rows($resulta);

  while ($rows = @mysql_fetch_array($resulta)){
 echo "<option value='".($rows['id'])."'>Sec: ".$rows['idsecretaria']." - ".number_format(($rows['id']), 0, '', '.')." - ".($rows['nombre'])."</option>";
}?></Select></td>

<td><Select name=ppto>
<?
  $sq=("SELECT * FROM `presupuesto`  WHERE bitactivo=1 order by idsecretaria ,id");
  $resulta = @mysql_query($sq);
$rows = @mysql_num_rows($resulta);

  while ($rows = @mysql_fetch_array($resulta)){
 echo "<option value='".($rows['id'])."'>Sec: ".$rows['idsecretaria']." - ".$rows['id']."-".($rows['nombre'])."</option>";
}?></Select></td>

<tr><td align=right><input type=submit value=Grabar name=grabarperppto></td><td>
</form><br><form action=creardatos.php><input size='10' type='submit'  name='listarpersonappto' value='Ver Todo'></form>
</td></tr>

</table>

<?
//Insertar registro
if($grabarperppto)
{

//validar si ya existe
$val=true;

$sql="SELECT `id` FROM `persona-ppto` WHERE `idusuario`='$us' and `idppto`='$ppto' and bitactivo=1";
$resultado = mysql_query($sql) or die('Error query de validaci&oacute;n');
$row = mysql_num_rows($resultado);

while ($row = mysql_fetch_array($resultado)) 
{
if($row['id']!='')
echo $val=false;
}

if ($val)
{
$sql=("INSERT INTO `persona-ppto` (
`id` ,
`idusuario` ,
`idppto` ,
`bitactivo`
)
VALUES (
'null', '$us', '$ppto', '1'
);");


mysql_query($sql) or die('Error query');;

echo $imgaviso2."Registro Alamacenado".$idppto;
}else{
echo $imgaviso."Registro ya Existe!";
}
}

//listar ppto

if($listarpersonappto)
{
$sql="SELECT us.idsecretaria, ppto.idsecretaria as pptosec, ppto.nombre as nomppto, perppto.id, perppto.idusuario, perppto.idppto, us.nombre
 FROM `persona-ppto` as perppto, usuario as us, presupuesto as ppto
 WHERE perppto.`bitactivo`=1 and
us.id=perppto.idusuario and
perppto.idppto = ppto.id
ORDER BY us.idsecretaria, perppto.idusuario, perppto.idppto
 ";
$result=mysql_query($sql);
$rows = @mysql_num_rows($result);
?>
 <table	rules=cols> 
<tr><th>Secretar&iacute;a</th><th>id Usuario</th><th>Nombre Usuario</th><th>Id Proyecto</th><th>Nombre Proyecto</th><th>Sec. Proyecto</th><th>Acci&oacute;n</th></tr><?
 while ($row = @mysql_fetch_array($result)){
 
 if($secre!=$row['idsecretaria'])
echo "<tr bgcolor=ffffff><td colspan=12><hr></td></tr>";
echo "<tr><td colspan=12><hr></td></tr>";
?>
<form action=creardatos.php >





<tr onmouseover="this.style.backgroundColor='<?echo  $colorOnMouseOver?>'" onmouseout="this.style.backgroundColor='#eeeeee'">
<input value='<? echo $row['id']?>' name=borrarreg type=hidden>
<td align=center><? echo $row['idsecretaria']?></td>
<td><? echo number_format(($row['idusuario']), 0, '', '.')?></td>
<td><? echo ($row['nombre'])?></td>
<td><? echo ($row['idppto'])?></td>
<td><? echo ($row['nomppto'])?></td>
<td align=center><? echo ($row['pptosec'])?></td>
<td align=center><input type=image src='images/b_drop.png' alt='Borrar'></form></td></tr>

<?
$secre=$row['idsecretaria'];
}
?></table>
<?
}//fin listar ppto

//update de ppto



//Inactivar ppto

if($borrarreg){
  $sql=("UPDATE `persona-ppto` SET 
`bitactivo` = 0 WHERE id= '$borrarreg' LIMIT 1 ;");

mysql_query($sql) or die ("Error Query");

echo $imgaviso2."Registro ".$borrarreg." Borrado	";
}

?>


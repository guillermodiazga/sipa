<?
//Asignar valores anteriores
$_SESSION["lastContrato"]=$contrato;
$_SESSION["lastCompromiso"]=$compromiso;
?>

 <table border=0 > <form action=creardatos.php > <?//onsubmit='return validarppto()'?>
<tr><th colspan=8>Administrar Ppto</th></tr>
<tr><th>Secretar&iacute;a</th><th>Proveedor</th><th>Tipo</th><th>Contrato</th><th>Proyecto</th><th>Compromiso</th><th>Nombre Proyecto</th><th>Valor</th></tr>
<tr><td><Select name=secretaria>
<?
  $sq=("SELECT * FROM `secretaria`  WHERE bitactivo=1 ");
  $resulta = @mysql_query($sq);
$rows = @mysql_num_rows($resulta);

  while ($rows = @mysql_fetch_array($resulta)){
 echo "<option value='".$dep=($rows['id'])."'>".$rows['id']."-".($dep=($rows['secretaria']))."</option>";
}?></Select></td>

<td><Select name=proveedor>
<?
  $sq=("SELECT * FROM `proveedor`  WHERE bitactivo=1 ");
  $resulta = @mysql_query($sq);
$rows = @mysql_num_rows($resulta);

  while ($rows = @mysql_fetch_array($resulta)){
 echo "<option value='".$dep=($rows['id'])."'>".$rows['id']."-".($dep=($rows['proveedor']))."</option>";
}?></Select></td>
<td>
  <select name="idTalimento">
    <option value="1">Refrigerio</option>
    <option value="2">Almuerzo</option>
    </select>
</td>
<td><input name=contrato size=10 maxlength="10" value="<?echo $_SESSION["lastContrato"]?>"></td>
<td><input name=proyecto size=6 maxlength="6"></td>
<td><input name=compromiso size=11 maxlength="11" value="<?echo $_SESSION["lastCompromiso"]?>"></td>
<td><input name=nombre size=30 maxlength="30"></td>
<td>$<input name=valor size=11 maxlength="11"></td>
</tr>
<tr><td align=right><input type=submit value=Grabar name=grabarppto></td><td>
</form><br><form action=creardatos.php>

<Select name=filtroContrato >
<option value="*">*</option>
<?
  $sq=("SELECT distinct contrato FROM `presupuesto`  WHERE bitactivo=1 ");
  $resulta = @mysql_query($sq);
$rows = @mysql_num_rows($resulta);

  while ($rows = @mysql_fetch_array($resulta)){
 echo "<option value='".($rows['contrato'])."'>".$rows['contrato']."</option>";
}?>
</Select>
<input size='10' type='submit'  name='listarppto' value='Ver Todo'></form>
</td></tr>

</table>

<?
//Insertar PPTO
$idppto=trim($proyecto)."-".trim($compromiso);

if($grabarppto)
{
 $sql=("INSERT INTO `presupuesto` (
`id` ,
`contrato` ,
`proyecto` ,
`pedido` ,
`nombre` ,
`idsecretaria` ,
`valorini` ,
`valorpedido` ,
`valorpagado` ,
`idproveedor` ,
`idtalimento` ,
`bitactivo`
)
VALUES (
'".trim($idppto)."', '".trim($contrato)."','".trim($proyecto)."','".trim($compromiso)."','$nombre', $secretaria, $valor, '0', '0', '$proveedor', $idTalimento,'1'
);");


mysql_query($sql) or die('Error query: Registro ya Existe.');;

echo $imgaviso2."Registro Alamacenado ".$idppto;


?>
<?
}

//listar ppto

if($listarppto)
{
	if($filtroContrato=="*")
		$sql=("SELECT presupuesto.*,tipoalimento.talimento FROM `presupuesto`, tipoalimento WHERE presupuesto.idtalimento=tipoalimento.id and presupuesto.`bitactivo`=1  order by presupuesto.idsecretaria, presupuesto.idproveedor");
	else
		$sql=("SELECT presupuesto.*,tipoalimento.talimento FROM `presupuesto`, tipoalimento WHERE presupuesto.idtalimento=tipoalimento.id and presupuesto.`bitactivo`=1  and contrato=$filtroContrato order by idsecretaria, idproveedor");

		$result=mysql_query($sql);
$rows = @mysql_num_rows($result);
?>
 <table	> 
<tr><th>Secretar&iacute;a</th><th>Contrato</th><th>Tipo</th><th>Proyecto-Compromiso</th><th>Nombre Proyecto</th><th>Valor Pedido</th><th>Valor</th><th>Vlr. No Requerido</th><th>Observaci&oacute;n</th><th colspan=2>Acci&oacute;n</th></tr><?
 while ($row = @mysql_fetch_array($result)){
?>
<form action=creardatos.php >
<tr onmouseover="this.style.backgroundColor='<?echo  $colorOnMouseOver?>'" onmouseout="this.style.backgroundColor='#eeeeee'">
<td><Select name=secretaria disabled>
<?
  $sq=("SELECT * FROM `secretaria`  WHERE id=".$row['idsecretaria']);
  $resulta = @mysql_query($sq);
$rows = @mysql_num_rows($resulta);


  while ($rows = @mysql_fetch_array($resulta)){
 echo "<option value='".$dep=($rows['id'])."'>".$rows['id']."</option>";
}?></Select></td>

<td><input disabled name=contrato size=10  value='<?echo $row['contrato']?>'>
</td>
<td><input disabled name=talimento size=10  value='<?echo $row['talimento']?>'>
</td>
<input type=hidden name=proyecto size=16  value='<?echo $row['id']?>'>
<td><input  size=16  disabled value='<?echo $row['id']?>'>
<td><input name=nombre size=30 maxlength="30"  value='<?echo ($row['nombre'])?>'></td>

<input name=valorpedido size=11 type="hidden"  value='<?echo $row['valorpedido']?>'>
<td><input name=valor size=11 maxlength="11" disabled value='$<?echo number_format(($row['valorpedido']), 0, '', '.') ?>'></td>
<td>$<input name=valor size=11 maxlength="11"  value='<?echo $row['valorini']?>'></td>
<td>$<input name=valorNoRequerido size=11 maxlength="11"  value='<?echo $row['valorNoRequerido']?>'></td>
<td><input name="observacion<?echo $row['id'] ?>" size=15 maxlength="60"  value='<?echo $row['observacion'];?>' onclick="getObservacion(obs, 'observacion<?echo $row['id'] ?>');"></td>

<input name=grabarmodppto type=hidden value=true>
<td align=right><input type='IMAGE' src='images\save.png' value=grabar name=grabar height='<?echo $tamano ?>'width='<?echo $tamano ?>'  alt='Grabar'></td><td>
</form><br>
<form action=creardatos.php>
<input name=borrarppto type=hidden value=true>
<input type=hidden name=proyecto value='<?echo $row['id']?>'>
<input size='10' type='IMAGE' src='images/b_drop.png' height='<?echo $tamano ?>'width='<?echo $tamano ?>'  alt='Borrar'  />
</form>
</td>
</tr><tr><td colspan=10><HR></td></tr>


<?
}
?></table>
<?
}//fin listar ppto

//update de ppto

if($grabarmodppto){
if($valor>=$valorpedido){

$obs="observacion".$proyecto;
$observacion = $_GET[$obs];

 $sql=("UPDATE `presupuesto` SET `nombre` = '$nombre', observacion='$observacion', valorNoRequerido='$valorNoRequerido' ,
`valorini` = '$valor' WHERE CONVERT( `presupuesto`.`id` USING utf8 ) = '$proyecto' LIMIT 1 ;");

mysql_query($sql) or die ("Error Query");

echo $imgaviso2."Registro ".$proyecto." Actualizado	";
$_SESSION["lastObservacion"]=$observacion;
}else{
echo("<script>alert('Error: Presupuesto no puede ser menor que el valor pedido!');</script>");
}
}

//Inactivar ppto

if($borrarppto){
  $sql=("UPDATE `presupuesto` SET 
`bitactivo` = 0 WHERE CONVERT( `presupuesto`.`id` USING utf8 ) = '$proyecto' LIMIT 1 ;");

mysql_query($sql) or die ("Error Query");

echo $imgaviso2."Registro ".$proyecto." Borrado	";
}


?><script>
var obs="<?echo $_SESSION["lastObservacion"]?>";

function getObservacion(obs, input){
if(obs!='')
	document.getElementById(input).value =obs;
	
}

</script>
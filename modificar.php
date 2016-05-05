<?
session_start();
if (isset($_SESSION['sipa_username'])) {
include "conexion.php";

include "cabeza.php";
include  "funciones.php";
?>
<head>

<script src="scw.js" type="text/javascript" language="JavaScript1.5"></script>
</head>

<body onLoad="muestraGranDiv()" >
<div id="cargando" style="width: 357px; height: 100px; position: absolute; padding-top:20px; text-align:center; left: 2px;">
<div align="center"><br><br><br><strong>Por favor espere un momento... <img src='images/cargando.gif'></strong></div>
</div>
<div id="granDiv" style="visibility:hidden;">

<?



if($borrar=='1')
{
include "conexion.php";
//Actualiza estado del pedido
$sql = 'UPDATE `pedido` SET `bitactivo` =0, estado=1 WHERE `id` ='.$idborrar.' LIMIT 1;'; 
mysql_query($sql) or die("Error en el Query de update");

//Grabar historico de estados
$idus=$_SESSION["sipa_id_username"];
$log=date("d/m/Y - G:i")." user: ".$idus;
$sql="INSERT INTO `historico_estados_ped` (
`id` ,
`pedido` ,
`newestado` ,
`comentario` ,
`log`
)
VALUES (
NULL , $idborrar, '1', 'Anulado', '$log');";

mysql_query($sql) or die ("Error en grabar el cambio de estado a anulado.");


//reduce el ppto
$sql="UPDATE `presupuesto` SET valorpedido = valorpedido-$valorped WHERE CONVERT( `presupuesto`.`id` USING utf8 ) = '$idppto'";
mysql_query($sql) or die ("No se pudo actualizar ppto de borrado");

//Enviar mail
mail($mailproveedor,"Pedido $idborrar Anulado","El Pedido $idborrar fue anulado");


echo"<script>location.href = 'index.php';</script>";
}

  if(($_SESSION['sipa_admin'])!=2 ){
?>



<TABLE border = 0 align="center" >
<form action='modificar.php' method="get">
<tr><th colspan=3>Modificar Pedido</th></tr>
<tr><td>Pedido:</td><td><input name=vbuscado size=5 /></td>
<td><input type="submit" value='Buscar' name='buscar'/></td></tr>

</form><br>

</table>
<? 
}
if ($vbuscado!='') {

include "conexion.php";
  $sql=("SELECT us.nombre, ped.idusuario,ped.valoradic, est.estado as estad, est.id as idestad, ped.evento, ped.personarecibe, ped.telfjorecibe, ped.movilrecibe, ped.fchentrega, ped.hora, ped.cantidad, ped.direccion, ped.idtalimento, ped.valorpedido, ped.idppto, ped.comentario, tip.talimento, ali.id as iditem, ali.nombre as item, ped.idalimento
 
 FROM pedido as ped, tipoalimento  as tip , usuario as us, alimento as ali, estados as est

 WHERE 
`ped`.`id` = $vbuscado
and us.id=idusuario and
ped.idtalimento=tip.id and
ped.idalimento=ali.id and
est.id=ped.estado
"); 


 $result = mysql_query($sql) or die('Query failed: ' . mysql_error() . "<br />\n$sql");  
$row = mysql_num_rows($result);


while ($row = mysql_fetch_array($result)){

  if(($_SESSION['sipa_admin'])!=1 ){
  
  if(($row["idusuario"])!=($_SESSION["sipa_id_username"])){
  echo "<SCRIPT > alert('No autorizado para modificar el pedido $vbuscado');</SCRIPT>"; 
  echo "<SCRIPT > location.href = 'index.php';</SCRIPT>"; 

  }
  }

$nombre=(($row["nombre"]));
$cantidad=(($row["cantidad"]));
$idppto=(($row["idppto"]));
$iditem=(($row["iditem"]));
$evento=(($row["evento"]));
$direccion=(($row["direccion"]));
$personarecibe=(($row["personarecibe"]));
$telfjorecibe=(($row["telfjorecibe"]));
$movilrecibe=(($row["movilrecibe"]));
$valorpedido=(($row["valorpedido"]));
$vadic=(($row["valoradic"]));
$item=(($row["item"]));
$fecha=fch_mysql_php(($row["fchentrega"]));
$hora=($row["hora"]);
$idtalimento=($row["idtalimento"]);
$talimento=($row["talimento"]);
$comentario=($row["comentario"]);
$idestad=($row["idestad"]);
$estad=($row["estad"]);

}
if($nombre!=''){
?>
<form action="modificar.php" method="POST" onsubmit="return validarform2();" >


<TABLE border = 1 align="center" RULES=none>

<tr><th colspan=3>

 Pedido: <?echo $vbuscado;?> Estado: <?echo $estad;?><input type=hidden value=<?echo $vbuscado;?> name=cons>
 Acci&oacute;n: <a href='#' >	<img name=borrar onclick="confBorrado('<?echo $vbuscado;?>', '<?echo $idppto;?>','<?echo $valorpedido;?>');" src='images/b_drop.png' border=0 alt='Anular pedido <?echo $vbuscado;?>' ></a>
 </th></tr>

<tr><td>Nombre Usuario:</td><td>
<input size="37" disabled name="nombre" value='<?echo $nombre;?>'>
</td>
<td rowspan=4 valign=bottom>
<input type=button value='Ver Catalogo' onclick="$('#catalogo, .dvStopUser').toggle()">
</td>

</tr>

<input value='<?echo $idppto;?>' size=16 name=ppto type=hidden>


<?//Consultar saldo antes y despues de la modificaci&oacute;n

$sql="SELECT valorini, valorpedido FROM `presupuesto` WHERE `id`='$idppto'";
$result = @mysql_query($sql);
$row = @mysql_num_rows($result);

  while ($row = @mysql_fetch_array($result))
{
 $saldoactual=(($row['valorini'])-(($row['valorpedido'])));
 } ?>
 
<input value='<?echo $saldoactual;?>' size=16 name=saldoactual type=hidden>
<tr><td>Presupuesto:</td><td><input value='<?echo $idppto." Saldo: $".number_format($saldoactual, 0, '', '.');?>' size=37 disabled ></td></tr>

<input value='<?echo $valorpedido?>' size=5 name=valorpedidoini type=hidden>
<tr><td>Valor Pedido:</td><td><input name=vped value='$<?echo number_format(($valorpedido-$vadic), 0, '', '.');?>' size=20 disabled>
Valor Adicional (IVA Incluido):
<input name=vadic size=10 maxlength=8  value=<?echo $vadic?> style="color:#FE2E2E;" > 
</td></tr>


<tr><td>Item:</td><td>
<select size="1" name="alimento" tabindex="1" onChange='vtotalped(); ver_detalle_alimento(); '>

<option value='<?echo $iditem;?>'><?echo $item;?></option>

<? 
$sql="SELECT * FROM `alimento` WHERE `bitactivo`=1 and idtalimento=$idtalimento ORDER BY `id` ASC";
$result = @mysql_query($sql);
$row = @mysql_num_rows($result);
    
$i=0;
?>
<script> 
matriz = new Array (); 
matriz2 = new Array (); 
matriz3 = new Array (); 
</script> 

<?
  while ($row = @mysql_fetch_array($result))
{
?>
<option value='<?echo $row['id']?>'><?echo $row['id']." - ".($row['nombre'])."  $".number_format($row['valor'], 0, '', '.')." Antes de IVA"?></option>
<?if(($row['id'])==$iditem)
$descripcion=($row['descripcion']);?>
<script> 
matriz[<?echo  $row['id']; ?>] = ('<? echo ($row['descripcion']); ?>') ; 
matriz2[<?echo  $row['id']; ?>] = ('<? echo $row['valor']; ?>') ; 
matriz3[<?echo  $row['id']; ?>] = ('<? echo $row['iva']; ?>') ; 

</script> 


<?
$i++;
}?>	

</select></td></tr>

<tr><td>Descripci&oacute;n:</td><td>

<textarea rows='4' cols='60' name=descripcion  style="color:#0B0B61; background-color:#eeeeee;" readonly tabindex = "-1" >
<?echo $descripcion?></textarea>

</td>

<td rowspan=2>
<input name=foto  disabled type=image src='images/alimentos/<?echo $iditem;?>.png' border=2 width=140 height=120 >
</td>

<input value='<?echo $cantidad;?>' size=5 name=cantidadini type=hidden>
<tr><td>Observaci&oacute;n:</td><td><textarea name="comentario" tabindex="2" onmouseout="this.disabled();" rows='4' cols='60'><?echo ($comentario);?></textarea></td></tr>
<tr><td>Cantidad:</td><td>

<select name="cantidad" onchange="vtotalped();" tabindex = "2" >

<option value=<?echo $cantidad;?>><?echo $cantidad;?></option><?
$i=1;
while ($i<10000){

echo"<option value=".$i.">".$i."</option>";
$i++;}
?>
</select>

</td></tr>

<tr><td>Fecha Entrega:</td><td><input tabindex="3" maxlength=10 onMouseOver='scwShow(this,event);' id="id_calendario" value='<?echo $fecha?>'  name="fecha" size="10"  value = <?php echo $hoy?>></td></tr>
<tr><td>Hora Entrega:</td><td><input tabindex="4" value='<?echo $hora;?>' size=5 name=horaentrega></td></tr>


<tr><td>Nombre del Evento:</td><td><input tabindex="5" value='<?echo $evento;?>' name="evento" size="35" maxlength=50 tabindex = "7"></td></tr>
<tr><td>Direcci&oacute;n de Entrega:</td><td><input tabindex="6" value='<?echo $direccion;?>' size=100 maxlength=150  name=direccion></td></tr>
<tr><td>Nombre Persona que Recibe:</td><td><input  tabindex="7" value='<?echo $personarecibe;?>' name="personarecibe" size="35" maxlength=35 tabindex = "8"></td></tr>
<tr><td>Telefono Fijo:</td><td><input value='<?echo $telfjorecibe;?>'  tabindex="8" name="telfjorecibe" size="35" maxlength=10 tabindex = "9"></td></tr>
<tr><td>Telefono Movil:</td><td><input value='<?echo $movilrecibe;?>' tabindex="9" name="movilrecibe" size="35" maxlength=10 tabindex = "10"></td></tr>

<tr> <td></td><td colspan ="2"><input tabindex="11" value="Grabar" onclick="return conf();" name='grabar' type="submit">
<input type="reset" value="Cancelar" tabindex="12" name="cancelar"></td></tr>

</table>
</form>
<?}else{}

if(($_SESSION['sipa_admin'])!=1 ){
if(($idestad==2)||($idestad==4)||($idestad==7)){}else{echo "<script>deshabilitar();</script></tr></table><br>".$imgaviso."No existen datos, o el estado del pedido no admite modificaciones.";
}
}

if(($idestad==5)||($idestad==1)){echo "<script>deshabilitar();</script></tr></table><br>".$imgaviso."No existen datos, o el estado del pedido no admite modificaciones.";
}

} //fin buscar?>
<?
//Actualizar datos
if($grabar)
{

//Preparar datos
$comentario=($comentario);
$log=date("d/m/Y - G:i");
$fecha=fch_php_mysql($fecha);
$evento=($evento);
$personarecibe=($personarecibe);
$direccion=($direccion);
$telfjorecibe=($telfjorecibe);
$movilrecibe=($movilrecibe);


include 'conexion.php';
//calcular el valor total del pedido
$sql="SELECT valor, iva  FROM `alimento` WHERE `id`=$alimento";
$result = @mysql_query($sql);
$row = @mysql_num_rows($result);

  while ($row = @mysql_fetch_array($result))
{
$vindividual=$row['valor'];
$iva=$row['iva'];}

$siniva=($vindividual*$cantidad);
$valorpedido=($siniva+($siniva*($iva/100))+$vadic);
echo "<script>Alert('Valor Pedido '+$valorpedido)</script>";


//Validaci&oacute;n por PHP

$val=true;

if(($fecha=='')||($horaentrega=='')||($cantidad==''))
$val=false;


//si el valor nuevo es inferior, no se consulta el ppto 
if($valorpedido<=$valorpedidoini)
{// echo "<script>alert('Pedido modificado por menor o igual valor: antes: $valorpedidoini actual: $valorpedido')</script>";
}else{

//Consultar saldo antes y despues de la modificaci&oacute;n

 
 $saldonew=($saldoactual+$valorpedidoini);
/* echo "<script>alert('valor pedido '+$valorpedido)</script>";
 echo "<script>alert('valor saldoactual '+$saldoactual)</script>";
 echo "<script>alert('valor saldonew '+$saldonew)</script>";
*/

//Consultar si alcanza el ppto

if($valorpedido>$saldonew)
{$val=false;

$superado=number_format((($valorpedido-($saldonew))), 0, '', '.');
echo "<img src=images/aviso.png> Error: Presupuesto superado!<script>alert('Error: Presupuesto  superado en $".$superado."')</script><br>";
echo "<script> window.location.href='javascript:history.go(-1)';</script>";
}

}


if ($val)
{

if($vadic=='')
$vadic==0;

include "conexion.php";
$sql=("
UPDATE `pedido` SET 
`fchentrega` =  '$fecha',
`hora` = '$horaentrega',
`direccion`='$direccion',
`evento`='$evento',
`personarecibe`='$personarecibe',
`movilrecibe`='$movilrecibe',
`telfjorecibe`='$telfjorecibe',
`valorpedido` = $valorpedido ,
`valoradic` = $vadic ,
`idalimento` = '$alimento',
`comentario` = '$comentario',
`cantidad` = '$cantidad',
`bitactivo` =1

WHERE `id`=$cons
");

 
mysql_query($sql) or die ("Error en el query, No se pudo Actualizar el pedido");

//enviar mail
include ('mail.php');
mailped($mailinterventor, $cons, "Modificado");

//Actualizar estado en ped

$sql="update pedido set estado=2 where id=$cons";
mysql_query($sql);

//Grabar historico de estados
$idus=$_SESSION["sipa_id_username"];
$log=date("d/m/Y - G:i")." user: ".$idus;
$sql="INSERT INTO `historico_estados_ped` (
`id` ,
`pedido` ,
`newestado` ,
`comentario` ,
`log`
)
VALUES (
NULL , $cons, '2', 'Modificado', '$log');";

mysql_query($sql) or die ("Error en grabar el cambio de estado.");

//afectar ppto
if($cantidadini<$cantidad)
$sql="UPDATE `presupuesto` SET valorpedido = (valorpedido-$valorpedidoini)+$valorpedido WHERE CONVERT( `presupuesto`.`id` USING utf8 ) = '$ppto'";
else
$sql="UPDATE `presupuesto` SET valorpedido = (valorpedido-$valorpedidoini)+$valorpedido WHERE CONVERT( `presupuesto`.`id` USING utf8 ) = '$ppto'";

mysql_query($sql) or die ("No se pudo actualizar ppto");

echo "<script>alert('Pedido Actualizado');</script>;";
echo "<script> location.href = 'index.php';</script>";
echo $imgaviso2." Pedido ".$cons." Modificado el: $log ";

}else
{ echo"<script>alert('Datos Erroneos')</script>";
	echo $imgaviso."Datos erroneos, favor revise.";
}
include "desconexion.php";

}	//fin Grabar
include "desconexion.php";

?>

<?}else{?>
<SCRIPT LANGUAGE="javascript"> location.href = "index.php";</SCRIPT>
<?}?>


  </div>
</body> 
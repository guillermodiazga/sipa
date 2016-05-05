<?
session_start();?>


<head>
<LINK REL="stylesheet" TYPE="text/css" HREF="estilos2.css"> 
<Title>.::SIPA::.</Title>
 <meta name="tipo_contenido"  content="text/html;" http-equiv="content-type" charset="utf-8">
 <script src="scw.js" type="text/javascript" language="JavaScript1.5"></script>
</head>

<form action=informe.php>
<table align=center>
<tr><th colspan=3>Busqueda por Fecha</th></tr>
<tr><td>
Desde:<input onMouseOver='scwShow(this,event);' id="id_calendario" value='<?if($fchdesde==''){echo $hoy;}else{echo $fchdesde;}?>' name='fchdesde' size='8'> 
</td><td>
Hasta:<input onMouseOver='scwShow(this,event);' id="id_calendario" value='<?if($fchhasta==''){echo $mes;}else{echo $fchhasta;}?>' name='fchhasta' size='8'> 
<td><input type=submit value=Buscar name=buscar></td></tr>
</table>
</form>


<?include 'conexion.php';
include 'funciones.php';
$user=$_SESSION['sipa_id_username'];
// Consultar datos Generales
$sql="select us.id as userid, us.nombre as user, sec.secretaria as sec
from  usuario as us, secretaria  as sec
where 
us.id=$user and
us.idsecretaria=sec.id
";
$resultado = mysql_query($sql);
$row = mysql_num_rows($resultado);

//Mostrar resultados

while ($row = @mysql_fetch_array($resultado)) 
{
$userid=$row['userid'];
$user=strtoupper($row['user']);
$sec=((strtoupper($row['sec'])));

}
?>


 	<table align=center border=0 cellspacing=0>
<tr><td rowspan=2 align=center width=100><img src='images/logocam.png' width=70></td><td rowspan=2 align=center width=450>INFORME PARCIAL DE INTERVENTOR&Iacute;A<br>CONTRATO 4600034055 DE 2011<br><?echo $sec?>	</td><td width=150 align=center>C&oacute;digo: IPIO- 001</td></tr>
		<tr><td align=center>Versi&oacute;n: 01</td></tr>
</table>



<p align=center><b>INFORME CORRESPONDIENTE A JUNIO DE 2011</b></p>
<table align=center border=0 cellspacing=2>
<tr><th width=200 align=left>	CONTRATANTE: </th><td width=500>MUNICIPIO DE MEDELL&Iacute;N - <?echo $sec?>	</td></tr>
<tr><th width=200 align=left valign=top>	CONTRATISTA: </th><td width=500>T&Iacute;A MIMA S.A.<br>NIT. 800027368-4<br>ELKIN DE JES&Uacute;S G&Oacute;MEZ RODR&Iacute;GUEZ<br>Representante Legal.
</td></tr>
<tr><th width=200 align=left  valign=top>	OBJETO: </th><td width=500>Prestaci&oacute;n del servicio de alimentaci&oacute;n para diferentes eventos. Grupo1 Refrigerios, bebidas y pasabocas.</td></tr>
<tr><th width=200 align=left>	FECHA DE INICIO: </th><td>09 de junio de 2011</td></tr>
<tr><th width=200 align=left>	FECHA DE TERMINACI&Oacute;N: </th><td>31 de diciembre de 2011</td></tr>
</table>

<p align=justify>
Con el objeto de realizar el seguimiento del avance de la ejecuci&oacute;n del contrato 4600026836 de 2010, se presenta el informe parcial de interventor&iacute;a operativa correspondiente a junio de 2011, con fundamento en la normatividad vigente de contrataci&oacute;n:
</p>
<p align=justify>
De acuerdo con lo anterior, el siguiente es el balance de la ejecuci&oacute;n del contrato 4600026836 de 2011:
</p>

<p align=left><b>1.	ASPECTOS T&Eacute;CNICOS</b>
</p>

<p align=justify>En cumplimiento del objeto contractual se recibieron los servicios solicitado por la <?echo capitalizar($sec)?> que cumpl&iacute;an con los requisitos de calidad y oportunidad.
</p>
<p align=justify>En el periodo no se presentan observaciones en cuanto a calidad y oportunidad en el servicio.
</p>
<p align=justify>Los pedidos se realizaron y controlaron a trav&eacute;s del sistema facilitado por el proveedor y/o comunicaciones telef&oacute;nicas.
</p>


<p align=left><b>2.	ASPECTOS FINANCIEROS DEL CONTRATO</b>
</p>

<p align=left><b>PAGOS EFECTUADOS AL CONTRATISTA:</b>
</p>


<table align=center border=0 cellspacing=0>
<tr><th width=100 align=center>FACTURA</th><th width=240 >FECHA DE CONTABILIZACI&Oacute;N</th><th width=150 >VALOR</th><th width=200 >DOCUMENTO DE COMPRAS</th></tr>

<?
$secuser=$_SESSION["sipa_sec_username"];

$sql="SELECT ped.factura, SUBSTRING( hist.log, 1, 10 ) as fecha , ppto.pedido, sum( ped.valorpedido ) as valor

FROM `pedido` AS ped, presupuesto AS ppto, historico_estados_ped AS hist

WHERE ped.estado =5
AND hist.newestado =5
AND ppto.id = ped.idppto
AND hist.pedido = ped.id
AND ped.idsecretaria = $secuser

GROUP BY factura
";
$resultado = mysql_query($sql);
$row = mysql_num_rows($resultado);

//Mostrar resultados

while ($row = @mysql_fetch_array($resultado)) 
{

?>
<tr><td align=center><?echo $row['factura']?></td><td align=center><?echo $row['fecha']?></td><td align=right>$<?echo number_format($row['valor'], 0, '', '.')?></td><td align=center><?echo $row['pedido']?></td></tr>
<?}?>



</table>

<p align=left><b>RESUMEN POR PROYECTO:</b>
</p>


<?


//Consulta acumulativa de valores
$fchdesde=fch_php_mysql($fchdesde);
$fchhasta=fch_php_mysql($fchhasta);

  $sql=("SELECT ppto.idsecretaria, sec.secretaria, ped.idppto, ppto.nombre,
 count(ped.id) as cantidad,
sum(DISTINCT ppto.valorini) as valorini, 
 sum( ped.`valorpedido`) as valorped, 
SUM(CASE 
WHEN ped.estado = '5' 
THEN (ped.valorpedido)
END) as valorpag,

count(CASE 
WHEN ped.estado = '5' 
THEN (ped.id)
END) as cantidadpag
 
FROM presupuesto as ppto, `pedido` as ped,  secretaria as sec

WHERE 
ped.`bitactivo`=1 and 
ped.idsecretaria=ppto.idsecretaria and
ped.idppto=ppto.id and
ppto.idsecretaria=sec.id and
ped.idsecretaria=sec.id and 
ped.fchentrega Between '$fchdesde' and '$fchhasta' 
AND ped.idsecretaria = $secuser

 group by ped.idsecretaria, ped.idppto, ppto.nombre
");

$result=mysql_query($sql);
$row = mysql_num_rows($result);



?>

<br>
<table align=center border=0 cellspacing=0>
<tr><th>Secretar&iacute;a</th><th>Presupuesto</th><th>Valor inicial</th><th>Valor Pedido</th><th>Valor Pagado</th><th>% Pagado</th></tr>

<?
$i=1;
while ($row = mysql_fetch_array($result)) 
{

$vpen=(($row['valorini'])-($row['valorpedido']));

$porc=((($vpen)/($row['valorini']))*100);

if($porc>49)
$img="Bgrafico.jpg";
else if(($porc>11) and ($porc<50))
$img="Bgrafico0.jpg";
else 	
$img="Bgrafico1.jpg";
if($i%2==0){
   ?><tr bgcolor='#D8D8D8'>
  
  <?}else{
  
 
?>
 <?}?>
<td><?echo $row['idsecretaria']." - ".($row['secretaria'])?></td>
<td><?echo $row['idppto']." - ".($row['nombre'])?></td>
<td align=right >$<?echo number_format($row['valorini'], 0, '', '.')?></td>
<td align=right >$<?echo number_format($row['valorped'], 0, '', '.')?></td>
<td align=right >$<?echo number_format($row['valorpag'], 0, '', '.')?></td>
<td><?echo round($porc=(($row['valorpag'])/($row['valorped']))*100,2) ?>%</td>

</tr>
<?
//Calcular Totales
$tcant+=$row['cantidad'];
$tini+=$row['valorini'];
$tped+=$row['valorped'];
$tpag+=$row['valorpag'];
$tcantpag+=$row['cantidadpag'];


$i++;}//fin while



//Totales
if(($tped!=0)||($tpag!=0))
  $tporc = round(($tpag/$tped)*100,2);
  else
  $tporc=0;
  ?>

 <tr>
 <td align=center colspan=2><b>Totales:</td>
 <td align=right><b>$<?echo number_format($tini, 0, '', '.')?></td>
 <td align=right><b>$<?echo number_format($tped, 0, '', '.')?></td>
 <td align=right><b>$<?echo number_format($tpag, 0, '', '.')?></td>
 <td><b><?echo $tporc ?>%</td>

 </tr>
 
</table>













<p align=justify>De acuerdo con las cifras que anteceden, el estado financiero del contrato a la fecha del presente informe es el siguiente:
</p>

<table>
<tr><td>Valor ejecutado seg&uacute;n informe de Interventor&iacute;a:</td><td>$2.691.716</td><td> 	equivalente al 26%</td></tr>
<tr><td>Saldo pendiente por ejecutar:	</td><td>$7.808.824 </td><td> 	equivalente al 74%</td></tr>
</table>

<p align=justify>En calidad de supervisor dejo constancia que:</p>

<p align=justify>Se encuentran aprobados y recibidos a satisfacci&oacute;n por parte de la Interventor&iacute;a, los servicios pagados.</p>

<p align=left>Se firma el <?echo fch_mysql_fchlarga((date('m/d/y')))?>.</p>
<br>
<br>
<br>
<br>
<br>
<?echo $user?><br>
Supervisor Operativo.


<?include 'desconexion.php';

?>


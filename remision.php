<?
session_start();?>


<head>
<LINK REL="stylesheet" TYPE="text/css" HREF="estilos.css"> 
<Title>.::SIPA::.</Title>
<script type="text/javascript" src="menu.js"></script>
<script src="SpryAssets/SpryMenuBar.js" type="text/javascript"></script>
<link href="SpryAssets/SpryMenuBarHorizontal.css" rel="stylesheet" type="text/css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"> 

<!-- HTTP 1.1 -->
<meta http-equiv="Cache-Control" content="no-store"/>
<!-- HTTP 1.0 -->
<meta http-equiv="Pragma" content="no-cache"/>
<!-- Prevents caching at the Proxy Server -->
<meta http-equiv="Expires" content="0"/>

</head>

<BODY STYLE="font-family:arial,helvetica,'Arial'" onload="window.print()"> 
<?php
$ped=$_GET['ped'];
if ($ped=='') {
include("formlogin.php"); 
include ("funciones.php");
}else{

include 'conexion.php';
include ("funciones.php");



// Consultar Interventor
$sql="select us.id, us.nombre, us.telefono, us.mail, us.movil from rol, usuario as us where interventor=us.id";
$resultado = mysql_query($sql);
$row = mysql_num_rows($resultado);

//Mostrar resultados

while ($row = @mysql_fetch_array($resultado)) 
{
$interventor=$row['nombre'];
$celinterventor=format_cel_num($row['movil']);
$telinterventor=format_tel_num($row['telefono']);
$mailinterventor=$row['mail'];
}

 


if($ped!='')
{
//Consultar datos del pedido

$sql="SELECT ped.id, ped.idppto,
ped.idsecretaria,
ped.estado,
 ped.fchentrega,
 ped.direccion as direcc, 
 ped.idusuario, 
 ped.evento,
 ped.hora, 
 ped.comentario,
 ped.idalimento, 
 ped.personarecibe,
 ped.telfjorecibe,
 ped.movilrecibe,
 ped.cantidad,
 ped.valorpedido,
 ali.descripcion,
 ali.valor,
 sec.secretaria,
 us.nombre,
 us.telefono,
 us.mail,
 us.movil,
 prov.proveedor,
 prov.direccion,
 prov.email,
 prov.telefono as telprov,
 ppto.contrato
 
FROM 
pedido as ped,
usuario as us, 
secretaria as sec, 
proveedor as prov, 
tipoalimento as tipo, 
alimento as ali,
presupuesto as ppto

WHERE
ped.id =($ped) and
ped.idalimento=ali.id and
ped.idsecretaria=sec.id and
ped.idtalimento=tipo.id and
ped.idusuario=us.id and
prov.id=tipo.idproveedor and
ped.idppto=ppto.id";
	
$resultado = mysql_query($sql);
$row = @mysql_num_rows($resultado);

//Mostrar resultados

while ($row = @mysql_fetch_array($resultado)) 
{
//si esta anulado
if($row['estado']==1)
{
?>
<SPAN ID="capa" STYLE="position:absolute; top:280; left:200">
<font size=30 color=red><img src='images/pedido anulado.png'>
</SPAN>

<?}?>

<table width="630" border="0" align="center" style="background: url(images/barraFondo.jpg) no-repeat"> 




<tr><td>Se&ntilde;ores<br><b><?echo $row['proveedor']?></b><br><?echo $row['direccion']?> (Comutador: <?echo $row['telprov']?>)<br> Medell&iacute;n 
<p align=justify>Cordial saludo, favor proceder de acuerdo al contrato <?echo $row['contrato']?>, y suministrar la siguiente solicitud de servicio, teniendo en cuenta la calidad de los productos y oportunidad en la entrega.
<br>Gracias por la atenci&oacute;n.</p>
</td></tr>
<tr><td align=center>

<table  border=2 cellspacing=0>

<tr><th colspan=2><font size=3><?echo ($row['secretaria'])?> - Solicitud Nro.:<font size=3><?echo $row['id']?> </th></tr>
<tbody bgcolor='#ffffff' style='font-size:50'>

<colgroup>
<col width="6" align=center>
<col bgcolor='#ffffff'  >
</colgroup>


<tr><td rowspan=2><b>Entrega:</td><td><b>Fecha: </b><?echo fch_mysql_fchlarga($row['fchentrega'])?>; <b>Hora: </b><?echo ($row['hora'])?></td></tr>
<tr><td><b>Direcci&oacute;n: </b><?echo ($row['direcc'])?>; <b>Evento:</b> <?echo ($row['evento'])?></td></tr>

<tr><td rowspan=2><b>Recibe:</td><td><b><?echo ($row['personarecibe'])?></td></tr>
<tr><td <?echo $fonttable?>><b>Tel Fijo: </b><?echo format_tel_num($row['telfjorecibe'])?>;<b> Movil: </b> <?echo format_cel_num($row['movilrecibe'])?></td></tr>


<tr><td rowspan=3><b>Detalle: </td><td><p align=justify><b>Item:</b> <?echo ($row['idalimento'])?>: <?echo ($row['descripcion']);if($row['comentario']!=''){echo "<br>Nota: ".($row['comentario']);}?></p></td></tr>
<tr><td><b>Cantidad:</b> <?echo number_format($row['cantidad'], 0, '', '.')?>; <b>Vr./U sin IVA:</b> $<?echo number_format(round($row['valor'],0), 0, '', '.')?>; <b>Vr. Total: </b> $<?echo number_format($row['valorpedido'], 0, '', '.')?></td></tr>
<tr><td><b>Nro. del Proyecto-Pedido: <?echo ($row['idppto'])?></td></tr>

<tr><td rowspan=2><b>Interventor <br> Operativo:</td><td><b><?echo ($row['nombre'])?></td></tr>
<tr><td><b>Tel Fijo: </b> <?echo format_tel_num($row['telefono'])?>; <b>Movil: </b><?echo format_cel_num($row['movil'])?>; <br><b>Mail: </b><?echo $row['mail']?></td></tr>

<tr><td rowspan=2><b>Interventor Adiministrativo:</td><td><? echo $interventor?></td></tr>
<tr><td><b>Tel Fijo:</b> <? echo $telinterventor?>; <b>Movil: </b><? echo $celinterventor?>; <br> <b>Mail: </b> <?echo $mailinterventor?></td></tr>
<tbody>
</table >

<tr><td><br></td></tr>
</td></tr>

<?//Remisi&oacute;n?>
<tr><td>
<table  border=1 rules=none cellspacing=0>

<tr><td colspan=2 align=center>

<table align=center border=0 width="630" cellspacing=0>

<tr><td align=center bgcolor='#ffffff' rowspan=3><img width=110 height=90 src='images/logo.png'></td><td ><b>Direccion: </b><?echo $row['direccion']?><br> <b> Comutador: </b><?echo $row['telprov']?><br><b>E-mail: </b><?echo $row['email']?></td><td align=left><b>Remisi&oacute;n Nro. </b>_________</td></tr>
<tr><td colspan=2><hr noshade="noshade" size="1"/> 
</td></tr>
<tr><td align=left colspan=1>Se&ntilde;ores:<br> <b>Municipio de Medell&iacute;n </b><br> <?echo ($row['secretaria'])?> </td><td><b>Pedido:</b><?echo $row['id']?><br><b>Fecha: </b><?echo ($row['fchentrega'])?> <br><b>Hora: </b><?echo ($row['hora'])?> </td></tr>

<tr><td colspan=3><hr noshade="noshade" size="1" valign=top/>

 </table>
 
<colgroup>
<col width="6" align=center>
<col bgcolor='#ffffff'  >
</colgroup>

<tr><td rowspan=2><b>Entrega:</td><td></td></tr>
<tr><td colspan=2><b>Direcci&oacute;n: </b>Medell&iacute;n, <?echo ($row['direcc'])?><br> <b>Evento:</b> <?echo (($row['evento']))?><br><b></td></tr>

</td></tr>

<tr><td colspan=3><hr noshade="noshade" size="1" valign=top/>

<tr><td rowspan=3><b>Detalle: </td><td><p align=justify><b>Item:</b> <?echo ($row['idalimento'])?>: <?echo ($row['descripcion']);if($row['comentario']!=''){echo "<br>Nota: ".($row['comentario']);}?></p></td></tr>
<tr><td><b>Cantidad:</b> <?echo number_format($row['cantidad'], 0, '', '.')?>; <b>Vr./U sin IVA:</b> $<?echo number_format(round($row['valor'],0), 0, '', '.')?>; <b>Vr. Total: </b> $<?echo number_format($row['valorpedido'], 0, '', '.')?></td></tr>
<tr><td><b>Nro. del Proyecto-Pedido: <?echo ($row['idppto'])?></td></tr>

<tr><td colspan=3><hr noshade="noshade" size="1" valign=top/>

<tr><td rowspan=2><b>Recibido:</td><td>
<table  cellspacing=0 border=1 rules=groups >
<colgroup>

<col >
<col bgcolor='#ffffff' border=1 rules=cols >
<col bgcolor='#ffffff'  >
</colgroup>
<tr><td>
<table border=0 rules=cols>
<tr>

<td rowspan=3><br>_______________________<font color=ffffff>_</font><br><font size=1><?echo ($row['personarecibe'])?></b><br><font size=1>CC:</font></td>
<td rowspan=3 width="40%" valign=bottom>__________________________<br><font size=1>V.B. <?echo ($row['nombre'])?><br><font size=1><br></font></td>
</tr>
<tr><td></td>
<td rowspan=3 valign=top width="40%" align=left><font size=1>Despachado:</font></td>
<td></td>
<td rowspan=3 valign=top width="40%" align=left><font size=1>Transportado:</font></td>
</tr>



</table>
</td></tr>
</table >

</td></tr>
</table>


<?}//fin while

}else{echo "<script>location.href = 'index.php'; </SCRIPT>";}
include 'desconexion.php';

}

?>

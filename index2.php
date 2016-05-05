<?
session_start();?>

<?php include ("cabeza.php")?>
<head>
<?//Actualizar automaticamente?>
<meta http-equiv="refresh" content="60;url=index.php"> 
<script>
function refrescar(){
location.href = 'index.php';
//location.reload(true)
}

</script>

</head>

<?php
include ("funciones.php");
if (!isset($_SESSION['sipa_username'])) {?>
<body onLoad="muestraGranDiv();">
<div id="cargando" style="width: 357px; height: 100px; position: absolute; padding-top:20px; text-align:center; left: 2px;">
<div align="center"><br><br><br><strong>Por favor espere un momento... <img src='images/cargando.gif'></strong></div>
</div>
<div id="granDiv" style="visibility:hidden;">
<?
include("formlogin.php"); 
?>

</body> <?
}else{

?><table align=center><?
//Bandeja de entrada
include ("conexion.php");

$consulta=("
SELECT   ped.id, ped.idsecretaria, tali.talimento, ped.fchentrega, ped.hora, ali.nombre as alimento, ped.cantidad, ped.valorpedido, ped.idppto, ped.direccion, ped.comentario, ped.personarecibe, ppto.nombre as nomppto, ped.estado

FROM pedido as ped, usuario as us, tipoalimento as tali, alimento as ali, presupuesto as ppto

WHERE  ped.bitactivo=1

and ped.estado Between 2 and 3

and ped.idtalimento=tali.id

and ped.idalimento=ali.id

and ped.idppto=ppto.id

and ped.idusuario=us.id

order by ped.estado desc
 ");
$resultado = @mysql_query($consulta);
$row = @mysql_num_rows($resultado);



if($rechazar!='')
{
//Actualizar estado
echo$sql="update pedido set estado=7 where id=$rechazar";
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
NULL , $rechazar, '7', '$motivo', '$log'
);";
mysql_query($sql);

//enviar mail
include 'conexion.php';
$sql1="SELECT usuario.mail
FROM pedido, usuario
WHERE usuario.id = idusuario
AND pedido.id =$rechazar
";
mysql_query($sql1);
$resultado1 = mysql_query($sql1);
$row1 = mysql_num_rows($resultado1);
while ($row1 = mysql_fetch_array($resultado1)) 
{$mailuser=$row1['mail'];
}
$dest=$mailinterventor;
include ('mail.php');
mailprov($dest, $rechazar, "Rechazado", $mailuser);

echo "<script>alert('Pedido $rechazar Rechazado.'); location.href = 'index.php';</SCRIPT>";
}
if($rech!=''){
?>

<tr><td colspan=13>
<table>
<form action="index2.php?rechazar=<?echo $rech?>" method="POST" onsubmit="return vrechazar()" >
<input type=hidden value="<?echo $rech?>" name=rechazar>
<tr><td>Motivo de Rechazo del <b>Pedido <?echo $rech?>:</b></td><td><textarea name="motivo" onmouseout="this.disabled();" rows='3' cols='30'></textarea></td></tr>
<tr><td></td><td><input type=submit  onclick='return confrechazar()' value="Rechazar";></td></tr>
</form>
</table>
</td></tr>

<?}

//encabezado tabla
if($row!=1){
?><tr><td bgcolor=ffffff colspan=2 valign=top align=center><input type=image src='images/actualizar.png' width=30 height=30 onclick='refrescar()' Alt='Actualizar Bandeja de Entrada'></td><td  valign=center align=center colspan=11 bgcolor=#ffffff><font color=#585858><?echo $row?> Pedidos Pendientes </td><td colspan=2 bgcolor=#ffffff valign=center align=center><form action='index.php' method='POST'><input type=hidden value=1 name=aprobartodo><input value="Aprobar_Todo" name="Aprobar_Todo"  alt="Aprobar Todo" type=image src='images/bandera_green.png' onclick="return confaprobartodo()" height='0'width='0'></form></td></tr><?
}else{
?><tr><td bgcolor=ffffff colspan=2><input type=image src='images/actualizar.png' width=30 height=30 onclick='refrescar()' Alt='Actualizar Bandeja de Entrada'></td><td align=center colspan=12 bgcolor=#ffffff><font color=#585858><?echo $row?> Pedido Pendiente </td></tr><?
}
echo "<tr BGCOLOR='#007b80'><th colspan=2><FONT COLOR=#FFFFFF>Acci&oacute;n</th><th><FONT COLOR=#FFFFFF>Sec</th><th><FONT COLOR=#FFFFFF>#</th><th><FONT COLOR=#FFFFFF>Tipo</th><th><FONT COLOR=#FFFFFF>Item</th><th><FONT COLOR=#FFFFFF>Cant</th><th><FONT COLOR=#FFFFFF>Valor Pedido</th><th><FONT COLOR=#FFFFFF>Fecha Entrega</th><th><FONT COLOR=#FFFFFF>Hora Entrega</th><th><FONT COLOR=#FFFFFF>Direcci&oacute;n</th><th><FONT COLOR=#FFFFFF>Presupuesto</th><th><FONT COLOR=#FFFFFF>Recibe</th><th><FONT COLOR=#FFFFFF>Observaci&oacute;n</th><tr>";

$i=1;


//Detalle tabla

while ($row = @mysql_fetch_array($resultado)) 
{
$iden=($row['id']);

if($i%2==0){
   ?><tr bgcolor='#D8D8D8' onmouseover="this.style.backgroundColor='<?echo  $colorOnMouseOver?>'" onmouseout="this.style.backgroundColor='#D8D8D8'">
   <?}else
{
   ?><tr bgcolor='#ffffff' onmouseover="this.style.backgroundColor='<?echo  $colorOnMouseOver?>'" onmouseout="this.style.backgroundColor='ffffff'">
   <?}?>
   
		<td>
	  <img src='images/b_view.png' name='Ver' height='<?echo $tamano?>'width='<?echo $tamano?>' alt='Ver historial de estados ped. <?echo $iden?>' onclick="javascript:window.open('verestados.php?ped=<?echo $iden;?>','noimporta', 'width=450, height=200, scrollbars =yes, top=250, status=no, toolbar=no, titlebar=no, menubar=no')">
	  <img src='images/Print.png' name='Ver' height='<?echo $tamano?>'width='<?echo $tamano?>' alt='Imprimir Pedido. <?echo $iden?>' onclick="javascript:window.open('remision.php?ped=<?echo $iden;?>','noimporta', 'width=800, height=600, scrollbars =yes, top=250, status=no, toolbar=no, titlebar=no, menubar=no, urlbar=no')">
<td align=center>
<?if($row['estado']==3){?>
<a href='index2.php?rech=<?echo $iden?>'><img src='images/rechazado.png' alt='Rechazar' border=0  height='15'width='15'></a><a href='index2.php?aprobar=<?echo $iden?>'><img onclick="return confaprobar()" src='images/liberado.png' alt='Aprobar' border=0 height='15'width='15'>
<?}else{?>
<font color=red>Pendiente
<?}?>
</td>
	  <?echo "</a></td><td>".$row['idsecretaria']."</td><td>".$iden=($row['id'])."</td><td>".($row['talimento'])."</td><td>".($row['alimento'])."</td><td align=right>".$row['cantidad']."</td><td align=right>$".number_format(($row['valorpedido']), 0, '', '.')."</td><td>".fch_mysql_php($row['fchentrega'])."</td><td>".$row['hora']."</td><td>".($row['direccion'])."</td><td>".$row['idppto']." ".($row['nomppto'])."</td><td>".($row['personarecibe'])."</td><td>".($row['comentario'])."</td></tr>";	


$i++;

}//fin while

//Mostrar Pedidos para mañana
$dia=fch_php_mysql($mañana);
$consulta=("
SELECT   ped.id, ped.idsecretaria, tali.talimento, ped.fchentrega, ped.hora, ali.nombre as alimento, ped.cantidad, ped.valorpedido, ped.idppto, ped.direccion, ped.comentario, ped.personarecibe, ppto.nombre as nomppto, ped.estado

FROM pedido as ped, usuario as us, tipoalimento as tali, alimento as ali, presupuesto as ppto

WHERE  ped.bitactivo=1


and ped.idtalimento=tali.id

and ped.idalimento=ali.id

and ped.idppto=ppto.id

and ped.idusuario=us.id

and ped.fchentrega='$dia'

order by ped.estado desc
 ");
$resultado = @mysql_query($consulta);
$row = @mysql_num_rows($resultado); 
?>

<tr><td colspan=14><br><hr></td></tr>
<table align=center>
<?
if($row!=1){
?><tr><td  valign=center align=center colspan=13 bgcolor=#ffffff><font color=red><h3><?echo $row?> Pedidos Para Ma&ntilde;ana </td><td colspan=2 bgcolor=#ffffff valign=center align=center><form action='index.php' method='POST'><input type=hidden value=1 name=aprobartodo><input value="Aprobar_Todo" name="Aprobar_Todo"  alt="Aprobar Todo" type=image src='images/bandera_green.png' onclick="return confaprobartodo()" height='0'width='0'></form></td></tr><?
}else{
?><tr><td align=center colspan=13 bgcolor=#ffffff><font color=red><h3><?echo $row?> Pedido para Ma&ntilde;ana </td></tr><?
}
?><tr BGCOLOR='#007b80'><th colspan=1><FONT COLOR=#FFFFFF>Acci&oacute;n</th><th>Estado</th><th><FONT COLOR=#FFFFFF>Sec</th><th><FONT COLOR=#FFFFFF>#</th><th><FONT COLOR=#FFFFFF>Item</th><th><FONT COLOR=#FFFFFF>Cant</th><th><FONT COLOR=#FFFFFF>Valor Pedido</th><th><FONT COLOR=#FFFFFF>Fecha Entrega</th><th><FONT COLOR=#FFFFFF>Hora Entrega</th><th><FONT COLOR=#FFFFFF>Direcci&oacute;n</th><th><FONT COLOR=#FFFFFF>Presupuesto</th><th><FONT COLOR=#FFFFFF>Recibe</th><th><FONT COLOR=#FFFFFF>Observaci&oacute;n</th><tr>
<?
$i=1;


//Detalle tabla

while ($row = @mysql_fetch_array($resultado)) 
{
$iden=($row['id']);

if($i%2==0){
   ?><tr bgcolor='#D8D8D8' onmouseover="this.style.backgroundColor='<?echo  $colorOnMouseOver?>'" onmouseout="this.style.backgroundColor='#D8D8D8'">
   <?}else
{
   ?><tr bgcolor='#ffffff' onmouseover="this.style.backgroundColor='<?echo  $colorOnMouseOver?>'" onmouseout="this.style.backgroundColor='ffffff'">
   <?}?>
   
		<td>
	  <img src='images/b_view.png' name='Ver' height='<?echo $tamano?>'width='<?echo $tamano?>' alt='Ver historial de estados ped. <?echo $iden?>' onclick="javascript:window.open('verestados.php?ped=<?echo $iden;?>','noimporta', 'width=650, height=400, scrollbars =yes, top=250, status=no, toolbar=no, titlebar=no, menubar=no')">
	  <img src='images/Print.png' name='Ver' height='<?echo $tamano?>'width='<?echo $tamano?>' alt='Imprimir Pedido. <?echo $iden?>' onclick="javascript:window.open('remision.php?ped=<?echo $iden;?>','noimporta', 'width=800, height=600, scrollbars =yes, top=250, status=no, toolbar=no, titlebar=no, menubar=no, urlbar=no')">
<td align=center>
<?
if(($row['estado'])==1)
{$estado="<img src='images/anular.png' alt='- Anulado'> Anulado";
}if(($row['estado'])==2)
{$estado="<img src='images/pendiente.png' alt='= Pendiente'> Pendiente";
}else if(($row['estado'])==3)
{$estado="<img src='images/liberado.png' alt='+ Aprobado'> Aprobado Alcald&iacute;a";
}else if(($row['estado'])==4)
{$estado="<img src='images/rechazado.png' alt='X Rechazado'> Rechazado";
}else if(($row['estado'])==5)
{$estado="<img src='images/pagado.png' alt='$ Pagado'>Pagado";
}else if(($row['estado'])==6)
{$estado="<img src='images/despacho.png' alt='>Para Despacho'> Para Despacho";
}else if(($row['estado'])==7)
{$estado="<img src='images/rechazadoprov.png' alt='X Rechazado T&iacute;a Mima'>Rechazado T&iacute;a Mima";}


echo $estado;?>
</td>
	  <?echo "</a></td><td>".$row['idsecretaria']."</td><td>".$iden=($row['id'])."</td><td>".($row['alimento'])."</td><td align=right>".$row['cantidad']."</td><td align=right>$".number_format(($row['valorpedido']), 0, '', '.')."</td><td>".fch_mysql_php($row['fchentrega'])."</td><td>".$row['hora']."</td><td>".($row['direccion'])."</td><td>".$row['idppto']." ".($row['nomppto'])."</td><td>".($row['personarecibe'])."</td><td>".($row['comentario'])."</td></tr>";	


$i++;

}//fin while



//Fin Mostrar Pedidos para mañana



if($aprobar!='')
{

//Actualizar estado en ped
$sql="update pedido set estado=6 where id=$aprobar";
mysql_query($sql) or die ('Error a l actualizar estado');


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
NULL , $aprobar, '6', '$comentario', '$log'
);";
mysql_query($sql);


//enviar mail
include 'conexion.php';
$sql1="SELECT usuario.mail
FROM pedido, usuario
WHERE usuario.id = idusuario
AND pedido.id =$aprobar
";
mysql_query($sql1);
$resultado1 = mysql_query($sql1);
$row1 = mysql_num_rows($resultado1);
while ($row1 = mysql_fetch_array($resultado1)) 
{$mailuser=$row1['mail'];
}

//$dest="emersondiazg@gmail.com";
$dest=$mailinterventor;

include ('mail.php');
mailprov($dest, $aprobar, "Aprobado", $mailuser);

mail(($dest.", ".$mailuser),"Pedido $aprobar Aprobado por Tía Mima","Ingrese a www.tiamima.com/sipa","");

echo "<table align=center><tr><td>".$imgaviso2."Pedido $aprobar Aprobado.";
echo "<script>location.href = 'index.php';</SCRIPT>";
}

include 'desconexion.php';
}?></div><script>

function confrechazar(){

if(confirm('\u00bfSeguro que desea rechazar?'))
{
//location.href = "index.php";
return true;
}
else{//location.href = "index.php";
return false;
}
}

function vrechazar(){
var comentario= document.getElementById("motivo").value;
if( comentario.length<1 || comentario.length>250 ) {
alert('Motivo Obligatorio (max 250 Caracteres)');
return false;
}else{
return true;
}}

</script>
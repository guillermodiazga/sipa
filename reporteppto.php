<?
session_start();
if (isset($_SESSION['sipa_username'])) {	?>
<?include  'funciones.php';?>
<?php
if ($print==1){

?><LINK REL="stylesheet" TYPE="text/css" HREF="estilos.css"><?

}else
 include ("cabeza.php"); 

?>
<head>

<script src="scw.js" type="text/javascript" language="JavaScript1.5"></script>



</head>
<? if ($print==1){?>
<body onLoad="window.print()">
<?}else{?>
<body onLoad="muestraGranDiv()">


<div id="granDiv">
<?}?>
<? if ($print==1){
 include ("printCabezaCliente.php"); 
 ?><table  align=center><tr><td><h4>Informe de Consumo: <?echo $fchdesde?> a <?echo $fchhasta?></h4></td></tr><?
 }else{?>
<form action=reporteppto.php>
<table align=center>
<tr><th colspan=3>Busqueda por Fecha de Entrega</th></tr>
<tr><td>
Desde:<input onMouseOver='scwShow(this,event);' id="id_calendario" value='<?if($fchdesde==''){echo $hoy;}else{echo $fchdesde;}?>' name='fchdesde' size='8'> 
</td><td>
Hasta:<input onMouseOver='scwShow(this,event);' id="id_calendario" value='<?if($fchhasta==''){echo $mes;}else{echo $fchhasta;}?>' name='fchhasta' size='8'> 
<td><input type=submit value=Buscar name=buscar></td></tr>
<?if($buscar){?>
<td colspan=2></td><td><button  class="nover"onclick="window.open('reporteppto.php?user=<?echo $user;?>&fchhasta=<?echo $fchhasta;?>&fchdesde=<?echo $fchdesde;?>&print=1&buscar=Buscar','noimporta', 'width=800, height=600, scrollbars =yes, top=250, status=no, toolbar=no, titlebar=no, menubar=no')">Imprimir</button></td></tr>
<?}}?>


</table>
</form>
<?php include ("conexion.php");

// Si no es administrador solo ve su secretaria
if (($_SESSION['sipa_admin'])!=1)  
$secre="and ped.idsecretaria=".$_SESSION["sipa_sec_username"];

//Consulta acumulativa de valores
$fchdesde=fch_php_mysql($fchdesde);
$fchhasta=fch_php_mysql($fchhasta);

   $sql=("SELECT ppto.idsecretaria, sec.secretaria, ped.idppto, ppto.nombre,
 count(ped.id) as cantidad,
sum(DISTINCT ppto.valorini) as valorini, 
 sum( ped.valorpedido+ped.valoradic) as valorped, 
SUM(CASE 
WHEN ped.estado = '5' 
THEN (ped.valorpedido+ped.valoradic)
END) as valorpag,

(ppto.valorini+ppto.valorNoRequerido)-ppto.valorpedido as saldo,

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
$secre
 group by ped.idsecretaria, ped.idppto, ppto.nombre
");

$result=mysql_query($sql);
$row = mysql_num_rows($result);


if($buscar){
?>

<br>
<table align=center <?if ($print==1){echo "border=1";}?>>
<tr><th colspan=9>Reporte de Consumo por Secretar&iacute;a <? if ($_SESSION["sipa_admin"]==0){echo "Secretar&iacute;a: ".$_SESSION["sipa_sec_username"];}?></th></tr>
<tr><th>Secretar&iacute;a</th><th>Presupuesto</th><th>Cant. Pedidos</th><th>Valor inicial</th><th>Valor Pedido</th><th>Cant. Ped. Pag.</th><th>Valor Pagado</th><th>% Pagado</th><th>Saldo</th></tr>

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
   ?><tr bgcolor='#D8D8D8' onmouseover="this.style.backgroundColor='<?echo  $colorOnMouseOver?>'" onmouseout="this.style.backgroundColor='#D8D8D8'">
  
  <?}else{
  
 
?><tr bgcolor='#ffffff' onmouseover="this.style.backgroundColor='<?echo  $colorOnMouseOver?>'" onmouseout="this.style.backgroundColor='#ffffff'">
 <?}?>
<td><?echo $row['idsecretaria']." - ".($row['secretaria'])?></td>
<td><?echo $row['idppto']." - ".($row['nombre'])?></td>
<td align=center><?echo $row['cantidad']?></td>

<td align=right >$<?echo number_format($row['valorini'], 0, '', '.')?></td>
<td align=right >$<?echo number_format($row['valorped'], 0, '', '.')?></td>
<td align=center><?echo $row['cantidadpag']?></td>
<td align=right >$<?echo number_format($row['valorpag'], 0, '', '.')?></td>
<td><?echo round($porc=(($row['valorpag'])/($row['valorped']))*100,2) ?>%</td>
<td align=right >$<?echo number_format($row['saldo'], 0, '', '.')?></td>
</tr>
<?
//Calcular Totales
$tcant+=$row['cantidad'];
$tini+=$row['valorini'];
$tped+=$row['valorped'];
$tpag+=$row['valorpag'];
$tcantpag+=$row['cantidadpag'];
$tsaldo+=$row['saldo'];


$i++;}//fin while


include ("desconexion.php");

//Totales
if(($tped!=0)||($tpag!=0))
  $tporc = round(($tpag/$tped)*100,2);
  else
  $tporc=0;
  ?>

 <tr>
 <td align=center colspan=2><b>Totales:</td>
 <td align=center><b><?echo number_format($tcant, 0, '', '.')?></td>
 <td align=right><b>$<?echo number_format($tini, 0, '', '.')?></td>
 <td align=right><b>$<?echo number_format($tped, 0, '', '.')?></td>
  <td align=center><b><?echo number_format($tcantpag, 0, '', '.')?></td>
 <td align=right><b>$<?echo number_format($tpag, 0, '', '.')?></td>
 <td><b><?echo $tporc ?>%</td>
 <td align=right><b>$<?echo number_format($tsaldo, 0, '', '.')?></td>
 </tr>
 
<? if ($print==1){
//Firma?>

<table>
<tr><td><br></td></tr>
<tr><td><br></td></tr>
<tr><td><br></td></tr>
<tr><td>____________________________</td></tr>
<tr><td><b><?echo $_SESSION['sipa_username']?></td></tr>
<tr><td>C.C. <?echo number_format($_SESSION['sipa_id_username'], 0, '', '.')?></script></td></tr>
</table>
<?}?>
<?}}else{?>
<SCRIPT LANGUAGE="javascript"> location.href = "index.php";</SCRIPT>
<?}  ?>
  <script>
function load(){
document.write("<img src='images/cargando.gif'>");
}


/*
<SPAN ID='capa' STYLE='position:absolute; top:100; left:200'>
<img src='images/cargando.gif'>
\</SPAN\>
*/
  </script>
  

  </div>
</body> 

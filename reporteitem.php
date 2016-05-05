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
<?if ($print==1){?>
<body onLoad="ejecutarRows(); window.print()" bgcolor=eeeeee>
<?}else{?>
<body onLoad="muestraGranDiv()">




<div id="granDiv"  >
<?}?>
<?if ($print==1){
//Texto  a Imprimir
include('printCabezaCliente.php');

?><table  align=center><tr><td><h4>Informe de Consumo: <?echo $fchdesde?> a <?echo $fchhasta?></h4></td></tr><?

}else{?>

<form action=reporteitem.php>
<table align=center >
<tr><th colspan=3>Busqueda por Fecha de Entrega</th></tr>
<tr><td>
Desde:<input onMouseOver='scwShow(this,event);' id="id_calendario" value='<?if($fchdesde==''){echo $hoy;}else{echo $fchdesde;}?>' name='fchdesde' size='8'> 
</td><td>
Hasta:<input onMouseOver='scwShow(this,event);' id="id_calendario" value='<?if($fchhasta==''){echo $mes;}else{echo $fchhasta;}?>' name='fchhasta' size='8'> 
<td><input type=submit value=Buscar name=buscar></td></tr>
<?if($buscar){?>
<td colspan=2></td><td><button onclick="window.open('reporteitem.php?fchhasta=<?echo $fchhasta;?>&fchdesde=<?echo $fchdesde;?>&print=1&buscar=Buscar','noimporta', 'width=800, height=600, scrollbars =yes, top=250, status=no, toolbar=no, titlebar=no, menubar=no')">Imprimir</button></td></tr>
<?}?>
</table>
</form>

<?}if($buscar){?>
<br>

<table align=center onMouseMove='ejecutarRows()' <?if ($print==1)echo "border=0 CELLSPACING=0"?>>
<tr><th colspan=9>Reporte de Consumo por Secretar&iacute;a <? if ($_SESSION["sipa_admin"]==0){echo ": ".$_SESSION["sipa_sec_username"];}?></th></tr>
<tr><th>Item</th><th>Secretar&iacute;a</th><th>Cant. Pedidos</th><th>Valor Pedido</th><th>Cant. Pagado</th><th>Valor Pagado</th></tr>

<?php include ("conexion.php");


//Consulta acumulativa de valores
$fchdesde=fch_php_mysql($fchdesde);
$fchhasta=fch_php_mysql($fchhasta);

if($_SESSION['sipa_admin']!=1)
$usBuscado="and ped.idusuario=".$_SESSION['sipa_id_username'];

 $sql=("SELECT ped.idalimento, ali.nombre, ped.idsecretaria, sec.secretaria, 

sum(ped.cantidad) as cantidad,

sum(ped.valorpedido)  as valorpedido,

SUM(CASE 
WHEN ped.estado = '5' 
THEN (ped.cantidad)
END) as cantidadpag,

SUM(CASE 
WHEN ped.estado = '5' 
THEN (ped.valorpedido)
END) as valorpag
 
FROM `pedido` as ped,  secretaria as sec, alimento as ali

WHERE 
ped.`idalimento`=ali.id and 
ped.`bitactivo`=1 and 
ped.idsecretaria=sec.id and
ped.fchentrega Between '$fchdesde' and '$fchhasta' 

$usBuscado

group by ped.idalimento, ped.idsecretaria
");

$result=mysql_query($sql);
$row = mysql_num_rows($result);
$i=1;

$resta=1;

$fila=1;

//show tabla de resultados
while ($row = mysql_fetch_array($result)) 
{

//$vpen=(($row['valorini'])-($row['valorpedido']));

//$porc=((($vpen)/($row['valorini']))*100);

if($porc>49)
$img="Bgrafico.jpg";
else if(($porc>11) and ($porc<50))
$img="Bgrafico0.jpg";
else 	
$img="Bgrafico1.jpg";
if($i%2==0){
   ?><tr  bgcolor='#D8D8D8' onmouseover="this.style.backgroundColor='<?echo  $colorOnMouseOver?>'" onmouseout="this.style.backgroundColor='#D8D8D8'">
  
  <?}else{
  
 
?><tr bgcolor='#ffffff' onmouseover="this.style.backgroundColor='<?echo  $colorOnMouseOver?>'" onmouseout="this.style.backgroundColor='#ffffff'">
 <?}
 
 //Subtotales
if($i==1)$sw_ver=1;
else $sw_ver=0;

if($producto!=$row['idalimento'] and $i!=1){
?> <tr  bgcolor=<?echo  $colorOnMouseOver;?> <?if($_SESSION['sipa_admin']!=1){?>class="nover"<?}?>>
<td colspan=2><b>Subtotal:</td>
<td align=center><b><?echo number_format($tcantSub, 0, '', '.')?></td>
<td align=right><b>$<?echo number_format($tpedSub, 0, '', '.')?></td>
<td align=center><b><?echo number_format($tcantpagSub, 0, '', '.')?></td>
<td align=right><b>$<?echo number_format($tpagSub, 0, '', '.')?></td>
</tr>
<?
//Limpiar subtotales
$tcantSub=0;
$tpedSub=0;
$tcantpagSub=0;
$tpagSub=0;





$rowspan=$i-($resta);

$resta=$i;
if($fila>0 && $rowspan>0)
$ejecutarRows.= "f_rowspan($fila, $rowspan);";

$sw_ver=1;
$fila=$i;
} 


if($sw_ver==1){
?>
 
<td id=row<?echo $i?>><?echo $row['idalimento']." - ".($row['nombre'])?></td>
<?
}
?>
<td><?echo $row['idsecretaria']." - ".($row['secretaria'])?></td>
<td align=center><?echo $row['cantidad']?></td>
<td align=right >$<?echo number_format($row['valorpedido'], 0, '', '.')?></td>
<td align=center><? if($row['cantidadpag']==''){echo 0;}else{ echo $row['cantidadpag'];}?></td>
<td align=right >$<?echo number_format($row['valorpag'], 0, '', '.')?></td>


</tr>
<?


//Incrementar Totales
$tcant+=$row['cantidad'];
$tcantpag+=$row['cantidadpag'];
$tped+=$row['valorpedido'];
$tpag+=$row['valorpag'];

//Incrementar Subtotales
$tcantSub+=$row['cantidad'];
$tcantpagSub+=$row['cantidadpag'];
$tpedSub+=$row['valorpedido'];
$tpagSub+=$row['valorpag'];


//asignar producto actual
$producto=$row['idalimento'];

$i++;}//fin while

//Subtotal Final

$rowspan=$i-($fila);
if($fila>0 && $rowspan>0)
$ejecutarRows.= "f_rowspan($fila, $rowspan);";
?>
<tr  bgcolor=<?echo  $colorOnMouseOver;?> <?if($_SESSION['sipa_admin']!=1){?>class="nover"<?}?>>
<td colspan=2><b>Subtotal:</td>
<td align=center><b><?echo number_format($tcantSub, 0, '', '.')?></td>
<td align=right><b>$<?echo number_format($tpedSub, 0, '', '.')?></td>
<td align=center><b><?echo number_format($tcantpagSub, 0, '', '.')?></td>
<td align=right><b>$<?echo number_format($tpagSub, 0, '', '.')?></td>
</tr>
<?

include ("desconexion.php");

//Totales
 ?>
 <tr>
 <td align=center colspan=2><b>Totales:</td>
 <td align=center><b><?echo number_format($tcant, 0, '', '.')?></td>
 <td align=right><b>$<?echo number_format($tped, 0, '', '.')?></td>
  <td align=center><b><?echo number_format($tcantpag, 0, '', '.')?></td>
 <td align=right><b>$<?echo number_format($tpag, 0, '', '.')?></td>
 </tr>
 
</table>

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


<?}//fin tabla resultados
}else{?>
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

  <script>
function f_rowspan(i, numRowspan){


var changeRow="row"+i;
document.getElementById(changeRow).rowSpan=numRowspan;

}
  
 function  ejecutarRows(){
  <?echo $ejecutarRows;?>
  }
  </script>
  
 
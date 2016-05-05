<?
session_start();
if (isset($_SESSION['sipa_username'])) {	?>
<?include  'funciones.php';?>
<?php include ("cabeza.php"); 

?>
<head>

<script src="scw.js" type="text/javascript" language="JavaScript1.5"></script>



</head>

<body onLoad="muestraGranDiv()">

<div id="granDiv">

<?php include ("conexion.php");

if (($_SESSION['sipa_admin'])==0 && $_SESSION["sipa_sec_username"]!=1000) { 
$Var="and  ppto.idsecretaria=".$_SESSION["sipa_sec_username"];

}else{
//Consulta acumulativa de valores

$sql="SELECT sum( valorini-valorNoRequerido) AS ini, (SELECT sum(valorpedido) FROM pedido WHERE bitactivo=1) AS ped,
		(SELECT sum(valorpedido) FROM pedido WHERE estado=5)  AS pag, 
		(sum( valorini ) - (SELECT sum(valorpedido) FROM `pedido` WHERE bitactivo=1) ) AS disponible,
		(SELECT sum(valorpedido) FROM pedido WHERE bitactivo=1)  /sum( valorini )*100 as porc
	FROM presupuesto
";



$result=mysql_query($sql);
$row = mysql_num_rows($result);

while ($row = mysql_fetch_array($result)) 
{
?>
<table align=center>
<tr><th colspan=6>Totales</th></tr>
<tr><th></th><th>Presupuesto:</th><th>Pedido:</th><th colspan=2>Disponible:</th></tr>
<tr><th>Valores:</th>
<td>$<?echo number_format(($row['ini']), 0, '', '.')?></td>
<td>$<?echo number_format(($row['ped']), 0, '', '.')?></td>
<td>$<?echo number_format(($row['disponible']), 0, '', '.')."</td></tr>"?>
<tr><th>Porcentajes:</th>
<td>100%</td>
<td><?echo round($row['porc'],2) ?>%</td>
<td><?echo round((($row['disponible'])/($row['ini']))*100,2) ?>%</td>
</tr>

</table>
<?
}



}



//Consulta PPTO
$sql=("SELECT sec.secretaria,sec.id as idsecretaria, 
				ppto.pedido,ppto.proyecto, ppto.nombre, ppto.valorini+ppto.valorNoRequerido as valorini, 
				ppto.valorpedido, (SELECT sum(valorpedido) FROM `pedido` WHERE idppto=ppto.id and estado=5) valorpagado, prov.proveedor as nomprov 
			FROM `presupuesto` as ppto, secretaria as sec, proveedor as prov
			WHERE 
			ppto.`bitactivo`=1 and
			ppto.idproveedor=prov.id and
			ppto.idsecretaria=sec.id
$Var
 order by  idsecretaria, ppto.proyecto ");
$result=mysql_query($sql);
$row = mysql_num_rows($result);


?>

<br>
<table align=center id='tablaRpteSaldoPpto'><thead>
<tr><th colspan=9>Reporte de Proyectos en General <? if ($_SESSION["sipa_admin"]==0 && $_SESSION["sipa_sec_username"]!=1000){echo "Secretar&iacute;a: ".$_SESSION["sipa_sec_username"];}?></th></tr>
<tr><th>Secretar&iacute;a</th><th>Proyecto</th><th>Compromiso</th><th>Nombre Proyecto</th><th>Valor inicial</th><th>Valor Pedido</th><th colspan=2>Valor Disp. Para Pedidos</th></tr></thead>
<tbody>
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

   ?><tr bgcolor='#D8D8D8' onmouseover="this.style.backgroundColor='<?echo  $colorOnMouseOver?>'" onmouseout="this.style.backgroundColor='#D8D8D8'">
   <?echo "<td align=center><font size=1 color=grey>".$row['idsecretaria']."-".($row['secretaria'])."</td><td>".$row['proyecto']."</td><td>".$row['pedido']."</td><td>".($row['nombre'])."</td><td align=right>$".number_format(($row['valorini']), 0, '', '.')."</td><td align=right>$".number_format(($row['valorpedido']), 0, '', '.')."</td><td align=right>$".number_format($vpen, 0, '', '.')."</td><td><img src='images/".$img."' width='".round($porc)."' HEIGHT='20'>".round($porc)."%</td></tr>";	

}//fin while


include ("desconexion.php");


 ?>
</tbody></table>
<?}else{?>
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
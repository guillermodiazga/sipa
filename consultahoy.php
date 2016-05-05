<?
session_start();
//phpinfo();
if ((isset($_SESSION['sipa_username'])) ||(!isset($_SESSION['sipa_username']))){?>
<?include  'funciones.php';?>
<?php include ("cabeza.php"); 

?>
<head>

<script src="scw.js" type="text/javascript" language="JavaScript1.5"></script>



</head>

<body onLoad="muestraGranDiv()">

<div id="granDiv" style="">

<?php include ("conexion.php");

//Obtener fechas
$hoy=date("d/m/Y");

if ($fchdesde=='')
$fchdesde=$hoy;
if ($fchhasta=='')
$fchhasta=$hoy;


?>
<br>
<table align='center'  border='1' RULES='cols'>

<tr><th><form action='consultahoy.php' onsubmit="return validarbuscar();"><?php echo ("<b>Pedidos de ".($_SESSION['sipa_username'])."</th>")?>
<tr><td align=center>
<b>Fecha</b>
Desde:<input onMouseOver='scwShow(this,event);' id="id_calendario" value='<?echo $fchdesde?>' name='fchdesde' size='8'> 
Hasta:<input onMouseOver='scwShow(this,event);' id="id_calendario" value='<?echo $fchhasta?>' name='fchhasta' size='8'> 
Estado:<select name="estado" >

<option value='*'>Todos</option>

<?echo $sql="SELECT * FROM `estados`";
$result = @mysql_query($sql);
$row = @mysql_num_rows($result);

  while ($row = @mysql_fetch_array($result))
{
//excluir estado borrado
if ($row['id']==1){}else{
?>
<option value=<?echo $row['id']?>><?echo ($row['estado'])?></option>
<?}}?>	

</select>

<input type='submit' value='Buscar' name='buscar'></form>

 </td></tr>

<?php 
 
 //Cambiar el formato de las fechas  a buscar
 $fchdesde=fch_php_mysql($fchdesde);
 $fchhasta=fch_php_mysql($fchhasta);
 
 //Consulta los pedidos hechos por el usuario por dias
$iduser=($_SESSION['sipa_id_username']);

//si busca todos
if($estado!='*')
$estadov="and ped.estado=$estado";

 $consulta=("
 SELECT   ped.estado, ped.id, tali.talimento, ped.fchentrega, ped.hora, ali.nombre as alimento, ped.cantidad, ped.valorpedido, ped.direccion, ped.comentario, ped.fchreg
, ppto.nombre

FROM pedido as ped, tipoalimento as tali, alimento as ali, presupuesto as ppto

WHERE  ped.bitactivo=1 and ped.idusuario=$iduser and ped.fchentrega Between '$fchdesde' and '$fchhasta' 

and ped.idtalimento=tali.id

and ped.idalimento=ali.id

and ped.idppto=ppto.id

$estadov

ORDER BY ped.id

 ");

 $resultado = @mysql_query($consulta);
$row = @mysql_num_rows($resultado);

echo "</table><table align='center' border='0' RULES='cols'>";
if($buscar and $row!=0){
if($row==1){?>
<tr><td align=center colspan=14 bgcolor=#ffffff><font color=#585858><?echo $row?> Resultado</td></tr>
<?}else {?>
<tr><td align=center colspan=14 bgcolor=#ffffff><font color=#585858><?echo $row?> Resultados</td></tr>
<?}?>
<tr BGCOLOR='#007b80'><th><FONT COLOR=#FFFFFF>Estado</th><th><FONT COLOR=#FFFFFF>#</th><th><FONT COLOR=#FFFFFF>Tipo</th><th><FONT COLOR=#FFFFFF>Item</th><th><FONT COLOR=#FFFFFF>Cantidad</th><th><FONT COLOR=#FFFFFF>Valor Pedido</th><th><FONT COLOR=#FFFFFF>Fecha Entrega</th><th><FONT COLOR=#FFFFFF>Hora Entrega</th><th><FONT COLOR=#FFFFFF>Direcci&oacute;n</th><th><FONT COLOR=#FFFFFF>Presupuesto</th><th><FONT COLOR=#FFFFFF>Fecha de Pedido</th><th><FONT COLOR=#FFFFFF>Observaci&oacute;n</th><tr>
<?
$i=1;
$total;
while ($row = @mysql_fetch_array($resultado)) 
{
if(($row['estado'])==2)
{$estado="<img src='images/pendiente.png' alt='= Pendiente'>";
}else if(($row['estado'])==3)
{$estado="<img src='images/liberado.png' alt='+ Aprobado'>";
}else if(($row['estado'])==4)
{$estado="<img src='images/rechazado.png' alt='X Rechazado'>";
}else if(($row['estado'])==5)
{$estado="<img src='images/pagado.png' alt='$ Pagado'>";
}else if(($row['estado'])==6)
{$estado="<img src='images/despacho.png' alt='>Para Despacho'>";
}else if(($row['estado'])==7)
{$estado="<img src='images/rechazadoprov.png' alt='X Rechazado T&iacute;a Mima'>";}

if($i%2==0){
   ?><tr bgcolor='#D8D8D8' onmouseover="this.style.backgroundColor='<?echo  $colorOnMouseOver?>'" onmouseout="this.style.backgroundColor='#D8D8D8'">	
<?}else
{
   ?><tr bgcolor='#ffffff' onmouseover="this.style.backgroundColor='<?echo  $colorOnMouseOver?>'" onmouseout="this.style.backgroundColor='ffffff'"><?
}
 echo "<td align=center>".$estado."</td><td>".$row['id']."</td><td>".($row['talimento'])."</td><td>".($row['alimento'])."</td><td align=right>".$row['cantidad']."</td><td align=right>$".number_format(($row['valorpedido']), 0, '', '.')."</td><td>".fch_mysql_php($row['fchentrega'])."</td><td>".$row['hora']."</td><td>".$row['direccion']."</td><td>".($row['nombre'])."</td><td>".($row['fchreg'])."</td><td>".($row['comentario'])."</td></tr>";	

 $total=$total+($row['valorpedido']);
$i++;
}//fin while


echo " <tr='center'><td colspan=5><b>Total</td><td align=right><b>$".number_format($total, 0, '', '.')."</th></tr>";
echo " <table align='center'><tr><td><form action='index.php'><input value='Inicio' type='submit'></tr></td></table>";
}else { if($buscar)
echo "0 Resultados ";}

@mysql_free_result($resultado);

@mysql_close($con); ?>
</table>
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
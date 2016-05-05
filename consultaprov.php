<?
session_start();
if ((isset($_SESSION['sipa_username'])) ||(!isset($_SESSION['sipa_username']))){?>
<?include  'funciones.php';?>
<?php include ("cabeza.php"); 

?>
<head>

<script src="scw.js" type="text/javascript" language="JavaScript1.5"></script>



</head>

<body onLoad="muestraGranDiv()">
<div id="cargando" style="width: 357px; height: 100px; position: absolute; padding-top:20px; text-align:center; left: 2px;">
<div align="center"><br><br><br><strong><img src='images/cargando.gif'>Por favor espere un momento... </strong></div>
</div>
<div id="granDiv" style="visibility:hidden;">

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

<tr><th><form action='consultaprov.php' onsubmit="return validarbuscar();"><?php echo ("<b>Pedidos en General</th>")?>
<tr><td align=center>
Pedido:<input  value='<?echo $pedido?>' name='pedido' size='4'> 
<b>Fecha</b>
Desde:<input onMouseOver='scwShow(this,event);' id="id_calendario" value='<?echo $fchdesde?>' name='fchdesde' size='8'> 
Hasta:<input onMouseOver='scwShow(this,event);' id="id_calendario" value='<?echo $fchhasta?>' name='fchhasta' size='8'> 
Secretar&iacute;a:<select name="secretaria"   >

<option value='*'>Todos</option>

<? $sql="SELECT * FROM `secretaria` order by id";
$result = @mysql_query($sql);
$row = @mysql_num_rows($result);

  while ($row = @mysql_fetch_array($result))
{
//excluir estado borrado
if ($row['id']==1){}else{
?>
<option value=<?echo $row['id']?>><?echo $row['id']."-".($row['secretaria'])?></option>
<?}}?>	

</select>

Estado:<select name="estado" >

<option value='*'>Todos</option>

<? $sql="SELECT * FROM `estados`";
$result = @mysql_query($sql);
$row = @mysql_num_rows($result);

  while ($row = @mysql_fetch_array($result))
{
?>
<option value=<?echo $row['id']?>><?echo (($row['estado']))?></option>
<?}?>	

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

if($secretaria!='*')
 $secretariav="and ppto.idsecretaria =$secretaria";

  $consulta=("
SELECT  ped.factura, ped.remision, ped.idppto, ped.estado,est.estado as nomestado, sec.secretaria, ped.id, ped.idusuario, us.nombre as usnam,  ppto.idsecretaria, tali.talimento, ped.fchentrega, ped.hora, ali.nombre as alimento, ped.cantidad, ped.valorpedido, ped.direccion, ped.comentario, ped.fchreg
, ppto.nombre

FROM secretaria as sec, estados as est, pedido as ped, usuario as us, tipoalimento as tali, alimento as ali, presupuesto as ppto

WHERE   

ped.fchentrega Between '$fchdesde' and '$fchhasta' 

and ped.idtalimento=tali.id

and ped.idalimento=ali.id

and ped.idppto=ppto.id

and ped.idusuario=us.id

and  ped.estado=est.id

and ppto.idsecretaria=sec.id

$estadov

$secretariav

ORDER BY ped.fchentrega,  ped.id,  ped.idsecretaria 

 ");
 
 //si busca por pedido
if($pedido!='')
{
$consulta=("
SELECT  ped.factura, ped.idppto, ped.remision, ped.estado,est.estado as nomestado, sec.secretaria, ped.id, ped.idusuario, us.nombre as usnam,  ppto.idsecretaria, tali.talimento, ped.fchentrega, ped.hora, ali.nombre as alimento, ped.cantidad, ped.valorpedido, ped.direccion, ped.comentario, ped.fchreg
, ppto.nombre

FROM secretaria as sec, estados as est, pedido as ped, usuario as us, tipoalimento as tali, alimento as ali, presupuesto as ppto

WHERE 
ped.id=$pedido 

and ped.idtalimento=tali.id

and ped.idalimento=ali.id

and ped.idppto=ppto.id

and ped.idusuario=us.id

and  ped.estado=est.id

and ppto.idsecretaria=sec.id


 order by ped.fchentrega, ped.id");
 }

 $resultado = @mysql_query($consulta);
$row = @mysql_num_rows($resultado);

echo "</table><table align='center' border='0' RULES='cols'>";
if($buscar and $row!=0){

if($row==1){?>
<tr><td align=center colspan=14 bgcolor=#ffffff><font color=#585858><?echo $row?> Resultado</td></tr>
<?}else {?>
<tr><td align=center colspan=14 bgcolor=#ffffff><font color=#585858><?echo $row?> Resultados</td></tr>
<?}?>
<tr BGCOLOR='#007b80'><th colspan=2>Estado</th><th>#</th><th>Secretaria</th><th>Persona</th><th>Tipo</th><th>Item</th><th>Cantidad</th><th>Valor Pedido</th><th>Fecha Entrega</th><th>Hora Entrega</th><th>Direcci&oacute;n</th><th>Presupuesto</th><th>Fecha de Pedido</th><th>Observaci&oacute;n</th><th>Remisi&oacute;n</th><th>Factura</th><tr>
<?
$i=1;
$total;
while ($row = @mysql_fetch_array($resultado)) 
{
if(($row['estado'])==1)
{$estado="<img src='images/anular.png' alt='- Anulado'>";
}if(($row['estado'])==2)
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

//si esta anulado sera negativo
if(($row['estado'])==1)
$row['valorpedido']=$row['valorpedido']*0;

if($i%2==0){
   ?><tr bgcolor='#D8D8D8' onmouseover="this.style.backgroundColor='<?echo  $colorOnMouseOver?>'" onmouseout="this.style.backgroundColor='#D8D8D8'">
   <?
}else
{
   ?><tr bgcolor='#ffffff' onmouseover="this.style.backgroundColor='<?echo  $colorOnMouseOver?>'" onmouseout="this.style.backgroundColor='ffffff'">
   <?} ?>
<td><img src='images/Print.png' name='Ver' height='<?echo $tamano?>'width='<?echo $tamano?>' alt='Imprimir Pedido. <?echo $row['id'];?>' onclick="javascript:window.open('remision.php?ped=<?echo $row['id'];?>','noimporta', 'width=800, height=600, scrollbars =yes, top=250, status=no, toolbar=no, titlebar=no, menubar=no, urlbar=no')"></td>   
<?echo "<td align=center><font size=1 color=grey>".$estado." ".($row['nomestado'])."</td><td>".($row['id'])."</td><td>".($row['idsecretaria'])."-".($row['secretaria'])."</td><td>".($row['usnam'])."</td><td>".($row['talimento'])."</td><td>".($row['alimento'])."</td><td align=right>".$row['cantidad']."</td><td align=right>$".number_format(($row['valorpedido']), 0, '', '.')."</td><td>".fch_mysql_php($row['fchentrega'])."</td><td>".$row['hora']."</td><td>".($row['direccion'])."</td><td>".$row['idppto']."</td><td>".($row['fchreg'])."</td><td>".($row['comentario'])."</td><td>".($row['remision'])."</td><td>".($row['factura'])."</td></tr>";	


 $total=$total+($row['valorpedido']);
 $i++;
}//fin while

echo " <tr='center'><td colspan=8><b>Total</td><td align=right><b>$".number_format($total, 0, '', '.')."</th></tr>";
echo " <table align='center'><tr><td><form action='index.php'><input value='Inicio' type='submit'></tr></td></table>";
}else echo "0 Resultados ";

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
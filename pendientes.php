<?
session_start();
if (isset($_SESSION['sipa_username'])) {
include "conexion.php";

$user=$_SESSION['sipa_id_username'];
?>
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

<table align='center'  border='1' RULES='cols'>
<?//si admin
 if (($_SESSION['sipa_admin'])==1){
 $consulta=("
SELECT   ped.estado, sec.secretaria, ped.id, ped.idsecretaria, tali.talimento, ped.fchentrega, ped.hora, ali.nombre as alimento, ped.cantidad, ped.valorpedido, ped.idppto, ped.direccion, ped.comentario, ped.personarecibe, ppto.nombre as nomppto

FROM pedido as ped, usuario as us, tipoalimento as tali, alimento as ali, presupuesto as ppto, secretaria as sec

WHERE  ped.bitactivo=1

and ped.idsecretaria=sec.id

and (ped.estado=2 or ped.estado=7)

and ped.idtalimento=tali.id

and ped.idalimento=ali.id

and ped.idppto=ppto.id

and ped.idusuario=us.id

 ");
  
 }else { //si no es admin
 $consulta=("
SELECT distinct  ped.id, ped.estado, tali.talimento, ped.fchentrega, ped.hora, ali.nombre as alimento, ped.cantidad, ped.valorpedido, ped.direccion, ped.comentario, ped.fchreg
, ppto.nombre as nomppto

FROM pedido as ped, usuario as us, tipoalimento as tali, alimento as ali, presupuesto as ppto

WHERE 
ped.bitactivo=1 


and ped.idusuario=$user

and ped.idtalimento=tali.id

and ped.idalimento=ali.id

and ped.idppto=ppto.id

and (ped.estado Between 2  and 4)and ped.estado!=3	
	");}
$resultado = @mysql_query($consulta);
$row = @mysql_num_rows($resultado);

echo "<table align='center' border='0' RULES='cols'>";
if (($_SESSION['sipa_admin'])==1){ //si es administrador
if($row!=1){
?><tr><td bgcolor=ffffff colspan=2 valign=top align=center><input type=image src='images/actualizar.png' width=30 height=30 onclick='refrescar()' Alt='Actualizar Bandeja de Entrada'></td><td  valign=center align=center colspan=11 bgcolor=#ffffff><font color=#585858><?echo $row?> Pedidos Pendientes </td><td colspan=2 bgcolor=#ffffff valign=center align=center><form action='index.php' method='POST'><input type=hidden value=1 name=aprobartodo><input value="Aprobar_Todo" name="Aprobar_Todo"  alt="Aprobar Todo" type=image src='images/bandera_green.png' onclick="return confaprobartodo()" height='0'width='0'></form></td></tr><?
}else{
?><tr><td bgcolor=ffffff colspan=2><input type=image src='images/actualizar.png' width=30 height=30 onclick='refrescar()' Alt='Actualizar Bandeja de Entrada'></td><td align=center colspan=12 bgcolor=#ffffff><font color=#585858><?echo $row?> Pedido Pendiente </td></tr><?
}
echo "<tr BGCOLOR='#007b80'><th colspan=2><FONT COLOR=#FFFFFF>Acci&oacute;n</th><th><FONT COLOR=#FFFFFF>Sec</th><th><FONT COLOR=#FFFFFF>#</th><th><FONT COLOR=#FFFFFF>Tipo</th><th><FONT COLOR=#FFFFFF>Item</th><th><FONT COLOR=#FFFFFF>Cant</th><th><FONT COLOR=#FFFFFF>Valor Pedido</th><th><FONT COLOR=#FFFFFF>Fecha Entrega</th><th><FONT COLOR=#FFFFFF>Hora Entrega</th><th><FONT COLOR=#FFFFFF>Direcci&oacute;n</th><th><FONT COLOR=#FFFFFF>Presupuesto</th><th><FONT COLOR=#FFFFFF>Recibe</th><th><FONT COLOR=#FFFFFF>Observaci&oacute;n</th><tr>";
}else{//no es Admin
if($row!=1){
?><tr><td bgcolor=ffffff colspan=2><input type=image src='images/actualizar.png' width=30 height=30 onclick='refrescar()' Alt='Actualizar Bandeja de Entrada'></td><td align=center colspan=12 bgcolor=#ffffff><font color=#585858><?echo $row?> Pedidos Pendientes</td></tr><?
}else{
?><tr><td bgcolor=ffffff colspan=2><input type=image src='images/actualizar.png' width=30 height=30 onclick='refrescar()' Alt='Actualizar Bandeja de Entrada'></td><td align=center colspan=12 bgcolor=#ffffff><font color=#585858><?echo $row?> Pedido Pendiente</td></tr><?
}
echo "<tr BGCOLOR='#007b80'><th><FONT COLOR=#FFFFFF>Estado</th><th>Acci&oacute;n</th><th><FONT COLOR=#FFFFFF>#</th><th><FONT COLOR=#FFFFFF>Tipo</th><th><FONT COLOR=#FFFFFF>Item</th><th><FONT COLOR=#FFFFFF>Cant</th><th><FONT COLOR=#FFFFFF>Valor Pedido</th><th><FONT COLOR=#FFFFFF>Fecha Entrega</th><th><FONT COLOR=#FFFFFF>Hora Entrega</th><th><FONT COLOR=#FFFFFF>Direcci&oacute;n</th><th><FONT COLOR=#FFFFFF>Presupuesto</th><th><FONT COLOR=#FFFFFF>Fecha de Pedido</th><th><FONT COLOR=#FFFFFF>Observaci&oacute;n</th><tr>";
}
$i=1;


  if(($_SESSION['sipa_admin'])==1 ){
while ($row = @mysql_fetch_array($resultado)) 
{
$iden=($row['id']);

if($i%2==0){?>
   <tr bgcolor='#D8D8D8' onmouseover="this.style.backgroundColor='<?echo  $colorOnMouseOver?>'" onmouseout="this.style.backgroundColor='#D8D8D8'"><td align=center><a href='rechazar.php?rechazar=<?echo $iden?>'><img src='images/rechazado.png' alt='Rechazar' border=0  height='15'width='15'></a><a href='index.php?aprobar=<?echo $iden?>'><img onclick="return confaprobar()" src='images/liberado.png' alt='Aprobar' border=0 height='15'width='15'><?//botn detalle rechazo?>
      </td><td><img src='images/b_view.png' name='Ver' height='<?echo $tamano?>'width='<?echo $tamano?>' alt='Ver historial de estados ped. <?echo $iden?>' onclick="javascript:window.open('verestados.php?ped=<?echo $iden;?>','noimporta', 'width=650, height=400, scrollbars =yes, top=250, status=no, toolbar=no, titlebar=no, menubar=no')">
<?//boton modificar?><a href='modificar.php?buscar=true&vbuscado=<?echo $iden?>'><img src='images/mod.png' alt='Modificar Pedido <?echo $iden?>' border=0></a>
	<?
}else
{
   ?><tr bgcolor='#ffffff' onmouseover="this.style.backgroundColor='<?echo  $colorOnMouseOver?>'" onmouseout="this.style.backgroundColor='ffffff'"><td align=center><a href='rechazar.php?rechazar=<?echo $iden?>'><img src='images/rechazado.png' alt='Rechazar' border=0  height='15'width='15'></a><a href='index.php?aprobar=<?echo $iden?>'><img onclick="return confaprobar()" src='images/liberado.png' alt='Aprobar' border=0 height='15'width='15'><?//botn detalle rechazo?>
      </td><td><img src='images/b_view.png' name='Ver' height='<?echo $tamano?>'width='<?echo $tamano?>' alt='Ver historial de estados ped. <?echo $iden?>' onclick="javascript:window.open('verestados.php?ped=<?echo $iden;?>','noimporta', 'width=650, height=400, scrollbars =yes, top=250, status=no, toolbar=no, titlebar=no, menubar=no')">
<a href='modificar.php?buscar=true&vbuscado=<?echo $iden?>'><img src='images/mod.png' alt='Modificar Pedido <?echo $iden?>' border=0></a>
<?	}echo "</a></td><td>".$row['idsecretaria']."-".($row['secretaria'])."</td><td>".$iden=($row['id'])."</td><td>".($row['talimento'])."</td><td>".($row['alimento'])."</td><td align=right>".$row['cantidad']."</td><td align=right>$".number_format(($row['valorpedido']), 0, '', '.')."</td><td>".fch_mysql_php($row['fchentrega'])."</td><td>".$row['hora']."</td><td>".($row['direccion'])."</td><td>".$row['idppto']." ".($row['nomppto'])."</td><td>".($row['personarecibe'])."</td><td>";
 if($row['estado']==7)
 {echo "<font color=red>Rechazado por T&iacute;a Mima!";} 
 echo ($row['comentario'])."</td></tr>";	

 $i++;
}//fin while
?>
<?
}else
{//Si no es adiministrador
while ($row = @mysql_fetch_array($resultado)) 
{
if(($row['estado'])==2)
{$estado="<img src='images/pendiente.png' alt='= Pendiente' height='15'width='15'>";
}else if(($row['estado'])==3)
{$estado="<img src='images/liberado.png' alt='+ Aprobado' height='15'width='15'>";
}else if(($row['estado'])==4)
{$estado="<img src='images/rechazado.png' alt='X Rechazado' height='15'width='15'>";
}else if(($row['estado'])==5)
{$estado="<img src='images/pagado.png' alt='$ Pagado' height='15'width='15'>";}

$iden=($row['id']);

if($i%2==0){
   ?><tr bgcolor='#D8D8D8' onmouseover="this.style.backgroundColor='<?echo  $colorOnMouseOver?>'" onmouseout="this.style.backgroundColor='#D8D8D8'"><?echo "<td align=center>".$estado;
   //botn detalle rechazo?>
      </td><td><img src='images/b_view.png' name='Ver' height='<?echo $tamano?>'width='<?echo $tamano?>' alt='Ver historial de estados ped. <?echo $iden?>' onclick="javascript:window.open('verestados.php?ped=<?echo $iden;?>','noimporta', 'width=450, height=200, scrollbars =yes, top=250, status=no, toolbar=no, titlebar=no, menubar=no')">
<?//boton modificar?><a href='modificar.php?buscar=true&vbuscado=<?echo $iden?>'><img src='images/mod.png' alt='Modificar Pedido <?echo $iden?>' border=0></a>
<img src='images/Print.png' name='Ver' height='<?echo $tamano?>'width='<?echo $tamano?>' alt='Imprimir Pedido. <?echo $iden?>' onclick="javascript:window.open('pedido.php?ped=<?echo $iden;?>','noimporta', 'width=800, height=600, scrollbars =yes, top=250, status=no, toolbar=no, titlebar=no, menubar=no, urlbar=no')">
	  <?echo "</td><td>".$iden=($row['id'])."</td><td>".($row['talimento'])."</td><td>".($row['alimento'])."</td><td align=right>".$row['cantidad']."</td><td align=right>$".number_format(($row['valorpedido']), 0, '', '.')."</td><td>".fch_mysql_php($row['fchentrega'])."</td><td>".$row['hora']."</td><td>".($row['direccion'])."</td><td>".($row['nomppto'])."</td><td>".($row['fchreg'])."</td><td>".($row['comentario'])."</td></tr>";	
}else
{
   ?><tr bgcolor='#ffffff' onmouseover="this.style.backgroundColor='<?echo  $colorOnMouseOver?>'" onmouseout="this.style.backgroundColor='ffffff'"><? echo "<td align=center>".$estado;
      //botn detalle rechazo?>
</td><td><img src='images/b_view.png' name='Ver' height='<?echo $tamano?>'width='<?echo $tamano?>' alt='Ver historial de estados ped. <?echo $iden?>' onclick="javascript:window.open('verestados.php?ped=<?echo $iden;?>','noimporta', 'width=450, height=200, scrollbars =yes, top=250, status=no, toolbar=no, titlebar=no, menubar=no')">
<?//boton modificar?><a href='modificar.php?buscar=true&vbuscado=<?echo $iden?>'><img src='images/mod.png' alt='Modificar Pedido <?echo $iden?>' border=0></a>
<img src='images/Print.png' name='Ver' height='<?echo $tamano?>'width='<?echo $tamano?>' alt='Imprimir Pedido. <?echo $iden?>' onclick="javascript:window.open('pedido.php?ped=<?echo $iden;?>','noimporta', 'width=800, height=600, scrollbars =yes, top=250, status=no, toolbar=no, titlebar=no, menubar=no, urlbar=no')">
      <?echo  "</td><td>".$row['id']."</td><td>".($row['talimento'])."</td><td>".($row['alimento'])."</td><td align=right>".$row['cantidad']."</td><td align=right>$".number_format(($row['valorpedido']), 0, '', '.')."</td><td>".fch_mysql_php($row['fchentrega'])."</td><td>".$row['hora']."</td><td>".($row['direccion'])."</td><td>".($row['nomppto'])."</td><td>".($row['fchreg'])."</td><td>".($row['comentario'])."</td></tr>";	
}
 
$i++;
}//fin while
?><tr><td align=center colspan=14 bgcolor=#ffffff><font color=#585858><?echo $row?> Pedidos Aprobados Recientemente</td></tr><?
//Consulta de pedidos aprobados en la ultima semana
$antes=mktime(0, 0, 0, date("m")  , date("d")-5, date("Y"));
$mas=mktime(0, 0, 0, date("m")  , date("d")+5, date("Y"));
$fechantes=date("Y-m-d",$antes);
$fechamas=date('Y-m-d',$mas);
$consulta=("
SELECT distinct  ped.id, ped.estado, tali.talimento, ped.fchentrega, ped.hora, ali.nombre as alimento, ped.cantidad, ped.valorpedido, ped.direccion, ped.comentario, ped.fchreg
, ppto.nombre as nomppto

FROM pedido as ped, usuario as us, tipoalimento as tali, alimento as ali, presupuesto as ppto

WHERE 
ped.bitactivo=1 

and ped.idusuario=$user

and ped.idtalimento=tali.id

and ped.idalimento=ali.id

and ped.idppto=ppto.id

and  ped.estado=3	
and (ped.fchentrega Between '$fechantes'  and '$fechamas')	
	");
$resultado = @mysql_query($consulta);
$row = @mysql_num_rows($resultado);

while ($row = @mysql_fetch_array($resultado)) 
{
if(($row['estado'])==2)
{$estado="<img src='images/pendiente.png' alt='= Pendiente' height='15'width='15'>";
}else if(($row['estado'])==3)
{$estado="<img src='images/liberado.png' alt='+ Aprobado' height='15'width='15'>";
}else if(($row['estado'])==4)
{$estado="<img src='images/rechazado.png' alt='X Rechazado' height='15'width='15'>";
}else if(($row['estado'])==5)
{$estado="<img src='images/pagado.png' alt='$ Pagado' height='15'width='15'>";}

$iden=($row['id']);

if($i%2==0){
   ?><tr bgcolor='#D8D8D8' onmouseover="this.style.backgroundColor='<?echo  $colorOnMouseOver?>'" onmouseout="this.style.backgroundColor='#D8D8D8'"><?echo "<td align=center>".$estado;
   //botn detalle rechazo?>
      </td><td><img src='images/b_view.png' name='Ver' height='<?echo $tamano?>'width='<?echo $tamano?>' alt='Ver historial de estados ped. <?echo $iden?>' onclick="javascript:window.open('verestados.php?ped=<?echo $iden;?>','noimporta', 'width=450, height=200, scrollbars =yes, top=250, status=no, toolbar=no, titlebar=no, menubar=no')">
<?//boton modificar?><a href='modificar.php?buscar=true&vbuscado=<?echo $iden?>'><img src='images/mod.png' alt='Modificar Pedido <?echo $iden?>' border=0></a>
	  <?echo "</td><td>".$iden=($row['id'])."</td><td>".($row['talimento'])."</td><td>".($row['alimento'])."</td><td align=right>".$row['cantidad']."</td><td align=right>$".number_format(($row['valorpedido']), 0, '', '.')."</td><td>".fch_mysql_php($row['fchentrega'])."</td><td>".$row['hora']."</td><td>".($row['direccion'])."</td><td>".($row['nomppto'])."</td><td>".($row['fchreg'])."</td><td>".($row['comentario'])."</td></tr>";	
}else
{
   ?><tr bgcolor='#ffffff' onmouseover="this.style.backgroundColor='<?echo  $colorOnMouseOver?>'" onmouseout="this.style.backgroundColor='ffffff'"><? echo "<td align=center>".$estado;
      //botn detalle rechazo?>
</td><td><img src='images/b_view.png' name='Ver' height='<?echo $tamano?>'width='<?echo $tamano?>' alt='Ver historial de estados ped. <?echo $iden?>' onclick="javascript:window.open('verestados.php?ped=<?echo $iden;?>','noimporta', 'width=450, height=200, scrollbars =yes, top=250, status=no, toolbar=no, titlebar=no, menubar=no')">
<?//boton modificar?><a href='modificar.php?buscar=true&vbuscado=<?echo $iden?>'><img src='images/mod.png' alt='Modificar Pedido <?echo $iden?>' border=0></a>

      <?echo  "</td><td>".$row['id']."</td><td>".($row['talimento'])."</td><td>".($row['alimento'])."</td><td align=right>".$row['cantidad']."</td><td align=right>$".number_format(($row['valorpedido']), 0, '', '.')."</td><td>".fch_mysql_php($row['fchentrega'])."</td><td>".$row['hora']."</td><td>".($row['direccion'])."</td><td>".($row['nomppto'])."</td><td>".($row['fchreg'])."</td><td>".($row['comentario'])."</td></tr>";	
}
 
$i++;
}//fin while

}

?>
</table>


<?
include "desconexion.php";
}else{?>
<SCRIPT LANGUAGE="javascript"> location.href = "index.php";</SCRIPT>
<?}
?>


<?
session_start();
if (isset($_SESSION['sipa_username'])) {
include "conexion.php";

$user=$_SESSION['sipa_id_username'];
?>
<head>

</head>
<?
include "cabeza.php";
include "conexion.php";
include "funciones.php";?>

<body onLoad="muestraGranDiv()">

<div id="granDiv" >

<?
// Mostrar la sumatoria de las facturas ingresadas
if($ver){
$i=1;
$vlrfactura=0;

while($i<$regs)
{

//Variables para recorrer todos los campos
$ped="pedido".$i;
$pedido=$$ped; 

$fac="factura".$i;
$factura=$$fac; 

$re="remision".$i;
$remision=$$re; 

$vp="valorpedido".$i;
$valorpedido=$$vp; 

if($i==1){
echo "<table align=center border=0 cellspacing=0>";
echo"<tr><th colspan=2>Valor Facturas Ingresadas</th></tr>";
echo"<tr><th>Factura</th><th>Valor</th></tr>";
}

if(($factura!='') && ($remision!='') )
{




if(($facturaA!=$factura)&&($vlrfactura!=0)){

echo "<tr><td>".$facturaA." </td><td align=right> $".number_format(($vlrfactura), 0, '', '.')."</td></tr>";
$total=$total+$vlrfactura;
$vlrfactura=0; 
}
$facturaA=$factura;
$vlrfactura=$valorpedido+$vlrfactura;
}
	

$i++;
}//fin while
$total=$total+$vlrfactura;
echo "<tr><td>".$facturaA." </td><td align=right> $".number_format(($vlrfactura), 0, '', '.')."</td></tr>";
echo "<tr><th>Total: </td><td align=right><b> $".number_format(($total), 0, '', '.')."</td></tr>";


}//fin verificar
?>

<table align='center'  border='1' RULES='cols'>
<?

if(($l1=='')||($l2==''))
{
$l1=1;
$l2=100;
}


 $consulta=("
SELECT   sec.secretaria, ped.id, ped.factura, ped.remision, ped.idsecretaria, ped.idppto, tali.talimento, ped.fchentrega, ped.hora, ali.nombre as alimento, ped.cantidad, ped.valorpedido, ped.direccion, ped.comentario, ped.fchreg, ppto.nombre as nomppto

FROM pedido as ped, usuario as us, tipoalimento as tali, alimento as ali, presupuesto as ppto, secretaria as sec

WHERE  ped.bitactivo=1

and ped.estado=6

and ped.idsecretaria=sec.id

and ped.idtalimento=tali.id

and ped.idalimento=ali.id

and ped.idppto=ppto.id

and ped.idusuario=us.id

order by ped.idsecretaria ,ped.id


 ");
$resultado = @mysql_query($consulta);
$row = @mysql_num_rows($resultado);

echo "<br><table align='center' border='0' RULES='cols' >";

?>
<tr>
<td align=center colspan=4><form action='pagar.php' method=post>Limites:
<input name=l1 size=3 value=<?echo $l1 ?>>
<input name=l2 size=3 value=<?echo $l2 ?>>
<input type=submit value='>'>
</form></td><td colspan=5 align=center>

<form action='pagar.php' method=post>

<input name=l1 type=hidden size=3 value=<?echo $l1 ?>>
<input name=l2 type=hidden  size=3 value=<?echo $l2 ?>>

<input type=submit value='Verificar' name='ver'>
<input type=submit value='Grabar Todo' name='grabar' onclick='return confgrabar()' >
</td><td >
<input  size='30' maxlength="50" id='inFactTodo' placeholder='Ingresar la misma factura a todos'>
<button>ok</button>
<script type="text/javascript">

		$("#inFactTodo").change(function(){
			$(".csFactura").val(this.value);
		});
</script>
</td>
</tr>
</table>
<table align='center'  border='1' RULES='cols' id='tablaPagos'>
<?
$sql=" select id from pedido where estado=6 and bitactivo=1";
$result = @mysql_query($sql);
$rows = @mysql_num_rows($result);

if(($l1<1)||($l2<1)||($l2<$l1))
{
echo "<img src='images/aviso.png'>Limites Incorrectos!";
$l1=1;
$l2=100;
}


//Mostrar resultados
if($row!=1){
?><tr><td align=center colspan=14 bgcolor=#ffffff><font color=#585858><?echo $l2-$l1+1?>  Pedidos Pendientes para Pagar de <?echo $rows?></td></tr><?
}else{
?><tr><td align=center colspan=14 bgcolor=#ffffff><font color=#585858><?echo $l2-$l1+1?>  Pedido Pendiente para Pagar de <?echo $rows?></td></tr><?
}
if($row>0){
echo "<tr BGCOLOR='#007b80'><th>#<FONT COLOR=#FFFFFF></th><th><FONT COLOR=#FFFFFF>Sec</th><th><FONT COLOR=#FFFFFF>Tipo</th><th><FONT COLOR=#FFFFFF>Item</th><th><FONT COLOR=#FFFFFF>Cant</th><th><FONT COLOR=#FFFFFF>Valor Pedido</th><th><FONT COLOR=#FFFFFF>Proyecto</th><th><FONT COLOR=#FFFFFF>Nom Proyecto</th><th><FONT COLOR=#FFFFFF>Fecha Entrega</th><th>#Pedido</th><th><FONT COLOR=#FFFFFF>Factura</th><th><FONT COLOR=#FFFFFF>Remisi&oacute;n</th><tr>";
$i=1;


//vector de enumeración
$vecid[$row][2];
while ($row = @mysql_fetch_array($resultado)) 
{


 $vecid[$i][2]=$i;

if(($vecid[$i][2]>=$l1)and($vecid[$i][2]<=$l2))
{
$iden=($row['id']);

 	if($secre!=$row['idsecretaria']){

	if($secre!='')
echo "<tr><td colspan=5><b>Subtotal ".$secret.":<hr></td><td align=right><b>$".number_format(($subtotal), 0, '', '.')."</td></tr>";
$subtotal=0;

}

   ?><tr bgcolor='#D8D8D8' onmouseover="this.style.backgroundColor='<?echo  $colorOnMouseOver?>'" onmouseout="this.style.backgroundColor='#D8D8D8'"><td align=center><img src='images/b_view.png' name='Ver' height='<?echo $tamano?>'width='<?echo $tamano?>' alt='Ver historial de estados ped. <?echo $iden?>' onclick="javascript:window.open('verestados.php?ped=<?echo $iden;?>','noimporta', 'width=450, height=200, scrollbars =yes, top=250, status=no, toolbar=no, titlebar=no, menubar=no')">

	<?/*emerson*/ echo "</a>".$vecid[$i][2]."</td><td class='secretaria'>".$row['idsecretaria']."</td><td>".($row['talimento'])."</td><td>".($row['alimento'])."</td><td align=right>".$row['cantidad']."</td><td align=right><input  type=hidden value=".$row['valorpedido']." name='valorpedido".$i."'>$".number_format(($row['valorpedido']), 0, '', '.')."</td><td><input type=hidden name='ppto".$i."' value='".$row['idppto']."'>".$row['idppto']."</td><td>".($row['nomppto'])."</td><td>".fch_mysql_php($row['fchentrega'])."</td><td align=center><input name='pedido".$i."' type=hidden value='".$iden=($row['id'])."'>".$row['id']."</td><td><input class='csFactura' tabindex='$i' size=10 name='factura".$i."'";
	
	//si en la db hay factura o remision; lo muestra, sino trae el que habia en el campo o vacio
	if($row['factura']!='')
	echo	"value='".($row['factura'])."'></td><td><input size=4  name='remision".$i."'";
	else{
	$fac="factura".$i;
	$factura=$$fac; 

	echo	"value='".$factura."' "; ?> 
	
	onFocus="getFactura(0,1, <?echo $i?>)"
	onchange="getFactura(document.getElementById('factura<?echo $i?>').value,0,0)"></td><td><input size=4 name='remision<?echo $i?>';
	<?}
	
	if($row['remision']!='')	
	echo "value='".($row['remision'])."'></td></tr>";	
	else{
	$rem="remision".$i;
	$remision=$$rem; 

	echo "value='".$row['id']."'></td></tr>";
	}
		
	$secre=$row['idsecretaria'];
	$secret=$row['idsecretaria']."-".($row['secretaria']);
 $subtotal=$subtotal+$row['valorpedido'];
$total=$row['valorpedido']+$total;
}	$i=$i+1;


}//fin while

echo "<tr><td colspan=5 ><b>Subtotal ".$secret.":<hr></td><td align=right><b>$".number_format(($subtotal), 0, '', '.')."</td></tr>";
echo "<tr><td colspan=5><b>Total General:<hr></td><td align=right><b>$".number_format(($total), 0, '', '.')."</td></tr>";

 ?><input type=hidden name=regs  value=<?echo $i?>><?

?>
<tr>
<td align=center colspan=12><input type=submit value='Verificar' name='ver'>
<input type=submit value='Grabar Todo' name='grabar' onclick='return confgrabar()' ></td>
</tr>
</form>
</table>

<?
}



if($grabar){
$i=1;

while($i<$regs)
{

$ped="pedido".$i;
$pedido=$$ped; 

$fac="factura".$i;
$factura=$$fac; 

$re="remision".$i;
$remision=$$re; 

$vp="valorpedido".$i;
$valorpedido=$$vp; 

$presu="ppto".$i;
$ppto=$$presu; 

if(($factura!='') && ($remision!='') )
{
//Actualizar Ped
 $sql="update pedido set remision='$remision', factura='$factura', estado=5 where id=$pedido;";
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
NULL , $pedido, '5', '', '$log'
);";
mysql_query($sql);
}
if((($factura!='') && ($remision=='') )||(($factura=='') && ($remision!='') ))
{
$mensaje=$mensaje.$pedido.", ";

}

//afectar ppto
$sql="UPDATE `presupuesto` SET valorpagado = valorpagado+$valorpedido WHERE CONVERT( `presupuesto`.`id` USING utf8 ) = '$ppto'";
mysql_query($sql);

$i++;
}//fin while
if($mensaje!='')
//echo "<script>alert('Los Pedidos: $mensaje no se guardaron por que estan incompletos; intente de nuevo.')</script>";
echo "<SCRIPT> location.href = 'pagar.php';</SCRIPT>";


}//fin grabar



include "desconexion.php";
}else{?>
<SCRIPT LANGUAGE="javascript"> location.href = "index.php";</SCRIPT>
<?}
?>

<script>

function confgrabar(){


if(confirm('\xbfSeguro que desea grabar todo?'))
{
//location.href = "pagar.php";
return true;
}
else{
return false;
}
}

var lastFact="";

function getFactura(fact, tipo, input){
var input="factura"+input;
if(tipo==1)
	document.getElementById(input).value=lastFact;
else
	lastFact=fact;

}

//Hacer filtro por secretaria
var Secres=Array();
$("#tablaPagos .secretaria").each(function(i){
	Secres[i]=$(this).text();
});

Secres.unique();

Array.prototype.unique=function(a){
  return function(){return this.filter(a)}}(function(a,b,c){return c.indexOf(a,b+1)<0
});

</script>



</body>
</div>
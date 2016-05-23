<!DOCTYPE html>
<head>
	<LINK REL="stylesheet" TYPE="text/css" HREF="estilos.css"> 
	<Title>T&iacute;a Mima - .::SIPA::.</Title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"> 
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
	
</head>

<div id="catalogo" class="dvDisplay" >
  <button onclick="$('#catalogo, .dvStopUser').toggle()" class="alignRight"> Cerrar Catalogo</button>
  <iframe src="catalogo.php" width="100%" height="100%"></iframe>
</div>
<div class="dvStopUser"></div>
<?php 

$hoy=date("d/m/Y");
$hoymas=mktime(0, 0, 0, date("m")  , date("d")+1, date("Y"));
$hoymasmes=mktime(0, 0, 0, date("m")  , date("d")+30, date("Y"));
$mañana=date("d/m/Y",$hoymas);
$mes=date("d/m/Y",$hoymasmes);
$hora = date("G")-5;
$min = date("i");

if($hora>21||$hora<7)
{
	$hora=00;
	$min ="00";
}
if($hora<10)
	$hora=('0'.$hora);


?>

<?if (isset($_SESSION['sipa_username'])) {?>
<table width='90%' border=0 bgcolor=red align=center BACKGROUND="images/fondocabeza.png" style="background-repeat:no-repeat">
<tr>
	<td>
		<a href='http://www.tiamima.com/'><IMG SRC="images/logo.png" WIDTH=100 HEIGHT=60 border=0></a>
	<br> <br> <br> <br> 	
	</td>
	<td ALIGN=right>
	<table border=0 >
		<tr><th colspan=2>Contacto:</th></tr>
		<tr ><td>Alimentos y Servicios HB: </td><td>   448 69 18</td></tr>
		<tr style="display:none"><td>MARTHA LIGIA RAMIREZ ESCOBAR: </td><td>311 306 0161</td></tr>
		<tr style="display:none"><td>CARLOS ANDRES RODRIGUEZ: </td><td>310 383 50 29</td></tr>
	</table>

	</td>
</tr>
<tr>
<td colspan=3>
<font color=ffffff >___________________________________________________________________________________________________________________________________________________________</font>
<div class="csDvUsuario"><font color=gray><b>Usuario: <?echo ($_SESSION["sipa_username"]);?></b></font></div>
<div id="dvMsg" ><div id="dvMsgTitle">Mensaje:</div><div id="dvMsgText"></div><button id="closeMsg">Aceptar</button></div>
<tr><td colspan=2 align=center>

<?
	//si es de tia mima
if($_SESSION["sipa_sec_username"]==1000)
{include 'menu2.php';}else
{include 'menu.php';
}
	?>
</td></tr></table><table width='90%'>
	

	<?}?>
<div ID="capa" STYLE="position:relative; top:10; left:450">

</div>

<div id="dvFlotante" class="csDivflotante"></div>

<script>
<?echo $HOY=date("d/m/Y")?>
/*$(document).ready(function () {
	var f = new Date();
	var fin='9/5/2014';
	var hoy=f.getDate() + "/" + (f.getMonth() +1) + "/" + f.getFullYear();
	if(hoy<=fin){
		$("#dvFlotante").text("Ambiente de Pruebas");
		$("#dvFlotante").addClass("csTituloSuperior");
		$("#dvFlotante").css({'top':'70','left':'250'});
	}
});*/
</script>
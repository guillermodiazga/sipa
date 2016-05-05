<?
session_start();?>

<?php include ("cabeza.php")?>
<table>
<form action="index.php" method="POST" onsubmit="return vrechazar()" >
<input type=hidden value="<?echo $rechazar?>" name=rechazar>
<tr><td>Motivo de Rechazo del <b>Pedido <?echo $rechazar?>:</b></td><td><textarea name="motivo" onmouseout="this.disabled();" rows='3' cols='30'></textarea></td></tr>
<tr><td></td><td><input type=submit  onclick='return confrechazar()' value="Rechazar";></td></tr>
</form>
</table>
<?php

if (!isset($_SESSION['sipa_username'])) {
include("formlogin.php"); 
}else{

include 'conexion.php';

if($recahzar!='')
{

echo$sql="update pedido set estado=4 where id=$rechazar";
//mysql_query($sql);
echo "<script>alert('Pedido $rechazar Rechazado.'); location.href = 'index.php';</SCRIPT>";
}

include 'desconexion.php';
include ("formulario.php");
}

?>
<script>

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
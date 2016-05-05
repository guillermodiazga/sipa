<?
session_start();?>

<?php include ("funciones.php")?>
<head>
<LINK REL="stylesheet" TYPE="text/css" HREF="estilos.css"> 
<Title>.::SIPA::.</Title>
<script type="text/javascript" src="menu.js"></script>
<script src="SpryAssets/SpryMenuBar.js" type="text/javascript"></script>
<link href="SpryAssets/SpryMenuBarHorizontal.css" rel="stylesheet" type="text/css">
</head>
<body >

<div id="granDiv">

<?php

if (!isset($_SESSION['sipa_username'])) {
include("formlogin.php"); 
}else{

include 'conexion.php';

if($ped!='')
{
//Consultar estados del pedido

$sql="SELECT his.pedido, his.id, his.`comentario` , his.`log` , est.estado
FROM historico_estados_ped AS his, estados AS est
WHERE est.id = his.newestado
AND his.pedido ='$ped' order by his.id desc";
$resultado = mysql_query($sql);
$row = @mysql_num_rows($resultado);

//Mostrar resultados
?>




<table border=0 align=center>
<tr><td>

<table rules=none border=2>
<tr><th colspan=4>Historial de Estados del Pedido <?echo $ped?></th>
<tr><th>Est. Asig</th><th>Fechay Hora del Cambio</th><th>Comentario/Motivo_Rechazo</th><tr>

<?
while ($row = @mysql_fetch_array($resultado)) 
{
$i++;
if($i%2==0){
echo "<tr bgcolor='#D8D8D8'><td>".$row['estado']."</td><td>".$row['log']."</td><td>".($row['comentario'])."</td><tr>";
}else{echo "<tr bgcolor='#ffffff'	><td>".$row['estado']."</td><td>".$row['log']."</td><td>".($row['comentario'])."</td><tr>";
}
}
?>
</table >
<?
//Mensaje y Voler al index
?>
</td></tr>
<tr><td align=center>
<input type="button" 
       value="Cerrar"
       onclick="window.close();">
	   </td></tr>
</table>
</div>
<?

}else{echo "<script>location.href = 'index.php'; </SCRIPT>";}
include 'desconexion.php';

}

?>

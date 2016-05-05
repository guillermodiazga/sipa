<?
session_start();?>

<head>
<LINK REL="stylesheet" TYPE="text/css" HREF="estilos.css"> 
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"> 
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<Title>.::SIPA::.</Title>
</head>


<?php

if (!isset($_SESSION['sipa_username'])) {
include("formlogin.php"); 
}else{

include 'conexion.php';
$ped=10;
if($ped!='')
{
//Consultar alimentos

$sql="SELECT * FROM `alimento` WHERE `bitactivo`=1";
$resultado = mysql_query($sql);
$row = @mysql_num_rows($resultado);

//Mostrar resultados


?>







<div  class='fixedHeaderTable' >
<table  border=0 rules=cols align=center cellspacing=0 >
<thead>
<tr>
<th  width=50>Item</th>
<th width=106>Nombre</th>
<th width=253>Descripci&oacute;n</th>
<th width=46>Valor Antes de IVA</th>
<th width=46>Valor IVA Incluido</th>
<th width=187>Foto</th>
</tr>
</thead>
<tbody>
<?
$i=0;
while ($row = @mysql_fetch_array($resultado)) {

if ($i%2){
?>
<tr bgcolor='#D8D8D8' onmouseover="this.style.backgroundColor='<?echo  $colorOnMouseOver?>'" onmouseout="this.style.backgroundColor='#D8D8D8'">
<?}else{?>
<tr bgcolor='#BDBDBD' onmouseover="this.style.backgroundColor='<?echo  $colorOnMouseOver?>'" onmouseout="this.style.backgroundColor='#BDBDBD'">
<?}?>

<td><?echo $row['id']?></td>
<td><?echo (($row['nombre']))?></td>
<td><?echo (($row['descripcion']))?></td>
<td>$<?echo number_format($row['valor'], 0, '', '.')?></td>
<td>$<?echo number_format($row['valor']*('1.'.$row['iva']), 0, '', '.')?></td>
<td><img width=140 height=120 src='images/alimentos/<?echo ($row['id'])?>.png?<?echo date("d/m/Y - G:i")?>'></td>
</tr>


<?$i++;}?>

</tbody>
</table >
</div>

	<?}else{echo "<script>location.href = 'index.php'; </SCRIPT>";}
include 'desconexion.php';

}

?>

</body> 
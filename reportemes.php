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

<? if ($print==1){
?>
 <div id="body">

 <body onload='sendTexto();window.print()' bgcolor='eeeeee'  >
 
<div id="header"> 
<table align=center>
 <tr><td rowspan=2>
 <textarea class="nover" id='areaTexto' onchange="sendTexto()" rows='4' cols='60'>
 Texto de la carta
 
 </textarea>
 </td>
 <td>
  <button  onclick="sendTexto()" class="nover"> Asignar Texto</button><br> </td> </tr>
  <tr><td><button  onclick="window.print();" class="nover"> Imprimir</button></td></tr>
  <tr><td colspan=2><hr class='nover'></td></tr>
  </div>
 <?
//Texto
 include ("printCabezaCliente.php"); ?>
<table>
<tr><td>Señor(es).</td></tr>
<tr><td><br></td></tr>
<tr><td><br></td></tr>
<tr><td>Asunto: <b>Informe de Consumo:<?echo $fchdesde?> a <?echo $fchhasta?></b></td></tr>
<tr><td><br></td></tr>
<tr><td  STYLE="text-align:justify" id='texto'></td></tr>
<tr><td><br></td></tr>
<tr><td><br></td></tr>
</table>
 
<?}else{?>
<form action=reportemes.php>
<table align=center >
<tr><th colspan=3>Busqueda por Fecha de Entrega</th></tr>
<tr><td colspan=2>Usuario:<select name="user" >

<option value='*'>Todos</option>

<? include ("conexion.php");
$sql="SELECT * FROM `usuario` where idsecretaria<>1000 order by nombre";
$result = @mysql_query($sql);
$row = @mysql_num_rows($result);

  while ($row = @mysql_fetch_array($result))
{
?>
<option value=<?echo $row['id']?>><?echo $row['idsecretaria'].'-'.($row['nombre'])?></option>
<?}?>	

</select>
</td></tr>
<tr><td>
Desde:<input onMouseOver='scwShow(this,event);' id="id_calendario" value='<?if($fchdesde==''){echo $hoy;}else{echo $fchdesde;}?>' name='fchdesde' size='8'> 
</td><td>
Hasta:<input onMouseOver='scwShow(this,event);' id="id_calendario" value='<?if($fchhasta==''){echo $mes;}else{echo $fchhasta;}?>' name='fchhasta' size='8'> 
<td><input type=submit value=Buscar name=buscar></td>
<?if($buscar){?>
<td colspan=2></td><td><button  class="nover"onclick="window.open('reportemes.php?user=<?echo $user;?>&fchhasta=<?echo $fchhasta;?>&fchdesde=<?echo $fchdesde;?>&print=1&buscar=Buscar','noimporta', 'width=800, height=600, scrollbars =yes, top=250, status=no, toolbar=no, titlebar=no, menubar=no')">Imprimir</button></td></tr>
<?}?>
</table>
</form>
<?}?>


<?php 
include ("conexion.php");

$fchdesde = $_GET["fchdesde"];
$fchhasta = $_GET["fchhasta"];
$user = $_GET["user"];
$buscar = $_GET["buscar"];

//Consulta acumulativa de valores
$fchdesde=fch_php_mysql($fchdesde);
$fchhasta=fch_php_mysql($fchhasta);

if($_SESSION['sipa_admin']!=1)
$usBuscado="and ped.idusuario=".$_SESSION['sipa_id_username'];
else {
	if($user=="*")$userv='';
	else
	$userv="and ped.idusuario=$user";
}
$sql=("SELECT  
ped.id,
ali.nombre as alimento,
ali.id as ali,
ped.cantidad,
ped.valorpedido,
ped.idppto,
ped.estado,
est.estado as nomestado,
sec.secretaria,
sec.id as idsec,
ped.idusuario,
us.nombre as usnam,
ppto.idsecretaria,
ped.fchentrega,
ppto.nombre,
ppto.valorini

FROM secretaria as sec, estados as est, pedido as ped, usuario as us, presupuesto as ppto, alimento as ali

WHERE  
ped.fchentrega Between '$fchdesde' and '$fchhasta' 


and ped.idalimento=ali.id

and ped.idppto=ppto.id

and ped.idusuario=us.id

and  ped.estado=est.id

and  ped.bitactivo=1

and ppto.idsecretaria=sec.id

$usBuscado

$userv

ORDER BY ped.id
");

$result=@mysql_query($sql);
$row = @mysql_num_rows($result);
$i=1;

?><script>var ar_data=new Array();</script>
<?if($buscar=='Buscar'){?>
<table align=center <?if ($print==1) echo "border=1 cellspacing =0"?>>
<tr><? if ($print==1){?><th class="nover">No Ver</th><?}?><th>Pedido</th><th>Item</th><th>Cantidad</th><th>Valor</th><th>Fecha Entrega</th><th>Proyecto-Pedido</th><?if($_SESSION['sipa_admin']==1){?><th>Sec-Usuario</th><th>% Gasto</th><?}?></tr>
<?

// resultados
while ($row = mysql_fetch_array($result)) 
{
?>
<script>
//declarar subvector
ar_data['<?echo $i?>'] = new Array();

//asignar datos a vector
<?if ($print!=1){?>
ar_data['<?echo $i?>']["activo"]="1";
<?}?>
ar_data['<?echo $i?>']["pedido"]="<?echo ($row['id']);?>";
ar_data['<?echo $i?>']["item"]="<?echo $row['ali']."-".($row['alimento']);?>";
ar_data['<?echo $i?>']["cantidad"]="<?echo $row['cantidad']?>";
ar_data['<?echo $i?>']["valor"]="<?echo $row['valorpedido']?>";
ar_data['<?echo $i?>']["fchEntrega"]="<?echo ($row['fchentrega']);?>";
ar_data['<?echo $i?>']["proyectoPedido"]="<?echo ($row['idppto']);?>";
ar_data['<?echo $i?>']["user"]="<?echo ($row['usnam']);?>";
ar_data['<?echo $i?>']["userId"]="<?echo ($row['idusuario']);?>";
ar_data['<?echo $i?>']["secretaria"]="<?echo ($row['secretaria']);?>";
</script>
<tr id=<?echo $i?>>
<? if ($print==1){?><td align=center class="nover"><input type=checkbox checked onclick="cambiarDisplay(<?echo $i?>)"></td><?}?>
<td align=center><?echo ($row['id']);?></td>
<td><?echo $row['ali']."-".($row['alimento']);?></td>
<td align=center><?echo number_format($row['cantidad'], 0, '', '.');?></td>
<td>$<?echo number_format($row['valorpedido'], 0, '', '.');?></td>
<td align=center><?echo ($row['fchentrega']);?></td>
<td align=center><?echo ($row['idppto']);?></td>
<?if($_SESSION['sipa_admin']==1){?>
<td align=left><?echo ($row['idsec']);?>-<?echo ($row['usnam']);?></td>
<td align=Right><?echo round($row['valorpedido']/$row['valorini'],4)*100;?>%</td>
<?}?>
</tr>


<?

$ttalCant+=$row['cantidad'];
$ttalVlr+=$row['valorpedido'];

$i++;
}//fin While?>

<tr><? if ($print==1){?><td class="nover"><br></td><?}?><td colspan=2><b>Totales:</td><td align=center id="ttalCant"><b><?echo number_format($ttalCant, 0, '', '.')?></td><td id='ttalVlr'>$<b><?echo number_format($ttalVlr, 0, '', '.')?></td><td><br></td><td><br></td><?if($_SESSION['sipa_admin']==1){?><td><br></td><?}?></tr>

</table>


<? }if ($print==1){
//Firma?>

<table>
<tr><td><br></td></tr>
<tr><td><br></td></tr>
<tr><td><br></td></tr>
<tr><td>____________________________</td></tr>
<tr><td><b><?echo $_SESSION['sipa_username']?></td></tr>
<tr><td>C.C. <?echo number_format($_SESSION['sipa_id_username'], 0, '', '.')?></script></td></tr>
</table>
</div>
<tr><td><br></td><tr>
<tr><td><br></td><tr>
<tr><td><img src="images/footer.png"  width=800 height="90" ></td></tr>
<?}?>




<?php include ("desconexion.php");}else{?>
<SCRIPT LANGUAGE="javascript"> location.href = "index.php";</SCRIPT>
<?}  ?>

<script>

function cambiarDisplay(id) {
  if (!document.getElementById) return false;
  fila = document.getElementById(id);
  if (fila.style.display != "none") {
    fila.style.display = "none"; //ocultar fila 
  } else {
    fila.style.display = ""; //mostrar fila 
  }
  //Desactivar pos
  ar_data[id]["activo"]=0;
  //Recalcular totales
  totales();
}

function totales()
{
var ttalCant=0;
var ttalVlr=0;

for(var i=1; i<ar_data.length; i++)
{
if (ar_data[i]["activo"]!=0){
	ttalCant+=parseInt(ar_data[i]["cantidad"]);
	ttalVlr+=parseInt(ar_data[i]["valor"]);
	}
}

document.getElementById("ttalCant").innerHTML="<b>"+addCommas(ttalCant);
document.getElementById("ttalVlr").innerHTML="$<b>"+addCommas(ttalVlr);

}

function sendTexto(){
document.getElementById("texto").innerHTML=document.getElementById('areaTexto').value;
}


</script>
<style>
html,body{
    height: 100%
}

#header{


}

#holder {
    min-height: 100%;
    position:relative;
}

#body {
    padding-bottom: 100px;
}

#footer{
    bottom: 0;
    left: 0;
    position: absolute;
    right: 0;
}
}</style>


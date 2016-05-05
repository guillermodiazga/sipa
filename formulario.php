<?
session_start();

if (isset($_SESSION['sipa_username'])) {
include "conexion.php";
//include "cabeza.php";
  $tipo=$_GET['tipo'];?>
<?include  "funciones.php";?>
<head><meta http-equiv="Content-Type" content="text/html; charset=windows-1252">

<script src="scw.js" type="text/javascript" language="JavaScript1.5"></script>
</head>



<div id="granDiv" >

<?if($tipo>0){?>
<form action="index.php" method="POST" onsubmit="return validarform();" >
<div id="dvFoto"><input class="alignRight" name='foto' id="foto" disabled type=image src='images/alimentos/0.png' border=2 width=240 height=220 ></div>
<TABLE align="center" border='0' rules='' >


<?
  //consultar nombre de tipos de alimentos

  $sq=("SELECT * FROM `tipoalimento`  WHERE bitactivo=1 and id=$tipo");
  $resulta = @mysql_query($sq);
$tipos = @mysql_num_rows($resulta);

  while ($tipos = @mysql_fetch_array($resulta)){
 $nombretipo=($tipos['talimento']);
}

?>


<tr><TH colspan=3><?echo ($_SESSION['sipa_username']);?>: Crear Pedido de <?echo $nombretipo;?>s</TH></tr>

<input type=hidden name="tipoalimento" value=<? echo $tipo;?>>
<?//Buscar descripci&oacute;n de alimentos

?>

  <tr><td class="alignRight">Item:</td><td><select   tabindex = "1" onChange='ver_detalle_alimento(); vtotalped();' id="alimento" name="alimento">


<? 
if (($_SESSION['sipa_admin'])==1)//emerson Si es Administrador solo puede ver los productos tipo 3
	$sql="SELECT * FROM `alimento` WHERE `bitactivo`=1 and idtalimento=3 ORDER BY `id` ASC";
else
	$sql="SELECT * FROM `alimento` WHERE `bitactivo`=1 and idtalimento=$tipo ORDER BY `id` ASC";

 $result = @mysql_query($sql);
$row = @mysql_num_rows($result);


    
$i=0;
?>
<script> 
matriz = new Array (); 
matriz2 = new Array (); 
matriz3 = new Array (); 
</script> 
<option style="color=grey" value=>Seleccione una opci&oacute;n</option>
<?
  while ($row = @mysql_fetch_array($result))
{
?>
<option value='<?echo $row['id']?>'><?echo $row['id']." - ".($row['nombre'])."  $".number_format($row['valor'], 0, '', '.')." Antes de IVA"?></option>

<script> 
matriz[<?echo  $row['id']; ?>] = ('<? echo ($row['descripcion']); ?>') ; 
matriz2[<?echo  $row['id']; ?>] = ('<? echo $row['valor']; ?>') ; 
matriz3[<?echo  $row['id']; ?>] = ('<? echo $row['iva']; ?>') ; 

</script> 


<?
$i++;
}?>	

</select>
<input class="alignRight" type=button value='Ver Catalogo' onclick="$('#catalogo, .dvStopUser').toggle()">
</td>

</tr>

<tr><td class="alignRight">Descripci&oacute;n:</td><td>

<textarea rows='10' cols='60' name=descripcion id='descripcion'  style="color:#0B0B61; background-color:#eeeeee;" wrap="soft" readonly tabindex = "-1" ></textarea>
</td>
<tr><td class="alignRight">Observaci&oacute;n:</td><td><textarea name="comentario" id="comentario"   rows='3' cols='60' tabindex = "2"></textarea></td></tr>

<tr><td class="alignRight">Cantidad:</td><td>


<select name="cantidad" id="cantidad" onchange="vtotalped();" tabindex = "3" >
<?
echo"<option value=''></option>";
$i=1;
while ($i<10000){

echo"<option value=".$i.">".$i."</option>";
$i++;}
?>
</select>


</td></tr>    

  <tr><td class="alignRight">Valor Pedido:</td><td>
<input name=vped id=vped size=20 maxlength=10  style="color:#0B0B61; background-color:#eeeeee" readonly> 
Valor Adicional (IVA Incluido):
<input name=vadic id='vadic' type='number' size=10 maxlength=8  value=0 style="color:#FE2E2E;" > 
</td></tr>
    
    
<tr><td class="alignRight">Fecha Entrega:</td><td><input tabindex = "4" maxlength=10 onclick='scwShow(this,event);' id="fecha" value='<?echo $mañana?>'  name="fecha"  size="10"  value = <?php echo $mañana?>></td></tr>
<tr><td class="alignRight">Hora Entrega:</td><td>
<select name="horaini" id="horaini" size="1" type="time" tabindex = "5">
<option value=<?php echo $hora?>><?php echo $hora?></option>
<?
$i=7;
while($i<21)
{?>

<option value='<?if($i<10){echo ("0".$i);}else{echo $i;};?>' ><?if($i<10){echo ("0".$i);}else{echo $i;};?></option>

<?$i++;}?>
</select>
:
<select name="minini" id="minini" size="1" type="time" tabindex = "6">
<option value=<?php echo $min?>><?php echo $min?></option>
<?
$i=0;
while($i<60)
{
?>

<option value=<?if($i<10){echo ("0".$i);}else{echo $i;};?>><?if($i<10){echo ("0".$i);}else{echo $i;};?></option>

<?$i++;}?>
</select>
</td></tr>

<tr><td class="alignRight">Nombre del Evento:</td><td><input name="evento" id="evento" size="65" maxlength=50 tabindex = "7"></td></tr>
<tr><td class="alignRight">Direccion de Entrega:</td><td><input name="direccion" id="direccion" size="100" maxlength=150 tabindex = "7"></td></tr>
<tr><td class="alignRight">Nombre Persona que Recibe:</td><td><input name="personarecibe" id="personarecibe" size="35" maxlength=35 tabindex = "8"></td></tr>
<tr><td class="alignRight">Telefono Fijo:</td><td><input name="telfjorecibe" id="telfjorecibe" size="35" maxlength=10 tabindex = "9"></td></tr>
<tr><td class="alignRight">Telefono Movil:</td><td><input name="movilrecibe" id="movilrecibe" size="35" maxlength=10 tabindex = "10"></td></tr>



<tr><td class="alignRight">Presupuesto:</td>
<td colspan=2>


  <select name="ppto" id="ppto" tabindex = "11">
  <?
  //consultar los proyectos del usuario para el tipo de pedido

  $sec=$_SESSION["sipa_sec_username"];
  $idus=$_SESSION["sipa_id_username"];

$sq=("SELECT  ppto.id, ppto.nombre, ppto.valorini, ppto.valorpedido 
FROM `persona-ppto` as rel,presupuesto as ppto, tipoalimento as tipo
WHERE

`idusuario`=$idus 
and ppto.id=rel.idppto and 
tipo.idproveedor=ppto.idproveedor
and tipo.id=$tipo and
ppto.bitactivo=1 and
rel.bitactivo=1 and
ppto.idtalimento=$tipo 
");


$resulta = @mysql_query($sq);
$rows = @mysql_num_rows($resulta);
if($rows==0)
echo "<script> alert('No hay Presupuesto'); window.location.href='index.php';</script>";
?>
<option style="color=grey" value=''>Seleccione una opci&oacute;n</option>
<?
  while ($rows = @mysql_fetch_array($resulta)){
  
  $saldo=(($rows['valorini'])-($rows['valorpedido']));
 echo "<option value='".$idppto=($rows['id'])."'>".($rows['id'])." - ".($rows['nombre'])." - saldo: $".number_format($saldo, 0, '', '.')."</option>";
}

?>
    
  </select><br /></td></tr>
<tr> <td></td><td colspan ="2"><input value="Grabar" onclick="return confgrabar();" ACCESSKEY="G" name='grabar' type="submit" tabindex = "12">
<input type="reset" value="Cancelar" name="cancelar" tabindex = "-1"></td></tr>
</table>
</form>

<?}else {$user=($_SESSION['sipa_username']); echo "<br><table align=center border=0><tr><td colspan=2><b>Pedidos Pendientes por Aprobar $user:</b></td></tr> 
<tr><td colspan=2>"; include('pendientes.php');echo"</td></tr>
<tr><td colspan=2><br><br></td></tr>
<tr><th align=left></th><th align=right><a target='_blank' href='http://www.guillermodiazga.tk'>Desarrollado por=>Guillermo D&iacute;az</a></th></tr>
</table>";}

if($grabar)
{
$comentario=($comentario);
$evento=($evento);
$personarecibe=($personarecibe);
$direccion=($direccion);
$telfjorecibe=($telfjorecibe);
$movilrecibe=($movilrecibe);
$ip=$REMOTE_ADDR;
$log=date("d/m/Y - G:i");
$horainicial=$horaini.":".$minini;

include 'conexion.php';
//calcular el valor total del pedido
$sql="SELECT * FROM `alimento` WHERE `id`=$alimento";
$result = @mysql_query($sql);
$row = @mysql_num_rows($result);

  while ($row = @mysql_fetch_array($result))
{
$vindividual=$row['valor'];
$iva=$row['iva'];}

$siniva=($vindividual*$cantidad);
$valorpedido=($siniva+($siniva*($iva/100))+$vadic);

//si es admin puede escojer otros usuarios
/*
if (($_SESSION['sipa_admin'])==1) {
 $iduser=$nombre;
}else{
$iduser=$_SESSION["sipa_id_username"];
}*/

$iduser=$_SESSION["sipa_id_username"];

//Validaci&oacute;n por PHP

$val=true;

if($ppto=='')
$val=false;

if($cantidad=='')
$val=false;

if($alimento=='')
$val=false;


if(($fecha=='')||($horaini=='00')||($horafin=='00'))
$val=false;

//variables auxiliares para validacion php
$diahoy=date("N");//num del dia actual
$diaentrega=(fecha_a_num_dia($fecha));//num del dia de entrega
//echo "<script>alert($diahoy)</script>";
//echo "<script>alert($diaentrega)</script>";
//$diahoy=5;
//$hora=20;


//$mensaje="<script>alert('Su pedido esta siendo enviado por fuera del tiempo mínimo estipulado; comuníquese con el supervisor y el proveedor para garantizar el envio.',1)</script>";
$mensaje="<script>alert('No se puede Guardar: pedido por fuera del tiempo mínimo estipulado; comuníquese con el supervisor y el proveedor para garantizar el envio.',1)</script>";
//validacion de fecha de entrega del pedido

//si el pedido es para un sabado domingo o lunes, retorna
if(($diahoy==6)&&($hora>15)&&(($diaentrega==6)||($diaentrega==7)||($diaentrega==1))){
  echo $mensaje;
  $val=false;
}

if(($diahoy==($diaentrega-1))&&($hora>17)){
  echo $mensaje;
  $val=false;
}

if($fecha==$hoy){
  echo $mensaje;
  $val=false;
}

if(($fecha==$mañana)&&($hora>15)&&(($horaini-$hora)<0)){
  echo $mensaje;
  $val=false;
}
//si esta en el pasado
if(($fecha==$hoy)&&($horaini<$hora))
{
echo "<script>message('Hora de entrega en el pasado.')</script>";
$val=false;
}


//Consultar si hay ppto
$sql="SELECT * FROM `presupuesto` WHERE `id`='$ppto'";
$result = @mysql_query($sql);
$row = @mysql_num_rows($result);
 
  while ($row = @mysql_fetch_array($result))
{
 $secppto=($row['idsecretaria']);
 $saldo=(($row['valorini'])-($row['valorpedido']));
 //echo "<script>alert('saldo'+$saldo)</script>";
}

if($saldo<0)
{$val=false;

echo "<img src=images/aviso.png> Error: Presupuesto superado!<script>alert('Error: Presupuesto superado (-)')</script><br>";
echo "<script> window.location.href='javascript:history.go(-1)';</script>";
}
 //echo "<script>alert('Vped'+$valorpedido)</script>";
if($valorpedido>$saldo)
{$val=false;

echo "<img src=images/aviso.png> Error: Presupuesto superado!<script>alert('Error: Presupuesto superado')</script><br>";
echo "<script>history.back(1)';</script>";
}

//Si todo bien Graba
if ($val)
{
  if($vadic=='')
  $vadic==0;

    $sec=$_SESSION["sipa_sec_username"];
  //numero de reg antes del inser
   $sql_antes=("SELECT * FROM `pedido`");
  $antes=@mysql_num_rows(@mysql_query($sql_antes));
  include 'conexion.php';
   $sql="
   INSERT INTO `pedido` (
  `id` ,
  `idsecretaria` ,
  `idusuario` ,
  `idppto` ,
  `fchreg` ,
  `fchentrega` ,
  `hora` ,
  `idtalimento` ,
  `idalimento` ,
  `comentario` ,
  `personarecibe` ,
  `telfjorecibe` ,
  `movilrecibe` ,
  `direccion` ,
  `evento` ,
  `cantidad` ,
  `valorpedido` ,
  `valoradic` ,
  `iplog` ,
  `bitactivo`,
  `estado` 
  )
  VALUES (
  NULL , '$secppto', '$iduser', '$ppto','$log',STR_TO_DATE('$fecha', '%d/%m/%Y'), '$horainicial', $tipoalimento,$alimento, '$comentario', '$personarecibe', '$telfjorecibe', '$movilrecibe',
  '$direccion','$evento', $cantidad, $valorpedido, $vadic,'$ip','1',2);";

  $sql1="
  INSERT INTO `pedido` VALUES (
  NULL ,
  '$secppto',
  '$iduser',
  '$ppto',
  '$log'


  ";

  include "conexion.php";

  @mysql_query($sql) or die('No se pudo grabar, error en el query');

  //numero de reg despues del inser
  $sql_despues=("SELECT * FROM `pedido`");
  $despues=@mysql_num_rows(@mysql_query($sql_antes));

  // verificar si guardo
  if(($despues-$antes)==0){
  $sql;
  echo "<img src=images/aviso.png> Error: No se pudo Grabar la Informaci\u00f3n!<script>alert('Error: En la Base de Datos, No se pudo Grabar')</script>";
  echo "<script> </script>";



  }else{
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
  NULL , $despues, '2', 'Creado', '$log'
  );";

  mysql_query($sql);

  echo "<SCRIPT LANGUAGE='javascript'> location.href = 'index.php';</SCRIPT><img src=images/aviso2.png> Pedido $despues grabado correctamente el $log en el pc: $ip <script>alert('Pedido $despues Grabado.'); location.href = 'index.php';</script>";

  //enviar mail
  include ('mail.php');
  mailped($mailinterventor, $despues, "Creado");


  //afectar ppto
  $sql="UPDATE `presupuesto` SET valorpedido = valorpedido+$valorpedido WHERE CONVERT( `presupuesto`.`id` USING utf8 ) = '$ppto'";
  @mysql_query($sql);
  }

}else{
  echo"<script>alert('Por favor verifique la fecha y hora del pedido e intente nuevamente.',1)</script>";
  echo "<script>history.back(1);</script>";
	//echo"<img src=images/aviso.png>Entrada datos err&oacute;neos, favor intente nuevamente.";
}

include "desconexion.php";


?>
<body onload="deshabilitar();">
<?
}	//fin Grabar

?>



<?}else{?>
<SCRIPT LANGUAGE="javascript"> location.href = "index.php";</SCRIPT>
<?}?>

</div>

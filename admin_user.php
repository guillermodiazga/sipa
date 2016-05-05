<?php session_start();
include 'cabeza.php';
include 'funciones.php';
 if (isset($_SESSION['sipa_username'])) {

include'conexion.php';
?>

<body onLoad="muestraGranDiv()">
<div id="cargando" style="width: 357px; height: 100px; position: absolute; padding-top:20px; text-align:center; left: 2px;">
<div align="center"><br><br><br><strong>Por favor espere un momento... <img src='images/cargando.gif'></strong></div>
</div>
<div id="granDiv" style="visibility:hidden;">
<?



//consultas 
if($idb!='' and $nombre==''){
   $consulta=("SELECT  us.id, us.nombre, us.idsecretaria, sec.secretaria, us.oficina, us.telefono, us.movil, us.mail,  us.bitactivo
FROM 
usuario as us, secretaria as sec 
where sec.id=us.idsecretaria and us.id=$idb order by us.idsecretaria");
}else if($nombre!='' and $idb=='' )
{
 $consulta=("SELECT  us.id, us.nombre, us.idsecretaria, sec.secretaria, us.oficina, us.telefono, us.movil, us.mail,  us.bitactivo
FROM 
usuario as us, secretaria as sec 
where sec.id=us.idsecretaria and us.nombre like '%$nombre%' order by us.idsecretaria");
}
else if ($activo==true and $nombre=='' and $idb=='')
{
    $consulta=("SELECT  us.id, us.nombre, us.idsecretaria, sec.secretaria, us.oficina, us.telefono, us.movil, us.mail,  us.bitactivo
FROM 
usuario as us, secretaria as sec 
where sec.id=us.idsecretaria and us.bitactivo=1 order by us.idsecretaria");
}else if ($activo==false and $nombre=='' and $idb=='')
{
  $consulta=("SELECT  us.id, us.nombre, us.idsecretaria, sec.secretaria, us.oficina, us.telefono, us.movil, us.mail,  us.bitactivo
FROM 
usuario as us, secretaria as sec 
where sec.id=us.idsecretaria and us.bitactivo=0 order by us.idsecretaria");
}		

if($buscar){
$result = @mysql_query( $consulta) or die ('Error query');
}
$row = @mysql_num_rows($result);


?>
<td valign=top></td>
<table align='center' border='1' rules=none>

<tr bgcolor='#95b0c1' ><tH  align='center' colspan='4'><font face='Arial' size='4'>Administrar Usuarios</th></tr>
<tr><td>

<?//formulario de busqueda?>
<table align='center' border='1' rules=none>



<form action='admin_user.php' method='get'><input type='hidden' name='accion'value='Administrar Usuario'>
<tr>
<td>Identificaci&oacute;n:</td><td><input name='idb' size=10 value='<?echo $idb?>'></td>
<td>Nombre:</td><td><input name='nombre' value=<?echo $nombre?>></td>
<td>Activo:</td>
<td><input type='checkbox' name='activo' checked></td>
<td><input type='submit' name=buscar value='Buscar' onclick="desha();"></td></form>
<td><table align=center>
<tr><th>Acci&oacute;n</th></tr>
<tr><td align=center><a href='newuser.php'><image border=0 src='images/b_usradd.png' alt='Crear Usuario'></a></td></tr>
</table></td>
</tr>
</table>
</td></tr>
<tr><td>

<?//Resultados
if($idb!=''||$nombre!=''||$buscar){?>

<table align='center' border='0'>

<tr bgcolor='#d3dce3'><th colspan='3'>Acci&oacute;n</th><th>Identificaci&oacute;n</th><th>Nombre</th><th>Secretar&iacute;a</th><th>Oficina</th><th>Telefono</th><th>Movil</th><th>E-mail</th><th>Activo</th></tr>

<?
$i=1;
// asignar valores encontrados a variables
while ($row = @mysql_fetch_array($result)){
 $id=($row["id"]);
$nombre=(($row["nombre"]));
$secretaria=($row["idsecretaria"]).'-'.(($row["secretaria"]));
$oficina=($row["oficina"]);
$telefono=format_tel_num($row["telefono"]);
$movil=format_cel_num($row["movil"]);
$email=($row["mail"]);
if(($row["bitactivo"])==0){$bitactivo='No';}else{$bitactivo='Si';};




$i++;
if($i%2==0){
?> <tr bgcolor='ffffff' onmouseover="this.style.backgroundColor='<?echo  $colorOnMouseOver?>'" onmouseout="this.style.backgroundColor='ffffff'">

<?
}else{
?> <tr bgcolor='e5e5e5' onmouseover="this.style.backgroundColor='<?echo  $colorOnMouseOver?>'" onmouseout="this.style.backgroundColor='eeeeee'">
<?}
?>
<td></td>
<td><form action=alteruser.php method='POST'> <input type=hidden value='Modificar Usuario' name='accion'> <input type=hidden name='editar'> <input type=hidden name=idedit value='<?echo $id ?>'> <input name=edit  type=image src='images/b_usredit.png' alt='Editar Usuario'></form></td>

<? 

if ($activo==true){?>
<td><form action=admin_user.php method='POST'>  <input type=hidden name=idborrar value='<?echo $id ?>'> <input type=image src='images/b_usrdrop.png' alt='Inactivar Usuario'></form></td>
<?} else {?>

<td><form action=admin_user.php method='POST'> <input type=hidden value='Administrar Usuario' name='accion'> <input type=hidden name=idactivar value='<?echo $id ?>'> <input type=image src='images/b_usrcheck.png' alt='Activar Usuario'></form></td>

<?}?>


<td><input type='hidden' name='id' value=<?echo $id?>><?echo number_format($id, 0, '', '.')?></td>
<td><input type='hidden' name=usuario value=<?echo ($nombre)?>><?echo $nombre;?></td>
<td><input type='hidden' name=dependencia value=<?echo $secretaria?>><?echo $secretaria;?></td>
<td><input type='hidden' name=fecha value=<?echo $fecha?>><?echo $oficina;?></td>
<td><input type='hidden' name=fecha value=<?echo $fecha?>><?echo $telefono;?></td>
<td><input type='hidden' name=fecha value=<?echo $fecha?>><?echo $movil;?></td>
<td><input type='hidden' name=email value=<?echo $email?>><?echo $email;?></td>

<td><input type='hidden' name=bitactivo value=<?echo $bitactivo?>><? if ($activo==true){echo 'Si';} else {echo 'No';}?></td>

</tr>

<?}//fin del while?>
</table>
<?}else//fin tabla de resultados?>

<?//echo 'edit'.$idedit;

if ($editar){
if ($idedit=$id)
{
//mostrar datos a editar
?>

<?}}?>
</td></tr>
</table>


</td></tr>

</div>

<?

//inactivar User



if($idborrar!=''){
 $consulta="UPDATE `usuario` SET `bitactivo` = '0' WHERE `id` = $idborrar";
@mysql_query($consulta);
echo "<script>alert('Inactivado Usuario $idborrar')</script>";
}

//activar User
//$idactivar;
if($idactivar!=''){
$consulta="UPDATE `usuario` SET `bitactivo` = '1' WHERE `usuario`.`id` = $idactivar";
@mysql_query($consulta);
echo "<script>alert('Activado Usuario $idactivar')</script>";
}




include'desconexion.php';}else echo"No Autorizado'<SCRIPT LANGUAGE='javascript'>location.href = 'index.php';</SCRIPT>"; ?> 


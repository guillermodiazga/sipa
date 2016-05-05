<?include  'funciones.php';?><br><br>
<table align = 'center' border=0   BACKGROUND='images/login-box-backg.png' width="590" height="550">
	<form action="index.php" method="post" onsubmit="return validarlogin();">

	<tr><td colspan=4  align=center><br></td></tr>
	<tr><td colspan=4  align=center><br></td></tr>
	<tr ><td></td><td colspan=1 align=><br><h2><h2></td><td align=right><img src='images/transparencia.png' WIDTH=100 HEIGHT=60></td></tr>

	<tr><td></td><td colspan=3  align=center><font color=ffffff><font color=grey></font><br></td></tr>
	<tr><td></td><td></td><td colspan=2 align=left><font color=ffffff><b>Sistema de Pedidos de Alimentaci&oacute;n</font><br></td></tr>
	<tr><td><br></td></tr>
	<tr><td colspan=4  align=FF0000><br></td></tr>
	<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	</td><td align=><h2><font color=FF0000>&nbsp;C&eacute;dula:</h2></td><td align='left'><input name="user" id="user" type="text" size="20" maxlength=10 style="height: 35px; width: 210px; font-size=20; font-family:Verdana,sans-serif;  font-weight:bold" ></td><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	</td></tr>

	<tr><td><br></td><td valign=top><h2><font color=FF0000>&nbsp;Contrase&ntilde;a:</h2></td><td align=left><input name="pass" id="pass" type="password" size="17" maxlength=10 style="height: 35px; width: 210px; font-size=20; font-family:Verdana,sans-serif;"  ><img src='images/ayuda.png' alt='No sabe su contrase&ntilde;a?' onclick="alert('La contrase\u00f1a es el n\u00famero de su c\u00e9dula, \nluego podra cambiarla.')"></td></tr>
	<tr><td colspan=4  align=center><input size=500 name="ingresar" value="Ingresar" type="submit"  style="height: 50px; width: 200px; font-size=30; font-family:Verdana,sans-serif; font-weight:bold"  ></td></tr>

	<tr><td colspan=4  align=center><br></td></tr>
	<tr><td colspan=4  align=center><br></td></tr>
	<tr><td colspan=4  align=center><br></td></tr>
	<tr><td colspan=4  align=center><br></td></tr>
	<tr><td colspan=4  align=center><br></td></tr>
	<tr><td colspan=4  align=center><br></td></tr>

	</form>

</table>

<? if ($vbuscado) {?>

<?}?>
<?	
 echo "test".$ingresar;
//if($_POST["ingresar"])
if($ingresar)
{
include 'conexion.php';
$pass=($_POST["pass"]);
$user=($_POST["user"]);
 $sql="SELECT * FROM `usuario` WHERE `id`='$user' and `password`='$pass'";
$result = mysql_query($sql);
$row = mysql_num_rows($result);

if($row==1)
{

  while ($row = mysql_fetch_array($result))		
  {
if($row['bitactivo']==0){
echo "<script>alert('Usuario inactivado, comuniquese con el administrador');</script>";
}else {
$_SESSION["sipa_username"]=($row['nombre']);
$_SESSION["sipa_id_username"]=($row['id']);
$_SESSION["sipa_email"]=($row['mail']);
$_SESSION["sipa_sec_username"]=($row['idsecretaria']);

//comprobar si es administrador/*
$sql="SELECT * FROM `rol` WHERE `interventor`=$user ";
$resul = mysql_query($sql);
$rows= mysql_num_rows($resul);
$_SESSION["sipa_admin"]=$rows;
	}
}

//si es de tia mima
if($_SESSION["sipa_sec_username"]==1000)
{?>
<SCRIPT LANGUAGE="javascript"> location.href = "index2.php";</SCRIPT>
<?}else{
?>
<SCRIPT LANGUAGE="javascript"> location.href = "index.php";</SCRIPT>
<?}
}	else
if($pass!='' and $user!=''){
echo "<script>alert('C\u00e9dula \u00f3 Contrase\u00f1a incorrecta');</script>";
}
include 'desconexion.php';
}
?>


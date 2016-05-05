<?if (isset($_SESSION['sipa_username'])) {?>
<script src="SpryAssets/SpryMenuBar.js" type="text/javascript"></script>
<link href="SpryAssets/SpryMenuBarHorizontal.css" rel="stylesheet" type="text/css" />
<div id='cssmenu'>
<ul>

<li  ><a href="index.php"><img src='images/entrada.png' border=0 width=23 height=17>Entrada</a></li>
  
  <li class="has-sub" ><a  href="#"><img src='images/NewDocumentHS.png' border=0>Pedido</a>
      <ul>
               <?
include 'conexion.php';

$sq=("SELECT * FROM `tipoalimento`  WHERE bitactivo=1 ORDER BY `tipoalimento`.`talimento` ASC ");
  $resulta = @mysql_query($sq);
$rows = @mysql_num_rows($resulta);

  while ($rows = @mysql_fetch_array($resulta)){
?><li><a   href="index.php?tipo=<?echo $dep=($rows['id'])?>"><img src='images/restaurante.png' border=0 width=19 height=17><?echo($rows['talimento'])?></a></li>
<?}
include 'desconexion.php';
?> </ul>  </li>


     

	 <li class="has-sub" ><a   href="#"><img src=images/busqueda.png border=0 width=23 height=17>Consultar</a>
<ul>
<li><a  href="consultahoy.php"><img src='images/b_calendar.png' border=0>Mis<font color=#FFFFFF>_</font>Pedidos</a></li>
<?if (($_SESSION['sipa_admin'])==1) {?>
<li><a   href="consultagral.php"><img src='images/b_calendar.png' border=0>Pedidos<font color=#FFFFFF>_</font>Gral.</a></li>
<?}?>
<li><a  onclick="$('#catalogo, .dvStopUser').toggle()"><img src='images/b_calendar.png' border=0>Cat&aacute;logo</a></li>

</ul>
  </li>
  
  
<li class="has-sub" ><a   href="#"><img src=images/grafico1.png border=0 width=23 height=17>Reportes</a>
<ul>

<li><a  href="reporteitem.php"><img src=images/graficos.png width=23 height=17 border=0>1. Item</a>

<li><a  href="reportemes.php"><img src=images/graficos.png width=23 height=17 border=0>2. Mensual</a>
<li><a  href="#" class="has-sub"><img src=images/graficos.png width=23 height=17 border=0>3. Presupuesto</a>
<ul>
<li><a  href="reporteppto.php"><img src=images/graficos.png width=23 height=17 border=0>3.1 Por Proyectos</a>
</li>
<li><a  href="reporteppto2.php"><img src=images/graficos.png width=23 height=17 border=0>3.2 Saldo Por Proy</a>
</li>

<li><a  href="reporteppto-sec.php"><img src=images/graficos.png width=23 height=17 border=0>3.3 Por Secretar&iacute;as</a>
</li>

</ul>
</li>
</ul>
</li>

<?if (($_SESSION['sipa_admin'])==1) { ?>
<li class="has-sub" ><a   href="#"><img src=images/configurar.png border=0 width=23 height=17>Administrar<font color=#FFFFFF></font></a>
<ul>
<li><a  href="modificar.php"><img src=images/editar.png border=0 width=18 height=17 alt='Modificar Pedidos'>Pedidos</a></li>

<li><a  href="admin_user.php"><img src=images/b_usrlist.png border=0>Usuarios</a></li>
<li><a  href="creardatos.php"><img src=images/b_insrow.png border=0>Datos Maestros</a></li>

</ul></li>
<?}else{	?>
<li><a  href="modificar.php"><img src=images/editar.png border=0 width=18 height=17 alt='Modificar Pedidos'>Mod.<font color=ffffff>_</font>Pedidos</a></li>
<?}?>
<li><a   href="alteruser.php?idedit=<?echo $id=$_SESSION["sipa_id_username"]; ?>">Mis Datos <img src=images/b_usredit.png  border=0 width=17 height=17></a></li>
<li><a   href="logout.php">Salir <img src=images/salir.png  border=0 width=17 height=17></a></li>
  
  
  


  
  </ul>
<?}?>
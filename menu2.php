<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<script src="SpryAssets/SpryMenuBar.js" type="text/javascript"></script>
<link href="SpryAssets/SpryMenuBarHorizontal.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div id='cssmenu'>
<ul>
<li><a href="index.php"><img src='images/entrada.png' border=0 width=23 height=17><b>Entrada</b></a></li>
<li><a  href="consultagral.php"><img src='images/busqueda.png' border=0 width=23 height=17>Pedidos</a></li>
<li><a   href="alteruser.php?idedit=<?echo $id=$_SESSION["sipa_id_username"]; ?>">Mis Datos <img src=images/b_usredit.png  border=0 width=17 height=17></a></li>




<li><a  href="admin_user.php"><img src=images/b_usrlist.png border=0>Crear Usuarios</a></li>
<li><a  href="creardatos.php"><img src=images/b_insrow.png border=0>Crear Datos Maestros</a></li>

<li><a  href="reporteppto2.php"><img src=images/graficos.png width=23 height=17 border=0>Saldo Por Proy</a>


<li><a   href="logout.php">Salir <img src=images/salir.png  border=0 width=17 height=17></a></li>
</ul>
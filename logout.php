<?php
session_start();
// Borramos toda la sesion
session_destroy();
//echo 'Ha terminado la session <p><a href="index.php">index</a></p>';
?>
<body onLoad="muestraGranDiv();">
<div id="cargando" style="width: 357px; height: 100px; position: absolute; padding-top:20px; text-align:center; left: 2px;">
<div align="center"><br><br><br><strong>Cerrando, Por favor espere un momento... <img src='images/cargando.gif'></strong></div>
</div>
<div id="granDiv" style="visibility:hidden;">

<SCRIPT LANGUAGE="javascript">
location.href = "index.php";
</SCRIPT>
</div>
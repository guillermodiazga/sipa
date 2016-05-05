<?
session_start();
?>

<head>
<LINK REL="stylesheet" TYPE="text/css" HREF="estilos.css"> 
<Title>.::SIPA::.</Title>
<script type="text/javascript" src="menu.js"></script>
<script src="SpryAssets/SpryMenuBar.js" type="text/javascript"></script>
<link href="SpryAssets/SpryMenuBarHorizontal.css" rel="stylesheet" type="text/css">
</head>

<?if (!isset($_SESSION['sipa_username'])) {
echo "<body onload='window.close()'>";
}else{?>
<?include 'funciones.php'?>

<body onLoad="muestraGranDiv();">
<div id="cargando" style="width: 357px; height: 100px; position: absolute; padding-top:20px; text-align:center; left: 2px;">
<div align="center"><br><br><br><strong>Por favor espere un momento... <img src='images/cargando.gif'></strong></div>
</div>
<div id="granDiv" style="visibility:hidden;">

<table style="table-layout:fixed;" border=0 rules=cols align=center >
<tr>
<th  width=50>Item</th>
<th width=106>Nombre</th>
<th width=253>Descripci&oacute;n</th>
<th width=46>Valor Antes de IVA</th>
<th width=46>Valor IVA Incluido</th>
<th width=187>Foto</th></tr>

<tr><td>
<DIV STYLE="position:relative;  top:0; left:0">
<IFRAME SRC="catalogo.php" width=701 height=639> </IFRAME>
</DIV>

<tr><td align=center colspan=6>
<input type="button" 
       value="Cerrar"
       onclick="window.close();">
	   </td></tr>
	 
	    </td></tr></table>
		</DIV>
		<?}?>
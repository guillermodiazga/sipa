<?
session_start();
if (isset($_SESSION['sipa_username'])) {
include "cabeza.php";
?>

<script>$.getScript('js/pagarPedidos.js');</script>


<div id="tablaDatos" onclick=";">Cargando Reporte...</div>

<?}else{?>
<SCRIPT LANGUAGE="javascript"> location.href = "index.php";</SCRIPT>
<?}?>
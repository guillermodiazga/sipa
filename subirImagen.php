<?php
echo $_POST['cadenatexto']."<br>";
if ($_POST['cadenatexto']!='')
{
$path="./images/alimentos/";	
$nombre_archivo = $_FILES['userfile']['name'];
$tipo_archivo = $_FILES['userfile']['type'];	
$tamano_archivo = $_FILES['userfile']['size'];	
if (!((strpos($nombre_archivo, "jpg") || (strpos($nombre_archivo, "JPG") ))))
{
echo "La extensión o el tamaño de los archivos no es correcta";	
}
else
{
if (move_uploaded_file($HTTP_POST_FILES['userfile']['tmp_name'], $path.$_POST['cadenatexto'].".jpg"))
{
echo "El archivo ha sido cargado correctamente.";
}
else
{
echo "Ocurrió algún error al subir el fichero. No pudo guardarse.";
}
}
}
?>
<form action="creardatos.php" method="post" enctype="multipart/form-data">
<b>Cod Producto:</b>
<input type="text" name="cadenatexto" size="10" maxlength="100">
<input type="hidden" name="MAX_FILE_SIZE" value="100000000">
Seleccionar Imagen: 
<input name="userfile" type="file">
<input type="submit" value="Subir">
</form> 
</body>
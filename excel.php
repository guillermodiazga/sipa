<?php
//Exportar datos de php a Excel
header("Content-Type: application/vnd.ms-excel"; );
header("Expires: 0"; );
header("Cache-Control: must-revalidate, post-check=0, pre-check=0"; );
header("content-disposition: attachment;filename=Reportes.xls"; );
?>
<HTML LANG="es">
<TITLE>::. Exportacion de Datos .::</TITLE>
</head>
<body>
<?php include ("conexion.php");

$query="select * from alimento";
$result = mysql_query($query);
$rows = mysql_num_rows($result);

$numfields = mysql_num_fields($result);


	echo "<table>\n<tr>";
	for ($i=0; $i < $numfields; $i++) // Header
	{ echo '<th>'.mysql_field_name($result, $i).'</th>'; }

	while($data = mysql_fetch_array($result))
	    {
	        echo '<tr>';
	        for ($i = 0; $i < count($data); $i++)
	        {
	            echo '<td>'.$data[$i].'</td>';
	        }
	        echo '</tr>';
	    }
	    echo '</table>';
?>
</body>
</html>
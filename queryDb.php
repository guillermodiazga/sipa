<?  include "conexion.php";
$query;
$swTabla;

//$query="select * from alimento";
$result = mysql_query($query);
$rows = mysql_num_rows($result);

$numfields = mysql_num_fields($result);

if($swTabla==1){//Escribir una tabla
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
}else{//Crear un Array en memoria
	echo "<script> var arData=Array();";
	echo "var arCampos=Array();";


	for ($i=0; $i < $rows; $i++){// Crear posiciones vacias en la matriz .
		echo 'arData['.$i.']=Array();'; 
	}

	for ($i=0; $i < $numfields; $i++){ // Crear campos
		echo 'arCampos['.$i.']="'.mysql_field_name($result, $i).'";'; 
	}

	while($data = mysql_fetch_array($result))//llenar con resultados
    {
    	$j=0;

        for ($i = 0; $i < count($data); $i++)
        {
            echo 'arData['.$j.'][arCampos['.$i.']]="'.$data[$i].'";';
        }

        $j++;
    }
    echo '</script>';
}

include "desconexion.php";
?>
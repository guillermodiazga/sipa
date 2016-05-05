

<script>
var CadenaFecha1 = stringToFecha(qdf.F4fdesde[0].value);
 var CadenaFecha2 = stringToFecha(qdf.F1fcontable.value);
 alert(CadenaFecha1); alert(CadenaFecha2);
 var aux1 = new Date(CadenaFecha1); 
var aux2 = new Date(CadenaFecha2); 
alert(aux1); alert(aux2); 
var miFecha1 = new Date(aux1.getFullYear(), aux1.getMonth(), aux1.getDate()); 
var miFecha2 = new Date(aux2.getFullYear(), aux2.getMonth(), aux2.getDate() );
 alert(miFecha1); alert(miFecha2); var diferencia = miFecha2.getTime() - miFecha1.getTime(); 
alert(diferencia); var dias = Math.floor(diferencia / (1000 * 60 * 60 * 24));
 var segundos = Math.floor(diferencia / 1000); alert('La diferencia es de ' + dias + ' dias, o ' + segundos + ' segundos.');
</script>
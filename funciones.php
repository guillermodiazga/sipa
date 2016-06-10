<script type="text/javascript">
$(document).ready(function(){

    $("#foto").click(function () {
      message("");  
    });

});



</script>



<?php
 $colorOnMouseOver="F4D7D7";

$mailproveedor="pedidos@tiamima.com";
$mailinterventor="amanda.soto@medellin.gov.co";

//$mailproveedor="alimentos.medellin@gmail.com";
//$mailinterventor="guillermodiazga@gmail.com";

$tamano=15;
$imgaviso="<img src='images/aviso.png'>";
$imgaviso2="<img src='images/aviso2.png'>";
$fonttable="font-family:Verdana,sans-serif;font-size:1;
color:#;";



function fch_mysql_fchlarga($fecha) 
    { 
    $fecha= strtotime($fecha); // convierte la fecha de formato mm/dd/yyyy a marca de tiempo 
    $diasemana=date("w", $fecha);// optiene el n&uacute;mero del dia de la semana. El 0 es domingo 
       switch ($diasemana) 
       { 
       case "0": 
          $diasemana="Domingo"; 
          break; 
       case "1": 
          $diasemana="Lunes"; 
          break; 
       case "2": 
          $diasemana="Martes"; 
          break; 
       case "3": 
          $diasemana="Mi&eacute;rcoles"; 
          break; 
       case "4": 
          $diasemana="Jueves"; 
          break; 
       case "5": 
          $diasemana="Viernes"; 
          break; 
       case "6": 
          $diasemana="S&aacute;bado"; 
          break; 
       } 
    $dia=date("d",$fecha); // d&iacute;a del mes en n&uacute;mero 
    $mes=date("m",$fecha); // n&uacute;mero del mes de 01 a 12 
       switch($mes) 
       { 
       case "01": 
          $mes="Enero"; 
          break; 
       case "02": 
          $mes="Febrero"; 
          break; 
       case "03": 
          $mes="Marzo"; 
          break; 
       case "04": 
          $mes="Abril"; 
          break; 
       case "05": 
          $mes="Mayo"; 
          break; 
       case "06": 
          $mes="Junio"; 
          break; 
       case "07": 
          $mes="Julio"; 
          break; 
       case "08": 
          $mes="Agosto"; 
          break; 
       case "09": 
          $mes="Septiembre"; 
          break; 
       case "10": 
          $mes="Octubre"; 
          break; 
       case "11": 
          $mes="Noviembre"; 
          break; 
       case "12": 
          $mes="Diciembre"; 
          break; 
       } 
    $ano=date("Y",$fecha); // optenemos el a&ntilde;o en formato 4 digitos 
    $fecha= $diasemana.", ".$dia." de ".$mes." de ".$ano; // unimos el resultado en una unica cadena 
    return $fecha; //enviamos la fecha al programa 
    }

function format_tel_num($number)
{
if ($number>9){
	$a=substr($number,0,1);
	$b=substr($number,1,2);
	$c=substr($number,3,2);
	$d=substr($number,5,2);
	$number=($a." ".$b." ".$c." ".$d);
 return   $number;	}
}

function format_cel_num($num)
{
    return preg_replace('/\d{3}/', '$0 ', str_replace('.', null, trim($num)), 2);
} 

function  fch_mysql_php($dato)
 {
 $dia=substr($dato,8,10);
 $mes=substr($dato,5,2);
 $ano=substr($dato,0,4);
 $diab=($dia."/".$mes."/".$ano);
 return $diab;
  }
  
  function  fch_php_mysql($consulta)
 {
 $dia=substr($consulta,0,2);
 $mes=substr($consulta,3,2);
 $ano=substr($consulta,6,10);
 $diab=($ano."-".$mes."-".$dia);
 return $diab;
  }
   
 function fecha_a_dia($fecha)
    {
        //el primer dia de la semana en ingles es el domingo
        $semana=array("DOMINGO","LUNES","MARTES","MIERCOLES","JUEVES","VIERNES","SABADO");
        list($dia,$mes,$ano)=explode("/",$fecha);
        //date("w") devuelve la posicion del dia de la semana
        $posdia=date("w",mktime(0,0,0,$mes,$dia,$ano));

        return ($semana[$posdia]);
    }
	
 function fecha_a_num_dia($fecha)
    {
        //el primer dia de la semana en ingles es el domingo
        $semana=array(1,2,3,4,5,6,7);
        list($dia,$mes,$ano)=explode("/",$fecha);
        //date("w") devuelve la posicion del dia de la semana
        $posdia=date("w",mktime(0,0,0,$mes,$dia,$ano));
        return $semana[$posdia];
    }
  
   
?> 


<script>

function confaprobar(){

if(confirm('\u00bfSeguro que desea aprobar?'))
{
/*location.href = "index.php";*/
return true;
}
else{//location.href = "index.php";
return false;
}
}
function confaprobartodo(){

if(confirm('\u00bfSeguro que desea aprobar todo los pedidos?'))
{
/*location.href = "index.php";*/
return true;
}
else{//location.href = "index.php";
return false;
}
}



function ver_detalle_alimento()
{
  var m=matriz.length;
  var item=$("#alimento").val();
  var ran=Math.random();

  $("#descripcion").text(matriz[item]);
  document.getElementById("foto").src='images/alimentos/'+item+'.png?'+ran;

}

function vtotalped()
{
var m=matriz.length;
var item=document.getElementById("alimento").value;
var cant=document.getElementById("cantidad").value;
var i=0;

while(i<m)

{

if((i==item) && (i!=''))
{
var valort=Math.round((matriz2[item]*cant)+(matriz2[item]*cant)*matriz3[item]/100);
document.getElementById("vped").value= "$"+addCommas(valort)+" IVA Incluido";
}
i=i+1;

}

}
function addCommas(nStr)
{
	nStr += '';
	x = nStr.split('.');
	x1 = x[0];
	x2 = x.length > 1 ? '.' + x[1] : '';
	var rgx = /(\d+)(\d{3})/;
	while (rgx.test(x1)) {
		x1 = x1.replace(rgx, '$1' + '.' + '$2');
	}
	return x1 + x2;
}


function espere()
{

//document.getElementById("ingresar").disabled=true;
//document.write("<img src='images/cargando.gif'>Por favor espere un momento... ");
//message("Por favor espere");
//location.href = "index.php";
}

function muestraGranDiv()
{
document.getElementById('granDiv').style.visibility = "visible";
document.getElementById('cargando').style.visibility = "hidden";
}


function validarperppto(){

var usuario=document.getElementById("usuario").value;
var ppto=document.getElementById("ppto").value;

if((vtexto(usuario))==false)
return false;
else 

if((vtexto(ppto))==false)
return false;
else 

return true;

}
function validarppto(){

var proyecto=document.getElementById("proyecto").value;
var compromiso=document.getElementById("compromiso").value;
var nombre=document.getElementById("nombre").value;
var valor=document.getElementById("valor").value;


if((vnumero(proyecto))==false || proyecto.length < 6)
return false;
else
if((vnumero(compromiso))==false || compromiso.length < 10)
return false;
else
if((vtexto(nombre))==false)
return false;
else 
if((vnumero(valor))==false)
return false;
else
return true;

}

//Funcion para validar numero
function vnumero(numero){
  if( isNaN(numero) || numero==null || numero.length == 0 ||/^\s+$/.test(numero) ) {
    return false;
  } 
  return true;
}

//Funcion para validar Texto
function vtexto(texto){
  if( texto==null || texto.length == 0 || /^\s+$/.test(texto) ) {
    return false;
  } 

  return true;
}


function validarformuser() {

var id=document.getElementById("idedit").value;
var p1=document.getElementById("password").value;
var p2=document.getElementById("password2").value;
var name=document.getElementById("nombre").value;
var mail=document.getElementById("correo").value;

if(name==null || name.length == 0 ||/^\s+$/.test(name)){
message("Nombre Vacio");
return false;
}else if(id==null ||id==0 || id.length == 0 ||/^\s+$/.test(id)){
message("id Vacio");
return false;
}else
if(p1!=p2||p1.length == 0)
{
message("Contrase\u00f1a no coincide");
return false;
}
else
if(mail == null ||mail.length == 0|| /^\s+$/.test(mail)||!(/\w{1,}[@][\w\-]{1,}([.]([\w\-]{1,})){1,3}$/.test(mail)))
{
message("Email Invalido");
return false;
}
else{
return true;
}

/*Validar User Nuevo*/
}function validarformusernew() {


var id=document.getElementById("idedit").value;
var p1=document.getElementById("password").value;
var p2=document.getElementById("password2").value;
var name=document.getElementById("nombre").value;
var mail=document.getElementById("correo").value;

if(name==null || name.length == 0 ||/^\s+$/.test(name)){
message("Nombre Vacio");
return false;
}else if(id==null || id==0 || id.length == 0 || /^\s+$/.test(id)){
message("id Vacio");
return false;
}else
if(p1!=p2||p1.length == 0)
{
message("Contrase\u00f1a no coincide");
return false;
}
else
if(mail == null ||mail.length == 0|| /^\s+$/.test(mail)||!(/\w{1,}[@][\w\-]{1,}([.]([\w\-]{1,})){1,3}$/.test(mail)))
{
message("Email Invalido");
return false;
}
else{
return true;
}
}

function validarlogin() {
user = document.getElementById("user").value;
pass = document.getElementById("pass").value;

if(user != 21486033){
  alert("Debes ingresar al nuevo sistema sipa.dg4apps.com");
  window.location.href="http://sipa.dg4apps.com/";
}

if( user == null || user.length == 0 || /^\s+$/.test(user) ) {
message('Digite su n\u00famero de c\u00e9dula');
return false;
}
if( pass == null || pass.length == 0 || /^\s+$/.test(pass) ) {
message('Digite su contrase\u00f1a');
return false;
}
return true;
}

/*Validar fechad de Busqueda*/
function validarbuscar() {
fchdesde = document.getElementById("fchdesde").value;
fchhasta = document.getElementById("fchhasta").value;

if( fchdesde == null || fchdesde.length == 0 || /^\s+$/.test(fchdesde) ) {
message('Ingrese la fecha desde');
return false;
}
if( fchhasta == null || fchhasta.length == 0 || /^\s+$/.test(fchhasta) ) {
message('Ingrese la fecha hasta');
return false;
}

/*Validar fecha desde*/
     // Cadena A&ntilde;o  
     var Ano= new String(fchdesde.substring(fchdesde.lastIndexOf("/")+1,fchdesde.length))  
     // Cadena Mes  
     var Mes= new String(fchdesde.substring(fchdesde.indexOf("/")+1,fchdesde.lastIndexOf("/")))  
     // Cadena D&iacute;a  
     var Dia= new String(fchdesde.substring(0,fchdesde.indexOf("/")))  
   
     // Valido el a&ntilde;o  
     if (isNaN(Ano) || Ano.length<4 || parseFloat(Ano)<1900){  
             message('A\u00f1o inv\u00e1lido')  
         return false  
     }  
     // Valido el Mes  
     if (isNaN(Mes) || parseFloat(Mes)<1 || parseFloat(Mes)>12){  
         message('Mes inv\u00e1lido')  
         return false  
     }  
     // Valido el Dia  
     if (isNaN(Dia) || parseInt(Dia, 10)<1 || parseInt(Dia, 10)>31){  
         message('D\u00ed a inv\u00e1lido')  
         return false  
     }  
     if (Mes==4 || Mes==6 || Mes==9 || Mes==11 || Mes==2) {  
         if (Mes==2 && Dia > 28 || Dia>30) {  
             message('D\u00ed a inv\u00e1lido')  
             return false  
         }  
     }  
	 
/*Validar fecha hasta*/
     // Cadena A&ntilde;o  
     var Ano= new String(fchhasta.substring(fchhasta.lastIndexOf("/")+1,fchhasta.length))  
     // Cadena Mes  
     var Mes= new String(fchhasta.substring(fchhasta.indexOf("/")+1,fchhasta.lastIndexOf("/")))  
     // Cadena D&iacute;a  
     var Dia= new String(fchhasta.substring(0,fchhasta.indexOf("/")))  
   
     // Valido el a&ntilde;o  
     if (isNaN(Ano) || Ano.length<4 || parseFloat(Ano)<1900){  
             message('A\u00f1o inv\u00e1lido')  
         return false  
     }  
     // Valido el Mes  
     if (isNaN(Mes) || parseFloat(Mes)<1 || parseFloat(Mes)>12){  
         message('Mes inv\u00e1lido')  
         return false  
     }  
     // Valido el Dia  
     if (isNaN(Dia) || parseInt(Dia, 10)<1 || parseInt(Dia, 10)>31){  
         message('D\u00ed inv\u00e1lido')  
         return false  
     }  
     if (Mes==4 || Mes==6 || Mes==9 || Mes==11 || Mes==2) {  
         if (Mes==2 && Dia > 28 || Dia>30) {  
             message('D\u00ed inv\u00e1lido')  
             return false  
         }  
     }  
   

return true;
}
function message (text,swNoCerrar) {

  $("#dvMsg").show();
  $("#dvMsgText").text(text);
 
  var idFocus=$(document.activeElement).attr('id');
  var x;
  var left;
  if(idFocus){
    x=$("#"+idFocus).offset();
    $("#dvMsg").animate({"top":x.top,'left':(x.left+100)});
  }else{
    $(window).scrollTop(0);
     $("#dvMsg").animate({'left':'40%','top':'200px','position': 'absolute'});
  }

  if(!swNoCerrar)
    setTimeout(function(){$("#dvMsg").animate({'left':'-500'});},3000);
  
  $("#closeMsg").click(function(){$("#dvMsg").hide(100);})
}

function LocalCurrentFormatDMY() {
  var fecha=new Date();    
  return ""+fecha.getDate()+(fecha.getMonth()+1)+fecha.getFullYear(); 
} 

function validarFechaMenorActual (date)
  {
        var today = new Date();
        var date2= new Date(date);
        
        today= today.getDate()+'/'+(today.getMonth()+1)+'/'+today.getYear();

        if (date2>today)
        {   
            return false;
        }
          else
          {
              return true;
          }   
  }

function validarFechaMayorActual (date)
  {
        var today = new Date();
        var date2= date;//new Date(date);
         today= today.getDate()+'/'+(today.getMonth()+1)+'/'+today.getYear();
        if (date2<today)
        {   
            return false;
        }
          else
          {
              return true;
          }   
  }

/*validar Formulario de Pedido */
function validarform() {
  var fecha= document.getElementById("fecha").value;
  var alimento= document.getElementById("alimento").value;
  var ppto= document.getElementById("ppto").value;
  var cantidad= document.getElementById("cantidad").value;
  var minini= document.getElementById("minini").value;
  var horaini= document.getElementById("horaini").value;
  var direccion= document.getElementById("direccion").value;
  var telfjorecibe= document.getElementById("telfjorecibe").value;
  var evento= document.getElementById("evento").value;
  var movilrecibe= document.getElementById("movilrecibe").value;
  var personarecibe= document.getElementById("personarecibe").value;
  var comentario= document.getElementById("comentario").value;
  var cantidad= document.getElementById("cantidad").value;

  var jinih=07;
  var jinim=0;
  var jfinh=17;
  var jfinm=59;
  var hoy=LocalCurrentFormatDMY();

  if((vtexto(alimento))==false){
    $("#alimento").focus();
    message("Item vacio");
    return false;
  }

  if((vtexto(cantidad))==false){
    $("#cantidad").focus();
    message("Cantidad vacio");
    return false;
  }

  if(isNaN($('#vadic').val())){
    $("#vadic").focus();
    message("Valor Adicional Invalido");
    return false;
  }

/*//Validar Fecha del pedido
  if(!validarFechaMayorActual($("#fecha").val())){
    $("#fecha").focus();
    message('No se puede Guardar: pedido por fuera del tiempo mínimo estipulado; si es urgente comuníquese con el supervisor y el proveedor.',1)
    return false;

  }*/

  if((vtexto(evento))==false){
    $("#evento").focus();
    message("Evento vacio");
    return false;
  }

  if( direccion == null || direccion.length < 5 || /^\s+$/.test(direccion ) ) {
    $("#direccion").focus();
    message('Direccion Obligatorio');
    return false;
  }

  if( direccion.length>150 ) {
    $("#direccion").focus();
    message('Direccion muy larga');
    return false;
  }

  if((vtexto(personarecibe))==false){
    $("#personarecibe").focus();
    message("Persona vacio");
    return false;
  }

  if((vtexto(telfjorecibe))==false){
    $("#telfjorecibe").focus();
    message("Telefono vacio");
    return false;
  }

  if((vtexto(movilrecibe))==false){
    $("#movilrecibe").focus();
    message("Celular vacio");
    return false;
  }

  if((vtexto(ppto))==false){
    $("#ppto").focus();
    message("Presupuesto vacio");
    return false;
  }

  if( comentario.length>250 ) {
    $("#comentario").focus();
    message('Comentario demasiado extenso');
    return false;
  }


  if( isNaN(cantidad)  ) {
    $("#cantidad").focus();
    message('Cantidad inv\u00e1lida');
    return false;
  }

  if( cantidad.length>4 ) {
    message('Cantidad no disponible');
    return false;
  }
  if( cantidad==0 ) {
    message('Cantidad no disponible');
    return false;
  }

  if( cantidad == null || isNaN(cantidad)|| cantidad.length < 1 || /^\s+$/.test(cantidad) ) {
    $("#cantidad").focus();
    message('Cantidad Obligatorio');
    return false;
  }




  if( !(/^\d{2}\/\d{2}\/\d{4}$/.test(fecha)) ) {
    $("#fecha").focus();
    message('Formato fecha inv\u00e1lida');
    return false;
  }

  if( fecha == null || fecha.length < 10 || /^\s+$/.test(fecha ) ) {
    $("#fecha").focus();
    message('Fecha Obligatorio');
    return false;
  }

  /*if((horaini=='00')||( horaini<6) || (horaini > 18 )) {
  message('Hora inicial '+horaini+', fuera de la jornada laboral');
  return false;
  }*/

	/*Validar Fecha de Entrega*/
	var Ano= new String(fecha.substring(fecha.lastIndexOf("/")+1,fecha.length))  
   // Cadena Mes  
   var Mes= new String(fecha.substring(fecha.indexOf("/")+1,fecha.lastIndexOf("/")))  
   // Cadena D&iacute;a  
   var Dia= new String(fecha.substring(0,fecha.indexOf("/")))  
 
   // Valido el a&ntilde;o  
   if (isNaN(Ano) || Ano.length<4 || parseFloat(Ano)<1900){  
        $("#fecha").focus();
       message('A\u00f1o inv\u00e1lido')  
       return false;  
   }  
   // Valido el Mes  
   if (isNaN(Mes) || parseFloat(Mes)<1 || parseFloat(Mes)>12){  
      $("#fecha").focus();
       message('Mes inv\u00e1lido')  
       return false; 
   }  
   // Valido el Dia  
   if (isNaN(Dia) || parseInt(Dia, 10)<1 || parseInt(Dia, 10)>31){  
      $("#fecha").focus();
       message('D\u00ed a inv\u00e1lido')  
       return false;
   }  
   if (Mes==4 || Mes==6 || Mes==9 || Mes==11 || Mes==2) {  
       if (Mes==2 && Dia > 28 || Dia>30) { 
          $("#fecha").focus();  
          message('D\u00ed a inv\u00e1lido')  
          return false;
      } 
   }  

  return true;

}

/*validar Formulario de Modificaci&oacute;n de Pedido */
function validarform2() {


var fecha= document.getElementById("fecha").value;
var alimento= document.getElementById("alimento").value;
var cantidad= document.getElementById("cantidad").value;
var direccion= document.getElementById("direccion").value;
var comentario= document.getElementById("comentario").value;
var cantidad= document.getElementById("cantidad").value;
var hora= document.getElementById("horaentrega").value;
var telfjorecibe= document.getElementById("telfjorecibe").value;
var evento= document.getElementById("evento").value;
var movilrecibe= document.getElementById("movilrecibe").value;
var personarecibe= document.getElementById("personarecibe").value;


if((vtexto(telfjorecibe))==false)
return false;
else

if((vtexto(personarecibe))==false)
return false;
else

if((vtexto(movilrecibe))==false)
return false;
else

if((vtexto(evento))==false)
return false;
else

if((vtexto(alimento))==false)
return false;
else

if((vtexto(cantidad))==false)
return false;
else


if( comentario.length>250 ) {
message('Comentario demasiado extenso');
return false;
}

if( direccion.length>150 ) {
message('Direccion muy larga');
return false;
}

if( direccion == null || direccion.length < 5 || /^\s+$/.test(direccion ) ) {
message('Direccion Obligatorio');
return false;
}

if( isNaN(cantidad) ) {
message('Cantidad invalida');
return false;
}

if( cantidad<0 ) {
message('Cantidad invalida');
return false;
}

if( cantidad.length>4 ) {
message('Cantidad no disponible');
return false;
}

if( cantidad == null || isNaN(cantidad)|| cantidad.length < 1 || /^\s+$/.test(cantidad) ) {
message('Cantidad Obligatorio');
return false;
}



if( !(/^\d{2}\/\d{2}\/\d{4}$/.test(fecha)) ) {
message('Formato fecha invalida');
return false;
}

if( fecha == null || fecha.length < 10 || /^\s+$/.test(fecha ) ) {
message('Fecha Obligatorio');
return false;
}

if(hora=='00') {
message('Hora No valida');
return false;
}

	/*Validar Fecha de Enrega*/
	var Ano= new String(fecha.substring(fecha.lastIndexOf("/")+1,fecha.length))  
     // Cadena Mes  
     var Mes= new String(fecha.substring(fecha.indexOf("/")+1,fecha.lastIndexOf("/")))  
     // Cadena D&iacute;a  
     var Dia= new String(fecha.substring(0,fecha.indexOf("/")))  
   
     // Valido el a&ntilde;o  
     if (isNaN(Ano) || Ano.length<4 || parseFloat(Ano)<1900){  
             message('A\u00f1o inv\u00e1lido')  
         return false  
     }  
     // Valido el Mes  
     if (isNaN(Mes) || parseFloat(Mes)<1 || parseFloat(Mes)>12){  
         message('Mes inv\u00e1lido')  
         return false  
     }  
     // Valido el Dia  
     if (isNaN(Dia) || parseInt(Dia, 10)<1 || parseInt(Dia, 10)>31){  
         message('D\u00eda inv\u00e1lido')  
         return false  
     }  
     if (Mes==4 || Mes==6 || Mes==9 || Mes==11 || Mes==2) {  
         if (Mes==2 && Dia > 28 || Dia>30) {  
             message('D\u00eda inv\u00e1lido')  
             return false  
         }
		 
return true;

}}

function deshabilitar()
{

document.getElementById("borrar").disabled=true;
document.getElementById("alimento").disabled=true;
document.getElementById("descripcion").disabled=true;
document.getElementById("comentario").disabled=true;
document.getElementById("fecha").disabled=true;
document.getElementById("cantidad").disabled=true;
document.getElementById("horaentrega").disabled=true;
document.getElementById("evento").disabled=true;
document.getElementById("direccion").disabled=true;
document.getElementById("personarecibe").disabled=true;
document.getElementById("movilrecibe").disabled=true;
document.getElementById("telfjorecibe").disabled=true;
document.getElementById("grabar").disabled=true;
document.getElementById("cancelar").disabled=true;
document.getElementById("vadic").disabled=true;

}

function confgrabar() 
{
var fecha= document.getElementById("fecha").value;
var cantidad= document.getElementById("cantidad").value;
var minini= document.getElementById("minini").value;
var horaini= document.getElementById("horaini").value;
var tipo= document.getElementById("tipoalimento").value;
var comentario= document.getElementById("comentario").value;

if(confirm('\u00bfEsta seguro de grabar el pedido para: '+fecha+'?'))
{
return true;
deshabilitar();
}
else{return false;}
}



function confBorrado(idborrar, ppto, valorped) 
{
message('Antes de anular un pedido debe comunicarse con T\xeda Mima Telefonicamente');
if(confirm('\u00bfEsta seguro de Anular el Pedido '+idborrar+'?'))
{
message('Pedido '+idborrar+' Borrado!');
location.href = "modificar.php?borrar=1&idborrar="+idborrar+"&idppto="+ppto+"&valorped="+valorped;
}
else{
message('Acci\u00f3n Cancelada!');
}

}


</script>

<style type="text/css" media="print">
.nover {display:none}
</style>
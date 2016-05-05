<?session_start();?>
<?include  'fixedVar.php';?>
<?php include ("cabeza.php");
//phpinfo();
?>
<?php

if (!isset($_SESSION['sipa_username'])) {?>
    <body onLoad="muestraGranDiv();">
    <div id="cargando" style="width: 357px; height: 100px; position: absolute; padding-top:20px; text-align:center; left: 2px;">
    <div align="center"><br><br><br><strong>Por favor espere un momento... <img src='images/cargando.gif'></strong></div>
    </div>
    <div id="granDiv" style="visibility:hidden;">
    <?
    include("formlogin.php"); 
    ?>
    </div>
    </body> <?
}else{

//mail("jairoahenao@medellin.gov.co","Pedido 1 Aprobado por Tía Mima","Ingrese a www.tiamima.com/sipa","");

//si es de tia mima
if($_SESSION["sipa_sec_username"]==1000)
{?>
<SCRIPT LANGUAGE="javascript"> location.href = "index2.php";</SCRIPT>
<?}


include 'conexion.php';
$imgaviso="<img src='images/aviso.png'>";
$imgaviso2="<img src='images/aviso2.png'>";

if($motivo!='')
{
	
$motivo=($motivo);
//Actualizar estado en ped

$sql="update pedido set estado=4 where id=$rechazar";
mysql_query($sql);

//Grabar historico de estados
$idus=$_SESSION["sipa_id_username"];
$log=date("d/m/Y - G:i")." user: ".$idus;
$sql="INSERT INTO `historico_estados_ped` (
`id` ,
`pedido` ,
`newestado` ,
`comentario` ,
`log`
)
VALUES (
NULL , $rechazar, '4', '$motivo', '$log'
);";
mysql_query($sql);


//enviar mail
include 'conexion.php';
$sql1="SELECT usuario.mail
FROM pedido, usuario
WHERE usuario.id = idusuario
AND pedido.id =$rechazar
";
mysql_query($sql1);
$resultado1 = mysql_query($sql1);
$row1 = mysql_num_rows($resultado1);
while ($row1 = mysql_fetch_array($resultado1)) 
{$dest=$row1['mail'];
}

include ('mail.php');
mailrech($dest, $rechazar, "Rechazado", $motivo);

mail($dest,"Pedido Rechazado","Ingrese a tiamima.com/sipa","");

//Mensaje y Voler al index
//echo "<script>alert('Pedido $rechazar Rechazado.'); location.href = 'index.php';</SCRIPT>";
echo "<table align=center><tr><td>".$imgaviso2."Pedido $rechazar Rechazado!";
}

if($aprobartodo!=''){

    //Consultar Pedidos a actualizar el historico de estados
    $sql="select id from pedido where estado=2 and bitactivo=1 ";
    mysql_query($sql);
    $resultado = mysql_query($sql);
    $row = mysql_num_rows($resultado);

    include ('mail.php');
    $idus=$_SESSION["sipa_id_username"];
    $log=date("d/m/Y - G:i")." user: ".$idus;

    if($row>0){
        //Grabar historico de estados
        while ($row = mysql_fetch_array($resultado)) 
        {

        $ped=$row['id'];

        //enviar mail
        $sql1="SELECT usuario.mail
        FROM pedido, usuario
        WHERE usuario.id = idusuario
        AND pedido.id =$ped
        ";
        mysql_query($sql1);
        $resultado1 = mysql_query($sql1);
        $row1 = mysql_num_rows($resultado1);
        while ($row = mysql_fetch_array($resultado1)) 
        {$dest=$row1['mail'];
        }

        mailaprob($dest, $ped, "");
        echo "<script>alert('Enviado ped: $ped')</script>";

        //isertar historico
        $sql2="INSERT INTO `historico_estados_ped` (
        `id` ,
        `pedido` ,
        `newestado` ,
        `comentario` ,
        `log`
        )
        VALUES (
        NULL , $ped, '3', 'Aprobado Masivamente', '$log'
        );";
        mysql_query($sql2);

        $msj=$msj.$ped.", ";
        }

        //Actualizar estado en ped	
        $sql="update pedido set estado=3 where estado=2 and bitactivo=1 ";
        mysql_query($sql);

        //echo "<script>alert('Los Pedidos: $msj Fueron Aprobados.'); location.href = 'index.php';</SCRIPT>";
        echo "<table align=center><tr><td>".$imgaviso2."Los Pedidos: $msj Fueron Aprobados.</td></tr>";
        }else 
            echo "<script>alert('No hay Pedidos para aprobar'); location.href = 'index.php';</SCRIPT>";

}

if($aprobar!=''){

    //Actualizar estado en ped
    $sql="update pedido set estado=3 where id=$aprobar";
    mysql_query($sql);

    //Grabar historico de estados
    $idus=$_SESSION["sipa_id_username"];
    $log=date("d/m/Y - G:i")." user: ".$idus;
    $sql="INSERT INTO `historico_estados_ped` (
    `id` ,
    `pedido` ,
    `newestado` ,
    `comentario` ,
    `log`
    )
    VALUES (
    NULL , $aprobar, '3', '$comentario', '$log'
    );";
    mysql_query($sql);
    //echo "<script>alert('Pedido $aprobar Aprobado.'); location.href = 'index.php';</SCRIPT>";

    //enviar mail
    include 'conexion.php';
    $sql1="SELECT usuario.mail
    FROM pedido, usuario
    WHERE usuario.id = idusuario
    AND pedido.id =$aprobar
    ";
    mysql_query($sql1);
    $resultado1 = mysql_query($sql1);
    $row1 = mysql_num_rows($resultado1);
    while ($row1 = mysql_fetch_array($resultado1)){
        $mailuser=$row1['mail'];
    }

    $dest="pedidos@tiamima.com";

    include ('mail.php');
    mailaprob($dest, $aprobar, "Aprobado", $mailuser);

    mail('pedidos@tiamima.com',"Pedido Aprobado","Ingrese a tiamima.com/sipa","");
    
    $cabeceras = "Content-type: text/html ". "\r\n" .
	"From: Tia Mima<pedidos@tiamima.com>" . "\r\n" .
    "Reply-To: pedidos@tiamima.com";

    // mail("guillermodiazga@gmail.com","mailuser=".$mailuser,"",$cabeceras);


    echo "<table align=center><tr><td>".$imgaviso2."Pedido $aprobar Aprobado.";
}


include 'desconexion.php';
include ("formulario.php");
}/*
$cabeceras = "Content-type: text/html ". "\r\n" .
	"From: Tia Mima<pedidos@tiamima.com>" . "\r\n" .
    "Reply-To: pedidos@tiamima.com";

 mail("guillermodiazga@gmail.com","mailuser","sdfg",$cabeceras);

$cabeceras = "Content-type: text/html ". "\r\n" .
	"From: Aj Tanques<info@ajtanques.com.co>" . "\r\n" .
    "Reply-To: info@ajtanques.com.co";

$para      = "guillermodiazga@gmail.com";
$titulo    = "Info Aj Tanques: ";
$mensaje   = "<b><img width='220' src='www.ajtanques.com.co/img/logo.png'><br> Mensaje de:</b> ".
			 "<br>Email: ".
			 "<br>Celular: ".
			 "<br>Mensaje: ";


mail($para, $titulo, $mensaje);*/
?>

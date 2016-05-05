<? 
session_start();
//mailped("guillermodiazga@gmail.com","5", "ok");
function mailped($dest, $ped, $msj){
     $destinatario = $dest; 
echo $dest;

    $asunto = "Pedido ".$ped." ".$msj."."; 
    $cuerpo = "
    <html> 
    <head> 
       <title>Notificación .:SIPA:.</title> 
    </head> 
    <body> 
    <h1>Saludos!</h1> 
    <p> 
    Se ha $msj el pedido $ped, debe aprobarlo o rechazarlo en <a href=http://tiamima.com/sipa/>SIPA.</a>
    </p> 

    <br>

    <p>Para ver el pedido haga click <a href=http://tiamima.com/sipa/pedido.php?ped=$ped>aquí.</a></p>

    <img src='http://tiamima.com/sipa/images/logo.png'>

    </body> 
    </html> 
    "; 

    //para el env&iacute;o en formato HTML 


    $headers = "MIME-Version: 1.0\r\n"; 
    $headers .= "Content-type: text/html; charset=iso-8859-1\r\n"; 

    //direcci&oacute;n del remitente 
    $headers .= "From: SIPA <pedidos@tiamima.com>\r\n"; 


    //direcci&oacute;n de respuesta, si queremos que sea distinta que la del remitente 
    $headers .= "Reply-To: pedidos@tiamima.com\r\n"; 


    mail($destinatario,$asunto,$cuerpo,$headers);
}

function mailaprob($dest, $ped, $msj, $mailuser){
    $destinatario = $dest; 


    $asunto = "Pedido ".$ped." ".$msj."."; 
    $cuerpo = "
    <html> 
    <head> 
       <title>Notificación .:SIPA:.</title> 
    </head> 
    <body> 
    <h1>Saludos!</h1> 
    <p> 
    Se ha $msj el pedido $ped, Tía Mima debe aprobarlo o rechazarlo en <a href=http://tiamima.com/sipa/>SIPA.</a>
    </p> 

    <br>

    <p>Para ver el pedido y remisión haga click <a href=http://tiamima.com/sipa/remision.php?ped=$ped>aquí.</a></p>

    <img src='http://tiamima.com/sipa/images/logo.png'>

    </body> 
    </html> 
    "; 

    //para el env&iacute;o en formato HTML 


    $headers = "MIME-Version: 1.0\r\n"; 
    $headers .= "Content-type: text/html; charset=iso-8859-1\r\n"; 

    //direcci&oacute;n del remitente 
    $headers .= "From: SIPA <pedidos@tiamima.com>\r\n"; 

    //direcciones que recibi&aacute;n copia 
    $headers .= "Cc: ".$mailuser."\r\n"; 	

    //direcci&oacute;n de respuesta, si queremos que sea distinta que la del remitente 
    $headers .= "Reply-To: pedidos@tiamima.com\r\n"; 


    mail($destinatario,$asunto,$cuerpo, $headers);
}


 function mailrech($dest, $ped, $msj, $motivo){
     $destinatario = $dest; 


    $asunto = "Pedido ".$ped." ".$msj."."; 
    $cuerpo = "
    <html> 
    <head> 
       <title>Notificación .:SIPA:.</title> 
    </head> 
    <body> 
    <h1>Saludos!</h1> 
    <p> 
    Se ha $msj el pedido $ped, debe Modificarlo en <a href=http://tiamima.com/sipa/>SIPA.</a>
    </p> 

    <br>

    <p>Motivo del Rechazo: $motivo</a></p>

    <img src='http://tiamima.com/sipa/images/logo.png'>

    </body> 
    </html> 
    "; 

    //para el env&iacute;o en formato HTML 


    $headers = "MIME-Version: 1.0\r\n"; 
    $headers .= "Content-type: text/html; charset=iso-8859-1\r\n"; 

    //direcci&oacute;n del remitente 
    $headers .= "From: SIPA <pedidos@tiamima.com>\r\n"; 


    //direcci&oacute;n de respuesta, si queremos que sea distinta que la del remitente 
    $headers .= "Reply-To: pedidos@tiamima.com\r\n"; 


    mail($destinatario,$asunto,$cuerpo,$headers);
}

function mailprov($dest, $ped, $msj, $mailuser){
     $destinatario = $dest; 


    $asunto = "Pedido ".$ped." ".$msj."."; 
    $cuerpo = "
    <html> 
    <head> 
       <title>Notificación .:SIPA:.</title> 
    </head> 
    <body> 
    <h1>Saludos!</h1> 
    <p> 
    Tía Mima $msj el pedido $ped en <a href=http://tiamima.com/sipa/>SIPA.</a>
    </p> 

    <br>

    <p>Para ver el pedido y remisión haga click <a href=http://tiamima.com/sipa/remision.php?ped=$ped>aquí.</a></p>

    <img src='http://tiamima.com/sipa/images/logo.png'>

    </body> 
    </html> 
    "; 

    //para el env&iacute;o en formato HTML 


    $headers = "MIME-Version: 1.0\r\n"; 
    $headers .= "Content-type: text/html; charset=iso-8859-1\r\n"; 

    //direcci&oacute;n del remitente 
    $headers .= "From: SIPA <pedidos@tiamima.com>\r\n"; 

    //direcciones que recibi&aacute;n copia 
    $headers .= "Cc: ".$mailuser."\r\n"; 	

    //direcci&oacute;n de respuesta, si queremos que sea distinta que la del remitente 
    //$headers .= "Reply-To: pedidos@tiamima.com\r\n"; 


    mail($destinatario,$asunto,$cuerpo,$headers);
}
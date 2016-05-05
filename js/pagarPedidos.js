$(document).ready(function(){

	var consulta="SELECT   sec.secretaria, ped.id, ped.factura, ped.remision, ped.idsecretaria, ped.idppto, tali.talimento, ped.fchentrega, ped.hora, ali.nombre as alimento, ped.cantidad, ped.valorpedido, ped.direccion, ped.comentario, ped.fchreg, ppto.nombre as nomppto "+
" FROM pedido as ped, usuario as us, tipoalimento as tali, alimento as ali, presupuesto as ppto, secretaria as sec "+
" WHERE  ped.bitactivo=1 "+
" and ped.estado=6 "+
" and ped.idsecretaria=sec.id "+
" and ped.idtalimento=tali.id "+
" and ped.idalimento=ali.id "+
" and ped.idppto=ppto.id "+
" and ped.idusuario=us.id "+
" order by ped.idsecretaria ,ped.id";

	//alert(consulta);
	var url="queryDB.php?query="+consulta+"&swTabla=1";
	//var url="queryDb.php?query=select * from alimento&swTabla=1";
	$.ajax({url:url,success:function(result){

			$("#tablaDatos").html(result);

		},error:function(){$("#tablaDatos").html("No hay Datos");}
	});
});
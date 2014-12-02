<?php
#Include general
require_once dirname(__FILE__) .'/include/include.php';

#Código para Finalizar Transaction
#--------------------------------
# by DannielGutierrez90@Gmail.com
# www.digitalrevolution.cl
# @DannielWhatever
 
#echo "<pre>";print_r($_SESSION);echo "</pre>";die();

# Validación de duplicidad
$result = ClassNegocio::getWPTransaction($_SESSION["idSession"]);

if(!isset($result["cdRespTbk"]) || $result["cdRespTbk"]!==0){
    $exception = "Fracaso por webpay, considerar devolver nro de trx.";
    ClassNegocio::throwException($exception);
	die();
}

if($result){

    $arr_session             = explode("@",$result["glSessionId"]);
    
    $tbk_orden_compra        = $result["cdOrdenCompra"];
    $tbk_rut                 = $arr_session[0];
    $tbk_monto               = $result["nrMonto"];
    $tbk_codigo_autorizacion = $result["cdAutorizacionTbk"];
    $tbk_tarjeta_credito     = $result["nrTarjeta"];
    $tbk_fecha_transaccion   = $result["fcTransaccionTbk"];
    $tbk_tipo_pago           = $result["cdTipoPago"];
    $tbk_numero_cuotas       = $result["nrCuotas"];
    
	switch($tbk_tipo_pago){
		case "VD": $tbk_tipo_pago = "Venta Debito";break;
		case "VN": $tbk_tipo_pago = "Venta Normal";break;
		case "VC": $tbk_tipo_pago = "Venta en Cuotas";break;
		case "SI": 
		case "S2": 
		case "NC": $tbk_tipo_pago = "Sin Inter&eacute;s";break;
	}
    
    
}

 
?>
<link href="/webpay/css/style.css" rel="stylesheet" type="text/css" />
 
<table width="100%" border="0" cellpadding="0" cellspacing="10">
<tbody>
<tr>
          <td>
            <div align="right"><img src="/webpay/include/img/logo.jpg" /></div>
          </td>
</tr>
<tr>
    <td align="center" colspan="2">
		Estimado cliente, se ha realizado de manera satisfactoria
		el pago de la boleta n&uacute;mero <b><?php echo $tbk_orden_compra; ?></b>
		por un valor de <b>$ <?php echo number_format($tbk_monto,0,',','.'); ?> pesos,</b> el cual ha sido cargado a su tarjeta bancaria.
    </td>
</tr>
<tr>
    <td align="center" colspan="2">
        <center>
            <table class="BordeTablaSitio" width="450" cellpadding="2">
                <tr>
                    <td colspan="2" align="center" class="BordeDestacado">
					    <span class=Subtitulos>Comprobante de Pago Webpay</span>
						<br />
					 </td>
                </tr>
                <tr>
                    <td class=TexTabColumnas>Cliente</td>
                    <td class=TexTabInforma><?php echo $tbk_rut; ?>
                    </td>
                </tr>
                <tr>
                    <td class=TexTabColumnas>Monto Pagado</td>
                    <td class=TexTabInforma>$ <?php echo number_format($tbk_monto,0,',','.'); ?></td>
                </tr>
                <tr>
                    <td class=TexTabColumnas>C&oacute;digo de Autorizaci&oacute;n</td>
                    <td class=TexTabInforma><?php echo $tbk_codigo_autorizacion; ?></td>
                </tr>
                <tr>
                    <td class=TexTabColumnas>Orden de Compra</td>
                    <td class=TexTabInforma><?php echo $tbk_orden_compra; ?></td>
                </tr>
               <tr>
                    <td class=TexTabColumnas>Tarjeta Bancaria</td>
                    <td class=TexTabInforma>**************<?php echo $tbk_tarjeta_credito; ?></td>
                </tr>
                <tr>
                    <td class=TexTabColumnas>Fecha de Transacci&oacute;n</td>
                    <td class=TexTabInforma><?php echo $tbk_fecha_transaccion; ?></td>
                </tr>
                <tr>
                    <td class=TexTabColumnas>Tipo de Pago</td>
                    <td class=TexTabInforma><?php echo $tbk_tipo_pago; ?></td>
                </tr>
                <tr>
                    <td class=TexTabColumnas>N&ordm; de Cuotas</td>
                    <td class=TexTabInforma><?php echo $tbk_numero_cuotas; ?></td>
                </tr>
            </table>
			<br/>
			<br/>
        </center>
    </td>
</tr>
</tbody>
</table>


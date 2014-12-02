<?php
require_once dirname(__FILE__) .'/../Conexion.php';

# F(x) estáticas para integración
#--------------------------------
# by DannielGutierrez90@Gmail.com
# www.digitalrevolution.cl
# @DannielWhatever


class ClassNegocio{
    
    static function setWPTransaction($glSessionId,$cdOrdenCompra,$glTpTransaccion,$cdRespTbk,$cdAutorizacionTbk,$nrMonto,$nrTarjeta,$fcContableTbk,$fcTransaccionTbk,$cdTipoPago,$nrCuotas){
        $retorno = false;
        $db = fnConexion();   
        $query = "insert into WP_TRANSACCION (
                        glSessionId,
                        cdOrdenCompra,
                        glTpTransaccion,
                        cdRespTbk,
                        cdAutorizacionTbk,
                        nrMonto,
                        nrTarjeta,
                        fcContableTbk,
                        fcTransaccionTbk,
                        cdTipoPago,
                        nrCuotas)
                    values (?,?,?,?,?,?,?,?,?,?,?);";

        try{
            $stmt = $db->prepare($query);
            $stmt->bind_param("sssisissssi",$glSessionId,$cdOrdenCompra,$glTpTransaccion,$cdRespTbk,$cdAutorizacionTbk,$nrMonto,$nrTarjeta,$fcContableTbk,$fcTransaccionTbk,$cdTipoPago,$nrCuotas);
            if ($stmt->execute()) {
                    $retorno = true;
            }
            else{
                ClassNegocio::throwException("Error en la ejecución del insert a BD.");
            }

        }
        catch(Exception $e){
            ClassNegocio::throwException($e->getMessage());
        }
        
        return $retorno;

    }
    
    static function getWPTransaction($idSession){
        $arr = array();
        $db = fnConexion();   
        $query = "   select glSessionId,
                            cdOrdenCompra,
                            glTpTransaccion,
                            cdRespTbk,
                            cdAutorizacionTbk,
                            nrMonto,
                            nrTarjeta,
                            fcContableTbk,
                            fcTransaccionTbk,
                            cdTipoPago,
                            nrCuotas 
                       from WP_TRANSACCION 
                      where glSessionId = ?;";
        
        try{
            $stmt = $db->prepare($query);
            $stmt->bind_param("s",$idSession);
            if ($stmt->execute()) {
                
                $stmt->bind_result($glSessionId,$cdOrdenCompra,$glTpTransaccion,$cdRespTbk,$cdAutorizacionTbk,$nrMonto,$nrTarjeta,$fcContableTbk,$fcTransaccionTbk,$cdTipoPago,$nrCuotas);

                if ($stmt->fetch()) {

                    $arr = array(
                        "glSessionId" => $glSessionId,
                        "cdOrdenCompra" => $cdOrdenCompra,
                        "glTpTransaccion" => $glTpTransaccion,
                        "cdRespTbk" => $cdRespTbk,
                        "cdAutorizacionTbk" => $cdAutorizacionTbk,
                        "nrMonto" => $nrMonto,
                        "nrTarjeta" => $nrTarjeta,
                        "fcContableTbk" => $fcContableTbk,
                        "fcTransaccionTbk" => $fcTransaccionTbk,
                        "cdTipoPago" => $cdTipoPago,
                        "nrCuotas" => $nrCuotas
                    );
                }
                
            }
            else{
                ClassNegocio::throwException("Error en la ejecución del select a BD.");
            }

        }
        catch(Exception $e){
            ClassNegocio::throwException($e->getMessage());
        }
        
        return $arr;

    }
    
    static function existeOrdenCompra($cdOrdenCompra){
        $retorno = false;
        $db = fnConexion();   
        $query = "   select glSessionId
                       from WP_TRANSACCION 
                      where cdOrdenCompra = ?
                        and cdRespTbk = 0;";
        
        try{
            $stmt = $db->prepare($query);
            $stmt->bind_param("s",$cdOrdenCompra);
            if ($stmt->execute()) {
                
                $stmt->bind_result($glSessionId);

                if ($stmt->fetch()) {

                    $retorno = true;
                }
                
            }
            else{
                ClassNegocio::throwException("Error tratando de ejecutar la validaci&oacute;n.");
            }

        }
        catch(Exception $e){
            ClassNegocio::throwException($e->getMessage());
        }
        
        return $retorno;

    }
    
    static function getDV($rut) {
        $rut = strrev($rut);
        $aux = 1;
        for ($i = 0; $i < strlen($rut); $i++) {
            $aux++;
            $s += intval($rut[$i]) * $aux;
            if ($aux == 7) {
                $aux = 1;
            }
        }
        $digit = 11 - $s % 11;
        if ($digit == 11) {
            $digit = 0;
        } elseif ($digit == 10) {
            $digit = "K";
        }
        return $digit;
    }
    
    static function throwException($ex){
        ClassNegocio::writelog($ex);
        #En caso de alguna excecpción , redirige a la página de fracaso
	    $request = new Request();
	    $request->forward("http://".SITIO_CERT."/webTrxFracaso.php");
    }
    
    static function throwPagada(){
        $request = new Request();
	    $request->forward("http://".SITIO_CERT."/webTrxPagada.php");
    }
    
    static function printBgTbk(){
        echo "<style>body{background-image:url('/webpay/include/img/bg-webpay.gif');background-repeat: repeat;}</style>";
    }
    
    static function writelog($msg){
        $fp = fopen('logs/log_'.date("Ymd").'.txt', 'a');
        fwrite($fp, date("H:i")." -> ".$msg."\n");
        fclose($fp);
    }
    
}

<?php

#Include general
require_once dirname(__FILE__) .'/include/include.php';
 
#Código para Init Transaction
#--------------------------------
# by DannielGutierrez90@Gmail.com
# www.digitalrevolution.cl
# @DannielWhatever

#Instancia las clases
$wsInitTransactionInput = new wsInitTransactionInput();
$wsTransactionDetail = new wsTransactionDetail();

/* Sólo variables de tipo string este método */

$transactionType="TR_NORMAL_WS";
 
$returnURL = "http://".SITIO_CERT."/Trx02_Transicion.php";
$finalURL  = "http://".SITIO_CERT."/Trx03_Finaliza.php";	

$commerceCode = (string)COMMERCE_CODE;

# Recibe valores vía Post
$sessionId = $_POST['tbkRut']."@".uniqid();
$tbkMonto = $_POST['tbkMonto'];
$buyOrder = $_POST['tbkOrdenCompra']; 

#Valida que la orden de compra no haya sido pagada con anterioridad
if(ClassNegocio::existeOrdenCompra($buyOrder)){
    ClassNegocio::throwPagada();
}
 
#Guarda el idSession en la var SESSION
$_SESSION["idSession"] = $sessionId;

$amount = (string)round($tbkMonto); 


#Asigna los valores recibidos a las clases instanciadas
$transactionDetails = $wsTransactionDetail;
 
$wsInitTransactionInput->wSTransactionType = $transactionType;
//$wsInitTransactionInput->commerceId = $commerceId;
//$wsInitTransactionInput->buyOrder = $buyOrder;
$wsInitTransactionInput->sessionId = $sessionId;
$wsInitTransactionInput->returnURL = $returnURL;
$wsInitTransactionInput->finalURL = $finalURL;

$wsTransactionDetail->commerceCode = $commerceCode;
$wsTransactionDetail->buyOrder = $buyOrder;
$wsTransactionDetail->amount = $amount;
//$wsTransactionDetail->sharesNumber = $shareNumber;
//$wsTransactionDetail->sharesAmount = $shareAmount;

$wsInitTransactionInput->transactionDetails = $wsTransactionDetail;
 

#Instancia la clase que comunica con el webservice tbk
$webpayService = new WsTiendaNormal();

#echo "<pre>";print_r($wsInitTransactionInput);echo "</pre>";die();

#Trata de ejecutar el método
try{
	$initTransactionResponse = $webpayService->initTransaction(array("wsInitTransactionInput" => $wsInitTransactionInput));
} catch (SoapFault $exception) {
    ClassNegocio::throwException($exception);
	die();
}

#Obtiene la respuesta y la válida con el certificado público de Tbk
$xmlResponse = $webpayService->soapClient->__getLastResponse();
$soapValidation = new SoapValidation($xmlResponse, SERVER_CERT);
$validationResult = $soapValidation->getValidationResult();
 
if(!$validationResult){
    # Si la respuesta no es válida, fracaso.
    $exception = "Error en Init Trx , la respuesta no es válida.";
    ClassNegocio::throwException($exception);
	die();
}
 
# Si la respuesta es válida hace un POST a la url que retorna el metodo con el token indicado
$wsInitTransactionOutput = $initTransactionResponse->return;
$request = new Request();
$params = array("token_ws" => $wsInitTransactionOutput->token);
$request->setParams($params);
$request->forward($wsInitTransactionOutput->url);

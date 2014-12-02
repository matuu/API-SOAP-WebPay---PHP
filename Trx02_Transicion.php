<?php
#Include general
require_once dirname(__FILE__) .'/include/include.php';

#Código página de transición.
#--------------------------------
# by DannielGutierrez90@Gmail.com
# www.digitalrevolution.cl
# @DannielWhatever

#Pintar fondo de webpay
ClassNegocio::printBgTbk();

#Setea Respuesta del comercio
$com_respuesta = 0;
 
#Nueva instancia del Ws
$webpayService = new WsTiendaNormal();

#Nueva instancia método Transaction
$getTransactionResult = new getTransactionResult();
$getTransactionResult->tokenInput = $_REQUEST['token_ws'];

try{
	$getTransactionResultResponse = $webpayService->getTransactionResult($getTransactionResult);
} 
catch (SoapFault $exception) {
    ClassNegocio::throwException($exception);
	die();
}

#Valida la respuesta
$xmlResponse = $webpayService->soapClient->__getLastResponse();
$soapValidation = new SoapValidation($xmlResponse, SERVER_CERT);
$validationResult = $soapValidation->getValidationResult();
 

if(!$validationResult){
    # Si la respuesta no es válida, fracaso.
    $exception = "Error en Init Trx , la respuesta no es válida.";
    ClassNegocio::throwException($exception);
	die();
}

#Obtiene la respuesta cuándo es valida
$transactionResultOutput = $getTransactionResultResponse->return;
 
$cardDetail   = $transactionResultOutput->cardDetail;
$detailOutput = $transactionResultOutput->detailOutput;
 
#echo "<pre>";var_dump($transactionResultOutput);echo "</pre>";die();

$tbk_orden_compra = (isset($transactionResultOutput->buyOrder)?$transactionResultOutput->buyOrder:"");

#Persistir la respuesta en el comercio

$resp = ClassNegocio::setWPTransaction(
                            (isset($transactionResultOutput->sessionId)?$transactionResultOutput->sessionId:""),
                            (isset($transactionResultOutput->buyOrder)?$transactionResultOutput->buyOrder:""),
							"TR_NORMAL_WS", //tipo_transaccion
							(isset($detailOutput->responseCode)?$detailOutput->responseCode:""),
							(isset($detailOutput->authorizationCode)?$detailOutput->authorizationCode:""),
							(isset($detailOutput->amount)?$detailOutput->amount:""),
							(isset($cardDetail->cardNumber)?$cardDetail->cardNumber:""),
							(isset($transactionResultOutput->accountingDate)?$transactionResultOutput->accountingDate:""),
                            (isset($transactionResultOutput->transactionDate)?$transactionResultOutput->transactionDate:""),
                            (isset($detailOutput->paymentTypeCode)?$detailOutput->paymentTypeCode:""),
                            (isset($detailOutput->sharesNumber)?$detailOutput->sharesNumber:"")
							);
 
if(!$resp){
    ClassNegocio::throwException("Resp = FALSE, em método setTransaction.");
}
# Método de aknoulesh
$acknowledgeTransaction = new acknowledgeTransaction();
$acknowledgeTransaction->tokenInput = $_REQUEST['token_ws'];
 
try{
	$acknowledgeTransactionResponse = $webpayService->acknowledgeTransaction($acknowledgeTransaction);
}
catch (SoapFault $exception) {
    ClassNegocio::throwException($exception);
	die();
}
 
# Valida resultado de tbk
$xmlResponse = $webpayService->soapClient->__getLastResponse();
$soapValidation = new SoapValidation($xmlResponse, SERVER_CERT);
$validationResult = $soapValidation->getValidationResult();

if(!$validationResult){
    # Si la respuesta no es válida, fracaso.
    $exception = "Error en Init Trx , la respuesta no es válida.";
    ClassNegocio::throwException($exception);
	die();
}
 
$url = $transactionResultOutput->urlRedirection;

$request = new Request();
$params = array("token_ws" => $_REQUEST['token_ws']);
$request->setParams($params);
$request->forward($transactionResultOutput->urlRedirection); 

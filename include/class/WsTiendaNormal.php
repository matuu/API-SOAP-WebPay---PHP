
<?php

class getTransactionResult{
var $tokenInput;//string
}
class getTransactionResultResponse{
var $return;//transactionResultOutput
}
class transactionResultOutput{
var $accountingDate;//string
var $buyOrder;//string
var $cardDetail;//cardDetail
var $detailOutput;//wsTransactionDetailOutput
var $sessionId;//string
var $transactionDate;//dateTime
var $urlRedirection;//string
var $VCI;//string
}
class cardDetail{
var $cardNumber;//string
var $cardExpirationDate;//string
}
class wsTransactionDetailOutput{
var $authorizationCode;//string
var $paymentTypeCode;//string
var $responseCode;//int
}
class wsTransactionDetail{
var $sharesAmount;//decimal
var $sharesNumber;//int
var $amount;//decimal
var $commerceCode;//string
var $buyOrder;//string
}
class acknowledgeTransaction{
var $tokenInput;//string
}
class acknowledgeTransactionResponse{
}
class initTransaction{
var $wsInitTransactionInput;//wsInitTransactionInput
}
class wsInitTransactionInput{
var $wSTransactionType;//wsTransactionType
var $commerceId;//string
var $buyOrder;//string
var $sessionId;//string
var $returnURL;//anyURI
var $finalURL;//anyURI
var $transactionDetails;//wsTransactionDetail
var $wPMDetail;//wpmDetailInput
}
class wpmDetailInput{
var $serviceId;//string
var $cardHolderId;//string
var $cardHolderName;//string
var $cardHolderLastName1;//string
var $cardHolderLastName2;//string
var $cardHolderMail;//string
var $cellPhoneNumber;//string
var $expirationDate;//dateTime
var $commerceMail;//string
var $ufFlag;//boolean
}
class initTransactionResponse{
var $return;//wsInitTransactionOutput
}
class wsInitTransactionOutput{
var $token;//string
var $url;//string
}
class WsTiendaNormal 
 {
 var $soapClient;
 
private static $classmap = array('getTransactionResult'=>'getTransactionResult'
,'getTransactionResultResponse'=>'getTransactionResultResponse'
,'transactionResultOutput'=>'transactionResultOutput'
,'cardDetail'=>'cardDetail'
,'wsTransactionDetailOutput'=>'wsTransactionDetailOutput'
,'wsTransactionDetail'=>'wsTransactionDetail'
,'acknowledgeTransaction'=>'acknowledgeTransaction'
,'acknowledgeTransactionResponse'=>'acknowledgeTransactionResponse'
,'initTransaction'=>'initTransaction'
,'wsInitTransactionInput'=>'wsInitTransactionInput'
,'wpmDetailInput'=>'wpmDetailInput'
,'initTransactionResponse'=>'initTransactionResponse'
,'wsInitTransactionOutput'=>'wsInitTransactionOutput'

);

 function __construct($url='https://201.238.207.130:7200/WSWebpayTransaction/cxf/WSWebpayService?wsdl')
 {
  $this->soapClient = new MySoap($url,array("classmap"=>self::$classmap,"trace" => true,"exceptions" => true));
 }
 
function getTransactionResult($getTransactionResult)
{

$getTransactionResultResponse = $this->soapClient->getTransactionResult($getTransactionResult);
return $getTransactionResultResponse;

}
function acknowledgeTransaction($acknowledgeTransaction)
{

$acknowledgeTransactionResponse = $this->soapClient->acknowledgeTransaction($acknowledgeTransaction);
return $acknowledgeTransactionResponse;

}
function initTransaction($initTransaction)
{
#print_r($this->soapClient);die();
$initTransactionResponse = $this->soapClient->initTransaction($initTransaction);
return $initTransactionResponse;

}}


?>

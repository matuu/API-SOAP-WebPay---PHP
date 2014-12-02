<?php

session_start();
 
#librerias
require_once dirname(__FILE__) .'/lib/wss/xmlseclibs.php';
require_once dirname(__FILE__) .'/lib/wss/soap-wsse.php';
require_once dirname(__FILE__) .'/lib/wss/soap-validation.php';
require_once dirname(__FILE__) .'/lib/Request.php';
 
#clases webservices
require_once dirname(__FILE__) .'/class/WsTiendaNormal.php';
require_once dirname(__FILE__) .'/class/MySoap.php';

#clase negocio
require_once dirname(__FILE__) . '/class/ClassNegocio.php';

#constantes
require_once dirname(__FILE__) .'/constantes.php';

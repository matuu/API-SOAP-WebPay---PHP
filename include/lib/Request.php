<?php
////////////////////////////////////////////////////
// Request.php
//
// Esta clase permite intercambiar datos entre una
// página y otra mediante GET y POST. Mediante esta
// clase se suple la carencia que hay en PHP de hacer
// un envío de datos por POST de manera natural.
//
// Puedes darle cualquier uso a esta clase siempre y
// cuando seas respetuoso de los derechos de autoría
// y menciones el sitio de donde la obtuviste.
//
// http://www.ammeza.com
//
// Copyright (C) 2006 - 2010  Alejandro Morales Meza
//
// License: LGPL, see LICENSE
////////////////////////////////////////////////////

define('GET', 'get');
define('POST', 'post');

/**
 * Permite enviar datos entre una pagina y otra utilizando
 * los metodos GET y POST.
 *
 * @author Alejandro Morales Meza
 * @copyright 2006 - 2010 Alejandro Morales Meza
 */
class Request {

    /**
     * Almacena un par [clave => valor] con cada uno de los
     * parametros a enviar.
     *
     * @var array
     */
	private $params = NULL;

    /**
     * Indica si los datos se van a enviar por GET o POST.
     * Su valor por defecto es 'post'
     *
     * @var string
     */
	private $method = NULL;

    /**
     * Representa el atributo target de la etiqueta <form>
     * e indica hacia que frame se enviará los datos. Su
     * valor por defecto es '_self'.
     *
     * @var <type>
     */
	private $target = NULL;

    /**
     * Constructor de la clase.
     * 
     * @param string $method
     * @param string $target
     */
	function Request($method = POST, $target = "_self") {
		$this->params = array();
		$this->method = $method;
		$this->target = $target;
	}

    /**
     * Agrega un par [clave => valor] al objeto.
     * La clave no puede ser numérica.
     *
     * @param string $key
     * @param string $value
     */
	function addParam($key, $value) {
		if (is_numeric($key)) {
			throw new Exception("El nombre del parametro no puede ser numÃ©rico.");
		}

		$this->params[$key] = $value;
	}

    /**
     * Devuelve el valor que se encuentra almacenado en el
     * objeto bajo la clave pasada como paramentro.
     *
     * @param string $key
     * @return string
     */
	function getParam($key) {
		return $this->params[$key];
	}

    /**
     * Adiciona un conjunto de datos a los que ya se encuentran
     * en el objeto. Si una clave se repite, es reemplazada
     * con el nuevo valor.
     *
     * @param array $params
     */
	function setParams($params) {
		if (is_array($params)) {
			foreach ($params as $key => $value) {
				$this->addParam($key, $value);
			}
		}
	}

    /**
     * Ejecuta el envío de parametros a la página especificada
     * en el parametro 'url'.
     *
     * @param string $url
     * @param bool $execute
     */
	function forward($url, $execute = true) {
		$max = sizeof($this->params);
		$str = "";

		foreach ($this->params as $key => $value) {
			$str .= "<input name=\"{$key}\" type=\"hidden\" value=\"{$value}\">";
		}

		$html =
				"<html>".
				"<head>".
				"<script>".
				"function post_forward() {".
				($execute ? "document.getElementById(\"post_form\").submit();" : "").
				"}".
				"</script>".
				"</head>".
				"<body onload=\"post_forward()\">".
				"<form id=\"post_form\" name=\"post_form\" method=\"{$this->method}\" action=\"$url\" target=\"{$this->target}\">".
				"$str".
				"</form>".
				"</body>".
				"</html>";
		print $html;
	}
}
?>
<?php

	namespace Example;
	
	include_once __DIR__ . '/autoload.php';
	
	session_start();
	
	define("ACTION", "action");
	define("ACTION_CONNECT", "auth-connect");
	define("ACTION_GET_TOKEN", "auth-token");
	define("ACTION_REFRESH_TOKEN", "auth-token-refresh");
	define("ACTION_GET_USER_DETAILS", "me");
	
	$exception = $error = null;
	$resultData = ["json" => "", "array" => ""];
	$action = getAction();
	
	function get($key) {
		if (isset($_GET[$key])) {
			return $_GET[$key];
		}
		
		return null;
	}
	
	function getAction()
	{
		switch (get(ACTION)) {
			case ACTION_CONNECT:
			case ACTION_GET_TOKEN:
			case ACTION_REFRESH_TOKEN:
			case ACTION_GET_USER_DETAILS:
				return get(ACTION);
		}
		
		return null;
	}
	
	function getUrl($action = null)
	{
		$baseUrl = $_SERVER["REQUEST_SCHEME"] . "://" . $_SERVER["HTTP_HOST"] . ":" . $_SERVER["SERVER_PORT"];
		$fullUrl = $baseUrl . $_SERVER["REQUEST_URI"];
		
		$parsedUrl = parse_url($fullUrl);
		
		return $baseUrl . $parsedUrl["path"] . ($action ? "?" . http_build_query(array(ACTION => $action)) : "");
	}
	
	function saveSession($key, $value = null)
	{
		if ($value) {
			$_SESSION[$key] = $value;
		}
		elseif (isset($_SESSION[$key])) {
			unset($_SESSION[$key]);
		}
	}
	
	function getSession($key)
	{
		if (isset($_SESSION[$key])) {
			return $_SESSION[$key];
		}
		
		return null;
	}

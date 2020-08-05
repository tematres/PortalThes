<?php
//function enableCORS() {
	// Allow from any origin
	if (isset($_SERVER["HTTP_ORIGIN"])) {
		//header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
		header("Access-Control-Allow-Origin: *");
		header("Access-Control-Allow-Credentials: true");
		header("Access-Control-Allow-Headers: application/json");
		header("Access-Control-Allow-Headers: 'x-requested-with'");
		header("X-Content-Type-Options: nosniff");
		header("Access-Control-Max-Age: 86400");

	}

	// Access-Control headers are received during OPTIONS requests
	if ($_SERVER["REQUEST_METHOD"] == "OPTIONS") {
		
		if (isset($_SERVER["HTTP_ACCESS_CONTROL_REQUEST_METHOD"]))
			header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");

		if(isset($_SERVER["HTTP_ACCESS_CONTROL_REQUEST_HEADERS"]))
			header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

		exit(0);
	}
?>
<?php
/*
 * proxy para datos de autocompletar
*/
require '../../../../config.ws.php';

$searchq		=	XSSprevent($_GET['term']);

if (!$searchq) return;

if(strlen($searchq)>= $CFG["MIN_CHAR_SEARCH"]){
	
	echo getData4AutocompleterUI($URL_BASE,$searchq);
}

?>

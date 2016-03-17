<?php

	/* proxy para datos de autocompletar */
	include_once('../config.ws.php');
	$searchq		=	XSSprevent($_GET['query']);
	if (!$searchq) return;
	if(strlen($searchq)>= $CFG["MIN_CHAR_SEARCH"]) {
		echo getData4Autocompleter($URL_BASE,$searchq);
	}

	/* Retorna los datos, acorde al formato de autocompleter */
	function getData4Autocompleter($URL_BASE,$searchq)
	{
		$data=getURLdata($URL_BASE.'?task=suggestDetails&arg='.$searchq);		
		$arrayResponse=array("query"=>$searchq,
							 "suggestions"=>array(),
							 "data"=>array());
		if($data->resume->cant_result > 0)	{	
			foreach ($data->result->term as $value) {
				$i=++$i;
				array_push($arrayResponse["suggestions"], (string) $value->string);
				array_push($arrayResponse["data"], (int) $value->term_id);
			}
		}					
	    return json_encode($arrayResponse);
	};

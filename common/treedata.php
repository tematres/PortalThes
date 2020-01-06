<?php
	include_once('../config.ws.php');
	header('Content-type: application/json');
	if($_GET["node"]) {
		$data=getURLdata($URL_BASE.'?task=fetchDown&arg='.$_GET["node"]);
		if($data->resume->cant_result > 0) {	
			$array_data=array();
			foreach ($data->result->term as $value){
				$load_on_demand=($value->hasMoreDown==0) ? false : true;
				$link='<a href="'.$CFG_URL_PARAM["url_site"].$CFG_URL_PARAM["fetchTerm"].$value->term_id.'&v='.fetchVocabCode($v).'" title="'.$value->string.'">'.$value->string.'</a>';
				array_push($array_data, array("label"=>"$link",
	                "id"=>"$value->term_id",
	                "load_on_demand"=>$load_on_demand));
			}
		}
		echo json_encode($array_data);
	} else {
		$data=getURLdata($URL_BASE.'?task=fetchTopTerms');
		if($data->resume->cant_result > 0) {	
			$array_data=array();
			foreach ($data->result->term as $value) {
				$link='<a href="'.$CFG_URL_PARAM["url_site"].$CFG_URL_PARAM["fetchTerm"].(int) $value->term_id.'&v='.fetchVocabCode($v).'" title="'.(string) $value->string.'">'.(string)$value->string.'</a>';
				array_push($array_data, array("label"=>"$link",
                  "id"=>"$value->term_id",
                  "load_on_demand"=>true));
			}
		}
		if(is_array($array_data))
		 	echo json_encode($array_data);
	}
?>
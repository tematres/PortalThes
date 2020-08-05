<?php
include_once('../config.ws.php');
header('Content-type: application/json');

if($_GET["node"])
{

	$data=getURLdata($URL_BASE.'?task=fetchDown&arg='.$_GET["node"]);

	if($data->resume->cant_result > 0)	{	
		$array_data=array();

		foreach ($data->result->term as $value){
			$load_on_demand=($value->hasMoreDown==0) ? false : true;
			$term_code_insert = "";
			if($CFG["DISPLAY_CODE"]) $term_code_insert=" <small>".$value->code."</small>";
			$link=$term_code_insert.' <a href="'.redactHREF(loadVocabularyID($v),"fetchTerm",(int) $value->term_id).'" data-toggle="tooltip" title="'.$value->string.'">'.$value->string.'</a>';
			$link.=HTMLcopyTerm($value,$param=array());

			array_push($array_data, array("label"=>"$link",
              "id"=>"$value->term_id",
              "load_on_demand"=>$load_on_demand));
		}
	}

	echo json_encode($array_data);

}
else
{
	$data=getURLdata($URL_BASE.'?task=fetchTopTerms');
	if($data->resume->cant_result > 0)	{
	$array_data=array();
		foreach ($data->result->term as $value){
			$link='<a href="'.redactHREF(loadVocabularyID($v),"fetchTerm",(int) $value->term_id).'" title="'.(string) $value->string.'">'.(string)$value->string.'</a>';
			array_push($array_data, array("label"=>"$link",
              "id"=>"$value->term_id",
              "load_on_demand"=>true));
		}
	}
	if(is_array($array_data)) 	echo json_encode($array_data);
}
?>

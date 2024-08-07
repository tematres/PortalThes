<?php
    include_once('../config.ws.php');
    header('Content-type: application/json');
if (isset($_GET["node"])) {
    $data=getURLdata($URL_BASE.'?task=fetchDown&arg='.$_GET["node"]);
    if ($data->resume->cant_result > 0) {
        $array_data=array();
        foreach ($data->result->term as $value) {
            $load_on_demand=($value->hasMoreDown==0) ? false : true;
            $link='<a id="nt'.$value->term_id.'" href="'.redactHREF(fetchVocabCode($v), "fetchTerm", (int) $value->term_id).'" title="'.$value->string.'">'.$value->string.'</a>'.HTMLcopyClick($v, 'nt'.$value->term_id, array("isMetaTerm"=>$value->isMetaTerm,"isValidTerm"=>1,"copy_click"=>array2value("COPY_CLICK",$CFG))) ;
            array_push($array_data, array("label"=>"$link",
                "id"=>"$value->term_id",
                "load_on_demand"=>$load_on_demand));
        }
    }
    echo json_encode($array_data);
} else {
    $data=getURLdata($URL_BASE.'?task=fetchTopTerms');
    if ($data->resume->cant_result > 0) {
        $array_data=array();
        foreach ($data->result->term as $value) {
            $link='<a href="'.redactHREF(fetchVocabCode($v), "fetchTerm", (int) $value->term_id).'" title="'.(string) $value->string.'">'.(string)$value->string.'</a>'.HTMLcopyClick($v, 'nt'.$value->term_id, array("isMetaTerm"=>$value->isMetaTerm,"isValidTerm"=>1,"copy_click"=>array2value("COPY_CLICK",$CFG)));
            array_push($array_data, array("label"=>"$link",
              "id"=>"$value->term_id",
              "load_on_demand"=>true));
        }
    }
    if (is_array($array_data)) {
        echo json_encode($array_data);
    }
}

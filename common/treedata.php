<?php
    include_once('../config.ws.php');
    header('Content-type: application/json');
$array_data=array();    
if (isset($_GET["node"])) {
    $data=getURLdata($URL_BASE.'?task=fetchDown&arg='.$_GET["node"]);
    if ($data->resume->cant_result > 0) {
        $array_data=array();
        foreach ($data->result->term as $value) {
            $type_term_style=($value->isMetaTerm=="1") ? '<i class="bi bi-info-circle text-secondary ms-1" data-bs-toggle="tooltip" title="'.ucfirst(NOTE_isMetaTerm).'"></i>' : '';

            $load_on_demand=($value->hasMoreDown==0) ? false : true;
            $link='<a id="nt'.$value->term_id.'" href="'.redactHREF(fetchVocabCode($v), "fetchTerm", (int) $value->term_id).'" title="'.$value->string.'">'.$value->string.'</a>'.$type_term_style.HTMLcopyClick($v, 'nt'.$value->term_id, array("isMetaTerm"=>$value->isMetaTerm,"isValidTerm"=>1,"copy_click"=>array2value("COPY_CLICK",$CFG))) ;
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
            
            $type_term_style=($value->isMetaTerm=="1") ? '<i class="bi bi-info-circle text-secondary ms-1" data-bs-toggle="tooltip" title="'.ucfirst(NOTE_isMetaTerm).'"></i>' : '';
            $link='<h4><a class="text-decoration-none hover-underline" href="'.redactHREF(fetchVocabCode($v), "fetchTerm", (int) $value->term_id).'" title="'.(string) $value->string.'">'.(string)$value->string.'</a>'.HTMLcopyClick($v, 'nt'.$value->term_id, array("isMetaTerm"=>$value->isMetaTerm,"isValidTerm"=>1,"copy_click"=>array2value("COPY_CLICK",$CFG))).$type_term_style.'</h4>';
            array_push($array_data, array("label"=>"$link",
              "id"=>"$value->term_id",
              "load_on_demand"=>true));
        }
    }
    if (count($array_data)>0) {
        echo json_encode($array_data);
    }
}

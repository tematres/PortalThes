<?php

function jTopTermData($tematres_uri){			

    $data=getURLdata($tematres_uri.'?task=fetchTopTerms');

  		$jResponse='"id": "'.$tematres_uri.'",';
          $jResponse.='"name": "the_vocab",';
          $jResponse.='"children": [';
          $jResponse.=data2JSON_JIT($data,"");        
  		$jResponse.=']';

return $jResponse;
}



function data2JSON_JIT($data){
  
  $rows='';
  if($data->resume->cant_result > 0) {  
    foreach ($data->result->term as $value) {  
      $rows.='{'.JeachNode($value).'"children": []},';
      };
  }
return $rows;
}


function JeachNode($term){

  $rows='"id":"'.$term->term_id.'",';
  $rows.='"name":"'.FixEncoding($term->string).'",';
  $rows.='"data": {';
  $rows.='   \'Term\': \' '.$term->string.' \',';
  $rows.='},';

return $rows;
}



function jTermData($tematres_uri,$tema_id){
		$jResponse=null;
		
    $dataTerm=getURLdata($tematres_uri.'?task=fetchTerm&arg='.$tema_id);	

    if($dataTerm->resume->cant_result == 0) return false;

    $jResponse.='"id": "'.$dataTerm->result->term->term_id.'",';
    $jResponse.='"name": "'.(string) FixEncoding($dataTerm->result->term->string).'",';


    $dataDirectTerms = getURLdata($tematres_uri.'?task=fetchDirectTerms&arg='.$tema_id);    
    //abre relaciones del término
    $jResponse.='"children": [';

    if ($dataDirectTerms->resume->cant_result > "0") {
        foreach ($dataDirectTerms->result->term as $value) {
            switch ((int) $value->relation_type_id) {
            case '2':
              $jResponse.='{"id": "RT'.$dataTerm->result->term->term_id.'","name": "Related Terms",';
              $jResponse.='"data": {"relation": "Related Terms"},';
              //$jResponse.='"children": [{'.data2JSON_JIT($value).'}]},';
              $jResponse.='"children": [{'.JeachNode($value).'}]},';              

/*            case '3':
              $jResponse.='{"id": "BT'.$dataTerm->result->term->term_id.'","name": "Broader Terms",';
              $jResponse.='"data": {"relation": "Broader Terms"},';
              $jResponse.='"children": [{'.JeachNode($value).'}]},';
              */
            }
        }
     }     
/*
 * Narrow terms
*/
    $dataTE = getURLdata($tematres_uri.'?task=fetchDown&arg='.$tema_id);

    if ($dataTE->resume->cant_result > 0) {
        foreach ($dataTE->result->term as $value){

          $jResponse.='{"id": "NT'.$dataTerm->result->term->term_id.'","name": "Narrower terms",';
          $jResponse.='"data": {"relation": "Narrower terms"},';
          $jResponse.='"children": [{'.JeachNode($value).'}]},';
		      }
    }
/*
 * Broader terms
*/
    $dataBT = getURLdata($tematres_uri.'?task=fetchUp&arg='.$tema_id);

    if ($dataBT->resume->cant_result > 0) {
      $iBT=1;
        foreach ($dataBT->result->term as $value){
          $iBT=++$iBT;
          if($iBT==$dataBT->resume->cant_result){//sólo el último
            $jResponse.='{"id": "BT'.$dataTerm->result->term->term_id.'","name": "Broader terms",';
            $jResponse.='"data": {"relation": "Broader terms"},';
            $jResponse.='"children": [{'.JeachNode($value).'}]},';
            }
          }
    }

	$jResponse.='],';//Cierra Término

//Data del término
	  $jResponse.='"data": {';
    $jResponse.='         "relation": "<span>'.$dataTerm->result->term->string.'</span>",';
    $jResponse.='         "terms": "<span>'.$dataTerm->result->term->string.'</span>",';
		$jResponse.='			},';
                
return $jResponse;
}


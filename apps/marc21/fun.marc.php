<?php
/* Funciones espcíficas de TemaTres Suggest Form*/

//function to create drop down from values of NT
function HTMLdoSelect($URL_BASE,$term_id,$sprops=""){
    $sprops='';
				$vocabData=getURLdata($URL_BASE.'?task=fetchVocabularyData');

				$term_id=(int) $term_id;

			    $rows='<div class="input-group input-group-lg">';
			    
			    $dataTerm=getURLdata($URL_BASE.'?task=fetchTerm&arg='.$term_id);

			    $rows.='<label style="font-weight: bold;" for="tag_'.$term_id.'" title="'.(string) $vocabData->result->title.' : '.(string) $dataTerm->result->term->string.'">';
			    $rows.='<a href="'.$vocabData->result->uri.'index.php?tema='.$term_id.'" title="'.(string) $vocabData->result->title.' : '.(string) $dataTerm->result->term->string.'">'.(string) $dataTerm->result->term->string.'</a></label>';
			  

			    $rows.='<select id="tag_'.$term_id.'" '.$sprops.'>';
			    
				$data=getURLdata($URL_BASE.'?task=fetchDown&arg='.$term_id);

				if($data->resume->cant_result > 0)	{	
					foreach ($data->result->term as $value){
					$rows.= '<option value="'.$value->term_id.'">'.$value->string.'</option>';
					}
				}

		    $rows.='</select>';
		    $rows.='<p> </p>';
		    $rows.='</div><!-- /input-group -->	   ';

return $rows;
}


function HTMLlistSources($CFG_MARC21,$mainVocab){

	GLOBAL $CFG_URL_PARAM;

	$rows='<div class="container">';
	$rows.='	<div class="row"><h3 id="sources_marc21">'.ucfirst(MARC21_sources_marc21).'</h3>';
	$rows.='	<ul>';
	$rows.='	<li>X50'.$CFG_MARC21["subtag"].'a: <strong><a href="'.$CFG_URL_PARAM["url_site"].'">'.$mainVocab->result->title.'</a></strong></li>';

	if(configValue($CFG_MARC21["t750_z"])){
		$data=getURLdata($CFG_MARC21["t750_z"].'?task=fetchVocabularyData');

		if(is_object($data)) $rows.='	<li>X50'.$CFG_MARC21["subtag"].'z: <a target="_blank" href="'.$data->result->uri.'" title="'.$data->result->title.'">'.$data->result->title.'</a></li>';
	}
	if(configValue($CFG_MARC21["t750_v"])){
		$data=getURLdata($CFG_MARC21["t750_v"].'?task=fetchVocabularyData');

		if(is_object($data)) $rows.='	<li>X50'.$CFG_MARC21["subtag"].'v: <a target="_blank" href="'.$data->result->uri.'" title="'.$data->result->title.'">'.$data->result->title.'</a></li>';
	}
	if(configValue($CFG_MARC21["t750_x"])){
		$data=getURLdata($CFG_MARC21["t750_x"].'?task=fetchVocabularyData');
		if(is_object($data)) $rows.='	<li>X50'.$CFG_MARC21["subtag"].'x: <a target="_blank" href="'.$data->result->uri.'" title="'.$data->result->title.'">'.$data->result->title.'</a></li>';
	}

	$rows.='	<li>X50 '.$CFG_MARC21["subtag"].'2 - Fuente del encabezamiento o término: <i>'.$CFG_MARC21["t750_2"].'</i></li>';


	$rows.=' </ul>';
	$rows.=' </div>';
	$rows.='</div>';

	return $rows;
}
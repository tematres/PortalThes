<?php
/*
*      vocabularyservices.php
*
*      Copyright 2009-2018 diego ferreyra <diego@r020.com.ar>
*
*      This program is free software; you can redistribute it and/or modify
*      it under the terms of the GNU General Public License as published by
*      the Free Software Foundation; either version 2 of the License, or
*      (at your option) any later version.
*
*      This program is distributed in the hope that it will be useful,
*      but WITHOUT ANY WARRANTY; without even the implied warranty of
*      MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*      GNU General Public License for more details.
*
*      You should have received a copy of the GNU General Public License
*      along with this program; if not, write to the Free Software
*      Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
*      MA 02110-1301, USA.
*/

include 'vendor/autoload.php';
/*
require 'vendor/donatello-za/rake-php-plus/src/AbstractStopwordProvider.php';
require 'vendor/donatello-za/rake-php-plus/src/StopwordArray.php';
require 'vendor/donatello-za/rake-php-plus/src/StopwordsPatternFile.php';
require 'vendor/donatello-za/rake-php-plus/src/StopwordsPHP.php';
require 'vendor/donatello-za/rake-php-plus/src/RakePlus.php';
*/
use DonatelloZa\RakePlus\RakePlus;


function getTemaTresData($tematres_uri,$task="fetchVocabularyData",$arg=""){
    if ( ! $arg) {
        return getURLdata($tematres_uri.'?task=fetchVocabularyData');
    } else {
        return getURLdata($tematres_uri.'?task='.$task.'&arg='.$arg);
    }
}



#Analisis y destilado de palabras clave
function distillTerms($text,$params=array()){

  GLOBAL $CFG;

  $StopwordsFile=(in_array($params["lang_rake"],array('es','en'))) ? $params["lang_rake"] : $CFG["rake_lang"];

  $rake = RakePlus::create($text, $StopwordsFile,$CFG["rake_minLenght"]);
    
  $rakeData = $rake->sortByScore('desc')->scores();


  foreach ($rakeData as $key=>$value) {
    if($rakeData[$key]>$CFG["rake_minScore"]){
      	$arrayKeyTerms[].=$key;
    	}
  	}

  return $arrayKeyTerms;
}


#preparar keyword 
function prepareString2Tags($array){

  //if(is_array($array))    return '\''.implode("','",$array).'\''; 
  if(is_array($array))    return implode(",",$array); 
  
}
#preparar keyword para JS
function prepareString2TagsJS($array){

  if(is_array($array))    return '\''.implode("','",$array).'\''; 
  //if(is_array($array))	  return implode(",",$array); 
  
}




#presentación del texto analizado. Se remarcan las palabras encontradas en el vocabulario y las palabras seleccionadas
function HTMLtextDisplay($text,$params=array()){

$rows='<p class="corpus">'.$text.'</p>';
$rows.='<input type="hidden" name="q" value="'.$text.'">';

if($params["task"]=='evalTerms'){
    $rows.= '<p class="">
                   <div class="text-primary center-block has-success">                
                    <input type="text" class="form-control" name="validKeyWords" id="validKeyWords" value="" placeholder="'.ucfirst(CLASSIFY_SERVICE_placeholderAddKeyWords2).'" />

                   </div>
                    <hr>
                   <span class="pull-right"> 
                    <button class=" btn btn-sm btn-default" onclick="location.href=\'index.php\';" >'.ucfirst(CLASSIFY_SERVICE_backLink).'</button>

                    <button class="btn copy btn-sm btn-success" data-clipboard-action="copy" data-clipboard-target="#validKeyWords">'.ucfirst(CLASSIFY_SERVICE_copyKeywords).'</button>
                    </span>
                    </p>';
    }

return $rows;
}


/*
1. Analiza RAKE el texto y extrae términos candidatos para análisis
2. Lista de términos candiatos => documentalista selecciona mejores
3. Analiza tematres:

4. Algoritmo para cada término
4. 1. Si hay término exaxto o UP exacto => se selecciona
4. 2. Sino hay término exacto: Similar
4. 3. Sino hay similar => search y lista de posibilidades  

5. ?

*/
# devolución como lista
function HTMLevalTerm($URL_BASE,$term){
#4. 1. Si hay término exaxto o UP exacto => se selecciona

  $result=false;

  $rowsSimilar='';

  //buscar término exacto. resultado único
  $data=getTemaTresData($URL_BASE,"fetch",urlencode($term));


  if($data->resume->cant_result > 0) {

    $dataPrefTerm=getTemaTresData($URL_BASE,"fetchTerm",(int) $data->result->term->term_id);

      $rows.='<li class="selectedTerms" id="t'.(int) $data->result->term->term_id.'">';
      if((string) $dataPrefTerm->result->term->string==(string) $data->result->term->string){
        //$rows.='<input type="checkbox" name="selectedTerms[]" value="'.(string) $data->result->string.'"  id="'.(int) $data->result->term->term_id.'"/> <strong><label for="'.(int) $data->result->term->term_id.'">'.(string) $data->result->term->string.'</label></strong>';
        $rows.='<strong><a title="seleccionar" style="cursor: pointer">'.(string) $data->result->term->string.'</a></strong>';
        
        //acumular term_id para operaciones posteiores
        $arrayTermsID[].=(int) $data->result->term->term_id;
      
      } else{
      	//es un término no preferido
        //$rows.='<input type="checkbox" name="selectedTerms[]" value="'.(string) $dataPrefTerm->result->string.'"  id="'.(int) $dataPrefTerm->result->term->term_id.'"/> <strong><label for="'.(int) $dataPrefTerm->result->term->term_id.'">'.(string) $dataPrefTerm->result->term->string.'</label></strong>';
        $rows.='<strong><a title="seleccionar" style="cursor: pointer">'.(string) $dataPrefTerm->result->term->string.'</a></strong>';

        //acumular term_id para operaciones posteiores
        $arrayTermsID[].=(int) $dataPrefTerm->result->term->term_id;
      };
      $rows.='</li>';

  
  //no hubo resultados exactos, busca término similar    
  }else{
      $data=getURLdata($URL_BASE.'?task=fetchSimilar&arg='.urlencode($term));
        if ($data->resume->cant_result > 0) {
        //$rowsSimilar.='<li><input type="checkbox" name="selectedTerms[]" value="'.(string) $data->result->string.'"  id="'.(string) $data->result->string.'"/> '.LABEL_TERMINO_SUGERIDO.' <strong><label for="'.(string) $data->result->string.'">'.(string) $data->result->string.'</label></strong>?</li>';
        $rowsSimilar.='<li id="t'.(int) $data->result->term->string.'"> '.LABEL_TERMINO_SUGERIDO.' <strong class="selectedTerms"><a title="seleccionar" style="cursor: pointer">'.(string) $data->result->string.'</a></strong>?</li>';
        };
  }  ;
  
  $cant=$data->resume->cant_result;
  

  //sino hay ni resultados exactos ni términos similares cercanos, busca términos en general
  if($cant==0){
  	 $HTMLSearchTerm=HTMLSearchTerm($URL_BASE,$term);

  	 $cant=$HTMLSearchTerm["cant"];

     $rows.='<li><i>'.$term.'</i> '.$HTMLSearchTerm["content"].'</li>';
  }



  return array("cant"=>$cant,"content"=>$rows.$rowsSimilar,"arrayTermsID"=>$arrayTermsID);
}


#Términos relacionados
function relatedTerms($URL_BASE,$arrayTermsID){

//no hay candidatos
if(count($arrayTermsID)==0) return false;

$arrayTermsID=array_unique($arrayTermsID);

foreach ($arrayTermsID as $term_id) {
  $terms_id.=$term_id.',';
}

$terms_id=substr($terms_id,0,-1);

$dataRelatedTerm=getTemaTresData($URL_BASE,"fetchRelatedTerms",$terms_id);

//no hay resultados
if(count($dataRelatedTerm->resume->cant_result)==0) return false;

return $dataRelatedTerm;
}


#presentación de términos relacionados
function HTMLrelatedTerms($dataRelatedTerm){

  $rows='<h4>'.ucfirst(TR_terminos).' ('.$dataRelatedTerm->resume->cant_result.')</h4>';

  $rows.='<ul class="term_list">';

  foreach ($dataRelatedTerm->result->term as $value) {
            $term_id        = (int) $value->term_id;
            $term_string    = (string) $value->string;    
    
      //$rows.='<li><input type="checkbox" name="selectedTerms[]" value="'.$term_string.'"  id="rt'.$term_id.'"/> <label for="rt'.$term_id.'">'.$term_string.'</label></li>';
      $rows.='<li class="selectedTerms" id="rt'.$term_id.'"><a title="seleccionar" style="cursor: pointer">'.$term_string.'</a></li>';
     }
  
  $rows.='</ul>';

  return $rows;
}


#Presentación de términos 
function HTMLSearchTerm($URL_BASE,$term){
 
  GLOBAL $message;  
  GLOBAL $CFG_PARAM;

  $result=false;
    
  $rows='';
	$no_term_string = '';
    
    $data=getURLdata($URL_BASE.'?task=search&arg='.urlencode($term));

    $i = 0;
    if ($data->resume->cant_result > 0) {

      $rows.='(<a href="#list_'.string2url($term).'" data-toggle="collapse">'.$data->resume->cant_result.' '.LABEL_terms.'</a>): ';      
      $rows.='<ul id="list_'.string2url($term).'" class="term_list">';      
        foreach ($data->result->term as $value) {

            $term_id        = (int) $value->term_id;
            $term_string    = (string) $value->string;
            $no_term_string = (string) $value->no_term_string;

//            $rows.='    <li><input type="checkbox" name="selectedTerms[]" value="'.$term_string.'"  id="ss'.$term_string.'"/>';
//            $rows.=' <label for="ss'.$term_string.'"><strong>'.$term_string.'</strong></label></li>';
            $rows.='    <li id="ss'.$term_string.'" class="selectedTerms"><strong><a title="seleccionar" style="cursor: pointer">'.$term_string.'</strong></a></li>';
        }
      $rows.='</ul>';
    } 

  return array("cant"=>$data->resume->cant_result,"content"=>$rows);
};


function prepareCorpus($text,$params=array()){

  GLOBAL $CFG;

  //define limit fot size text
  $limit = (isset($params["limit"])) ? $params["limit"] : $CFG['max_text_length'];

  //prevent big text
  $limit=  ($limit<300) ? $limit : 200;

  //quitar comillas dobles y comillas simpres
  $text=str_replace(array("'",'"'),array("",''),$text);

  $text=XSSprevent($text);

  if(strlen($text)<1) return;
  
  if (str_word_count($text, 0) > $limit) {
    $words = str_word_count($text, 2);
    $pos = array_keys($words);
    $text = substr($text, 0, $pos[$limit]);
    }

  return $text;
}

?>

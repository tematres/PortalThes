<?php
header('Content-Type: text/html; charset=UTF-8');
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

/*  if($params["format"]=='text') return implode(',',$arrayKeyTerms); 
  //if($params["format"]=='text') return implode(',',$rake->sort('desc')->get()); 
  //$rake->sortByScore('desc')
  return $rake->sort('desc')->get(); 
  */
}


#preparar keyword para JS
function prepareString2Tags($array){
  $text=implode("','",$array); 

  return '\''.$text.'\'';
}




#presentación del texto analizado. Se remarcan las palabras encontradas en el vocabulario y las palabras seleccionadas
function HTMLtextDisplay($text,$params=array()){

$rows='<p>'.$text.'</p>';
$rows.='<input type="hidden" name="q" value="'.$text.'">';

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

function HTMLevalTerm($URL_BASE,$term){
#4. 1. Si hay término exaxto o UP exacto => se selecciona

  $result=false;

  $data=getTemaTresData($URL_BASE,"fetch",urlencode($term));
  if($data->resume->cant_result > 0) {
    $dataPrefTerm=getTemaTresData($URL_BASE,"fetchTerm",(int) $data->result->term->term_id);
      $rows.='<li class="success">';
      if((string) $dataPrefTerm->result->term->string==(string) $data->result->term->string){
        $rows.='<strong>'.(string) $data->result->term->string.'</strong>';
      } else{
        $rows.='<strong>'.(string) $dataPrefTerm->result->term->string.'</strong> ('.(string) $data->result->term->string.' )';
              };
      $rows.='</li>';
  }else{
      $data=getURLdata($URL_BASE.'?task=fetchSimilar&arg='.urlencode($term));
        if ($data->resume->cant_result > 0) {
        $rows.='<li>'.$term.' <i>'.LABEL_TERMINO_SUGERIDO.'</i>: <strong>'.(string) $data->result->string.'</strong>?</li>';
        };
  }  ;
  
  $cant=$data->resume->cant_result;

  if($data->resume->cant_result == 0){
     $cant=$data->resume->cant_result;
     $HTMLSearchTerm=HTMLSearchTerm($URL_BASE,$term);
        $rows.='<li>'.$term.' '.$HTMLSearchTerm["content"].'</li>';
  }

  return array("cant"=>$data->resume->cant_result,"content"=>$rows);
}



#Presentación de términos 
function HTMLSearchTerm($URL_BASE,$term){
  GLOBAL $message;  
  GLOBAL $CFG_PARAM;
  GLOBAL $URL_BASE; 

  $result=false;
    
    $rows='';
    
    $data=getURLdata($URL_BASE.'?task=search&arg='.urlencode($term));

    $i = 0;
    if ($data->resume->cant_result > 0) {

      $rows.='(<a href="#'.$term.'" data-toggle="collapse">'.$data->resume->cant_result.' '.LABEL_terms.'</a>): ';      
      $rows.='<ul id="'.$term.'" class="collapse">';      
        foreach ($data->result->term as $value) {
            $i=++$i;
            $term_id        = (int) $value->term_id;
            $term_string    = (string) $value->string;
            $no_term_string = '';
            $no_term_string = (string) $value->no_term_string;
            $rows.='    <li><span about="t'.$term_id.'" typeof="skos:Concept" >';
            $rows.= ($no_term_string != '') ? $no_term_string.' <strong><i>use</i></strong> ' : '';
            $rows.='            <a resource="'.$term_id.'" property="skos:prefLabel" href="'.$arrayVocabularyMetadata["uri"].$CFG_PARAM["fetchTerm"].$term_id.'"  title="'.$term_string.'">'.$term_string.'</a></span>
            </li>';
        }
      $rows.='</ul>';
    } 

  return array("cant"=>$data->resume->cant_result,"content"=>$rows);
};


## === funciones desechadas ===
/*
#Presentación de las palabras clave extraídas como lista
function HTMLdistilledTerms($text,$params=array()){
  $rake = RakePlus::create($text, 'es_AR','5');
  $results=$rake->sort('asc')->get(); 

  $rows='';
  $iKeyword=0 ;

  $rows.='<form name="text2parse" id="text2parse" method=post action="index.php">';
  $rows.='<ul id="distilled_keywords">';

  foreach ($results as $keywordCandidate){
    $iKeyword=++$iKeyword;
    $rows.='<li><input type="checkbox" value="'.urlencode($keywordCandidate).'" id="'.$iKeyword.'keywd"><label for="'.$iKeyword.'keywd">'.$keywordCandidate.'</label></li>';           
  }
  $rows.='</ul>';       
  $rows.='</form>';       

return $rows;
}




#Presentación de términos y los términos sugeridos
function getTemaTresTerm($data,$arrayVocabularyMetadata,$term){
  GLOBAL $message;  
  GLOBAL $CFG_PARAM;
  GLOBAL $URL_BASE; 
    
  $rows='';

    $i = 0;
    if ($data->resume->cant_result > 0) {
      $rows.='<ul id="list_search_result">';      
        foreach ($data->result->term as $value) {
            $i=++$i;
            $term_id        = (int) $value->term_id;
            $term_string    = (string) $value->string;
            $no_term_string = '';
            $no_term_string = (string) $value->no_term_string;
            $rows.='    <li>
                            <span about="t'.$term_id.'" typeof="skos:Concept" >';
            if ($no_term_string != '')
                $rows.=         $no_term_string.' <strong>use</strong> ';
            $rows.='            <a resource="'.$term_id.'" property="skos:prefLabel" href="'.$arrayVocabularyMetadata["uri"].$CFG_PARAM["fetchTerm"].$term_id.'"  title="'.$term_string.'">
                                    '.$term_string.'
                                </a>
                            </span>
                        </li>';
        }
      $rows.='</ul>';
    } else {
        //No hay resultados, buscar términos similares
        GLOBAL $URL_BASE;
        $data=getURLdata($URL_BASE.'?task=fetchSimilar&arg='.urlencode((string) $data->resume->param->arg));
        if($data->resume->cant_result > 0) {
            $rows.='<h4>'.ucfirst(LABEL_TERMINO_SUGERIDO).' <a href="'.$arrayVocabularyMetadata["uri"].'?search='.(string) $data->result->string.'" title="'.(string) $data->result->string.'">'.(string) $data->result->string.'</a>?</h4>';
        }
    }
  
return $rows;
};

#Presentación de palabras claves destiladas
function HTMLterms2select($arrayTerms){

  foreach ($arrayTerms as $keywordCandidate){
    $iKeyword=++$iKeyword;
    $rows.='<li><input type="checkbox" value="'.urlencode($keywordCandidate).'" id="'.$iKeyword.'keywd"><label for="'.$iKeyword.'keywd">'.$keywordCandidate.'</label></li>';           
  }

  

}
*/
?>

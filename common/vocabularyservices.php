<?php
if ( ! defined('WEBTHES_ABSPATH'))   die("no access");
/*
 *      vocabularyservices.php
 *
 *      Copyright 2014 diego ferreyra <tematres@r020.com.ar>
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
/* Funciones de consulta de datos */

/*  Hacer una consulta y devolver un array
*       $uri = url de servicios tematres
*       & task = consulta a realizar
*       & arg = argumentos de la consulta   */
function getURLdata($url){
    if (extension_loaded('curl')) {
        $rCURL = curl_init();
        curl_setopt($rCURL, CURLOPT_URL, $url);
        curl_setopt($rCURL, CURLOPT_HEADER, 0);
        curl_setopt($rCURL, CURLOPT_RETURNTRANSFER, 1);
        $xml = curl_exec($rCURL) or die ("Could not open a feed called: " . $url);
        curl_close($rCURL);
    } else {
        $xml=file_get_contents($url) or die ("Could not open a feed called: " . $url);
    }
    $content = new SimpleXMLElement($xml);
    return $content;
}

/*
Datos de definicion del vocabularios
* */
function getTemaTresData($tematres_uri,$task="fetchVocabularyData",$arg=""){
    if ( ! $arg) {
        return getURLdata($tematres_uri.'services.php?task=fetchVocabularyData');
    } else {
        return getURLdata($tematres_uri.'services.php?task='.$task.'&arg='.$arg);
    }
}


/*  Funciones de presentación de datos
    Recibe un objeto con las notas y lo publica como HTML  */
function data2html4Notes($data,$param=array())
{
    GLOBAL $CFG;
    $rows = '';
    if ($data->resume->cant_result > 0) {
        $i = 0;
        $rows.='<div class="well well-small" id="notabnm">';
        $i=0;
            foreach ($data->result->term as $value) {
                $i=++$i;

                    $note_type=(string) $value->note_type;
                    //note_label is one of the standard type of note
                    $note_label=(in_array($note_type,array("NA","NH","NB","NP","NC","CB"))) ? str_replace(array("NA","NH","NB","NP","NC"),array(LABEL_NA,LABEL_NH,LABEL_NB,LABEL_NC),$note_type) : $note_type;

                    //note_label is custom type of note
                    $note_label=(isset($CFG["LOCAL_NOTES"]["$note_type"])) ? $CFG["LOCAL_NOTES"]["$note_type"] : $note_type;
                    $rows.='<div rel="skos:scopeNote">';
                    $rows.='<span class="note_label">'.$note_label.':</span>';
                    $rows.='<p class="note">'.(string) $value->note_text.'</p>';
                    $rows.='</div>';
            }
        $rows.='</div>';
    }
    return $rows;
};

/*  data to letter html  */
function data2html4Letter($data,$param=array())
{
    GLOBAL $URL_BASE;
    $vocab_code=fetchVocabCode(@$param["vocab_code"]);
    $rows=' <h3>
                '.$param["div_title"].'  <i>'.$data->resume->param->arg.'</i>: '.$data->resume->cant_result.'
            </h3>';
    $i=0;
    if($data->resume->cant_result > 0) {
        $rows.='<ul>';
        foreach ($data->result->term as $value) {
            $i=++$i;
            //Controlar que no sea un resultado unico
            $rows.='<li>
                        <span about="'.redactHREF($vocab_code,"fetchTerm",$value->term_id).'" typeof="skos:Concept">';
            $rows.=(strlen($value->no_term_string)>0) ? $value->no_term_string." <i>".USE_termino."</i> " : "";
            $rows.='        <a resource="'.redactHREF($vocab_code,"fetchTerm",$value->term_id).'" property="skos:prefLabel" href="'.redactHREF($vocab_code,"fetchTerm",$value->term_id).'" title="'.FixEncoding($value->string).'">
                                '.FixEncoding($value->string).'
                            </a>
                        </span>
                    </li>';
        }
        $rows.='</ul>';
    }
    return array("task"=>"letter","results"=>$rows);
}

/* data to last terms created  */
function data2html4LastTerms($data,$param=array())
{
    GLOBAL $URL_BASE;
    
    $vocab_code=fetchVocabCode(@$param["vocab_code"]);

    $rows='<h3>'.$param["div_title"].'</h3>';
    $i = 0;
    if ($data->resume->cant_result > 0) {
        $rows.='<ul>';
        foreach ($data->result->term as $value) {
            $i=++$i;
            $term_date = do_date(($value->date_mod > $value->date_create) ? $value->date_mod : $value->date_create);
            $rows.= '<li>
                        <span about="'.redactHREF($vocab_code,"fetchTerm",$value->term_id).'" typeof="skos:Concept">';
            $rows.=         (strlen($value->no_term_string)>0) ? $value->no_term_string." ".USE_termino." " : "";
            $rows.=         '<a resource="'.redactHREF($vocab_code,"fetchTerm",$value->term_id).'" property="skos:prefLabel" href="'.redactHREF($vocab_code,"fetchTerm",$value->term_id).'" title="'.FixEncoding($value->string).'">'
                                .FixEncoding($value->string).
                            '</a>
                             ('.$term_date["dia"].'/'.$term_date["mes"].'/'.$term_date["ano"].')
                        </span>
                    </li>';
        }
    $rows.='</ul>';
    }
    return array("task"=>"fetchLast","results"=>$rows);
}

/*  Recibe un objeto con resultados de búsqueda y lo publica como HTML  */
function data2html4Search($data,$string,$param=array())
{
    GLOBAL $message, $URL_BASE;

    $vocab_code=fetchVocabCode(@$param["vocab_code"]);
    $rows=' <div>
                <h3 id="msg_search_result">
                    '.ucfirst(MSG_ResultBusca).' <i>'.(string) $data->resume->param->arg.'</i>: '.(string) $data->resume->cant_result.'
                </h3>
            </div>
            <ul id="list_search_result">';
    $i = 0;
    if ($data->resume->cant_result > 0) {
        foreach ($data->result->term as $value) {
            $i=++$i;
            $term_id        = (int) $value->term_id;
            $term_string    = (string) $value->string;
            $no_term_string = '';
            $no_term_string = (string) $value->no_term_string;
            $rows.='    <li>
                            <span about="'.redactHREF($vocab_code,"fetchTerm",$value->term_id).'" typeof="skos:Concept" >';
            if ($no_term_string != '')
                $rows.=         $no_term_string.' <strong>use</strong> ';
            $rows.='            <a resource="'.redactHREF($vocab_code,"fetchTerm",$value->term_id).'" property="skos:prefLabel" href="'.redactHREF($vocab_code,"fetchTerm",$value->term_id).'"  title="'.$term_string.'">
                                    '.$term_string.'
                                </a>
                            </span>
                        </li>';
        }
        $rows.='</ul>';
    } else {
        //No hay resultados, buscar términos similares

        $data=getURLdata($URL_BASE.'?task=fetchSimilar&arg='.urlencode((string) $data->resume->param->arg));
        if($data->resume->cant_result > 0) {
            $rows.='<h4>'.ucfirst(LABEL_TERMINO_SUGERIDO).' <a href="'.redactHREF($vocab_code,"search",(string) $data->result->string).'" title="'.(string) $data->result->string.'">'.(string) $data->result->string.'</a>?</h4>';
        }
    }
    return $rows;
}

/*  HTML details for one term  */
function data2htmlTerm($data,$param=array()){

    GLOBAL $URL_BASE, $CFG_URL_PARAM, $CFG_VOCABS ;

    $vocab_code = fetchVocabCode(@$param["vocab_code"]);
    $date_term  = ($data->result->term->date_mod) ? $data->result->term->date_mod : $data->result->term->date_create;
    $date_term  = date_create($date_term);
    $term_id    = (int) $data->result->term->tema_id;
    $term       = (string) $data->result->term->string;
    $class_term = ($data->result->term->isMetaTerm == 1) ? ' class="metaTerm" ' :'';

    $arrayRows["termdata"] = '<span '.$class_term.' id="xterm_prefLabel" property="skos:prefLabel" content="'.FixEncoding($term).'">'.FixEncoding($term).'</span> '.HTMLcopyClick($vocab_code,'xterm_prefLabel',array("isMetaTerm"=>$data->result->term->isMetaTerm,"isValidTerm"=>1,"copy_click"=>$CFG["COPY_CLICK"]));


    /*  Notas  */
    $dataNotes = getURLdata($URL_BASE.'?task=fetchNotes&arg='.$term_id);
    $arrayRows["NOTES"] = data2html4Notes($dataNotes,$param);

    $dataTE = getURLdata($URL_BASE.'?task=fetchDown&arg='.$term_id);

    if ($dataTE->resume->cant_result > 0) {
        $arrayRows["NT"]='<div><span class="label_list">'.ucfirst(TE_terminos).':</span>';
        $arrayRows["NT"].='<div id="treeTerm" data-url="'.$CFG_URL_PARAM["url_site"].'common/treedata.php?node='.$term_id.'&amp;v='.$vocab_code.'"></div></div>';
    }
    //Fetch data about associated terms (BT,RT,UF)
    $dataDirectTerms = getURLdata($URL_BASE.'?task=fetchDirectTerms&arg='.$term_id);
    $array2HTMLdirectTerms = data2html4directTerms($dataDirectTerms,array("vocab_code"=>$vocab_code));

    if ($array2HTMLdirectTerms["UFcant"] > 0) {
        
        $arrayRows["UF"]='<div id="alt_terms" class="term_relations"><span class="label_list">'.ucfirst(UP_terminos).':</span><ul class="uf_terms">'.$array2HTMLdirectTerms["UF"].'</ul></div>';
    }

    if ($array2HTMLdirectTerms["RTcant"] > 0) {        

        $arrayRows["RT"]='<div id="related_terms" class="term_relations"><span class="label_list">'.ucfirst(TR_terminos).':</span><ul class="rt_terms">'.$array2HTMLdirectTerms["RT"].'</ul></div>';

    }

    /*  fetch broader terms  */
    $dataTG = getURLdata($URL_BASE.'?task=fetchUp&arg='.$term_id);

    $arrayRows["breadcrumb"]=data2html4Breadcrumb($dataTG,array("term_id"=>$term_id,"term"=>$term),array("vocab_code"=>$vocab_code));    

    /* términos generales */
    if ($array2HTMLdirectTerms["BTcant"] > 0) {
        $arrayRows["BT"]='<div id="broader_terms" class="term_relations"><span class="label_list">'.ucfirst(TG_terminos).':</span><ul class="bt_terms">'.$array2HTMLdirectTerms["BT"].'</ul></div>';        
    }



    /* Buscar términos mapeados  */
    $dataMapped=getURLdata($URL_BASE.'?task=fetchTargetTerms&arg='.$term_id);
    if ($dataMapped->resume->cant_result > 0) {
        $arrayRows["MAP"]=data2html4MappedTerms($dataMapped,array("vocab_code"=>$vocab_code));
    }
    /*  Buscar términos linkeados // fetchURI  */
    $dataMappedURI=getURLdata($URL_BASE.'?task=fetchURI&arg='.$term_id);
    if ($dataMappedURI->resume->cant_result >"0")  {
        $arrayRows["LINKED"]=data2html4MappedURITerms($dataMappedURI,array("vocab_code"=>$vocab_code));
    }

    return array("task"=>"fetchTerm","results"=>$arrayRows,"resultData"=>array("nt"=>$dataTE,"rt"=>$dataDirectTerms,"bt"=>$dataTG));
}



function data2html4MappedTerms($data,$param=array()){
    GLOBAL $URL_BASE;
    GLOBAL $CFG_URL_PARAM;
    $vocab_code=fetchVocabCode(@$param["vocab_code"]);

    if ($data->resume->cant_result >"0") {
        $rows.='<div>';
        $rows.='        <ul>';
        foreach ($data->result->term as $value) {
            $i=++$i;
            $rows.='<li><span about="'.redactHREF($vocab_code,"fetchTerm",$value->term_id).'" typeof="skos:Concept">';
            $rows.=(string) $value->target_vocabulary_label.': <span resource="'.redactHREF($vocab_code,"fetchTerm",$value->term_id).'" property="skos:prefLabel" href="'.redactHREF($vocab_code,"fetchTerm",$value->term_id).'" title="'.(string) $value->string.'">'.(string) $value->string.'</span>';
            $rows.='</span>';
            $rows.='</li>';
        }
        $rows.='</ul>';
        $rows.='</div>';
    }
    return $rows;
}

/*  HTML details for direct terms  */
function data2html4directTerms($data,$param=array()){
    GLOBAL $URL_BASE,$CFG;

    $vocab_code=fetchVocabCode(@$param["vocab_code"]);
    $i = 0;
    $iRT = 0;
    $iBT = 0;
    $iUF = 0;
    $RT_rows = '';
    $BT_rows = '';
    $UF_rows = '';

    if ($data->resume->cant_result > "0") {
        foreach ($data->result->term as $value) {
            $i=++$i;
            $term_id=(int) $value->term_id;
            $term_string=(string) $value->string;

            if (isset($v["isMetaTerm"])) $class_dd=($v["isMetaTerm"]==1) ? 'metaTerm ' :'';

            switch ((int) $value->relation_type_id) {
                case '2':
                    $iRT=++$iRT;
                    $RT_rows.='<li class="rt_term post-tags" id="rt'.$value->term_id.'" about="'.redactHREF($vocab_code,"fetchTerm",$value->term_id).'" typeof="skos:Concept">';
                    //$RT_rows.=($value->code) ? '<span property="skos:notation">'.$value->code.'</span>' :'';
                    $RT_rows.=' <a rel="tag" href="'.redactHREF($vocab_code,"fetchTerm",$value->term_id).'" title="'.$term_string.'">'.$term_string.'</a>'.HTMLcopyClick($vocab_code,'rt'.$value->term_id,array("isMetaTerm"=>$value->term->isMetaTerm,"isValidTerm"=>1,"copy_click"=>$CFG["COPY_CLICK"])).'</li>';                    

                    break;
                case '3':
                    $iBT=++$iBT;
                    
                    $BT_rows.=' <li class="'.$class_dd.' bt_term post-tags" id="bt'.$value->term_id.'" about="'.redactHREF($vocab_code,"fetchTerm",$value->term_id).'" typeof="skos:Concept"><a rel="tag" href="'.redactHREF($vocab_code,"fetchTerm",$value->term_id).'" title="'.$term_string.'">'.$term_string.'</a>'.HTMLcopyClick($vocab_code,'bt'.$value->term_id,array("isMetaTerm"=>$value->term->isMetaTerm,"isValidTerm"=>1,"copy_click"=>$CFG["COPY_CLICK"])).'</li>';
                    break;
                case '4':
                    if ($value->relation_code !='H') {
                        $iUF=++$iUF;
                        $UF_rows.=' <li class="uf_term alt-tags" typeof="skos:altLabel" property="skos:altLabel" content="'.$term_string.'" xml:lang="'.(string) $value->lang.'">'.$term_string.'</li>';
                    }
                    break;
            }
        }
    }
    return array(   "RT"=>$RT_rows,
                    "BT"=>$BT_rows,
                    "UF"=>$UF_rows,
                    "RTcant"=>$iRT,
                    "BTcant"=>$iBT,
                    "UFcant"=>$iUF);
}

function data2html4Breadcrumb($data,$the_term=array(),$param=array()){

    GLOBAL $URL_BASE, $CFG_URL_PARAM;

    $vocab_code=fetchVocabCode(@$param["vocab_code"]);
    
    if ($data->resume->cant_result > 0){

        $rows.='<div id="term_breadcrumb">';                    
        $rows.='<span typeof="v:Breadcrumb">';
        $rows.='<a rel="v:url" property="v:title" href="'.$CFG_URL_PARAM["url_site"].'index.php?v='.$vocab_code.'" title="'.MENU_Inicio.'">'.MENU_Inicio.'</a>';
        $rows.='</span>  ';

        $i=0;

        foreach ($data->result->term as $value){
            $i=++$i;
            if((int) $value->term_id!==$the_term["term_id"])
            {
                $rows.='› <span typeof="v:Breadcrumb">';
                $rows.='<a rel="v:url" property="v:title" href="'.redactHREF($vocab_code,"fetchTerm",$value->term_id).'" title="'.(string) $value->string.'">'.(string) $value->string.'</a>';
                $rows.='</span>  ';
            } else {
                
                $rows.='› <span typeof="v:Breadcrumb">';
                $rows.=(string) $value->string;
                $rows.='</span>  ';
            }
        }

        $rows.='</div>';        
    }        else        {
        //there are only one result

        $rows.='<div id="term_breadcrumb">';                    
        $rows.='<span typeof="v:Breadcrumb">';
        $rows.='<a rel="v:url" property="v:title" href="'.$CFG_URL_PARAM["url_site"].'index.php?v='.$vocab_code.'" title="'.MENU_Inicio.'">'.MENU_Inicio.'</a>';
        $rows.='</span>  ';

        $rows.='› <span typeof="v:Breadcrumb">';
        $rows.=(string) $the_term["term"];
        $rows.='</span>  ';

        $rows.='</div>';
        }

return $rows;
}

function data2html4MappedURITerms($data,$param=array())
{
    GLOBAL $URL_BASE;

    $vocab_code=fetchVocabCode(@$param["vocab_code"]);
    $rows.='<div>';
    if($data->resume->cant_result > 0) {
        $rows.='<ul>';
        foreach ($data->result->term as $value) {
            $rows.='<li><span about="'.redactHREF($vocab_code,"fetchTerm",$value->term_id).'" typeof="skos:Concept">';
            $rows.=(string) $value->link_type.': <a resource="'.(string) $value->link.'" property="skos:'.(string) $value->link_type.'" href="'.(string) $value->link.'" title="'.(string) $value->link_type.' '.(string) $value->link.'">'.(string) $value->link.'</a>';
            $rows.='</span>';
            $rows.='</li>';
        }
        $rows.='</ul>';
    }
    $rows.='</div>';
    return $rows;
};



function data2html4TopTerms($data,$param=array()){
    GLOBAL $URL_BASE;
    $vocab_code=fetchVocabCode(@$param["vocab_code"]);
    if($data->resume->cant_result > 0) {
        $rows.='<div>
        <ul class="topterms">';
        foreach ($data->result->term as $value) {
            $term_id=(int) $value->term_id;
            $term_string=(string) $value->string;
            $class_li=($value->isMetaTerm==1) ? ' class="metaTerm" ' :'';
            $rows.='<li '.$class_li.' about="'.redactHREF($vocab_code,"fetchTerm",$value->term_id).'" typeof="skos:Concept">';
            $rows.=($value->code) ? '<span property="skos:notation">'.$value->code.'</span> ' :'';
            $rows.='<a resource="'.redactHREF($vocab_code,"fetchTerm",$value->term_id).'" property="skos:hasTopConcept" href="'.redactHREF($vocab_code,"fetchTerm",$value->term_id).'" title="'.$term_string.'">'.$term_string.'</a>';
            $rows.='</li>';
        }
        $rows.='</ul>';
        $rows.='</div>';
    }
    return $rows;
}

//lista alfabética
function HTMLalphaNav($arrayLetras=array(),$param=array(),$select_letra=""){
    GLOBAL $URL_BASE;

    $vocab_code=fetchVocabCode(@$param["vocab_code"]);
    $rows='    <ul class="nav nav-alpha nav-pills">';
    foreach ($arrayLetras as $letra) {
        $class=($select_letra==$letra) ? 'active' : '';
        $rows.='    <li class="'.$class.'">
                        <a href="'.redactHREF($vocab_code,"letter",strtoupper($letra)).'">
                            '.strtoupper($letra).'
                        </a>
                    </li>';
    }
    $rows.='    </ul>';
    return $rows;
}

/*  Armado de salida RSS  */
function fetchRSS($URL_BASE,$param=array())
{
    GLOBAL $CFG_URL_PARAM;
    $vocabularyMetadata=fetchVocabularyMetadata($URL_BASE) ;
    $data=getURLdata($URL_BASE.'?task=fetchLast');
    $vocab_code=fetchVocabCode(@$param["vocab_code"]);
    if ($data->resume->cant_result > 0) {
        foreach ($data->result->term as $value) {
            $term_id=(int) $value->term_id;
            $term_string=(string) $value->string;
            $term_date=($value->date_mod >0) ? $value->date_mod :  $value->date_create ;
            $xml_seq.='<li xmlns:dc="http://purl.org/dc/elements/1.1/" rdf:resource="'.$CFG_URL_PARAM["url_site"].$CFG_URL_PARAM["fetchTerm"].$term_id.'&amp;v='.$vocab_code.'"/>';
            $xml_item.='<item xmlns:dc="http://purl.org/dc/elements/1.1/" rdf:about="'.$CFG_URL_PARAM["url_site"].$CFG_URL_PARAM["fetchTerm"].$term_id.'&amp;v='.$vocab_code.'">';
            $xml_item.='<title>'.$term_string.'</title>';
            $xml_item.='<date xmlns:dc="http://purl.org/dc/elements/1.1/">'.$term_date.'</date>';
            $xml_item.='<link>'.$CFG_URL_PARAM["url_site"].$CFG_URL_PARAM["fetchTerm"].$term_id.'&amp;v='.$vocab_code.'</link>';
            $xml_item.='</item>';
        }
    }
    header ('content-type: text/xml');
    $xml.='<?xml version="1.0" encoding="utf8" standalone="yes"?>';
    $xml.='<rdf:RDF xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#" xmlns="http://purl.org/rss/1.0/" xmlns:dc="http://purl.org/dc/elements/1.1/">';
    $xml.='<channel rdf:about="'.$CFG_URL_PARAM["url_site"].'index.php?v='.$vocab_code.'">';
    $xml.='<title xmlns:dc="http://purl.org/dc/elements/1.1/">'.xmlentities($vocabularyMetadata["title"]).'</title>';
    $xml.='<creator xmlns:dc="http://purl.org/dc/elements/1.1/">'.xmlentities($vocabularyMetadata["author"]).'</creator>';
    $xml.='<description xmlns:dc="http://purl.org/dc/elements/1.1/">'.xmlentities($vocabularyMetadata["author"]).'. '.xmlentities($vocabularyMetadata["scope"],true).'</description>';
    $xml.='<link xmlns:dc="http://purl.org/dc/elements/1.1/">'.$CFG_URL_PARAM["url_site"].'?v='.$vocab_code.'</link>';
    $xml.='<items>';
    $xml.='<rdf:Seq>';
    $xml.=$xml_seq;
    $xml.='</rdf:Seq>';
    $xml.='</items>';
    $xml.='</channel>';
    $xml.=$xml_item;
    $xml.='</rdf:RDF>';
    echo $xml;
}

/*  fetch vocabulary metadata  */
function fetchVocabularyMetadata($url){
    $data=getURLdata($url.'?task=fetchVocabularyData');
    if (is_object($data)) {

        $array["title"]=        (string) $data->result->title;
        $array["author"]=       (string) $data->result->author;
        $array["lang"]=         (string) $data->result->lang;
        $array["scope"]=        (string) $data->result->scope;
        $array["keywords"]=     (string) $data->result->keywords;
        $array["lastMod"]=      (string) $data->result->lastMod;
        $array["uri"]=          (string) $data->result->uri;
        $array["contributor"]=  (string) $data->result->contributor;
        $array["publisher"]=    (string)$data->result->publisher;
        $array["rights"]=       (string) $data->result->rights;
        $array["createDate"]=   (string) $data->result->createDate;
        $array["cant_terms"]=   (int) $data->result->cant_terms;
        $array["adminEmail"]=   (string) $data->result->adminEmail;
        return $array;
    } else { $array=array();}   
}

/*  Funciones generales  */
// string 2 URL legible
// based on source from http://code.google.com/p/pan-fr/
function string2url ($string)
{
    $string = strtr($string,
    "�������������������������������������������������������",
    "AAAAAAaaaaaaCcOOOOOOooooooEEEEeeeeIIIIiiiiUUUUuuuuYYyyNn");
    $string = str_replace('�','AE',$string);
    $string = str_replace('�','ae',$string);
    $string = str_replace('�','OE',$string);
    $string = str_replace('�','oe',$string);
    $string = preg_replace('/[^a-z0-9_\s\'\:\/\[\]-]/','',strtolower($string));
    $string = preg_replace('/[\s\'\:\/\[\]-]+/',' ',trim($string));
    $res = str_replace(' ','-',$string);
    return $res;
}

//form http://www.compuglobalhipermega.net/php/php-url-semantica/
function is_utf ($t)
{
    if (@preg_match ('/.+/u', $t))
        return 1;
}

/* Banco de vocabularios 2013 */
// XML Entity Mandatory Escape Characters or CDATA
function xmlentities ($string, $pcdata=FALSE)
{
    if($pcdata == TRUE) {
        return  '<![CDATA[ '.str_replace ( array ('[[',']]' ), array ('',''), $string ).' ]]>';
    } else {
        return str_replace ( array ( '&', '"', "'", '<', '>','[[',']]' ), array ( '&amp;' , '&quot;', '&apos;' , '&lt;' , '&gt;','',''), $string );
    }
}

function fixEncoding($input, $output_encoding="UTF-8")
{
    return $input;
    // For some reason this is missing in the php4 in NMT
    $encoding = mb_detect_encoding($input);
    switch($encoding) {
        case 'ASCII':
        case $output_encoding:
            return $input;
        case '':
            return mb_convert_encoding($input, $output_encoding);
        default:
            return mb_convert_encoding($input, $output_encoding, $encoding);
    }
}

/**
 * Checks to see if a string is utf8 encoded.
 *
 * NOTE: This function checks for 5-Byte sequences, UTF8
 *       has Bytes Sequences with a maximum length of 4.
 *
 * @author bmorel at ssi dot fr (modified)
 * @since 1.2.1
 *
 * @param string $str The string to be checked
 * @return bool True if $str fits a UTF-8 model, false otherwise.
 * From WordPress
 */
function seems_utf8($str)
{
    $length = strlen($str);
    for ($i=0; $i < $length; $i++) {
        $c = ord($str[$i]);
        if ($c < 0x80) $n = 0; # 0bbbbbbb
        elseif (($c & 0xE0) == 0xC0) $n=1; # 110bbbbb
        elseif (($c & 0xF0) == 0xE0) $n=2; # 1110bbbb
        elseif (($c & 0xF8) == 0xF0) $n=3; # 11110bbb
        elseif (($c & 0xFC) == 0xF8) $n=4; # 111110bb
        elseif (($c & 0xFE) == 0xFC) $n=5; # 1111110b
        else return false; # Does not match any model
        for ($j=0; $j<$n; $j++) { # n bytes matching 10bbbbbb follow ?
            if ((++$i == $length) || ((ord($str[$i]) & 0xC0) != 0x80))
                return false;
        }
    }
    return true;
}

/*  Convierte una cadena a latin1
    http://gmt-4.blogspot.com/2008/04/conversion-de-unicode-y-latin1-en-php-5.html  */
function latin1($txt)
{
    $encoding = mb_detect_encoding($txt, 'ASCII,UTF-8,ISO-8859-1');
    if ($encoding == "UTF-8") {
        $txt = utf8_decode($txt);
    }
    return $txt;
}

/*  Convierte una cadena a utf8
    http://gmt-4.blogspot.com/2008/04/conversion-de-unicode-y-latin1-en-php-5.html  */
function utf8($txt)
{
    $encoding = mb_detect_encoding($txt, 'ASCII,UTF-8,ISO-8859-1');
    if ($encoding == "ISO-8859-1") {
        $txt = utf8_encode($txt);
    }
    return $txt;
}


function XSSprevent($string){
$string = str_replace ( array ('<',">","&",'"' ), array ('','','',''), $string );

//$string=htmlentities($string, ENT_QUOTES | ENT_HTML5, 'UTF-8');

require_once 'htmlpurifier/HTMLPurifier.auto.php';
    $config = HTMLPurifier_Config::createDefault();
  //$config->set('HTML.Allowed', '');
    $purifier = new HTMLPurifier($config);
    $clean_string = $purifier->purify($string);

    return $clean_string;

}



function sendMail($to_address,$subject,$message,$extra=array())
{
    require_once("mailer/class.phpmailer.php");
    $mail = new PHPMailer();
    $mail->IsSMTP();                                      // set mailer to use SMTP
    //$mail->Host = 'ssl://smtp.gmail.com';
    //$mail->Port = 465;
    $mail->SMTPAuth = false;
    //$mail->Username = 'username';
    //$mail->Password = 'password';
    $mail->From = $extra["from"];
    $mail->CharSet = "UTF-8";
    $mail->AddAddress($to_address);
    $mail->WordWrap = 150;                                 // set word wrap to 50 characters
    $mail->IsHTML(false);                                  // set email format to HTML
    $mail->Subject = $subject;
    $mail->Body    = $message;
    $mail->SMTPDebug  = 0;
    /*  Debug  */
    // error_reporting(E_ALL);
    // ini_set("display_errors", 1);
    // echo $mail->ErrorInfo.$to_address.$subject.$message;
    return ($mail->Send()) ? true  : $mail->ErrorInfo;
}

/*  Arma un array con una fecha  */
function do_date($time)
{
    $array=array(
        'min'  => date("i",strtotime($time)),
        'hora' => date("G",strtotime($time)),
        'dia'  => date("d",strtotime($time)),
        'mes'  => date("m",strtotime($time)),
        'ano'  => date("Y",strtotime($time))
    );
    return $array;
}



function redactHREF($v,$task,$arg,$extra=array()){

    GLOBAL $CFG_VOCABS,$CFG,$CFG_URL_PARAM;

    $v=(is_array($CFG_VOCABS[$v])) ? $v : $CFG["DEFVOCAB"];

    $task=(in_array($task,array('fetchTerm','search','letter','last'))) ? $task : 'last' ;

    return $CFG_URL_PARAM["url_site"].$CFG_URL_PARAM["v"].$v.$CFG_URL_PARAM[$task].$arg;
}


/*Check for values and not null in a variable*/
function configValue($value,$default=false,$defaultValues=array()){


    if(strlen($value)<1) return $default;

    //si es que ser uno de una lista de valores
    if(count($defaultValues)>0){
        if(!in_array($value,$defaultValues)) return $default;        
    }

    return $value;

}
?>

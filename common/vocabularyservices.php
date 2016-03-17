<?php

if ( ! defined('WEBTHES_ABSPATH'))
    die("no access");
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
function getURLdata($url)
{
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

function getURLdata2($url)
{
    if (extension_loaded('curl')) {
        $rCURL = curl_init();
        curl_setopt($rCURL, CURLOPT_URL, $url);
        curl_setopt($rCURL, CURLOPT_HEADER, 0);
        curl_setopt($rCURL, CURLOPT_RETURNTRANSFER, 1);
        $xml = curl_exec($rCURL);
    } else {
        $xml=file_get_contents($url);
    }
    if ( ! $xml) {
        return false;
    }
    return new SimpleXMLElement($xml);
}

/*  Funciones de presentación de datos
    Recibe un objeto con las notas y lo publica como HTML  */
function data2html4Notes($data,$param=array())
{
    GLOBAL $CFG;
    $rows = '';
    if ($data->resume->cant_result > 0) {
        $i = 0;
        $haynota = 0;
        foreach ($data->result->term as $value) {
            $i=++$i;
            if ($value->note_type == "NA" || $value->note_type == "NH" || $value->note_type == "CB") {
                $haynota=1;
            }
        }
        if ($haynota == 1) {
            $rows.='<div class="well well-small" id="notabnm">';
            $i=0;
            foreach ($data->result->term as $value) {
                $i=++$i;
                if ($value->note_type == "NA" || $value->note_type == "NH" || $value->note_type == "CB" || ($value->note_type == "NB" && $param["vocab_code"] != "VEA")) {
                    $note_label=(in_array((string) $value->note_type,array("NA","NH","NB","NP","NC","CB","DEF"))) ? str_replace(array("NA","NH","NB","NP","NC","CB","DEF"),array(LABEL_NA,LABEL_NH,LABEL_NB,LABEL_NP,LABEL_NC,"Nota bibliográfica",$CFG["LOCAL_NOTES"]["DEF"]),(string) $value->note_type) : (string) $value->note_type;
                    $rows.='<div rel="skos:scopeNote">';
                    $rows.='<span class="note_label">'.$note_label.':</span>';
                    $rows.='<p class="note">'.(string) $value->note_text.'</p>';
                    $rows.='</div>';
                }
            }
            $rows.='</div>';
        }
    }
    return $rows;
};

/*  data to letter html  */
function data2html4Letter($data,$param=array())
{
    GLOBAL $URL_BASE, $PHP_SELF;
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
                        <span about="'.$URL_BASE.'?task=fetchtTerm&amp;arg='.$value->term_id.'&amp;v='.$vocab_code.'" typeof="skos:Concept">';
            $rows.=(strlen($value->no_term_string)>0) ? $value->no_term_string." ".USE_termino." " : "";
            $rows.='        <a resource="'.$URL_BASE.'?task=fetchtTerm&amp;arg='.$value->term_id.'&amp;v='.$vocab_code.'" property="skos:prefLabel" href="'.$PHP_SELF.'?task=fetchTerm&amp;arg='.$value->term_id.'&amp;v='.$vocab_code.'" title="'.FixEncoding($value->string).'">
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
    GLOBAL $URL_BASE,$PHP_SELF;
    $vocab_code=fetchVocabCode(@$param["vocab_code"]);
    $rows='<h3>'.$param["div_title"].'</h3>';
    $i = 0;
    if ($data->resume->cant_result > 0) {
        $rows.='<ul>';
        foreach ($data->result->term as $value) {
            $i=++$i;
            $term_date = do_date(($value->date_mod > $value->date_create) ? $value->date_mod : $value->date_create);
            $rows.= '<li>
                        <span about="'.$URL_BASE.'?task=fetch tTerm&amp;arg='.$value->term_id.'&amp;v='.$vocab_code.'" typeof="skos:Concept">';
            $rows.=         (strlen($value->no_term_string)>0) ? $value->no_term_string." ".USE_termino." " : "";
            $rows.=         '<a resource="'.$URL_BASE.'?task=fetchtTerm&amp;arg='.$value->term_id.'&amp;v='.$vocab_code.'" property="skos:prefLabel" href="'.$PHP_SELF.'?task=fetchTerm&amp;arg='.$value->term_id.'&amp;v='.$vocab_code.'" title="'.FixEncoding($value->string).'">'
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
    GLOBAL $message;
    GLOBAL $CFG_URL_PARAM;
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
                            <span about="'.$CFG_URL_PARAM["url_site"].$CFG_URL_PARAM["fetchTerm"].$term_id.'&amp;v='.$vocab_code.'" typeof="skos:Concept" >';
            if ($no_term_string != '')
                $rows.=         $no_term_string.' <strong>use</strong> ';
            $rows.='            <a resource="'.$CFG_URL_PARAM["url_site"].$CFG_URL_PARAM["fetchTerm"].$term_id.'&amp;v='.$vocab_code.'" property="skos:prefLabel" href="'.$CFG_URL_PARAM["url_site"].$CFG_URL_PARAM["fetchTerm"].$term_id.'&amp;v='.$vocab_code.'"  title="'.$term_string.'">
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
            $rows.='<h4>'.ucfirst(LABEL_TERMINO_SUGERIDO).' <a href="'.$CFG_URL_PARAM["url_site"].$CFG_URL_PARAM["search"].(string) $data->result->string.'&amp;v='.$vocab_code.'" title="'.(string) $data->result->string.'">'.(string) $data->result->string.'</a>?</h4>';
        }
    }
    return $rows;
}

/*  HTML details for one term  */
function data2htmlTerm($data,$param=array())
{
    GLOBAL $URL_BASE, $CFG_URL_PARAM, $CFG_VOCABS, $array, $array2HTMLdirectTerms;
    $vocab_code = fetchVocabCode(@$param["vocab_code"]);
    $date_term  = ($data->result->term->date_mod) ? $data->result->term->date_mod : $data->result->term->date_create;
    $date_term  = date_create($date_term);
    $term_id    = (int) $data->result->term->tema_id;
    $term       = (string) $data->result->term->string;
    $class_term = ($data->result->term->isMetaTerm == 1) ? ' class="metaTerm" ' :'';
    /*  fetch broader terms  */
    $dataTG     = getURLdata($URL_BASE.'?task=fetchUp&arg='.$term_id);
    $arrayRows["termdata"] = '<span '.$class_term.' id="term_prefLabel" property="skos:prefLabel" content="'.FixEncoding($array["result"]["term"]["string"]).'">'.FixEncoding($term).'</span>';
    /*  Notas  */
    $dataNotes = getURLdata($URL_BASE.'?task=fetchNotes&arg='.$term_id);
    $arrayRows["NOTES"] = data2html4Notes($dataNotes,$param);
    $dataTE = getURLdata($URL_BASE.'?task=fetchDown&arg='.$term_id);
    if ($dataTE->resume->cant_result > 0) {
        // <dt>'.ucfirst(TE_terminos).':</dt>
        $arrayRows["NT"]='  <dd>
                                <div id="treeTerm" data-url="'.$CFG_URL_PARAM["url_site"].'common/treedata.php?node='.$term_id.'&amp;v='.$vocab_code.'">
                                </div>
                            </dd>';
    }
    //Fetch data about associated terms (BT,RT,UF)
    $dataDirectTerms = getURLdata($URL_BASE.'?task=fetchDirectTerms&arg='.$term_id);
    $array2HTMLdirectTerms = data2html4directTerms($dataDirectTerms,array("vocab_code"=>$vocab_code));
    if ($array2HTMLdirectTerms["UFcant"] > 0) {
        // $arrayRows["UF"].='<dt>'.ucfirst(UP_terminos).':</dt>';
        $arrayRows["UF"]=$array2HTMLdirectTerms["UF"];
    }
    if ($array2HTMLdirectTerms["RTcant"] > 0) {
        // $arrayRows["RT"].='<dt class="relation translation panel">'.ucfirst(TR_terminos).'</dt>';
        $arrayRows["RT"]=$array2HTMLdirectTerms["RT"];
    }
    /* el breadcrumb se visualiza si tiene un solo término general; si no, se ven los términos generales */
    if ($array2HTMLdirectTerms["BTcant"] == 1) {
        if ($dataTG->resume->cant_result > 0) {
            $arrayTG["term"]["string"]=$term;
            $arrayRows["breadcrumb"]=data2html4Breadcrumb($dataTG,$term_id,array("vocab_code"=>$vocab_code));
        }
    }
    /* si tiene más de un término general, se visualizan; si no, está el breadcrumb */
    if ($array2HTMLdirectTerms["BTcant"] > 1) {
        $arrayRows["BT"].='<dt>'.ucfirst(TG_terminos).':</dt>';
        $arrayRows["BT"].=$array2HTMLdirectTerms["BT"];
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

    return array("task"=>"fetchTerm","results"=>$arrayRows);
}

function data2html4MappedTerms($data,$param=array())
{
    GLOBAL $URL_BASE;
    GLOBAL $CFG_URL_PARAM;
    $vocab_code=fetchVocabCode(@$param["vocab_code"]);

    if ($data->resume->cant_result >"0") {
        $rows.='<div>';
        $rows.='        <ul>';
        foreach ($data->result->term as $value) {
            $i=++$i;
            $rows.='<li><span about="'.$CFG_URL_PARAM["url_site"].$CFG_URL_PARAM["fetchTerm"].(int) $value->term_id.'&amp;v='.$vocab_code.'" typeof="skos:Concept">';
            $rows.=(string) $value->target_vocabulary_label.': <span resource="'.$CFG_URL_PARAM["url_site"].$CFG_URL_PARAM["fetchTerm"].(int) $value->term_id.'&amp;v='.$vocab_code.'" property="skos:prefLabel" href="'.$CFG_URL_PARAM["url_site"].$CFG_URL_PARAM["fetchTerm"].(int) $value->term_id.'&amp;v='.$vocab_code.'" title="'.(string) $value->string.'">'.(string) $value->string.'</span>';
            $rows.='</span>';
            $rows.='</li>';
        }
        $rows.='</ul>';
        $rows.='</div>';
    }
    return $rows;
}

/*  HTML details for direct terms  */
function data2html4directTerms($data,$param=array())
{
    GLOBAL $URL_BASE, $CFG_URL_PARAM, $class_dd, $BT_rows, $RT_rows, $UF_rows;
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
            switch ((int) $value->relation_type_id) {
                case '2':
                    $iRT=++$iRT;
                    $RT_rows.='<dd id="relac" about="'.$CFG_URL_PARAM["url_site"].$CFG_URL_PARAM["fetchTerm"].$term_id.'&amp;v='.$vocab_code.'" typeof="skos:Concept">';
                    $RT_rows.=($value->code) ? '<span property="skos:notation">'.$value->code.'</span>' :'';
                    $RT_rows.=' <a rel="skos:related"
                    href="'.$CFG_URL_PARAM["url_site"].$CFG_URL_PARAM["fetchTerm"].$term_id.'&amp;v='.$vocab_code.'"
                    title="'.$term_string.'">'.$term_string.'</a>';
                    $RT_rows.='</dd>';
                    break;
                case '3':
                    $iBT=++$iBT;
                    if (isset($v["isMetaTerm"]))
                        $class_dd=($v["isMetaTerm"]==1) ? ' class="metaTerm" ' :'';
                    $BT_rows.=' <dd '.$class_dd.' about="'.$CFG_URL_PARAM["url_site"].$CFG_URL_PARAM["fetchTerm"].$term_id.'&amp;v='.$vocab_code.'" typeof="skos:Concept">
                                    <a rel="skos:broather" href="'.$CFG_URL_PARAM["url_site"].$CFG_URL_PARAM["fetchTerm"].$term_id.'&amp;v='.$vocab_code.'"
                    title="'.$term_string.'">'
                                        .$term_string.
                                    '</a>
                                </dd>';
                    break;
                case '4':
                    if ($value->relation_code != H) {
                        $iUF=++$iUF;
                        $UF_rows.=' <dd
                                        id="nopref"
                                        typeof="skos:altLabel"
                                        property="skos:altLabel"
                                        content="'.$term_string.'"
                                        xml:lang="'.(string) $value->lang.'">
                                        <i class="fa fa-ban"></i> '
                                        .$term_string.'
                                    </dd>';
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

function data2html4Breadcrumb($data,$tema_id="0",$param=array()){

    GLOBAL $URL_BASE;
    GLOBAL $CFG_URL_PARAM;

    $vocab_code=fetchVocabCode(@$param["vocab_code"]);

    $tema_id = (int) $tema_id;
    
    if ($data->resume->cant_result > 0)
    {
    
        $rows.='<div id="term_breadcrumb">';                    
        $rows.='<span typeof="v:Breadcrumb">';
        $rows.='<a rel="v:url" property="v:title" href="'.$CFG_URL_PARAM["url_site"].'index.php?v='.$vocab_code.'" title="'.MENU_Inicio.'">'.MENU_Inicio.'</a>';
        $rows.='</span>  ';

        $i=0;

        foreach ($data->result->term as $value){
            $i=++$i;
            if((int) $value->term_id!==$tema_id)
            {
                $rows.='› <span typeof="v:Breadcrumb">';
                $rows.='<a rel="v:url" property="v:title" href="'.$CFG_URL_PARAM["url_site"].$CFG_URL_PARAM["fetchTerm"].(int) $value->term_id.'&amp;v='.$vocab_code.'" title="'.(string) $value->string.'">'.(string) $value->string.'</a>';
                $rows.='</span>  ';
            }
            else
            {
                
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
        $rows.=(string) $data->term->string;
        $rows.='</span>  ';

        $rows.='</div>';

        }

return $rows;
}

function data2html4MappedURITerms($data,$param=array())
{
    GLOBAL $URL_BASE;
    GLOBAL $CFG_URL_PARAM;
    $vocab_code=fetchVocabCode(@$param["vocab_code"]);
    $rows.='<div>';
    if($data->resume->cant_result > 0) {
        $rows.='<ul>';
        foreach ($data->result->term as $value) {
            $rows.='<li><span about="'.$URL_BASE.'?task=fetchtTerm&amp;arg='.$value->term_id.'&amp;v='.$vocab_code.'" typeof="skos:Concept">';
            $rows.=(string) $value->link_type.': <a resource="'.(string) $value->link.'" property="skos:'.(string) $value->link_type.'" href="'.(string) $value->link.'" title="'.(string) $value->link_type.' '.(string) $value->link.'">'.(string) $value->link.'</a>';
            $rows.='</span>';
            $rows.='</li>';
        }
        $rows.='</ul>';
    }
    $rows.='</div>';
    return $rows;
};

function data2html4TopTerms($data,$param=array())
{
    GLOBAL $CFG_URL_PARAM;
    GLOBAL $URL_BASE;
    $vocab_code=fetchVocabCode(@$param["vocab_code"]);
    if($data->resume->cant_result > 0) {
        $rows.='<div>
        <ul class="topterms">';
        foreach ($data->result->term as $value) {
            $term_id=(int) $value->term_id;
            $term_string=(string) $value->string;
            $class_li=($value->isMetaTerm==1) ? ' class="metaTerm" ' :'';
            $rows.='<li '.$class_li.' about="'.$CFG_URL_PARAM["url_site"].$CFG_URL_PARAM["fetchTerm"].$term_id.'&amp;v='.$vocab_code.'" typeof="skos:Concept">';
            $rows.=($value->code) ? '<span property="skos:notation">'.$value->code.'</span> ' :'';
            $rows.='<a resource="'.$CFG_URL_PARAM["url_site"].$CFG_URL_PARAM["fetchTerm"].$term_id.'&amp;v='.$vocab_code.'" property="skos:hasTopConcept" href="'.$CFG_URL_PARAM["url_site"].$CFG_URL_PARAM["fetchTerm"].$term_id.'&amp;v='.$vocab_code.'" title="'.$term_string.'">'.$term_string.'</a>';
            $rows.='</li>';
        }
        $rows.='</ul>';
        $rows.='</div>';
    }
    return $rows;
}

//lista alfabética
function HTMLalphaNav($arrayLetras=array(),$param=array(),$select_letra="")
{
    GLOBAL $URL_BASE, $CFG_URL_PARAM;
    $vocab_code=fetchVocabCode(@$param["vocab_code"]);
    $rows='    <ul class="nav nav-alpha nav-pills">';
    foreach ($arrayLetras as $letra) {
        $class=($select_letra==$letra) ? 'active' : '';
        $rows.='    <li class="'.$class.'">
                        <a href="'.$CFG_URL_PARAM["url_site"].'?task=letter&amp;arg='.strtoupper($letra).'&amp;v='.$vocab_code.'">
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
function fetchVocabularyMetadata($url)
{
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
    } else {
        $array=array();
    }
    return $array;
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

function clean($val)
{
    // remove all non-printable characters. CR(0a) and LF(0b) and TAB(9) are allowed
    // this prevents some character re-spacing such as <java\0script>
    // note that you have to handle splits with \n, \r, and \t later since they *are* allowed in some inputs
    $val = preg_replace('/([\x00-\x08][\x0b-\x0c][\x0e-\x20])/', '', $val);
    // straight replacements, the user should never need these since they're normal characters
    // this prevents like <IMG SRC=&#X40&#X61&#X76&#X61&#X73&#X63&#X72&#X69&#X70&#X74&#X3A&#X61&#X6C&#X65&#X72&#X74&#X28&#X27&#X58&#X53&#X53&#X27&#X29>
    $search = 'abcdefghijklmnopqrstuvwxyz';
    $search .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $search .= '1234567890!@#$%^&*()';
    $search .= '~`";:?+/={}[]-_|\'\\';
    for ($i = 0; $i < strlen($search); $i++) {
        // ;? matches the ;, which is optional
        // 0{0,7} matches any padded zeros, which are optional and go up to 8 chars
        // &#x0040 @ search for the hex values
        $val = preg_replace('/(&#[x|X]0{0,8}'.dechex(ord($search[$i])).';?)/i', $search[$i], $val); // with a ;
        // &#00064 @ 0{0,7} matches '0' zero to seven times
        $val = preg_replace('/(&#0{0,8}'.ord($search[$i]).';?)/', $search[$i], $val); // with a ;
    }
    // now the only remaining whitespace attacks are \t, \n, and \r
    $ra1 = Array('javascript', 'vbscript', 'expression', 'applet', 'meta', 'xml', 'blink', 'link', 'style', 'script', 'embed', 'object', 'iframe', 'frame', 'frameset', 'ilayer', 'layer', 'bgsound', 'title', 'base');
    $ra2 = Array('onabort', 'onactivate', 'onafterprint', 'onafterupdate', 'onbeforeactivate', 'onbeforecopy', 'onbeforecut', 'onbeforedeactivate', 'onbeforeeditfocus', 'onbeforepaste', 'onbeforeprint', 'onbeforeunload', 'onbeforeupdate', 'onblur', 'onbounce', 'oncellchange', 'onchange', 'onclick', 'oncontextmenu', 'oncontrolselect', 'oncopy', 'oncut', 'ondataavailable', 'ondatasetchanged', 'ondatasetcomplete', 'ondblclick', 'ondeactivate', 'ondrag', 'ondragend', 'ondragenter', 'ondragleave', 'ondragover', 'ondragstart', 'ondrop', 'onerror', 'onerrorupdate', 'onfilterchange', 'onfinish', 'onfocus', 'onfocusin', 'onfocusout', 'onhelp', 'onkeydown', 'onkeypress', 'onkeyup', 'onlayoutcomplete', 'onload', 'onlosecapture', 'onmousedown', 'onmouseenter', 'onmouseleave', 'onmousemove', 'onmouseout', 'onmouseover', 'onmouseup', 'onmousewheel', 'onmove', 'onmoveend', 'onmovestart', 'onpaste', 'onpropertychange', 'onreadystatechange', 'onreset', 'onresize', 'onresizeend', 'onresizestart', 'onrowenter', 'onrowexit', 'onrowsdelete', 'onrowsinserted', 'onscroll', 'onselect', 'onselectionchange', 'onselectstart', 'onstart', 'onstop', 'onsubmit', 'onunload');
    $ra = array_merge($ra1, $ra2);
    $found = true; // keep replacing as long as the previous round replaced something
    while ($found == true) {
        $val_before = $val;
        for ($i = 0; $i < sizeof($ra); $i++) {
            $pattern = '/';
            for ($j = 0; $j < strlen($ra[$i]); $j++) {
                if ($j > 0) {
                    $pattern .= '(';
                    $pattern .= '(&#[x|X]0{0,8}([9][a][b]);?)?';
                    $pattern .= '|(&#0{0,8}([9][10][13]);?)?';
                    $pattern .= ')?';
                }
                $pattern .= $ra[$i][$j];
            }
            $pattern .= '/i';
            $replacement = substr($ra[$i], 0, 2).'<x>'.substr($ra[$i], 2); // add in <> to nerf the tag
            $val = preg_replace($pattern, $replacement, $val); // filter out the hex tags
            if ($val_before == $val) {
                // no replacements were made, so exit the loop
                $found = false;
            }
        }
    }
   return $val;
}

function XSSprevent($string)
{
    require_once 'htmlpurifier/HTMLPurifier.auto.php';
    $config = HTMLPurifier_Config::createDefault();
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

?>
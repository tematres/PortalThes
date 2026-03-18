<?php
if (! defined('WEBTHES_ABSPATH')) {
    die("no access");
}
    /*
     *      portalthes.php
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
function HTMLestilosyjs($v="")
{
    global $CFG_URL_PARAM;

    $rows='
    <!-- js -->
    <script src="'.$CFG_URL_PARAM["url_site"].'js/jquery-3.7.1.min.js"></script>
    <script src="'.$CFG_URL_PARAM["url_site"].'js/jquery-ui-1.14.1/jquery-ui.js"></script>
    <script src="'.$CFG_URL_PARAM["url_site"].'js/vis-network.min.js"></script>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">



    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-mockjax/2.6.1/jquery.mockjax.min.js"></script>
    <script src="'.$CFG_URL_PARAM["url_site"].'vendor/jquery.validate.min.js"></script>
    <script src="'.$CFG_URL_PARAM["url_site"].'vendor/masonry.pkgd.min.js"></script>
    <script src="'.$CFG_URL_PARAM["url_site"].'vendor/imagesloaded.pkgd.min.js"></script>
    <script src="'.$CFG_URL_PARAM["url_site"].'js/tree.jquery.js"></script>
    <script src="'.$CFG_URL_PARAM["url_site"].'js/js.php?v='.$_SESSION["v"].'"></script>
    <script type="text/javascript" src="'.$CFG_URL_PARAM["url_site"].'js/clipboard.min.js"></script>
    <!-- css -->
    <link type="image/x-icon" href="'.$CFG_URL_PARAM["url_site"].'css/favicon.ico" rel="icon" />

    <link rel="stylesheet" href="'.$CFG_URL_PARAM["url_site"].'css/jqtree.postcss">';

    
    $rows.='<link rel="stylesheet" href="'.$CFG_URL_PARAM["url_site"].'css/thes3.css">';
    $rows.='<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans|Syncopate">';
    $rows.='<link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">';
    $rows.='<link rel="stylesheet" href="'.$CFG_URL_PARAM["url_site"].'js/jquery-ui-1.14.1/jquery-ui.css">';
    $rows.='<link rel="stylesheet" href="'.$CFG_URL_PARAM["url_site"].'css/modal-notes.css">';

    $rows.='<link rel="alternate" href="'.$CFG_URL_PARAM["url_site"].'rss.php?v='.$_SESSION["v"].'" title="RSS '.$_SESSION["vocab"]["title"].'" />';

    $rows.='<link rel="preconnect" href="https://fonts.googleapis.com">';
    $rows.='<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>';
    $rows.='<link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">';
    return $rows;
}

function HTMLmeta($vocabularyMetadata, $extraTitle = "")
{
    global $CFG_URL_PARAM;

    $extraTitle=FixEncoding($extraTitle);
    $extraTitle     =(strlen($extraTitle)>0) ? $extraTitle.'.  ' : null;
    $rows='<title>'.$extraTitle.$vocabularyMetadata["title"].' / '.$vocabularyMetadata["author"].'</title>';
    $rows.='
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <meta name="encoding" charset="utf-8" />
    <meta name="author" content="'.$vocabularyMetadata["author"].'">
    <meta name="description" content="'.$vocabularyMetadata["scope"].'">
    <meta name="keywords" content="'.$vocabularyMetadata["keywords"].'">
    <meta name="date" content="'.$vocabularyMetadata["lastMod"].'">
    <meta name="generator" content="TemaTres PortalThes">
    <meta name="application-name" content="TemaTres">';

    return $rows;
}

function HTMLformSearch()
{
    global $CFG_URL_PARAM;

    $rows='        <!-- Búsqueda -->
            <div class="search-container">
            <form name="searchForm" method="get" id="searchform" action="'.$CFG_URL_PARAM["url_site"].'index.php">
                <div class="input-group input-group-lg mb-3">
                    <input type="text" class="form-control" id="query" name="arg" class="search-query" placeholder="'.LABEL_Buscar.'">
                    <button type="submit" class="btn btn-primary" type="button" id="searchButton">
                        <i class="bi bi-search"></i> Buscar</button>
                </div>

                <!-- Opciones de búsqueda -->
                <div class="search-options">
                    <div class="d-flex align-items-center flex-wrap gap-3 mb-4">
                        <span class="text-muted small">Opciones:</span>
                        <div class="btn-group btn-group-sm" role="group">
                          <input type="radio" class="btn-check" name="optSearch" id="optSearchExact" value="optSearchExact" >
                          <label class="btn btn-outline-secondary" for="optSearchExact">Exacta</label>
                          
                          <input type="radio" class="btn-check" name="optSearch" id="optSearchDefault"  value="optSearchDefault" checked>
                          <label class="btn btn-outline-secondary" for="optSearchDefault">General</label>

                          
                          <input type="radio" class="btn-check" name="optSearch" id="optSearchNotes" value="optSearchNotes">
                          <label class="btn btn-outline-secondary" for="optSearchNotes">En notas</label>
                        </div>
                      </div>
                </div>
                <input type="hidden" id="task" name="task" value="search" />
                <input type="hidden" id="v" name="v" value="'.$_SESSION["vocab"]["CODE"].'" />
                </form>
            </div>';
    return $rows;
}


/*  Presentación de menú global  */
function HTMLglobalMenu($params = array())
{

    global $CFG_URL_PARAM;
    $rows='        <!-- Encabezado del vocabulario -->
            <div class="vocabulary-header bg-secondary bg-opacity-75 text-white py-3">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h1 class="display-5 fw-bold">
                           <a style="color:#fff;" href="'.redactHREF($params["vocab_code"], "topterms", "").'" title="'.$_SESSION["vocab"]["title"].'">'.$_SESSION["vocab"]["title"].'</a>
                        </h1>                   
                        <p class="lead mt-3">
                            '.textoConColapsoSimple($_SESSION["vocab"]["scope"]).'
                        </p>
                        <div class="mt-3">
                            <span class="badge bg-light text-dark me-2">
                                <i class="bi bi-calendar me-1"></i>'.$_SESSION["vocab"]["createDate"].'
                            </span>
                            <span class="badge bg-light text-dark me-2">
                                <i class="bi bi-globe me-1"></i>'.ucfirst(LABEL_language).': '.$_SESSION["vocab"]["lang"].'
                            </span>
                        <span class="badge bg-light text-dark">
                            <i class="bi bi-tags me-1"></i> '.$_SESSION["vocab"]["cant_terms"].' '.LABEL_terms.' 
                        </span>

                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-center">
                            <i class="bi bi-journal-text" style="font-size: 5rem; opacity: 0.8;"></i>
                        </div>
                    </div>
                </div>
            </div>

';

    return $rows;
}

/*  Presentación de footer global  */
function HTMLglobalFooter($params = array())
{
    global $CFG_URL_PARAM,$CFG;

    $createDate = str_replace('/', '-', $params["vocabularyMetadata"]["createDate"]);

    $rows=' <div class="border-top" style="clear: both;"><p></p></div>';

    $rows.=' <!-- Estadísticas del vocabulario -->
        <div class="stats-grid fade-in-up " style="animation-delay: 0.1s">
 
            <div class="stat-card">
                <div class="stat-icon" style="background: rgba(52, 152, 219, 0.1); color: var(--secondary);">
                    <i class="bi bi-calendar-plus"></i>
                </div>
                <div class="stat-content">
                    <h5>'.date("d/m/Y",strtotime($createDate)).'</h5>
                    <p>'.ucfirst(LABEL_datePublish).'</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon" style="background: rgba(39, 174, 96, 0.1); color: var(--success);">
                    <i class="bi bi-arrow-repeat"></i>
                </div>
                <div class="stat-content">
                    <h5>'.date("d/m/Y",strtotime($params["vocabularyMetadata"]["lastMod"])).'</h5>
                    <p>'.ucfirst(LABEL_dateLastUpdate).'</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon" style="background: rgba(155, 89, 182, 0.1); color: #9b59b6;">
                    <i class="bi bi-hash"></i>
                </div>
                <div class="stat-content">
                    <h5>'.$params["vocabularyMetadata"]["cant_terms"].'</h5>
                    <p>'.ucfirst(LABEL_terms).'</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon" style="background: rgba(241, 196, 15, 0.1); color: #f1c40f;">
                    <i class="bi bi-bell"></i>
                </div>
                <div class="stat-content">
                    <h5>
                        <a href="'.$CFG_URL_PARAM["url_site"].'index.php?task=fetchLast&amp;v='.$params["vocab_code"].'" title="'.ucfirst(LABEL_showNewsTerm).' '.$params["vocabularyMetadata"]["title"].'" class="text-decoration-none" style="color: var(--primary);">'.ucfirst(LABEL_lastChanges).'</a>
                    </h5>
                    <p>'.ucfirst(LABEL_showNewsTerm).'</p>
                </div>
            </div>
        </div>';
    if ((checkModuleCFG('BULK_TERMS_REVIEW')==true) && (@$params["page"]!=='BULK_TERMS_REVIEW') && (count($_GET)==1)) {
        $rows.='<div class="card-header row g-3  py-3">
                    <div class="card-header row g-3 border-top py-3">
                        <div><h4><i class="bi bi-ui-checks-grid fs-4" style="color: var(--secondary);"></i> <a href="'.$CFG_URL_PARAM["url_site"].'apps/autoridades/?v='.$params["vocab_code"].'">'.BULK_TERMS_REVIEW_title.'</a></h4> '.sprintf(BULK_TERMS_REVIEW_home_description,$params["vocabularyMetadata"]["title"]).'
                        </div>
                    </div>
                </div>';

    };

    $rows.='       <footer class="modern-footer fade-in-up" style="animation-delay: 0.4s">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="d-flex align-items-center gap-3">
                        <img src="'.$CFG_URL_PARAM["url_site"].'img/tematres-logo.gif" width="40" height="40" viewBox="0 0 24 24" alt="TemaTres" style="width: 40px;">
                        <div>
                            <p class="mb-0 fw-semibold text-start"><a href="'.redactHREF($params["vocab_code"], "topterms", "").'" title="'.$_SESSION["vocab"]["title"].'">'.$params["vocabularyMetadata"]["title"].'</a></p>
                            <p class="mb-0 small text-muted text-start">'.$params["vocabularyMetadata"]["author"].' '.$params["vocabularyMetadata"]["contributor"].'</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 text-md-end mt-3 mt-md-0">
        
                    <a href="https://github.com/tematres/PortalThes" target="_blank" class="text-decoration-none small text-secondary">
                        <i class="bi bi-github me-1"></i>Tematres PortalThes
                        <i class="bi bi-box-arrow-up-right ms-1" style="font-size: 0.7rem;"></i>
                    </a>
                </div>
            </div>
        </footer>';

    $rows.='    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>';
    $rows.='<script type="text/javascript">var clipboard = new ClipboardJS(\'.copy-clipboard\')</script>';

    if ($CFG["GA_TRACKING_ID"]!=='0') {
        $rows.='<!-- Google Analytics -->' ;
        $rows.='<script async src="https://www.googletagmanager.com/gtag/js?id='.$CFG["GA_TRACKING_ID"].'"></script>
            <script>
              window.dataLayer = window.dataLayer || [];
              function gtag(){dataLayer.push(arguments);}
              gtag(\'js\', new Date());
              gtag(\'config\', \''.$CFG["GA_TRACKING_ID"].'\');
            </script>
            <!-- Google Analytics -->' ;
    }

    return $rows;
}


/* LISTA VOCS */
function HTMLlistaVocabularios($array_vocabs, $selected_vocab = "")
{

    global $CFG_URL_PARAM;
    $rows='';
    foreach ($array_vocabs as $k => $v) {
        if ($k!=='default_vocab') {
            $rows.='<li id="vocab_'.$v["CODE"].'">';
            $rows.='<a href="'.$CFG_URL_PARAM["url_site"].'index.php?v='.$v["CODE"].'" title="'.$v["TITLE"].'">'.$v["TITLE"].'</a>';
            $rows.='</li>';
        };
    }
    return $rows;
}


/* BOXES VOCABULARIOS */
function HTMLdivlistaVocabularios($array_vocabs)
{
    global $CFG_URL_PARAM;
    $rows='';
    foreach ($array_vocabs as $k => $v) {
        $rows.=HTMLrss2home($v["CODE"]);
    }
    return $rows;
}


function HTMLcrawlerContentTab($data, $vocab_code, $array_plugins = array())
{
    return ;
}

function selectVocabulario($array_vocabs, $selected)
{
    if (is_array($array_vocabs[$selected])) {
        return $array_vocabs[$selected]["URL_BASE"];
    } else {
        return $array_vocabs[$selected][$dft];
    }
}

/*  Presentación de menú de sugerencias  */
function HTMLtermSuggestionMenu($params = array())
{
    global $CFG_URL_PARAM;
    $rows=' <div class="tab-pane" id="suggests">
                <h4>'.SUGGESTION_SERVICE_title.'</h4>
                <ul>
                    <li>
                        <a href="'.$CFG_URL_PARAM["url_site"].'apps/suggest/index.php?v='.$params["vocab_code"].'&amp;term_id='.$params["term_id"].'&amp;r=modT" title="'.LABEL_modSuggest.'">
                            '.LABEL_modSuggest.'
                        </a>
                    </li>
                    <li>
                        <a href="'.$CFG_URL_PARAM["url_site"].'apps/suggest/index.php?v='.$params["vocab_code"].'&amp;term_id='.$params["term_id"].'&amp;r=NT" title="'.LABEL_ntSuggest.'">
                            '.LABEL_ntSuggest.'
                        </a>
                    </li>
                    <li>
                        <a href="'.$CFG_URL_PARAM["url_site"].'apps/suggest/index.php?v='.$params["vocab_code"].'&amp;term_id='.$params["term_id"].'&amp;r=UF" title="'.LABEL_altSuggest.'">
                            '.LABEL_altSuggest.'
                        </a>
                    </li>
                    <li>
                        <a href="'.$CFG_URL_PARAM["url_site"].'apps/suggest/index.php?v='.$params["vocab_code"].'" title="'.LABELFORM_newSuggest.'">
                            '.LABELFORM_newSuggest.'
                        </a>
                    </li>
                </ul>
            </div>';
    return $rows;
}



function HTMLtermDetaills($htmlTerm, $dataTerm, $vocabularyMetadata)
{

    $term= (string) FixEncoding($dataTerm->result->term->string);
    $term_id= (int) $dataTerm->result->term->term_id;
    $rows='';

    if (! isset($htmlTerm["results"]["MAP"])) {
        $htmlTerm["results"]["MAP"] = '';
    }
    if (! isset($htmlTerm["results"]["LINKED"])) {
        $htmlTerm["results"]["LINKED"] = '';
    }

    if (isset($htmlTerm["results"]["breadcrumb"])) {
        $rows.=$htmlTerm["results"]["breadcrumb"];
    }

    if (isset($htmlTerm["results"]["termdata"])) {

        $type_term_style=($dataTerm->result->term->isMetaTerm==1) ? '<i class="bi bi-info-circle text-secondary ms-1" data-bs-toggle="tooltip" title="'.ucfirst(NOTE_isMetaTerm).'"></i>' : '';        
        
        $rows.= '<!-- Detalle del término -->
                    <div class="col-lg-7 mb-4"><h4 class="mb-3"><i class="bi bi-record-circle me-2"></i><span class="highlight">'.$dataTerm->result->term->string.'</span>'.$type_term_style.'</h4>
                        <div class="term-detail-card" id="termDetail">';
    }


    $htmlTerm["results"]["UF"]=$htmlTerm["results"]["UF"] ?? '';
    $htmlTerm["results"]["BT"]=$htmlTerm["results"]["BT"] ?? '';
    $htmlTerm["results"]["NT"]=$htmlTerm["results"]["NT"] ?? '';
    $htmlTerm["results"]["RT"]=$htmlTerm["results"]["RT"] ?? '';
    $htmlTerm["results"]["MAP"]=$htmlTerm["results"]["MAP"] ?? '';
    $htmlTerm["results"]["LINKED"]=$htmlTerm["results"]["LINKED"] ?? '';

    if (strlen($htmlTerm["results"]["UF"])>0) {
    $rows.= '<div class="term-section" id="altTerms">'.$htmlTerm["results"]["UF"].'</div>';
    }

    if (strlen($htmlTerm["results"]["NOTES"]) > 0) {
          $rows.= '<div class="term-section"><h5 class="term-section-title">
                            <i class="bi bi-sticky"></i>'.ucfirst(LABEL_notes).' </h5>'.$htmlTerm["results"]["NOTES"].'</div>';
    }

    if (strlen($htmlTerm["results"]["BT"])>0) {
        $rows.= '<div class="relation-body term-section">'.$htmlTerm["results"]["BT"].'</div>';

    }

    if (strlen($htmlTerm["results"]["NT"])>0) {
     $rows.= '<div class="relation-body term-section">'.$htmlTerm["results"]["NT"].'</div>';
    }

    if (isset($htmlTerm["results"]["RT"])) {
        $rows.= '<div class="relation-body  term-section">'.$htmlTerm["results"]["RT"].'</div>';
    }

    if (strlen($htmlTerm["results"]["MAP"]>0)) {
        $rows.= '<div class="relation-body  term-section">'.$htmlTerm["results"]["MAP"].'</div>';
    }
    if (strlen($htmlTerm["results"]["LINKED"]>0)) {
        $rows.= '<div class="relation-body  term-section">'.$htmlTerm["results"]["LINKED"].'</div>';
    }
    $rows.=  '</div><!-- #Detalle del término -->';


    $rows.=  '</div><!-- #term -->';

      /** if there are relations */
      if(isset($htmlTerm["data4vis"]["edges"])){
        $rows.=  '             <!-- Visualización gráfica -->
            <div class="col-lg-5 mb-4">
                <h4 class="mb-3"><i class="bi bi-diagram-3 me-2"></i>Vista gráfica de relaciones</h4>
                <!-- El grafo se renderizará aquí -->
                <div class="graph-container" id="networkGraph">
                    <div id="graphterm"></div>
                </div>
            </div>';
      }


    return $rows;
}




//Eval if the code is a valid vocab => VCODE is default CODE
function fetchVocabCode($vocab_code)
{
    global $CFG_VOCABS;
    global $CFG;

    $v=configValue($vocab_code, $CFG["DEFVOCAB"], $CFG["VOCABS"]);

    $v=(strlen($vocab_code)>0) ? XSSprevent($vocab_code) : '';

    $v=(strlen($v)>0) ? $v : $CFG["DEFVOCAB"];

    foreach ($CFG_VOCABS as $k => $val) {
        if(isset($val[$k])) :
            if ($val[$k] == $v) {
                return $v;
            };
        endif;
    }
    return $v;
}

/* Retorna los datos, acorde al formato de autocompleter */
function getData4Autocompleter($URL_BASE, $searchq)
{

        $data=getURLdata($URL_BASE.'?task=suggestDetails&arg='.$searchq);
        $arrayResponse=array("query"=>$searchq,
                             "suggestions"=>array(),
                             "data"=>array());
    if ($data->resume->cant_result > 0) {
        foreach ($data->result->term as $value) {
            $i=++$i;
            array_push($arrayResponse["suggestions"], (string) $value->string);
            array_push($arrayResponse["data"], (int) $value->term_id);
        }
    }
        return json_encode($arrayResponse);
};


/* Retorna los datos, acorde al formato de autocompleter UI*/
function getData4AutocompleterUI($URL_BASE, $searchq,$v)
{

    $data=getURLdata($URL_BASE.'?task=suggestDetails&arg='.$searchq);
    $arrayResponse=array();
    $i=0;
    if ($data->resume->cant_result > 0) {
        foreach ($data->result->term as $value) {
            $i=++$i;
            array_push($arrayResponse, (string) $value->string);
        }
    }
return json_encode($arrayResponse);
};



/*Revisa si el módulo esta habilitado para el vocabulario*/
function checkModuleCFG($module, $v = 1)
{

    $enable_modules=(is_array($_SESSION["vocab"]["MODULES"])) ? $_SESSION["vocab"]["MODULES"] : array();

    switch ($module) {
        case 'BULK_TERMS_REVIEW':
            if (in_array('BULK_TERMS_REVIEW', $enable_modules)) {
                return true;
            }
            break;

        case 'SUGGESTION_SERVICE':
            //que este habilitado y que haya mail de contacto
            if ((in_array('SUGGESTION_SERVICE', $enable_modules)) && (strlen($_SESSION["vocab"]["mail"])>0)) {
                return true;
            }
            break;

        case 'COPY_CLICK':
            if (in_array('COPY_CLICK', $enable_modules)) {
                return true;
            }
            break;

        case 'MARC21':
            if (in_array('MARC21', $enable_modules)) {
                return true;
            }
            break;
        default:
            return false;
            break;
    }
}

/*HTML button to copy the value string for valid term*/
function HTMLcopyClick($v, $targt_div, $array_flags)
{

    if (($array_flags["isMetaTerm"]==1) || ($array_flags["isValidTerm"]==0) || (checkModuleCFG('COPY_CLICK', $v)==0)) {
        return;
    }

    return '<button class="btn btn-default btn-xs copy-clipboard" data-clipboard-action="copy" data-clipboard-target="#'.$targt_div.'" alt="'.ucfirst(LABEL_copy_click).'"><span class="glyphicon glyphicon-copy" aria-hidden="true"  title="'.ucfirst(LABEL_copy_click).'"></span></button>';
}



/**
 * DATA for visual graph
 *
 * @param array $termData  object term data
 * @param array $DirectData  object data about terms who are related with any relation to the term
 * @param array $NTdata  data about terms who is more narrowed to the term
 *
 * @return return $array with nodes and edges content
 */
function data4vis($termData,$DirectData,$NTdata){

if ($NTdata->resume->cant_result > 0) {
    foreach ($NTdata->result->term as $value) {
    $term_context["NT"][]=array("term_id"=>object2value($value,"term_id"),
                                                         "term_string"=>object2value($value,"string","string"),
                                                         "term_code"=>object2value($value,"code","string"),
                                                         "rel_type"=>object2value($value,"relation_type","string"),
                                                         "rel_rel_type"=>object2value($value,"relation_label","string")
                                                         );
    };
}

if ($DirectData->resume->cant_result > 0) {

    foreach ($DirectData->result->term as $value) {
    $term_context[(string) $value->relation_type][]=array("term_id"=>object2value($value,"term_id"),
                                                         "term_string"=>object2value($value,"string","string"),
                                                         "term_code"=>object2value($value,"code","string"),
                                                         "rel_type"=>object2value($value,"relation_type","string"),
                                                         "rel_rel_type"=>object2value($value,"relation_label","string")
                                                         );
    };
}


$array_node[]=array("id"=>"TheTerm", "label"=>wordwrap((string) $termData->result->term->string,20,"\n"),"color"=>"#E39A94","fixed"=>true,"level"=>1,"shape"=>"circle");

if(isset($term_context["NT"])) :
    $nt_nodes_cant=count($term_context["NT"]);

    $array_node[]=array("id"=>"NT", "label"=>wordwrap(ucfirst(TE_terminos.' ('.$nt_nodes_cant.')'),20,"\n"),"fixed"=>false,"level"=>3, "color"=>"lightgrey","font"=>false);
    $array_edge[]=array("from"=>"TheTerm","to"=>"NT","arrows"=>"to");
    if ($nt_nodes_cant<50) :
        foreach ($term_context["NT"] as $key => $value) {
            $array_node[]=array("id"=>$value["term_id"], "label"=>wordwrap($value["term_string"],20,"\n"),"fixed"=>false,"group"=>"GNT");
                $edge_label=(isset($value["rel_rel_type"])) ? $value["rel_rel_type"] : null;
                $array_edge[]=array("from"=>"NT","to"=>$value["term_id"],"label"=>$edge_label);
        }
        endif;
    endif;

if(isset($term_context["BT"])) :
    $bt_nodes_cant=count($term_context["BT"]);
    $array_node[]=array("id"=>"BT", "label"=>wordwrap(ucfirst(TG_terminos),20,"\n"),"group"=>"GBT","fixed"=>false,"level"=>3,"color"=>"lightgrey");
    $array_edge[]=array("from"=>"TheTerm","to"=>"BT","arrows"=>"from");
    foreach ($term_context["BT"] as $key => $value) {
        $array_node[]=array("id"=>$value["term_id"], "label"=>wordwrap($value["term_string"],20,"\n"),"fixed"=>false,"group"=>"GBT");
        $edge_label=(isset($value["rel_rel_type"])) ? $value["rel_rel_type"] : null;
        $array_edge[]=array("from"=>"BT","to"=>$value["term_id"],"label"=>$edge_label);
    }
    endif;

if(isset($term_context["RT"])) :
    $rt_nodes_cant=count($term_context["RT"]);
    $array_node[]=array("id"=>"RT", "label"=>wordwrap(ucfirst(TR_terminos.' ('.$rt_nodes_cant.')'),20,"\n"),"group"=>"GRT","fixed"=>false,"level"=>3,"color"=>"lightgrey");
    $array_edge[]=array("from"=>"TheTerm","to"=>"RT","arrows"=>"from,to");

        if ($rt_nodes_cant<30) :
            foreach ($term_context["RT"] as $key => $value) {
                $array_node[]=array("id"=>$value["term_id"], "label"=>wordwrap($value["term_string"],20,"\n"),"fixed"=>false,"group"=>"GRT");
                    $edge_label=(isset($value["rel_rel_type"])) ? $value["rel_rel_type"] : null;
                    $array_edge[]=array("from"=>"RT","to"=>$value["term_id"],"label"=>$edge_label);
            }
        endif;

    endif;


    return array("nodes"=>$array_node,"edges"=>$array_edge);
}

//lista alfabética
function HTMLalphaNav($arrayLetras = array(), $select_letra = "",$param = array())
{
    global $URL_BASE;
    $vocab_code=fetchVocabCode(@$param["vocab_code"]);

    $rows='        <!-- Navegación por letras -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="alphabet-nav">';
    foreach ($arrayLetras as $letra) {
        $class=($select_letra==$letra) ? 'active' : '';
        //$rows.='    <li class="'.$class.'">';
        $rows.='<a class="alphabet-letter '.$class.'" href="'.redactHREF($vocab_code, "letter", strtoupper($letra)).'">'.strtoupper($letra).'</a>';
        //$rows.='</li>';
    }
    $rows.='    </ul>';
    $rows.='     </div>
              </div>
            </div>';
    return $rows;
}



/**
 * HTML for visual graph
 *
 * @param array $nodes  array terms as nodes
 * @param array $edges  array of edges about relations between the terms.
 *
 * @return return $html content
 */
function HTMLvisualGraph($nodes,$edges){

if(is_array($nodes)) :
$html='   <script type="text/javascript">

        let nodes = new vis.DataSet('. json_encode($nodes). ');
        let edges = new vis.DataSet('. json_encode($edges). ');

        let data = {
            nodes: nodes,
            edges: edges,
        };
        let options = {
            interaction:{
            hover:true,
            },
            groups: {
                \'GBT\': {color:{background:\'#FADD91\'}, borderWidth:3},
                \'GRT\': {color:{background:\'#81D9E3\'}, borderWidth:3},
                \'GNT\': {color:{background:\'#B2FF8F\'}, borderWidth:3}
            },
            physics: {
                    stabilization: false,
                    barnesHut: {
                    springLength: 200,
                    },
            },
            nodes:{
                color: \'#B982FF\',
                fixed: false,
                font: \'20px arial black\',
                scaling: {
                    label: false,
                },
                shadow: true,
                shape: \'box\',
                margin: 10,
                multi: false,
            },
            edges: {
                color: \'black\',
                scaling: {
                    label: false,
                },
                shadow: true,
            },


        };
        let container = document.getElementById("graphterm");
        let network = new vis.Network(container, data, options);
    </script>';
endif;


return $html;
}


/**
 * HTML to display error data
 *
 * @param array $array_vocab  data about the vocab from config.ws.php
 * @param array $array_error  data about the error
 *
 * @return return $html content
 */

function HTMLerrorVocabulary($vocab_id,$array_error=array()){

    global $CFG_VOCABS;

    $rows='<div class="alert alert-danger" role="alert">';
    $rows.=' <h3 class="text-center">Cannot retrieve the data with the given config.</h3>';
    $rows.='</div>';

return $rows;
}

/**
 * Convierte un texto en HTML con sistema de colapso/expansión usando Bootstrap
 * si el texto supera los 400 caracteres
 *
 * @param string $texto Texto a procesar
 * @return string Código HTML generado
 */

function textoConColapsoSimple(string $texto): string {
    // Limpiar el texto
    $texto = htmlspecialchars($texto, ENT_QUOTES, 'UTF-8');
    
    // Si el texto tiene 400 caracteres o menos, devolverlo sin cambios
    if (strlen($texto) <= 400) {
        return '<p>' . $texto . '</p>';
    }
    
    // Obtener los primeros 400 caracteres
    $textoCorto = substr($texto, 0, 400);
    $ultimoEspacio = strrpos($textoCorto, ' ');
    if ($ultimoEspacio !== false) {
        $textoCorto = substr($textoCorto, 0, $ultimoEspacio);
    }
    
    // El resto del texto
    $textoLargo = substr($texto, strlen($textoCorto));
    
    // Construir el HTML
    $html = '<p>';
    $html .= '<span id="shortVersion">' . $textoCorto . '...</span>';
    $html .= '<span id="fullVersion" class="collapse" aria-expanded="false">';
    $html .= $textoLargo;
    $html .= '</span>';
    $html .= '</p>';
    
    // Botón para expandir/colapsar
    $html .= '<button class="btn btn-sm btn-outline-light" 
                    type="button" 
                    data-bs-toggle="collapse" 
                    data-bs-target="#fullVersion"
                    aria-expanded="false" 
                    aria-controls="fullVersion"
                    onclick="toggleButtonText(this)">'.ucfirst(LABEL_showMore).'
              </button>';
    
    return $html;
}
/**
 * Corta texto en HTML si el texto supera los 400 caracteres
 *
 * @param string $texto Texto a procesar
 * @return string Código HTML generado
 */

function textoLimitado(string $texto, int $char_limit=400): string {

        // Limpiar el texto
    $texto = htmlspecialchars($texto, ENT_QUOTES, 'UTF-8');

    $char_limit = ($char_limit>400) ? 200 : $char_limit;
    
    // Si el texto tiene 400 caracteres o menos, devolverlo sin cambios
    if (strlen($texto) <= $char_limit) {
        return $texto;
    }
    
    // Obtener los primeros 400 caracteres
    $textoCorto = substr($texto, 0, $char_limit);
    $ultimoEspacio = strrpos($textoCorto, ' ');
    if ($ultimoEspacio !== false) {
        $textoCorto = substr($textoCorto, 0, $ultimoEspacio);
    }
    
    return $textoCorto;
}



/**
 * Convierte un texto en HTML con sistema de colapso/expansión usando Bootstrap
 * si el texto supera los 400 caracteres
 *
 * @param string $texto Texto a procesar
 * @return string Código HTML generado
 */
function HTMLgridTerminos($vocab_code,$note_type,$limit=1){
    GLOBAL $URL_BASE, $CFG_VOCABS;

    $note_type=configValue($note_type, 'NA');

    $array_terms=data4randomTerms($limit,$note_type,$terms_array=array());

    $i=0;
     $rows='    <!-- Grid de Términos -->
                <div class="row g-4" id="glossaryGrid">';

    foreach ($array_terms as $term_data) {
        
        $data2html4NotesBadge=data2html4NotesBadge(getURLdata($URL_BASE.'?task=fetchNotes&arg='.$term_data["term_id"]),$note_type);
        $nota=textoLimitado($data2html4NotesBadge,200);
        $nota=(strlen($data2html4NotesBadge)>strlen($nota)) ? $nota.' ... <i class="bi bi-card-text me-1"></i> <a href="'.redactHREF($_SESSION["v"], "fetchTerm", $term_data["term_id"]).'">'.LABEL_showMore.' ...</a> ' : $nota;
        $i=++$i;
        $rows.='            <!-- Término '.$i.' -->
                    <div class="col-md-6 col-lg-4">
                        <div class="card glossary-card shadow-sm">
                            <!--<span class="badge bg-warning category-badge">Tecnología</span> -->
                            <div class="card-body p-4">
                                <!-- <div class="term-icon bg-primary bg-opacity-10 text-primary">
                                    <i class="bi bi-cloud"></i>
                                </div> -->';
        $rows.='<h3 class="text-decoration-none hover-underline term-link" data-term-id="'.$term_data["term_id"].'">'.ucfirst($term_data["term"]).'</h3>';                                
        //$rows.='<h3  class="text-decoration-none text-dark hover-underline"><a href="'.redactHREF($_SESSION["v"], "fetchTerm", $term_data["term_id"]).'" >'.ucfirst($term_data["term"]).'</a></h3>';
        $rows.='                <p class="term-note">'.$nota.'</p>';
        $rows.='                   <div class="mt-3">
                                    
                                </div>';
        $rows.='           </div>
                        </div>
                    </div>';
    };

        $rows .= '<a href="'.redactHREF($_SESSION["v"], "topterms", "").'" class="btn btn-primary" title="'.ucfirst(LABEL_showMore).'"><i class="bi bi-chevron-down me-2"></i>'.ucfirst(LABEL_showMore).'</a>';

$rows.='        </div>';//cierre grid            

return $rows;

}




/**Cloud terms  */
function HTMLcloudTerms($vocab_code,$limit, $term_id = 0){

    GLOBAL $URL_BASE, $CFG_VOCABS;

    //$URL_BASE=$CFG_VOCABS["$vocab_code"]["URL_BASE"];

    $data=getURLdata($URL_BASE.'?task=fetchCentralTerms');
    $i=0;
     if ($data->resume->cant_result > 0) {
        $rows='<div class="row g-3 border-top"><h3>'.ucfirst(LABEL_prototypeTerms).'</h3>' ;
        $rows.='<div class="d-flex flex-wrap align-items-center gap-2 p-4 bg-light rounded" id="tagcloud">' ;
        foreach ($data->result->term as $value) {
            $i=++$i;
            if($i==1){$max_widht=$value->width;}
            $font_size=str_replace(array(0,1,2,3,4), array("tag-xs","tag-sm","tag-md","tag-lg","tag-xl"),round(ceil($value->width*100/$max_widht)/25));
            $rows.='<a class="btn btn-primary '.$font_size.'" href="'.redactHREF($_SESSION["v"], "fetchTerm", $value->term_id).'" role="button" rel="'.$value->width.'" title="'.$value->string.'">'.$value->string.'</a>' ;
            $max_width=$value->width;
        }
        $rows.=' </div>' ;
        $rows.='</div>' ;
    } else {
        return false;
    }

    return $rows;
}

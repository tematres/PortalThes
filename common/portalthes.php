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
    <script src="'.$CFG_URL_PARAM["url_site"].'js/jquery-3.4.1.min.js"></script>
    <script src="'.$CFG_URL_PARAM["url_site"].'js/jquery-ui-1.12.1/jquery-ui.js"></script>
    <script src="'.$CFG_URL_PARAM["url_site"].'js/vis-network.min.js"></script>

    <script src="'.$CFG_URL_PARAM["url_site"].'bt/3.3.4/js/bootstrap.min.js"></script>

    <script src="'.$CFG_URL_PARAM["url_site"].'js/jquery.mockjax.js"></script>
    <script src="'.$CFG_URL_PARAM["url_site"].'vendor/jquery.validate.min.js"></script>
    <script src="'.$CFG_URL_PARAM["url_site"].'vendor/masonry.pkgd.min.js"></script>
    <script src="'.$CFG_URL_PARAM["url_site"].'vendor/imagesloaded.pkgd.min.js"></script>
    <script src="'.$CFG_URL_PARAM["url_site"].'js/tree.jquery.js"></script>
    <script src="'.$CFG_URL_PARAM["url_site"].'js/js.php?v='.$_SESSION["v"].'"></script>
    <script type="text/javascript" src="'.$CFG_URL_PARAM["url_site"].'js/clipboard.min.js"></script>
    <!-- css -->
    <link rel="stylesheet" href="'.$CFG_URL_PARAM["url_site"].'bt/3.3.4/css/bootstrap.min.css">
    <link type="image/x-icon" href="'.$CFG_URL_PARAM["url_site"].'css/favicon.ico" rel="icon" />

    <link rel="stylesheet" href="'.$CFG_URL_PARAM["url_site"].'css/jqtree.css">
    <link rel="stylesheet" href="'.$CFG_URL_PARAM["url_site"].'css/thes.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans|Syncopate">
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="'.$CFG_URL_PARAM["url_site"].'js/jquery-ui-1.12.1/jquery-ui.css">';

    $rows.='<link rel="alternate" href="'.$CFG_URL_PARAM["url_site"].'rss.php?v='.$_SESSION["v"].'" title="RSS '.$_SESSION["vocab"]["title"].'" />';

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
    $rows='<div class="row">
      <div class="col-12">
        <form name="searchForm" method="get" id="searchform" action="'.$CFG_URL_PARAM["url_site"].'index.php">
            <div id="custom-search-input">
                <div class="input-group ">
                    <input type="text" class="form-control input-lg"  id="query" name="arg" class="search-query" placeholder="'.LABEL_Buscar.'">
                    <span class="input-group-btn">
                        <button class="btn btn-info btn-lg" type="submit">
                            <i class="glyphicon glyphicon-search"></i>
                        </button>
                    </span>
                </div>
            </div>
            <input type="hidden" id="task" name="task" value="search" />
            <input type="hidden" id="v" name="v" value="'.$_SESSION["vocab"]["CODE"].'" />             
        </form>
        </div>
    </div>';
    return $rows;
}



/*  Presentación de menú global  */
function HTMLglobalMenu($params = array())
{

    global $CFG_URL_PARAM;

 //   $vocab_code=$_SESSION["vocab"]["code"];

    $rows=' <nav class="navbar navbar-inverse navbar-fixed-top bnm_navbar">
                <div class="container">
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <a class="navbar-brand completo" href="'.redactHREF($params["vocab_code"], "topterms", "").'" title="'.$_SESSION["vocab"]["title"].'">'.$_SESSION["vocab"]["title"].'</a>
                        <a class="navbar-brand breve" href="'.$CFG_URL_PARAM["url_site"].'index.php" title="'.$_SESSION["vocab"]["title"].'"></a>
                    </div>
                    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                        <ul class="nav navbar-nav navbar-right">
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">'.ucfirst(LABEL_tools).'<b class="caret"></b></a>
                                <ul class="dropdown-menu" role="menu">';

<<<<<<< HEAD
    if (checkModuleCFG('BULK_TERMS_REVIEW',$_SESSION["vocab"]["CODE"])==true) {
        $rows.='<li><a href="'.$CFG_URL_PARAM["url_site"].'apps/autoridades/index.php?v='.$params["vocab_code"].'" title="'.ucfirst(BULK_TERMS_REVIEW_short_description).'">'.ucfirst(BULK_TERMS_REVIEW_title).'</a></li>';
    }

    if (checkModuleCFG('CLASSIFFY',$_SESSION["vocab"]["CODE"])==true) {
        $rows.='<li><a href="'.$CFG_URL_PARAM["url_site"].'apps/classify/index.php?v='.$params["vocab_code"].'" title="'.ucfirst(CLASSIFY_SERVICE_short_description).'">'.ucfirst(CLASSIFY_SERVICE_title).'</a></li> ';
    }

    if (checkModuleCFG('SUGGESTION_SERVICE',$_SESSION["vocab"]["CODE"])==true) {
        $rows.='<li><a href="'.$CFG_URL_PARAM["url_site"].'apps/suggest/index.php?v='.$params["vocab_code"].'" title="'.ucfirst(SUGGESTION_SERVICE_short_description).'">'.ucfirst(SUGGESTION_SERVICE_title).'</a></li>';
    }
                    
    if (checkModuleCFG('MARC21',$_SESSION["vocab"]["CODE"])==true) {
=======
    if (checkModuleCFG('BULK_TERMS_REVIEW')==true) {
        $rows.='<li><a href="'.$CFG_URL_PARAM["url_site"].'apps/autoridades/index.php?v='.$params["vocab_code"].'" title="'.ucfirst(BULK_TERMS_REVIEW_short_description).'">'.ucfirst(BULK_TERMS_REVIEW_title).'</a></li>';
    }

    if (checkModuleCFG('CLASSIFFY')==true) {
        $rows.='<li><a href="'.$CFG_URL_PARAM["url_site"].'apps/classify/index.php?v='.$params["vocab_code"].'" title="'.ucfirst(CLASSIFY_SERVICE_short_description).'">'.ucfirst(CLASSIFY_SERVICE_title).'</a></li> ';
    }

    if (checkModuleCFG('SUGGESTION_SERVICE')==true) {
        $rows.='<li><a href="'.$CFG_URL_PARAM["url_site"].'apps/suggest/index.php?v='.$params["vocab_code"].'" title="'.ucfirst(SUGGESTION_SERVICE_short_description).'">'.ucfirst(SUGGESTION_SERVICE_title).'</a></li>';
    }
                    
    if (checkModuleCFG('MARC21')==true) {
>>>>>>> 84c0b9b0b042da9954423285a2b83b4183995a97
        $rows.='<li><a href="'.$CFG_URL_PARAM["url_site"].'apps/marc21/index.php?v='.$params["vocab_code"].'" title="'.ucfirst(MARC21_SERVICE_short_description).'">'.ucfirst(MARC21_SERVICE_title).'</a></li>';
    }

    $rows.='    <li><a href="'.$CFG_URL_PARAM["url_site"].'index.php?task=fetchLast&v='.$params["vocab_code"].'">Consultar últimas modificaciones</a></li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>';
    return $rows;
}

/*  Presentación de footer global  */
function HTMLglobalFooter($params = array())
{
    global $CFG_URL_PARAM,$CFG_SITE;

    $rows=' <div style="clear: both;"></div>
            <footer class="row">
                <section class="col-xs-12 col-sm-4">
                    <h5>'.ucfirst(LABEL_tools).'</h5>
                    <ul class="list-unstyled">';

    if (checkModuleCFG('BULK_TERMS_REVIEW')==true) {
        $rows.='<li><a href="'.$CFG_URL_PARAM["url_site"].'apps/autoridades/index.php?v='.$params["vocab_code"].'" title="'.ucfirst(BULK_TERMS_REVIEW_short_description).'">'.ucfirst(BULK_TERMS_REVIEW_title).'</a></li>';
    }

    if (checkModuleCFG('CLASSIFFY')==true) {
        $rows.='<li><a href="'.$CFG_URL_PARAM["url_site"].'apps/classify/index.php?v='.$params["vocab_code"].'" title="'.ucfirst(CLASSIFY_SERVICE_short_description).'">'.ucfirst(CLASSIFY_SERVICE_title).'</a></li> ';
    }

    if (checkModuleCFG('SUGGESTION_SERVICE')==true) {
        $rows.='<li><a href="'.$CFG_URL_PARAM["url_site"].'apps/suggest/index.php?v='.$params["vocab_code"].'" title="'.ucfirst(SUGGESTION_SERVICE_short_description).'">'.ucfirst(SUGGESTION_SERVICE_title).'</a></li>';
    }
                    
    if (checkModuleCFG('MARC21')==true) {
        $rows.='<li><a href="'.$CFG_URL_PARAM["url_site"].'apps/marc21/index.php?v='.$params["vocab_code"].'" title="'.ucfirst(MARC21_SERVICE_short_description).'">'.ucfirst(MARC21_SERVICE_title).'</a></li>';
    }

    $rows.='       <li>
                            <a href="'.$CFG_URL_PARAM["url_site"].'index.php?task=fetchLast&v='.$params["vocab_code"].'">
                                '.ucfirst(LABEL_showNewsTerm).'
                            </a>
                        </li>                        
                    </ul>
                </section>
                <section class="col-xs-12 col-sm-4">
                    <h5> '.$CFG_URL_PARAM["site_info"].'</h5>
                    <ul class="list-unstyled">
                        <li>
                        '.$CFG_URL_PARAM["site_info_line1"].'
                        </li>
                        <li>
                        '.$CFG_URL_PARAM["site_info_line2"].'
                        </li>
                    </ul>
                </section>
                <section class="col-xs-12 col-sm-4">
                    <h5>'.LABEL_contact.'</h5>
                    <ul class="list-unstyled">
                        <li>'.array2value("contact_info_line1",$CFG_SITE).'</li>
                        <li>'.array2value("contact_info_line2",$CFG_SITE).'</li>
                        <li>
                            <address>
                                <a href="mailto:'.$_SESSION["vocab"]["mail"].'" target="_blank">
                                    '.$_SESSION["vocab"]["mail"].'
                                </a>
                            </address>
                        </li>
                    </ul>
                </section>
            </footer>';
            
            $rows.='<script type="text/javascript">var clipboard = new ClipboardJS(\'.copy-clipboard\')</script>';
    return $rows;
}

/*  Presentación de menú contextual global  */
function HTMLglobalContextualMenu($params = array())
{
    global $CFG_URL_PARAM;
    global $CFG_SITE;

    if (@$params["vocab_code"]) {
        $rows=' <h1>
                    <a href="'.redactHREF($params["vocab_code"], "topterms", "").'" title="'.$params["vocabularyMetadata"]["title"].'">'.$params["vocabularyMetadata"]["title"].'</a>
                </h1>
                <p class="autor text-right">
                    '.LABEL__attrib.' '.$params["vocabularyMetadata"]["author"].'
                </p>
                <p class="text-left ocultar">
                    '.$params["vocabularyMetadata"]["scope"].'
                </p>
                <div id="carousel" class="carousel slide ocultar" data-ride="carousel">
                    <ol class="carousel-indicators">
                      <li data-target="#carousel" data-slide-to="0" class="active"></li>
                      <li data-target="#carousel" data-slide-to="1"></li>
                    </ol>
                    <!-- Wrapper for slides -->
                    <div class="carousel-inner" role="listbox">
                        <div class="item active">
                            <div class="whitebox text-center">
                                <p class="statsnum">
                                    '.$params["vocabularyMetadata"]["cant_terms"].'
                                </p>
                                <p class="statstext"> '.LABEL_terms.' ';
        
        if ($_SESSION["vocab"]["mail"]) {
            $rows.='<a href="'.$CFG_URL_PARAM["url_site"].'apps/suggest/index.php?v='.$params["vocab_code"].'" title="'.LABELFORM_newSuggest.' '.$params["vocabularyMetadata"]["title"].'"><span>'.LABELFORM_newSuggest.'</span></a>';
        }

        $rows.='                </p>
                            </div>
                        </div>
                        <div class="item">
                            <div class="whitebox text-center">';
                                /*La última actualizacionesción fue en '.date_format(date_create_from_format('Y-m-d G:i:s', $params["vocabularyMetadata"]["lastMod"]), 'm/y').'. */$rows.='<br><a href="'.$CFG_URL_PARAM["url_site"].'index.php?task=fetchLast&amp;v='.$params["vocab_code"].'" title="Últimas modificaciones de '.$params["vocabularyMetadata"]["title"].'"><span>'.ucfirst(LABEL_showNewsTerm).'</span></a>
                            </div>
                        </div>
                    </div>
                </div>';
    }
    return $rows;
}


/*  Presentación de menú contextual de término  */
function datosdeltermino($params = array())
{
    global $CFG_URL_PARAM;
    $rows=' <div class="tab-pane" id="datos">
                <h4>URI DEL TÉRMINO Y REPRESENTACIONES ALTERNATIVAS</h4>
                <ul class="list-unstyled">
                    <li class="">
                        <a href="'.$params["vocabularyMetadata"]["uri"].'services.php?task=fetchTerm&amp;arg='.$params["term_id"].'">
                                <i class="fa fa-link"></i>'.LABEL_URIterm.'
                            </a>
                        </li>
                    <li class="">
                        <a href="'.$params["vocabularyMetadata"]["uri"].'services.php?task=fetchTerm&amp;arg='.$params["term_id"].'&amp;output=json'.'" title="Javascript Object Notation representation">
                            <img src="img/json.png">JSON
                        </a>
                    </li>
                    <li class="">
                        <a href="'.$params["vocabularyMetadata"]["uri"].'services.php?task=fetchTerm&amp;arg='.$params["term_id"].'&amp;output=skos'.'" title="RDF Skos-core representation" id="rdf_link_xml">
                            <i class="fa fa-share-alt"></i>Skos-Core
                        </a>
                    </li>
                    <li class="">
                        <a href="'.$params["vocabularyMetadata"]["uri"].'services.php?task=fetchTerm&amp;arg='.$params["term_id"].'" id="xml_link">
                            <i class="fa fa-code"></i>XML
                        </a>
                    </li>
                </ul>
            </div>';
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

    if (isset($htmlTerm["results"]["termdata"])) {
        $rows.= '          <div>
                            <h2>
                                '.$htmlTerm["results"]["termdata"].'
                            </h2>
                        </div>';
    }


    $rows.= '<div class="tabbable">
                    <ul class="nav nav-tabs sections">
                        <li class="active">
                            <a href="#main" data-toggle="tab">
                                '.LABEL_Termino.'
                            </a>
                        </li>';
    if (isset($htmlTerm["results"]["MAP"]) || isset($htmlTerm["results"]["LINKED"])) {
        $rows.= '  <li class="">
                            <a href="#matches" data-toggle="tab">
                                '.LABEL_TargetTerm.'
                            </a>
                        </li>';
    }
    if ($_SESSION["vocab"]["mail"]) {
        $rows.= '  <li class="">
                            <a href="#suggests" data-toggle="tab">
                                '.LABEL_suggests.'
                            </a>
                        </li>';
    }
             $rows.= '      <li class="">
                            <a href="#datos" data-toggle="tab">
                                '.LABEL_webservices.'
                            </a>
                        </li>
                    </ul>


            <div id="term" about="'.$dataTerm->result->term->term_id.'" typeof="skos:Concept">
                <div class="tab-content">
                    <div class="tab-pane active" id="main">';

    if (isset($htmlTerm["results"]["breadcrumb"])) {
        $rows.=$htmlTerm["results"]["breadcrumb"];
    }


    if (! isset($htmlTerm["results"]["UF"])) {
        $htmlTerm["results"]["UF"]='';
    }
     $rows.= '              <div class="relation-body padbot">
                            <div id="altTerms">'
                                .$htmlTerm["results"]["UF"].'
                            </div>
                        </div>';


    if (strlen($htmlTerm["results"]["NOTES"]) > 0) {
          $rows.= '      <div class="relation panel">
                            <div id="notas" class="relation-body">
                                '.$htmlTerm["results"]["NOTES"].'
                            </div>
                        </div>';
    }

    if (isset($htmlTerm["results"]["BT"])) {
        $rows.= '          <div class="relation-body">
                            '.$htmlTerm["results"]["BT"].'
                        </div>';
    }

    if (! isset($htmlTerm["results"]["NT"])) {
        $htmlTerm["results"]["NT"]='';
    }
     $rows.= '              <div class="row">
                                <div class="relation-body col-md-5">'.$htmlTerm["results"]["NT"].'</div>
                            </div>';

    if (isset($htmlTerm["results"]["RT"])) {
        $rows.= '          <div class="relation-body">
                            '.$htmlTerm["results"]["RT"].'
                        </div>';
    }

     $rows.=  '</div>';

    if (! isset($htmlTerm["results"]["MAP"])) {
        $htmlTerm["results"]["MAP"] = '';
    }
    if (! isset($htmlTerm["results"]["LINKED"])) {
        $htmlTerm["results"]["LINKED"] = '';
    }
      $rows.=  '          <!-- mapeos -->
                    <div class="tab-pane" id="matches">
                        <h4>'.LABEL_TargetTerm.'</h4>
                        <div class="relation-body">
                            '.$htmlTerm["results"]["MAP"].'
                        </div>
                        <div class="relation-body">
                          '.$htmlTerm["results"]["LINKED"].'
                        </div>
                    </div>
                    '.HTMLtermSuggestionMenu(array("vocab_code"=>$_SESSION["v"],"term_id"=>(int) $term_id,"vocabularyMetadata"=>$vocabularyMetadata)).'
                    '.datosdeltermino(array("term_id"=>(int) $term_id,"vocabularyMetadata"=>$vocabularyMetadata,"vocab_code"=>$_SESSION["v"]));'
                </div>';
      
      $rows.=  '</div><!-- #term -->';
      /** if there are relations */
      if(isset($htmlTerm["data4vis"]["edges"])){
        $rows.=  ' <div class="card"><div id="graphterm"></div></div>';  
      }
      

      $rows.=  '</div> <! --#tabbable -->';
 
    return $rows;
}

function human_filesize($bytes, $decimals = 2)
{
    $sz = 'BKMGTP';
    $factor = floor((strlen($bytes) - 1) / 3);
    return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) .' '.@$sz[$factor];
}



function HTMLrss2home($code)
{
    global $CFG_URL_PARAM, $CFG_VOCABS;
    // Include SimplePie
    include_once('simplepie/autoloader.php');
    // Create a new instance of the SimplePie object
    $feed = new SimplePie();
    // Use the URL that was passed to the page in SimplePie
    $feed->set_feed_url($CFG_URL_PARAM["url_site"].'rss.php?v='.$code);

    if (@$CFG_URL_PARAM["cache_site"]) {
        $feed->set_cache_location($CFG_URL_PARAM["cache_site"]);
    } else {
        $feed->enable_cache(false);
    }
    $success = $feed->init();
    $feed->handle_content_type();
    $rows=' <div class="box box-voc '.$code.' '.$CFG_VOCABS[$code]["CAT"].'" id="vocabw_'.$code.'">
                <h4 class="widgettitulo">
                    <a href="'.
                        // $feed->get_link()
                        $CFG_URL_PARAM["url_site"].'index.php?v='.$code
                        .'" title="'.$feed->get_title().'">
                            '.$feed->get_title().'
                    </a>
                </h4>
                <p class="borde">
                    '.$feed->get_description().'
                </p>
                <p class="padd">
                    <strong>'.ucfirst(LABEL_lastChanges).'</strong>: ';
    $i = 0;
    foreach ($feed->get_items() as $item) {
        ++$i;
        if (($item->get_permalink()) && ($i<5)) {
            $rows.='<a href="' . $item->get_permalink() . '" title="'.$item->get_title().'">
                        '.$item->get_title().'
                    </a>; ';
        }
    }
    $rows.='        <a href="'.$CFG_URL_PARAM["url_site"].'index.php?task=fetchLast&amp;v='.$code.'" title="'.ucfirst(LABEL_lastChanges).'">...</a>
                </p>
            </div>';
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
<<<<<<< HEAD
=======

>>>>>>> 84c0b9b0b042da9954423285a2b83b4183995a97
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

        case 'CLASSIFFY':
            if (in_array('CLASSIFFY', $enable_modules)) {
                return true;
            }
            break;

        case 'VISUAL_VOCAB':
            if (in_array('VISUAL_VOCAB', $enable_modules)) {
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



/*  Información sobre el voc para pantallas chicas  */
function HTMLlittleinfo($params=array())
{
    GLOBAL $CFG_URL_PARAM;
    GLOBAL $CFG_SITE;
    setlocale(LC_ALL, 'es_AR');
    if (@$params["vocab_code"]) {
        $rows=' <p class="text-justify">
                    '.$params["vocabularyMetadata"]["title"].': '.$params["vocabularyMetadata"]["scope"].'
                </p>
                <div id="carousel2" class="carousel slide" data-ride="carousel">
                    <ol class="carousel-indicators">
                      <li data-target="#carousel2" data-slide-to="0" class="active"></li>
                      <li data-target="#carousel2" data-slide-to="1"></li>
                      <li data-target="#carousel2" data-slide-to="2"></li>
                    </ol>
                    <!-- Wrapper for slides -->
                    <div class="carousel-inner" role="listbox">
                        <div class="item active">
                            <div class="whitebox text-center">';
                                /*La última actualización fue en '.date_format(date_create_from_format('Y-m-d G:i:s', $params["vocabularyMetadata"]["lastMod"]), 'm/y').'. */
                                $rows.='<br><a href="'.$CFG_URL_PARAM["url_site"].'index.php?task=fetchLast&amp;v='.$params["vocab_code"].'" title="Últimas modificaciones de '.$params["vocabularyMetadata"]["title"].'"><span>¡Mirá las últimas modificaciones!</span></a>
                            </div>
                        </div>
                        <div class="item">
                            <div class="whitebox text-center">
                                <p class="statsnum">
                                    '.$params["vocabularyMetadata"]["cant_terms"].'
                                </p>
                                <p class="statstext">
                                    términos';
        if (isset($_SESSION["vocab"]["mail"]))
            $rows.='                ... &nbsp;&nbsp;
                                    <a href="'.$CFG_URL_PARAM["url_site"].'apps/suggest/index.php?v='.$params["vocab_code"].'" title="'.$params["vocabularyMetadata"]["title"].'"><span>¡Sugerí uno!</span></a>';
        $rows.='                </p>
                            </div>
                        </div>
                        <div class="item">
                            <div class="whitebox text-right">
                                <p class="text-center">Recibí las actualizaciones de este vocabulario... </p>
                                <!-- Button trigger modal -->
                                <button type="button" class="btn btn-link btn-sm" data-toggle="modal" data-target="#myModal">
                                    <span>¡Suscribíte!</span>
                                </button>
                            </div>
                        </div>
                    </div>
                    <!-- Modal -->
                    <div class="modal fade" id="myModal" tabindex="-1" role="note" aria-hidden="true">
                        <div class="modal-dialog modal-sm">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close btn-lg" data-dismiss="modal" aria-label="Cerrar ventana"><span aria-hidden="true">&times;</span></button>
                                    <h4 class="modal-title" id="myModalLabel">Suscripción al RSS</h4>
                                </div>
                                <div class="modal-body">
                                    <p class="text-justify">RSS es una forma muy sencilla para recibir información actualizada sobre determinadas páginas web, sin necesidad de visitarlas una a una.</p>
                                    <p class="text-justify">Para poder recibir noticias RSS se necesita un lector RSS como <a href="http://digg.com/reader">Digg Reader</a> o <a href="https://www.inoreader.com/">Inoreader</a>.</p>
                                    <p class="text-center"><a href="'.$CFG_URL_PARAM["url_site"].'rss.php?v='.$params["vocab_code"].'" title="Suscripción al RSS">RSS de este vocabulario</a></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>';
    }
    return $rows;
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



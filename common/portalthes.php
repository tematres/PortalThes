<?php
    if ( ! defined('WEBTHES_ABSPATH'))
        die("no access");
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

function HTMLestilosyjs()
{
    GLOBAL $CFG_URL_PARAM;
    $rows='
    <!-- js -->
    <script src="'.$CFG_URL_PARAM["url_site"].'js/jquery-2.1.0.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
    <script src="'.$CFG_URL_PARAM["url_site"].'vendor/jquery.autocomplete.min.js"></script>
    <script src="'.$CFG_URL_PARAM["url_site"].'js/jquery.mockjax.js"></script>
    <script src="'.$CFG_URL_PARAM["url_site"].'vendor/jquery.validate.min.js"></script>
    <script src="'.$CFG_URL_PARAM["url_site"].'vendor/masonry.pkgd.min.js"></script>
    <script src="'.$CFG_URL_PARAM["url_site"].'vendor/imagesloaded.pkgd.min.js"></script>
    <script src="'.$CFG_URL_PARAM["url_site"].'js/tree.jquery.js"></script>
    <script src="'.$CFG_URL_PARAM["url_site"].'js/js.php"></script>
    <script src="https://www.google.com/recaptcha/api.js"></script>
    <!-- css -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="'.$CFG_URL_PARAM["url_site"].'css/jqtree.css">
    <link rel="stylesheet" href="'.$CFG_URL_PARAM["url_site"].'css/jquery.autocomplete.css">
    <link rel="stylesheet" href="'.$CFG_URL_PARAM["url_site"].'css/thes.css">
    <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Open+Sans|Syncopate">
    <link rel="stylesheet" href="http://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!--[if IE]>
    <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->';

    $rows.='<link rel="alternate" href="'.$CFG_URL_PARAM["url_site"].'rss.php?v='.$_SESSION["vocab"]["CODE"].'" title="RSS '.$_SESSION["vocab"]["title"].'" />';

return $rows;
}

function HTMLmeta($vocabularyMetadata,$extraTitle="")
{
    GLOBAL $CFG_URL_PARAM;

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



function HTMLformSearch(){

    GLOBAL $CFG_URL_PARAM;

    $rows.='<div class="container">
    <div class="row">
        <form name="searchForm" method="get" id="searchform" action="'.$CFG_URL_PARAM["url_site"].'">
        <div class="col-md-8">
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
        </div>
        <input type="hidden" id="task" name="task" value="search" />
        <input type="hidden" id="v" name="v" value="'.$_SESSION["vocab"]["code"].'" />
        </form>
    </div>
</div>';

return $rows;
}


/*  Presentación de menú global  */
function HTMLglobalMenu($params=array())
{
    GLOBAL $CFG_URL_PARAM;
    $rows=' <nav class="navbar navbar-inverse navbar-fixed-top bnm_navbar">
                <div class="container">
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <a class="navbar-brand completo" href="'.$CFG_URL_PARAM["url_site"].'index.php?v='.$_SESSION["vocab"]["code"].'" title="'.$_SESSION["vocab"]["title"].'">'.$_SESSION["vocab"]["title"].'</a>
                        <a class="navbar-brand breve" href="'.$CFG_URL_PARAM["url_site"].'index.php" title="'.$_SESSION["vocab"]["title"].'"></a>
                    </div>
                    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                        <ul class="nav navbar-nav navbar-right">
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">'.ucfirst(LABEL_tools).'<b class="caret"></b></a>
                                <ul class="dropdown-menu" role="menu">
                                    <li><a href="'.$CFG_URL_PARAM["url_site"].'apps/autoridades/index.php">'.ucfirst(BULK_TERMS_REVIEW_title).'</a></li>
                                    <li><a href="'.$CFG_URL_PARAM["url_site"].'apps/suggest/index.php">'.ucfirst(SUGGESTION_SERVICE_title).'</a></li>
                                    <li><a href="'.$CFG_URL_PARAM["url_site"].'index.php?task=fetchLast&v='.$_SESSION["vocab"]["code"].'">Consultar últimas modificaciones</a></li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>';
    return $rows;
}

/*  Presentación de footer global  */
function HTMLglobalFooter($params=array())
{
    GLOBAL $CFG_URL_PARAM;

    $rows=' <div style="clear: both;"></div>
            <footer class="row">
                <section class="col-xs-12 col-sm-4">
                    <h5>'.ucfirst(LABEL_tools).'</h5>
                    <ul class="list-unstyled">
                        <li>
                            <a href="'.$CFG_URL_PARAM["url_site"].'apps/autoridades/index.php">
                                '.ucfirst(BULK_TERMS_REVIEW_title).'
                            </a>
                        </li>
                        <li>
                            <a href="'.$CFG_URL_PARAM["url_site"].'apps/suggest/index.php">
                                '.ucfirst(SUGGESTION_SERVICE_title).'
                            </a>
                        </li>
                        <li>
                            <a href="'.$CFG_URL_PARAM["url_site"].'index.php?task=fetchLast&v='.$_SESSION["vocab"]["code"].'">
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
                        <li>'.$CFG_SITE["contact_info_line1"].'</li>
                        <li>'.$CFG_SITE["contact_info_line2"].'</li>
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
    return $rows;
}

/*  Presentación de menú contextual global  */
function HTMLglobalContextualMenu($params=array())
{
    GLOBAL $CFG_URL_PARAM;
    GLOBAL $CFG_SITE;

    if (@$params["vocab_code"]) {
        $rows=' <h1>
                    '.$params["vocabularyMetadata"]["title"].'
                </h1>
                <p class="autor text-right">
                    '.LABEL__attrib.' '.$params["vocabularyMetadata"]["author"].'
                </p>
                <p class="text-justify ocultar">
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

        if ($_SESSION["vocab"]["mail"])
            $rows.='<a href="'.$CFG_URL_PARAM["url_site"].'apps/suggest/index.php?v='.$params["vocab_code"].'" title="'.LABELFORM_newSuggest.' '.$params["vocabularyMetadata"]["title"].'"><span>'.LABELFORM_newSuggest.'</span></a>';

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

/*  Información sobre el voc para pantallas chicas  */
function littleinfo($params=array())
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
                                /*La última actualización fue en '.date_format(date_create_from_format('Y-m-d G:i:s', $params["vocabularyMetadata"]["lastMod"]), 'm/y').'. */$rows.='<br><a href="'.$CFG_URL_PARAM["url_site"].'index.php?task=fetchLast&amp;v='.$params["vocab_code"].'" title="Últimas modificaciones de '.$params["vocabularyMetadata"]["title"].'"><span>¡Mirá las últimas modificaciones!</span></a>
                            </div>
                        </div>
                        <div class="item">
                            <div class="whitebox text-center">
                                <p class="statsnum">
                                    '.$params["vocabularyMetadata"]["cant_terms"].'
                                </p>
                                <p class="statstext">
                                    términos';
        if ($_SESSION["vocab"]["mail"])
            $rows.='                ... &nbsp;&nbsp;
                                    <a href="'.$CFG_URL_PARAM["url_site"].'apps/suggest/index.php?v='.$params["vocab_code"].'" title="'.LABEL_sendSuggest.' '.$params["vocabularyMetadata"]["title"].'"><span>¡Sugerí uno!</span></a>';
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

/*  Presentación de menú contextual de término  */
function datosdeltermino($params=array())
{
    GLOBAL $CFG_URL_PARAM;
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
function HTMLlistaVocabularios($array_vocabs,$selected_vocab="")
{

    GLOBAL $CFG_URL_PARAM;
    $rows='';
    foreach ($array_vocabs as $k => $v) {
        if ($k!=='default_vocab'){
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
    GLOBAL $CFG_URL_PARAM;
    $rows='';
    foreach ($array_vocabs as $k => $v) {
        $rows.=HTMLrss2home($v["CODE"]);
    }
    return $rows;
}


function HTMLcrawlerContentTab($data,$vocab_code,$array_plugins=array())
{
    return ;
}

function selectVocabulario($array_vocabs,$selected)
{
    if(is_array($array_vocabs[$selected])) {
        return $array_vocabs[$selected]["URL_BASE"];
    } else {
        return $array_vocabs[$selected][$dft];
    }
}

/*  Presentación de menú de sugerencias  */
function HTMLtermSuggestionMenu($params=array())
{
    GLOBAL $CFG_URL_PARAM;
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



function HTMLtermDetaills($htmlTerm,$dataTerm,$vocabularyMetadata){

    $term= (string) FixEncoding($dataTerm->result->term->string);
    $term_id= (int) $dataTerm->result->term->term_id;

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
            <div id="term" about="'.$URL_BASE.$dataTerm->result->term->term_id.'" typeof="skos:Concept">
                <div class="tab-content">
                    <div class="tab-pane active" id="main">';

    if (isset($htmlTerm["results"]["breadcrumb"]))
      $rows.=            $htmlTerm["results"]["breadcrumb"];
    if (isset($htmlTerm["results"]["BT"])) {
      $rows.= '          <div class="relation-body">
                            '.$htmlTerm["results"]["BT"].'
                        </div>';
    }
    if (isset($htmlTerm["results"]["termdata"])) {
      $rows.= '          <div>
                            <h2>
                                '.$htmlTerm["results"]["termdata"].'
                            </h2>
                        </div>';
    }
    if ( ! isset($htmlTerm["results"]["UF"]))
        $htmlTerm["results"]["UF"]='';
     $rows.= '              <div class="relation-body padbot">
                            <div>'
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
    if ( ! isset($htmlTerm["results"]["NT"]))
        $htmlTerm["results"]["NT"]='';
     $rows.= '              <div class="row">
                            <div class="relation-body col-md-5">
                              '.$htmlTerm["results"]["NT"].'
                            </div>';
    if (isset($htmlTerm["results"]["RT"])) {
      $rows.= '              <div class="col-md-4 pull-right" id="box-rel">
                                <div class="row">
                                    <em>Términos relacionados</em>
                                    <div class="relation-body">
                                        '.$htmlTerm["results"]["RT"].'
                                    </div>
                                </div>
                            </div>';
    }
      $rows.=  '              </div>
                    </div>';
    if ( ! isset($htmlTerm["results"]["MAP"]))
        $htmlTerm["results"]["MAP"] = '';
    if ( ! isset($htmlTerm["results"]["LINKED"]))
        $htmlTerm["results"]["LINKED"] = '';
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
                    '.HTMLtermSuggestionMenu(array("vocab_code"=>$v,"term_id"=>(int) $term_id,"vocabularyMetadata"=>$vocabularyMetadata)).'
                    '.datosdeltermino(array("term_id"=>(int) $term_id,"vocabularyMetadata"=>$vocabularyMetadata,"vocab_code"=>$v));'
                </div>';
      $rows.=  '</div><!-- #term -->';
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
    GLOBAL $CFG_URL_PARAM, $CFG_VOCABS;
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
    GLOBAL $CFG_VOCABS;
    GLOBAL $CFG;
    $v=(in_array($vocab_code,$CFG_VOCABS)) ? $vocab_code : $CFG["DEFVOCAB"];
    return $v;
}

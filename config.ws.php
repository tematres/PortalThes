<?php
/*
*      config.ws.php
*
*      Copyright 2018 diego <tematres@r020.com.ar>
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
*
********************************************************************************************
CONFIGURATION
********************************************************************************************/
$URL_BASE = '';
$lang_tematres = '';
$CFG["ENCODE"] = 'UTF-8';


$lang_tematres = "es_AR" ;

//  VOCABULARIOS == vocabulary to use
$CFG_VOCABS["1"]["CODE"]       	= "1"; // internal and arbitrary code to identify each vocab. This code must to be the same used in $CFG_VOCABS[""] array.
$CFG_VOCABS["1"]["URL_BASE"]    = 'http://localhost/tematres/TemaTres-Vocabulary-Server/vocab/services.php'; // URL of the tematres web services provider
$CFG_VOCABS["1"]["ALPHA"]      	= array("A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z");; // array of char used to alphabetic global menu navigation. For example: array('a','b','c','d')
$CFG_VOCABS["1"]["SHOW_TREE"]  	= 1; // show main tree navigation. Default=1
$CFG_VOCABS["1"]["MODULES"]     = array("CLASSIFFY","BULK_TERMS_REVIEW","SUGGESTION_SERVICE");//Enable modules: CLASSIFFY, BULK_TERMS_REVIEW, SUGGESTION_SERVICE

## Config data about THIS WEB PORTAL
$CFG_URL_PARAM["url_site"]      ='http://localhost/tematres/portalthes/';
$CFG_URL_PARAM["title_site"]    = 'Portal de Vocabularios ';
$CFG_URL_PARAM["site_info"]='Lorem ipsum 0';
$CFG_URL_PARAM["site_info_line1"]='Lorem ipsum 1';
$CFG_URL_PARAM["site_info_line2"]='Lorem ipsum 2';

$CFG["DEFVOCAB"]                = "1";                                  //Default vocab
$CFG["MIN_CHAR_SEARCH"]         = 2;                                    //search strings with more than x chars
$CFG["LOCAL_NOTES"]["DEF"]      = "Nota de definición";


//$CFG_URL_PARAM["fetchTerm"]		= 'index.php?task=fetchTerm&amp;arg=';
//$CFG_URL_PARAM["search"]		= 'index.php?task=search&amp;arg=';
//$CFG_URL_PARAM["letter"]		= 'index.php?task=letter&amp;arg=';
$CFG_URL_PARAM["URIfetchTerm"]	= 'fetchTerm/';

$CFG_URL_PARAM["fetchTerm"]		= 'term/';
$CFG_URL_PARAM["search"]		= 'search/';
$CFG_URL_PARAM["letter"]		= 'letter/';



/*  In almost cases, you don't need to touch nothing here!! */
error_reporting(E_ERROR | E_WARNING | E_PARSE);
// error_reporting(E_ALL);

if ( !defined('WEBTHES_ABSPATH') )
	/** Use this for version of PHP < 5.3 */
	define('WEBTHES_ABSPATH', dirname(__FILE__));

    require_once("common/lang/$lang_tematres.php");
	include_once('common/vocabularyservices.php');
	include_once('common/portalthes.php');

  	session_start();
	
//get for valid vocab_code provided by GET
    $v=(isset($_GET["v"])) ? fetchVocabCode($_GET["v"]) : $CFG_VOCABS["1"]["CODE"];

//check if vocab_code is valid.
    $v=(is_array($CFG_VOCABS[$v])) ? $v : $CFG_VOCABS["1"]["CODE"];
	
    $_SESSION["vocab"]=$CFG_VOCABS[$v];	
	
    $URL_BASE=$CFG_VOCABS[$v]["URL_BASE"];

        $vocabularyMetadata=fetchVocabularyMetadata($URL_BASE);
        $_SESSION["vocab"]["mail"]  = ((strlen($vocabularyMetadata["adminEmail"])>0)) ? $vocabularyMetadata["adminEmail"] : false ;
        $_SESSION["vocab"]["title"] = (string) $vocabularyMetadata["title"];
        $_SESSION["vocab"]["author"] = (string) $vocabularyMetadata["author"];
        $_SESSION["vocab"]["scope"] = xmlentities((string) $vocabularyMetadata["scope"]);
        $_SESSION["vocab"]["lang"] = (string) $vocabularyMetadata["lang"];
        $_SESSION["vocab"]["keywords"] = (string) $vocabularyMetadata["keywords"];
        $_SESSION["vocab"]["lastMod"] = (string) $vocabularyMetadata["lastMod"];
        $_SESSION["vocab"]["cant_terms"] = (string) $vocabularyMetadata["cant_terms"];

?>

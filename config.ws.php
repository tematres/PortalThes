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
session_start();

<<<<<<< HEAD
error_reporting(E_ERROR | E_WARNING);
//error_reporting(E_ALL);
ini_set('display_errors', 0);
=======
/** 
 * VOCABULARIOS == vocabulary to use
 * Internal and arbitrary code to identify each vocab. This code must to be the same used in $CFG_VOCABS["x"] array.  
*/
$CFG_VOCABS["1"]["CODE"]        = "1";
$CFG_VOCABS["1"]["URL_BASE"]    = 'http://vocabularios.caicyt.gov.ar/flacso/'; // URL of the tematres instance
$CFG_VOCABS["1"]["URL_BASE"]    = 'http://localhost/mole.guru/colombia.rama/vocab/'; // URL of the tematres instance
$CFG_VOCABS["1"]["URL_BASE"]    = 'https://vocabularyserver.com/lab/saij/tsjcaba/'; // URL of the tematres instance
//$CFG_VOCABS["1"]["URL_BASE"]    = 'https://eduthes.cdc.qc.ca/vocab/'; // URL of the tematres instance
>>>>>>> 84c0b9b0b042da9954423285a2b83b4183995a97


/**
 *  Default vocab. index from the array vocabularies
 */
$CFG["DEFVOCAB"] = "1";

/**
 *  Enabled vocabs. index from the array vocabularies
 */
$CFG["VOCABS"] = array(1,2);

/** 
<<<<<<< HEAD
 * VOCABULARIOS == vocabulary to use
 * Internal and arbitrary code to identify each vocab. This code must to be the same used in $CFG_VOCABS["x"] array.  
*/
=======
 * Enable modules: CLASSIFFY, BULK_TERMS_REVIEW, SUGGESTION_SERVICE,COPY_CLICK
 */
$CFG_VOCABS["1"]["MODULES"]     = array("SUGGESTION_SERVICE","VISUAL_VOCAB","BULK_TERMS_REVIEW");//"VISUAL_VOCAB",


##
$CFG_VOCABS["2"]["CODE"]        = "2";
$CFG_VOCABS["2"]["URL_BASE"]    = 'http://localhost/tematres.import/kostype/en/'; // URL of the tematres instance
$CFG_VOCABS["2"]["ALPHA"]       = array("A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z");
$CFG_VOCABS["2"]["SHOW_TREE"]   = 1;
$CFG_VOCABS["2"]["MODULES"]     = array("SUGGESTION_SERVICE","BULK_TERMS_REVIEW");//"VISUAL_VOCAB",
>>>>>>> 84c0b9b0b042da9954423285a2b83b4183995a97

/* The default vocab (configurated in $CFG["DEFVOCAB"]). The web path will be the $CFG_URL_PARAM["url_site"] + /1/. For example http://localhost/tematres/portalthes/1/ */
$CFG_VOCABS["1"]["TITLE"]="Demo Tematres";
$CFG_VOCABS["1"]["CODE"]="1";
$CFG_VOCABS["1"]["URL_BASE"]="https://r020.com.ar/tematres/demo/";   // URL of the tematres instance
$CFG_VOCABS["1"]["MODULES"]= array("BULK_TERMS_REVIEW","CODE"); //Enable modules: BULK_TERMS_REVIEW, SUGGESTION_SERVICE;
$CFG_VOCABS["1"]["SHOW_TREE"]   = 1;    //Show main tree navigation. Default=1. 0 = "do not show" 
$CFG_VOCABS["1"]["ALPHA"]=array("A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z");   //Array of char used to alphabetic global menu navigation. For example: array('a','b','c','d') 

/*Example of second vocabulary in the same path.  The web path will be the $CFG_URL_PARAM["url_site"] + /2/. For example http://localhost/tematres/portalthes/2/ */
$CFG_VOCABS["2"]["CODE"]="2";
$CFG_VOCABS["2"]["URL_BASE"]="https://vocabularyserver.com/tadirah/pt/";   // URL of the tematres instance
$CFG_VOCABS["2"]["MODULES"]= array(); //Enable modules: BULK_TERMS_REVIEW, SUGGESTION_SERVICE;
$CFG_VOCABS["2"]["SHOW_TREE"]   = 1;    //Show main tree navigation. Default=1. 0 = "do not show" 
$CFG_VOCABS["2"]["ALPHA"]=array("A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z");   //Array of char used to alphabetic global menu navigation. For example: array('a','b','c','d') 

/** 
 * HTML encode
 */
$CFG["ENCODE"] = 'UTF-8';

/** 
 * Language of the interface
 */
$lang_tematres = "es_AR" ;

/** Config data about THIS WEB PORTAL
 * base URL to the portalthes implementation
 */
$CFG_URL_PARAM["url_site"]      ='http://localhost/tematres/portalthes/'; // Web path of the site
$CFG_URL_PARAM["name_site"]= 'Name site. See $CFG_URL_PARAM param in config.ws.php to to configure';
$CFG_URL_PARAM["title_site"]    = 'Title site. ';
$CFG_URL_PARAM["site_info"]='Little site info, line 0.';
$CFG_URL_PARAM["site_info_line1"]='Little site info, line 1. ';
$CFG_URL_PARAM["site_info_line2"]='Little site info, line 2. See $CFG_URL_PARAM param in config.ws.php to to configure';


/**
 *  Search strings with more than x chars
 */
$CFG["MIN_CHAR_SEARCH"] = 2;

/**
 *  Config local labels for custum notes. Use code of the note
 */
$CFG["LOCAL_NOTES"]["DEF"] = "Nota de definición";
$CFG["LOCAL_NOTES"]["NA"]  = "Nota de alcance";
$CFG["LOCAL_NOTES"]["NB"]  = "Nota bibliográfica";


/** 
 * Check HTTPS certificates
 */
$CFG["CHK_HTTPS"] = 0; //0=false ; 1=true


//require_once("config.uba.php");

/**  In almost cases, you don't need to touch nothing here!! */


/**
 * params for GET if you do not have enable mod_rewrite
 */
/*
$CFG_URL_PARAM["fetchTerm"]       = '&task=term&arg=';
$CFG_URL_PARAM["search"]      = '&task=search&arg=';
$CFG_URL_PARAM["letter"]      = '&task=letter&arg=';
$CFG_URL_PARAM["v"]       = 'index.php?v=';
*/

/** 
 * params for GET if you enable mod_rewrite
 */
$CFG_URL_PARAM["URIfetchTerm"]  = '/fetchTerm/';
$CFG_URL_PARAM["v"]         = '';
$CFG_URL_PARAM["fetchTerm"] = '/term/';
$CFG_URL_PARAM["search"]    = '/search/';
$CFG_URL_PARAM["letter"]    = '/letter/';
$CFG_URL_PARAM["topterms"]  = '/';


if (!defined('WEBTHES_ABSPATH')) {
    /** Use this for version of PHP < 5.3 */
    define('WEBTHES_ABSPATH', dirname(__FILE__));
}

    require_once("common/lang/$lang_tematres.php");
    include_once('common/vocabularyservices.php');
    include_once('common/portalthes.php');


    
//get for valid vocab_code provided by GET
    $v=(isset($_GET["v"])) ? $_GET["v"] : $_SESSION["v"];

    if(array2value($v,$_SESSION)!==$v){
    $_SESSION["vocab"]=$CFG_VOCABS[$v];
    $_SESSION["v"]=$v;
    }

//check if vocab_code is valid.
    $v=configValue(array2value("v",$_GET), $CFG["DEFVOCAB"], $CFG["VOCABS"]);
    
    $URL_BASE=$CFG_VOCABS[$v]["URL_BASE"].'services.php';
    

    $vocabularyMetadata=fetchVocabularyMetadata($URL_BASE);
    $_SESSION["vocab"]["mail"]  = ((strlen($vocabularyMetadata["adminEmail"])>0)) ? $vocabularyMetadata["adminEmail"] : false ;
    $_SESSION["vocab"]["title"] = (string) $vocabularyMetadata["title"];
    $_SESSION["vocab"]["author"] = (string) $vocabularyMetadata["author"];
    $_SESSION["vocab"]["scope"] = xmlentities((string) $vocabularyMetadata["scope"]);
    $_SESSION["vocab"]["lang"] = (string) $vocabularyMetadata["lang"];
    $_SESSION["vocab"]["keywords"] = (string) $vocabularyMetadata["keywords"];
    $_SESSION["vocab"]["lastMod"] = (string) $vocabularyMetadata["lastMod"];
    $_SESSION["vocab"]["cant_terms"] = (string) $vocabularyMetadata["cant_terms"];

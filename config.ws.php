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

/** Modo debug = 1 // debug mode = 1 */
$CFG["debugMode"] = "0";

if ($CFG["debugMode"]=='1') {
      ini_set('display_errors', 'On');
      error_reporting(E_ALL);
} else {
      ini_set('display_errors', false);
}
/**
 *  Default vocab. index from the array vocabularies
 */
$CFG["DEFVOCAB"] = "1";

/**
 *  Enabled vocabs. index from the array vocabularies
 */
$CFG["VOCABS"] = array(1,2,3);



/**
 * Google Analytics  GA_TRACKING_ID, Default false=0, to add Google Analytics replace 0 with your GA_TRACKING_ID
 */
$CFG["GA_TRACKING_ID"] = '0';


/**
 * VOCABULARIOS == vocabulary to use
 * Internal and arbitrary code to identify each vocab. This code must to be the same used in $CFG_VOCABS["x"] array.
*/

$CFG_VOCABS["1"]=array(
                "CODE"  => "1",
                "URL_BASE"  => "http://localhost/tematres/vocab/",   // URL of the tematres instance
                "CONFIG"   => array("SHOW_TREE_TERMS"=>1, "SHOW_CLOUD_TERMS"=>1, "HOME_GRID_SIZE"=>3,"FEATURE_NOTE"=>"NA"),    //Show main tree navigation. Default=1. 0 = "do not show"
                "ALPHA" => array("A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z")   //Array of char used to alphabetic global menu navigation. For example: array('a','b','c','d')
);


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
$CFG["LOCAL_NOTES"]["EX"]  = "Nota de ejemplo";


/**
 * Check HTTPS certificates
 */
$CFG["CHK_HTTPS"] = 0; //0=false ; 1=true


/**  In almost cases, you don't need to touch nothing here!! */


/**
 * params for GET if you do not have enable mod_rewrite
 */
/*
$CFG_URL_PARAM["fetchTerm"]       = '&task=term&arg=';
$CFG_URL_PARAM["search"]      = '&task=search&arg=';
$CFG_URL_PARAM["letter"]      = '&task=letter&arg=';
$CFG_URL_PARAM["v"]       = 'index.php?v=';
$CFG_URL_PARAM["topterms"]  = 'index.php?v=';
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
    $_SESSION["vocab"]["createDate"] = (string) $vocabularyMetadata["createDate"];
    $_SESSION["vocab"]["cant_terms"] = (string) $vocabularyMetadata["cant_terms"];

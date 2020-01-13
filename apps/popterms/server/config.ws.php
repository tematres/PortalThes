<?php
/*
*      config.ws.php
*
*      Copyright 2015 diego <tematres@r020.com.ar>
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

********************************************************************************************
CONFIGURATION
***************************************************************************************
*/
$URL_BASE='';
$lang_tematres='';
$CFG["ENCODE"]='UTF-8';

$CFG_POP["PATH"]='apps/popterms/server/';

//define accepted params from GET
$CFG["ENABLE_TASK"]=array("fetchTerm","search","letter","fetchLast");

//error_reporting(E_ERROR | E_WARNING | E_PARSE);
//ini_set("display_errors", 1);
// lang :
$lang_tematres = "es_AR" ;
//search strings with more than x chars
$CFG["MIN_CHAR_SEARCH"]=2;



/*
 * Servers configuration
 */

#$CFG_VOCABS[1]["ALIAS"]="SAIJ";
#$CFG_VOCABS[1]["URL_BASE"]="http://vocabularios.saij.gob.ar/saij/services.php";
#$CFG_VOCABS[1]["ALPHA"]=array("A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z");
/*
$CFG_VOCABS[2]["ALIAS"]="INAP";
$CFG_VOCABS[2]["URL_BASE"]="http://vocabularios.saij.gob.ar/inap/services.php";
$CFG_VOCABS[2]["ALPHA"]=array("A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z");

$CFG_VOCABS[3]["ALIAS"]="FAMILIA";
$CFG_VOCABS[3]["URL_BASE"]="http://vocabularios.saij.gob.ar/familia/services.php";
$CFG_VOCABS[3]["ALPHA"]=array("A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z");
*/

/*fetch params*/
session_start();


/*  In almost cases, you don't need to touch nothing here!!
 *  Absolute path to the directory where are located /common/include. 
 */

date_default_timezone_set('America/Buenos_Aires');

if ( !defined('WEBTHES_ABSPATH') )
	/** Use this for version of PHP < 5.3 */
	define('WEBTHES_ABSPATH', dirname(__FILE__).'/');

if ( !defined('WEBTHES_PATH') )
	/** Use this for version of PHP < 5.3 */
	define('WEBTHES_PATH', '');


if (!isset($_SESSION['_PARAMS'])){
		$_SESSION['_PARAMS']["target_x"] = $_GET["tx"];
		$_SESSION['_PARAMS']["vocab_id"] = loadVocabularyID($_GET["v"]);
		$_SESSION['_PARAMS']["URL_BASE"] = $CFG_VOCABS[$_SESSION['_PARAMS']["vocab_id"]]["URL_BASE" ].'services.php';
	}

	$URL_BASE=$_SESSION['_PARAMS']["URL_BASE"];

	//$CFG_URL_PARAM["fetchTerm"]='term/';
	$CFG_URL_PARAM["fetchTerm"]='index.php?task=fetchTerm&amp;arg=';
	$CFG_URL_PARAM["URIfetchTerm"]='fetchTerm/';
	$CFG_URL_PARAM["search"]='index.php?task=search&amp;arg=';
	$CFG_URL_PARAM["letter"]='index.php?task=letter&amp;arg=';



//div to copy term
function HTMLcopyTerm($term,$param=array())
{
	$_PARAMS=$_SESSION['_PARAMS'];
	
	if(count($_PARAMS)<2) return;
	if($term->isMetaTerm==1) return;

//	$string=addslashes((string) $term->string);
//	$string = htmlentities(str_replace("'", "\'", $term->string));
	$string = htmlspecialchars($term->string);
	
	$insert = ' onClick="return PopTermsWrite(\''.$string.'\',\''.$_PARAMS["target_x"].'\')" ';	
	
	$rows.='  <button type="button" class="btn btn-default btn-xs" '.$insert.'>';
	$rows.='<span class="glyphicon glyphicon-save" aria-hidden="true"></span>';
	//$rows.='	<span class="glyphicon glyphicon-import" aria-hidden="true"></span>';
	$rows.='</button>';
	
	return $rows;
}


?>
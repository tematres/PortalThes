<?php
if (checkModuleCFG('CLASSIFFY')!==true) {
    header("Location:../../index.php");
}
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
$lang = 'es_AR';

//ini_set('display_errors', 'On');
//error_reporting(E_ALL);
require_once("lang/$lang.php");

//maximum number of words allowed
$CFG['max_text_length']= 200;

//languaje stop words. es by default
$CFG["rake_lang"]=(in_array($_SESSION["vocab"]["lang"], array('es','en'))) ? $_SESSION["vocab"]["lang"] : 'es';

// min size of extracted keyword
$CFG["rake_minLenght"]=3;


// min score of extracted keyword
$CFG["rake_minScore"]=2;


//how much controled terms to suggest
$CFG["max_fetch_keywords"]=20;
//set the length of keywords you like
$CFG['min_word_length'] = 5;  //minimum length of single words
$CFG['min_word_occur'] = 2;  //minimum occur of single words

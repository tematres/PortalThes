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
*
********************************************************************************************
CONFIGURATION
********************************************************************************************/
$lang_suggest = $lang_tematres;

require_once("lang/$lang_suggest.php");

$CFG["MAX_TERMS4MASS_CTRL"]     = 300;                                  //Cantidad máxima de términos en control terminológico
?>

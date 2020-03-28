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

$lang_marc21 = 'es_AR';

require_once("lang/$lang_marc21.php");


//sub encabezamiento de materia $x

//URL for the vocab provider for Subdivisión de forma. Optional.
$CFG_MARC21["t750_v"]='https://vocabularyserver.com/bne/forma/services.php';

//URL for the vocab provider for  Subdivisión general (R). Optional.
$CFG_MARC21["t750_x"]='https://vocabularyserver.com/bne/subencabezamientos/services.php';

//URL for the vocab provider for $y - Subdivisión cronológica (R). Optional.
//$CFG_MARC21["t650_y"]='';

//URL for the vocab provider for  $z - Subdivisión Geográfica (R). Optional.
$CFG_MARC21["t750_z"]='http://vocabularios.educacion.gov.ar/admin/toponimos/services.php';

//$2 - Fuente del encabezamiento o término (NR). Optional.
$CFG_MARC21["t750_2"]='TEMATRES';


//sub tag selector
$CFG_MARC21["subtag"]='$';

?>

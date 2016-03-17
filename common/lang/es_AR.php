<?php
#   TemaTres : aplicación para la gestión de lenguajes documentales #       #
#                                                                        #
#   Distribuido bajo Licencia GNU Public License, versión 2 (de junio de 1.991) Free Software Foundation
#   #
###############################################################################################################
#
setlocale(LC_ALL, 'es_AR');
define("LANG","es");
define('MENU_Inicio','Inicio');
define('LABEL_Buscar','Buscar');
define('LABEL_Termino','Término');
define('LABEL_Relaciones','Relaciones');
define('LABEL_notes','Notas');
define('LABEL_NotaAlcance','Nota de alcance');
define('LABEL_NotaDefinicion','Nota de definición');
define('LABEL_NotaBibliografica','Nota bibliográfica');
define('LABEL_TargetTerm','Relaciones con otras ontologías');
define('MSG_ResultLetra','Letra');
define('LABEL_start','Términos que comienzan con');
define('LABEL_TERMINO_SUGERIDO','¿Quizás quiso decir');
define('MSG_ResultBusca','Término/s encontrados para la búsqueda');
define('TG_terminos','Términos más generales');
define('TE_terminos','Términos más específicos');
define('UP_terminos','Términos equivalentes');
define('TR_terminos','Términos relacionados');
define('LABEL_Abreviatura','Abreviatura');
define('LABEL_CODE','Notación');
define('USE_termino','USE');
define('LABEL_terms','términos');

define('LABEL_resources','Recursos');

define('LABEL_suggests','Sugerencias');
define('LABEL_webservices','Servicios web');
define('LABEL_URIterm','URI del término');
define('LABEL_contact','Contacto');
define('LABEL_showNewsTerm','ver cambios recientes');
define('LABEL_lastChanges','cambios recientes');
define('LABEL_tools','Herramientas');
define('LABEL__attrib','por ');

/*TERM SUGGESTION PLUGIN */
define('SUGGESTION_SERVICE_title','Sugerencia de términos y correcciones');
define('SUGGESTION_SERVICE_description','Esta herramienta permite proponer términos nuevos, modificaciones y términos alternativos o sinónimos. Por favor consigne una justificación y alguna fuente.');
define('LABEL_termSuggest','Sugerencias terminológicas');
define('LABEL_modSuggest','Sugerir una modificación');
define('LABEL_altSuggest','Sugerir un término alternativo');
define('LABEL_ntSuggest','Sugerir un término más específico');
define('LABELFORM_modSuggest','Proponer una modificación para el término ');
define('LABELFORM_ntSuggest','Proponer un término subordinado para ');
define('LABELFORM_altSuggest','Proponer un término alternativo para ');
define('LABELFORM_newSuggest','Proponer un término ');
define('LABELFORM_eqSuggest;','Proponer una traducción ');


/*BULK TERM ANALISIS PLUGIN */
define('BULK_TERMS_REVIEW_title','Servicio de análisis masivo de términos');
define('BULK_TERMS_REVIEW_description','Esta herramienta permite comparar un conjunto de términos, <strong>pueden ser hasta %s</strong>. Al finalizar la comparación se obtiene un informe en línea o descargable que indica:
términos coincidentes, términos no coincidentes, términos equivalentes (no preferidos), términos similares (variantes ortográficas).');
?>

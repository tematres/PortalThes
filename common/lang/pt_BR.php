<?php
#   TemaTres : aplicación para la gestión de lenguajes documentales #       #
#                                                                        #
#   Distribuido bajo Licencia GNU Public License, versión 2 (de junio de 1.991) Free Software Foundation
#   #
###############################################################################################################
#
setlocale(LC_ALL, 'pt_BR');
define("LANG","br");
define('MENU_Inicio','Inicio');
define('LABEL_Buscar','Buscar');
define('LABEL_Termino','Termo');
define('LABEL_Relaciones','Relações');
define('LABEL_notes','Notas');
define('LABEL_NotaAlcance','Nota de alcance');
define('LABEL_NotaDefinicion','Nota de definição');
define('LABEL_NotaBibliografica','Nota bibliográfica');
define('LABEL_TargetTerm','Relações com outras ontologias');
define('MSG_ResultLetra','Letra');
define('LABEL_start','Termos que começam com');
define('LABEL_TERMINO_SUGERIDO','Você quis dizer?');
define('MSG_ResultBusca','Termo(s) encontrados para a busca');
define('TG_terminos','Termos mais genéricos');
define('TE_terminos','Termos mais específicos');
define('UP_terminos','Termos equivalentes');
define('TR_terminos','Termos relacionados');
define('LABEL_Abreviatura','Abreviatura');
define('LABEL_CODE','Notação');
define('USE_termino','USE');
define('LABEL_terms','termos');

define('LABEL_resources','Recursos');

define('LABEL_suggests','Sugestões');
define('LABEL_webservices','Web services');
define('LABEL_URIterm','URI do termo');
define('LABEL_contact','Contato');
define('LABEL_showNewsTerm','ver alterações recentes');
define('LABEL_lastChanges','alterações recentes');
define('LABEL_tools','Ferramentas');
define('LABEL__attrib','por ');

/*TERM SUGGESTION PLUGIN */
define('SUGGESTION_SERVICE_title','Sugestões de termos e correções');
define('SUGGESTION_SERVICE_description','Esta ferramenta permite propor termos novos, alterações e termos alternativos ou sinônimos. Por favor inclua uma justificativa e alguma fonte.');
define('LABEL_termSuggest','Sugestões terminológicas');
define('LABEL_modSuggest','Sugerir uma alteração');
define('LABEL_altSuggest','Sugerir um termo alternativo');
define('LABEL_ntSuggest','Sugerir um termo mais específico');
define('LABELFORM_modSuggest','Propor uma alteração para o termo ');
define('LABELFORM_ntSuggest','Propor um termo subordinado para ');
define('LABELFORM_altSuggest','Propor um termo alternativo para ');
define('LABELFORM_newSuggest','Propor um termo ');
define('LABELFORM_eqSuggest;','Propor uma tradução ');


/*BULK TERM ANALISIS PLUGIN */
define('BULK_TERMS_REVIEW_title','Serviço de análise de termos em lote');
define('BULK_TERMS_REVIEW_description','Esta ferramenta permite comparar um conjunto de termos, <strong>podem ser até %s</strong>. Ao finalizar a comparação se obtém um relatório online ou descarregável que indica:
termos coincidentes, termos não coincidentes, termos equivalentes (não preferidos), termos similares (variantes ortográficas).');
?>

<?php
#   TemaTres: open source thesaurus management #       #
#                                                                        #
#   Distribuido bajo Licencia GNU Public License, versión 2 (de junio de 1.991) Free Software Foundation
#
###############################################################################################################
#
setlocale(LC_ALL, 'en_US');
define("LANG","en");
define("TR_acronimo","RT");
define("TE_acronimo","NT");
define("TG_acronimo","BT");
define("UP_acronimo","UF");


define('MENU_Inicio','Start');
define('LABEL_Vocabularios','ATCM Vocabulary server');
define('LABEL_Buscar','Search');
define('LABEL_Termino','Term');
define('LABEL_Relaciones','Relationships');
define('LABEL_notes','Notes');
define('LABEL_NotaAlcance','Scope Note');
define('LABEL_NotaDefinicion','Definition Note');
define('LABEL_NotaBibliografica','Bibliographic Note');
define('LABEL_TargetTerm','Relations with other ontologies');
define('MSG_ResultLetra','Letter');
define('LABEL_start','Terms beginning with');
define('LABEL_TERMINO_SUGERIDO','Did you mean');
define('MSG_ResultBusca','Term/s for search');
define('TG_terminos','Broader terms');
define('TE_terminos','More specific terms');
define('UP_terminos','Equivalent terms');
define('TR_terminos','Related Terms');
define('LABEL_Abreviatura','Abbreviation');
define('LABEL_CODE','Notation');
define('USE_termino','USE');
define('LABEL_resources','Resources');
define('LABEL_ATS_resources','Antarctic Treaty database');


//Labels suggest form
define('LABEL_sendSuggest','Send a suggestion');
define('LABEL_termSuggest','Terminology suggestions');
define('LABEL_modSuggest','Suggest a change');
define('LABEL_altSuggest','Suggest an alternative term');
define('LABEL_ntSuggest','Suggest a more specific term');
define('LABELFORM_modSuggest','Suggest a change to the term');
define('LABELFORM_ntSuggest','Suggest a subordinate term for');
define('LABELFORM_altSuggest','Suggest an alternative term for');
define('LABELFORM_newSuggest','Suggest a term');
define('LABELFORM_eqSuggest;','Suggest a translation');
define('LABEL_suggest','Suggestion');
define('LABELFORM_note','Why should this term be included?');
define('LABELFORM_name','First and last name');
define('LABELFORM_mail','E-mail address');
define('LABELFORM_captcha1','Please enter the characters you see in the image');
define('LABELFORM_captcha2','This is not case sensitive');
define('LABELFORM_send','Send');
define('LABEL_suggestFor','Suggestions for');
define('BT_term','Broader term');
define('LABEL_BTlegend','It will be subject to the selected term');
define('LABEL_ERROR_duplicateTerm','Existing term');
define('LABEL_ERROR_name','Please enter your name');
define('LABEL_ERROR_note','Please enter a comment');
define('LABEL_ERROR_mail','Please enter your email address');
define('LABEL_ERROR_captcha','Please check the characters (Captcha)');
define('LABEL_ERROR_field','This is a mandatory field');
define('MAILSGS_subject','Vocabulary suggestion');
define('MAILSGS_salute1','Thank you very much for your suggestion');
define('MAILSGS_salute2','The team responsible for  %s will contact you shortly.');
?>

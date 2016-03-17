<?php

/*   http://jqueryvalidation.org/
http://getbootstrap.com/2.3.2/base-css.html#buttons
http://localhost/test/jQuery-Autocomplete-master/
Funciones espcíficas de TemaTres Suggest Form
Datos de definición del vocabulario   */

function getTemaTresData($tematres_uri,$task="fetchVocabularyData",$arg="")
{
    if ( ! $arg) {
        return getURLdata($tematres_uri.'?task=fetchVocabularyData');
    } else {
        return getURLdata($tematres_uri.'?task='.$task.'&arg='.$arg);
    }
}

//Suggest term, with note, with TT term or BT.
//If do no have BT, must select TT.
/*  Param: vocab_code, string to term (optional), BT (optional)  */
function formSuggestTerm($tematres_uri,$params=array())
{
    GLOBAL $CFG_URL_PARAM;
    GLOBAL $CFG;
    GLOBAL $CFG_SGS;

    $ref_term_id=((int) $params["term_id"]>0) ? $params["term_id"] : 0;
    if ($ref_term_id == 0) {
        $params["t_relation"]=='';
    }
    switch ($params["t_relation"]) {
        case 'UF':
            $termData=getURLdata($tematres_uri.'?task=fetchTerm&arg='.$ref_term_id);
            $ref_term_id=$termData->result->term->term_id;
            $task=$params["t_relation"];
            $label=LABEL_altTermFor;
            break;
        case 'EQ':
            $termData=getURLdata($tematres_uri.'?task=fetchTerm&arg='.$ref_term_id);
            $ref_term_id=$termData->result->term->term_id;
            $task=$params["t_relation"];
            $label=LABEL_tradTermFor;
            break;
        case 'NT':
            $termData=getURLdata($tematres_uri.'?task=fetchTerm&arg='.$ref_term_id);
            $ref_term_id=$termData->result->term->term_id;
            $task=$params["t_relation"];
            $label=LABEL_narrowerTermFor;
            break;
        case 'modT':
            $termData=getURLdata($tematres_uri.'?task=fetchTerm&arg='.$ref_term_id);
            $ref_term_id=$termData->result->term->term_id;
            $task=$params["t_relation"];
            $label=LABEL_modTerm;
            break;
        default:
            $termData=getURLdata($tematres_uri.'?task=fetchTopTerms');
            $ref_term_id=0;
            $task='term';
            $label=LABEL_newTermFor;
            break;
    }
    if ($termData->resume->cant_result > 0) {
        $rows.='    <form id="suggest-form" class="form-horizontal" action="index.php?v='.$params["v"].'&term_id='.$ref_term_id.'#resultados" method="post">
                        <fieldset>';
        if ($task=='term') {
            $rows.='        <legend align="right">
                                '.ucfirst($label).'
                            </legend>
                            <div class="form-group has-feedback">
                                <div class="input-group">
                                    <span class="input-group-addon">'.ucfirst(BT_term).'</span>
                                    <select name="term_id" class="form-control" id="term_id"/>
                                        <option value="'.ucfirst(LABEL_selectTopTerm).'" selected disabled hidden>
                                            '.ucfirst(LABEL_selectTopTerm).'
                                        </option>';
            foreach ($termData->result->term as $value) {
            $rows.='                    <option value="'.(int) $value->term_id.'">
                                            '.(string) $value->string.'
                                        </option>';
            }
            $rows.='                </select>
                                    <span class="glyphicon form-control-feedback" id="term_id1"></span>
                                </div>
                            </div>';
        } else {
            $rows.='        <input type="hidden" id="term_id" name="term_id" value="'.$ref_term_id.'" />
                            <legend align="right">
                                '.ucfirst($label).'
                                <strong><a href="'.$CFG_URL_PARAM["url_site"].'index.php?task=fetchTerm&amp;arg='.$ref_term_id.'&amp;v='.$params["v"].'">'.$termData->result->term->string.'</a></strong>
                            </legend>
                            <input type="hidden" id="ref_term_id" name="ref_term_id" value="'.$ref_term_id.'"> ';
        }
        $rows.='            <div class="form-group has-feedback">
                                <div class="input-group '.$css_error.'">
                                    <span class="input-group-addon"><i class="fa fa-lightbulb-o"></i></span>
                                    <input class="form-control" type="text" name="suggest_string" value="'.$params["suggest_string"].'" id="suggest_string" placeholder="'.ucfirst(LABEL_suggestedTerm).'">
                                    <span class="glyphicon form-control-feedback" id="suggest_string1"></span>';
        $rows.= $params["error"]["_term"]["div"];
        $rows.= '               </div>
                                <span id="error_suggest_term" style="font-weight: bold; color:red;" class="help-inline"></span>
                            </div>
                            <div id="selction-ajax"></div>
                            <div class="form-group has-feedback">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-info"></i></span>
                                    <textarea class="form-control" placeholder="'.ucfirst(LABEL_justificationNote).'" rows="5" name="suggest_note" id="suggest_note">'.$params["suggest_note"].'</textarea>
                                    <span class="glyphicon form-control-feedback" id="suggest_note1"></span>';
        $rows.=$params["error"]["_note"]["div"];
        $rows.='                </div>
                            </div>
                            <div class="form-group has-feedback">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-book"></i></span>
                                    <textarea class="form-control" placeholder="'.ucfirst(LABEL_sourceNote).'" rows="5" name="suggest_source" id="suggest_source">'.$params["suggest_source"].'</textarea>
                                    <span class="glyphicon form-control-feedback" id="suggest_source1"></span>';
        $rows.='                </div>
                            </div>

                            <div class="form-group has-feedback">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                    <input class="form-control" type="text" name="suggest_name" value="'.$params["suggest_name"].'" id="suggest_name" placeholder="'.ucfirst(LABEL_personalName).'">
                                    <span class="glyphicon form-control-feedback" id="suggest_name1"></span>';
        $rows.=$params["error"]["_name"]["div"];
        $rows.='                </div>
                            </div>
                            <div class="form-group has-feedback">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-at"></i></span>
                                    <input class="form-control" type="text" name="suggest_mail" id="suggest_mail" placeholder="'.ucfirst(LABEL_mail).'" value="'.$params["suggest_mail"].'">
                                    <span class="glyphicon form-control-feedback" id="suggest_mail1"></span>
                                </div>
                            </div>
                            <div class="g-recaptcha" id="recaptcha" data-sitekey="'.$CFG_SGS["sitekey_google_captcha"].'"></div>
                            <div>
                                <input type="submit" id="parse_text" class="col-md-2 btn btn-primary pull-right" value="'.ucfirst(LABELFORM_send).'" />
                                <input type="hidden" id="task" name="task" value="'.$task.'" />
                            </div>
                            '.$params["error"]["_captcha"]["div"].'
                        </fieldset>
                    </form>
                    <script id="suggest_ctrl" type="text/javascript">
                        $(document).ready(function() {
                            $("#suggest-form").validate({
                                rules: {
                                    term_id: {required: true},
                                    suggest_string: {required: true},
                                    suggest_note: {required: true, minlength: 5},
                                    suggest_source: {required: true, minlength: 10},
                                    suggest_name: {required: true, minlength: 5},
                                    suggest_mail: {required: true, email: true}
                                },
                                highlight: function(element) {
                                    var id_attr = "#" + $( element ).attr("id") + "1";
                                    $(element).closest(".form-group").removeClass("has-success").addClass("has-error");
                                    $(id_attr).removeClass("glyphicon-ok").addClass("glyphicon-remove");
                                },
                                unhighlight: function(element) {
                                    var id_attr = "#" + $( element ).attr("id") + "1";
                                    $(element).closest(".form-group").removeClass("has-error").addClass("has-success");
                                    $(id_attr).removeClass("glyphicon-remove").addClass("glyphicon-ok");
                                },
                                errorElement: "span",
                                errorClass: "help-block",
                                errorPlacement: function(error, element) {
                                }
                            });
                        });
                    </script>';
    }//end of if termData
    return $rows;
}

function evalSuggestForm($tematres_uri,$params=array())
{

    GLOBAL $CFG_SGS;
    $errorData = array();
    // $securimage = new Securimage();
    //controles
    if (strlen($params["suggest_string"]) > 0) {
        $errorData["flag_task"] = 1;
        // $suggested_term=getURLdata($tematres_uri.'?task=fetch&arg='.urlencode($params["suggest_string"]));
        // if ($suggested_term->resume->cant_result > 0) {
        //     $errorData["_term"]["flag_task"] = 0;
        //     $errorData["_term"]["div"] = '<span id="error_suggest" style="font-weight: bold; color:red;" class="help-inline">Término existente</span>';
        //     $errorData["flag_task"] = 0;
        // }
        // if (strlen($params["suggest_name"]) < 5) {
        //     $errorData["_name"]["flag_task"] = 0;
        //     $errorData["_name"]["div"] = '<span id="error_suggest" style="font-weight: bold; color:red;" class="help-inline">Por favor consigne su nombre</span>';
        //     $errorData["flag_task"] = 0;
        // }
        // if (strlen($params["suggest_note"]) < 5) {
        //     $errorData["_note"]["flag_task"] = 0;
        //     $errorData["_note"]["div"] = '<span id="error_suggest" style="font-weight: bold; color:red;" class="help-inline">Por favor consigne una nota</span>';
        //     $errorData["flag_task"] = 0;
        // }
        // if (strlen($params["suggest_mail"]) < 5) {
        //     $errorData["_mail"]["flag_task"] = 0;
        //     $errorData["_mail"]["div"] = '<span id="error_suggest" style="font-weight: bold; color:red;" class="help-inline">Por favor consigne su correo electrónico</span>';
        //     $errorData["flag_task"] = 0;
        // }
        // if ($securimage->check($_POST['ct_captcha']) == false) {
        //     $errorData["_captcha"]["flag_task"] = 0;
        //     $errorData["_captcha"]["div"] = '<span id="error_suggest" style="font-weight: bold; color:red;" class="help-inline">  Por favor revise el texto de verificación</span>';
        //     $errorData["flag_task"] = 0;
        // }
        if (isset($_POST['g-recaptcha-response'])) {
            // RECAPTCHA SETTINGS
            $captcha = $_POST['g-recaptcha-response'];
            $key = $CFG_SGS["key_google_captcha"];
            $url = 'https://www.google.com/recaptcha/api/siteverify';
            // RECAPTCH RESPONSE
            $recaptcha_response = file_get_contents($url.'?secret='.$key.'&response='.$captcha);
            $data = json_decode($recaptcha_response);

            if (isset($data->success) &&  $data->success === true) {
            // code goes here
            }
            else {
                $errorData["flag_task"] = 0;
                $errorData["_captcha"]["div"] = '<span id="error_suggest" style="font-weight: bold; color:red;" class="help-inline">  Por favor revise el texto de verificación</span>';
            }
        }
    } else {
        $errorData["_term"]["flag_task"] = 0;
        $errorData["_term"]["div"] = '<span id="error_suggest" style="font-weight: bold; color:red;" class="help-inline">  Este campo es obligatorio</span>';
        $errorData["flag_task"] = 0;
    }
    return $errorData;
}

function storeSuggestion($vocabularyMetaData,$params=array())
{
    if (is_array($params)) {
        $session_name = md5($params["suggest_mail"].time());
        $_SESSION[$session_name] = $params;
    }
    return $session_name;
}

function redactSuggestion($vocabularyMetaData,$session_name)
{
    GLOBAL $CFG_URL_PARAM;
    GLOBAL $CFG;
    $tematres_uri=$vocabularyMetaData->result->uri.'services.php';
    $params=$_SESSION[$session_name];
    $ref_term_id=((int) $params["term_id"]>0) ? $params["term_id"] : 0;
    switch ($params["t_relation"]) {
        case 'UF':
            $termData=getURLdata($tematres_uri.'?task=fetchTerm&arg='.$ref_term_id);
            $ref_term_id=$termData->result->term->term_id;
            $label=ucfirst(LABEL_altTermFor).' ';
            break;
        case 'EQ':
            $termData=getURLdata($tematres_uri.'?task=fetchTerm&arg='.$ref_term_id);
            $ref_term_id=$termData->result->term->term_id;
            $label=ucfirst(LABEL_tradTermFor).' ';
            break;
        case 'NT':
            $termData=getURLdata($tematres_uri.'?task=fetchTerm&arg='.$ref_term_id);
            $ref_term_id=$termData->result->term->term_id;
            $label=ucfirst(LABEL_narrowerTermFor).' ';
            break;
        case 'modT':
            $termData=getURLdata($tematres_uri.'?task=fetchTerm&arg='.$ref_term_id);
            $ref_term_id=$termData->result->term->term_id;
            $label=ucfirst(LABEL_modTerm).' ';
            break;
        default:
            $termData=getURLdata($tematres_uri.'?task=fetchTerm&arg='.$ref_term_id);
            $ref_term_id=$termData->result->term->term_id;
            $label=ucfirst(LABEL_newTermFor).' ';
            break;
    }
//  $to=$_SESSION[$session_name]["suggest_mail"];
    $to=(string) $vocabularyMetaData->result->adminEmail;
    $subject=MAILSGS_subject.' '.(string) $vocabularyMetaData->result->title.' - '.$label.$termData->result->term->string;
    $body.=LABEL_fromMail.': '.$_SESSION[$session_name]["suggest_mail"]."\r\n";
    $body.=LABEL_personalName.': '.$_SESSION[$session_name]["suggest_name"]."\r\n";
    $body.= ucfirst($label).': '.$termData->result->term->string."\r\n";
    $body.=LABEL_suggest.': '.$_SESSION[$session_name]["suggest_string"]."\r\n";
    $body.=LABEL_justificationNote.': '.$_SESSION[$session_name]["suggest_note"]."\r\n";
    $body.=LABEL_sourceNote.': '.$_SESSION[$session_name]["suggest_source"]."\r\n";
    return array("to"=>$to,
                 "subject"=>$subject,
                 "body"=>$body);
}

function HTMLthank4suggest($vocabularyMetaData,$msg_id=1)
{
    GLOBAL $CFG;
    switch ($msg_id) {
        case '1':
            $rows.='<h4>'.ucfirst(MSG_salutation).'</h4>';
            $rows.='<p>'.ucfirst(MSG_salutation1).'</p>';
            $rows.='<p>'.ucfirst(MSG_salutation2).'</p>';
            $rows.='<p>'.(string) $vocabularyMetaData->result->author.'.</p>';
            $rows.='<p>'.ucfirst(MSG_salutation3).'</p>';
            break;
        default:
            $rows.='<h3>'.ucfirst(MSG_salutation3).'</h3>';
            //$rows.='<p>El equipo responsable del '.(string) $vocabularyMetaData->result->title.' lo contactará a la brevedad.</p>';
            $rows.='<h4 class="text-right">'.(string) $vocabularyMetaData->result->author.'</h4>';
            break;
    }
    return $rows;
}

<?php
    require '../../config.ws.php';
    
    if(checkModuleCFG('SUGGESTION_SERVICE')!==true) header("Location:../../index.php");      
      
    require 'config.ws.php';
    
    include_once('fun.suggest.php');
    $params["TEMATRES_URI_SERVICE"]=$CFG_VOCABS[$CFG["DEFVOCAB"]]["URL_BASE"];

    $vocabularyMetaData=getTemaTresData($params["TEMATRES_URI_SERVICE"]);

        

        
    //type of sugges _$GET["r"]= : UF / EQ / NT / modT
    //Se invoca con un hash y se envía la sugerencia
    $params = array();
    $params["suggest_string"] = (strlen($_POST["suggest_string"])>0)? XSSprevent($_POST["suggest_string"]) : '';
    $params["suggest_note"]   = (strlen($_POST["suggest_note"])>0) ? XSSprevent($_POST["suggest_note"]) : '';
    $params["suggest_source"] = (strlen($_POST["suggest_source"])>0) ? XSSprevent($_POST["suggest_source"]) : '';
    $params["suggest_name"]   = (strlen($_POST["suggest_name"])>0) ? XSSprevent($_POST["suggest_name"]) : '';
    $params["suggest_mail"]   = (strlen($_POST["suggest_mail"])>0) ? XSSprevent($_POST["suggest_mail"]) : '';
    $params["term_id"]        = ((int) $_GET["term_id"]>0) ?  $_GET["term_id"] : 0;
    $params["v"]              = (is_array($CFG_VOCABS[$_GET["v"]])) ? $_GET["v"]  : $CFG["DEFVOCAB"];
    $r = (isset($_POST["task"])) ? $_POST["task"] : $_GET["r"];
    $params["t_relation"]     = (in_array($r, $CFG["SUGGEST_OPT"])) ? $r  : "";
    //se envió el formulario
    if (in_array($_POST["task"], $CFG["SUGGEST_OPT"])) {
        //evaluar envío
        $params["error"]=evalSuggestForm($vocabularyMetaData->result->uri.'services.php',$params);
        if ($params["error"]["flag_task"] == 1) {
            //enviar mail = todo ok
            $params["term_id"]=((int) $_POST["term_id"]>0) ? (int) $_POST["term_id"] : 0;
            //componer mail
            $mail2user=redactSuggestion($vocabularyMetaData,storeSuggestion($vocabularyMetaData,$params));
            //enviar mail
            $mail2user["to"]=(string) $vocabularyMetaData->result->adminEmail;
            $sendMail=sendMail($mail2user["to"],$mail2user["subject"],$mail2user["body"],array("from"=>$params["suggest_mail"]));
        }
    }
?>
<!DOCTYPE html>
<html lang="<?php echo $_SESSION["vocab"]["lang"]; ?>">
    <head>
        <?php
            echo HTMLmeta($_SESSION["vocab"],LABEL_suggest.' ');
            echo HTMLestilosyjs();
        ?>

        <style type="text/css">
            .sidebar-nav {
                padding: 9px 0;
            }
            @media (max-width: 980px) {
                /* Enable use of floated navbar text */
                .navbar-text.pull-right {
                    float: none;
                    padding-left: 5px;
                    padding-right: 5px;
                }
            }
            .search-query:focus + button {
                z-index: 3;
            }
            .error {
                background:#ffe1da url('../images/icon_error.png') 13px 50% no-repeat;
                border:2px solid #f34f4f;
                border-bottom:2px solid #f34f4f;
                color:#be0b0b;
                font-size:120%;
                padding:10px 11px 8px 36px;
            }
            .errorNoImage{background:#ffe1da 13px 50% no-repeat;border:2px solid #f34f4f;color:#be0b0b;font-size:120%;padding:10px 11px 8px 36px;}
            .information{background:#dedfff url('../images/icon_information.png') 13px 50% no-repeat;border:2px solid #9bb8d9;color:#406299;font-size:120%;padding:10px 11px 8px 36px;}
            .success{background:#e2f9e3 url('../images/icon_success.png') 13px 50% no-repeat;border:2px solid #9c9;color:#080;font-size:120%;padding:10px 11px 8px 38px;}
            .warning{background:#fff8bf url('../images/icon_warning.png') 13px 50% no-repeat;border:2px solid #ffd324;color:#eb830c;font-size:120%;padding:10px 11px 8px 38px;}
            .successNoImage{background:#fff8bf;color:#080;padding:1px 5px;}
        /* end search form */
        </style>
        <!-- Initialize the plugin: -->
        <script type="text/javascript">
            var options, a;
            var onSelect = function(val, data) {
                $('#suggest-form #id').val(data);
            };
            jQuery(function() {
                options = {
                    serviceUrl:'../../common/proxy.php' ,
                    minChars:3,
                    delimiter: /(,|;)\s*/, // regex or character
                    maxHeight:300,
                    zIndex: 9999,
                    deferRequestBy: 0, //miliseconds
                    showNoSuggestionNotice: false,
                    params: { v:'<?php echo $v;?>'}, //aditional parameters
                    noCache: false, //default is false, set to true to disable caching
                    // callback function:
                    onSelect: function (suggestion) {
                        $('#error_suggest_term').html(suggestion.value +': término existente');
                    }
                };
                a = $('#suggest_string').autocomplete(options);
            });
        </script>
    </head>
    <body>
        <?php
            echo HTMLglobalMenu(array("CFG_VOCABS"=>$CFG_VOCABS));
        ?>
        <div class="container">
            <div id="keep">
                <div class="grid-sizer"></div>
                <div class="gutter-sizer"></div>
                <div class="box box-pres box-pres2">
                    <h1><?php echo SUGGESTION_SERVICE_title;?></h1>
                    <p><?php echo sprintf(SUGGESTION_SERVICE_description);?></p>

                    <h1>
                    <?php echo $_SESSION["vocab"]["title"];?>
                </h1>
                <p class="autor text-right">
                    <?php echo $_SESSION["vocab"]["author"];?>
                </p>
                <p class="text-justify ocultar">
                    <?php echo $_SESSION["vocab"]["scope"];?>
                </p>
                </div><!-- END box presentación -->
                <div class="box box-info triple">
                    <?php
                        //se envió el correo
                        if ($sendMail) {
                            echo HTMLthank4suggest($vocabularyMetaData,2);
                        } else {
                            //Hay errores
                            echo formSuggestTerm($CFG_VOCABS[$v]["URL_BASE"],$params);
                        };
                    ?>
                </div><!--  END sugerencias -->
            </div><!--END keep -->
            <?php
                echo HTMLglobalFooter(array());
            ?>
        </div><!--END container -->
    </body>
</html>

<?php
    require '../../config.ws.php';
    $searchq  =  XSSprevent($_GET['term']);
if (strlen($searchq)>= $CFG["MIN_CHAR_SEARCH"]) {
    echo getData4AutocompleterUI($URL_BASE, $searchq);
    exit();
};
    require 'config.ws.php';
    include_once('fun.termdistiller.php');
    // URL for the TemaTres web services provider
    $params["TEMATRES_URI_SERVICE"]=$CFG_VOCABS[$CFG["DEFVOCAB"]]["URL_BASE"];

    $vocabularyMetaData=getTemaTresData($params["TEMATRES_URI_SERVICE"]);

if (! isset($params["content"])) {
    $params["content"] = '';
}
                        
                        $params["content"] = isset($_POST["q"]) ? prepareCorpus($_POST["q"]) : '' ;


    $task=(isset($_POST["task"])) ? $_POST["task"] :'';

    $task=in_array($task, array('init0','evalText','evalTerms')) ? $task : 'init0';

switch ($task) {
    case 'evalText':
        $ccsStep2='active-step';
        break;
    case 'evalTerms':
        $ccsStep3='active-step';
        break;
    default:
        $ccsStep1='active-step';
        break;
}
header('Content-Type: text/html; charset=UTF-8');
?>
<!DOCTYPE html>
<html lang="<?php echo $_SESSION["vocab"]["lang"]; ?>">
    <head>
        <?php
            echo HTMLmeta($_SESSION["vocab"], CLASSIFY_SERVICE_title);
            echo HTMLestilosyjs();
        ?>
           
    <style>
        .corpus{opacity: 0.9;}
        .marked-text {opacity: 0.9;background-color: DarkCyan;padding: 0 3px;color: LightGrey;border-radius: 3px;font-weight: 600;}
        .active-step{opacity: 1 !important;filter: alpha(opacity=100) !important;font-weight: bold;}
        .term_list{list-style-type:none;}
        .noSelectedTerms {opacity: 0.5;font-weight: bold;}
  .ui-autocomplete {
    max-height: 200px;
    overflow-y: auto;
    /* prevent horizontal scrollbar */
    overflow-x: hidden;
  }
  /* IE 6 doesn't support max-height
   * we use height instead, but this forces the menu to always be this tall
   */
  * html .ui-autocomplete {
    height: 200px;
  }        
    </style>           
    <!-- Tokenfield CSS -->
    <link href="tokenfield/dist/css/bootstrap-tokenfield.css" type="text/css" rel="stylesheet">

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
                    <h1><?php echo CLASSIFY_SERVICE_title;?></h1>
                    <p><?php echo sprintf(CLASSIFY_SERVICE_description).' <a href="'.$CFG_URL_PARAM["url_site"].'index.php?v='.$_SESSION["vocab"]["code"].'" title="'.$_SESSION["vocab"]["title"].'">'.$_SESSION["vocab"]["title"].'</a>';?></p>
<!--
                <h1>
                    <?php echo $_SESSION["vocab"]["title"];?>
                </h1>
                <p class="autor text-right">
                    <?php echo $_SESSION["vocab"]["author"];?>
                </p>
              <p class="text-justify ocultar">
                    <?php echo $_SESSION["vocab"]["scope"];?>
                </p>
-->                
                <p><ol>
                    <li class="<?php echo $ccsStep1;?>"><?php echo CLASSIFY_SERVICE_helpStep1;?></li>
                    <li class="<?php echo $ccsStep2;?>"><?php echo CLASSIFY_SERVICE_helpStep2;?></li>
                    <li class="<?php echo $ccsStep3;?>"><?php echo CLASSIFY_SERVICE_helpStep3;?></li>
                    <li style="list-style-type: none;"><?php echo CLASSIFY_SERVICE_helpStep4;?></li>
                </ol>
                </p>
                </div><!-- END box presentación -->

                <div class="box box-info triple">
                    <?php echo '<h2><a href="index.php" title="'.CLASSIFY_SERVICE_title.'">'.CLASSIFY_SERVICE_title.'</a></h2>';?> 
                    <?php echo '<h5>'.$_SESSION["vocab"]["title"].'</h5>';?> 
                  <hr>
        
            <?php

            if ($task=='init0') {
                ?>
                    <form class="controlterm"  accept-charset="utf-8" action="index.php#massiveresult" method="post">
                        <div class="text-field">
                            <textarea id="searchbox" name="q" rows="20" placeholder="<?php echo CLASSIFY_SERVICE_help;?>"></textarea>
                        </div>
                        <div class="button-field pull-right">
                            <input type="hidden" name="task" value="evalText">

                            <input type="submit" id="parse_text" class="btn btn-info" value="<?php echo ucfirst(ENVIAR);?>">
                        </div>
                    </form>        

            <?php
            };//fin (!isset($_POST["task"]))

            if ($task=='evalText') {
                $keywordsText=distillTerms($params["content"]);
                $keywordsText

            ?>
                <form name="text2parse" id="text2parse" method=post  accept-charset="utf-8" action="index.php">
                <?php echo HTMLtextDisplay($params["content"]);?>
                <div class="text-field">            

    
                <div class="form-group">                    
                    <label for="suggestedKeywords"><?php echo sprintf(ucfirst(CLASSIFY_SERVICE_terms_tag), $CFG["max_fetch_keywords"]);?></label>

                    <input type="text" class="form-control" name="suggestedKeywords" id="suggestedKeywords" value="<?php echo prepareString2Tags($keywordsText);?>" placeholder="<?php echo ucfirst(CLASSIFY_SERVICE_placeholderAddKeyWords);?>" />                

                </div>


                <input type="hidden" name="task" value="evalTerms">
                </div>
                <div class="button-field pull-right">  
                    <br><input type="submit" id="parse_text" class="btn btn-info" value="<?php echo ucfirst(LABELFORM_evalTerms);?>">
                </div>    
                </form>            
            <?php
            }; //fin ($_POST["task"]=='evalText')

            if ($task=='evalTerms') {
                echo HTMLtextDisplay($params["content"], array("task"=>$task));


                $suggestedKeywords=(explode(',', urldecode(XSSprevent($_POST["suggestedKeywords"]))));

                echo '<div id="foundTerms">';
                echo '<h4>'.ucfirst(LABEL_terms).'</h4>';
                echo '<ul id="distilled_keywords" class="term_list">';
                
                $iKeyword=0;

                //iteración de análisis para cada término
                foreach ($suggestedKeywords as $term) {
                        //mientras sea menor que el máximo de términos a analizar
                    if ($iKeyword<=$CFG["max_fetch_keywords"]) {
                        //buscar término
                        $HTMLevalTerm=HTMLevalTerm($URL_BASE, $term);
                
                        if ($HTMLevalTerm["cant"]>0) {
                            echo $HTMLevalTerm["content"];
                            $iKeyword=++$iKeyword;
                        }
                    }
                }
                echo '</ul>';

                //buscar términos relacionados
                $relatedTerms=relatedTerms($URL_BASE, $HTMLevalTerm["arrayTermsID"]);

                if ((int)$relatedTerms->resume->cant_result>0) {
                    echo HTMLrelatedTerms($relatedTerms);
                }

                /*//buscar términos más generales
                $broadTerms=broadTerms($URL_BASE,$HTMLevalTerm["arrayTermsID"]);

                if((int)$broadTerms->resume->cant_result>0) echo HTMLbroadTerms($relatedTerms);
            */

                echo '</div>';
                echo '<div><br>
                </div>' ;
            } // if($_POST["task"]=='evalTerms')
    
            ?>      

                </div><!-- END input autoridades -->
        

              
            </div><!--END keep -->
            <?php
                echo HTMLglobalFooter(array());
            ?>
    <script type="text/javascript" src="tokenfield/dist/bootstrap-tokenfield.js" charset="UTF-8"></script>

    <script src="clipboard.min.js"></script>
    <script src="jquitelight.js"></script>

    <script type="text/javascript">
             
    $('#suggestedKeywords').tokenfield({
             autocomplete: { delay: 0, // show suggestions immediately
                                    position: { collision: 'flip' }, // automatic menu position up/down },
                                    'source': '<?php echo $CFG_URL_PARAM["url_site"];?>apps/classify/index.php', 
                                    'minLength': 3,  autoFocus: true },
            showAutocompleteOnFocus: false,
            createTokensOnBlur:true,
            delimiter:',',

        })

    $('#validKeyWords').tokenfield({
             autocomplete: { delay: 0, // show suggestions immediately
                                    position: { collision: 'flip' }, // automatic menu position up/down },
                                    'source': '<?php echo $CFG_URL_PARAM["url_site"];?>apps/classify/index.php', 
                                    'minLength': 3,  autoFocus: true },
            showAutocompleteOnFocus: true,
            createTokensOnBlur:true,
            delimiter:',' 


        })

    $('#validKeyWords').on('tokenfield:removedtoken', function (e) {        
        var $results = $(':contains("'+ e.attrs.value+'")').filter(function() {
                        return $(this).text() === e.attrs.value;
                    });
                    $results.removeClass('noSelectedTerms');
      })


    $('.selectedTerms').click(function() {
        var tokens = $('#validKeyWords').tokenfield('getTokensList');
        var tokensArray = tokens.split(', ');

        if(jQuery.inArray($(this).text(), tokensArray) == -1){
            $('#validKeyWords').tokenfield('createToken', $(this).text());
            $(this).toggleClass('noSelectedTerms');

            }
    });

    var clipboard = new ClipboardJS('.copy');

    $(".corpus").mark([<?php echo prepareString2TagsJS($keywordsText);?>]);

    </script>

        </div><!-- END container -->
    </body>
</html>
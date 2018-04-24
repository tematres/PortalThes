<?php
    require '../../config.ws.php';
    require 'config.ws.php';
    include_once('fun.termdistiller.php');
    // URL for the TemaTres web services provider
    $params["TEMATRES_URI_SERVICE"]=$CFG_VOCABS[$CFG["DEFVOCAB"]]["URL_BASE"];

    $vocabularyMetaData=getTemaTresData($params["TEMATRES_URI_SERVICE"]);

                        if ( ! isset($params["content"]))  $params["content"] = '';
                        
                        $params["content"] = isset($_POST["q"]) ? XSSprevent($_POST["q"]) : '' ;

    //$distilledTerms=distillTerms($params["content"]);

?>
<!DOCTYPE html>
<html lang="<?php echo $_SESSION["vocab"]["lang"]; ?>">
    <head>
        <?php
            echo HTMLmeta($_SESSION["vocab"],TERMS_DISTILLER_title);
            echo HTMLestilosyjs();
        ?>
    <style>
        /* color tags */
        .tag-editor .red-tag .tag-editor-tag { color: #c65353; background: #ffd7d7; }
        .tag-editor .red-tag .tag-editor-delete { background-color: #ffd7d7; }
        .tag-editor .green-tag .tag-editor-tag { color: #45872c; background: #e1f3da; }
        .tag-editor .green-tag .tag-editor-delete { background-color: #e1f3da; }
    </style>
          <link rel="stylesheet" href="jquery.tag-editor.css">
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
                    <h1><?php echo TERMS_DISTILLER_title;?></h1>
                    <p><?php echo sprintf(TERMS_DISTILLER_description).' '.$_SESSION["vocab"]["title"];?></p>

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
                  <?php echo '<h2><a href="'.$CFG_URL_PARAM["url_site"].'index.php?v='.$_SESSION["vocab"]["code"].'" title="'.$_SESSION["vocab"]["title"].'">'.$_SESSION["vocab"]["title"].'</a></h2>';?> 


        
            <?php

            if(!isset($_POST["task"])){
                ?>
                    <form class="controlterm" action="index.php#massiveresult" method="post">
                        <div class="text-field">
                            <textarea id="searchbox" name="q" rows="20" placeholder="<?php echo TERMS_DISTILLER_help;?>"></textarea>
                        </div>
                        <div class="button-field">
                            <input type="hidden" name="task" value="evalText">

                            <input type="submit" id="parse_text" class="btn btn-info" value="<?php echo ucfirst(ENVIAR);?>">
                        </div>
                    </form>        

            <?php
            };//fin (!isset($_POST["task"]))

            if($_POST["task"]=='evalText'){

            ?>
                <form name="text2parse" id="text2parse" method=post action="index.php">
                <?php echo HTMLtextDisplay($params["content"]);?>
                <div class="text-field">            

                <label for="suggestedKeywords"><?php echo sprintf(ucfirst(TERMS_DISTILLER_terms_tag),$CFG["max_fetch_keywords"]);?></label>
                <textarea name="suggestedKeywords" id="suggestedKeywords"></textarea>
                <input type="hidden" name="task" value="evalTerms">
                </div>
                <div class="button-field">  
                    <br><input type="submit" id="parse_text" class="btn btn-info" value="<?php echo ucfirst(LABELFORM_evalTerms);?>">
                </div>    
                </form>            
            <?php
             }; //fin ($_POST["task"]=='evalText')

            if($_POST["task"]=='evalTerms'){

                echo HTMLtextDisplay($params["content"]);
                
                $suggestedKeywords=(explode(',', urldecode($_POST["suggestedKeywords"])));

                echo '<ul id="distilled_keywords" class="term_list">';
                $iKeyword=0;
                foreach ($suggestedKeywords as $term) {
                        if($iKeyword<=$CFG["max_fetch_keywords"]){

                            $HTMLevalTerm=HTMLevalTerm($URL_BASE,$term);
                            if($HTMLevalTerm["cant"]>0){
                                echo $HTMLevalTerm["content"];
                                $iKeyword=++$iKeyword;
                                }
                        }               
                        }
                echo '</ul>';   

                echo '<div><br><a href="index.php" class="btn btn-secondary btn-lg active" role="button" aria-pressed="true">'.ucfirst(MENU_Inicio).'</a>
                </div>' ;   

            } // if($_POST["task"]=='evalTerms')
    
            ?>
                </div><!-- END input autoridades -->
        

                <!-- <div class="box box-info triple">
            
        
                </div> -->
            </div><!--END keep -->
            <?php
                echo HTMLglobalFooter(array());
            ?>
                            <script src="jquery.caret.min.js"></script>
                <script src="jquery.tag-editor.js"></script>
                <script>
                $('#suggestedKeywords').tagEditor({ initialTags: [<?php echo prepareString2Tags(distillTerms($params["content"])); ?>        ], placeholder: 'Enter tags ...' }).css('display', 'block').attr('readonly', true);
                </script>


        </div><!-- END container -->
    </body>
</html>
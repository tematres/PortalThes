<?php
    require '../../config.ws.php';
    if(checkModuleCFG('BULK_TERMS_REVIEW')!==true) header("Location:../../index.php");          

    header('Content-Type: text/html; charset=UTF-8');        
    require 'config.ws.php';

    include_once('../../common/excel/ExcelWriterXML.php');
    include_once('fun.autoridades.php');
    // URL for the TemaTres web services provider
    $params["TEMATRES_URI_SERVICE"]=$CFG_VOCABS[$CFG["DEFVOCAB"]]["URL_BASE"];

    $vocabularyMetaData=getTemaTresData($params["TEMATRES_URI_SERVICE"]);

    if ( ! isset($params["content"]))
        $params["content"] = '';
        $params["content"] = isset($_POST["q"]) ? XSSprevent($_POST["q"]) : $params["content"] ;
        
    if (isset($_POST["f"]) && $_POST["f"] == 'XLS') {
        $array_text=explode("\n",$params["content"]);
        if (count($array_text)>1) {
            return getXLSTemaTresTerm($vocabularyMetaData->result->uri."services.php",$vocabularyMetaData,$array_text);
        }
    }
?>
<!DOCTYPE html>
<html lang="<?php echo $_SESSION["vocab"]["lang"]; ?>">
    <head>
        <?php
            echo HTMLmeta($_SESSION["vocab"],BULK_TERMS_REVIEW_title);
            echo HTMLestilosyjs();
        ?>
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
                    <h1><?php echo BULK_TERMS_REVIEW_title;?></h1>
                    <p><?php echo sprintf(BULK_TERMS_REVIEW_description,$CFG["MAX_TERMS4MASS_CTRL"]);?></p>

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
                    <form class="controlterm" action="index.php#massiveresult" method="post">
                        <?php echo '<h2><a href="'.$CFG_URL_PARAM["url_site"].'index.php?v='.$_SESSION["vocab"]["code"].'" title="'.$_SESSION["vocab"]["title"].'">'.$_SESSION["vocab"]["title"].'</a></h2>';?> 

                        <div class="text-field">
                            <textarea id="searchbox" name="q" rows="20" placeholder="<?php echo BULK_TERMS_REVIEW_help;?>"><?php echo $params["content"];?></textarea>
                        </div>
                        <label class="radio">
                            <input type="radio" name="f" id="f1" value="HTML" checked>
                            <?php echo ucfirst(BULK_TERMS_REVIEW_inBrowser);?>
                        </label>
                        <label class="radio">
                            <input type="radio" name="f" id="f2" value="XLS">
                            <?php echo ucfirst(BULK_TERMS_REVIEW_inExcel);?>                            
                        </label>
                        <div class="button-field">
                            <input type="submit" id="parse_text" class="btn btn-info" value="<?php echo ucfirst(BULK_TERMS_REVIEW_compare);?>">
                        </div>
                    </form>
                </div><!-- END input autoridades -->
                <div class="box box-info triple">
                    <?php
                        if (isset($_POST["f"]) && $_POST["f"] == 'HTML') {
                            $array_text=explode("\n",$params["content"]);
                            if (count($array_text)>1) {
                                echo getHTMLTemaTresTerm($vocabularyMetaData->result->uri."services.php",$vocabularyMetaData,$array_text);
                            }
                        }
                    ?>
                </div><!-- END resultados autoridades-->
            </div><!--END keep -->
            <?php
                echo HTMLglobalFooter(array());
            ?>
        </div><!-- END container -->
    </body>
</html>
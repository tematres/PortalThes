<?php
    require '../../config.ws.php';
if (checkModuleCFG('BULK_TERMS_REVIEW')!==true) {
    header("Location:../../index.php");
}

    header('Content-Type: text/html; charset=UTF-8');
    require 'config.ws.php';

    include_once('../../common/excel/ExcelWriterXML.php');
    include_once('fun.autoridades.php');
    // URL for the TemaTres web services provider
    $params["TEMATRES_URI_SERVICE"]=$CFG_VOCABS[$CFG["DEFVOCAB"]]["URL_BASE"];

    $vocabularyMetaData=getTemaTresData($params["TEMATRES_URI_SERVICE"]);

if (! isset($params["content"])) {
    $params["content"] = '';
}
        $params["content"] = isset($_POST["q"]) ? XSSprevent($_POST["q"]) : $params["content"] ;
        
if (isset($_POST["f"]) && $_POST["f"] == 'XLS') {
    $array_text=explode("\n", $params["content"]);
    if (count($array_text)>1) {
        return getXLSTemaTresTerm($vocabularyMetaData->result->uri, $vocabularyMetaData, $array_text);
    }
}
?>
<!DOCTYPE html>
<html lang="<?php echo $_SESSION["vocab"]["lang"]; ?>">
    <head>
        <?php
            echo HTMLmeta($_SESSION["vocab"], BULK_TERMS_REVIEW_title);
            echo HTMLestilosyjs();
        ?>
    </head>
    <body onload="countLines(document.getElementById('searchbox'))">
        <?php
            echo HTMLglobalMenu(array("CFG_VOCABS"=>$CFG_VOCABS));
        ?>
        <div class="container">
            <div id="keep">
                <div class="grid-sizer"></div>
                <div class="gutter-sizer"></div>
                <div class="box box-pres box-pres2">
                    <h1><a href="<?php echo $CFG_URL_PARAM["url_site"];?>" title="<?php echo $_SESSION["vocab"]["title"];?>"><?php echo $_SESSION["vocab"]["title"];?></a></h1>
                    <p class="autor text-right"><?php echo $_SESSION["vocab"]["author"];?></p>
                    <p class="text-justify ocultar"><?php echo $_SESSION["vocab"]["scope"];?></p>
                </div><!-- END box presentación -->

                <div class="col-sm-8 col-md-9">
                    <h2><?php echo BULK_TERMS_REVIEW_title;?></h2>
                    <p><?php echo sprintf(BULK_TERMS_REVIEW_description, $CFG["MAX_TERMS4MASS_CTRL"]);?></p>                    
                </div><!--  END buscador  -->

                <div class="col-sm-8 col-md-9" id="content">

                <div class="box box-info triple">
                    <form class="controlterm" action="index.php#massiveresult" method="post">
                        <div class="text-field">
                            <textarea id="searchbox" class="" name="q" rows="20" placeholder="<?php echo BULK_TERMS_REVIEW_help;?>"><?php echo $params["content"];?></textarea>
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
                    <script>
                    $(function() {
                        $(".lined").linedtextarea(
                            {selectedLine: 4}
                        );
                    });
                    </script>

                </div><!-- END input autoridades -->
                
                    <?php
                    if (isset($_POST["f"]) && $_POST["f"] == 'HTML') {
                        $array_text=explode("\n", $params["content"]);

                        if (count($array_text)>1) {
                            echo '<div class="box box-info triple">'    ;
                                
                            echo getHTMLTemaTresTerm($vocabularyMetaData->result->uri, $vocabularyMetaData, $array_text);

                            echo '</div><!-- END resultados autoridades-->'    ;
                        }
                    }
                    ?>

            </div><!--END keep -->
            <?php
                echo HTMLglobalFooter(array());
            ?>
        </div><!-- END container -->
    </body>
</html>
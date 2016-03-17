<?php
    header('Content-Type: text/html; charset=UTF-8');
    require 'config.ws.php';
    if (is_array($CFG_VOCABS[$v])) {
        $task = '';
        if (isset ($_GET["task"])) {
            switch ($_GET["task"]) {
                //datos de un término == term data
                case 'fetchTerm':
                    //sanitiar variables
                    $tema_id = is_numeric($_GET['arg']) ? intval($_GET['arg']) : 0;
                    if ($tema_id > 0) {
                        $dataTerm=getURLdata($URL_BASE.'?task=fetchTerm&arg='.$tema_id);
                        $htmlTerm=data2htmlTerm($dataTerm,array("vocab_code"=>$v));
                        $term= (string) FixEncoding($dataTerm->result->term->string);
                        $term_id= (int) $dataTerm->result->term->term_id;
                        $task='fetchTerm';
                    }
                break;
                //datos de una letra == char data
                case 'letter':
                    $letter     = isset($_GET['arg']) ? XSSprevent($_GET['arg']) : null;
                    $dataTerm   = getURLdata($URL_BASE.'?task=letter&arg='.$letter);
                    $htmlTerm   = data2html4Letter($dataTerm,array("vocab_code"=>$v,"div_title"=>"Términos con la letra "));
                    $task       = 'letter';
                break;
                //ultimos términos
                case 'fetchLast':
                    $dataTerm   = getURLdata($URL_BASE.'?task=fetchLast');
                    $htmlTerm   = data2html4LastTerms($dataTerm,array("vocab_code"=>$v,"div_title"=>"Últimas modificaciones"));
                    $task       = 'fetchLast';
                break;
                //búsqueda  == search
                case 'search':
                    //sanitiar variables
                    $string = isset($_GET['arg']) ? XSSprevent($_GET['arg']) : null;
                    if (strlen($string) > 0) {
                        $dataTerm = getURLdata($URL_BASE.'?task=search&arg='.urlencode($string));
                        //check for unique results
                        if (((int) $dataTerm->resume->cant_result == 1) && (mb_strtoupper((string) $dataTerm->result->term->string) == mb_strtoupper($string)))
                            header('Location:'.$CFG_URL_PARAM["url_site"].'?task=fetchTerm&arg='.$dataTerm->result->term->term_id.'&v='.$v);
                        $htmlSearchTerms = data2html4Search($dataTerm,/*ucfirst($message["searchExpresion"]).' : <i>'.*/$string/*.'</i>'*/,array("vocab_code"=>$v));
                        $task = 'search';
                    }
                break;
            }
        }
        //default values
        $c=isset($_GET['c']) ? XSSprevent($_GET['c']) : '';
    }
?>
<!DOCTYPE html>
<html lang="<?php echo $_SESSION["vocab"]["lang"];?>">
    <head>
        <?php 
            echo HTMLmeta($_SESSION["vocab"],$term);
            echo HTMLestilosyjs();
        ?>
    </head>
    <body>
        <?php
            echo HTMLglobalMenu(array("CFG_VOCABS"=>$CFG_VOCABS,"vocab_code"=>$v));
        ?>
        <div class="container">
            <div class="row">
                <div class="col-sm-4 col-md-3">
                    <div class="caja box-info">
                        <?php
                            echo HTMLglobalContextualMenu(array("vocab_code"=>$v,"vocabularyMetadata"=>$vocabularyMetadata));
                        ?>
                    </div>
                </div><!--  END box presentación  -->
                <div class="col-sm-8 col-md-9">
                    <?php echo HTMLformSearch();?>
                </div><!--  END buscador  -->
                <div class="col-sm-8 col-md-9" id="content">
                    <?php
                    switch ($task) {
                        case 'search':
                            echo $htmlSearchTerms;
                            break;
                        case 'letter':
                            echo $htmlTerm["results"];
                            break;
                        case 'fetchLast':
                            echo $htmlTerm["results"];
                            break;
                        case 'fetchTerm':

                            echo HTMLtermDetaills($htmlTerm,$dataTerm,$vocabularyMetadata);

                            break;
                        case 'mdata':
                            echo HTMLmetadataVocabulary($CFG_VOCABS[$v]);
                            break;
                        default:
                            if ($CFG_VOCABS[$v]["SHOW_TREE"]!==0) {
                                echo '  <div id="treeTerm" data-url="'.$CFG_URL_PARAM["url_site"].'common/treedata.php?v='.$v.'">
                                        </div><!-- #topterms -->';
                            }
                            break;
                    }
                    ?>
                </div><!--  END main  -->
                <div class="col-xs-12 littleinfo">
                    <div class="caja box-info">
                        <?php
                            echo littleinfo(array("vocab_code"=>$v,"vocabularyMetadata"=>$vocabularyMetadata));
                        ?>
                    </div>
                </div><!-- END littleinfo  -->
            </div><!--  END row  -->
                 <?php
                echo HTMLglobalFooter(array());
            ?>
       </div><!--  END container  -->
    </body>
</html>

<?php
require 'config.ws.php';
<<<<<<< HEAD

if (is_array($CFG_VOCABS[$v])) {
    $v=configValue(array2value("v",$_GET), $CFG["DEFVOCAB"]);
}

    $searchq  =  XSSprevent(array2value('sgterm',$_GET));
=======

if (is_array($CFG_VOCABS[$v])) {
    $v=configValue($_GET["v"], $CFG["DEFVOCAB"]);
}

    $searchq  =  XSSprevent($_GET['sgterm']);
>>>>>>> 84c0b9b0b042da9954423285a2b83b4183995a97
if (strlen($searchq)>= $CFG["MIN_CHAR_SEARCH"]) {    
    header('Content-type: application/json; charset=utf-8');
    echo getData4AutocompleterUI($URL_BASE, $searchq,$v);
    exit();
};
    header('Content-Type: text/html; charset=UTF-8');

<<<<<<< HEAD
$c=isset($_GET['c']) ? XSSprevent($_GET['c']) : '';
$term='';
$array_node=array();
$task=array2value("task",$_GET);
$task=is_array($vocabularyMetadata) ? $task : 'error';


switch ($task) {
    //datos de un término == term data
    case 'term':
        //sanitiar variables
        $tema_id = is_numeric($_GET['arg']) ? intval($_GET['arg']) : 0;
        if ($tema_id > 0) {
            $dataTerm=getURLdata($URL_BASE.'?task=fetchTerm&arg='.$tema_id);
            $htmlTerm=data2htmlTerm($dataTerm, array("vocab_code"=>$v));
            $term= (string) FixEncoding($dataTerm->result->term->string);
            $term_id= (int) $dataTerm->result->term->term_id;
            $arrayTermData=$htmlTerm["resultData"];
            $task='fetchTerm';
            //Nodes and archs to vis.js
            $array_node=is_array($htmlTerm["data4vis"]) ? $htmlTerm["data4vis"]["nodes"] : array();
            $array_edge=is_array($htmlTerm["data4vis"]) ? $htmlTerm["data4vis"]["edges"] : array();
        }
        break;
    //datos de una letra == char data
    case 'letter':
        $letter     = isset($_GET['arg']) ? XSSprevent($_GET['arg']) : null;
        $dataTerm   = getURLdata($URL_BASE.'?task=letter&arg='.$letter);
        $htmlTerm   = data2html4Letter($dataTerm, array("vocab_code"=>$v,"div_title"=>"Términos con la letra "));
        $task       = 'letter';
        break;
    //ultimos términos
    case 'fetchLast':
        $dataTerm   = getURLdata($URL_BASE.'?task=fetchLast');
        $htmlTerm   = data2html4LastTerms($dataTerm, array("vocab_code"=>$v,"div_title"=>"Últimas modificaciones"));
        $task       = 'fetchLast';
        break;
    //búsqueda  == search
    case 'search':
        //sanitiar variables
        $string = isset($_GET['arg']) ? XSSprevent($_GET['arg']) : null;
        if (strlen($string) > 0) {
            $dataTerm = getURLdata($URL_BASE.'?task=search&arg='.urlencode($string));
            //check for unique results
            if (((int) $dataTerm->resume->cant_result == 1) && (mb_strtoupper((string) $dataTerm->result->term->string) == mb_strtoupper($string))) {
                header('Location:'.$CFG_URL_PARAM["url_site"].$CFG_URL_PARAM["v"].$v.$CFG_URL_PARAM["fetchTerm"].$dataTerm->result->term->term_id);
            }
            $htmlSearchTerms = data2html4Search($dataTerm, /*ucfirst($message["searchExpresion"]).' : <i>'.*/$string/*.'</i>'*/, array("vocab_code"=>$v));
            $task = 'search';
        } else {
            $task = 'topterms';
        }

        break;
    //error    
    case 'error':

        break;
    default:
        $task       = 'topterms';
}
=======


    if (isset($_GET["task"])) {
        switch ($_GET["task"]) {
            //datos de un término == term data
            case 'term':
                //sanitiar variables
                $tema_id = is_numeric($_GET['arg']) ? intval($_GET['arg']) : 0;
                if ($tema_id > 0) {
                    $dataTerm=getURLdata($URL_BASE.'?task=fetchTerm&arg='.$tema_id);
                    $htmlTerm=data2htmlTerm($dataTerm, array("vocab_code"=>$v));
                    $term= (string) FixEncoding($dataTerm->result->term->string);
                    $term_id= (int) $dataTerm->result->term->term_id;
                    $arrayTermData=$htmlTerm["resultData"];
                    $task='fetchTerm';
                }
                break;
            //datos de una letra == char data
            case 'letter':
                $letter     = isset($_GET['arg']) ? XSSprevent($_GET['arg']) : null;
                $dataTerm   = getURLdata($URL_BASE.'?task=letter&arg='.$letter);
                $htmlTerm   = data2html4Letter($dataTerm, array("vocab_code"=>$v,"div_title"=>"Términos con la letra "));
                $task       = 'letter';
                break;
            //ultimos términos
            case 'fetchLast':
                $dataTerm   = getURLdata($URL_BASE.'?task=fetchLast');
                $htmlTerm   = data2html4LastTerms($dataTerm, array("vocab_code"=>$v,"div_title"=>"Últimas modificaciones"));
                $task       = 'fetchLast';
                break;
            //búsqueda  == search
            case 'search':
                //sanitiar variables
                $string = isset($_GET['arg']) ? XSSprevent($_GET['arg']) : null;
                if (strlen($string) > 0) {
                    $dataTerm = getURLdata($URL_BASE.'?task=search&arg='.urlencode($string));
                    //check for unique results
                    if (((int) $dataTerm->resume->cant_result == 1) && (mb_strtoupper((string) $dataTerm->result->term->string) == mb_strtoupper($string))) {
                        header('Location:'.$CFG_URL_PARAM["url_site"].$CFG_URL_PARAM["v"].$v.$CFG_URL_PARAM["fetchTerm"].$dataTerm->result->term->term_id);
                    }
                    $htmlSearchTerms = data2html4Search($dataTerm, /*ucfirst($message["searchExpresion"]).' : <i>'.*/$string/*.'</i>'*/, array("vocab_code"=>$v));
                    $task = 'search';
                }
                break;
            default:
                $task       = 'topterms';
        }
    } else {
            $task       = 'topterms';
    }
    //default values
    $c=isset($_GET['c']) ? XSSprevent($_GET['c']) : '';
>>>>>>> 84c0b9b0b042da9954423285a2b83b4183995a97
?>
<!DOCTYPE html>
<html lang="<?php echo $_SESSION["vocab"]["lang"];?>">
    <head>
<<<<<<< HEAD
<?php
    echo HTMLmeta($_SESSION["vocab"], $term);
    echo HTMLestilosyjs($v);
?>  
=======
        <?php
            echo HTMLmeta($_SESSION["vocab"], $term);
            echo HTMLestilosyjs($v);

        if ((checkModuleCFG("VISUAL_VOCAB", $v)) && ($task=='fetchTerm')) {
            include_once('apps/vv/config.ws.php');
            echo '<link type="text/css" href="'.$CFG_URL_PARAM["url_site"].'apps/vv/css/hypertree.css" rel="stylesheet" />
                            <!-- JIT Library File -->
                    <script language="javascript" type="text/javascript" src="'.$CFG_URL_PARAM["url_site"].'apps/vv/js/jit-yc.js"></script>
                    <!-- Source File -->
                    <script language="javascript" type="text/javascript" src="'.$CFG_URL_PARAM["url_site"].'apps/vv/vv.php?term_id='.$term_id.'"></script>';
        }
        ?>        
>>>>>>> 84c0b9b0b042da9954423285a2b83b4183995a97
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

                    <?php
                    if (is_array($CFG_VOCABS[$v]["ALPHA"])) {
                        echo HTMLalphaNav($CFG_VOCABS[$v]["ALPHA"], array2value('letter',$_GET), array("vocab_code"=>$v));
                    }
                        ?>                    
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
                            echo HTMLtermDetaills($htmlTerm, $dataTerm, $vocabularyMetadata);
                            break;
                        case 'mdata':
                            echo HTMLmetadataVocabulary($CFG_VOCABS[$v]);
                            break;
                        case 'error':
                            echo HTMLerrorVocabulary($v);
                            break;

                        default:
                            if (array2value("SHOW_TREE",$CFG_VOCABS[$v])!==0) {
                                echo '<div id="treeTerm" data-url="'.$CFG_URL_PARAM["url_site"].'common/treedata.php?v='.$v.'"></div><!-- #topterms -->';
                            }
                            break;
                    }
                    ?>
                </div><!--  END main  -->
                <div class="col-xs-12 littleinfo">
                    <div class="caja box-info">
                        <?php
                            echo HTMLlittleinfo(array("vocab_code"=>$v,"vocabularyMetadata"=>$vocabularyMetadata));
                        ?>
                    </div>
                </div><!-- END littleinfo  -->
            </div><!--  END row  -->
                    <?php
                    echo HTMLglobalFooter(array("vocab_code"=>$v,"vocabularyMetadata"=>$vocabularyMetadata));
            ?>
       </div><!--  END container  -->
    <?php if(count($array_node)>1) : ;?>       
    <script type="text/javascript">
        
        let nodes = new vis.DataSet(<?php echo json_encode($array_node);?>);
        let edges = new vis.DataSet(<?php echo json_encode($array_edge);?>);

        let data = {
            nodes: nodes,
            edges: edges,
        };
        let options = {
            interaction:{
            hover:true,
            },
            groups: {
                'GBT': {color:{background:'#FADD91'}, borderWidth:3},
                'GRT': {color:{background:'#81D9E3'}, borderWidth:3},
                'GNT': {color:{background:'#B2FF8F'}, borderWidth:3}
            },
            physics: {
                    stabilization: false,
                    barnesHut: {
                    springLength: 200,
                    },
            },
            nodes:{
                color: '#B982FF',
                fixed: false,
                font: '20px arial black',
                scaling: {
                    label: false,
                },
                shadow: true,
                shape: 'box',
                margin: 10,
                multi: false,
            },
            edges: {
                arrows: '',
                color: 'black',
                scaling: {
                    label: false,
                },
                shadow: true,
            },
            
            
        };
        let container = document.getElementById("graphterm");
        let network = new vis.Network(container, data, options);
    </script>
<?php endif;?>
    </body>
</html>

<?php
require '../../../config.ws.php';
    
//if(checkModuleCFG('SUGGESTION_SERVICE')!==true) header("Location:../../index.php");      

include_once('config.ws.php');

$vocabularyMetadata=fetchVocabularyMetadata($URL_BASE);

if($vocabularyMetadata)
{
	$task='';
	switch ($_GET["task"]) {
		//datos de un término == term data
		case 'fetchTerm':
			//sanitiar variables
			$tema_id = is_numeric($_GET['arg']) ? intval($_GET['arg']) : 0;

			if($tema_id>0){

				$dataTerm=getURLdata($URL_BASE.'?task=fetchTerm&arg='.$tema_id);
				$htmlTerm=data2htmlTerm($dataTerm,array());
				$term= (string) FixEncoding($dataTerm->result->term->string);
				$term_id= (int) $dataTerm->result->term->term_id;
				$task='fetchTerm';

				$div_data='<div id="term" about="'.$URL_BASE.$CFG_FETCH_PARAM.$dataTerm->result->term->term_id.'" typeof="skos:Concept">';
				$div_data.='<h2>'.$term.'</h2>';
				$div_data.=$htmlTerm["results"]["breadcrumb"];
				$div_data.=$htmlTerm["results"]["termdata"];


				$div_data.='<dl class="dl-horizontal">'.$htmlTerm["results"]["BT"].'</dl>';
				$div_data.='<dl class="dl-horizontal">'.$htmlTerm["results"]["NT"].'</dl>';
				$div_data.='<dl class="dl-horizontal">'.$htmlTerm["results"]["RT"].'</dl>';
				$div_data.='<dl class="dl-horizontal">'.$htmlTerm["results"]["UF"].'</dl>';
				$div_data.=$htmlTerm["results"]["NOTES"];
				$div_data.='</div><!-- #term -->';

			}
		break;

			//datos de una letra == char data
		case 'letter':
			$letter = isset($_GET['arg']) ? XSSprevent($_GET['arg']) : null;

			$dataTerm=getURLdata($URL_BASE.'?task=letter&arg='.$letter);

			$htmlTerm=data2html4Letter($dataTerm,array("div_title"=>"Términos con la letra "));
			$task='letter';

			$div_data='<h2>'.$vocabularyMetadata["title"].'</h2>';
			$div_data.=$htmlTerm["results"];

		break;
			//ultimos términos
		case 'fetchLast':

			$dataTerm=getURLdata($URL_BASE.'?task=fetchLast');

			$htmlTerm=data2html4LastTerms($dataTerm,array("div_title"=>"Últimas modificaciones"));
			$task='fetchLast';

		break;

			//búsqueda  == search
		case 'search':

			//sanitiar variables
			$string = isset($_GET['arg']) ? XSSprevent($_GET['arg']) : null;

			if(strlen($string)>0)
			{
				$dataTerm=getURLdata($URL_BASE.'?task=search&arg='.urlencode($string));

				//check for unique results
				if( ((int) $dataTerm->resume->cant_result==1) && (strtolower((string) $dataTerm->result->term->string)==strtolower($string)))
				{
					header('Location:'.WEBTHES_PATH.'?task=fetchTerm&arg='.$dataTerm->result->term->term_id.'&v='.$v);
				}

				$htmlSearchTerms=data2html4Search($dataTerm,ucfirst($message["searchExpresion"]).' : <i>'.$string.'</i>',array());
				$task='search';

				$div_data.=$htmlSearchTerms;
			}
		break;

		default:
			$div_data='<h2>'.$vocabularyMetadata["title"].'</h2>';
			$div_data.='<div id="treeTerm" data-url="'.$CFG_URL_PARAM["url_site"].$CFG_POP["PATH"].'common/treedata.php"></div><!-- #topterms -->';
		break;
	}
}
?>
<!DOCTYPE html>
<html lang="<?php echo $vocabularyMetadata["lang"];?>">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="<?php echo FixEncoding($term).' '.$vocabularyMetadata["title"];?>">
    <meta name="author" content="<?php echo $vocabularyMetadata["author"];?>">
	<link type="image/x-icon" href="<?php echo WEBTHES_PATH;?>css/tematres.ico" rel="icon" />
<title><?php echo FixEncoding($term).' '.$vocabularyMetadata["title"].'. '.$vocabularyMetadata["author"];?></title>
    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom styles for this template -->
    <link href="css/sticky-footer.css" rel="stylesheet">
    <!-- Custom CSS -->

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

	<link rel="stylesheet" href="<?php echo WEBTHES_PATH;?>css/jqtree.css">
	<link rel="stylesheet" href="<?php echo WEBTHES_PATH;?>css/thes.css">

	<link rel="stylesheet" type="text/css" href="<?php echo WEBTHES_PATH;?>css/jquery.autocomplete.css" />
    <!-- jQuery Version 1.11.1 -->
    <script src="js/jquery.js"></script>
 	<script type="text/javascript" src="<?php echo WEBTHES_PATH;?>js/jquery.autocomplete.min.js"></script>
 	<script type="text/javascript" src="<?php echo WEBTHES_PATH;?>js/jquery.mockjax.js"></script>
	<script type="text/javascript" src="<?php echo WEBTHES_PATH;?>js/tree.jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>

		<script type="text/javascript">
		var options, a;
		var onSelect = function(val, data) { $('#searchform #id').val(data); $('#searchform').submit(); };
		jQuery(function(){
		options = {
		    serviceUrl:'<?php echo WEBTHES_PATH;?>common/proxy.php' ,
		    minChars:2,
		    delimiter: /(,|;)\s*/, // regex or character
		    maxHeight:400,
		    width:600,
		    zIndex: 9999,
		    deferRequestBy: 0, //miliseconds
		    params: { v:'<?php echo $v;?>' }, //aditional parameters
		    noCache: false, //default is false, set to true to disable caching
		    // callback function:
		    onSelect: onSelect,
			};
		  a = $('#query').autocomplete(options);
		});

		$(function() {
		    $('#treeTerm').tree({
		      dragAndDrop: false,
		      autoEscape: false
		  });
		});
</script

</head>

<body>
    <!-- Navigation -->
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
     <div class="container">
    <div class="col-sm-4 col-md-4">
        <form name="searchForm" method="get" id="searchform" action="<?php echo WEBTHES_PATH;?>" class="navbar-form" role="search">
        <div class="input-group">
                <input type="hidden" name="search_param" value="all" id="search_param">
                <input type="text" class="form-control" id="query" name="arg" class="search-query" placeholder="Buscar">
                <input type="hidden" id="task" name="task" value="search" />
            <div class="input-group-btn">
                <button class="btn btn-default" type="submit"><i class="glyphicon glyphicon-search"></i></button>
            </div>
        </div>
        </form>
    </div>
        </div><!-- /.container -->
    </nav>
    <!-- Page Content -->
    <div class="container">

    <div class="control-group col-sm">
	<button type="button" class="close" aria-hidden="true" onclick="javascript:window.close()">&times;</button>
    </div>

        <div class="row">
            <div class="col-lg-12">
		<?php
			//display HTML
			echo $div_data;
			?>
            </div>
        </div>
        <!-- /.row -->
    </div>
    <!-- /.container -->
  <!-- <div class="navbar navbar-default navbar-fixed-bottom"> -->
  <footer class="navbar navbar-default navbar-fixed-bottom" role="navigation">
  <div class="container">
    <?php echo HTMLalphaNav($CFG_VOCABS[$_SESSION['_PARAMS']["vocab_id"]]["ALPHA"],$letter,array()); ?>
  </div>
</footer>

<!-- PopTerms JavaScript -->
<script src="js/popterms.js"></script>

</body>
</html>

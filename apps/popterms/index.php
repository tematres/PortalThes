<?php
include_once('common/enableCORS.php');
include_once('config.ws.php');
$vocabularyMetadata=fetchVocabularyMetadata($URL_BASE);
if($vocabularyMetadata){
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
				$arg=$term_id;
				$task='fetchTerm';

				$div_data='<div id="term" about="'.$URL_BASE.$CFG_FETCH_PARAM.$dataTerm->result->term->term_id.'" typeof="skos:Concept">';					
				//$div_data.='<h2>'.$term.'</h2>';	
				$div_data.=$htmlTerm["results"]["breadcrumb"];
				$div_data.='<hr>'.$htmlTerm["results"]["termdata"];
				$div_data.='<dl class="dl-horizontal">'.$htmlTerm["results"]["BT"].'</dl>';			
				$div_data.='<dl class="dl-horizontal">'.$htmlTerm["results"]["NT"].'</dl>';
				$div_data.='<dl class="dl-horizontal">'.$htmlTerm["results"]["RT"].'</dl>';							
				$div_data.='<dl class="dl-horizontal">'.$htmlTerm["results"]["UF"].'</dl>';
				$div_data.=$htmlTerm["results"]["NOTES"];			
				$div_data.='</div><!-- #term -->';
				$task='fetchTerm';

			}	
		break;

			//datos de una letra == char data
		case 'letter':
			$arg  = isset($_GET['arg']) ? XSSprevent($_GET['arg']) : null;
			$dataTerm=getURLdata($URL_BASE.'?task=letter&arg='.$arg);			
			$htmlTerm=data2html4Letter($dataTerm,array("div_title"=>LABEL_terms_with_the_letter));
			$div_data='<h2>'.$vocabularyMetadata["title"].'</h2>';
			$div_data.=$htmlTerm["results"];
			$task='letter';

		break;
			//búsqueda  == search
		case 'search':
			//sanitiar variables
			$arg = isset($_GET['arg']) ? XSSprevent($_GET['arg']) : null;		
			if(strlen($arg)>0){
				$dataTerm=getURLdata($URL_BASE.'?task=search&arg='.urlencode($arg));				
				//check for unique results
				if( ((int) $dataTerm->resume->cant_result==1) && (strtolower((string) $dataTerm->result->term->string)==strtolower($arg))){
					header('Location:'.WEBTHES_PATH.'?task=fetchTerm&arg='.$dataTerm->result->term->term_id.'&v='.$v);
				}				
				$htmlSearchTerms=data2html4Search($dataTerm,ucfirst($message["searchExpresion"]).' : <i>'.$arg.'</i>',array());
				$div_data.=$htmlSearchTerms;
				$task='search';				
			}	
		break;

		default:
			$div_data='<h2>'.$vocabularyMetadata["title"].'</h2>';
			if(configValue($CFG_VOCABS[$_SESSION['_PARAMS']["vocab_id"]]["TREE" ],1)) $div_data.='<div id="treeTerm" data-url="'.WEBTHES_PATH.'common/treedata.php"></div><!-- #topterms -->';
			$task='';							
		break;
	}
}
?>
<!DOCTYPE html>
<html lang="<?php echo $vocabularyMetadata["lang"];?>">
<head>
    <meta charset="utf-8">
    <link type="image/x-icon" href="<?php echo WEBTHES_PATH;?>css/favico.ico" rel="shortcut icon" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="<?php echo FixEncoding($term).' '.$vocabularyMetadata["title"];?>">
    <meta name="author" content="<?php echo $vocabularyMetadata["author"];?>">	
	<title><?php echo FixEncoding($term).' '.$vocabularyMetadata["title"].'. '.$vocabularyMetadata["author"];?></title>

    <link rel="stylesheet" href="<?php echo WEBTHES_PATH;?>bt/3.3.4/css/bootstrap.min.css">

    <link href="<?php echo WEBTHES_PATH;?>css/sticky-footer.css" rel="stylesheet">
	<link rel="stylesheet" href="<?php echo WEBTHES_PATH;?>css/jqtree.css">
	<link rel="stylesheet" href="<?php echo WEBTHES_PATH;?>css/thes.css">

	<link rel="stylesheet" type="text/css" href="<?php echo WEBTHES_PATH;?>css/jquery.autocomplete.css" />
    <script src="<?php echo WEBTHES_PATH;?>js/jquery-3.4.1.min.js"></script>
 	<script type="text/javascript" src="<?php echo WEBTHES_PATH;?>js/jquery.autocomplete.min.js"></script>    
 	<script type="text/javascript" src="<?php echo WEBTHES_PATH;?>js/jquery.mockjax.js"></script>    
	<script type="text/javascript" src="<?php echo WEBTHES_PATH;?>js/tree.jquery.js"></script>
    <script src="<?php echo WEBTHES_PATH;?>bt/3.3.4/js/bootstrap.min.js"></script>
	
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
		<div class="container-fluid">
			<form name="searchForm" method="get" id="searchform" action="<?php echo WEBTHES_PATH;?>" class="navbar-form col-xs-11 col-md-10" role="search">
				<div class="input-group">
				    <input type="hidden" name="search_param" value="all" id="search_param">
				    <input type="text" class="form-control" id="query" name="arg" class="search-query" placeholder="<?php print LABEL_Buscar; ?>">
				    <input type="hidden" id="task" name="task" value="search" />
					<div class="input-group-btn">
				        <button class="btn btn-default" type="submit"><i class="glyphicon glyphicon-search"></i></button>
				        <button class="btn btn-default" type="button"  onclick="javascript:window.close()"><i class="glyphicon glyphicon-remove"></i></button>
					</div>
				</div>
			</form>
			<div class="col-xs-1 col-md-2">
			</div>
		</div><!-- /.container -->
	</nav>
    <!-- Page Content -->
    <div class="container">
<!--    <div class="control-group col-sm">
	<button type="button" class="close" aria-hidden="true" onclick="javascript:window.close()">&times;</button>
    </div>
-->
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
  <div class="container alphamenu">
    <?php echo HTMLalphaNav($CFG_VOCABS[$_SESSION['_PARAMS']["vocab_id"]]["ALPHA"],$letter,array()); ?>    	
  </div>
</footer>

<!-- PopTerms JavaScript -->
<script src="js/popterms.js.php"></script>
<script>
$(function () {
  $('[data-toggle="tooltip"]').tooltip()
})
</script>

</body>
</html>

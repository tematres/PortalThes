<?php
require '../../../config.ws.php';
    
//if(checkModuleCFG('SUGGESTION_SERVICE')!==true) header("Location:../../index.php");      

//include_once('../../../config.ws.php');
//include_once('../common/vocabularyservices.php');
?>
<!DOCTYPE html>
<html>
<title>Ejemplo de uso de PopTerms</title>
    <meta name="description" content="PopTerms es una aplicación web sencilla que permite utilizar vocabularios controlados gerenciados por TemaTres e integrarlos con cualquier formulario web o aplicación web">

  <meta http-equiv="cache-control" content="no-cache, mustrevalidate" /> 
  <meta content="text/html; charset=UTF-8" http-equiv="content-type" />

  <meta name="viewport" content="width=device-width, initial-scale=1.0">

 <!-- Jquery -->
<script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
<script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
  <!-- Bootstrap -->
  <!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">

<!-- Optional theme -->
<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap-theme.min.css">

<!-- Latest compiled and minified JavaScript -->
<script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>

<link href="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.2/css/bootstrap-combined.no-icons.min.css" rel="stylesheet">
<link href="//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css" rel="stylesheet">


  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js"></script>
  <script>
  $( function() {
    function split( val ) {
      return val.split( /,\s*/ );
    }
    function extractLast( term ) {
      return split( term ).pop();
    }
 
    $( "#many_terms" )
      // don't navigate away from the field on tab when selecting an item
      .on( "keydown", function( event ) {
        if ( event.keyCode === $.ui.keyCode.TAB &&
            $( this ).autocomplete( "instance" ).menu.active ) {
          event.preventDefault();
        }
      })
      .autocomplete({
        source: function( request, response ) {
          $.getJSON( "../server/common/proxyui.php", {
            term: extractLast( request.term )
          }, response );
        },
        search: function() {
          // custom minLength
          var term = extractLast( this.value );
          if ( term.length < 2 ) {
            return false;
          }
        },
        focus: function() {
          // prevent value inserted on focus
          return false;
        },
        select: function( event, ui ) {
          var terms = split( this.value );
          // remove the current input
          terms.pop();
          // add the selected item
          terms.push( ui.item.value );
          // add placeholder to get the comma-and-space at the end
          terms.push( "" );
          this.value = terms.join( ", " );
          return false;
        }
      });


 $( "#one_term" )
      // don't navigate away from the field on tab when selecting an item
      .on( "keydown", function( event ) {
        if ( event.keyCode === $.ui.keyCode.TAB &&
            $( this ).autocomplete( "instance" ).menu.active ) {
          event.preventDefault();
        }
      })
      .autocomplete({
        source: function( request, response ) {
          $.getJSON( "../server/common/proxyui.php", {
            term: extractLast( request.term )
          }, response );
        },
        search: function() {
          // custom minLength
          var term = extractLast( this.value );
          if ( term.length < 2 ) {
            return false;
          }
        },
        focus: function() {
          // prevent value inserted on focus
          return false;
        },

      });      
  } );
  </script>
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css">
  <link rel="stylesheet" href="http://jqueryui.com/resources/demos/style.css">
  <style>
  .ui-autocomplete-loading {
    background: white url("http://jqueryui.com/images/ui-anim_basic_16x16.gif") right center no-repeat;
  }
  </style>

</head>
<body>

<div class="container">
  <div class="row">
    <div class="span9" id="content">
      
    <div class="page-header">
    	<a href="../../index.php" title="inicio">Index</a>
    	<h1>Ejemplo de uso de PopTerms</h1>
    </div>
     	<div class="span0">
 		<div class="input-group input-group-lg">

		<label for="one_term">Ejemplo de palabra clave única: </label>
        <a href="#" data-popterms-server="../server/"
                     data-popterms-vocabulary="SAIJ"
                     data-popterms-target="#one_term"
        > Seleccionar un término</a>
        <br>
        <input id="one_term" class="form-control" type="text" size="100">

		</div>
		
		<br>

		<div >

		<label for="many_terms">Ejemplo para varias palabras clave: </label>
       <button data-popterms-server="../server/"
                 data-popterms-vocabulary="FAMILIA"
                 data-popterms-multiple="true"
                 data-popterms-separator=" , " 
                 data-popterms-target="#many_terms"
        > Seleccionar los términos</button>				  
				  <input id="many_terms" name="keywords" class="form-control input-lg"  size="250" >
				</div>

        <hr>


 		<div class="input-group input-group-lg">



		</div><!-- /input-group -->	    	

    	</div>

  	</div>
  </div>
</div>

    <!-- PopTerms Client JavaScript -->
    <script src="js/popterms.js"></script>
  <script>
        PopTerms.size(400, 500); 
        PopTerms.separator = " - ";
    </script>

</body></html>

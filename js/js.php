<?php
header('Content-Type: application/javascript');
include_once('../config.ws.php');
?>
$(document).ready(function (){

    // masonry home
    var $container = $('#keep');
    // initialize
    if ($container.length > 0) {
        $container.imagesLoaded( function() {
            $container.masonry({
                itemSelector: '.box',
                gutter: '.gutter-sizer',
                transitionDuration: '0.6s',
                columnWidth: '.grid-sizer'
            });
        });
    }

$("#query").autocomplete({
          source: function( request, response ) {
        $.ajax( {
          url: "<?php echo $CFG_URL_PARAM["url_site"];?>index.php",
          dataType: "json",
          data: {
            term: request.term
          },
          success: function( data ) {
            response( data );
          }
        } );
      },
      select: function(event, ui) { 
              $(this).val(ui.item.label);
              $("#searchform").submit(); }  
                
});


  $(function() {
        $('#treeTerm').tree({
           // buttonLeft: false,
            dragAndDrop: false,
            autoEscape: false,
            selectable: false,
           // closedIcon: $('<i class="fa fa-angle-right"></i>'),
           // openedIcon: $('<i class="fa fa-angle-down"></i>')
        });
    });
    $(function() {
        $('.rss').tooltip();
    });

});


$(function() {
  $("#query").focus();
});
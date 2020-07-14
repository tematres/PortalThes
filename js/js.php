<?php
header('Content-Type: application/javascript');
include_once('../config.ws.php');
?>
$(document).ready(function (){
    $("#query").autocomplete({
            source: function( request, response ) {
            $.ajax( {
              url: "<?php echo $CFG_URL_PARAM["url_site"];?>index.php",
              dataType: "json",
              data: {
                sgterm: request.term
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

});



$(function() {
      $("#query").focus();
    });
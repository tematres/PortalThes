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
                sgterm: request.term,
                v: <?php echo $_SESSION["v"];?>
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
            //buttonLeft: false,
            dragAndDrop: false,
            autoEscape: false,
            selectable: false,
            closedIcon: $('<i class="bi bi-plus-square">'),
            openedIcon: $('<i class="bi bi-dash-square">')
        });
    });

});


$(function() {
      $("#query").focus();
    });

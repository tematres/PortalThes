<?php 
require '../../config.ws.php';
include_once('config.ws.php');
include_once('fun.visual.php');
include_once('lang/'.$CFG_lang.'.php');

$tema_id = isset($_GET["tema_id"]) ? $_GET["tema_id"] : null ;
$Jcontent = ($tema_id) ? jTermData($URL_BASE,$tema_id) : jTopTermData($URL_BASE);
?>
<!DOCTYPE html>
<html lang="<?php echo $_SESSION["vocab"]["lang"]; ?>">
    <head>
          <!-- CSS Files -->

        <?php
            echo HTMLmeta($_SESSION["vocab"],'VisualVocabulary');
            echo HTMLestilosyjs();
        ?>
    <link type="text/css" href="css/hypertree.css" rel="stylesheet" />

    <!--[if IE]><script language="javascript" type="text/javascript" src="js/excanvas.js"></script><![endif]-->
    <!-- JIT Library File -->
    <script language="javascript" type="text/javascript" src="js/jit-yc.js"></script>

<!-- Source File -->
<script language="javascript" type="text/javascript" src="index.php?tema_id=<?php echo $tema_id;?>"></script>

<script type="text/javascript">
var labelType, useGradients, nativeTextSupport, animate;

(function() {
  var ua = navigator.userAgent,
      iStuff = ua.match(/iPhone/i) || ua.match(/iPad/i),
      typeOfCanvas = typeof HTMLCanvasElement,
      nativeCanvasSupport = (typeOfCanvas == 'object' || typeOfCanvas == 'function'),
      textSupport = nativeCanvasSupport 
        && (typeof document.createElement('canvas').getContext('2d').fillText == 'function');
  //I'm setting this based on the fact that ExCanvas provides text support for IE and that as of today iPhone/iPad current text support is lame
  labelType = (!nativeCanvasSupport || (textSupport && !iStuff))? 'Native' : 'HTML';
  nativeTextSupport = labelType == 'Native';
  useGradients = nativeCanvasSupport;
  animate = !(iStuff || !nativeCanvasSupport);
})();

var Log = {
  elem: false,
  write: function(text){
    if (!this.elem) 
      this.elem = document.getElementById('log');
    this.elem.innerHTML = text;
    this.elem.style.left = (500 - this.elem.offsetWidth / 2) + 'px';
  }
};


function init(){
    //init data
   var json = {<?php echo $Jcontent;?>};
    //end

    var infovis = document.getElementById('infovis');
    var w = infovis.offsetWidth - 50, h = infovis.offsetHeight - 50;
    
    //init Hypertree
    //Cambiar color DAF
    var ht = new $jit.Hypertree({
      //id of the visualization container
      injectInto: 'infovis',
      //canvas width and height
      width: w,
      height: h,
      //Change node and edge styles such as color, width and dimensions.
        Node: {
            dim: 9,
            color: "blue"
        },        
        Edge: {
            lineWidth: 2,
            color: "black"
        },        

      //Attach event handlers and add text to the labels. This method is only triggered on label creation

      onCreateLabel: function(domElement, node){

      if (node.id > 0) {            
        domElement.innerHTML = "<a href=index.php?tema_id=" + node.id +">" + node.name + "</a>";
      }   else    {
        domElement.innerHTML = node.name;
      }
      
      $jit.util.addEvent(domElement, 'click', function () {
         ht.onClick(node.id);
         });
      },
      //Change node styles when labels are placed or moved.
      onPlaceLabel: function(domElement, node){
          var style = domElement.style;
          style.display = '';
          style.cursor = 'pointer';

          if (node._depth == 0) {
              style.fontSize = "1.4em";
              //style.color = "#ddd";

          }          
          else if (node._depth == 1) {
              style.fontSize = "1.2em";
              //style.color = "#ddd";

          } else if(node._depth == 2){
              style.fontSize = "1.1em";
              style.color = "#555";
          } else {
              style.display = 'none';
          }

          var left = parseInt(style.left);
          var w = domElement.offsetWidth;
          style.left = (left - w / 2) + 'px';
      },
      

    });
    //load JSON data.
    ht.loadJSON(json);
    //compute positions and plot.
    ht.refresh();
    //end
    ht.controller.onAfterCompute();
}
</script>
    </head>
<body onload="init();">

<div id="container">

    <div id="infovis"></div>    

</div>
</body>
</html>

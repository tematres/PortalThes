<?php
header('Content-Type: application/javascript');
include_once('config.ws.php');
require 'config.ws.php';
?>
$(document).ready(function (){
function split( val ) {
      return val.split( / \s*/ );
    }
    function extractLast( term ) {
      return split( term ).pop();
    }
 

	$("#t750_a").autocomplete({
	          source: function( request, response ) {
	        $.ajax( {
	          url: "index.php",
	          separator: ", ",
	          dataType: "json",
	          data: {t750_a: request.term},
	          success: function( data ) {response( data );}
	        } );

	      },                
//https://stackoverflow.com/questions/24533562/jquery-autocomplete-special-char-trigger
       select: function( event, ui ) {
          var terms = split( this.value );
          // remove the current input
          terms.pop();
          // add the selected item
          terms.push( ui.item.value );
          // add placeholder to get the comma-and-space at the end
          terms.push( "" );
          //this.value = "$a" + this.value;
          return false;
        }	        	      
		});

	$("#t750_x").autocomplete({
	          source: function( request, response ) {
	        $.ajax( {
	          url: "index.php?s=t750_x",
	          dataType: "json",
	          data: {t750_x: request.term},
	          success: function( data ) {response( data );}
	        } );
	      },                
		});

	$("#t750_z").autocomplete({
	          source: function( request, response ) {
	        $.ajax( {
	          url: "index.php?s=t750_z",
	          dataType: "json",
	          data: {t750_z: request.term},
	          success: function( data ) {response( data );}
	        } );
	      },                
		});


	$("#t750_v").autocomplete({
	          source: function( request, response ) {
	        $.ajax( {
	          url: "index.php?s=t750_v",
	          dataType: "json",
	          data: {t750_v: request.term},
	          success: function( data ) {response( data );}
	        } );
	      },                
		});



});


/* ---------------------------- */
/* BOTAO GERAR                  */
/* ---------------------------- */


function btngerar(){
	with(document.getElementById('resultwrapper')){
	
		var resultstr = strtermocatalog(xtr('t750_a'),
						xtr('t750_x'),
						xtr('qualificadorresposta2'),
						xtr('t750_v'),
						document.getElementById('qualificadorresposta2').value.trim(),
						xtr('t750_z'),
						xtr('geograficoresposta2'));
		
		if(resultstr.trim().length > 0){
			insertBefore(document.querySelectorAll('#resultado')[document.querySelectorAll('#resultado').length-1].cloneNode(true),document.querySelectorAll('#resultado')[0]);
			with(document.querySelectorAll('#resultado')[0]){
				style.visibility='visible';
				style.display='table-row';
				childNodes[1].innerText = resultstr;				
			}
		}
	}
}

function strtermocatalog(t,q1,q2,gf,d,g1,g2){
	if(htr(t)){
		msgseterr('');
		return (ctr(t,'\<?php echo $CFG_MARC21["subtag"];?>a') + ctr(q1,'\<?php echo $CFG_MARC21["subtag"];?>x') + ctr(q2,'\<?php echo $CFG_MARC21["subtag"];?>x') + ctr(gf,'\<?php echo $CFG_MARC21["subtag"];?>v') + ctr(d,'\<?php echo $CFG_MARC21["subtag"];?>y') + ctr(g1,'\<?php echo $CFG_MARC21["subtag"];?>z') + ctr(g2,'\<?php echo $CFG_MARC21["subtag"];?>z') + '\<?php echo $CFG_MARC21["subtag"];?>2<?php echo $CFG_MARC21["t750_2"];?>');
	}
	else if(!htr(q1) && !htr(q2)){
		     if(htr(gf)) {
			msgseterr('');
			return (gf + ctr(d,'\<?php echo $CFG_MARC21["subtag"];?>y') + ctr(g1,'\<?php echo $CFG_MARC21["subtag"];?>z') + ctr(g2,'\<?php echo $CFG_MARC21["subtag"];?>z') + '\<?php echo $CFG_MARC21["subtag"];?>2<?php echo $CFG_MARC21["t750_2"];?>');
		     }
		     else if(htr(g1)) {
				msgseterr('');
				return (g1 + ctr(g2,'\<?php echo $CFG_MARC21["subtag"];?>z') + ctr(d,'\<?php echo $CFG_MARC21["subtag"];?>y') + '\<?php echo $CFG_MARC21["subtag"];?>2<?php echo $CFG_MARC21["t750_2"];?>');
			  }
			  else if(htr(g2)){
					msgseterr('');
					return (g2 + ctr(d,'\<?php echo $CFG_MARC21["subtag"];?>y') + '\<?php echo $CFG_MARC21["subtag"];?>2<?php echo $CFG_MARC21["t750_2"];?>');
				}
				else {
					msgseterr('<?php echo MARC21_error_data;?>');
					return '';
				}
	     }
	     else {
		msgseterr('Qualificador exige Termo.');
		return '';
	     }
}

function msgseterr(strmsgerr){
	if(htr(strmsgerr)){
		document.getElementById('btngerid').style.backgroundColor='red';
		document.getElementById('msgerr').innerText = strmsgerr;
	}
	else {
		document.getElementById('btngerid').style.backgroundColor='transparent';
		document.getElementById('msgerr').innerText = '';
	}
}

function ctr(strx,cif){
	if(htr(strx)){
		return (cif+strx.trim());
	}
	return ''
}

function htr(strx){
	if(strx.trim().length > 0){
		return true;
	}
	return false;
}

function xtr(idprt){
	var vparte = document.querySelector('#' + idprt).value;
	//var dparte = document.querySelector('#' + idprt).dataset['d' + idprt];

	return vparte.trim();
	
	/*
	if(vparte.trim()==dparte.trim()){
		return vparte.trim();
	}	
	return '';
	*/
}


function toggle_visibility(id) {
       var e = document.getElementById(id);
       if(e.style.display == 'block')
          e.style.display = 'none';
       else
          e.style.display = 'block';
}

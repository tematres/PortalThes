<?php
    require '../../config.ws.php';
    require 'config.ws.php';
    
    $t750_a  =  XSSprevent($_GET['t750_a']);
    if(strlen($t750_a)>= $CFG["MIN_CHAR_SEARCH"]) {
        echo getData4AutocompleterUI($URL_BASE,$t750_a);
        exit();
    };

    //sub encabezamiento
    $t750_x  =  XSSprevent($_GET['t750_x']);
    if(strlen($t750_x)>= $CFG["MIN_CHAR_SEARCH"]) {
        echo getData4AutocompleterUI($CFG_MARC21["t750_x"],$t750_x);
        exit();
    };

    //forma
    $t750_v  =  XSSprevent($_GET['t750_v']);
    if(strlen($t750_v)>= $CFG["MIN_CHAR_SEARCH"]) {        
        echo getData4AutocompleterUI($CFG_MARC21["t750_v"],$t750_v);
        exit();
    };

    //geográficos
    $t750_z  =  XSSprevent($_GET['t750_z']);
    if(strlen($t750_z)>= $CFG["MIN_CHAR_SEARCH"]) {        
        echo getData4AutocompleterUI($CFG_MARC21["t750_z"],$t750_z);
        exit();
    };
      
    require 'config.ws.php';
    
    include_once('fun.marc.php');
    // URL for the TemaTres web services provider
    $params["TEMATRES_URI_SERVICE"]=$CFG_VOCABS[$CFG["DEFVOCAB"]]["URL_BASE"];

    $vocabularyMetaData=getTemaTresData($params["TEMATRES_URI_SERVICE"]);
?>
<!DOCTYPE html>
<html lang="<?php echo $_SESSION["vocab"]["lang"];?>">
    <head>
        <?php 
            echo HTMLmeta($_SESSION["vocab"],ucfirst(MARC21_SERVICE_title));
            echo HTMLestilosyjs();            
        ?>        
        <script type="text/javascript" src="<?php echo $CFG_URL_PARAM["url_site"];?>apps/marc21/zeroc/ZeroClipboard.js"></script>        
        <script language="javascript" type="text/javascript" src="<?php echo $CFG_URL_PARAM["url_site"];?>apps/marc21/marcjs.php"></script>

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
                    <h2><?php echo ucfirst(MARC21_SERVICE_title);?></h2>
                    <p><?php echo MARC21_SERVICE_description;?></p>                                        

                </div><!--  END buscador  -->
                <div class="col-sm-8 col-md-9" id="content">
                <div class="box box-info triple">
                <form name="construtor">    
                    <div class="panel-body">
                        <div class="form-group">
                        <label for="termoresposta">
                        </label>
                        <br>
                        <input type="text" class="form-control" id="t750_a" id="t750_a" placeholder="<?php echo ucfirst(MARC21_t750_a);?>">
                        </div>

                        <div class="form-group">
                        <label class="sr-only" for="qualificador"><?php echo ucfirst(MARC21_t750_x);?></label>
                        <input type="text" class="form-control-add" name="t750_x" id="t750_x" placeholder="<?php echo ucfirst(MARC21_t750_x);?>" > 

                        <button id="b1" class="btn add-more btn-success" type="button" onclick="toggle_visibility('t750_aa2div');">+</button>
                        </div>
                        <div class="form-group" id="t750_aa2div" style="display:none;">
                        <label class="sr-only" for="qualificador2"><?php echo ucfirst(MARC21_t750_x).' '.MARC21_r;?></label>
                        <input type="text" class="form-control-add" id="qualificadorresposta2" placeholder="<?php echo ucfirst(MARC21_t750_x).' '.MARC21_r;?>">  <button id="b2" class="btn add-more btn-danger" type="button" onclick="toggle_visibility('t750_aa2div');">-</button>
                        </div>
                        <div class="form-group">
                        <label class="sr-only" for="genero"><?php echo ucfirst(MARC21_t750_v);?></label>
                        <input type="text" class="form-control" id="t750_v" name="t750_v" placeholder="<?php echo ucfirst(MARC21_t750_v);?>"/>
                        </div>
                        
                        <?php
                        if(configValue($CFG_MARC21["t750_z"])){
                            ?>

                        <div class="form-group">
                        <label class="sr-only" for="t750_z"><?php echo ucfirst(MARC21_t750_z);?></label>
                        <input type="text" class="form-control-add" id="t750_z" placeholder="<?php echo ucfirst(MARC21_t750_z);?>" > <button id="b3" class="btn add-more btn-success" type="button" onclick="toggle_visibility('geo2');">+</button>
                        </div>
                        <div id="geo2" style="display:none;">
                        <label class="sr-only" for="geografico2"><?php echo ucfirst(MARC21_t750_z).' '.MARC21_r;?></label>
                        <input type="text" class="form-control-add" id="geograficoresposta2" placeholder="<?php echo ucfirst(MARC21_t750_z).' '.MARC21_r;?>"> <button id="b4" class="btn add-more btn-danger" type="button" onclick="toggle_visibility('geo2');">-</button>
                        </div>
                        <?php
                            }
                            ?>
                        <button id="btngerid" type="button" class="btn btn-default" onclick="btngerar()" onblur="msgseterr('')"><?php echo ucfirst(MARC21_create);?></button><span id="msgerr" style="color:red;padding:5px;"></span>

                        <br><br>

                        <div class="form-group" id="resultwrapper" style="display:table;border-collapse:separate;border-spacing:5px;">
                        <div id="resultado" name="resultado" class="alert alert-success" style="visibility:hidden;display:none;">
                            <div style="display:table-cell;padding:5px;vertical-align:middle;width:100%;border:1px solid #55AA55;border-radius:4px;"></div>

                            
                            <div style="display:table-cell;vertical-align:middle;">
                                <button type="button" class="btn copy-clipboard" data-clipboard-action="copy" data-clipboard-target="#resultado" alt="<?php echo ucfirst(LABEL_copy_click);?>"><span class="glyphicon glyphicon-copy" aria-hidden="true"  title="<?php echo ucfirst(LABEL_copy_click);?>"></span></button>
                            </div>
                            <div style="display:table-cell;vertical-align:middle;">
                                <button onclick="parentNode.parentNode.parentNode.removeChild(parentNode.parentNode)"><span class="glyphicon glyphicon-remove-circle" aria-hidden="true"  title="eliminar"></span></button>
                            </div>

                            </div>

                        </div>
                    </div>
                </form>
                
                <?php echo HTMLlistSources($CFG_MARC21,$vocabularyMetaData); ?>

                </div>




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

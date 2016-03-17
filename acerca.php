<?php
require 'header.php';
?>
<!DOCTYPE html>
<html lang="es-AR">
    <head>
        <title>
            <?php echo 'Acerca de '.$CFG_URL_PARAM["title_site"]; ?>
        </title>
        <?php estilosyjs(); ?>
    </head>
    <body>
        <?php
        include_once("analyticstracking.php");
        echo HTMLglobalMenu(array("CFG_VOCABS"=>$CFG_VOCABS));
        ?>
        <div class="container">
            <div id="keep">
                <div class="grid-sizer"></div>
                <div class="gutter-sizer"></div>
                <div class="box box-pres box-pres2">
                    <h1>ACERCA DEL PROYECTO</h1>
                    <p>Vocabularios en Educación Argentina (VEA) es un servicio terminológico de información educativa en línea, accesible para todos, de uso libre y gratuito y en tiempo real.</p>
                    <div id="links" class="text-center">
                        <p>
                            <a href="http://www.educacion.gov.ar" title="Ministerio de Educación"><img src="imagenes/logome.png" alt="Ministerio de Educación"></a>
                            <a title="Biblioteca Nacional de Maestros" href="http://www.bnm.me.gov.ar/"><img src="imagenes/bndm.png" alt="Biblioteca Nacional de Maestros" title="Biblioteca Nacional de Maestros"></a>
                        </p>
                    </div>
                </div><!  END box presentación  >
                <div class="box box-info doble">
                    <div>
                        <span class="fa-stack fa-lg" style="float:left; margin-right:3px;">
                          <i class="fa fa-check-square-o fa-stack-2x"></i>
                        </span>
                        Esta herramienta provee terminología específica de educación y relacionada por medio de vocabularios controlados, desarrollados por la BNM y diferentes áreas del Ministerio de Educación de la Nación, que conforman la representación del conocimiento educativo en la Argentina.
                    </div>
                </div>
                <div class="box box-info">
                    <div>
                        <span class="fa-stack fa-lg" style="float:left; margin-right:3px;">
                          <i class="fa fa-check-square-o fa-stack-2x"></i>
                        </span>
                        Brinda herramientas para encontrar, recuperar y visualizar la información educativa en la Argentina.
                    </div>
                </div>
                <div class="box box-info">
                    <div>
                        <span class="fa-stack fa-lg" style="float:left; margin-right:3px;">
                          <i class="fa fa-check-square-o fa-stack-2x"></i>
                        </span>
                        Las unidades de información del área educativa encontrarán en VEA un instrumento esencial para la calificación de contenidos de catálogos, documentos y repositorios.
                    </div>
                </div>
                <div class="box box-info">
                    <div>
                        <span class="fa-stack fa-lg" style="float:left; margin-right:3px;">
                          <i class="fa fa-check-square-o fa-stack-2x"></i>
                        </span>
                        Se brinda la infraestructura necesaria para el desarrollo de servicios web que faciliten la producción, transmisión, tratamiento y gestión del conocimiento especializado educativo.
                    </div>
                </div>
                <div class="box box-info">
                    <div>
                        <span class="fa-stack fa-lg" style="float:left; margin-right:3px;">
                          <i class="fa fa-check-square-o fa-stack-2x"></i>
                        </span>
                        Permite fortalecer la vinculación entre el Estado y la ciudadanía y garantizar una mayor eficiencia y transparencia en la gestión pública de la información, dando así cumplimiento a los objetivos enunciados por el Plan Nacional de Gobierno Electrónico.
                    </div>
                </div>
                <div class="box box-info">
                    <div>
                        <span class="fa-stack fa-lg" style="float:left; margin-right:3px;">
                          <i class="fa fa-check-square-o fa-stack-2x"></i>
                        </span>
                        Se ofrece a las unidades de información del Sistema Educativo Nacional la posibilidad de <a href='crea.php'>gestionar en línea sus propios vocabularios</a>, que serán publicados en VEA.
                    </div>
                </div>
            </div><!  END keep  >
            <?php
                echo HTMLglobalFooter(array());
            ?>
        </div><!  END container  >
    </body>
</html>

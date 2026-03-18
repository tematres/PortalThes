<?php
require 'config.ws.php';

if (is_array($CFG_VOCABS[$v])) {
    $v=configValue(array2value("v",$_GET), $CFG["DEFVOCAB"]);
}

    $searchq  =  XSSprevent(array2value('sgterm',$_GET));
if (strlen($searchq)>= $CFG["MIN_CHAR_SEARCH"]) {
    header('Content-type: application/json; charset=utf-8');
    echo getData4AutocompleterUI($URL_BASE, $searchq,$v);
    exit();
};
    header('Content-Type: text/html; charset=UTF-8');

$c=isset($_GET['c']) ? XSSprevent($_GET['c']) : '';
$term='';
$array_node=array();
$task=array2value("task",$_GET);
$task=is_array($vocabularyMetadata) ? $task : 'error';


switch ($task) {
    //datos de un término == term data
    case 'term':
        //sanitiar variables
        $tema_id = is_numeric($_GET['arg']) ? intval($_GET['arg']) : 0;
        if ($tema_id > 0) {
            $dataTerm=getURLdata($URL_BASE.'?task=fetchTerm&arg='.$tema_id);
            $htmlTerm=data2htmlTerm($dataTerm, array("vocab_code"=>$v));
            $term= (string) FixEncoding($dataTerm->result->term->string);
            $term_id= (int) $dataTerm->result->term->term_id;
            $arrayTermData=$htmlTerm["resultData"];
            $task='fetchTerm';
            //Nodes and archs to vis.js
            $array_node=is_array($htmlTerm["data4vis"]) ? $htmlTerm["data4vis"]["nodes"] : array();
            $array_edge=is_array($htmlTerm["data4vis"]) ? $htmlTerm["data4vis"]["edges"] : array();
        }
        break;
    //datos de una letra == char data
    case 'letter':
        $letter     = isset($_GET['arg']) ? XSSprevent($_GET['arg']) : null;
        $dataTerm   = getURLdata($URL_BASE.'?task=letter&arg='.$letter);
        $link_type  = $CFG_VOCABS["$v"]["CONFIG"]["HOME_GRID_SIZE"] ?? 0;
        $htmlTerm   = data2html4Letter($dataTerm, array("vocab_code"=>$v,"div_title"=>ucfirst(LABEL_start),"link_type"=>$link_type));
        $task       = 'letter';
        break;
    //ultimos términos
    case 'fetchLast':
        $dataTerm   = getURLdata($URL_BASE.'?task=fetchLast');
        $htmlTerm   = data2html4LastTerms($dataTerm, array("vocab_code"=>$v,"div_title"=>ucfirst(LABEL_lastChanges)));
        $task       = 'fetchLast';
        break;
    //búsqueda  == search
    case 'search':
        //sanitiar variables
        $string = isset($_GET['arg']) ? XSSprevent($_GET['arg']) : 'null';
        $searchType = isset($_GET['optSearch']) ? XSSprevent($_GET['optSearch']) : 'optSearchDefault';
        if (strlen($string) > 0) {
            $command=str_replace(array("optSearchExact","optSearchDefault","optSearchMatch","optSearchNotes"),array("fetch","search","match","searchNotes"), $searchType);
            $dataTerm = getURLdata($URL_BASE.'?task='.$command.'&arg='.urlencode($string));
            //check for unique results
            if (((int) $dataTerm->resume->cant_result == 1) && (mb_strtoupper((string) $dataTerm->result->term->string) == mb_strtoupper($string))) {
                header('Location:'.$CFG_URL_PARAM["url_site"].$CFG_URL_PARAM["v"].$v.$CFG_URL_PARAM["fetchTerm"].$dataTerm->result->term->term_id);
            }
            $htmlSearchTerms = data2html4Search($dataTerm, /*ucfirst($message["searchExpresion"]).' : <i>'.*/$string/*.'</i>'*/, array("vocab_code"=>$v));
            $task = 'search';
        } else {
            $task = 'topterms';
        }

        break;
    //error
    case 'error':

        break;
    default:
        $task       = 'topterms';
}
?>
<!DOCTYPE html>
<html lang="<?php echo $_SESSION["vocab"]["lang"];?>">
    <head>
<?php
    echo HTMLmeta($_SESSION["vocab"], $term);
    echo HTMLestilosyjs($v);
?>
<script>
function toggleButtonText(button) {
  if (button.getAttribute('aria-expanded') === 'true') {
    button.textContent = '<?php echo ucfirst(LABEL_showLess);?>';
  } else {
    button.textContent = '<?php echo ucfirst(LABEL_showMore);?>';
  }
}
</script>
</head>
    <body>
    <div class="container">
        <?php
            echo HTMLglobalMenu(array("CFG_VOCABS"=>$CFG_VOCABS,"vocab_code"=>$v));
            echo HTMLformSearch();
            if (is_array($CFG_VOCABS[$v]["ALPHA"])) {
              echo HTMLalphaNav($CFG_VOCABS[$v]["ALPHA"], array2value('letter',$_GET), array("vocab_code"=>$v));
            }
        ?>
        <!-- Resultados y visualización -->
        <div class="row">
                    <?php
                    $_SHOW_TREE_TERMS=array2value("SHOW_TREE_TERMS",$CFG_VOCABS[$v]["CONFIG"]);
                    $_HOME_GRID_SIZE=array2value("HOME_GRID_SIZE",$CFG_VOCABS[$v]["CONFIG"]);
                    $_FEATURE_NOTE=array2value("FEATURE_NOTE",$CFG_VOCABS[$v]["CONFIG"]);
                    $_SHOW_CLOUD_TERMS=array2value("SHOW_CLOUD_TERMS",$CFG_VOCABS[$v]["CONFIG"]);

                    switch ($task) {
                        case 'search':
                            echo $htmlSearchTerms;
                            break;
                        case 'letter':
                            echo $htmlTerm["results"];
                            break;
                        case 'fetchLast':
                            echo $htmlTerm["results"];
                            break;
                        case 'fetchTerm':
                            echo HTMLtermDetaills($htmlTerm, $dataTerm, $vocabularyMetadata);
                            break;
                        case 'mdata':
                            echo HTMLmetadataVocabulary($CFG_VOCABS[$v]);
                            break;
                        case 'error':
                            echo HTMLerrorVocabulary($v);
                            break;

                        default:
                            if ($_SHOW_TREE_TERMS==1) {
                                echo '<div id="treeTerm" data-url="'.$CFG_URL_PARAM["url_site"].'common/treedata.php?v='.$v.'"></div><!-- #topterms -->';
                            }
                            if ($_HOME_GRID_SIZE>1) {
                                echo HTMLgridTerminos($v,configValue($_FEATURE_NOTE,"NA"),$_HOME_GRID_SIZE);
                            }
                            if ($_SHOW_CLOUD_TERMS==1) {
                                echo HTMLcloudTerms($v,20);
                            }
                            break;
                    }                    
                    ?>
            </div><!--  END row  -->
                    <?php
                    echo HTMLglobalFooter(array("vocab_code"=>$v,"vocabularyMetadata"=>$vocabularyMetadata));
            ?>
       </div><!--  END container  -->
    <?php if(count($array_node)>1) : ;?>
    <script type="text/javascript">

        let nodes = new vis.DataSet(<?php echo json_encode($array_node);?>);
        let edges = new vis.DataSet(<?php echo json_encode($array_edge);?>);

        let data = {
            nodes: nodes,
            edges: edges,
        };
        let options = {
            interaction:{
            hover:true,
            },
            groups: {
                'GBT': {color:{background:'#FADD91'}, borderWidth:3},
                'GRT': {color:{background:'#81D9E3'}, borderWidth:3},
                'GNT': {color:{background:'#B2FF8F'}, borderWidth:3}
            },
            physics: {
                    stabilization: false,
                    barnesHut: {
                    springLength: 200,
                    },
            },
            nodes:{
                color: '#B982FF',
                fixed: false,
                font: '20px arial black',
                scaling: {
                    label: false,
                },
                shadow: true,
                shape: 'box',
                margin: 10,
                multi: false,
            },
            edges: {
                arrows: '',
                color: 'black',
                scaling: {
                    label: false,
                },
                shadow: true,
            },


        };
        let container = document.getElementById("graphterm");
        let network = new vis.Network(container, data, options);
    </script>
<?php endif;?>
  <!-- Modal de Bootstrap -->
    <div class="modal fade" id="termModal" tabindex="-1" aria-labelledby="termModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="termModalLabel">Cargando...</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body" id="modal-body-content">
                    <div class="text-center">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Obteniendo información...</span>
                        </div>
                        <p class="mt-2">Obteniendo información del término...</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <a href="#" id="modalFullLink" class="btn btn-primary" target="_blank">Ver página completa</a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Script con llamadas a la API - VERSIÓN CON TODAS LAS NOTAS -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const termModalEl = document.getElementById('termModal');
            if (!termModalEl) return;
            const termModal = new bootstrap.Modal(termModalEl);
            
            // IMPORTANTE: Cambia esta URL según tu vocabulario
            //const baseUrl = '<?php echo $URL_BASE;?>';
            const baseUrl = '<?php echo $CFG_URL_PARAM["url_site"];?>common/proxy-notes.php?url=' + ('<?php echo $CFG_URL_PARAM["url_site"];?>');
            
            // Función para obtener datos del término
            async function fetchTermData(termId) {
                const url = `${baseUrl}/api/fetchTerm/${termId}/json/<?php echo $v;?>`;
                console.log('📡 Solicitando término:', url);
                
                try {
                    const response = await fetch(url);
                    if (!response.ok) throw new Error(`HTTP ${response.status}`);
                    const data = await response.json();
                    console.log('📥 Respuesta término:', data);
                    
                    if (data.result?.term) {
                        return data.result.term;
                    }
                    return null;
                } catch (error) {
                    console.error('❌ Error en fetchTermData:', error);
                    return null;
                }
            }
            
            // Función para obtener TODAS las notas del término
            async function fetchAllTermNotes(termId) {
                const url = `${baseUrl}/api/fetchNotes/${termId}/json/<?php echo $v;?>`;
                console.log('📡 Solicitando todas las notas:', url);
                
                try {
                    const response = await fetch(url);
                    if (!response.ok) throw new Error(`HTTP ${response.status}`);
                    const data = await response.json();
                    console.log('📥 Respuesta notas (completa):', data);
                    
                    // Array para almacenar todas las notas encontradas
                    const allNotes = [];
                    
                    // La API puede devolver múltiples notas en diferentes estructuras
                    if (data.result) {
                        // Caso 1: Objeto con IDs como claves
                        for (let key in data.result) {
                            const note = data.result[key];
                            if (note && note.note_text) {
                                allNotes.push({
                                    id: note.note_id || key,
                                    term_id: note.term_id || termId,
                                    type: note.note_type || 'NA',
                                    text: note.note_text,
                                    lang: note.note_lang || 'es',
                                    source: note.note_src || null
                                });
                            }
                        }
                        
                        // Caso 2: Array de notas (si existe)
                        if (Array.isArray(data.result.notes)) {
                            data.result.notes.forEach(note => {
                                if (note && note.note_text) {
                                    allNotes.push({
                                        id: note.note_id,
                                        term_id: note.term_id || termId,
                                        type: note.note_type || 'NA',
                                        text: note.note_text,
                                        lang: note.note_lang || 'es',
                                        source: note.note_src || null
                                    });
                                }
                            });
                        }
                    }
                    
                    // Caso 3: Si data es directamente un array
                    if (Array.isArray(data)) {
                        data.forEach(note => {
                            if (note && note.note_text) {
                                allNotes.push({
                                    id: note.note_id,
                                    term_id: note.term_id || termId,
                                    type: note.note_type || 'NA',
                                    text: note.note_text,
                                    lang: note.note_lang || 'es',
                                    source: note.note_src || null
                                });
                            }
                        });
                    }
                    
                    console.log('📊 Notas procesadas:', allNotes);
                    return allNotes;
                    
                } catch (error) {
                    console.error('❌ Error en fetchAllTermNotes:', error);
                    return [];
                }
            }
            
            // Función para obtener el nombre del tipo de nota
            function getNoteTypeName(type) {
                const types = {
                    'NA': 'Nota de alcance',
                    'NB': 'Nota bibliográfica',
                    'NC': 'Nota de catalogación',
                    'ND': 'Nota de definición',
                    'NH': 'Nota histórica',
                    'NR': 'Nota de referencia',
                    'NU': 'Nota de uso'
                };
                return types[type] || `Nota tipo ${type}`;
            }
            
            // Función principal
            async function loadTermDetails(termId) {
                console.log(`🚀 Cargando término ID: ${termId}`);
                
                // Mostrar estado de carga
                document.getElementById('termModalLabel').textContent = 'Cargando...';
                document.getElementById('modal-body-content').innerHTML = `
                    <div class="text-center">
                        <div class="spinner-border text-primary" role="status"></div>
                        <p class="mt-2">Consultando APIs...</p>
                        <small class="text-muted">Término ID: ${termId}</small>
                    </div>
                `;
                
                // Llamadas en paralelo
                const [termData, allNotes] = await Promise.all([
                    fetchTermData(termId),
                    fetchAllTermNotes(termId)
                ]);
                
                console.log('📊 Datos procesados:', { termData, allNotes });
                
                // Construir contenido
                if (termData) {
                    document.getElementById('termModalLabel').textContent = termData.string || 'Término';
                    
                    let contentHtml = '';
                    
                    // SECCIÓN 1: Mostrar TODAS las notas
                    if (allNotes && allNotes.length > 0) {
                        contentHtml += `<h6 class="fw-bold mb-3">Notas (${allNotes.length}):</h6>`;
                        
                        allNotes.forEach((note, index) => {
                            contentHtml += `
                                <div class="note-card">
                                    <span class="note-type" data-type="${note.type}">
                                        <i class="bi bi-tag me-1"></i>${getNoteTypeName(note.type)}
                                    </span>
                                    <div class="note-text">${note.text}</div>
                                    <div class="note-meta">
                                        <i class="bi bi-info-circle me-1"></i> ID: ${note.id} | 
                                        <i class="bi bi-translate me-1"></i> ${note.lang.toUpperCase()}
                                        ${note.source ? ` | <i class="bi bi-book me-1"></i> Fuente: ${note.source}` : ''}
                                    </div>
                                </div>
                            `;
                        });
                    } else {
                        contentHtml += `
                            <div class="alert alert-info">
                                <i class="bi bi-info-circle"></i> No hay notas disponibles para este término.
                            </div>
                        `;
                    }
                    
                    // SECCIÓN 2: Metadatos del término
                    contentHtml += `
                        <div class="mt-4 p-3 bg-light rounded">
                            <h6 class="fw-bold mb-3"><i class="bi bi-gear me-2"></i>Metadatos:</h6>
                            <div class="row">
                            </div>
                            ${termData.date_create ? `<p class="mb-0 mt-2"><strong>Fecha creación:</strong> ${new Date(termData.date_create).toLocaleDateString()}</p>` : ''}
                        </div>
                    `;
                    
                    document.getElementById('modal-body-content').innerHTML = contentHtml;
                    
                    // Actualizar enlace
                    const fullLink = document.getElementById('modalFullLink');
                    fullLink.href = `<?php echo $CFG_URL_PARAM["url_site"];?>/<?php echo $v;?>/term/${termId}`;
                    fullLink.style.display = 'inline-block';
                    
                } else {
                    // Error
                    document.getElementById('termModalLabel').textContent = 'Error';
                    document.getElementById('modal-body-content').innerHTML = `
                        <div class="alert alert-danger">
                            <i class="bi bi-exclamation-triangle"></i>
                            No se pudo encontrar el término ID ${termId}
                        </div>
                    `;
                    document.getElementById('modalFullLink').style.display = 'none';
                }
            }
            
            // Asignar eventos
            document.querySelectorAll('.term-link').forEach(link => {
                link.addEventListener('click', async (e) => {
                    e.preventDefault();
                    const termId = link.getAttribute('data-term-id');
                    if (termId) {
                        termModal.show();
                        await loadTermDetails(termId);
                    }
                });
            });
            
            console.log('✅ Script cargado. Base URL:', baseUrl);
        });
    </script>    </body>
</html>

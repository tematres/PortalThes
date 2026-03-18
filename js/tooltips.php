<?php
header('Content-Type: application/javascript');
include_once('../config.ws.php');
?>
        document.addEventListener('DOMContentLoaded', function() {
            // Obtener todos los enlaces de términos
            const termLinks = document.querySelectorAll('.term-link');
            const overlay = document.querySelector('.overlay');
            const tooltipTemplate = document.getElementById('tooltip-template');
            const connectionStatus = document.getElementById('connection-status');
            
            // URL del proxy PHP
            const PROXY_URL = '<?php echo $CFG_URL_PARAM["url_site"];?>common/proxy-notes.php';
            
            // Función para probar la conexión con el proxy
            async function testProxyConnection() {
                try {
                    const response = await fetch(PROXY_URL + '?test=1');
                    const data = await response.json();
                    
                    if (data.status === 'success') {
                        //connectionStatus.textContent = 'Conexión con el servidor establecida correctamente';
                        //connectionStatus.className = 'connection-status status-connected';
                        return true;
                    } else {
                        throw new Error('Respuesta inesperada del proxy');
                    }
                } catch (error) {
                    console.error('Error probando conexión con proxy:', error);
                    connectionStatus.textContent = '❌ Error de conexión con el proxy PHP. Usando datos de ejemplo.';
                    connectionStatus.className = 'connection-status status-error';
                    return false;
                }
            }
            
            // Función para obtener datos a través del proxy PHP
            async function fetchTermData(termId) {
                try {
                    const url = `${PROXY_URL}?term_id=${termId}`;
                    const response = await fetch(url);
                    
                    if (!response.ok) {
                        throw new Error(`Error HTTP: ${response.status}`);
                    }
                    
                    const data = await response.json();
                    
                    // Verificar la nueva estructura de respuesta
                    if (data.status === 'success' && data.data && data.data.notes) {
                        return data.data.notes;
                    } else if (data.status === 'error') {
                        throw new Error(data.message || 'Error en el proxy');
                    } else {
                        throw new Error('Estructura de datos inesperada');
                    }
                } catch (error) {
                    console.error('Error al obtener datos mediante proxy:', error);
                    return null;
                }
            }
            
            // Función para formatear las notas según el nuevo formato
            function formatNotes(notes) {
                if (!notes || Object.keys(notes).length === 0) {
                    return '<div class="no-notes">No hay notas disponibles para este término.</div>';
                }
                
                let htmlContent = '';
                
                // Convertir el objeto de notas a array y procesar cada nota
                Object.values(notes).forEach(note => {
                    const noteType = note.note_type || 'unknown';
                    const noteLang = note.note_lang || 'unknown';
                    const noteText = note.note_text || '';
                    const noteId = note.note_id || '';
                    const noteSrc = note.note_src || '';
                    
                    htmlContent += `
                        <div class="note-item">
                            <div>
                                <span class="note-type note-type-${noteType}">${noteType}</span>
                                <span class="note-lang">${noteLang}</span>
                            </div>
                            <div class="note-text">${noteText}</div>
                            <div class="note-meta">
                                ID: ${noteId}
                                ${noteSrc ? `| Fuente: ${noteSrc}` : ''}
                            </div>
                        </div>
                    `;
                });
                
                return htmlContent;
            }
            
            // Función para mostrar el tooltip con los datos
            function showTooltip(termText, termId, notes, useFallback = false) {
                const newTooltip = tooltipTemplate.cloneNode(true);
                newTooltip.id = `tooltip-${Date.now()}`;
                
                const title = newTooltip.querySelector('.tooltip-title');
                const content = newTooltip.querySelector('.tooltip-content');
                
                title.textContent = termText;
                
                if (notes && Object.keys(notes).length > 0) {
                    let htmlContent = '';
                    
                    if (useFallback) {
                        htmlContent = `<div class="success">
                            <p>💡 Usando datos de ejemplo (proxy no disponible)</p>
                        </div>`;
                    } else {
                        
                        //htmlContent = `<a href="<?php echo $CFG_URL_PARAM["url_site"].$CFG_URL_PARAM["v"].$_SESSION["v"].$CFG_URL_PARAM["fetchTerm"];?>${termId}">${termText}</a>`;
                    }
                    
                    htmlContent += formatNotes(notes);
                    htmlContent += `<div class="text-end"><a class="term-badge" href="<?php echo $CFG_URL_PARAM["url_site"].$CFG_URL_PARAM["v"].$_SESSION["v"].$CFG_URL_PARAM["fetchTerm"];?>${termId}" title="${termText}"><?php echo ucfirst(LABEL_showMore);?></a></div>`;
                    content.innerHTML = htmlContent;
                } else {

                    htmlContent = `<div class="text-end"><a class="term-badge" href="<?php echo $CFG_URL_PARAM["url_site"].$CFG_URL_PARAM["v"].$_SESSION["v"].$CFG_URL_PARAM["fetchTerm"];?>${termId}" title="${termText}"><?php echo ucfirst(LABEL_showMore);?></a></div>`;

                    content.innerHTML = htmlContent;
                }
                
                document.body.appendChild(newTooltip);
                
                setTimeout(() => {
                    newTooltip.classList.add('active');
                    overlay.classList.add('active');
                    document.body.style.overflow = 'hidden';
                }, 10);
                
                const closeBtn = newTooltip.querySelector('.close-btn');
                closeBtn.addEventListener('click', function() {
                    closeTooltip(newTooltip);
                });
                
                return newTooltip;
            }
            
            // Función para cerrar el tooltip
            function closeTooltip(tooltip) {
                tooltip.classList.remove('active');
                overlay.classList.remove('active');
                document.body.style.overflow = 'auto';
                
                setTimeout(() => {
                    if (tooltip.parentNode) {
                        tooltip.parentNode.removeChild(tooltip);
                    }
                }, 400);
            }
            
            // Agregar evento de clic a cada enlace
            termLinks.forEach(link => {
                link.addEventListener('click', async function() {
                    const termId = this.getAttribute('data-term-id');
                    const vocabId = this.getAttribute('data-vocab-id');
                    const termText = this.textContent;
                    
                    // Mostrar tooltip de carga
                    const loadingTooltip = showTooltip(termText, termId, null);
                    
                    // Obtener datos mediante el proxy
                    const termNotes = await fetchTermData(termId);
                    const useFallback = !await testProxyConnection();
                    
                    // Cerrar tooltip de carga
                    closeTooltip(loadingTooltip);
                    
                    // Mostrar tooltip con los datos obtenidos
                    showTooltip(termText, termId, termNotes, useFallback);
                });
            });
            
            // Cerrar tooltip al hacer clic en el overlay
            overlay.addEventListener('click', function() {
                const activeTooltip = document.querySelector('.tooltip.active');
                if (activeTooltip) {
                    closeTooltip(activeTooltip);
                }
            });
            
            // Cerrar tooltip con la tecla Escape
            document.addEventListener('keydown', function(event) {
                if (event.key === 'Escape') {
                    const activeTooltip = document.querySelector('.tooltip.active');
                    if (activeTooltip) {
                        closeTooltip(activeTooltip);
                    }
                }
            });
            
            // Probar conexión al cargar la página
            testProxyConnection();
        });
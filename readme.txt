X Revisar footer que no aparece en index
- revisar footer hacer customizable
X revisar armado de miga de pan: se vé mal el último término
X revisar despliegue de notas
- revisar autoridades:
X sacar box de búsqueda en sugerencias
- revisar qué hacer con términos generales
- metadatos que se habran en otra pestaña
- despliegue de códigos
patricia
stop over

48 72: nro tramite: cf643873

uv028937_omeka

uv028937_esteban
atlantacampeon


SELECT s.stem,match(s.stem) AGAINST ('comun autor' IN NATURAL LANGUAGE MODE) as score
FROM vuce__stem s
        WHERE MATCH (stem)
        AGAINST ('comun autor s' IN NATURAL LANGUAGE MODE);
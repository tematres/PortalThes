# PortalThes: Portal Web para Servicios Terminológicos
PortalThes es una herramienta web desarrollada en PHP que actúa como un portal para exponer y explotar servicios terminológicos. Permite consultar y navegar por vocabularios controlados como diccionarios, glosarios, tesauros y ontologías que estén publicados a través del servicio web de TemaTres.

## Requisitos del Sistema
Servidor Web: Apache (recomendado) o Nginx.

* PHP: Versión 5.6 o superior (recomendado PHP 7.x u 8.x).

* Extensiones PHP: Las extensiones estándar (curl, xml) suelen ser necesarias para consumir los servicios web.

* Rewrite Module: Se recomienda habilitar mod_rewrite de Apache para URLs más limpias (opcional pero recomendado).

* Navegador: Un navegador web con JavaScript habilitado.

## Instalación
Sigue estos pasos para instalar PortalThes en tu servidor:

1. Descargar el código fuente:
Obtén el código de PortalThes y descomprímelo en el directorio raíz de tu servidor web (por ejemplo, /var/www/html/portalthes/ o htdocs/portalthes/).

2. Permisos de archivos:
Asegúrate de que el servidor web tenga permisos de lectura en todos los archivos del directorio. Para directorios de caché (si existen en el futuro), podrían ser necesarios permisos de escritura.

3. Configurar el archivo config.ws.php:
El archivo principal de configuración es config.ws.php. 
Configuración
Toda la configuración se gestiona a través de arrays de PHP en los archivos config.ws.php. A continuación, se detallan las variables más importantes.

## Los índices numéricos (1, 2, 3...) son los códigos internos que usa el portal.
```
$CFG_VOCABS["1"] = array(
    // Código interno (debe coincidir con el índice del array).
    "CODE"      => "1",
    // URL de la instalación de TemaTres (DEBE terminar con '/').
    "URL_BASE"  => "http://localhost/tematres/", // ¡IMPORTANTE! Apunta a la raíz de TemaTres, no al servicio.
    // Configuraciones adicionales de la interfaz para este vocabulario.
    "CONFIG"    => array(
        "SHOW_TREE_TERMS" => 1,  // Muestra el árbol de navegación en la home del vocabulario (1 = sí, 0 = no).
        "SHOW_CLOUD_TERMS" => 1, // Muestra la nube de términos en la home del vocabulario (1 = sí, 0 = no).
        "HOME_GRID_SIZE" => 0,   // Tamaño de la grilla en la home (0 = desactivado).
        "FEATURE_NOTE" => "NA"   // Nota destacada o de alcance.
    ),
    // Caracteres para el índice alfabético global.
    "ALPHA"     => array("A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z")
);
```
### Ejemplo de un segundo vocabulario:
```
$CFG_VOCABS["2"] = array(
    "CODE"      => "2",
    "URL_BASE"  => "https://otro-servidor.com/mi-tesauro/",
    "CONFIG"    => array(
        "SHOW_TREE_TERMS" => 0,
        "SHOW_CLOUD_TERMS" => 1,
        "HOME_GRID_SIZE" => 5,
        "FEATURE_NOTE" => "DEF"
    ),
    "ALPHA"     => array("A", "B", "C", "D", "E")
);
```

## Índice del vocabulario por defecto (debe existir en $CFG_VOCABS).
```
$CFG["DEFVOCAB"] = "1";
```

## Array con los índices de los vocabularios habilitados.
```
$CFG["VOCABS"] = array(1, 2); // Aquí solo se muestran los vocabularios con código 1 y 2.
```
## Personalización de notas: puede cambiar las etiquetas por defecto de los diferentes tipos de notas que pueden tener los términos.
```
$CFG["LOCAL_NOTES"]["DEF"] = "Definición";
$CFG["LOCAL_NOTES"]["NA"]  = "Nota de alcance";
$CFG["LOCAL_NOTES"]["NB"]  = "Nota bibliográfica";
$CFG["LOCAL_NOTES"]["EX"]  = "Ejemplo de uso";
// ... puedes agregar o modificar más.
```

## Uso de URLs Amigables (mod_rewrite)
```
/* 
Por defecto, la configuración asume que tienes mod_rewrite habilitado. Si tu servidor no lo soporta, puedes cambiar a URLs con parámetros GET. Para ello, en tu config.ws.php, debes comentar las líneas de "mod_rewrite" y descomentar las líneas de "GET".
Si optas por URLs amigables, asegúrate de que el archivo .htaccess (si usas Apache) esté presente y configurado correctamente en el directorio raíz de PortalThes.
*/

// --- Si NO tienes mod_rewrite habilitado, usa esta sección ---
/*
$CFG_URL_PARAM["fetchTerm"]       = '&task=term&arg=';
$CFG_URL_PARAM["search"]          = '&task=search&arg=';
$CFG_URL_PARAM["letter"]          = '&task=letter&arg=';
$CFG_URL_PARAM["v"]               = 'index.php?v=';
$CFG_URL_PARAM["topterms"]        = 'index.php?v=';
*/

// --- Si TIENES mod_rewrite habilitado (recomendado), usa esta sección ---
// (Estas líneas ya están activas en config.ws.php por defecto)
$CFG_URL_PARAM["URIfetchTerm"]  = '/fetchTerm/';
$CFG_URL_PARAM["v"]         = '';
$CFG_URL_PARAM["fetchTerm"] = '/term/';
$CFG_URL_PARAM["search"]    = '/search/';
$CFG_URL_PARAM["letter"]    = '/letter/';
$CFG_URL_PARAM["topterms"]  = '/';
```

## --- Modo Debug ---
```
// Actívalo solo para desarrollo o resolución de problemas (1 = activado, 0 = desactivado).
$CFG["debugMode"] = "0";
```

## --- Codificación ---
```
// Codificación de caracteres para la salida HTML (generalmente UTF-8).
$CFG["ENCODE"] = 'UTF-8';
```

## --- Idioma de la Interfaz ---
```
// Define el idioma de los textos del portal.
// Valores posibles: 'es_AR' (español), 'en' (inglés), etc.
$lang_tematres = "es_AR";
```

## --- Búsqueda ---
```
// Longitud mínima de caracteres para realizar una búsqueda.
$CFG["MIN_CHAR_SEARCH"] = 2;
```

## --- Google Analytics (opcional) ---
```
// Si tienes Google Analytics, reemplaza '0' con tu ID de seguimiento (GA_TRACKING_ID).
// Ejemplo: $CFG["GA_TRACKING_ID"] = 'UA-12345678-1';
$CFG["GA_TRACKING_ID"] = '0';
```

## --- Verificación HTTPS ---
```
// Para entornos de desarrollo con certificados SSL autofirmados, puedes desactivar la verificación.
// 0 = No verificar certificados SSL (menos seguro, útil para pruebas locales).
// 1 = Verificar certificados SSL (recomendado para entornos de producción).
$CFG["CHK_HTTPS"] = 0;
```

## URL base de tu instalación de PortalThes.
```
$CFG_URL_PARAM["url_site"]      = 'http://localhost/portalthes/';

// Nombre corto del sitio (se usa en títulos de navegación).
$CFG_URL_PARAM["name_site"]     = 'Mi Portal Terminológico';

// Título principal del sitio (se muestra en la cabecera de la página).
$CFG_URL_PARAM["title_site"]    = 'Portal de Vocabularios Controlados';

// Una breve descripción o lema del sitio.
$CFG_URL_PARAM["site_info"]     = 'Consulta y exploración de tesauros, glosarios y ontologías.';

```

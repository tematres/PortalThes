<?php

    /* proxy para datos de autocompletar */
    include_once('../config.ws.php');
    $searchq        =   XSSprevent($_GET['query']);
if (!$searchq) {
    return;
}
if (strlen($searchq)>= $CFG["MIN_CHAR_SEARCH"]) {
    echo getData4AutocompleterUI($URL_BASE, $searchq);
    exit;
}

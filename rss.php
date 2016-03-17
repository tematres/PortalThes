<?php
    include_once('config.ws.php');
    include_once('common/vocabularyservices.php');
    if (isset($_GET["v"]))
        return fetchRSS($URL_BASE,array("vocab_code"=>$_GET["v"]));

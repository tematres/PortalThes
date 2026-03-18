<?php
include('../config.ws.php');
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type');

$url = $_GET['url'] ?? '';
if (!$url) {
    echo json_encode(['error' => 'URL no especificada']);
    exit;
}
$params_url = explode("/",strrchr(parse_url($url, PHP_URL_PATH), 'api/'));


//((int) $var == $var) 
$term_id=(is_int($params_url[2]) || ctype_digit($params_url[2])) ? $params_url[2] : '';
$task=(in_array($params_url[1],array('fetchNotes','fetchTerm'))) ? $params_url[1]: '';
$vocab_id=(in_array($params_url[4],$CFG["VOCABS"])) ? $params_url[4]: '';


$url2=$CFG_VOCABS[$vocab_id]["URL_BASE"].'api/'.$task.'/'.$term_id.'/json';

$ch = curl_init($url2);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

http_response_code($httpCode);
echo $response;
?>

# Portalthes
Web tool to publish thesaurus and controlled vocabularies in the web. Portalthes use TemaTres web services to expose data and services based on vocabularies


#### Technical requirements
- HTTP server with PHP 5.x
- Access to public WWW
- cURL mod or allow_url_fopen enable


#### Config title and presentation data of the site

##### URL of the site
$CFG_URL_PARAM["url_site"]      ='http://vocabularyserver.com/portalthes/';

##### Title siste to publish 
$CFG_URL_PARAM["title_site"]    = 'Portal de Vocabularios ';

##### Site info to publish in footer
$CFG_URL_PARAM["site_info"]='Lorem ipsum 0';

##### Site info to publish in footer line 1
$CFG_URL_PARAM["site_info_line1"]='Lorem ipsum 1';

##### Site info to publish in footer line 2
$CFG_URL_PARAM["site_info_line2"]='Lorem ipsum 2';


#### Config provider of terminological data

##### Internal code for de vocab. must be the same of the array key
$CFG_VOCABS["1"]["CODE"]       	= "1"; // must be the same of the array key

#####  URL of tematres web services endpoint
$CFG_VOCABS["1"]["URL_BASE"]   	= 'http://www.r020.com.ar/tematres/demo/services.php'; // tematres web services provider URL

##### List of letters to publish. TO donto publish, config empty array: array()
$CFG_VOCABS["1"]["ALPHA"]      	= array("A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z"); // list of letter to publish in alphabetic menu

##### Show hieraquical menu in home
$CFG_VOCABS["1"]["SHOW_TREE"]  	= 1; // show (1 )or not (0 ) hieraquical menu in home

##### Suggestion form 
$CFG_VOCABS["1"]["SGS"]		 	= 1; // enable suggestion form to add or change terms. Use the mail contact provided in your vocabulary

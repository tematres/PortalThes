# PortalThes

Web tool to publish thesaurus and controlled vocabularies in the web. PortalThes use TemaTres web services to expose data and services based on vocabularies 

Demo in https://vocabularyserver.com/portalthes/


## Config PortalThes
* Edit the config.ws.php file available in the root directory.

/** internal and arbitrary code to identify each vocab. This code must to be the same used in $CFG_VOCABS["x"] array.  */
`$CFG_VOCABS["1"]["CODE"]        = "1"; `

/** URL of the tematres instance */
`$CFG_VOCABS["1"]["URL_BASE"]    = 'https://vocabularyserver.com/bne/encabezamientos/';`

/** Array of char used to alphabetic global menu navigation. For example: array('a','b','c','d') */
`$CFG_VOCABS["1"]["ALPHA"]       = array("A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z"); `

/** Show main tree navigation. Default=1. 0 = "do not show" */
`$CFG_VOCABS["1"]["SHOW_TREE"]   = 0; `

/** Enable modules: BULK_TERMS_REVIEW, SUGGESTION_SERVICE,COPY_CLICK, VISUAL_VOCAB */
`$CFG_VOCABS["1"]["MODULES"]     = array("MARC21","BULK_TERMS_REVIEW","SUGGESTION_SERVICE","COPY_CLICK","VISUAL_VOCAB");`



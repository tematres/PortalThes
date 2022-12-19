<?php

function getTemaTresTerm($tematres_uri, $vocab, $term)
{
    global $message;
    global $CFG_URL_PARAM;
    $data=getTemaTresData($tematres_uri, "fetch", urlencode($term));
    $rows='';
    if ($data->resume->cant_result>0) {
        $i=0;
        foreach ($data->result->term as $value) {
            $rows.='<li class="successNoImage"><strong>'.$term.'</strong> => ';
            $rows.= (strlen($value->no_term_string)>0) ? '<em title="'.$message['UF'].'  '.$message['USE'].' '.FixEncoding($value->string).'">'.FixEncoding($value->no_term_string).'</em> '.$message['USE'].' ' : '';
            $rows.='<a href="'.$CFG_URL_PARAM["url_site"].'index.php?task=fetchTerm&arg='.$value->term_id.'&v='.$vocab.'#t3" title="'.FixEncoding($value->string).'">'.FixEncoding($value->string).'</a>';
            $rows.='</li>';
        }
    } else {
        $rows.='<li class="errorNoImage"><strong>'.$term.'</strong>: '.LABEL_noResults;
        $rows.='</li>';
    }
    return $rows;
}

function getXLSTemaTresTerm($tematres_uri, $vocabularyMetaData, $array_terms)
{
    global $CFG_URL_PARAM;
    global $CFG;


    $xml = new ExcelWriterXML($vocabularyMetaData->result->title.'_report.xls');
    $xml->docTitle($CFG["MASS_CTRL_TITLE"].' '.$CFG["title_site"]);
    $xml->docAuthor($CFG["author_site"]);
    $xml->docCompany($CFG["author_site"]);
    $format = $xml->addStyle('StyleHeader');
    $format->fontBold();
    $sheet = $xml->addSheet(ucfirst(BULK_TERMS_REVIEW_sheetLabel).' - '.$vocabularyMetaData->result->title);
    $sheet->writeString(2, 1, BULK_TERMS_REVIEW_term, 'StyleHeader');
    $sheet->writeString(2, 2, BULK_TERMS_REVIEW_results, 'StyleHeader');
    $sheet->writeString(2, 3, BULK_TERMS_REVIEW_term_id, 'StyleHeader');
    $sheet->writeString(2, 4, BULK_TERMS_REVIEW_code, 'StyleHeader');
    $sheet->writeString(2, 5, BULK_TERMS_REVIEW_termIn.' '.$vocabularyMetaData->result->title, 'StyleHeader');
    $formatRed = $xml->addStyle('red');
    $formatRed->fontColor('#FF0000');
    $formatYellow = $xml->addStyle('Yellow');
    $formatYellow->bgColor('Yellow', 'Solid');
    $i=2;
    // $array_terms = array_unique($array_terms,SORT_STRING);
    

    $time_start = time();

    foreach ($array_terms as $term) {
        $time_now = time();
        if ($time_start >= $time_now + 10) {
            $time_start = $time_now;
            header('X-pmaPing: Pong');
        };

        
        $i=++$i;
        if ($i-2 <= $CFG["MAX_TERMS4MASS_CTRL"]) {
            $data=getTemaTresData($tematres_uri, "fetch", urlencode($term));
            if ($data->resume->cant_result>0) {
                foreach ($data->result->term as $value) {
                    $sheet->writeString($i, 1, $term);
                    $isUF=((string) $value->string==(string) $data->resume->arg) ? 0 : 1;
                    if (strtoupper((string)$value->string)==strtoupper((string)$term)) {
                        $sheet->writeString($i, 2, BULK_TERMS_REVIEW_termFound);
                        $sheet->writeString($i, 3, (int) $value->term_id);
                        $sheet->writeString($i, 4, (string) $value->code);
                        $sheet->writeString($i, 5, (string) $value->string);



                    } else {
                        $sheet->writeString($i, 2, (string) BULK_TERMS_REVIEW_termFoundDiff, $formatRed);
                        $sheet->writeString($i, 3, (int) $value->term_id);
                        $sheet->writeString($i, 4, (string) $value->code);
                        $sheet->writeString($i, 5, (string) $value->string);
                        /** búsqueda del término preferente */
                        /*
                        if($isUF==1){
                            $dataPrefTerm=getTemaTresData($tematres_uri, "fetchTerm", $value->term_id);
                            $sheet->writeString($i, 6, $dataPrefTerm->result->term->string);

                        }*/

                    }
                }
            } else {
                $dataSimilarTerm=getTemaTresData($tematres_uri, "fetchSimilar", urlencode($term));

                $sheet->writeString($i, 1, $term);
                if ($dataSimilarTerm->resume->cant_result>0) {//hay recomendacion
                    $sheet->writeString($i, 2, ucfirst(BULK_TERMS_REVIEW_TERMSUGGES), $formatYellow);
                    $sheet->writeString($i, 3, '');
                    $sheet->writeString($i, 4, '');
                    $sheet->writeString($i, 5, (string) $dataSimilarTerm->result->string);
                } else {//no hay término ni recomendación
                    $sheet->writeString($i, 2, BULK_TERMS_REVIEW_termNoFound, $formatRed);
                    $sheet->writeString($i, 3, '');
                    $sheet->writeString($i, 4, '');
                    $sheet->writeString($i, 5, '');
                }
            }
        }
    }//fin del if ($i-1 <= $CFG["MAX_TERMS4MASS_CTRL"])
    $xml->sendHeaders();
    $xml->writeData();
}

///tomado de http://stackoverflow.com/questions/3930975/alternative-for-php-excel/3931142#3931142
function getHTMLTemaTresTerm($tematres_uri, $vocabularyMetaData, $array_terms)
{

    global $CFG_URL_PARAM;
    global $CFG;
    $rows=' <div id="massiveresult">
                <h5><strong>'.BULK_TERMS_REVIEW_results.'</strong></h5>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>'.BULK_TERMS_REVIEW_term.'</th>
                                <th>'.BULK_TERMS_REVIEW_results.'</th>
                                <th>'.BULK_TERMS_REVIEW_termIn.' '.$vocabularyMetaData->result->title.'</th>
                            </tr>
                        </thead>';
    $i=0;

    $array_terms = array_map("trim", $array_terms);
    $array_terms = array_unique($array_terms);
    foreach ($array_terms as $term) {
        $i=++$i;
        if ($i <= $CFG["MAX_TERMS4MASS_CTRL"]) {
            $data=getTemaTresData($tematres_uri, "fetch", urlencode($term));

            if ($data->resume->cant_result>0) {
                foreach ($data->result->term as $value) {
                    //if((string)$value->string==(string)$term)
                    if (strtoupper((string)$value->string)==strtoupper((string)$term)) {
                        $rows.='    <tr class="success">
                                        <td>'.$i.'</td>
                                        <td>'.$term.'</td>
                                        <td>'.ucfirst(BULK_TERMS_REVIEW_termFound).'</td>
                                        <td style="font-weight: bold;">'.(string) $value->string.'</td>
                                    </tr>';
                    } else {
                        $rows.='    <tr class="warning">
                                        <td>'.$i.'</td>
                                        <td>'.$term.'</td>
                                        <td>'.ucfirst(BULK_TERMS_REVIEW_termFoundDiff).'</td>
                                        <td style="font-weight: bold;">'.(string) $value->string.'</td>
                                    </tr>';
                    }
                }
            } else {
                $dataSimilarTerm=getTemaTresData($tematres_uri, "fetchSimilar", urlencode($term));
                if ($dataSimilarTerm->resume->cant_result>0) {//hay recomendacion
                        $rows.='    <tr >
                                        <td class="danger">'.$i.'</td>
                                        <td class="danger">'.$term.'</td>
                                        <td class="warning">'.ucfirst(BULK_TERMS_REVIEW_TERMSUGGES).'</td>
                                        <td class="warning">'.(string) $dataSimilarTerm->result->string.'</td>
                                    </tr>';
                } else {//no hay término ni recomendación
                    $rows.='            <tr class="danger">
                                        <td>'.$i.'</td>
                                        <td>'.$term.'</td>
                                        <td><strong>'.ucfirst(BULK_TERMS_REVIEW_termNoFound).'</strong></td>
                                        <td>&nbsp;</td>
                                    </tr>';
                }
            }
        }//fin del if ($i-1 <= $CFG["MAX_TERMS4MASS_CTRL"])
    }
    $rows.='    </table>
            </div>';
    return $rows;
}

function HTMLformSelectVocabularios($array_vocabs, $vocab = "")
{
    global $CFG_URL_PARAM;
    global $CFG;
    foreach ($array_vocabs as $k => $v) {
        $selected=($vocab==$v["CODE"]) ? "selected":"";
        $rows.='<option value="'.$v["CODE"].'" '.$selected.'>'.$v["TITLE"].'</option> ';
    }
    return $rows;
}

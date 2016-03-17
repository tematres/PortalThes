<?php
/*
Datos de definicion del vocabularios
* */
function getTemaTresData($tematres_uri,$task="fetchVocabularyData",$arg="")
{
    if ( ! $arg) {
        return getURLdata($tematres_uri.'?task=fetchVocabularyData');
    } else {
        return getURLdata($tematres_uri.'?task='.$task.'&arg='.$arg);
    }
}

function getTemaTresTerm($tematres_uri,$vocab,$term)
{
    GLOBAL $message;
    GLOBAL $CFG_URL_PARAM;
    $data=getTemaTresData($tematres_uri,"fetch",urlencode($term));
    $rows='';
    if($data->resume->cant_result>0) {
        $i=0;
        foreach ($data->result->term as $value){
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

function getXLSTemaTresTerm($tematres_uri,$vocabularyMetaData,$array_terms)
{
    GLOBAL $CFG_URL_PARAM;
    GLOBAL $CFG;

    $sheetTitle=BULK_TERMS_REVIEW_sheetTitle;
    $sheetLabel=BULK_TERMS_REVIEW_sheetLabel;
    $sheetLabelTerm=BULK_TERMS_REVIEW_term;
    $sheetLabelCode=BULK_TERMS_REVIEW_code;
    $sheetLabelTermId=BULK_TERMS_REVIEW_term_id;
    $sheetLabelResults=BULK_TERMS_REVIEW_results;
    $sheetLabelTermIn=BULK_TERMS_REVIEW_termIn;
    $sheetLabelTermOK=BULK_TERMS_REVIEW_termFound;
    $sheetLabelTermNoOK=BULK_TERMS_REVIEW_termNoFound;
    $sheetLabelTermOKdiff=BULK_TERMS_REVIEW_termFoundDiff;

    $xml = new ExcelWriterXML($vocabularyMetaData->result->title.'_report.xls');
    $xml->docTitle($CFG["MASS_CTRL_TITLE"].' '.$CFG["title_site"]);
    $xml->docAuthor($CFG["author_site"]);
    $xml->docCompany($CFG["author_site"]);
    $format = $xml->addStyle('StyleHeader');
    $format->fontBold();
    $sheet = $xml->addSheet($sheetTitle);
    $sheet->writeString(1,1,BULK_TERM_REVIEW_sheetLabel,'StyleHeader');
    $sheet->cellMerge( 1, 1, 3, 0);
    $sheet->writeString(2,1,$sheetLabelTerm,'StyleHeader');
    $sheet->writeString(2,2,$sheetLabelResults,'StyleHeader');
    $sheet->writeString(2,3,$sheetLabelTermId,'StyleHeader');
    $sheet->writeString(2,4,$sheetLabelCode,'StyleHeader');
    $sheet->writeString(2,5,$sheetLabelTermIn.' '.$vocabularyMetaData->result->title,'StyleHeader');
    $formatRed = $xml->addStyle('red');
    $formatRed->fontColor('#FF0000');
    $i=2;
    // $array_terms = array_unique($array_terms,SORT_STRING);
    foreach ($array_terms as $term) {
        $i=++$i;
        if($i-2 <= $CFG["MAX_TERMS4MASS_CTRL"]) {
            $data=getTemaTresData($tematres_uri,"fetch",urlencode($term));
            if($data->resume->cant_result>0) {
                foreach ($data->result->term as $value) {
                    $sheet->writeString($i,1,$term);
                    if(strtoupper((string)$value->string)==strtoupper((string)$term)) {
                        $sheet->writeString($i,2,$sheetLabelTermOK);
                        $sheet->writeString($i,3,(int) $value->term_id);
                        $sheet->writeString($i,4,(string) $value->code);
                        $sheet->writeString($i,5,(string) $value->string);
                    } else {
                        $sheet->writeString($i,2,(string) $sheetLabelTermOKdiff,$formatRed);
                        $sheet->writeString($i,3,(int) $value->term_id);
                        $sheet->writeString($i,4,(string) $value->code);                        
                        $sheet->writeString($i,5,(string) $value->string,$formatRed);
                    }
                }
            } else {
                $term_result=$sheetLabelTermNoOK;
                $sheet->writeString($i,1,$term);
                $sheet->writeString($i,2,$term_result,$formatRed);
                $sheet->writeString($i,3,'');
            }
        }
    }//fin del if ($i-1 <= $CFG["MAX_TERMS4MASS_CTRL"])
    $xml->sendHeaders();
    $xml->writeData();
}

///tomado de http://stackoverflow.com/questions/3930975/alternative-for-php-excel/3931142#3931142
function getHTMLTemaTresTerm($tematres_uri,$vocabularyMetaData,$array_terms)
{
    GLOBAL $CFG_URL_PARAM;
    GLOBAL $CFG;
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
    $array_terms = array_map("trim",$array_terms);
    $array_terms = array_unique($array_terms);
    foreach ($array_terms as $term) {
        $i=++$i;
        if($i <= $CFG["MAX_TERMS4MASS_CTRL"]) {
            $data=getTemaTresData($tematres_uri,"fetch",urlencode($term));
            if($data->resume->cant_result>0) {
                foreach ($data->result->term as $value) {
                    //if((string)$value->string==(string)$term)
                    if(strtoupper((string)$value->string)==strtoupper((string)$term)) {
                        $rows.='    <tr class="success">
                                        <td>'.$i.'</td>
                                        <td>'.$term.'</td>
                                        <td>'.BULK_TERMS_REVIEW_termFound.'</td>
                                        <td>'.(string) $value->string.'</td>
                                    </tr>';
                    } else {
                        $rows.='    <tr class="warning">
                                        <td>'.$i.'</td>
                                        <td>'.$term.'</td>
                                        <td>'.BULK_TERMS_REVIEW_termFoundDiff.'</td>
                                        <td>'.(string) $value->string.'</td>
                                    </tr>';
                    }
                }
            } else {
                $rows.='            <tr class="danger">
                                        <td>'.$i.'</td>
                                        <td>'.$term.'</td>
                                        <td><strong>'.BULK_TERMS_REVIEW_termNoFound.'</strong></td>
                                        <td>&nbsp;</td>
                                    </tr>';
            }
        }//fin del if ($i-1 <= $CFG["MAX_TERMS4MASS_CTRL"])
    }
    $rows.='    </table>
            </div>';
    return $rows;
}

function HTMLformSelectVocabularios($array_vocabs,$vocab="")
{
    GLOBAL $CFG_URL_PARAM;
    GLOBAL $CFG;
    foreach ($array_vocabs as $k => $v) {
        $selected=($vocab==$v["CODE"]) ? "selected":"";
        $rows.='<option value="'.$v["CODE"].'" '.$selected.'>'.$v["TITLE"].'</option> ';
    }
    return $rows;
}

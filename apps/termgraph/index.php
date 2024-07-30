<?php
require '../../config.ws.php';
include_once('config.ws.php');
include_once('lang/es_AR.php');

$tema_id=$_GET['term_id'];

$dataTerm=getURLdata($URL_BASE.'?task=fetchTerm&arg='.$tema_id);
$term= (string) $dataTerm->result->term->string;
$term_id= (int) $dataTerm->result->term->term_id;

$dataNT = getURLdata($URL_BASE.'?task=fetchDown&arg='.$term_id);
$dataDirectTerms = getURLdata($URL_BASE.'?task=fetchDirectTerms&arg='.$term_id);

if ($dataNT->resume->cant_result > 0) {
    foreach ($dataNT->result->term as $value) {
    $term_context["NT"][]=array("term_id"=>object2value($value,"term_id"),
                                                         "term_string"=>object2value($value,"string","string"),
                                                         "term_code"=>object2value($value,"code","string"),
                                                         "rel_type"=>object2value($value,"relation_type","string"),
                                                         "rel_rel_type"=>object2value($value,"relation_label","string")
                                                         );
    };
}   

if ($dataDirectTerms->resume->cant_result > 0) {

    foreach ($dataDirectTerms->result->term as $value) {
    $term_context[(string) $value->relation_type][]=array("term_id"=>object2value($value,"term_id"),
                                                         "term_string"=>object2value($value,"string","string"),
                                                         "term_code"=>object2value($value,"code","string"),
                                                         "rel_type"=>object2value($value,"relation_type","string"),
                                                         "rel_rel_type"=>object2value($value,"relation_label","string")
                                                         );
    };
}


$array_node[]=array("id"=>"TheTerm", "label"=>$term,"color"=>"#E39A94","fixed"=>true,"level"=>1);

if(is_array($term_context["NT"])) :
    $array_node[]=array("id"=>"NT", "label"=>"Narrower","group"=>"GNT","fixed"=>false,"level"=>3,"shape"=>"circle");
    $array_edge[]=array("from"=>"TheTerm","to"=>"NT","arrows"=>"from");
    foreach ($term_context["NT"] as $key => $value) {
        $array_node[]=array("id"=>$value["term_id"], "label"=>$value["term_string"],"fixed"=>false,"group"=>"GNT");
        $array_edge[]=array("from"=>"NT","to"=>$value["term_id"]);
    }
    endif;

if(is_array($term_context["BT"])) :
    $array_node[]=array("id"=>"BT", "label"=>"Broader","group"=>"GBT","fixed"=>false,"level"=>3,"shape"=>"circle");
    $array_edge[]=array("from"=>"TheTerm","to"=>"BT","arrows"=>"to");
    foreach ($term_context["BT"] as $key => $value) {
        $array_node[]=array("id"=>$value["term_id"], "label"=>$value["term_string"],"fixed"=>false,"group"=>"GBT");
        $array_edge[]=array("from"=>"BT","to"=>$value["term_id"]);
    }
    endif;

if(is_array($term_context["RT"])>0) :
    $array_node[]=array("id"=>"RT", "label"=>"Related","group"=>"GRT","fixed"=>false,"level"=>3,"shape"=>"circle");
    $array_edge[]=array("from"=>"TheTerm","to"=>"RT","arrows"=>"from,to");
    foreach ($term_context["RT"] as $key => $value) {
        $array_node[]=array("id"=>$value["term_id"], "label"=>$value["term_string"],"fixed"=>false,"group"=>"GRT");
        $array_edge[]=array("from"=>"RT","to"=>$value["term_id"]);
    }
    endif;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Term Graph: <?php echo $term;?></title>
    <script type="text/javascript" src="js/vis-network.min.js"></script>
    <style type="text/css">
        #myterm {
            width: 1600px;
            height: 800px;
            border: 1px solid lightgray;
            text-align: center;
        }
    </style>
</head>

<body>
    <?php echo count($array_node);?>
    <div id="myterm"></div>
    <script type="text/javascript">
        let nodes = new vis.DataSet(<?php echo json_encode($array_node);?>);
        let edges = new vis.DataSet(<?php echo json_encode($array_edge);?>);

        let data = {
            nodes: nodes,
            edges: edges,
        };
        let options = {
            interaction:{
            hover:true,
            },
            groups: {
                'GBT': {color:{background:'#FADD91'}, borderWidth:3},
                'GRT': {color:{background:'#81D9E3'}, borderWidth:3},
                'GNT': {color:{background:'#B2FF8F'}, borderWidth:3}
            },
            physics: {
                    stabilization: false,
                    barnesHut: {
                    springLength: 200,
                    },
            },
            nodes:{
                color: '#B982FF',
                fixed: false,
                font: '20px arial black',
                scaling: {
                    label: false,
                },
                shadow: true,
                shape: 'box',
                margin: 10
            },
            edges: {
                arrows: '',
                color: 'black',
                scaling: {
                    label: false,
                },
                shadow: true,
            },
            
            
        };
        let container = document.getElementById("myterm");
        let network = new vis.Network(container, data, options);
    </script>
</body>
</html>
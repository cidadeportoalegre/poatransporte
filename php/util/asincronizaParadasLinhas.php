<?php

/***********************************************************************************************************************
            P A R A D A S   D A S   L I N H A S
************************************************************************************************************************/


$host = "'ldbdes3'";
$user = "'guiaonibus_updt'";
$pwd =  "'se1in#gnbs'";
$db =   "'guiaonibus'";

$conMY = mysql_connect('ldbdes3', 'guiaonibus_updt', 'se1in#gnbs');

$ok = mysql_select_db('guiaonibus', $conMY);
$v_i = 0;

$sql = "delete from paradalinha";
$insert = mysql_query($sql);


echo "Guia do Onibus\n";
echo "Iniciando Carga de Parads<br>\n";
	
	$opts = array(
        'http' => array(
            'method'=>"GET",
            'header'=>"Content-Type: text/html; charset=UTF-8"
        )
    );

$context = stream_context_create($opts);

$json = file_get_contents("http://pmpa-geo2:8399/arcgis/rest/services/EPTC/POATRANSPORTE/MapServer/7/query?text=&geometry=&geometryType=esriGeometryEnvelope&inSR=&spatialRel=esriSpatialRelIntersects&relationParam=&objectIds=&where=OID+%3C%3E+0+OR+OID+%3D+0&time=&returnCountOnly=false&returnIdsOnly=false&returnGeometry=true&maxAllowableOffset=&outSR=&outFields=CODLIN%2C+PCODIGO&f=pjson",false,$context);
$linhas = json_decode($json,1);
	
//	print_r($linhas);
//	return;

//            A T E N � � O
//*************************    n�o est� excluindo as linhas n�o sei porque. outra hora dar uma pesquisada
	
	
echo "Iniciando insercoes de Paradas...<br>\n";
	
	
foreach ($linhas["features"] as $v) {
	echo "Incluindo " . $v["attributes"]["PCODIGO"] . " - ";
	echo  $v["attributes"]["CODLIN"] . "<br>\n";

	$v_sql = "insert into paradalinha (idparada,idlinha) (select " . $v["attributes"]["PCODIGO"] . ", idlinha from linha where codigo = '" . $v["attributes"]["CODLIN"] . "')";
	$insert = mysql_query($v_sql);

$v_i = $v_i + 1;
//echo $v_i . $v_sql . "<BR>";

}

	echo "Sincronizacao realizada com sucesso.";
?>



























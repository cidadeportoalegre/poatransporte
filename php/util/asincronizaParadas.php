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

$sql = "delete from parada";
$insert = mysql_query($sql);

echo "Guia do Onibus\n";
echo "Iniciando Carga de Parads<br>\n";
	
	$opts = array(
        'http' => array(
            'method'=>"GET",
            'header'=>"Content-Type: text/html; charset=UTF-8"));
    $context = stream_context_create($opts);

//    $json = file_get_contents("http://pmpa-geo1:8399/arcgis/rest/services/EPTC_WGS84_v2/MapServer/2/query?text=0&geometry=&geometryType=esriGeometryEnvelope&inSR=&spatialRel=esriSpatialRelIntersects&relationParam=&objectIds=&where=&time=&returnCountOnly=false&returnIdsOnly=false&returnGeometry=true&maxAllowableOffset=&outSR=&outFields=GEOPMPA.GIGTRXONIBUS.CODLIN%2CGEOPMPA.ZCBO0110.NMIDELIN&f=pjson",false,$context);
    $json = file_get_contents("http://pmpa-geo2:8399/arcgis/rest/services/EPTC/POATRANSPORTE/MapServer/0/query?text=&geometry=&geometryType=esriGeometryEnvelope&inSR=&spatialRel=esriSpatialRelIntersects&relationParam=&objectIds=&where=FID+%3C%3E+0&time=&returnCountOnly=false&returnIdsOnly=false&returnGeometry=true&maxAllowableOffset=&outSR=&outFields=PCODIGO&f=pjson",false,$context);
	$linhas = json_decode($json,1);
	
//	print_r($linhas);
//	return;

	
	echo "Iniciando insercoes de Paradas...<br>\n";
	
$v_i = 0;

	foreach ($linhas["features"] as $v) {
		$v_sql = "insert into parada (idparada,codigo,longitude,latitude,terminal) values(" . $v["attributes"]["PCODIGO"] . "," . $v["attributes"]["PCODIGO"] . "," .
			$v["geometry"]["x"] . "," . $v["geometry"]["y"] . ",'N')";
		$insert = mysql_query($v_sql);
		$v_i = $v_i + 1;
		echo $v_i . $v_sql . "<BR>";
	}

	echo "Sincronizacao realizada com sucesso.";
/*
*/
?>

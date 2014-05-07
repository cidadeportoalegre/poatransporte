<?php

/***********************************************************************************************************************
            L I N H A S
************************************************************************************************************************/

$host = "'ldbdes3'";
$user = "'guiaonibus_updt'";
$pwd =  "'se1in#gnbs'";
$db =   "'guiaonibus'";

$conMY = mysql_connect('ldbdes3', 'guiaonibus_updt', 'se1in#gnbs');
$ok = mysql_select_db('guiaonibus', $conMY);

$sql = "delete from coordenada where idlinha in (select idlinha from linha where tipo = 'O')";
$insert = mysql_query($sql);
$sql = "delete from linha where tipo = 'O'";
$insert = mysql_query($sql);

echo "Guia do Onibus\n";
echo "Iniciando Carga de Linhas de ônibus<br>\n";
	
	$opts = array(
        'http' => array(
            'method'=>"GET",
            'header'=>"Content-Type: text/html; charset=UTF-8"));

    $context = stream_context_create($opts);

//    $json = file_get_contents("http://pmpa-geo1:8399/arcgis/rest/services/EPTC_WGS84_v2/MapServer/2/query?text=0&geometry=&geometryType=esriGeometryEnvelope&inSR=&spatialRel=esriSpatialRelIntersects&relationParam=&objectIds=&where=&time=&returnCountOnly=false&returnIdsOnly=false&returnGeometry=true&maxAllowableOffset=&outSR=&outFields=GEOPMPA.GIGTRXONIBUS.CODLIN%2CGEOPMPA.ZCBO0110.NMIDELIN&f=pjson",false,$context);
    $json = file_get_contents("http://pmpa-geo2:8399/arcgis/rest/services/EPTC/POATRANSPORTE/MapServer/2/query?text=&geometry=&geometryType=esriGeometryEnvelope&inSR=&spatialRel=esriSpatialRelIntersects&relationParam=&objectIds=&where=CDIDELIN+%3C%3E+%270%27&time=&returnCountOnly=false&returnIdsOnly=false&returnGeometry=true&maxAllowableOffset=&outSR=&outFields=onibus.ID%2C+GEOPMPA.ZCBO0110.NMIDELIN%2C+onibus.CODLIN&f=pjson",false,$context);

	$linhas = json_decode($json,1);
	
//	print_r($linhas);
//	return;

	echo "Iniciando insercoes de Linhas...<br>\n";
	$v_id = 5000;
$v_i = 0;

	foreach ($linhas["features"] as $v) {
		$v_sql = "insert into linha (idlinha, nome, codigo, tipo) values(" . $v_id . ", '" . $v["attributes"]["GEOPMPA.ZCBO0110.NMIDELIN"] . "', '" .
		$v["attributes"]["onibus.CODLIN"] . "', 'O')";
		$insert = mysql_query($v_sql);
		$v_i = $v_i + 1;
		echo $v_i . $v_sql . "<BR>";

		foreach($v["geometry"]["paths"][0] as $v2){
			$v_sql = "insert into coordenada (latitude,longitude,idlinha) values(" . $v2[1] . ", " . $v2[0] . ", '" . $v_id . "')";
			$insert = mysql_query($v_sql);
			$v_i = $v_i + 1;
//			if ($v_i < 500)
//			echo $v_i . $v_sql . "<BR>";
		}
		$v_id = $v_id + 1;
	}

	echo "Sincronizacao realizada com sucesso.";
?>

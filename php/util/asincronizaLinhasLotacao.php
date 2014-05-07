<?php
/***********************************************************************************************************************
            L I N H A S   d e   l o t a ç ã o 
************************************************************************************************************************/
$host = "'ldbdes3'";
$user = "'guiaonibus_updt'";
$pwd =  "'se1in#gnbs'";
$db =   "'guiaonibus'";

$conMY = mysql_connect('ldbdes3', 'guiaonibus_updt', 'se1in#gnbs');
$ok = mysql_select_db('guiaonibus', $conMY);

$sql = "delete from coordenada where idlinha in (select idlinha from linha where tipo = 'L')";
$insert = mysql_query($sql);
$sql = "delete from linha where tipo = 'L'";
$insert = mysql_query($sql);

echo "Iniciando Carga de Linhas de lotação<br>\n";
	
	$opts = array(
        'http' => array(
            'method'=>"GET",
            'header'=>"Content-Type: text/html; charset=UTF-8"));
    $context = stream_context_create($opts);

    $json = file_get_contents("http://pmpa-geo2:8399/arcgis/rest/services/EPTC/POATRANSPORTE/MapServer/5/query?text=&geometry=&geometryType=esriGeometryEnvelope&inSR=&spatialRel=esriSpatialRelIntersects&relationParam=&objectIds=&where=FID+%3C%3E+0&time=&returnCountOnly=false&returnIdsOnly=false&returnGeometry=true&maxAllowableOffset=&outSR=&outFields=lotacoes.ID%2C+lotacoes.CODLIN%2C+GEOPMPA.ZCBO0110.NMIDELIN&f=pjson",false,$context);

	$linhas = json_decode($json,1);
	
//	print_r($linhas);
//	return;

	echo "Iniciando insercoes de Linhas de lotação...<br>\n";
	
$v_i = 0;

	foreach ($linhas["features"] as $v) {
		$v_sql = "insert into linha (idlinha, nome, codigo, tipo) values(" . $v["attributes"]["Lotacoes.ID"] . ", '" . $v["attributes"]["GEOPMPA.ZCBO0110.NMIDELIN"] . "', '" .
		$v["attributes"]["Lotacoes.CODLIN"] . "', 'L')";
		$insert = mysql_query($v_sql);
		$v_i = $v_i + 1;
		echo $v_i . $v_sql . "<BR>";

		foreach($v["geometry"]["paths"][0] as $v2){
			$v_sql = "insert into coordenada (latitude,longitude,idlinha) values(" . $v2[1] . ", " . $v2[0] . ", '" . $v["attributes"]["Lotacoes.ID"] . "')";
			$insert = mysql_query($v_sql);
			$v_i = $v_i + 1;
			echo $v_i . $v_sql . "<BR>";
		}
	}

	echo "Sincronizacao realizada com sucesso.";
?>


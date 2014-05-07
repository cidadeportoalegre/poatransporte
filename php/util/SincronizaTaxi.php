<?php
include('../dominio/Taxi.php');

echo "Guia do Onibus\n";
echo "Iniciando Carga de Taxi\n";
	
	$opts = array(
        'http' => array(
            'method'=>"GET",
            'header'=>"Content-Type: text/html; charset=UTF-8"
        )
    );

    $context = stream_context_create($opts);

    $json = file_get_contents("http://pmpa-geo1:8399/arcgis/rest/services/EPTC_WGS84/MapServer/4/query?text=0&geometry=&geometryType=esriGeometryEnvelope&inSR=&spatialRel=esriSpatialRelIntersects&relationParam=&objectIds=&where=&time=&returnCountOnly=false&returnIdsOnly=false&returnGeometry=true&maxAllowableOffset=&outSR=&outFields=ENDERECO%2CTELEFONE&f=pjson",false,$context);
	$taxis = json_decode($json,1);

// 	echo "<pre>";
// 	print_r($taxis);
// 	return;
	
//TODO Tipar o deleteAll Taxis	
//	echo "Excluindo Taxis...\n";
//	Taxi::deleteAll();
//	echo "Taxis excluidos!\n";
	
	echo "Iniciando insercoes de Taxis...\n";
	
	$count = 0;
	
	foreach ($taxis["features"] as $v) {
		$taxi = new Taxi();
		$taxi->setEndereco($v["attributes"]["ENDERECO"]);
		$taxi->setTelefone($v["attributes"]["TELEFONE"]);
		$taxi->setLongitude($v["geometry"]["x"]);
		$taxi->setLatitude($v["geometry"]["y"]);
		echo "Incluindo " . $v["attributes"]["ENDERECO"] . "\n";

		$taxi->insert();
		echo $v["attributes"]["ENDERECO"] . " incluida. \n";

		$count++;
	}
	
	echo "\nForam incluidos " . $count . " taxis. \nSincronizacao realizada com sucesso.\n";
?>
<?php
include('../dominio/Linha.php');

echo "Guia do Onibus\n";
echo "Iniciando Carga de Linhas\n";
	
	$opts = array(
        'http' => array(
            'method'=>"GET",
            'header'=>"Content-Type: text/html; charset=UTF-8"
        )
    );

    $context = stream_context_create($opts);

    $json = file_get_contents("http://pmpa-geo1:8399/arcgis/rest/services/EPTC_WGS84/MapServer/5/query?text=0&geometry=&geometryType=esriGeometryEnvelope&inSR=&spatialRel=esriSpatialRelIntersects&relationParam=&objectIds=&where=&time=&returnCountOnly=false&returnIdsOnly=false&returnGeometry=true&maxAllowableOffset=&outSR=&outFields=CODLINHA%2CCODIGO%2CNMIDELIN&f=pjson",false,$context);
	$linhas = json_decode($json,1);

//	echo "<pre>";
//	print_r($linhas);
//	return;
	
//TODO Tipar o deleteAll por tipo de linha	
//	echo "Excluindo linhas...\n";
//	Linha::deleteAll(Linha::TIPO_LOTACAO);
//	echo "Linhas excluindas!\n";
	
	echo "Iniciando insercoes de Linhas...\n";
	
	
	foreach ($linhas["features"] as $v) {
		$linha = new Linha();
		$linha->setCodigo($v["attributes"]["CODIGO"]);
		$linha->setNome($v["attributes"]["nmidelin"]);
		$linha->setTipo(Linha::TIPO_LOTACAO);
		echo "Incluindo " . $v["attributes"]["CODIGO"] . " - ";
		echo  $v["attributes"]["nmidelin"] . "\n";

		foreach($v["geometry"]["paths"][0] as $v2){
			$coordenada = new Coordenada();
			$coordenada->setLatitude($v2[1]);
			$coordenada->setLongitude($v2[0]);
			$linha->addCoordenadas($coordenada);	
		}
		
		$linha->insert();
		echo $v["attributes"]["CODIGO"] . " incluida. \n";
	}
	
	echo "Sincronizacao realizada com sucesso.";
?>

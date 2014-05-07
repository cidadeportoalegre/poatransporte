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

    $json = file_get_contents("http://pmpa-geo1:8399/arcgis/rest/services/EPTC_WGS84_v2/MapServer/2/query?text=0&geometry=&geometryType=esriGeometryEnvelope&inSR=&spatialRel=esriSpatialRelIntersects&relationParam=&objectIds=&where=&time=&returnCountOnly=false&returnIdsOnly=false&returnGeometry=true&maxAllowableOffset=&outSR=&outFields=GEOPMPA.GIGTRXONIBUS.CODLIN%2CGEOPMPA.ZCBO0110.NMIDELIN&f=pjson",false,$context);
	$linhas = json_decode($json,1);
	
	print_r($linhas);
	return;
	
	//echo "Excluindo linhas...\n";
    //Linha::deleteAll(Linha::TIPO_ONIBUS);
	//echo "Linhas excluindas!\n";
	
	echo "Iniciando insecoes de Linhas...\n";
	
	
	foreach ($linhas["features"] as $v) {
		$linha = new Linha();
		$linha->setCodigo($v["attributes"]["GEOPMPA.GIGTRXONIBUS.CODLIN"]);
		$linha->setNome($v["attributes"]["GEOPMPA.ZCBO0110.NMIDELIN"]);
		echo "Incluindo " . $v["attributes"]["GEOPMPA.GIGTRXONIBUS.CODLIN"] . " - ";
		echo  $v["attributes"]["GEOPMPA.ZCBO0110.NMIDELIN"] . "\n";

		foreach($v["geometry"]["paths"][0] as $v2){
			$coordenada = new Coordenada();
			$coordenada->setLatitude($v2[1]);
			$coordenada->setLongitude($v2[0]);
			$linha->addCoordenadas($coordenada);	
		}
		
//		$linha->insert();
		echo $v["attributes"]["GEOPMPA.GIGTRXONIBUS.CODLIN"] . " incluida. \n";
	}
	
	echo "Sincronizacao realizada com sucesso.";
?>

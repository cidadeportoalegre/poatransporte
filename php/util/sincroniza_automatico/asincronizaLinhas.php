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

//    $json = file_get_contents("http://pmpa-geo1:8399/arcgis/rest/services/EPTC_WGS84_v2/MapServer/2/query?text=0&geometry=&geometryType=esriGeometryEnvelope&inSR=&spatialRel=esriSpatialRelIntersects&relationParam=&objectIds=&where=&time=&returnCountOnly=false&returnIdsOnly=false&returnGeometry=true&maxAllowableOffset=&outSR=&outFields=GEOPMPA.GIGTRXONIBUS.CODLIN%2CGEOPMPA.ZCBO0110.NMIDELIN&f=pjson",false,$context);
    $json = file_get_contents("http://pmpa-geo2:8399/arcgis/rest/services/EPTC/POATRANSPORTE/MapServer/2/query?text=&geometry=&geometryType=esriGeometryEnvelope&inSR=&spatialRel=esriSpatialRelIntersects&relationParam=&objectIds=&where=CDBAILIN+%3D+0&time=&returnCountOnly=false&returnIdsOnly=false&returnGeometry=true&maxAllowableOffset=&outSR=&outFields=onibus_mar_2013.CODLIN%2CGEOPMPA.ZCBO0110.NMIDELIN&f=pjson",false,$context);
	//			   http://pmpa-geo2:8399/arcgis/rest/services/EPTC/EPTC_WGS84/MapServer/2/query?text=&geometry=&geometryType=esriGeometryEnvelope&inSR=&spatialRel=esriSpatialRelIntersects&relationParam=&objectIds=&where=CDBAILIN+%3D+0&time=&returnCountOnly=false&returnIdsOnly=false&returnGeometry=true&maxAllowableOffset=&outSR=&outFields=onibus_mar_2013.CODLIN%2CGEOPMPA.ZCBO0110.NMIDELIN&f=pjson

	$linhas = json_decode($json,1);
	
//	print_r($linhas);
//	return;

//            A T E N Ç Ã O
//*************************    não está excluindo as linhas não sei porque. outra hora dar uma pesquisada
	
//	echo "Excluindo linhas...\n";
//    Linha::deleteAll(Linha::TIPO_ONIBUS);
//	echo "Linhas excluidas!\n";
	
	echo "Iniciando insecoes de Linhas...\n";
	

//	foreach ($linhas["onibus_mar_2013"] as $v) {
//		$linha = new Linha();
//		$linha->setCodigo($v["fieldAliases"]["GEOPMPA.GIGTRXONIBUS.CODLIN"]);
//		$linha->setNome($v["fieldAliases"]["GEOPMPA.ZCBO0110.NMIDELIN"]);
//		echo "Incluindo " . $v["fieldAliases"]["GEOPMPA.GIGTRXONIBUS.CODLIN"] . " - ";
//		echo  $v["fieldAliases"]["GEOPMPA.ZCBO0110.NMIDELIN"] . "<br>\n";

//		foreach($v["geometry"]["paths"][0] as $v2){
//			$coordenada = new Coordenada();
//			$coordenada->setLatitude($v2[1]);
//			$coordenada->setLongitude($v2[0]);
//			$linha->addCoordenadas($coordenada);	
//		}
		
//		$linha->insert();
//		echo $v["fieldAliases"]["GEOPMPA.GIGTRXONIBUS.CODLIN"] . " incluida. <br>\n";
//	}
	










	
	foreach ($linhas["features"] as $v) {
//		$linha = new Linha();
//		$linha->setCodigo($v["attributes"]["onibus_mar_2013.CODLIN"]);
//		$linha->setNome($v["attributes"]["GEOPMPA.ZCBO0110.NMIDELIN"]);
		echo "Incluindo " . $v["attributes"]["onibus_mar_2013.CODLIN"] . " - ";
		echo  $v["attributes"]["GEOPMPA.ZCBO0110.NMIDELIN"] . "\n";

		foreach($v["geometry"]["paths"][0] as $v2){
//			$coordenada = new Coordenada();
//			$coordenada->setLatitude($v2[1]);
//			$coordenada->setLongitude($v2[0]);
//			$linha->addCoordenadas($coordenada);	
		}
		
//		$linha->insert();
		echo $v["attributes"]["onibus_mar_2013.CODLIN"] . " incluida. \n";
	}

	echo "Sincronizacao realizada com sucesso.";

?>

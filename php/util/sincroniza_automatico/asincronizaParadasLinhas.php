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

echo "Guia do Onibus\n";
echo "Iniciando Carga de Parads<br>\n";
	
	$opts = array(
        'http' => array(
            'method'=>"GET",
            'header'=>"Content-Type: text/html; charset=UTF-8"
        )
    );

    $context = stream_context_create($opts);

//    $json = file_get_contents("http://pmpa-geo1:8399/arcgis/rest/services/EPTC_WGS84_v2/MapServer/2/query?text=0&geometry=&geometryType=esriGeometryEnvelope&inSR=&spatialRel=esriSpatialRelIntersects&relationParam=&objectIds=&where=&time=&returnCountOnly=false&returnIdsOnly=false&returnGeometry=true&maxAllowableOffset=&outSR=&outFields=GEOPMPA.GIGTRXONIBUS.CODLIN%2CGEOPMPA.ZCBO0110.NMIDELIN&f=pjson",false,$context);
//    $json = file_get_contents("http://pmpa-geo2:8399/arcgis/rest/services/teste/EPTC_Modelo_Google/MapServer/4/query?text=&geometry=&geometryType=esriGeometryEnvelope&inSR=&spatialRel=esriSpatialRelIntersects&relationParam=&objectIds=&where=OID+%3C%3E+0&time=&returnCountOnly=false&returnIdsOnly=false&returnGeometry=true&maxAllowableOffset=&outSR=&outFields=*&f=pjson",false,$context);
    $json = file_get_contents("http://pmpa-geo2:8399/arcgis/rest/services/EPTC/POATRANSPORTE/MapServer/7/query?text=&geometry=&geometryType=esriGeometryEnvelope&inSR=&spatialRel=esriSpatialRelIntersects&relationParam=&objectIds=&where=OID+%3C%3E+0+OR+OID+%3D+0&time=&returnCountOnly=false&returnIdsOnly=false&returnGeometry=true&maxAllowableOffset=&outSR=&outFields=CODLIN%2C+PCODIGO&f=pjson",false,$context);
	//			   http://pmpa-geo2:8399/arcgis/rest/services/EPTC/EPTC_WGS84/MapServer/2/query?text=&geometry=&geometryType=esriGeometryEnvelope&inSR=&spatialRel=esriSpatialRelIntersects&relationParam=&objectIds=&where=CDBAILIN+%3D+0&time=&returnCountOnly=false&returnIdsOnly=false&returnGeometry=true&maxAllowableOffset=&outSR=&outFields=onibus_mar_2013.CODLIN%2CGEOPMPA.ZCBO0110.NMIDELIN&f=pjson

	$linhas = json_decode($json,1);
	
//	print_r($linhas);
//	return;

//            A T E N Ç Ã O
//*************************    não está excluindo as linhas não sei porque. outra hora dar uma pesquisada
	
//	echo "Excluindo linhas...\n";
//    Linha::deleteAll(Linha::TIPO_ONIBUS);
//	echo "Linhas excluidas!\n";
	
	echo "Iniciando insercoes de Paradas...<br>\n";
	
//	foreach ($linhas["PCODIGO"] as $v) {
//		$linha = new Linha();
//		$linha->setCodigo($v["fieldAliases"]["GEOPMPA.GIGTRXONIBUS.CODLIN"]);
//		$linha->setNome($v["fieldAliases"]["GEOPMPA.ZCBO0110.NMIDELIN"]);
//		echo "Incluindo " . $v["OID"]["CODLIN"] . " - ";
//		echo "Incluindo " . $v["fieldAliases"]["OID"] . " - ";
//		echo  $v["fieldAliases"]["GEOPMPA.ZCBO0110.NMIDELIN"] . "<br>\n";

//		foreach($v["geometry"]["paths"][0] as $v2){
//			$coordenada = new Coordenada();
//			$coordenada->setLatitude($v2[1]);
//			$coordenada->setLongitude($v2[0]);
//			$linha->addCoordenadas($coordenada);	
//		}
		
//		$linha->insert();
//		echo $v["fieldAliases"]["GEOPMPA.GIGTRXONIBUS.CODLIN"] . " incluida. \n";
//	}
	




	
	foreach ($linhas["features"] as $v) {
//		$linha = new Linha();
//		$linha->setCodigo($v["attributes"]["onibus_mar_2013.CODLIN"]);
//		$linha->setNome($v["attributes"]["GEOPMPA.ZCBO0110.NMIDELIN"]);
		echo "Incluindo " . $v["attributes"]["PCODIGO"] . " - ";
		echo  $v["attributes"]["CODLIN"] . "<br>\n";

$v_sql = "insert into paradalinha (idparada,idlinha) (select " . $v["attributes"]["PCODIGO"] . ", idlinha from linha where codigo = '" . $v["attributes"]["CODLIN"] . "')";

$insert = mysql_query($v_sql);
$v_i = $v_i + 1;
echo $v_i . $v_sql . "<BR>";

/*
		foreach($v["geometry"]["paths"][0] as $v2){
			$coordenada = new Coordenada();
			$coordenada->setLatitude($v2[1]);
			$coordenada->setLongitude($v2[0]);
			$linha->addCoordenadas($coordenada);	
		}
*/		
//		$linha->insert();
//		echo $v["attributes"]["CODLIN"] . " incluida. \n";
	}

	echo "Sincronizacao realizada com sucesso.";
?>

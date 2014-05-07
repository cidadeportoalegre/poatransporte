<?php

/***********************************************************************************************************************
           T E R M I N A I S
************************************************************************************************************************/


//$host = "'ldbdes3'";
//$user = "'guiaonibus_updt'";
//$pwd =  "'se1in#gnbs'";
//$db =   "'guiaonibus'";

$conMY = mysql_connect('ldbdes3', 'guiaonibus_updt', 'se1in#gnbs');

$ok = mysql_select_db('guiaonibus', $conMY);
$v_i = 0;

//$sql = "delete from parada";
//$insert = mysql_query($sql);


echo "Guia do Onibus\n";
echo "Iniciando Carga de Terminais<br>\n";
	
	$opts = array(
        'http' => array(
            'method'=>"GET",
            'header'=>"Content-Type: text/html; charset=UTF-8"
        )
    );

    $context = stream_context_create($opts);

    $json = file_get_contents("http://pmpa-geo2:8399/arcgis/rest/services/EPTC/POATRANSPORTE/MapServer/8/query?text=&geometry=&geometryType=esriGeometryEnvelope&inSR=&spatialRel=esriSpatialRelIntersects&relationParam=&objectIds=&where=OID+%3C%3E+0+OR+OID+%3D+0&time=&returnCountOnly=false&returnIdsOnly=false&returnGeometry=true&maxAllowableOffset=&outSR=&outFields=*&f=pjson",false,$context);

	$linhas = json_decode($json,1);
	
//	print_r($linhas);
//	return;

	echo "Iniciando insercoes de Terminais...<br>\n";
	
$v_pcodigo = 0;	
	foreach ($linhas["features"] as $v) {
//echo "aaa" . $v_pcodigo . "aaa" . $v["attributes"]["PCODIGO"] . "aaa";
		if ($v_pcodigo <> $v["attributes"]["PCODIGO"]){
			$v_pcodigo = $v["attributes"]["PCODIGO"];
			$v_sql = 'insert into parada (idparada,codigo,longitude,latitude,terminal) values (' . $v["attributes"]["PCODIGO"] . ', '  . $v["attributes"]["PCODIGO"] . ', ' .
					$v["attributes"]["LONG"] . ', ' . $v["attributes"]["LAT"] . ', "S")';
			$insert = mysql_query($v_sql);
			echo $v_sql . "<BR>";
		}
		echo "Incluindo " . $v["attributes"]["PCODIGO"] . " - ";
		echo  $v["attributes"]["LINHA_SENT"] . "<br>\n";

$v_sql = "insert into paradalinha (idparada,idlinha) (select " . $v["attributes"]["PCODIGO"] . ", idlinha from linha where codigo = '" . $v["attributes"]["LINHA_SENT"] . "')";

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

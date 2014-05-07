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
echo "Iniciando Carga de Terminaisssssssssssssssssss<br>\n";
	
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
	
	$v_long_aux = 0;
	$v_lat_aux = 0;
	$v_pcodigo = 100000;
	$v_cont1 = 0;
	$v_cont2 = 0;

	$v_sql = 'delete FROM paradalinha where idparada > 100000';
	$insert = mysql_query($v_sql);
	$v_sql = 'delete FROM parada where idparada > 100000';
	$insert = mysql_query($v_sql);

	foreach ($linhas["features"] as $v) {
//echo "aaa" . $v_pcodigo . "aaa" . $v["attributes"]["PCODIGO"] . "aaa";
		if ($v_long_aux <> $v["attributes"]["LONG"] && $v_lat_aux <>  $v["attributes"]["LAT"]){
			$v_long_aux = $v["attributes"]["LONG"];
			$v_lat_aux  = $v["attributes"]["LAT"];
			$v_pcodigo = $v_pcodigo + 1;
			$v_sql = 'delete FROM `paradalinha` where idparada =  ' . $v["attributes"]["PCODIGO"];
			$insert = mysql_query($v_sql);
//			echo $v_sql . "<BR>";

			$v_sql = 'delete FROM `parada` where idparada =  ' . $v["attributes"]["PCODIGO"];
			$insert = mysql_query($v_sql);
//			echo $v_sql . "<BR>";

			$v_longitude = $v["attributes"]["LONG"] - 0.000006;
//echo "longitude=" . $v["attributes"]["LONG"] . " " . $v_longitude . "<br>";
			$v_latitude = $v["attributes"]["LAT"] - 0.000094;
//echo "latitude=" . $v["attributes"]["LAT"] . " " . $v_latitude . "<br>";

//			$v_sql = 'insert into parada (idparada,codigo,longitude,latitude,terminal) values (' . $v["attributes"]["PCODIGO"] . ', '  . $v["attributes"]["PCODIGO"] . ', ' .
//					$v["attributes"]["LONG"] . ' - 0.000830, ' . $v["attributes"]["LAT"] . ' -0.000070, "S")';

			$v_sql = 'insert into parada (idparada,codigo,latitude,longitude,terminal) values (' . $v_pcodigo . ', '  . $v_pcodigo . ', ' .
					$v_latitude . ', ' . $v_longitude . ', "S")';

			$insert = mysql_query($v_sql);
			echo $v_sql . ";<BR>";
			$v_cont1 = $v_cont1 + 1;
		}
//		echo "Incluindo " . $v["attributes"]["PCODIGO"] . " - ";
//		echo  $v["attributes"]["LINHA_SENT"] . "<br>\n";

		$v_sql = "insert into paradalinha (idparada,idlinha) (select " . $v_pcodigo . ", idlinha from linha where codigo = '" . $v["attributes"]["LINHA_SENT"] . "')";
		echo $v_sql . ";<BR>";
		$v_cont2 = $v_cont2 + 1;

		$insert = mysql_query($v_sql);
$v_i = $v_i + 1;

	}
echo "Linhas =" . $v_cont1 . "<br>";
echo "Paradas =" . $v_cont2 . "<br>";

	echo "Sincronizacao realizada com sucesso.";
?>

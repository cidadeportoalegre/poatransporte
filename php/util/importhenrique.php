<?php
/******************************************************************************/
/*                              query principal                               */
/******************************************************************************/

	$sql = "SELECT {$campo} FROM {$tabela} {$where} {$order}";
	$query = mysql_query($sql);
	echo $sql_rows = "SELECT {$campo} FROM {$tabela} {$where}";
	$query_rows = mysql_query($sql_rows);
	$num = mysql_num_fields($query_rows);
	for($y = 0; $y < $num; $y++){ 
		$names[$y] = mysql_field_name($query_rows,$y);
	}
	for($k=0;$resultado = mysql_fetch_array($query);$k++){
		for($i = 0; $i < $num; $i++){ 
			$resultados[$k][$names[$i]] = $resultado[$names[$i]];
		}
	}


$host = "'ldbdes3'";
$user = "'guiaonibus_updt'";
$pwd =  "'se1in#gnbs'";
$db =   "'guiaonibus'";
$conMY = mysql_connect('ldbdes3', 'guiaonibus_updt', 'se1in#gnbs');
$ok = mysql_select_db('guiaonibus', $conMY);

$sql = "SELECT * FROM parada_temp order by codigo asc, data_inicial desc";
$obj_rs = mysql_query($sql);

while($consulta = mysql_fetch_array($obj_rs)) {
	if ($consulta["codigo"] <> $v_codigo){
		$sql = "insert into parada (idparada, codigo, longitude, latitude, terminal) values ('" . 
				$consulta["codigo"] . "', '" . $consulta["idparada"] . "', '" . $consulta["longitude"] . "', '" . 
						 $consulta["latitude"] . "', '" . $consulta["terminal"] . "')"; 
		$insert = mysql_query($sql);
		echo "<br>" . $consulta["codigo"];
	}
	$v_codigo = $consulta["codigo"];
}
/*****************************************************************
	importação de linhas de ônibus
******************************************************************/
/* 
for( $i=1; $i <= $data->rowcount($sheet_index=0); $i++ ){

	$query = "SELECT cdidelin as Linha, nmidelin as Nome FROM zcbo0110 " . 
				" WHERE dtatulin <= getdate() AND (dtbailin IS NULL OR dtbailin > getdate()) and cdidelin = '" . $data->val($i, 2) . 
				"' ORDER BY cdideemp, cdidelin";
	$res = mssql_query($query,$conexao);
	while($consulta = mssql_fetch_array($res)) {
		$sql = "insert into linha (idlinha, nome, codigo, tipo) values ('" . $data->val($i, 1) . "', '" . $consulta["Nome"] . "', '" . $data->val($i, 2) . "-" . 
						 $data->val($i, 3) . "', 'O')"; 
		$insert = mysql_query($sql);
//		echo $sql . "<br>";
	}
}
*/
/*****************************************************************
	importação de paradas
******************************************************************/
/*
$data = new Spreadsheet_Excel_Reader("PARADAS.XLS");
for( $i=1; $i <= $data->rowcount($sheet_index=0); $i++ ){
	if ($i > 1){
		$sql = "insert into parada (idparada, codigo, longitude, latitude, terminal) values ('" . $data->val($i, 1) . "', '" .  $data->val($i, 4) . "', '" . 
					$data->val($i, 2) . "', '" . $data->val($i, 3) . "', '" .  $data->val($i, 5)  . "')"; 
//		$insert = mysql_query($sql);
		echo $sql . "<br>";
	}
}
*/




?>
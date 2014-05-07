<?php
require_once 'excel_reader2.php';
/******************************************************************************/
/*                              query principal                               */
/******************************************************************************/


$host = "'ldbdes3'";
$user = "'guiaonibus_updt'";
$pwd =  "'se1in#gnbs'";
$db =   "'guiaonibus'";
$conMY = mysql_connect('ldbdes3', 'guiaonibus_updt', 'se1in#gnbs');
$ok = mysql_select_db('guiaonibus', $conMY);

$sql = "delete from coordenada where idlinha in (SELECT idlinha FROM linha where tipo = 'O')";
$insert = mysql_query($sql);

$sql = "delete from paradalinha";
$insert = mysql_query($sql);

$sql = "delete from parada";
$insert = mysql_query($sql);

$sql = "delete from linha where tipo = 'O'";
$insert = mysql_query($sql);


/*****************************************************************
	importação de linhas de ônibus
******************************************************************/
$conexao   = mssql_connect("PROCEMPA7","INGNET","proc7WEBeptc") or die ("Não foi possível conectar ao Banco de dados.");
mssql_select_db("ZSTO01DB",$conexao);

$data = new Spreadsheet_Excel_Reader("ONIBUS.XLS");
 
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

/*****************************************************************
	importação de paradas
******************************************************************/
$data = new Spreadsheet_Excel_Reader("PARADAS.XLS");
for( $i=1; $i <= $data->rowcount($sheet_index=0); $i++ ){
	if ($i > 1){
		$sql = "insert into parada (idparada, codigo, longitude, latitude, terminal) values ('" . $data->val($i, 1) . "', '" .  $data->val($i, 4) . "', '" . 
					$data->val($i, 2) . "', '" . $data->val($i, 3) . "', '" .  $data->val($i, 5)  . "')"; 
//		$insert = mysql_query($sql);
		echo $sql . "<br>";
	}
}





?>
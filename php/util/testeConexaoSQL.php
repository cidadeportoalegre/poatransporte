<?php
/******************************************************************************/
/*                              query principal                               */
/******************************************************************************/

echo "aaa";

$host = "'PROCEMPA7'";
$user = "'INGNET'";
$pwd =  "'proc7WEBeptc'";
$v_db =   "'ZSTO01DB'";



$conexao = mssql_connect("PROCEMPA7","INGNET","proc7WEBeptc") or die ("ERROR: Could not connect to MS-SQL Server.\n");
                $db = mssql_select_db("ZSTO01DB", $conexao) or die ("ERROR: Could not select database.");

$query = "SELECT cdidelin as Linha, nmidelin as Nome FROM zcbo0110 
		WHERE dtatulin <= getdate() AND (dtbailin IS NULL OR dtbailin > getdate())
		ORDER BY cdideemp, cdidelin";

$resultado = mssql_query($query, $conexao) or die ("Não foi possível executar a consulta");



echo "bbb";



?>
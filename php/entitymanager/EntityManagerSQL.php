<?php

class EntityManagerSQL {

	static private $instance;
	
	private $connection;
	
	private function EntityManagerSQL() {
		$host = '';
		$user = '';
        	$pwd = '';
		$db = '';

	$constring = mssql_connect($host, $user, $pwd);
	mssql_select_db($db,$constring);

//		$constring = 'mssql:host=' . $host . ';dbname=' . $db;
//		$this->connection = new PDO($constring, $user,$pwd, array(PDO::ATTR_PERSISTENT => true));
	}

	public function executeQuery($query) {
//		$rows = $this->connection->query($query)->fetchAll();
		$rows = mssql_query($query,$constring);
		return $rows;
	}
	
	//Executa a instrução SQL, retornando o número de linhas afetadas.
	public function execute($statement) {
		$rowsAffected  = $this->connection->exec($statement);
		return $rowsAffected;
	}
	

	public static function getInstance() {
		if (!isset(self::$instance)) {
			self::$instance = new EntityManagerSQL();
		}

		return self::$instance;
	}
	
}
?>



<?
/*

Um exemplo de select com esta conexão:

<?
$SQL = "SELECT * FROM bancodedados.dbo.tabela"; 
$res = mssql_query($SQL,$con);
while($RFP = mssql_fetch_array($res))
{
    echo $RFP['campo'].'<br />';
}

*/
?>

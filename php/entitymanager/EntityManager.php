<?php

class EntityManager {

	static private $instance;
	
	private $connection;
	
	private function EntityManager() {
		$host = '';
		$pwd = '';

		$user = '';
	   	$db = '';

		$constring = 'mysql:host=' . $host . ';dbname=' . $db;
		$this->connection = new PDO($constring, $user,$pwd, array(PDO::ATTR_PERSISTENT => true));
	}

	public function executeQuery($query) {
		$rows = $this->connection->query($query)->fetchAll();
		return $rows;
	}
	
	//Executa a instrução SQL, retornando o número de linhas afetadas.
	public function execute($statement) {
		$rowsAffected  = $this->connection->exec($statement);
		return $rowsAffected;
	}
	
	
	public static function getInstance() {
		if (!isset(self::$instance)) {
			self::$instance = new EntityManager();
		}

		return self::$instance;
	}
}
?>

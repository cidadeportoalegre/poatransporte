<?php
require_once('../entitymanager/EntityManager.php');

//Classe de dominio de trata Taxi
class Taxi {	
	private $id;
	private $endereco;
	private $telefone;
	private $latitude;
	private $longitude;	
	
	

	public function getId() {
		return $this->id;
	}
	
	public function setId($i) {
		$this->id = $i;
	}
	
	public function getEndereco() {
		return $this->endereco;
	}
	
	public function setEndereco($e) {
		$this->endereco = $e;
	}
	
	public function getTelefone() {
		return $this->telefone;
	}
	
	public function setTelefone($t) {
		$this->telefone = $t;
	}
	
	public function getLatitude() {
		return $this->latitude;
	}
	
	public function setLatitude($l) {
		$this->latitude = $l;
	}
	
	public function getLongitude() {
		return $this->longitude;
	}
	
	public function setLongitude($l) {
		$this->longitude = $l;
	}
	
	//Recupera taxis do SGBD  
	public static function getTaxis($bounds) {
		$entity = EntityManager::getInstance();
		
		$query  = "SELECT * FROM taxi WHERE ";
		$query .= "latitude > " . $bounds->getLatitudeI() . " AND ";
		$query .= "longitude > " . $bounds->getLongitudeI() . " AND ";
		$query .= "latitude < " . $bounds->getLatitudeF() . " AND ";
		$query .= "longitude < " . $bounds->getLongitudeF();

		$rows = $entity->executeQuery($query);
		
		if(count($rows) == 0) {
			throw new Exception('Pontos de táxi não encontrados');
		} else {
			foreach ($rows as $i => $row) {
				$taxi = new Taxi();
		    	$taxi->setId($row['idtaxi']);
  		   		$taxi->setEndereco($row['endereco']);
  		   		$taxi->setTelefone($row['telefone']);
  		   		$taxi->setLatitude($row['latitude']);
  		  		$taxi->setLongitude($row['longitude']);
  		  		$taxis[] = $taxi;
			}
		}

		return $taxis;
	}
	
	public function deleteAll() {
		$query = "delete from taxi";
		$entity = EntityManager::getInstance();
		$entity->execute($query);
	}	
	
	public function insert() {
		$query = "INSERT INTO taxi (endereco, telefone, longitude, latitude) VALUES('" . $this->getEndereco() . "', '" . $this->getTelefone() . "', '" . $this->getLongitude() . "', '" . $this->getLatitude() . "')";
	
		$entity = EntityManager::getInstance();
		$entity->execute($query);
	}	
}

?>
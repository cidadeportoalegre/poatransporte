<?php
require_once('../entitymanager/EntityManager.php');
require_once('../dominio/Bound.php');

//Classe de dominio de trata Parada
class Parada {	
	private $id;
	private $codigo;
	private $latitude;
	private $longitude;
	private $terminal;	
	
	private $linhas;

	public function getId() {
		return $this->id;
	}
	
	public function setId($i) {
		$this->id = $i;
	}
	
	public function getCodigo() {
		return $this->codigo;
	}
	
	public function setCodigo($c) {
		$this->codigo = $c;
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
	
	public function getTerminal() {
		return $this->terminal;
	}
	
	public function setTerminal($t) {
		$this->terminal = $t;
	}
	
	
	public function getLinhas() {
		return $this->linhas;
	}
	
	public function addLinha($l) {
		$this->linhas[] = $l;
	}
	
	//Recupera as linhas do SGBD a partir de uma condição. 
	public static function getParadas($bounds) {
		$entity = EntityManager::getInstance();

		$query  = "SELECT p.idparada, p.codigo AS pcodigo, p.longitude, p.latitude, p.terminal, ";
		$query .= "l.idlinha, nome, l.codigo AS lcodigo ";
		$query .= "FROM parada p, linha l, paradalinha pl ";
		$query .= "WHERE p.idparada = pl.idparada AND l.idlinha = pl.idlinha ";
		
		if ($bounds->getLatitudeI() != null) {
			$query .= "AND p.latitude > " . $bounds->getLatitudeI() . " AND ";
			$query .= "p.longitude > " . $bounds->getLongitudeI() . " AND ";
			$query .= "p.latitude < " . $bounds->getLatitudeF() . " AND ";
			$query .= "p.longitude < " . $bounds->getLongitudeF();
		} 

		$query .= " ORDER BY p.idparada, nome";

		$rows = $entity->executeQuery($query);
		
		if(count($rows) == 0) {
			throw new Exception('Paradas não encontradas');
		} else {
			$parada_aux = -1;
			foreach ($rows as $i => $row) {
				if ($parada_aux != $row['idparada']) {
					$parada_aux = $row['idparada'];
					
 					$parada = new Parada();
	
  			    	$parada->setId($row['idparada']);
  		    		$parada->setCodigo($row['pcodigo']);
  		    		$parada->setLatitude($row['latitude']);
  		    		$parada->setLongitude($row['longitude']);
  		    		$parada->setTerminal($row['terminal']);
  		    		
  		    		$paradas[] = $parada;
				} 

				$linha = new Linha();
				$linha->setId($row['idlinha']);
				$linha->setCodigo($row['lcodigo']);
				$linha->setNome($row['nome']);
				
				$parada->addLinha($linha);
			}
		}

		return $paradas;
	}
}

?>
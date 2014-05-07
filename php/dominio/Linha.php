<?php
require_once('../entitymanager/EntityManager.php');
require_once('Coordenada.php');

//Classe de dominio de trata Linha - atributos e mщtodos de acessos aos dados
class Linha {
	
	const TIPO_ONIBUS = "O";
	const TIPO_LOTACAO = "L";
	
	private $id;
	private $nome;
	private $codigo;
	private $coordenadas;

	private $tipo;

	public function getId() {
		return $this->id;
	}
	
	public function setId($i) {
		$this->id = $i;
	}
	
	public function getNome() {
		return $this->nome;
	}
	
	public function setNome($n) {
		$this->nome = $n;
	}
	
	public function getCodigo() {
		return $this->codigo;
	}
	
	public function setCodigo($c) {
		$this->codigo = $c;
	}
	
	public function getTipo() {
		return $this->tipo;
	}
	
	public function setTipo($t) {
		$this->tipo = $t;
	}
	
	
	public function getCoordenadas() {
		return $this->coordenadas;
	}
	
	public function setCoordenadas($c) {
		$this->coordenadas = $c;
	}
	
	public function addCoordenadas($coordenada) {
		$this->coordenadas[] = $coordenada;
	}
	
	public function insert() {
		$query = "INSERT INTO linha (nome, codigo, tipo) VALUES('" . $this->getNome() . "', '" . $this->getCodigo() . "', '" . $this->getTipo(). "')";

		$entity = EntityManager::getInstance();
		$entity->execute($query);
		
		$idLinha = Linha::getMaxId($this->getCodigo());
		
		foreach ($this->getCoordenadas() as $i => $coordenada) {
			$insert = "INSERT INTO coordenada (latitude, longitude, idlinha) VALUES ( ";
			$insert .= $coordenada->getLatitude() . ",";  
			$insert .= $coordenada->getLongitude() . ",";
			$insert .= $idLinha . ")";
			
			$entity->execute($insert);
		}
	}
	
	public static function getMaxId($codigo) {
		$query = "SELECT idlinha as id FROM linha WHERE codigo = '" . $codigo . "'";
		$entity = EntityManager::getInstance();
		$row = $entity->executeQuery($query);
		return $row[0]['id'];
	}
	
	private static function getByNome($nomelinha, $tipo) {
		$where = "nome LIKE '%" . $nomelinha . "%' AND tipo = " . $tipo . " ORDER BY idlinha, nome";		
		return Linha::getLinhas($where);
	}
	
	public static function getLotacaoByNome($nomelinha) {
		return Linha::getByNome($nomelinha, Linha::TIPO_LOTACAO);
	}

	public static function getOnibusByNome($nomelinha) {
		return Linha::getByNome($nomelinha, Linha::TIPO_ONIBUS);
	}
	
	//Recupera as linhas do SGBD a partir de uma condiчуo. 
	private static function getLinhas($condicao) {
		$entity = EntityManager::getInstance();

		$query = "SELECT * FROM linha WHERE " . $condicao;
		$rows = $entity->executeQuery($query);

		if(count($rows) == 0) {
			throw new Exception('Linhas nуo encontradas');
		} else {
			foreach ( $rows as $i => $row) {
 				$linha = new Linha();
	
  			    $linha->setId($row['idlinha']);
 			    $linha->setNome($row['nome']);
  		    	$linha->setCodigo($row['codigo']);
	    
				$linhas[] = $linha; 
			}
		}
		return $linhas;
	}

	//Recupera a linha do SGBD a partir de um idlinha, carregando as coordenadas.
	public static function getById($idlinha) {
		$entity = EntityManager::getInstance();
	
		$query = "SELECT * FROM linha as l, coordenada as c WHERE c.idlinha = l.idlinha AND l.idlinha = '" . $idlinha . "' order by idcoordenada";
		$rows = $entity->executeQuery($query);

		if(count($rows) == 0) {
			throw new Exception('Linha nуo encontrada');
		} else {
		
		
			$linha = new Linha();
			
			$linha->setId($rows[0]['idlinha']);
			$linha->setNome($rows[0]['nome']);
			$linha->setCodigo($rows[0]['codigo']);		
			
			foreach ($rows as $i => $row) {
				$coordenada = new Coordenada();
				$coordenada->setId($row['idcoordenada']);
				$coordenada->setLatitude($row['latitude']);
				$coordenada->setLongitude($row['longitude']);
			 	
				$linha->addCoordenadas($coordenada);
			}
		}
		return $linha;
	}
	

	public static function getLotacaoByNomeOuCodigo($string) {
		return Linha::getByNomeOuCodigo($string, Linha::TIPO_LOTACAO);
	}
	
	public static function getOnibusByNomeOuCodigo($string) {
		return Linha::getByNomeOuCodigo($string, Linha::TIPO_ONIBUS);
	}
	
	private static function getByNomeOuCodigo($string, $tipo) {
		$entity = EntityManager::getInstance();
		
		$query = "SELECT * FROM linha WHERE tipo = '" . $tipo . "' AND (nome LIKE '%" . $string . "%' OR codigo LIKE '%" . $string . "%') order by nome, idlinha";

		$rows = $entity->executeQuery($query);

		if(count($rows) == 0) {
			throw new Exception('Linhas nуo encontradas');
		} else {
			foreach ( $rows as $i => $row) {
				$linha = new Linha();
	
				$linha->setId($row['idlinha']);
				$linha->setNome($row['nome']);
				$linha->setCodigo($row['codigo']);
				 
				$linhas[] = $linha;
			}
		}
		return $linhas;
	}
	
	public static function deleteAll($tipo) {
		$entity = EntityManager::getInstance();

		$query = "DELETE FROM coordenada WHERE idlinha IN (SELECT id FROM linha where tipo = '" . $tipo . "'";
		$entity->execute($query);
		
		$query = "delete from linha where tipo = '" . $tipo . "'";
		$entity->execute($query);
	}
	
	
	public function getArray() {
		$array = array("idlinha" => $this->id, "nome" => $this->nome, "codigo" => $this->codigo );
		foreach ($this->coordenadas as $i => $coordenada) {
			$array[] = array("lat" => $coordenada->getLatitude(), "lng" => $coordenada->getLongitude());	
		}
		return $array;
	}
}

?>
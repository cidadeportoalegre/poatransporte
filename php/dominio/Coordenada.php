<?php

//Ponto georeferenciado através de latitude e longitude, pertencente a uma linha
class Coordenada {

	private $id;
	private $latitude;
	private $longitude;
	
	public function getId() {
		return $this->id;
	}
	
	public function setId($i) {
		$this->id = $i;
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
}

?>
<?php

//Classe VO para bounds
class Bound {
	
	private $latitudeI;
	private $longitudeI;	
	private $latitudeF;
	private $longitudeF;
	
	public function getLatitudeI() {
		return $this->latitudeI;
	}
	
	public function setLatitudeI($l) {
		$this->latitudeI = $l;
	}
	
	public function getLongitudeI() {
		return $this->longitudeI;
	}
	
	public function setLongitudeI($l) {
		$this->longitudeI = $l;
	}

	public function getLatitudeF() {
		return $this->latitudeF;
	}
	
	public function setLatitudeF($l) {
		$this->latitudeF = $l;
	}
	
	public function getLongitudeF() {
		return $this->longitudeF;
	}
	
	public function setLongitudeF($l) {
		$this->longitudeF = $l;
	}
}

?>
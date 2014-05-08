<?php 

require_once('../dominio/Taxi.php');


//Servicos de tratamento dos objetos Taxi
class TaxiService {

	//Retorna todas as instâncias de Taxi, no formado JSON
	//TODO Refatorar criando um metodo parada->getArray, como em Linha
	public static function getJSONTaxis($bounds) {
		$taxis = Taxi::getTaxis($bounds);

		foreach ($taxis as $i => $taxi) {
			$json[$i]['endereco'] = $taxi->getEndereco();
			$json[$i]['telefone'] = $taxi->getTelefone();
			$json[$i]['latitude'] = $taxi->getLatitude();
			$json[$i]['longitude'] = $taxi->getLongitude();
		}

		return json_encode($json);
	}
}
?>
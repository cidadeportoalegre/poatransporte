<?php 

require_once('../dominio/Parada.php');


//Servicos de tratamento dos objetos Parada
class ParadaService {

	//Retorna todas as instâncias de Parada, no formado JSON
	//TODO Refatorar criando um metodo parada->getArray, como em Linha
	public static function getJSONParadas($bounds) {
		$paradas = Parada::getParadas($bounds);
		
		foreach ($paradas as $i => $parada) {
			$json[$i]['codigo'] = $parada->getCodigo();
			$json[$i]['latitude'] = $parada->getLatitude();
			$json[$i]['longitude'] = $parada->getLongitude();
			$json[$i]['terminal'] = $parada->getTerminal();
				
	
			foreach ($parada->getLinhas() as $j => $linha) {
				$json[$i]["linhas"][$j]['idLinha'] = $linha->getId(); 
				$json[$i]["linhas"][$j]['codigoLinha'] = $linha->getCodigo(); 
				$json[$i]["linhas"][$j]['nomeLinha'] = $linha->getNome(); 
			}
		}

		return json_encode($json);
	}
}
?>
<?php 

require_once('../dominio/Linha.php');


//Servicos de tratamento dos objetos Linha
class LinhaService {

	//Retorna instâncias de Linhas, filtradas por código da linha,
	//no formado JSON
	public static function getJSONPorId($codigo) {
		$linha = Linha::getById($codigo);
		return json_encode($linha->getArray());
	}
	
	
	public static function getJSONLotacao($string) {
		$linhas = Linha::getLotacaoByNomeOuCodigo($string);
		return LinhaService::getJSONPorNomeOuCodigo($linhas);
	}

	public static function getJSONOnibus($string) {
		$linhas = Linha::getOnibusByNomeOuCodigo($string);
		return LinhaService::getJSONPorNomeOuCodigo($linhas);	
	}
	
	
	//Retorna instâncias de Linhas, filtradas por nome ou código da linha,
	//no formado JSON
	private static function getJSONPorNomeOuCodigo($linhas) {
		foreach ($linhas as $i => $linha) {
			$json[$i]['id'] = $linha->getId();
			$json[$i]['codigo'] = $linha->getCodigo();
			$json[$i]['nome'] = $linha->getNome();
		}
		
		return json_encode($json);
	}
}
?>
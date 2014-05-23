<?php
class cmwXML{

	public $xml;

	public function escreverXML($tabela,$next,$hash,$schema) {
		$xml = '<?xml version="1.0" encoding="iso-8859-1"?>';
		$xml .= '<cmw:REPLICACAO ';
		$xml .= "tabela=\"$tabela\" ";
		$xml .= "proxReg=\"$next\" ";
		$xml .= 'hash="'.$_SESSION["md5Valido"].'|'.session_id().'" ';
		$xml .= 'xmlns:xsd="http://www.w3.org/2001/XMLSchema" ';
		$xml .= 'xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" ';

		$file = explode('/',$schema);
		$xmlns = null;
		for ($f=0;$f < count($file);$f++) {
			if ($f==0) $xmlns = $file[0] . '/';
			elseif ($f==2) $xmlns .= $file[2];
			elseif ($f != count($file)-1) $xmlns .= '/'.$file[$f];
			else $location =  $xmlns.'/'.$file[$f];
		}
		$xml .= "xmlns:cmw=\"$xmlns\" ";
		$xml .= "xsi:schemaLocation=\"$xmlns $location\">";
		$this->schema = $schema;

		$this->xml = $xml;
	}

	public function exibirXML() {
		$this->xml .= '</cmw:REPLICACAO>';
		$xml = new DOMDocument();
		$xml->loadXML($this->xml);
		$xml->normalize();
		//if ($xml->schemaValidate($this->schema)) {
		header("Content-type: application/xml; charset=iso-8859-1");
		return $xml->saveXML();
		//} else {
		//return "<br />Erro na <b>validação</b> do XML, conforme acima.";
		//}
	}

	public function adicionarElemento($elemento,$attr,$cont=null,$prefix="cmw:") {
		$this->xml .= "<$prefix$elemento ";
		foreach($attr as $nome => $valor) {
			$this->xml .= $nome . '="'.str_replace("&","&amp;",$valor).'" ';
		}
		$this->xml .= ($cont != null) ? ">" .$cont . "</$prefix$elemento>" : " />";
	}

	public function escreverElemento($elemento,$attr,$cont=null,$prefix="cmw:") {
		$xml = "<$prefix$elemento ";
		foreach($attr as $nome => $valor) {
			$xml .= $nome . '="'.$valor.'" ';
		}
		$xml .= ($cont != null) ? ">" .$cont . "</$prefix$elemento>" : " />";
		return $xml;
	}
}
?>
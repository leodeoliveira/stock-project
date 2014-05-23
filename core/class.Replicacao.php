<?php
class Replicacao {

	public $db = null;
	protected $xml = null;
	protected $schema = null;
	protected $isXMLFile = null;
	protected $isSchemaFile = null;

	function __construct(&$db,$xml, $schema, $fileXML=false, $fileSchema=true) {
		$this->db = &$db;
		$this->xml = $xml;
		$this->schema = $schema;
		$this->isXMLFile = $fileXML;
		$this->isSchemaFile = $fileSchema;
		header("Content-type: application/xml; charset=iso-8859-1");
		echo '<?xml version="1.0" encoding="iso-8859-1" ?>';
		echo '<retorno>';
	}

	public function gravarDados(&$refClient,$regAtual) {
		$client = &$refClient;
		$data = $this->getArrayData();
		ob_start();

		if (is_array($data)) {
			//$data = array_reverse($data);
			$inserir = false;
			$data["chave"] = null;
			foreach($data as $cmw => $array) if (substr($cmw,0,3) == "cmw") {
				$pars[0] = substr($cmw,4);
				if (strpos($this->schema,"tab_revendedor") || strpos($this->schema,"tab_pedv_urbanauta")) {
					$sql = "SELECT DS_CHAVE FROM TAB_REPLICACAO " .
					"WHERE NM_BANCO = '".$pars[0]."';";
				} else if (strpos($this->schema,"produto")) {
					$sql = "SELECT DS_CHAVE FROM TAB_REPLICACAO " .
					"WHERE NM_BANCO = 'PECA_PEDV_REPLIC';";
				} else {
					$sql = "SELECT DS_CHAVE FROM TAB_REPLICACAO " .
					"WHERE NM_BANCO = '" .$pars[0]. "_REPLIC';";
				}

				$qyChave = $this->db->selectQuery($sql);
				$chave = $qyChave->getReg("DS_CHAVE",0);
				$arrChave = explode("|",$chave);
				unset($chave);
				unset($qyChave);
				foreach($array as $reg => $conteudo) {
					$sql_insert = "(";
					$sql_values = "(";
					$atualizar = "";
					$pk = "";
					if (strpos($this->schema,"tab_config_dscto_pedv") === false) $conteudo["cd_usu"] = "MASTER";
					if ($pars[0]=="TAB_PECA") unset($conteudo["fl_ativo"]);
					foreach($conteudo as $campo => $valor) {
						if ($campo == "dt_registro") {
							$campo = (strpos($pars[0],"PANHA") === false) ? "DT_REGISTR" : "DT_REGISTRO";
						} elseif($campo == "fl_replicado") {
							$valor = "S";
						} else $campo = strtoupper($campo);
						$sql_insert .= "$campo,";
						$sql_values .= "'".strToUpper(htmlspecialchars_decode($valor))."',";
						$t=0;
						do {
							if ($campo == $arrChave[$t]) {
								$pk .= "$campo='".strToUpper(htmlspecialchars_decode($valor))."' AND ";
								$t=0;
								break;
							} else $t++;
						} while ($t < count($arrChave));
						if ($t>0) $atualizar .= "$campo='".strToUpper(htmlspecialchars_decode($valor))."', ";
					}
					$inserir = substr($sql_insert,0,-1) . ") VALUES ";
					$inserir .= substr($sql_values,0,-1) . ")";
					$pars[1] = $inserir;
					$pars[2] = substr($atualizar,0,-2);
					$pars[3] = substr($pk,0,-5);
					if (strpos($this->schema,"tab_revendedor")) {
						if ($pars[0] == "TAB_REVENDEDOR") $data["chave"] = '('.$pars[3];
					}
					else $data["chave"] .= $data["chave"] == null ? '(('.$pars[3].')' : ' OR ('.$pars[3].')';
					$data["pars"] = $pars;

					echo '<error tipo="1"><![CDATA[';
					//var_dump($sql);
					$data["ok"] = $this->db->executeProcedure("P_GRAVA_REPLICADO",$pars);
					echo ']]></error>';
					if (strpos($this->schema,"tab_revendedor") || strpos($this->schema,"tab_con") || strpos($this->schema,"produto")) {
						if ($pars[0] == "TAB_REVENDEDOR") $regAtual++;
						elseif ($pars[0] == "TAB_CON") $regAtual++;
						elseif ($pars[0] == "TAB_COND_PAGTO_PEDV") $regAtual++;
						elseif ($pars[0] == "TAB_CONFIG_DSCTO_PEDV") $regAtual++;
						elseif ($pars[0] == "TAB_CONJUNTOS") $regAtual++;
						elseif ($pars[0] == "PECA_PEDV") $regAtual++;
					} else $regAtual++;
				}
			}
			echo '<error tipo="2"><![CDATA[';
			$data["chave"] .= ')';
			//var_dump($client);
			//echo $data["chave"];
			if (!isset($data["ok"])) {
				$regAtual++;
				echo 'Nenhum registro selecionado.<br />\n';
			}
			echo $client->confirmaReplic($_SESSION["sid"],$data["ok"],$data["chave"]);
			echo ']]></error>';

			echo '<tabela>'.$data["tabela"].'</tabela>';
			echo '<proxReg>'.$data["proxReg"].'</proxReg>';
			echo '<hash>'.$data["hash"].'</hash>';
			echo '<regAtual>'.$regAtual.'</regAtual>';
			echo '<ok>'.$data["ok"].'</ok>';
			echo '<varDump><![CDATA[';
			var_dump($data);
			echo ']]></varDump>';
		}
		return ob_get_clean();
	}

	public function getArrayData() {
		echo '<error tipo="4"><![CDATA[';
		$dom = new DOMDocument();
		if ($this->isXMLFile) $dom->load($this->xml);
		else $dom->loadXML($this->xml);

		$valid = ($this->isSchemaFile) ? $dom->schemaValidate($this->schema) : $dom->schemaValidateSource($this->schema);
		$valid = true;
		if (strpos($this->schema,"padrao")) echo "\nXSD inexistente";
		echo ']]></error>';

		if ($valid) {
			$ret = $dom->getElementsByTagName("REPLICACAO");
			$data = array ();
			for ($r=0;$r < $ret->length;$r++) {
				$no = $ret->item($r);
				$data["tabela"] = $no->getAttribute("tabela");
				$data["proxReg"] = $no->getAttribute("proxReg");
				$data["hash"] = $no->getAttribute("hash");
			}
			unset($dom);

			$xml = new XMLReader();
			if ($this->isXMLFile) $xml->open($this->xml);
			else $xml->XML($this->xml);
			$i = -1;
			while ($xml->read()) {
				if ($xml->nodeType == 1) if ($xml->name != "cmw:REPLICACAO") {
					$elemento = $xml->name;
					if ($elemento != "cmw:TAB_PECA" && $elemento != "cmw:PECA_PEDV") $i++;
					$xml->moveToFirstAttribute();
					while ($xml->moveToNextAttribute() != FALSE) {
						if ($elemento == "cmw:PRODUTO") {
							$data["cmw:TAB_PECA"][$i][$xml->name] = $xml->value;
							$data["cmw:PECA_PEDV"][$i][$xml->name] = $xml->value;
						} else $data[$elemento][$i][$xml->name] = $xml->value;
					}
					//if ($elemento != "cmw:PRODUTO" && $elemento != "cmw:TAB_PECA" && $elemento != "cmw:PECA_PEDV") $i++;
				}
			}
			unset($xml);
			return $data;
		}
	}

	public function fechar() {
		echo '</retorno>';
		/*
		TODO gerar log conforme parâmetro no conf
		if(!is_dir("logs")) {
		mkdir("logs","0777");
		}
		$fp = fopen("logs/".date("Y-m-d His").".xml", "a+");
		fwrite($fp, $this->xml);
		*/
	}
}
?>
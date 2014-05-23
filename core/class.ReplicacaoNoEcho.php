<?php
class ReplicacaoNoEcho {

	public $db = null;
	protected $xml = null;
	protected $schema = null;
	protected $isXMLFile = null;
	protected $isSchemaFile = null;
	public $result = "";

	function __construct(&$db,$xml, $schema, $fileXML=false, $fileSchema=true) {
		$this->db = &$db;
		$this->xml = $xml;
		$this->schema = $schema;
		$this->isXMLFile = $fileXML;
		$this->isSchemaFile = $fileSchema;
		$this->result .= '<?xml version="1.0" encoding="iso-8859-1" ?>';
		$this->result .= '<retorno>';
	}

	public function gravarDados() {
		$data = $this->getArrayData();

		if (is_array($data)) {
			//$data = array_reverse($data);
			$inserir = false;
			foreach($data as $cmw => $array) if (substr($cmw,0,3) == "cmw") {
				$pars[0] = substr($cmw,4);
				if (strpos($this->schema,"tab_revendedor") ||
				strpos($this->schema,"tab_pedv_urbanauta") ||
				strpos($this->schema,"tab_tarefas_agenda") ||
				strpos($this->schema,"tab_compromissos_agenda")) {
					$sql = "SELECT DS_CHAVE FROM TAB_REPLICACAO " .
							"WHERE NM_BANCO = '".$pars[0]."';";
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
					if ($pars[0] != "PEDV_BLOQUEIO" &&
					$pars[0] != "TAB_COMPROMISSOS_AGENDA" &&
					$pars[0] != "TAB_TAREFAS_AGENDA")
					$conteudo["cd_usu"] = "MASTER";
					foreach($conteudo as $campo => $valor) {
						if ($campo == "dt_registro") {
							$campo = (strpos($pars[0],"PANHA") === false) ? "DT_REGISTR" : "DT_REGISTRO";
						} elseif ($campo == "fl_replicado") {
							$campo = strtoupper($campo);
							$valor = "S";
						} else $campo = strtoupper($campo);
						$sql_insert .= "$campo,";
						$sql_values .= substr($campo,0,2) == 'XT' ? "STRING2BLOB('".strToUpper($valor)."'), " : "'".strToUpper($valor)."',";
						$t=0;
						do {
							if ($campo == $arrChave[$t]) {
								$pk .= "$campo='".strToUpper($valor)."' AND ";
								$t=0;
								break;
							} else $t++;
						} while ($t < count($arrChave));
						if ($t>0) $atualizar .= "$campo='".strToUpper($valor)."', ";
					}
					$inserir = substr($sql_insert,0,-1) . ") VALUES ";
					$inserir .= substr($sql_values,0,-1) . ")";
					$pars[1] = $inserir;
					$pars[2] = substr($atualizar,0,-2);
					$pars[3] = substr($pk,0,-5);
					$data[$cmw][$reg]["chave"] = $pars[3];
					$data[$cmw][$reg]["pars"] = $pars;
					//GAMB Replicação de REVENDEDOR.
					if ($pars[0] == "TAB_REVENDEDOR") {
						$parsRev = $pars;
						$data["chaveRev"] = $data["chave"];
						$inserir = false;
					} else {
						$this->result .= '<error tipo="5"><![CDATA[';
						if ($pars[0] == "TAB_CON") $inserir = true;
						$data[$cmw][$reg]["ok"] = $this->db->executeProcedure("P_GRAVA_REPLICADO",$pars);
						//echo "dump $cmw $reg: ";
						//var_dump($pars);
						//echo $pars[0];
						if (isset($parsRev) && $inserir) {
							$data[$cmw][$reg]["ok"] = $this->db->executeProcedure("P_GRAVA_REPLICADO",$parsRev);
							//echo "\n&nbsp;" . $parsRev[0];
							unset($parsRev);
						}
						$this->result .= ']]></error>';
					}
				}
			}
		}
		return $data;
	}

	public function getArrayData() {
		$this->result .= '<error tipo="6"><![CDATA[';
		$dom = new DOMDocument();
		if ($this->isXMLFile) $dom->load($this->xml);
		else $dom->loadXML($this->xml);
		ob_start();
		//$valid = ($this->isSchemaFile) ? $dom->schemaValidate($this->schema) : $dom->schemaValidateSource($this->schema);
		$valid = true;
		if (strpos($this->schema,"padrao")) $this->result .= "\nXSD inexistente";
		$this->result .= ob_get_clean() . ']]></error>';

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
			$i = 0;
			while ($xml->read()) {
				if ($xml->nodeType == 1) if ($xml->name != "cmw:REPLICACAO") {
					$elemento = $xml->name;
					$xml->moveToFirstAttribute();
					do {
						if ($xml->name != "seq") $data[$elemento][$i][$xml->name] = $xml->value;
					} while ($xml->moveToNextAttribute() != FALSE);
					$i++;
				}
			}
			unset($xml);
			return $data;
		}
	}

	public function fechar() {
		$this->result .= '</retorno>';
	}
}
?>
<?php
error_reporting(E_ALL);

if (0 > version_compare(PHP_VERSION, '5')) {
	die('This file was generated for PHP 5');
}

require_once ('class.UI.php');

/**
 * Classe para manipulação de dados diversos.
 *
 * @access public
 * @author Anderson Jordão Marques <ajm@urbanauta.com.br>
 * @version 1.0
 * @since 1.0 - 24/08/2006
 */
class Util {
	// --- ATTRIBUTES ---
	private $db;

	// --- OPERATIONS ---

	function Util(&$db=null,&$smarty=null) {
		$this->db = &$db;
		$this->smarty = &$smarty;
	}

	/**
	 * retira acentos - phpbrasil.com
	 */
	function trocaacento($palavra) {
		$array1 = array("á", "à", "â", "ã", "ä", "é", "è", "ê", "ë", "í", "ì", "î", "ï", "ó", "ò", "ô", "õ", "ö", "ú", "ù", "û", "ü", "ç", "Á", "À", "Â", "Ã", "Ä", "É", "È", "Ê", "Ë", "Í", "Ì", "Î", "Ï", "Ó", "Ò", "Ô", "Õ", "Ö", "Ú", "Ù", "Û", "Ü", "Ç","'","´","`","/","\\","~","^","\"" );
		$array2 = array("a", "a", "a", "a", "a", "e", "e", "e", "e", "i", "i", "i", "i", "o", "o", "o", "o", "o", "u", "u", "u", "u", "c" , "A", "A", "A", "A", "A", "E", "E", "E", "E", "I", "I", "I", "I", "O", "O", "O", "O", "O", "U", "U", "U", "U", "C","_","_","_","_","_","_","_","_" );
		//echo "<pre>";
		$resultado = str_replace($array1, $array2, $palavra);
		//var_dump($array1);
		//var_dump($array2);
		//var_dump($resultado);
		//echo "</pre>";
		return  $resultado;
	}

	/**
	 * Função que gera arquivos XML
	 *
	 * @access public
	 * @param string $nome1 Nome de grupo geral.
	 * @param string $nome21 Nome de grupo individual.
	 * @param array $grid Grid com os dados gerados na consulta SQL.
	 * @return XML Dados no formato XML
	 */
	function gerarXML($nome1,$nome2,$grid) {
		$xml = "<$nome1>";
		for ($i=1;$i < count($grid);$i++) {
			$xml .= "<$nome2>";
			for ($j=0; $j < count($grid[0]); $j++) {
				$campo = $grid[0][$j];
				$valor = strpos($grid[$i][$campo],"&") ? "<![CDATA[". str_replace("&amp;","&",$grid[$i][$campo])."]]>" : trim($grid[$i][$campo]) ;
				$xml .= "<$campo>$valor</$campo>";
			}
			$xml .= "</$nome2>";
		}
		$xml .= "</$nome1>";
		return $xml;
	}

	/**
	 * Faz uma pequena montagem de xml.
	 *
	 * @param array $lista
	 * @return unknown
	 */
	function gerarLinhaXML($nome, array $lista) {
		/*$xml = '<?xml version="1.0" encoding="iso-8859-1"?>';*/
		$xml = "<estrutura><$nome>";
		foreach($lista as $campo => $valor) {
			if (is_numeric($campo)) $campo = "C_".$campo;
			$xml .= "<$campo><![CDATA[$valor]]></$campo>";
		}
		$xml .= "</$nome></estrutura>";
		return $xml;
	}

	/**
	 * Função que gera combos
	 *
	 * @access public
	 * @param string $id Identificação do Combo
	 * @param Array $opcoes As opções do combo
	 */
	function gerarComboPlus($id,$opcoes) {
		$output = '<ul>';
		foreach($opcoes as $valor => $tela) {
			$output .= '<li id="'.$id.'_'.$valor.'" ' .
					'onMouseOver="this.style.backgroundColor=\'#316AC5\';this.style.color=\'#FFF\';" ' .
					'onMouseOut="this.style.backgroundColor=\'#FFF\';this.style.color=\'#000\';" ' .
					'onClick="app.selectedValueCombo(\''.$id.'\',\''.$valor.'\',this.innerHTML);" >';
			$output .= $tela;
			$output .= '</li>';
		}
		$output .= '</ul>';
		return $output;
	}

	/**
	 * Gera o combo Plus sem necessidade do smarty
	 *
	 * @param array $pars
	 * @return String com o combo
	 */
	function comboPlus($pars) {
		$output = null;
		$id = (string)$pars['id'];
		$opcoes = (array)$pars['opcoes'];
		$tam = (string)$pars['tamanho'];
		$inicial = (string) (isset($pars['inicial'])) ? $pars['inicial'] : null;
		$edit = (string) (isset($pars['autocomplete'])) ? ($pars['autocomplete'] != "off") ?  $pars['autocomplete'] : "OFF" : false;
		$atrAdd = (string) (isset($pars['js'])) ? $pars['js'] : null;
		$habilitado = (bool) isset($pars['disabled']) ? false : true;
		$title = (string) (isset($pars['title'])) ? $pars['title'] : "";
		$tabIndex = (int) (isset($pars['tabIndex'])) ? $pars['tabIndex'] : "";

		$output .= '<span style="margin: 0px; padding: 0px;"><input type="text" title="'.$title.'" id="'.$id.'_tela" class="CampoComboCMW" style="width: '.$tam.';"';
		$output .= $atrAdd != null ? ' onChange="'.$atrAdd.'"' : null;
		$output .= $inicial == null ? null : ' value="'.$opcoes[$inicial].'"';
		$output .= ($tabIndex != null) ? 'tabindex="'.$tabIndex.'" ' : null;

		if ($edit != false) {
			$output .=  ' autocomplete="off"';
		} else {
			$output .= !$habilitado ? null : ' onclick="app.selectBox(\''.$id.'\',true);"' .
					'onMouseOver="$(\'img_dropCombo_'.$id.'\').src=\'skin/kavo/ico/combo-over.gif\';" ' .
					'onMouseOut="$(\'img_dropCombo_'.$id.'\').src=\'skin/kavo/ico/combo.gif\';"';
			$output .= ' readOnly';
		}
		$output .= ' />';

		$output .= '<input type="hidden" id="'.$id.'" ';
		$output .= ($inicial != null) ? 'value="'.$inicial.'" ' : null;
		$output .= '/>';

		$output .= '<img class="img_dropCombo" id="img_dropCombo_'.$id.'" src="skin/kavo/ico/combo.gif" ' .
				'style="cursor: pointer;';
		$output .= $habilitado == false ? 'visibility: hidden;' : null;
		$output .= '" onClick="app.selectBox(\''.$id.'\',true);" ' .
				'onMouseOver="this.src=\'skin/kavo/ico/combo-over.gif\';" ' .
				'onMouseOut="this.src=\'skin/kavo/ico/combo.gif\';" />';

		$output .= '<div id="'.$id.'_values" class="comboCMW" style="display: none; overflow: auto; height: 64px;"><ul>';

		foreach($opcoes as $valor => $tela) {
			$output .= '<li id="'.$id.'_'.$valor.'" ' .
					'onMouseOver="this.style.backgroundColor=\'#316AC5\';this.style.color=\'#FFF\';" ' .
					'onMouseOut="this.style.backgroundColor=\'#FFF\';this.style.color=\'#000\';" ' .
					'onClick="app.selectedValueCombo(\''.$id.'\',\''.$valor.'\',this.innerHTML);" >';
			$output .= $tela;
			$output .= '</li>';
		}
		$output .= '</ul></div>';
		$output .= $edit != false && $edit != "OFF" ? '<div id="'.$id.'_ac" class="comboCMW" style="display: none; overflow: auto; height: 80px;"></div>' : null;
		$output .= '</span>';

		$output .= $edit != false && $edit != "OFF" ? '<script> ' .
				'new Ajax.Autocompleter("'.$id.'_tela", "'.$id.'_ac", "'.$edit.'", { paramName: "valor", afterUpdateElement: app.getSelectionId }); ' .
						'</script>' : null;
		return $output;
	}

	/**
	 * Função que gera arquivos no formato CSV.
	 * Arquivos separados por vírgula.
	 *
	 * @access public
	 * @param array $grid Grid com os dados gerados na consulta SQL.
	 * @return CSV Dados no formato CSV
	 */
	function gerarCSV($grid,$header=false) {
		//cabecalho
		$csv = null;

		if ($header==true) {
			for ($j=0; $j < count($grid[0]); $j++) {
				$campo = $grid[0][$j];
				$csv .= "$campo;";
			}
			$csv = substr($csv,0,-1)."\n";
		}
		$csv = substr($csv,0,-1)."\n";

		//valores
		for ($i=1;$i < count($grid);$i++) {
			for ($j=0; $j < count($grid[0]); $j++) {
				$campo = $grid[0][$j];
				$valor = trim($grid[$i][$campo]);
				$csv .= "$valor;";
			}
			$csv = substr($csv,0,-1)."\n";
		}
		return substr($csv,0,-1);
	}

	/**
	 * Função para conversar datas antes de salvar e integrar com campos DATE no firebird.
	 *
	 * @param date $data Data na formato dd/mm/aaaa
	 * @return date Data na formato mm/dd/aaaa
	 */
	function dataToFire($data) {
		//$dtTemp = explode("/",$data);
		//return $dtTemp[1] . "/" . $dtTemp[0] . "/" . $dtTemp[2];
		return str_replace("/",".",$data);
	}

	function comboUF() {
		$estados = $this->db->selectQuery("SELECT CD_UF, DS_UF FROM tab_uf;");
		for ($i = 0; $i < $estados->getNumLinhas(); $i++) {
			$comboUF[$estados->getReg("CD_UF",$i)] = $estados->getReg("DS_UF",$i);
		}
		unset($estados);
		return $comboUF;
	}

	function getTabela($idTabela,$pag=25,$opInfo=null,$registro_add=null,&$objRemoto=null) {
		$UI = new UI($this->smarty);
		if ($opInfo=="GRID") {
			$_SESSION[$idTabela]['novo'] = 0;
			$grid["paginacao"] = $_SESSION[$idTabela]['paginacao'];
			$grid["paginacao"]["pagAtual"] = $pag-1;
		} else {
			$grid["paginacao"]["regPagina"] = $pag;
			$grid["paginacao"]["pagAtual"] = 0;
			$_SESSION[$idTabela]['novo'] = 1;
		}

		$this->db->conferirDireitoAcesso($idTabela);

		$grid = ($grid["paginacao"]["regPagina"] == 0) ? $_SESSION[$idTabela]["grid"] : $this->paginacao($idTabela,$grid["paginacao"]["pagAtual"]);
		/*
		 echo "<pre>";
		 var_dump($_SESSION[$idTabela]);
		 echo "</pre><br />";
		 */
		return @$UI->montarTabela($grid,$_SESSION[$idTabela]["tabela"],$idTabela,$opInfo,$registro_add,$objRemoto);
	}

	/**
	 * Retorna uma linha de uma determinada tabela na Sessão do PHP
	 *
	 * @param string $idTabela
	 * @param int $i
	 * @param object $objRemoto
	 * @return string
	 */
	function getLinhaGrid($idTabela, $n = null, &$objRemoto = null) {
		$n = ($n != null) ?  $n : $_SESSION["PEDV_PECA"]["proxNrSeq"];
		$this->smarty->assign("objJS", strtolower($idTabela));
		$this->db->conferirDireitoAcesso($idTabela);

		$c =  ($_SESSION[$idTabela]['flCtrl'] != "N") ? 1 : 0;
		$tabela = $_SESSION[$idTabela]["tabela"];
		$registro = $_SESSION[$idTabela]["grid"][$n];

		for ($i=0;$i < count($tabela[0]); $i++) {
			$temp = $tabela[1][$i];
			while (strpos($temp,"%[")!==FALSE && $temp != "%[CTRL]%") {
				$posI = strpos($temp,"%[")+2;
				$posF = strpos($temp,"]%")-$posI;
				$campo = substr($temp,$posI,$posF);
				$temp = str_replace("%[".$campo."]%",trim($registro[$campo]),$temp);
			}

			if (isset($tabela[2]) && ($tabela[2][$i] != "")) {
				$temp2 = $tabela[2][$i];
				while (strpos($temp2,"%[")!==FALSE) {
					$posI = strpos($temp2,"%[")+2;
					$posF = strpos($temp2,"]%")-$posI;
					$campo2 = substr($temp2,$posI,$posF);
					$temp2 = str_replace("%[".$campo2."]%",trim($registro[$campo2]),$temp2);
				}
				eval($temp2);
			}
			$linha[$i+$c] = $temp;
		}

		if ($_SESSION[$idTabela]['flCtrl'] != "N") {
			foreach($tabela['key'] as $num => $campo) {
				$chave .= "'".$registro[$campo]."',";
			}
			$this->smarty->assign("chave",substr($chave,0,-1));
			$linha[0] = $this->smarty->fetch("lib/imgCtrlGrid.tpl");
		}

		return $this->gerarLinhaXML("linha",$linha);
	}

	function paginacao($idTabela,$pag) {
		@$estGrid = array_slice($_SESSION[$idTabela]['grid'],0,2);
		@$ini = ($pag * $estGrid["paginacao"]["regPagina"]) + 2 ;
		@$dadosGrid = array_slice($_SESSION[$idTabela]['grid'],$ini,$estGrid["paginacao"]["regPagina"]);

		return @array_merge($estGrid,$dadosGrid);
	}



	/*
	 * cd_campo = onde o valor do banco de dados é igual a este valor.
	 * nm_campo = nome da coluna do banco de dados que será retornada.
	 */
	function buscaCampo() {
		$retorno = false;

		$nm_campo = $_GET["nm_campo"];
		$vl_campo = strtoupper($_GET["vl_campo"]);

		switch ($nm_campo){
			//PROMOTOR
			case 'CD_USU_SISTEMA':
			case 'AP_CD_USU_SISTEMA':
				$sql = "select b.nm_usu as DS_USU_SISTEMA from tab_usu_urbanauta a
						inner JOIN TAB_USU b on(b.CD_USU = a.cd_usu_sistema)
						where a.fl_promotor='S' and a.CD_USU = '$vl_campo'";
				break;

				//USUÁRIO ABERTURA PEDIDO
			case 'CD_USU_ABERTURA':
			case 'AP_CD_USU_ABERTURA':
				$sql = "SELECT B.NM_USU AS DS_USU_ABERTURA
					FROM TAB_USU C
					INNER JOIN TAB_USU B ON(B.CD_USU=C.CD_USU_SISTEMA)
					WHERE C.CD_USU = '$vl_campo' AND
						C.FL_ATIVO='S' AND EXISTS(
								SELECT FIRST 1 SKIP 0 A.CD_USU_ABERTURA
								FROM TAB_PEDV A
								WHERE A.CD_USU_ABERTURA = C.CD_USU)";
				break;

				//CLIENTE
			case 'CD_REVENDEDOR':
			case 'AP_CD_REVENDEDOR':
				$sql = "SELECT B.NM_CON, A.CD_REVENDEDOR, C.CD_FABRICA
						FROM TAB_REVENDEDOR A
					LEFT OUTER JOIN TAB_CON B ON(B.CD_CON = A.CD_REVENDEDOR)
					LEFT OUTER JOIN CON_FABR C ON(C.CD_FABRP=(SELECT CD_RESERV3 FROM TAB_PAR) AND C.CD_CON=B.CD_CON)
					WHERE C.CD_FABRICA = '$vl_campo' OR A.CD_REVENDEDOR = '$vl_campo'";
				break;

				//STATUS
			case 'CD_STATUS_PEDV':
			case 'AP_CD_STATUS_PEDV':
				$sql = "select distinct b.ds_status_pedv, b.cd_status_pedv from tab_pedv_urbanauta a
						inner JOIN tab_status_pedv b on(b.cd_status_pedv = a.cd_status_pedv)
						where b.cd_status_pedv = '$vl_campo'";
				break;

				//SITUACAO
			case 'CD_SITPEDV':
			case 'AP_CD_SITPEDV':
				$sql = "select distinct b.ds_sitpedv, a.cd_sitpedv from tab_pedv_urbanauta a " .
							"inner join tab_sitpedv b on (a.cd_sitpedv=b.cd_sitpedv) " .
						"where b.cd_sitpedv = '$vl_campo'";
				break;

				//GERENCIA
			case 'CD_GERENTE':
				$sql = "select b.nm_usu as nm_gerente, a.CD_USU from tab_usu_urbanauta a
						inner JOIN tab_usu b on(b.cd_usu = a.cd_usu_sistema)
						where a.CD_USU = '$vl_campo' and a.fl_gerencia='S'";
				break;

				//DIRETOR
			case 'CD_DIRETOR':
				$sql = "select b.nm_usu as nm_diretor, a.CD_USU from tab_usu_urbanauta a
												inner JOIN tab_usu b on(b.cd_usu = a.cd_usu_sistema)
												where a.CD_USU = '$vl_campo' and a.fl_diretoria='S'";
				break;

				//SUPERVISOR
			case 'CD_SUPERVISOR':
				$sql = "select b.nm_usu as ds_supervisor, a.CD_USU from tab_usu_urbanauta a
						inner JOIN tab_usu b on(b.cd_usu = a.cd_usu_sistema)
						where a.CD_USU = '$vl_campo' and a.fl_supervisor='S'";
				break;

				//TIPO DO PEDIDO
			case 'CD_TIPO_PEDV':
			case 'AP_CD_TIPO_PEDV':
				$sql = "select distinct b.ds_tipo_pedv, b.cd_tipo_pedv from tab_pedv_urbanauta a
						inner JOIN tab_tipo_pedv b on(b.cd_tipo_pedv = a.cd_tipo_pedv)
						where b.cd_tipo_pedv = '$vl_campo'";
				break;

				//PRODUTO
			case 'CD_PECA':
			case 'AP_CD_PECA':
				$sql = "select ds_peca, cd_peca from tab_peca
						where cd_peca = '$vl_campo'";
				break;
			case 'CD_CAMPANHA':
			case 'AP_CD_CAMPANHA':
				$sql = "select ds_campanha, cd_campanha from tab_campanha
							where cd_campanha = '$vl_campo'";
				break;
			case 'CD_PECA_DSCTO':
			case 'AP_CD_PECA_DSCTO':
				$sql = "SELECT DS_PECA AS DS_PECA_DSCTO, CD_PECA FROM TAB_PECA
						WHERE CD_PECA = '$vl_campo'";
				break;

			case 'CD_DSCTO_PEDV_CF':
			case 'AP_CD_DSCTO_PEDV_CF':
				$sql = "select DS_DSCTO_PEDV as DS_DSCTO_PEDV_CF, " .
							"CD_DSCTO_PEDV as CD_DSCTO_PEDV_CF " .
							"from TAB_DSCTO_PEDV " .
							"where CD_DSCTO_PEDV = '$vl_campo'";
				break;

				//VARIANTES - CD_COMPONENTE
			case 'CD_CONJUNTO':
				$sql = "select ds_peca as ds_conjunto, cd_peca as cd_conjunto from tab_peca
						where cd_fabrp=(select cd_reserv3 from tab_par) and cd_peca = '$vl_campo'";
				break;

				//VARIANTES - CD_COMPONENTE
			case 'CD_COMPONENTE_CFG':
				$sql = "select ds_peca as ds_componente_cfg, cd_peca as cd_componente_cfg from tab_peca
						where cd_fabrp=(select cd_reserv3 from tab_par) and cd_peca = '$vl_campo'";
				break;

			case 'CD_COMPONENTE':
				$sql = "select ds_peca as ds_componente, cd_peca as cd_componente from tab_peca
						where cd_fabrp=(select cd_reserv3 from tab_par) and cd_peca = '$vl_campo'";
				break;

				//CONDICAO DE PAGAMENTO
			case 'CD_COND_PAGTO_PEDV':
				$sql = "select distinct b.cd_cond_pagto_pedv, b.ds_cond_pagto_pedv from tab_pedv_urbanauta a
						inner JOIN tab_cond_pagto_pedv b on(b.cd_cond_pagto_pedv = a.cd_cond_pagto_pedv)
						where b.cd_cond_pagto_pedv = '$vl_campo' order by b.cd_cond_pagto_pedv";
				break;
		}

		$rs = $this->db->selectQuery($sql);
		$grid = $rs->getGrid();
		unset($rs);

		return $this->gerarXML("retorno", "campo", $grid);
	}
}
?>
<?php
error_reporting(E_ALL);

if (0 > version_compare(PHP_VERSION, '5')) {
    die('This file was generated for PHP 5');
}
/**
 * Classe para manipula��o de objetos da User Interface.
 *
 * @access public
 * @author Leonardo Cidral <leonardo@softin.com.br>
 * @version 1.0
 * @since 1.0 - 28/08/2006
 */
class UI {
    // --- ATTRIBUTES ---
    private $db = null;
    private $smarty = null;
    private $idGuias;
    private $aba;

    // --- OPERATIONS ---
    /**
     * Construtor para menu. L� tabela em banco de dados, e salva os dados nas matrizes.
     *
     * @access public
     * @param resource $db Banco de dados, por refer�ncia.
     * @param resource $smarty Smarty, por refer�ncia.
     * @return void
     */
    function UI(&$smarty) {
    	$this->smarty = &$smarty;
    }

    /**
     * Fun��o que gera o Segmento de tela
     *
     * @access public
     * @param $cd_segmento string Id do elemento
     * @param $tt_segmento string T�tulo do segmento
     * @param $ds_cont_segmento string Conteudo do segmento
     * @param $ocultar bool Aberto ou Fechado
     * @return $str_segmento string
     */
    function sAddSegmento($cd_segmento, $tt_segmento, $ds_cont_segmento, $ocultar=false, $extra=null) {
		$this->smarty->assign("cd_segmento",$cd_segmento);
		$this->smarty->assign("tt_segmento",$tt_segmento);
		$this->smarty->assign("conteudo_segmento",$ds_cont_segmento);
		$this->smarty->assign("ocultar",$ocultar);
		$this->smarty->assign("styleExtra",$extra);
		$str_segmento = $this->smarty->fetch("lib/segmento.tpl");
    	return $str_segmento;
    }

    function addAba($guia,$id,$tt,$ct) {
		$this->aba[$guia][] = array($id,$tt,$ct);
    }

    function getGuias($guia) {
    	$this->smarty->assign("idGuias",$guia);
    	for ($i=0;$i < count($this->aba[$guia]); $i++) {
	    	$idsAbas[$i] = $this->aba[$guia][$i][0];
	    	$ttsAbas[$i] = $this->aba[$guia][$i][1];
	    	$ctsAbas[$i] = $this->aba[$guia][$i][2];
    	}
    	$this->smarty->assign("idAba",$idsAbas);
    	$this->smarty->assign("ttAba",$ttsAbas);
    	$this->smarty->assign("ctAba",$ctsAbas);
    	$this->aba = null;
    	return $this->smarty->fetch("lib/tabWebFx.tpl");
    }

    /**
     * Monta uma tabela na tela
     *
     * @version 1.0
     * @author Anderson Jord�o Marques <anderson at softin com br>
     * @param array $grid Grid com a seguinte estrutura:
     * +["paginacao"] = dados references a paginacao, quando houver
     * | + ["regPagina"] = qtd de registros por pagina;
     * | + ["pagAtual"] = numero da pagina atual que � mostrada na tela;
     * | + ["totReg"] = numero total de registros a serem exibidos;
     * +[0] = array com as identificacoes do campo
     * | + [0] = "campo1"
     * | + [1] = "campo2"
     * | + [N-1] = "campoN"
     * +[1] = array com os valores de cada campo, no primeiro registro
     * | + ["campo1"] = valor do campo1 no 1� registro
     * | + ["campo2"] = valor do campo2 no 1� registro
     * | + ["campoN"] = valor do campoN no 1� registro
     * +[2] = array com os valores de cada campo, no segundo registro
     * | + ["campo1"] = valor do campo1 no 2� registro
     * | + ["campo2"] = valor do campo2 no 2� registro
     * | + ["campoN"] = valor do campoN no 2� registro
     * +[N] = array com os valores de cada campo, no en�ssimo registro
     * | + ["campo1"] = valor do campo1 no N� registro
     * | + ["campo2"] = valor do campo2 no N� registro
     * | + ["campoN"] = valor do campoN no N� registro
     *
     * @param array $tabela Estrutura onde a tabela deve ser montada.
     * +[0] = cabe�alhos da tabela
     * | + [0] = "1� cabe�alho"
     * | + [1] = "2� cabe�alho"
     * | + [N-1] = "N� cabe�alho"
     * +[1] = estrutura simples do campo
     * | + [0] = ""
     * | + [1] = "%[campo2]% - %[campoN]%"
     * | + [N-1] = "%[campoN]%"
     * +[2] = estrutura complexa, com c�digo php executado pela fun��o eval.
     * |	usando sempre \ antes dos especias {", ', \, etc}.
     * |	caso n�o haja, deixar '' vazio.
     * | + [0] = '$temp = \"R$ \" . substr('%[campo1]%',\".\",\",\");'
     * | + [1] = ''
     * | + [N-1] = ''
     * +["key"] = array que ser� passado a fun��o de alterar do js.
     * | + ["campo1"] = apenas o identifica��o do campo.
     * | + ["campoN"] = apenas o identifica��o do campo.
     * @param String $flCtrl Identifica��o da tabela, e nome do JS.
     * TODO: implementar opInfo, para informa��es adicionaois.
	 * @param array $opInfo Quando informado, a feita verifica��o dos campos informados e mostrado
	 * o conte�do no TPL especificado, atrav�s de ativa��o de sua fun��o JS: informacao(�chave�).
	 * $opInfo = {default: NULL || "falta implementa��o"}
     */
    function montarTabela($grid, $tabela, $idTabela, $opInfo, $registro_add=null, &$objRemoto=null) {
		$this->smarty->assign("cdTela",$idTabela);

		if (!isset($grid["paginacao"]["regPagina"])) {
			$grid["paginacao"]["regPagina"] = 25;
		}

		if ($_SESSION[$idTabela]['flCtrl'] != "N") {
			$cabecalho['ds'][0] = "A&ccedil;&otilde;es";
			$cabecalho['cd'][0] = "CTRL";
			$c = 1;
		} else $c = 0;
		$this->smarty->assign("objJS", strtolower($idTabela));

		if (count($grid) > 2 || $registro_add != null) {
			$j=0;
			foreach ($grid as $numLinha => $registro) if ($numLinha != "0" && $numLinha != "paginacao") {
				//$linha = $j+1;
				for ($i=0;$i < count($tabela[0]); $i++) {
					$cabecalho['ds'][$i+$c] = $tabela[0][$i];
					$temp = $tabela[1][$i];
					while (strpos($temp,"%[")!==FALSE) {
						$posI = strpos($temp,"%[")+2;
						$posF = strpos($temp,"]%")-$posI;
						$campo = substr($temp,$posI,$posF);
						$temp = str_replace("%[".$campo."]%",trim($registro[$campo]),$temp);
						if (isset($_SESSION[$idTabela]["totalizar"]) && $_SESSION[$idTabela]["totalizar"][$i] == "S") {
							if ($j==0) $_SESSION[$idTabela]["rodape"][$campo] = 0;
							$registro[$campo] *= 100;
							$registro[$campo] = intval("$registro[$campo]")/100;
							$_SESSION[$idTabela]["rodape"][$campo] += $registro[$campo];
						}
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
					if (isset($tabela[3]) && ($tabela[3][$i] != "")) {
						$cabecalho['ord'][$i+$c] = $tabela[3][$i];
					}
					$new_grid[$j][$i+$c] = $temp;
					$cabecalho['cd'][$i+$c] = isset($campo) ? $campo : $campo2;
					$cabecalho['tipo'][$i+$c] = isset($campo) ? substr($campo,0,2) == "PS" ? substr($campo,3,2) : substr($campo,0,2) : substr($campo2,0,2);
				}
				$chave = NULL;
				if ($_SESSION[$idTabela]['flCtrl'] != "N") {
					foreach($tabela['key'] as $num => $campo) {
						$chave .= "'".$registro[$campo]."',";
					}
					$this->smarty->assign("chave",substr($chave,0,-1));
					$new_grid[$j][0] = $this->smarty->fetch("lib/imgCtrlGrid.tpl");
				}
				$j++;
			}

			//coloca��o das linhas adicionais
			for ($j=0;$j < count($registro_add); $j++) {
				for ($i=0;$i < count($tabela[0]); $i++) {
					$cabecalho['ds'][$i+$c] = $tabela[0][$i];
					$temp = $tabela[1][$i];

					$posI = strpos($temp,"%[")+2;
					$posF = strpos($temp,"]%")-$posI;
					$campo = substr($temp,$posI,$posF);

					if (isset($registro_add["reg"][$j])) {
						$linhas_add[$j][$i+$c] = $registro_add["reg"][$j][$i];
						$temp = $tabela[1][$i];
					}
					$cabecalho['cd'][$i+$c] = isset($campo) ? $campo : $campo2;
				}
				$chave = NULL;
				if ($_SESSION[$idTabela]['flCtrl'] != "N") {
					$this->smarty->assign("chave",substr($chave,0,-1));
					$linhas_add[$j][0] = "";
				}
			}

			if (isset($_SESSION[$idTabela]["totalizar"])) for ($i=0;$i < count($tabela[0]); $i++) {
				$temp = $tabela[1][$i];
				$posI = strpos($temp,"%[")+2;
				$posF = strpos($temp,"]%")-$posI;
				$campo = substr($temp,$posI,$posF);
				if ($_SESSION[$idTabela]["totalizar"][$i] == "S") {
					$valor = number_format($_SESSION[$idTabela]["rodape"][$campo], 2, ",",".");
					if (isset($_SESSION[$idTabela]["coresValor"][$i])) {
						$temp = '<span style="color:#';
						$temp .= ($valor < 0) ? $_SESSION[$idTabela]["coresValor"][$i]["neg"] : $_SESSION[$idTabela]["coresValor"][$i]["pos"];
						$temp .= ';">';
						$temp .= $valor;
						$temp .= '</span>';
						$valor = $temp;
					}
				} else {
					$valor = "&nbsp;";
				}
				$rodape[0][$i+$c] = $valor;
			}

			//registros do grid no smarty
			$this->smarty->assign("cabecalho",$cabecalho);
			$this->smarty->assign("registro",$new_grid);
			$this->smarty->assign("rodape",isset($rodape) ? $rodape : "");

			if ($registro_add != null) {
				$this->smarty->assign("registro_add",$linhas_add);
				if (isset($registro_add["class"])) $this->smarty->assign("class_add",$registro_add["class"]);
				if (isset($registro_add["mouseOver"])) $this->smarty->assign("mouse_over",$registro_add["mouseOver"]);
				if (isset($registro_add["mouseOut"])) $this->smarty->assign("mouse_out",$registro_add["mouseOut"]);
			} else
				$this->smarty->assign("registro_add", "");

			if ($grid["paginacao"]["regPagina"]!=0 && @$_SESSION[$idTabela]['novo']==1) {
				$linhas = $grid["paginacao"]["totReg"];
				$this->smarty->assign("primeiro",TRUE);
				$paginas = $linhas/$grid["paginacao"]["regPagina"];
				if ($paginas > 1) {
					for ($p=1;$p < $paginas + 1; $p++) $pg[] = $p;
					$this->smarty->assign("nr_paginas",count($pg));
					$this->smarty->assign("paginas",$pg);
				}
			}
			$this->smarty->assign("ordenacao",$grid["paginacao"]["totReg"]/$grid["paginacao"]["regPagina"]);
			$_SESSION[$idTabela]['opInfo'] = $opInfo;
			$_SESSION[$idTabela]['tabela'] = $tabela;
			$_SESSION[$idTabela]['paginacao'] = $grid["paginacao"];

			return $this->smarty->fetch("lib/grid.tpl");
		} else {
			ob_start();
			echo '<div id="fullgrid" style="width: 90%;">' .
				'<p class="msgGrid">' .
				'<strong style="text-transform: uppercase; text-align: center; clear: both; font-weight: bolder; display: block;">Nenhum registro encontrado</strong><br />';
			echo 'N&atilde;o foi poss&iacute;vel encontrar registros correspondentes aos crit&eacute;rios preenchidos ou n�o existem registro cadastrados nesta base de dados.';
			//var_dump($grid);
			echo '</p>' .
				'</div>';
			return ob_get_clean();
		}
	}
}
?>
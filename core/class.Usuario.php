<?php
error_reporting(E_ALL);

/**
 * frameSoft - class.Menu.php
 *
 * $Id$
 *
 * This file is part of frameSoft.
 *
 * Automatic generated with ArgoUML 0.22.beta2 on 25.07.2006, 08:23:01
 *
 * @author Anderson Jord�o Marques <ajm@urbanauta.com.br>
 * @since 24/07/2006
 * @version 1.0
 */

if (0 > version_compare(PHP_VERSION, '5'))
{
	die('This file was generated for PHP 5');
}

require_once ('class.MyDataBase.php');
require_once ('class.Util.php');

/**
 * Classe para manipula��o de usu�rios.
 *
 * @access public
 * @author Anderson Jord�o Marques <ajm@urbanauta.com.br>
 * @version 1.0
 */
class Usuario {
	// --- ATTRIBUTES ---

	private $db = null;
	private $smarty = null;
	private $crypto = null;

	// --- OPERATIONS ---

	/**
	 * Construtor para usu�rio.
	 *
	 * @access public
	 * @param resource $db Banco de dados, por refer�ncia.
	 * @param resource $smarty Smarty, por refer�ncia.
	 * @return void
	 */
	function Usuario(& $db, & $smarty, & $crypto, & $UI) {
		$this->db = & $db;
		$this->smarty = & $smarty;
		$this->crypto = & $crypto;
		$this->util = new Util(& $db, & $smarty);
		$this->UI = & $UI;
	}

	public function verificarPermissao($cdUsu, $cdTela) {
		return $db->selectQuery("SELECT FL_CONSULTAR, FL_INCLUIR, FL_ALTERAR " .
		"FROM TAB_MENU_USUARIO WHERE CD_MENU='$cdTela' AND CD_USU='$cdUsu'");
	}

	public function atribuirBotoesCtrl($cdUsu, $cdTela) {
		$consulta = $this->verificarPermissao($cdUsu, $cdTela);
		$this->smarty->assign("opIncluir", $consulta->getReg("FL_INCLUIR"));
		$this->smarty->assign("opAlterar", $consulta->getReg("FL_ALTERAR"));
		//$this->smarty->assign("opExcluir",$consulta->getReg("FL_EXCLUIR"));
		$this->smarty->assign("cdTela", $cdTela);
		return $this->smarty->fetch("lib/botoesCtrl.tpl");
	}

	public function listar() {
		$usuarios = $this->db->selectQuery("SELECT CD_USU, CD_USU_SISTEMA
			FROM TAB_USU
			WHERE (CD_USU_SISTEMA <> 'SUPERVISOR' OR '".$_SESSION["dsUsuario"]."' = 'SUPERVISOR')
			AND FL_ATIVO = 'S'
			ORDER BY CD_USU_SISTEMA;");
		$grid = $usuarios->getGrid();
		ini_set("error_reporting", E_ALL & ~E_NOTICE);
		$retorno[0] = '-----------------';
		for ($j = 1; $j < count($grid); $j++) {
			$retorno[$grid[$j]['CD_USU']] = $grid[$j]['CD_USU_SISTEMA'];
		}
		return $retorno;
	}

	public function gridUsuarios() {
		$sql = "SELECT a.CD_USU, a.CD_USU_SISTEMA, " .
				"b.NM_USU, a.FL_ATIVO " .
				"FROM TAB_USU a " .
				"INNER JOIN TAB_USU b ON(b.CD_USU=a.CD_USU_SISTEMA)" .
				"WHERE a.FL_ATIVO = 'S' " .
				"ORDER BY CD_USU_SISTEMA";
		$tabela[0] = array (
			"Commerce",
			"Sistema",
			"Nome",
			"Ativo"
			);
			$tabela[1] = array (
			"%[CD_USU]%",
			"%[CD_USU_SISTEMA]%",
			"%[NM_USU]%",
			"%[FL_ATIVO]%"
			);
			$tabela[2] = array (
			'',
			'',
			'',
			'$temp = ("%[FL_ATIVO]%" == "S") ? "<img src=\"skin/'.NM_TEMA.'/ico/on.gif\" alt=\"Sim\" />" : "<img src=\"skin/'.NM_TEMA.'/ico/off.gif\" alt=\"N�o\" />";'
			);
			$tabela['key'] = array (
			"CD_USU"
			);
			return $this->db->getTabela($sql, $tabela, "USU_ACESSOS", "A");
	}

	public function frmAlterarSenha() {
		$cdUsu = $_SESSION['cdUsuario'];
		$usu = $this->db->selectQuery("SELECT CD_USU_SISTEMA FROM TAB_USU WHERE CD_USU = '$cdUsu';");
		$dsUsu = trim($usu->getReg("CD_USU_SISTEMA"));
		if ($dsUsu != "SUPERVISOR") {
			$this->smarty->assign("dsUsu", $dsUsu);
			$this->smarty->assign("cdUsu", $cdUsu);
		} else {
			$this->smarty->assign("comboUsu", $this->listar());
		}

		$str_frm = $this->smarty->fetch("telas/USU_SENHA.tpl");
		$str_tela = $this->UI->sAddSegmento("frm_usu_altera_senha", "Usu�rio: Alterar Senha", $str_frm, false);

		return $str_tela;
	}

	public function frmAlterarSenhaPW() {
		$str_frm = $this->smarty->fetch("portal/telas/USU_SENHAPW.tpl");
		$str_tela = $this->UI->sAddSegmento("frm_usu_altera_senha", "Usu�rio: Alterar Senha", $str_frm, false);

		return $str_tela;
	}

	public function alterarSenha($cdUsu, $senhaA, $senhaN) {
		if ($senhaA == "supervisor") {
			$sql["CD_SENHA_MASTER"] = $this->crypto->encrypt($senhaN);
			$onde["CD_USU"] = trim($cdUsu);
			$login = $this->db->updateQuery("TAB_USU", $sql, $onde);
			return 1;
		} else {
			$senhaA = $this->crypto->encrypt($senhaA);

			$selecao = $this->db->selectQuery("SELECT CD_USU FROM TAB_USU " .
			"WHERE CD_SENHA_MASTER = '$senhaA' AND CD_USU = '$cdUsu'");
			if ($selecao->getNumLinhas() == 1) {
				$sql["CD_SENHA_MASTER"] = $this->crypto->encrypt($senhaN);
				$onde["CD_USU"] = trim($cdUsu);
				$login = $this->db->updateQuery("TAB_USU", $sql, $onde);
				return 1;
			} else {
				return $selecao->getNumLinhas();
			}
		}
	}

	public function alterarSenhaPW() {
		//$senhaA = $this->crypto->encrypt($senhaA);
		$senhaA = isset ($_GET["senhaA"]) ? $_GET["senhaA"] : "";
		$senhaN = isset ($_GET["senhaN"]) ? $_GET["senhaN"] : "";
		$cdUsu = $this->getUsuarioLogado();
		$strSql = "SELECT CD_REVENDEDOR FROM TAB_REVENDEDOR WHERE CD_REVENDEDOR = '$cdUsu' AND CD_SENHA = '$senhaA'";
		$selecao = $this->db->selectQuery($strSql);
		if ($selecao->getNumLinhas() == 1) {
			$sql["CD_SENHA"] = trim($senhaN);
			$onde["CD_REVENDEDOR"] = trim($cdUsu);
			$login = $this->db->updateQuery("TAB_REVENDEDOR", $sql, $onde);
			return 1;
		} else {
			return $selecao->getNumLinhas();
		}
	}

	public function gridFrmHierarquia($onde = NULL) {
		$sql = "SELECT A.CD_EQUIPE_VENDAS, A.CD_USU_SISTEMA,
		A.CD_USU, B.NM_USU, A.FL_ATIVO,
		CASE WHEN (A.FL_DIRETORIA='S') THEN 'Diretoria'
		WHEN (A.FL_GERENCIA='S') THEN 'Ger�ncia'
		WHEN (A.FL_SUPERVISOR='S') THEN 'Supervisor'
		WHEN (A.FL_PROMOTOR='S') THEN 'Promotor' END as FL_HIERARQUIA,
		A.CD_FABRICA, A.CD_PROTOCOLO_SEGURANCA, C.CD_USU_SISTEMA AS CD_USU_SUPERIOR
		FROM TAB_USU A
		INNER JOIN TAB_USU B ON(B.CD_USU = A.CD_USU_SISTEMA)
		LEFT OUTER JOIN TAB_USU C ON(C.CD_USU=A.CD_USU_SUPERIOR)";
		if ($onde != NULL) {
			$where = "";
			foreach ($onde as $campo => $valor) if (trim($valor) != "") {
				if (trim($campo) == "a.fl_ativo") {
					$where .= strtoupper($campo) . "='" . strtoupper($valor) . "' AND ";
				} elseif (substr($campo, 0, 4) == "a.fl") {
					$where .= (strtoupper($valor) == "S") ? strtoupper($campo) . "='" . strtoupper($valor) . "' AND " : "";
				} else $where .= strtoupper($campo) . " LIKE '%" . str_replace("*", "%", strtoupper($valor)) . "%' AND ";
			}
			$sql .= ($where != "") ? "WHERE " . substr($where, 0, -5) : null;
		}
		$sql .= " ORDER BY B.NM_USU";
		$tabela[0] = array (
			"C�d.",
			"Usu�rio",
			"Hierarquia",
			"C�d. F�brica",
			"Equipe",
			"Protocolo de Seguran�a",
			"Superior",
			"Ativo"
			);
			$tabela[1] = array (
			"%[CD_USU_SISTEMA]%",
			"<span valor='%[NM_USU]%' id='NM_PROMO_%[CD_USU]%'>%[NM_USU]%</span>",
			"<span valor='%[FL_HIERARQUIA]%' id='FL_HIERARQUIA_%[CD_USU]%'>%[FL_HIERARQUIA]%</span>",
			"%[CD_FABRICA]%",
			"%[CD_EQUIPE_VENDAS]%",
			"%[CD_PROTOCOLO_SEGURANCA]%",
			"%[CD_USU_SUPERIOR]%",
			"%[FL_ATIVO]%"
			);
			$tabela[2] = array (
			'',
			'',
			'',
			'',
			'',
			'',
			'',
			'$temp = ("%[FL_ATIVO]%" == "S") ? "<img src=\"skin/' . NM_TEMA . '/ico/on.gif\" alt=\"Sim\" />" : "<img src=\"skin/' . NM_TEMA . '/ico/off.gif\" alt=\"N�o\" />";'
			);
			$tabela['key'] = array ("CD_USU");
			$grid = $this->db->getTabela($sql, $tabela, "USU_HIERARQUIA", "A");
			return $grid;
	}

	public function frmHierarquia() {
		$grid = $this->gridFrmHierarquia(array("a.FL_ATIVO" => "S"));

		$hierarquia[0] = "Diretor";
		$hierarquia[1] = "Gerente";
		$hierarquia[2] = "Supervisor";
		$hierarquia[3] = "Promotor";
		$this->smarty->assign("hierarquia", $hierarquia);

		$formulario = $this->smarty->fetch("telas/USU_HIERARQUIA.tpl");

		$str_tela = $this->UI->sAddSegmento("form_usu_hierarquia", "Configurador de Hierarquia", $formulario, false);
		$str_tela .= $this->UI->sAddSegmento("grid_usu_hierarquia", "Usu�rios", $grid, false);

		$this->smarty->assign("gridRevPromo", $this->gridRevPromo());
		$this->smarty->assign("gridRevOutPromo", '<div id="fullgrid" style="width: 90%;">' .
		'<p class="msgGrid">' .
		'<strong style="text-transform: uppercase; text-align: center; clear: both; font-weight: bolder; display: block;">Nenhum registro encontrado</strong><br />' .
		'N&atilde;o foi poss&iacute;vel encontrar registros correspondentes aos crit&eacute;rios preenchidos ou n&atilde;o existem registro cadastrados nesta base de dados.' .
		'</p>' .
		'</div>');
		$nivel = $this->smarty->fetch("telas/USU_REVENDEDORES.tpl");
		$str_tela .= $this->UI->sAddSegmento("grid_frm_revendedores", "Revendedores", $nivel, false);

		return $str_tela;
	}

	public function gridRevPromo($cdPromotor = "") {
		$sql = "SELECT A.CD_REVENDEDOR, B.NM_CON " .
		"FROM TAB_REVENDEDOR A " .
		"INNER JOIN TAB_CON B ON(A.CD_REVENDEDOR=B.CD_CON) " .
		"WHERE A.cd_usu_promotor = '$cdPromotor' " .
		"ORDER BY B.NM_CON;";
		$tabela[0] = array (
			"<img src=\"skin/" . NM_TEMA . "/ico/ok.gif\" />",
			"Revendedor"
			);
			$tabela[1] = array (
			"<input type='checkbox' id='CD_REV_%[CD_REVENDEDOR]%' />",
			"%[NM_CON]%"
			);
			$tabela['key'] = array ("CD_REVENDEDOR");
			$grid = $this->db->getTabela($sql, $tabela, "USU_REVENDEDORES");

			return $grid;
	}

	public function gridRevOutPromo($cdPromotor = "", $semPromotor = "N", $valor = "", $campo = "NM_CON") {
		$sql = "SELECT A.CD_REVENDEDOR, B.NM_CON, D.NM_USU, E.DS_CIDADE, E.CD_UF " .
		"FROM TAB_REVENDEDOR A " .
		"INNER JOIN TAB_CON B ON(A.CD_REVENDEDOR=B.CD_CON) " .
		"LEFT OUTER JOIN TAB_USU C ON(C.CD_USU=A.CD_USU_PROMOTOR) " .
		"LEFT OUTER JOIN TAB_USU D ON(D.CD_USU=C.CD_USU_SISTEMA) " .
		"LEFT OUTER JOIN TAB_CON E ON(E.CD_CON=A.CD_REVENDEDOR) " .
		"WHERE ";
		$sql .= $semPromotor == "N" ? "(A.CD_USU_PROMOTOR IS NULL OR A.CD_USU_PROMOTOR <> '$cdPromotor') " : " A.CD_USU_PROMOTOR IS NULL ";

		$sql .= $valor != "" ? "AND UPPER($campo) LIKE '%$valor%'" : "";
		$sql .= "ORDER BY E.CD_UF, E.DS_CIDADE, D.NM_USU, B.NM_CON;";
		$tabela[0] = array (
			"<img src=\"skin/" . NM_TEMA . "/ico/ok.gif\" />",
			"Revendedor",
			"Promotor",
			"Cidade",
			"Estado"
			);
			$tabela[1] = array (
			"<input type='checkbox' id='CD_REVOUT_%[CD_REVENDEDOR]%' />",
			"%[NM_CON]%",
			"%[NM_USU]%",
			"%[DS_CIDADE]%",
			"<center>%[CD_UF]%</center>"
			);
			$tabela['key'] = array ("CD_REVENDEDOR");
			$grid = $this->db->getTabela($sql, $tabela, "USU_OUTROS_REVENDEDORES");

			return $grid;
	}

	public function atribuir($atribuir, $revendedores, $promotor) {
		$onde = "";
		$revendedores = explode("|", $revendedores);
		for ($i = 0; $i < count($revendedores); $i++) {
			$onde .= "CD_REVENDEDOR='" . $revendedores[$i] . "' OR ";
		}
		$set["CD_USU_PROMOTOR"] = ($atribuir == "S") ? $promotor : "NULL";
		$onde = substr($onde, 0, -4);
		$this->db->updateQuery("TAB_REVENDEDOR", $set, $onde);
		return $this->gridRevPromo($promotor);
	}

	public function comboUsuSuperior($nivel = 0) {
		$sql = "SELECT A.CD_USU, B.NM_USU " .
		"FROM TAB_USU A " .
		"LEFT OUTER JOIN TAB_USU B ON(B.CD_USU=A.CD_USU_SISTEMA) " .
		"WHERE A.FL_ATIVO='S' AND ";
		switch ($nivel) {
			case 1 :
				$sql .= "A.FL_DIRETORIA='S' ";
				break;
			case 2 :
				$sql .= "A.FL_GERENCIA='S' ";
				break;
			case 3 :
				$sql .= "A.FL_SUPERVISOR='S' ";
				break;
			default :
				$sql = "";
				$cdUsuario["clear"] = "Nivel superior vazio";
				break;
		}
		if ($sql != "") {
			$usuarios = $this->db->selectQuery($sql . "ORDER BY B.NM_USU;");
			if ($usuarios->getNumLinhas() > 0) {
				for ($i = 0; $i < $usuarios->getNumLinhas(); $i++) {
					$cdUsuario[trim($usuarios->getReg("CD_USU", $i))] = $usuarios->getReg("NM_USU", $i);
				}
			}
			else $cdUsuario[""] = "---Nivel superior vazio---";
			unset ($usuarios);
		}
		return $cdUsuario;
	}

	/* TODO implementar auto complete
	 public function getUsuSuperiorAutoComplete($campo_por) {
	 if (is_numeric($campo_por)) {
	 $onde = "a.cd_usu_sistema like '%$campo_por' order by a.cd_usu_sistema";
		} else {
		$onde = "b.nm_usu like '%$campo_por%' order by b.nm_usu";
		}

		$sql = "select first 5 skip 0 b.nm_usu, a.cd_usu_sistema from tab_usu_urbanauta a " .
		"inner JOIN tab_usu b on(b.cd_usu = a.cd_usu_sistema) " .
		"where ".$onde;
		$qyAC = $this->db->selectQuery($sql);
		$grid = $qyAC->getGrid();
		unset($qyAC);
		$ulli = "<ul>";
		if (count($grid) > 1) {
		for ($i=1;$i < count($grid);$i++) {
		$id = $grid[$i]['CD_USU_SISTEMA'];
		$valor = trim($grid[$i]['NM_USU']);
		$ulli .= "<li id='$id'>$valor</li>";
		}
		} else {
		$ulli .= "<li id='clear'>Registro n�o encontrado</li>";
		}
		$ulli .= "</ul>";
		unset($grid);
		return $ulli;
		}*/

	public function alternaUsuario($cdUsu) {
		$permissoes = $this->db->selectQuery("SELECT * FROM TAB_MENU_USUARIO " .
		"WHERE CD_USU = '$cdUsu'");
		$loop = $permissoes->getGrid();
		return $this->util->gerarXML("permissoes", "campo", $loop);
		unset ($loop);
	}

	public function selecionarUsuario($cdUsu) {
		$usuarios = $this->db->selectQuery("SELECT
		A.CD_USU_SISTEMA, A.CD_USU_SUPERIOR, B.NM_USU, A.FL_ATIVO,
		A.CD_FABRICA, A.CD_EQUIPE_VENDAS, A.CD_PROTOCOLO_SEGURANCA,
		A.CD_SENHA_CLIENT, A.DS_EMAIL, A.FL_FINANCEIRO,
		A.FL_DIRETORIA, A.FL_GERENCIA, A.FL_SUPERVISOR, A.FL_PROMOTOR,
		A.NR_DDD, A.NR_FONE, A.NR_RAML
		FROM TAB_USU A
		INNER JOIN TAB_USU B ON(B.CD_USU=A.CD_USU_SISTEMA)
		WHERE CD_USU = '$cdUsu';");
		$loop = $usuarios->getGrid();
		$xml = $this->util->gerarXML("usuarios", "usuario", $loop);
		$xml = substr($xml, 0, -21);
		$xml .= "<comboUsuSuperior><![CDATA[";
		$hierarquia = 0;
		if ($usuarios->getReg("FL_DIRETORIA") == "S") $hierarquia = 0;
		elseif ($usuarios->getReg("FL_GERENCIA") == "S") $hierarquia = 1;
		elseif ($usuarios->getReg("FL_SUPERVISOR") == "S") $hierarquia = 2;
		elseif ($usuarios->getReg("FL_PROMOTOR") == "S") $hierarquia = 3;
		$xml .= $this->util->gerarComboPlus("CD_USU_SUPERIOR", $this->comboUsuSuperior($hierarquia));
		$xml .= "]]></comboUsuSuperior>";
		$xml .= "</usuario>";
		$xml .= "</usuarios>";
		unset ($usuarios);
		return $xml;
	}

	public function incluir($pars, $senha) {
		$pars[13] = $this->crypto->encrypt($senha);
		//var_dump($pars);
		return $this->db->executeProcedure("P_CADASTRA_USU", $pars);
	}

	public function validarHierarquia($cdUsuSistema) {
		$qyCommerce = $this->db->selectQuery("SELECT CD_USU
        	FROM TAB_USU
            WHERE CD_USU_SISTEMA = '$cdUsuSistema' ");
		$cdUsuCommerce = $qyCommerce->getReg("CD_USU",0);
		unset($qyCommerce);

		$qyValida = $this->db->selectQuery("SELECT CD_USU
                FROM TAB_USU
                WHERE CD_USU_SUPERIOR='$cdUsuCommerce'");
		return $qyValida->getNumLinhas();
	}

	public function alterar($pars) {
		$pars[13] = '';
		return $this->db->executeProcedure("P_CADASTRA_USU", $pars);
	}

	public function gridPermissaoMenu($cdMenu, $cdUsuCommerce) {
		$sql = "SELECT " .
		"CASE WHEN b.CD_MENU IS NULL THEN a.CD_MENU " .
		"WHEN b.CD_MENU IS NOT NULL THEN b.CD_MENU " .
		"END as CD_MENU, " .
		"CASE WHEN b.DS_MENU IS NULL THEN a.DS_MENU " .
		"WHEN b.DS_MENU IS NOT NULL THEN a.DS_MENU || '/' || b.DS_MENU " .
		"END as DS_MENU, " .
		"CASE WHEN c.FL_CONSULTAR IS NULL THEN 'N' " .
		"WHEN c.FL_CONSULTAR IS NOT NULL THEN c.FL_CONSULTAR END as FL_CONSULTAR, " .
		"CASE WHEN c.FL_INCLUIR IS NULL THEN 'N' " .
		"WHEN c.FL_INCLUIR IS NOT NULL THEN c.FL_INCLUIR END as FL_INCLUIR, " .
		"CASE WHEN c.FL_ALTERAR IS NULL THEN 'N' " .
		"WHEN c.FL_ALTERAR IS NOT NULL THEN c.FL_ALTERAR END as FL_ALTERAR " .
		"FROM TAB_MENUS b " .
		"RIGHT OUTER JOIN TAB_MENUS a on(b.CD_MENU_PAI = a.CD_MENU and b.CD_GRUPO=a.CD_GRUPO) " .
		"LEFT OUTER JOIN TAB_MENU_USUARIO c on " .
		"(c.CD_MENU = CASE WHEN b.CD_MENU IS NULL THEN a.CD_MENU " .
		"WHEN b.CD_MENU IS NOT NULL THEN b.CD_MENU END AND c.CD_USU='$cdUsuCommerce') " .
		"WHERE a.CD_MENU_PAI = '$cdMenu' AND " .
		"a.CD_GRUPO=CASE " .
		"WHEN (SELECT FL_BASE_MASTER FROM TAB_PAR) = 'S' THEN 1 " .
		"WHEN (SELECT FL_BASE_CLIENT FROM TAB_PAR) = 'S' THEN 0 " .
		" END";
		$tabela[0] = array (
			"Item",
			"Consulta",
			"Inclus�o",
			"Altera��o"
			);
			$tabela[1] = array (
			"%[DS_MENU]%",
			"<span style=\"display: none;\">%[FL_CONSULTAR]%</span><img src=\"skin/kavo/ico/off.gif\" onClick=\"usu_acessos.permissaoUsuario(this,'%[CD_MENU]%','C');\" id=\"imgC_%[CD_MENU]%\">",
			"<span style=\"display: none;\">%[FL_INCLUIR]%</span><img src=\"skin/kavo/ico/off.gif\" onClick=\"usu_acessos.permissaoUsuario(this,'%[CD_MENU]%','I');\" id=\"imgI_%[CD_MENU]%\">",
			"<span style=\"display: none;\">%[FL_ALTERAR]%</span><img src=\"skin/kavo/ico/off.gif\" onClick=\"usu_acessos.permissaoUsuario(this,'%[CD_MENU]%','A');\" id=\"imgA_%[CD_MENU]%\">"
			);
			$tabela[2] = array (
			"",
			'if (\'%[FL_CONSULTAR]%\' == \'S\') $temp = str_replace(\'off\',\'on\',$temp);',
			'if (\'%[FL_INCLUIR]%\' == \'S\') $temp = str_replace(\'off\',\'on\',$temp);',
			'if (\'%[FL_ALTERAR]%\' == \'S\') $temp = str_replace(\'off\',\'on\',$temp);',

			);
			//$tabela['key'] = array("CD_USU");
			$usuarios = $this->db->selectQuery("SELECT b.CD_USU, a.NM_USU FROM TAB_USU b " .
		"LEFT OUTER JOIN TAB_USU a on(a.CD_USU=b.CD_USU_SISTEMA) " .
		"WHERE b.CD_USU = '$cdUsuCommerce'");
			$this->smarty->assign("dsUsu", $usuarios->getReg("NM_USU"));
			$this->smarty->assign("cdUsu", $usuarios->getReg("CD_USU"));
			$this->smarty->assign("comboMenuBar", $this->comboMenuBar());
			$this->smarty->assign("menuSel", $cdMenu);
			$this->smarty->assign("gridPermissaoMenu", $this->db->getTabela($sql, $tabela, "PERMISSAO_USU", "N"));
			return $this->smarty->fetch("telas/USU_ACESSOS.tpl");
	}

	public function frmAcessoUsuario() {
		$str_tela = <<<EOD
<style>
.USU_ACESSOS_col_CTRL {
	width: 10%;
	text-align: center;
}

.USU_ACESSOS_col_CD_USU {
	width: 10%;
	text-align: center;
}

.USU_ACESSOS_col_CD_USU_SISTEMA {
	width: 20%;
}

.USU_ACESSOS_col_NM_USU {
	width: 50%;
}

.USU_ACESSOS_col_FL_ATIVO {
	width: 10%;
	text-align: center;
}


.PERMISSAO_USU_col_DS_MENU {
	width: 70%;
}

.PERMISSAO_USU_col_CD_MENU {
	text-align: center;
}

</style>
EOD;
		$str_tela .= $this->UI->sAddSegmento("grid_usuarios", "Lista de usu�rios", $this->gridUsuarios(), false);
		$str_tela .= $this->UI->sAddSegmento("grid_menu_acesso", "Permiss�o de Acesso", $this->gridPermissaoMenu(null, null), false);
		return $str_tela;
	}

	public function comboMenuBar() {
		$menuBar = $this->db->selectQuery("SELECT CD_MENU, DS_MENU " .
		"FROM TAB_MENUS WHERE FL_NIVEL=0 AND " .
		"CD_GRUPO=CASE " .
		"WHEN (SELECT FL_BASE_MASTER FROM TAB_PAR) = 'S' THEN 1 " .
		"WHEN (SELECT FL_BASE_CLIENT FROM TAB_PAR) = 'S' THEN 0 " .
		" END;");
		for ($i = 0; $i < $menuBar->getNumLinhas(); $i++) {
			$comboMenuBar[trim($menuBar->getReg("CD_MENU", $i))] = trim($menuBar->getReg("DS_MENU", $i));
		}
		unset ($menuBar);
		return $comboMenuBar;
	}

	public function getGridPromotorLivrinho($campo_busca, $campo_por, $objID, $objJS) {

		$onde = "";
		if ($campo_busca != "") {
			$onde = " and ";
			$onde .= ($campo_por == 1) ? "a.cd_usu_sistema = '$campo_busca'": "b.nm_usu like '%$campo_busca%'";
		}

		$sql = "select distinct b.nm_usu, a.CD_USU " .
		"from tab_usu_urbanauta a " .
		"inner JOIN tab_pedv_urbanauta c on(a.cd_usu_urbanauta = c.cd_usu_urbanauta) " .
		"inner JOIN TAB_USU b on(b.CD_USU = a.cd_usu_sistema) " .
		"where a.fl_ativo='S' and a.fl_promotor='S' " . $onde;
		$tabela[0] = array (
			"A��o",
			"C�d.",
			"Usu�rio"
			);
			$tabela[1] = array (
			"<button class=\"btAcao\" style='cursor: pointer; width: 21px;' onClick=\"$objJS.selecionarRegLivrinho('%[CD_USU]%','%[NM_USU]%','CD_USU_SISTEMA','DS_USU_SISTEMA','$objID');\"><img src=\"skin/kavo/ico/selecionar.gif\" /></button>",
			"%[CD_USU]%",
			"%[NM_USU]%"
			);
			$tabela['key'] = array ("CD_USU");
			//echo $sql;
			$grid = $this->db->getTabela($sql, $tabela, "USU_PROMOTOR_LIVRO", "N", null, 10);
			return $grid;
	}

	public function getGridUsuarioLivrinho($campo_busca, $campo_por, $objID, $objJS) {
		$onde = "";
		if ($campo_busca != "") {
			$onde = " and ";
			$onde .= ($campo_por == 1) ? "a.cd_usu_abertura = '$campo_busca'" : "b.nm_usu like '%$campo_busca%'";
		}

		$sql = "SELECT C.CD_USU, B.NM_USU
				FROM TAB_USU C
				INNER JOIN TAB_USU B ON(B.CD_USU=C.CD_USU_SISTEMA)
				WHERE C.FL_ATIVO='S' AND EXISTS(
				      SELECT FIRST 1 SKIP 0 A.CD_USU_ABERTURA
				      FROM TAB_PEDV A
				      WHERE A.CD_USU_ABERTURA = C.CD_USU) " . $onde;
		$tabela[0] = array (
			"A��o",
			"C�d.",
			"Usu�rio"
			);
			$tabela[1] = array (
			"<button class=\"btAcao\" style='cursor: pointer; width: 21px;' onClick=\"$objJS.selecionarRegLivrinho('%[CD_USU]%','%[NM_USU]%','CD_USU_ABERTURA','DS_USU_ABERTURA','$objID');\"><img src=\"skin/kavo/ico/selecionar.gif\" /></button>",
			"%[CD_USU]%",
			"%[NM_USU]%"
			);
			$tabela['key'] = array ("CD_USU");
			//echo $sql;
			return $this->db->getTabela($sql, $tabela, "USU_ABERTURA_LIVRO", "N", null, 10);
	}

	public function getPromotorAutoComplete($campo_por) {
		if (is_numeric($campo_por)) {
			$onde = "a.cd_usu_sistema like '%$campo_por' order by a.cd_usu_sistema";
		} else {
			$onde = "b.nm_usu like '%$campo_por%' order by b.nm_usu";
		}

		$sql = "select first 5 skip 0 b.nm_usu, a.cd_usu_urbanauta from tab_usu_urbanauta a " .
		"inner JOIN tab_usu b on(b.cd_usu = a.cd_usu_sistema) " .
		"where " . $onde;
		$qyAC = $this->db->selectQuery($sql);
		$grid = $qyAC->getGrid();
		unset ($qyAC);
		$ulli = "<ul>";
		if (count($grid) > 1) {
			for ($i = 1; $i < count($grid); $i++) {
				$id = $grid[$i]['CD_USU'];
				$valor = trim($grid[$i]['NM_USU']);
				$ulli .= "<li id='$id'>$valor</li>";
			}
		} else {
			$ulli .= "<li id='clear'>Registro n�o encontrado</li>";
		}
		$ulli .= "</ul>";
		unset ($grid);
		return $ulli;
	}

	public function getUsuarioLogado() {
		$cdUsu = isset ($_SESSION["cdUsuario"]) ? $_SESSION["cdUsuario"] : "";
		return $cdUsu;
	}

	public function nmUsuario($cdUsu) {
		$sql = "select NM_USU from tab_usu where cd_usu = '$cdUsu'";
		$rsU = $this->db->selectQuery($sql);
		return $rsU->getReg("NM_USU");
	}

	public function graphHier($cd_promotor) {

		//$rsPromo = $this->db->queryProcedure("P_RETORNO_PROMOTOR",$cd_promotor);
		$rsSup = $this->db->queryProcedure("P_RETORNO_SUPERVISOR", $cd_promotor);
		$rsGer = $this->db->queryProcedure("P_RETORNO_GERENCIA", $cd_promotor);
		$rsDir = $this->db->queryProcedure("P_RETORNO_DIRETOR", $cd_promotor);
		/*
		 $tmp_str = "xDiretor: ".$rsDir->getReg("PS_CD_USU")."....<br />........." .
		 "xGerente: ".$rsGer->getReg("PS_CD_USU")."....<br />........." .
		 "xSupervisor: ".$rsSup->getReg("PS_CD_USU")."....<br />........." .
		 "xPromotor: ".$rsPromo->getReg("PS_CD_USU")."....<br />.........";
		 */
		$tmp_str = "";
		//$tmp_str = ($rsPromo->getReg("PS_NM_USU") != "") ? "<ul><li>Promotor: ".$rsPromo->getReg("PS_NM_USU") . "</li></ul>": "";
		$tmp_str = ($rsSup->getReg("PS_NM_USU") != "") ? "<ul><li>Supervisor: " . $rsSup->getReg("PS_NM_USU") . "</li>$tmp_str</ul>" : "";
		$tmp_str = ($rsGer->getReg("PS_NM_USU") != "") ? "<ul><li>Gerente: " . $rsGer->getReg("PS_NM_USU") . "</li>$tmp_str</ul>" : "";
		$tmp_str = ($rsDir->getReg("PS_NM_USU") != "") ? "<ul><li>Diretor: " . $rsDir->getReg("PS_NM_USU") . "</li>$tmp_str</ul>" : "";

		return $tmp_str;
	}

}
?>
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
 * @author Anderson Jordão Marques <ajm@urbanauta.com.br>
 * @since 24/07/2006
 * @version 1.0
 */

if (0 > version_compare(PHP_VERSION, '5')) {
	die('This file was generated for PHP 5');
}

require_once('class.MyDataBase.php');
require_once('class.Usuario.php');


class Menu {
	// --- ATTRIBUTES ---

	private $db = null;
	private $smarty = null;

	// --- OPERATIONS ---

	/**
	 * Construtor para menu. Lê tabela em banco de dados, e salva os dados nas matrizes.
	 *
	 * @access public
	 * @param resource $db Banco de dados, por referência.
	 * @param resource $smarty Smarty, por referência.
	 * @return void
	 */
	function Menu(&$db,&$smarty) {
		$this->db = &$db;
		$this->smarty = &$smarty;
	}

	/**
	 * Mostra para determinado usuário, determinado item menu.
	 *
	 * @access public
	 * @param int $cdMenu Código do menu que será mostrado ao usuário.
	 * @param int $usuario Código do usuário que verá o menu.
	 * @param char $fl Indicia a flag de alterar, incluir, atualizar ou excluir.
	 * @param int $subMenu Indica a existência de submenus.
	 * @return void
	 */
	function mostrarItem($cdMenu, $usuario, $fl) {

		switch ($fl) {
			case "C": $sql["FL_CONSULTAR"] = "S"; break;
			case "I": $sql["FL_INCLUIR"] = "S"; break;
			case "A": $sql["FL_ALTERAR"] = "S"; break;
			case "E": $sql["FL_EXCLUIR"] = "S"; break;
		}
		$dataPai = $this->db->selectQuery("SELECT CD_MENU_PAI " .
				"FROM TAB_MENUS " .
				"WHERE CD_MENU = '$cdMenu' AND CD_GRUPO='1'");
		$cdSubMenu = $dataPai->getReg("CD_MENU_PAI",0);

		$dataAvo = $this->db->selectQuery("SELECT CD_MENU_PAI " .
				"FROM TAB_MENUS " .
				"WHERE CD_MENU = '$cdSubMenu' AND CD_GRUPO='1'");

		if ($dataAvo->getNumLinhas() >= 1) {
			$cd = $dataAvo->getReg("CD_MENU_PAI",0);
			$data = $this->db->selectQuery("SELECT * FROM TAB_MENU_USUARIO WHERE " .
				"CD_MENU = '$cd' AND CD_USU = '$usuario'");
			if ($data->getNumLinhas() >= 1) {
				$onde["CD_MENU"] = $cd;
				$onde["CD_USU"] = $usuario;
				$this->db->updateQuery("TAB_MENU_USUARIO",$sql,$onde);
			} else {
				$sql2 = $sql;
				$sql2["CD_MENU"] = $cd;
				$sql2["CD_USU"] = $usuario;
				$this->db->insertQuery("TAB_MENU_USUARIO",$sql2);
			}
			unset($data);
		}
		unset($dataAvo);
		$data = $this->db->selectQuery("SELECT * FROM TAB_MENU_USUARIO WHERE " .
				"CD_MENU = '$cdSubMenu' AND CD_USU = '$usuario'");
		if ($data->getNumLinhas() >= 1) {
			$onde["CD_MENU"] = $cdSubMenu;
			$onde["CD_USU"] = $usuario;
			$this->db->updateQuery("TAB_MENU_USUARIO",$sql,$onde);
		} else {
			$sql2 = $sql;
			$sql2["CD_MENU"] = $cdSubMenu;
			$sql2["CD_USU"] = $usuario;
			$this->db->insertQuery("TAB_MENU_USUARIO",$sql2);
		}
		unset($data);
		unset($dataPai);

		//cadastra a permissão para o menu clicado.
		$data = $this->db->selectQuery("SELECT * FROM TAB_MENU_USUARIO WHERE " .
				"CD_MENU = '$cdMenu' AND CD_USU = '$usuario'");
		$numLinhas = count($data->getGrid()) - 1;
		if ($numLinhas == 1) {
			$onde["CD_MENU"] = $cdMenu;
			$onde["CD_USU"] = $usuario;
			$this->db->updateQuery("TAB_MENU_USUARIO",$sql,$onde);
		} else {
			$sql["CD_MENU"] = $cdMenu;
			$sql["CD_USU"] = $usuario;
			$this->db->insertQuery("TAB_MENU_USUARIO",$sql);
		}
		//echo "OP: $op\n";
		//print_r($sql);
	}

	/**
	 * Esconde para determinado usuário, determinado item menu.
	 *
	 * @access public
	 * @param int $cdMenu Código do menu que será mostrado ao usuário.
	 * @param int $usuario Código do usuário que verá o menu.
	 * @param char $fl Indicia a flag de alterar, incluir, atualizar ou excluir.
	 * @param int $subMenu Indica a existência de submenus.
	 * @return void
	 */
	function esconderItem($cdMenu, $usuario, $fl) {
		$op = "";
		switch ($fl) {
			case "C": $op = "D"; break;
			case "I": $sql["FL_INCLUIR"] = "N"; break;
			case "A": $sql["FL_ALTERAR"] = "N"; break;
		}
		$onde["CD_MENU"] = $cdMenu;
		$onde["CD_USU"] = $usuario;

		if ($op=="D") $this->db->deleteQuery("TAB_MENU_USUARIO",$onde);
		else $this->db->updateQuery("TAB_MENU_USUARIO",$sql,$onde);

		#Verificando outros que utilizam o mesmo PAI
		$this->verificarPai($cdMenu,$usuario,$op,$sql,$onde);

		//return $retorno;
	}


	function verificarPai($cdMenu,$usuario,$op,$sql,$onde) {
		$data = $this->db->selectQuery("SELECT A.CD_MENU FROM TAB_MENU_USUARIO A
											WHERE EXISTS (

											SELECT B.CD_MENU FROM TAB_MENUS B
											WHERE
													B.CD_MENU=A.CD_MENU AND
													B.CD_GRUPO='1' AND
													B.CD_MENU_PAI = (
															SELECT C.CD_MENU_PAI FROM TAB_MENUS C
															WHERE C.CD_MENU='$cdMenu'
															AND C.CD_GRUPO='1'
													)
											) AND A.CD_USU='$usuario'");
		//$retorno = "data: ".$data->getNumLinhas() . "<br/>\n";
		if ($data->getNumLinhas() < 1) {
			$dataPai = $this->db->selectQuery("SELECT A.CD_MENU AS CD_PAI FROM TAB_MENU_USUARIO A " .
											"WHERE A.CD_MENU=(SELECT B.CD_MENU_PAI FROM TAB_MENUS B " .
											"WHERE B.CD_MENU='$cdMenu' AND CD_GRUPO='1') " .
											"AND A.CD_USU='$usuario'");
			//$retorno .= "dataPai: ".$data->getNumLinhas() . "<br/>\n";
			if ($dataPai->getNumLinhas() >= 1) {
				$onde["CD_MENU"] = $dataPai->getReg("CD_PAI",0);
				if ($op=="D") $this->db->deleteQuery("TAB_MENU_USUARIO",$onde);
				else $this->db->updateQuery("TAB_MENU_USUARIO",$sql,$onde);
				$this->verificarPai($dataPai->getReg("CD_PAI",0),$usuario,$op,$sql,$onde);
			}
		}
	}

	/**
	 * Adiciona um novo item ao menu. Indicando sua posição, e
	 * a existência de separdores, e se é negritado ou não.
	 *
	 * @access public
	 * @param array $menu Deve conter as chaves CD,DS,LINK,HINT,NIVEL,ORDEM,GRUPO. Se sem valor, atribuir 'NULL'.
	 * @param int $cdMenuPai indica quem eh o "pai". DEFAULT 0 (menubar).
	 * @param char $separador Indica a existência de separadores. 'N' nenhum, 'A' antes, 'D' depois, 'T' ambos. DEFAULT 0.
	 * @param bool $negrito Indica se existe negrito. DEFAULT false.
	 * @return void
	 */
	function addItem($menu, $cdMenuPai = 0, $separador='N', $negrito=false) {
		$sql["CD_MENU"] = isset($menu['CD']) ? $menu['CD'] : "";
		$sql["DS_MENU"] = isset($menu['DS']) ? $menu['DS'] : "";
		$sql["DS_LINK"] = isset($menu['LINK']) ? $menu['LINK'] : "";
		$sql["DS_HINT"] = isset($menu['HINT']) ? $menu['HINT'] : "";
		$sql["NR_ORDEM"] = isset($menu['ORDEM']) ? $menu['ORDEM'] : "";
		$sql["CD_GRUPO"] = isset($menu['GRUPO']) ? $menu['GRUPO'] : "";
		$sql["CD_MENU_PAI"] = $cdMenuPai;
		$sql["FL_NEGRITO"] = $negrito;
		$sql["FL_NIVEL"] = isset($menu['NIVEL']) ? $menu['NIVEL'] : "";
		if ($separador=='T') {
			$sql["FL_SEPARADOR_ANTES"] = 'S';
			$sql["FL_SEPARADOR_DEPOIS"] = 'S';
		} elseif ($separador=='A') {
			$sql["FL_SEPARADOR_ANTES"] = 'S';
			$sql["FL_SEPARADOR_DEPOIS"] = 'N';
		} elseif ($separador=='D') {
			$sql["FL_SEPARADOR_ANTES"] = 'N';
			$sql["FL_SEPARADOR_DEPOIS"] = 'S';
		} else {
			$sql["FL_SEPARADOR_ANTES"] = 'N';
			$sql["FL_SEPARADOR_DEPOIS"] = 'N';
		}
		$this->db->insertQuery("TAB_MENUS",$sql);
	}

	/**
	 * Atualiza um determinado item de menu. Indicando sua nova posição, e
	 * a existência de separadores e negrito.
	 *
	 * @access public
	 * @param array $menu Deve conter as chaves CD,DS,LINK,HINT,NIVEL,ORDEM,GRUPO. Se sem valor, atribuir 'NULL'.
	 * @param int $cdMenuPai indica quem eh o "pai". DEFAULT 0 (menubar).
	 * @param int $separador Indica a existência de separadores. 0 nenhum, 1 superior, 2 inferior, 3 ambos. DEFAULT 0.
	 * @param bool $negrito Indica se existe negrito. DEFAULT false.
	 * @return void
	 */
	function atualizarItem($menu, $cdMenuPai = 0, $separador='N', $negrito=false) {
		$onde["CD_MENU"] = isset($menu['CD']) ? $menu['CD'] : "";
		$sql["DS_MENU"] = isset($menu['DS']) ? $menu['DS'] : "";
		$sql["DS_LINK"] = isset($menu['LINK']) ? $menu['LINK'] : "";
		$sql["DS_HINT"] = isset($menu['HINT']) ? $menu['HINT'] : "";
		$sql["NR_ORDEM"] = isset($menu['ORDEM']) ? $menu['ORDEM'] : "";
		$sql["CD_GRUPO"] = isset($menu['GRUPO']) ? $menu['GRUPO'] : "";
		$sql["CD_MENU_PAI"] = $cdMenuPai;
		$sql["FL_NEGRITO"] = $negrito;
		$sql["FL_NIVEL"] = isset($menu['NIVEL']) ? $menu['NIVEL'] : "";
		if ($separador=='T') {
			$sql["FL_SEPARADOR_ANTES"] = 'S';
			$sql["FL_SEPARADOR_DEPOIS"] = 'S';
		} elseif ($separador=='A') {
			$sql["FL_SEPARADOR_ANTES"] = 'S';
			$sql["FL_SEPARADOR_DEPOIS"] = 'N';
		} elseif ($separador=='D') {
			$sql["FL_SEPARADOR_ANTES"] = 'N';
			$sql["FL_SEPARADOR_DEPOIS"] = 'S';
		} else {
			$sql["FL_SEPARADOR_ANTES"] = 'N';
			$sql["FL_SEPARADOR_DEPOIS"] = 'N';
		}
		$this->db->updateQuery("TAB_MENUS",$sql,$onde);

	}

	/**
	 * Gera o menu para determinado usuário, cfme tabela de visibilidade no banco de dados.
	 *
	 * @access public
	 * @param int $usuario Código de usuário que acessa o sistema.
	 * @return tpl $menu
	 */
	function geraMenu($cdUsu) {
		$sql = "SELECT CD_USU_SISTEMA, " .
					"(SELECT CASE WHEN FL_BASE_MASTER = 'S' THEN 1 " .
					"WHEN FL_BASE_CLIENT = 'S' THEN 0 END " .
					"FROM TAB_PAR) as CD_GRUPO " .
					"FROM TAB_USU WHERE CD_USU = '$cdUsu'";
		$qyUsu = $this->db->selectQuery($sql);
		$usuario = trim($qyUsu->getReg("CD_USU_SISTEMA",0));
		$cdGrupo = $qyUsu->getReg("CD_GRUPO",0);
		$this->smarty->assign("user","$cdUsu - $usuario");
		unset($qyUsu);
		if($usuario == "SUPERVISOR") {
			$sql = "SELECT * FROM TAB_MENUS WHERE CD_GRUPO='$cdGrupo' ORDER BY FL_NIVEL, NR_ORDEM";
		} else {
			$sql = "SELECT A.* FROM TAB_MENUS A, TAB_MENU_USUARIO B " .
						"WHERE B.CD_USU='$cdUsu' AND A.CD_MENU=B.CD_MENU AND CD_GRUPO='$cdGrupo' ORDER BY A.FL_NIVEL, NR_ORDEM";
		}
		$qyMenu = $this->db->selectQuery($sql);
		$linhas = $qyMenu->getNumLinhas();
		for ($i=0;$i < $linhas;$i++) {
			$nivel = $qyMenu->getReg("FL_NIVEL",$i);
			if ($nivel==0) {
				$menuBar["ds_menubar"][] = $qyMenu->getReg("DS_MENU",$i);
				$menuBar["ds_link"][] 	 = ($qyMenu->getReg("DS_LINK",$i)) ? $qyMenu->getReg("DS_LINK",$i) : false;
				$menuBar["fl_negrito"][] = $qyMenu->getReg("FL_NEGRITO",$i);
				$menuBar["id_menubar"][] = $qyMenu->getReg("CD_MENU",$i);
				$menuBar["fl_submenu"][]	 = "N";
			} else if ($nivel==1) {
				$menuList["ds_menulist"][]	 = $qyMenu->getReg("DS_MENU",$i);
				$menuList["fl_sep_antes"][]	 = $qyMenu->getReg("FL_SEPARADOR_ANTES",$i);
				$menuList["fl_submenu"][]	 = "N";
				$menuList["fl_negrito"][]	 = $qyMenu->getReg("FL_NEGRITO",$i);
				$menuList["id_menulist"][]	 = $qyMenu->getReg("CD_MENU",$i);
				$menuList["ds_link"][]		 = ($qyMenu->getReg("DS_LINK",$i)) ? $qyMenu->getReg("DS_LINK",$i) : false;
				$menuList["fl_sep_depois"][] = $qyMenu->getReg("FL_SEPARADOR_DEPOIS",$i);
				$menuList["id_menubar"][] 	 = $qyMenu->getReg("CD_MENU_PAI",$i);
				for ($j=0;$j < count($menuBar["id_menubar"]);$j++) {
					if ($qyMenu->getReg("CD_MENU_PAI",$i) == $menuBar["id_menubar"][$j] && $menuBar["fl_submenu"][$j]=="N") {
						$menuBar["fl_submenu"][$j] = "S";
						$menuList["id_menubarPai"][] = $menuBar["id_menubar"][$j];
					}
				}
			} else {
				$subMenu["nr_ordem"][] 		 = $qyMenu->getReg("NR_ORDEM",$i);
				$subMenu["ds_submenulist"][] = $qyMenu->getReg("DS_MENU",$i);
				$subMenu["fl_sep_antes"][]	 = $qyMenu->getReg("FL_SEPARADOR_ANTES",$i);
				$subMenu["ds_link"][]		 = ($qyMenu->getReg("DS_LINK",$i)) ? $qyMenu->getReg("DS_LINK",$i) : false;
				$subMenu["fl_negrito"][]	 = $qyMenu->getReg("FL_NEGRITO",$i);
				$subMenu["fl_sep_depois"][]	 = $qyMenu->getReg("FL_SEPARADOR_DEPOIS",$i);
				$subMenu["id_menulist"][] 	 = $qyMenu->getReg("CD_MENU_PAI",$i);
				for ($j=0;$j < count($menuList["id_menulist"]);$j++) {
					if ($qyMenu->getReg("CD_MENU_PAI",$i) == $menuList["id_menulist"][$j] && $menuList["fl_submenu"][$j]=="N") {
						$menuList["fl_submenu"][$j] = "S";
						$subMenu["id_menulistPai"][] = $menuList["id_menulist"][$j];
					}
				}
			}//fim ELSE- nivel != 0 e != 1
		}//FIM DA busca no menu
		unset($qyMenu);

		//acrescentar o botão Ajuda
		$menuBar["ds_menubar"][] = "Ajuda";
		$menuBar["fl_negrito"][] = "N";
		$menuBar["id_menubar"][] = "AJUDA";
		$menuBar["fl_submenu"][] = "S";
		$menuBar["ds_link"][] = false;

		//acrescentar o botão do SAIR
		$menuBar["ds_menubar"][] = "Sair";
		$menuBar["ds_link"][] = "logout.php";
		$menuBar["fl_negrito"][] = "S";
		$menuBar["id_menubar"][] = "SAIR";

		//assimilar os valores aos devidos TPLs
		$this->smarty->assign("ds_menubar",$menuBar["ds_menubar"]);
		$this->smarty->assign("ds_link",$menuBar["ds_link"]);
		$this->smarty->assign("fl_negrito",$menuBar["fl_negrito"]);
		$this->smarty->assign("id_menubar",$menuBar["id_menubar"]);
		$this->smarty->assign("root_path","");
		$this->smarty->config_load("urbanauta.conf","smarty_vars");
		$this->menuBar = trim($this->smarty->fetch("adm/gMenu/menuBar.tpl"));

		if (isset($menuList)) {
			$this->smarty->assign("id_menubarPai",$menuList["id_menubarPai"]);
			$this->smarty->assign("id_menubar",$menuList["id_menubar"]);
			$this->smarty->assign("ds_menulist",$menuList["ds_menulist"]);
			$this->smarty->assign("fl_sep_antes",$menuList["fl_sep_antes"]);
			$this->smarty->assign("fl_submenu",$menuList["fl_submenu"]);
			$this->smarty->assign("fl_negrito",$menuList["fl_negrito"]);
			$this->smarty->assign("id_menulist",$menuList["id_menulist"]);
			$this->smarty->assign("ds_link",$menuList["ds_link"]);
			$this->smarty->assign("fl_sep_depois",$menuList["fl_sep_depois"]);
			$this->menuList = trim($this->smarty->fetch("adm/gMenu/menuItem.tpl"));
		}

		if (isset($subMenu)) {
			$this->smarty->assign("id_menulistPai",$subMenu["id_menulistPai"]);
			$this->smarty->assign("id_menulist",$subMenu["id_menulist"]);
			$this->smarty->assign("nr_ordem",$subMenu["nr_ordem"]);
			$this->smarty->assign("ds_submenulist",$subMenu["ds_submenulist"]);
			$this->smarty->assign("fl_sep_antes",$subMenu["fl_sep_antes"]);
			$this->smarty->assign("fl_negrito",$subMenu["fl_negrito"]);
			$this->smarty->assign("ds_link",$subMenu["ds_link"]);
			$this->smarty->assign("fl_sep_depois",$subMenu["fl_sep_depois"]);
			$this->subMenu = trim($this->smarty->fetch("adm/gMenu/menuSubItem.tpl"));
		}

		$retorno = $this->menuBar;
		$retorno .= (isset($this->menuList)) ? "\n".$this->menuList : "\n";
		$retorno .= (isset($this->subMenu)) ? "\n".$this->subMenu : "\n";

		return $retorno;

	}


	/**
	 * Gera o menu para determinado usuário, cfme tabela de visibilidade no banco de dados.
	 *
	 * @access public
	 * @param int $usuario Código de usuário que acessa o sistema.
	 * @return tpl $menu
	 */
	function geraMenuPortal($cdUsu) {
		$sql = "SELECT NM_CON, CD_CON FROM TAB_CON WHERE CD_CON = '$cdUsu'";
		$qyUsu = $this->db->selectQuery($sql);
		$usuario = trim($qyUsu->getReg("NM_CON",0));
		$this->smarty->assign("user","$cdUsu - $usuario");
		unset($qyUsu);

		$sql = "SELECT * FROM TAB_MENUS WHERE CD_GRUPO='2' ORDER BY FL_NIVEL, NR_ORDEM";
		$qyMenu = $this->db->selectQuery($sql);
		$linhas = $qyMenu->getNumLinhas();
		for ($i=0;$i < $linhas;$i++) {
			$nivel = $qyMenu->getReg("FL_NIVEL",$i);
			if ($nivel==0) {
				$menuBar["ds_menubar"][] = $qyMenu->getReg("DS_MENU",$i);
				$menuBar["ds_link"][] 	 = ($qyMenu->getReg("DS_LINK",$i)) ? $qyMenu->getReg("DS_LINK",$i) : false;
				$menuBar["fl_negrito"][] = $qyMenu->getReg("FL_NEGRITO",$i);
				$menuBar["id_menubar"][] = $qyMenu->getReg("CD_MENU",$i);
				$menuBar["fl_submenu"][]	 = "N";
			} else if ($nivel==1) {
				$menuList["ds_menulist"][]	 = $qyMenu->getReg("DS_MENU",$i);
				$menuList["fl_sep_antes"][]	 = $qyMenu->getReg("FL_SEPARADOR_ANTES",$i);
				$menuList["fl_submenu"][]	 = "N";
				$menuList["fl_negrito"][]	 = $qyMenu->getReg("FL_NEGRITO",$i);
				$menuList["id_menulist"][]	 = $qyMenu->getReg("CD_MENU",$i);
				$menuList["ds_link"][]		 = ($qyMenu->getReg("DS_LINK",$i)) ? $qyMenu->getReg("DS_LINK",$i) : false;
				$menuList["fl_sep_depois"][] = $qyMenu->getReg("FL_SEPARADOR_DEPOIS",$i);
				$menuList["id_menubar"][] 	 = $qyMenu->getReg("CD_MENU_PAI",$i);
				for ($j=0;$j < count($menuBar["id_menubar"]);$j++) {
					if ($qyMenu->getReg("CD_MENU_PAI",$i) == $menuBar["id_menubar"][$j] && $menuBar["fl_submenu"][$j]=="N") {
						$menuBar["fl_submenu"][$j] = "S";
						$menuList["id_menubarPai"][] = $menuBar["id_menubar"][$j];
					}
				}
			} else {
				$subMenu["nr_ordem"][] 		 = $qyMenu->getReg("NR_ORDEM",$i);
				$subMenu["ds_submenulist"][] = $qyMenu->getReg("DS_MENU",$i);
				$subMenu["fl_sep_antes"][]	 = $qyMenu->getReg("FL_SEPARADOR_ANTES",$i);
				$subMenu["ds_link"][]		 = ($qyMenu->getReg("DS_LINK",$i)) ? $qyMenu->getReg("DS_LINK",$i) : false;
				$subMenu["fl_negrito"][]	 = $qyMenu->getReg("FL_NEGRITO",$i);
				$subMenu["fl_sep_depois"][]	 = $qyMenu->getReg("FL_SEPARADOR_DEPOIS",$i);
				$subMenu["id_menulist"][] 	 = $qyMenu->getReg("CD_MENU_PAI",$i);
				for ($j=0;$j < count($menuList["id_menulist"]);$j++) {
					if ($qyMenu->getReg("CD_MENU_PAI",$i) == $menuList["id_menulist"][$j] && $menuList["fl_submenu"][$j]=="N") {
						$menuList["fl_submenu"][$j] = "S";
						$subMenu["id_menulistPai"][] = $menuList["id_menulist"][$j];
					}
				}
			}//fim ELSE- nivel != 0 e != 1
		}//FIM DA busca no menu
		unset($qyMenu);
		//acrescentar o botão do SAIR
		$menuBar["ds_menubar"][] = "Sair";
		$menuBar["ds_link"][] = "logout.php";
		$menuBar["fl_negrito"][] = "S";
		$menuBar["id_menubar"][] = "SAIR";

		//assimilar os valores aos devidos TPLs
		$this->smarty->assign("ds_menubar",$menuBar["ds_menubar"]);
		$this->smarty->assign("ds_link",$menuBar["ds_link"]);
		$this->smarty->assign("fl_negrito",$menuBar["fl_negrito"]);
		$this->smarty->assign("id_menubar",$menuBar["id_menubar"]);
		$this->smarty->assign("root_path","../");
		$this->smarty->config_load("portal.conf","smarty_vars");
		$this->menuBar = trim($this->smarty->fetch("adm/gMenu/menuBar.tpl"));

		if (isset($menuList)) {
			$this->smarty->assign("id_menubarPai",$menuList["id_menubarPai"]);
			$this->smarty->assign("id_menubar",$menuList["id_menubar"]);
			$this->smarty->assign("ds_menulist",$menuList["ds_menulist"]);
			$this->smarty->assign("fl_sep_antes",$menuList["fl_sep_antes"]);
			$this->smarty->assign("fl_submenu",$menuList["fl_submenu"]);
			$this->smarty->assign("fl_negrito",$menuList["fl_negrito"]);
			$this->smarty->assign("id_menulist",$menuList["id_menulist"]);
			$this->smarty->assign("ds_link",$menuList["ds_link"]);
			$this->smarty->assign("fl_sep_depois",$menuList["fl_sep_depois"]);
			$this->menuList = trim($this->smarty->fetch("adm/gMenu/menuItem.tpl"));
		}

		if (isset($subMenu)) {
			$this->smarty->assign("id_menulistPai",$subMenu["id_menulistPai"]);
			$this->smarty->assign("id_menulist",$subMenu["id_menulist"]);
			$this->smarty->assign("nr_ordem",$subMenu["nr_ordem"]);
			$this->smarty->assign("ds_submenulist",$subMenu["ds_submenulist"]);
			$this->smarty->assign("fl_sep_antes",$subMenu["fl_sep_antes"]);
			$this->smarty->assign("fl_negrito",$subMenu["fl_negrito"]);
			$this->smarty->assign("ds_link",$subMenu["ds_link"]);
			$this->smarty->assign("fl_sep_depois",$subMenu["fl_sep_depois"]);
			$this->subMenu = trim($this->smarty->fetch("adm/gMenu/menuSubItem.tpl"));
		}

		$retorno = $this->menuBar;
		$retorno .= (isset($this->menuList)) ? "\n".$this->menuList : "\n";
		$retorno .= (isset($this->subMenu)) ? "\n".$this->subMenu : "\n";

		return $retorno;

	}


	/**
	 * Gera o menu para determinar as permissões.
	 *
	 * @access public
	 * @param string $cdUsu Código de usuário logado.
	 * @return tpl $menu
	 */
	function listaMenu(&$user=NULL) {
		$sql = "SELECT * FROM TAB_MENUS WHERE CD_GRUPO='1' ORDER BY FL_NIVEL, NR_ORDEM";
		$qyMenu = $this->db->selectQuery($sql);
		$linhas = $qyMenu->getNumLinhas();
		for ($i=0;$i < $linhas;$i++) {
			$nivel = $qyMenu->getReg("FL_NIVEL",$i);
			if ($nivel==0) {
				$menuBar["ds"][] = $qyMenu->getReg("DS_MENU",$i);
				$menuBar["cd"][] = $qyMenu->getReg("CD_MENU",$i);
				$menuBar["sm"][] = "N";
			} else if ($nivel==1) {
				for ($j=0;$j < count($menuBar["cd"]);$j++) {
					if ($qyMenu->getReg("CD_MENU_PAI",$i) == $menuBar["cd"][$j]) {
						$menuBar["fl_subMenu"][$j] = "S";
						$menuList["ds"][$j][] = $qyMenu->getReg("DS_MENU",$i);
						$menuList["cd"][$j][] = $qyMenu->getReg("CD_MENU",$i);
						$menuList["sm"][$j][] = "N";
						$menuBar["sm"][$j] = "S";
					}
				}
			} else {
				for ($k=0;$k < count($menuBar["cd"]);$k++) if ($menuBar["sm"][$k]=="S") {
					for ($j=0;$j < count($menuList["cd"][$k]);$j++) if ($qyMenu->getReg("CD_MENU_PAI",$i) == $menuList["cd"][$k][$j]) {
						$subMenu["ds"][$k][$j][] = $qyMenu->getReg("DS_MENU",$i);
						$subMenu["cd"][$k][$j][] = $qyMenu->getReg("CD_MENU",$i);
						$menuList["sm"][$k][$j] = "S";
					}
				}
			}//fim ELSE- nivel != 0 e != 1
		}//FIM DA busca no menu
		unset($qyMenu);
		//assimilar os valores aos devidos TPLs
		$this->smarty->assign("menuBar",$menuBar);
		$this->smarty->assign("menuList",$menuList);
		$this->smarty->assign("subMenu",$subMenu);
		if ($user != NULL) $this->smarty->assign("cdUsu",$user->listar());
		$this->menu = trim($this->smarty->fetch("telas/USU_ACESSOS.tpl"));

		return $this->menu;

	}


} /* end of class Menu */

?>
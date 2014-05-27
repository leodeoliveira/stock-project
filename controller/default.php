<?php
if (MANUT) {
	$setup->addCSS("skin/principal.css","text/css","screen","StyleSheet","Titi");
	$setup->pagina(true,$setup->smarty->fetch("under_construction.tpl"));
} else {
	ob_start();
	$setup->smarty->assign("msg","Boa noite Kleger!!!");
	$setup->addCSS("skin/bootstrap.css","text/css","screen","StyleSheet","Titi");
	$setup->addJS("lib/scripts/wufoo.js");

	$setup->smarty->assign("include_menu", true);

	if (file_exists("controller/" . $_REQUEST["arquivo"] . ".php")) {
		include("controller/" . $_REQUEST["arquivo"] . ".php");
	} else {
		$setup->pagina(true,ob_get_clean());
	}
}
?>
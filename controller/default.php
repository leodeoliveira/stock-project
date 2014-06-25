<?php
if (MANUT) {
	$setup->addCSS("skin/principal.css","text/css","screen","StyleSheet","Titi");
	$setup->pagina(true,$setup->smarty->fetch("under_construction.tpl"));
} else {
	ob_start();
	$setup->smarty->assign("msg","Boa noite Kleger!!!");
	$setup->addCSS("skin/bootstrap.css","text/css","screen","StyleSheet","Titi");
	$setup->addCSS("skin/bootstrap.min.css","text/css","screen","StyleSheet","Titi");
	$setup->addCSS("skin/css/site-ifc.css","text/css","screen","StyleSheet","Titi");

	$setup->addJS("jquery-2.1.1.min.js");
	$setup->addJS("bootstrap.min.js");
	$setup->addJS("typeahead.jquery.min.js");
	$setup->addJS("bloodhound.min.js");

	$setup->smarty->assign("include_menu", true);

	$arquivo = $_REQUEST["arquivo"];

	if ($_REQUEST['ext'] == "json") {
		$fakeQuery = explode("data/", $_REQUEST["pastas"]);
		$realQuery = explode("/", $fakeQuery[1]);
		$arquivo = $realQuery[0];
	}

	if (file_exists("controller/" . $arquivo . ".php")) {
		if (file_exists("controller/js/" . $arquivo . ".js")) $setup->addJS($arquivo. ".jsh");
		include("controller/" . $arquivo . ".php");
	} else {
		$setup->pagina(true,ob_get_clean());
	}
}
?>
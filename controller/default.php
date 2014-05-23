<?php
if (MANUT) {
	$setup->addCSS("skin/principal.css","text/css","screen","StyleSheet","Titi");
	$setup->pagina(true,$setup->smarty->fetch("under_construction.tpl"));
} else {
	ob_start();
	var_dump($_REQUEST);

	echo '<p><img src="img/boxRight.png"></p>';

	$setup->smarty->assign("msg","Boa noite Kleger!!!");
	$setup->addCSS("skin/bootstrap.css","text/css","screen","StyleSheet","Titi")
;	$setup->addJS("lib/scripts/wufoo.js");

	switch ($_REQUEST["arquivo"]) {
		case "estabelecimento":
			ob_get_clean();
			$setup->pagina(true,$setup->smarty->fetch("cad_estabelecimento.tpl"));
			break;
		case "usuario":
			ob_get_clean();
			$setup->pagina(true,$setup->smarty->fetch("cad_usuario.tpl"));
			break;
		default:
			$setup->pagina(true,ob_get_clean());
			break;
	}
}
?>
<?php
header("Content-type: text/html; charset=utf8");

/* $testa = $setup->auth->chk_sessao();
 if ($testa == false) {
header("Location: ../../logout.php");
} */
$idTabela = $_GET['cdTela'];
if (isset($_SESSION[$idTabela]['grid'])) {
	echo $setup->util->getTabela($idTabela,$_GET['pagina'],"GRID");
} else {
	if (isset($_GET["campoOrd"])) {
		$arrOrd = explode(",",$_GET["campoOrd"]);
		$ord = "";
		foreach ($arrOrd as $k => $campoOrd) {
			if ($k > 0) $ord .= ", ";
			$ord .= $campoOrd . " " . $_GET["ordem"];
		}
	} else $ord = null;
	echo $setup->conn->getTabela("GRID",NULL,$idTabela,NULL,NULL,$_GET['pagina'],eval($_GET['objeto']),$ord);
}

unset($setup);
?>
<?php
header("Content-type: text/html; charset=utf-8");

require("../class.Setup.php");
$setup = new Setup($_GET["arq_grid"]);

$testa = $setup->auth->chk_sessao();
if ($testa == false) {
	header("Location: ../../logout.php");
}
$idTabela = $_GET['cdTela'];
if (isset($_GET["mysql"])) {
	require_once("class.MyDataBase.php");
	$dbmy = new MyDataBase(DS_MY_HOST,DS_MY_DATABASE,DS_MY_USER,DS_MY_PASS,&$setup->smarty);
	echo $dbmy->getTabela("GRID",NULL,$idTabela,NULL,NULL,$_GET['pagina']);
} else {
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
}

unset($setup);
?>

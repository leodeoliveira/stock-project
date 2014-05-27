<?php
ob_start();
switch ($_REQUEST["arquivo"]) {
	case "user":
		$conteudo["name"] = $_REQUEST["name"];
		$conteudo["email"] = $_REQUEST["email"];
		$conteudo["password"] = $_REQUEST["password"];
		$setup->conn->insertQuery("users", $conteudo);
		echo "sucesso";
		break;
	default:
		break;
}
?>
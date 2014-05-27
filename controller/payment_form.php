<?php
ob_start();
$message = "";
switch ($_REQUEST["ext"]) {
	case "put":
		try	{
			$conteudo["name"] = $_REQUEST["name"];
			$conteudo["email"] = $_REQUEST["email"];
			$conteudo["password"] = $_REQUEST["password"];
			$setup->conn->insertQuery("users", $conteudo);
			$message = "sucesso";
		}
		catch (Exception $e) {
			$message = $e->getMessage();
		}
	default:
		ob_get_clean();
		$setup->smarty->assign("message", $message);
		$setup->pagina(true,$setup->smarty->fetch("payment_form.tpl"));
		break;
}
?>
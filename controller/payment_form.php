<?php
ob_start();
$message = "";
switch ($_REQUEST["ext"]) {
	case "put":
		try	{
			$conteudo["name"] = $_REQUEST["name"];
			$conteudo["description"] = $_REQUEST["description"];
			$setup->conn->insertQuery("payment_methods", $conteudo);
			$message = "sucesso";
		}
		catch (Exception $e) {
			$message = $e->getMessage();
		}
	default:
		ob_get_clean();
		$setup->smarty->assign("message", $message);
		$estrutura[0] = array("C&oacute;digo", "Descri&ccedil;&atilde;o");
		$estrutura[1] = array("%[id_payment_method]%", "%[description]%");
		$tabela = $setup->conn->getTabela("SELECT id_payment_method, description FROM payment_methods", $estrutura, "users");
		$setup->smarty->assign("tabela", $tabela);
		$setup->pagina(true,$setup->smarty->fetch("payment_form.tpl"));
		break;
}
?>
<?php
ob_start();
$message = "";
switch ($_REQUEST["ext"]) {
	case "put":
		try	{
			$conteudo["description"] = $_REQUEST["description"];
			$setup->conn->insertQuery("payment_methods", $conteudo);
			$message = $conteudo["description"];
		}
		catch (Exception $e) {
			$message = $e->getMessage();
		}
	default:
		ob_get_clean();
		$setup->smarty->assign("message", $message);
		$estrutura[0] = array("C&oacute;digo", "Descri&ccedil;&atilde;o");
		$estrutura[1] = array("%[id_payment_condition]%", "%[description]%");
		$tabela = $setup->conn->getTabela("SELECT id_payment_condition, description FROM payment_conditions", $estrutura, "conditions");
		$setup->smarty->assign("tabela", $tabela);
		$setup->pagina(true,$setup->smarty->fetch("payment_condition.tpl"));
		break;
}
?>

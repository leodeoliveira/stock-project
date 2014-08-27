<?php
ob_start();
$message = "";
$id = 0;
switch ($_REQUEST["ext"]) {
	case "put":
		try	{
			$conteudo["id_sales_order"] = $_REQUEST["id_sales_order"];
			$conteudo["id_customer"] = $_REQUEST["id_customer"];
			$conteudo["id_product"] = $_REQUEST["id_product"];
			$conteudo["id_payment_method"] = $_REQUEST["payment_method"];
			$conteudo["id_payment_condition"] = $_REQUEST["payment_condition"];
			$conteudo["date"] = $_REQUEST["date"];
			$conteudo["delivery_date"] = $_REQUEST["delivery_date"];
			$setup->conn->insertQuery("sales_order", $conteudo);
			$message = "sucesso";
		}
		catch (Exception $e) {
			$message = $e->getMessage();
		}
	default:
		ob_get_clean();
		$setup->smarty->assign("message", $message);
		//$estrutura[0] = array("ID", "Nome", "E-mail");
		//$estrutura[1] = array("%[id_user]%", "%[name]%", "%[email]%");
		//$tabela = $setup->conn->getTabela("SELECT id_user, name, email FROM sale_orders", $estrutura, "users");
		//$setup->smarty->assign("tabela", $tabela);
		$setup->pagina(true,$setup->smarty->fetch("sales_order.tpl"));
		break;
}
?>

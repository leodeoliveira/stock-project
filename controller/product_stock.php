<?php
ob_start();
$message = "";
$id = 0;
switch ($_REQUEST["ext"]) {
	case "put":
		try	{
			$conteudo["id_product"] = $_REQUEST["id_product"];
			$conteudo["quantity_movement"] = $_REQUEST["quantity_movement"];
			$conteudo["id_stock"] = $_REQUEST["id_stock"];
			$setup->conn->insertQuery("stock", $conteudo);
			$message = "sucesso";
		}
		catch (Exception $e) {
			$message = $e->getMessage();
		}
	default:
		ob_get_clean();
		$setup->smarty->assign("id", $id);
		$setup->smarty->assign("message", $message);
		$estrutura[0] = array("ID", "Produto", "Quantidade");
		$estrutura[1] = array("%[id_product]%", "%[id_product]%", "%[quantity_movement]%");
		$tabela = $setup->conn->getTabela("SELECT id_stock, id_product, quantity_movement FROM stock", $estrutura, "users");
		$setup->smarty->assign("tabela", $tabela);
		$setup->pagina(true,$setup->smarty->fetch("product_stock.tpl"));
		break;
}
?>

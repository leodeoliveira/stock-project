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
		$estrutura[0] = array("ID", "Nome", "E-mail");
		$estrutura[1] = array("%[id_user]%", "%[name]%", "%[email]%");
		$tabela = $setup->conn->getTabela("SELECT id_user, name, email FROM users", $estrutura, "users");
		$setup->smarty->assign("tabela", $tabela);
		$setup->pagina(true,$setup->smarty->fetch("cad_usuario.tpl"));
		break;
}
?>
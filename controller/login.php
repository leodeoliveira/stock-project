<?php
ob_start();
$message = "";
switch ($_REQUEST["ext"]) {
	case "put":
		try	{
			$email = "'" . escapeshellcmd(strtoupper($_REQUEST["email"])) . "'";
			$password = "'" . escapeshellcmd(strtoupper($_REQUEST["password"])) . "'";
			$qy = $setup->conn->selectQuery("SELECT id_user FROM users WHERE email = ".$email." AND password = " . $password);
			if ($qy->getNumLinhas() != 1)
				$message = "Acesso negado, confira seu e-mail e senha";
			else {
				$_SESSION["online"] = true;
				$setup->pagina(true,$setup->smarty->fetch("index.tpl"));
				die();
			}
		}
		catch (Exception $e) {
			$message = $e->getMessage();
		}
	default:
		/* if(isset($_SESSION)) {
			$setup->pagina(true,$setup->smarty->fetch("index.tpl"));
			die();
		} */
		$setup->smarty->assign("include_menu", false);
		ob_get_clean();
		$setup->smarty->assign("message", $message);
		$setup->pagina(true,$setup->smarty->fetch("login.tpl"));
		break;
}
?>
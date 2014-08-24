<?php
/**
 * Classe que faz a autentica��o do usu�rio
 *
 * @author Anderson Jord�o Marques <ajm@urbanauta.com.br>
 * @version 1.0
 * @since 1.0 - 28/07/2006
 */

class Auth {

	private $conn;

	/**
	 * Fun��o construtora da classe, recebe o BD, e objeto Crypto.
	 *
	 * @access public
	 * @param resource $conn Vari�vel que cont�m a conex�o com o banco de dados.
	 * @return Auth instancia de Auth.
	 */
	function Auth($conn, $crypto) {
		$this->conn = $conn;
		$this->crypto = $crypto;
	}

	/**
	 * Fun��o que recebe c�digo usu�rio e senha, e autentica.
	 *
	 * @access public
	 * @param string $cdUsu
	 * @param string $senha
	 * @return string Mensagem de erro.
	 */
	function login ($cdUsu, $senha) {

		$senha = $this->crypto->encrypt($senha);
		$sql = "SELECT CD_USU,FL_ATIVO " .
				"FROM TAB_USU " .
				"WHERE " .
				"CD_USU_SISTEMA='$cdUsu' " .
				"AND CD_SENHA_MASTER='$senha' " .
				"AND ( ((SELECT FL_BASE_MASTER FROM TAB_PAR) <> 'N') " .
				" OR FL_PROMOTOR <> 'N' ); ";
		$login = $this->conn->selectQuery($sql);

		if ($login->getNumLinhas() == 1) {
			if ($login->getReg("FL_ATIVO") == 'S') {
				$_SESSION['cdUsuario'] = $login->getReg("CD_USU");
				$_SESSION['dsUsuario'] = $cdUsu;
			} else return "Usu�rio Desativado";
		} else return "Dados inv�lidos!";
		$_SESSION['SID'] = session_id();
		return "";
	}

	/**
	 * Fun��o que recebe c�digo usu�rio e senha, e autentica.
	 *
	 * @access public
	 * @param string $cdUsu
	 * @param string $senha
	 * @return string Mensagem de erro.
	 */
	function loginPortal ($cdUsu, $senha) {
		//$senha = $this->crypto->encrypt($senha);
		$sql = "SELECT CD_REVENDEDOR,FL_ATIVO FROM TAB_REVENDEDOR " .
				"WHERE CD_LOGIN='$cdUsu' " .
				"AND CD_SENHA='$senha'";
		//die($sql);
		$login = $this->conn->selectQuery($sql);
		if ($login->getNumLinhas() == 1) {
			if ($login->getReg("FL_ATIVO") == 'S') {
				$_SESSION['cdUsuario'] = $login->getReg("CD_REVENDEDOR");
				$_SESSION['dsUsuario'] = $cdUsu;
			} else return "Usu�rio Desativado";
		} else return "Dados inv�lidos!";
		$_SESSION['SID'] = session_id();
		return "";
	}

	/**
	 * VERIFICA SESS�O - se logado=cdUsu | se nao false
	 *
	 * @access public
	 * @return string Sess�o n�mero ID || boolean.
	 */
	function chk_sessao() {
		if(isset($_SESSION['cdUsuario'])) return $_SESSION['cdUsuario'];
		else return false;
	}

	/**
	 * Fun��o para efetuar o logout.
	 *
	 * @access public
	 */
	function logout() {
		unset($this->conn);
		session_destroy();
		return true;
	}

}
?>
<?php
/**
 * Classe que faz a autenticação do usuário
 *
 * @author Anderson Jordão Marques <ajm@urbanauta.com.br>
 * @version 1.0
 * @since 1.0 - 28/07/2006
 */

class Auth {

	private $conn;

	/**
	 * Função construtora da classe, recebe o BD, e objeto Crypto.
	 *
	 * @access public
	 * @param resource $conn Variável que contém a conexão com o banco de dados.
	 * @return Auth instancia de Auth.
	 */
	function Auth(&$conn,&$crypto) {
		$this->conn = &$conn;
		$this->crypto = &$crypto;
	}

	/**
	 * Função que recebe código usuário e senha, e autentica.
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
			} else return "Usuário Desativado";
		} else return "Dados inválidos!";
		$_SESSION['SID'] = session_id();
		return "";
	}

	/**
	 * Função que recebe código usuário e senha, e autentica.
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
			} else return "Usuário Desativado";
		} else return "Dados inválidos!";
		$_SESSION['SID'] = session_id();
		return "";
	}

	/**
	 * VERIFICA SESSÃO - se logado=cdUsu | se nao false
	 *
	 * @access public
	 * @return string Sessão número ID || boolean.
	 */
	function chk_sessao() {
		if(isset($_SESSION['cdUsuario'])) return $_SESSION['cdUsuario'];
		else return false;
	}

	/**
	 * Função para efetuar o logout.
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
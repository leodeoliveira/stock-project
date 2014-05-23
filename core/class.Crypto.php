<?php
/**
 * Classe que cuida da criptografia de senhas e DIVs.
 *
 * @access public
 * @author Anderson Jordão Marques <ajm@urbanauta.com.br>
 * @version 1.0
 * @since 1.0 - 28/07/2006
 */
class Crypto {

	/**
	 * Função de criptografia.
	 *
	 * @access public
	 * @param string $plain_text conteúdo plano.
	 * @return string conteúdo criptografado.
	 */
	function encrypt($plain_text) {
		return strrev(base64_encode($plain_text));
	}

	/**
	 * Função de descriptografia.
	 *
	 * @access public
	 * @param string $enc_text conteúdo criptografado.
	 * @return string conteúdo plano.
	 */
	function decrypt($enc_text) {
		return base64_decode(strrev($enc_text));
	}

}
?>
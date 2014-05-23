<?php
/**
 * Classe que cuida da criptografia de senhas e DIVs.
 *
 * @access public
 * @author Anderson Jord�o Marques <ajm@urbanauta.com.br>
 * @version 1.0
 * @since 1.0 - 28/07/2006
 */
class Crypto {

	/**
	 * Fun��o de criptografia.
	 *
	 * @access public
	 * @param string $plain_text conte�do plano.
	 * @return string conte�do criptografado.
	 */
	function encrypt($plain_text) {
		return strrev(base64_encode($plain_text));
	}

	/**
	 * Fun��o de descriptografia.
	 *
	 * @access public
	 * @param string $enc_text conte�do criptografado.
	 * @return string conte�do plano.
	 */
	function decrypt($enc_text) {
		return base64_decode(strrev($enc_text));
	}

}
?>
<?php

error_reporting(E_ALL);

/**
 * untitledModel - class.Query.php
 *
 * $Id$
 *
 * This file is part of untitledModel.
 *
 * Automatic generated with ArgoUML 0.22.beta2 on 19.07.2006, 13:30:09
 *
 * @author Anderson Jordão Marques <ajm@urbanauta.com.br>
 */

if (0 > version_compare(PHP_VERSION, '5')) {
	die('This file was generated for PHP 5');
}


/**
 * include FbDataBase
 *
 * @author Anderson Jordão Marques
 * @version 2.0
 */
require_once('class.FbDataBase.php');
require_once('class.Util.php');

/**
 * Short description of class Query
 *
 * @access public
 * @author Anderson Jordão Marques <ajm@urbanauta.com.br>
 */
class Query {
	// --- ATTRIBUTES ---

	private $numLinhas = 0;
	private $numColunas = 0;
	private $colInfo = NULL;
	private $registro = NULL;
	private $grid = NULL;
	private $pagg = NULL;
	private $util;

	// --- OPERATIONS ---

	public function getNumLinhas() {
		return $this->numLinhas;
	}
	public function getNumColunas() {
		return (int) $this->numColunas;
	}
	public function getColInfo($nome) {
		return $this->colInfo[$nome];
	}
	public function getReg($id,$linha=0) {
		return trim($this->registro[$id][$linha]);
		//return $this->registro[$id][$linha];
	}
	public function getGrid() {
		return $this->grid;
	}

	public function getNext() {
		return $this->pagg;
	}

	/**
	 * Recebe como parâmetro uma consulta, e salva ela com um identificador, e
	 * os registro na classe registro
	 *
	 * @access public
	 * @author Anderson Jordão Marques <ajm@urbanauta.com.br
	 * @param resource $query consulta firebird/interbase.
	 * @param int $pagg nº de registros por página, 0 para sem paginação.
	 * @param int $page nº da página atual.
	 * @return void
	 * @since 19/07/2006
	 * @version 1.0
	 */
	public function Query($query,$pagg,$page) {
		$this->numColunas = (int) ibase_num_fields($query);
		$this->pagg["registros"] = $pagg;
		$this->pagg["pagina"] = $page;
		$this->util = new Util();

		if ($this->numColunas > 0) {

			# Faz um Loop entre as colunas e verifica o nome e tipo das colunas
			# Atualizando as propriedades correspondentes
			for ($i = 0; $i < $this->numColunas; $i++) {
				$colInfo = ibase_field_info($query, $i);
				$this->colInfo[$i]['tp'] = $colInfo['type'];
				$this->colInfo[$i]['nm'] = $colInfo['alias'];
				$this->colInfo[$i]['rt'] = $colInfo['relation'];
				$this->colInfo[$colInfo['alias']]['tp'] = $colInfo['type'];
				$this->colInfo[$colInfo['alias']]['tam'] = $colInfo['length'];
				$this->colInfo[$colInfo['alias']]['rt'] = $colInfo['relation'];
				$this->grid[0][$i] = $colInfo['alias'];
			}

			$j = 0;

			while ($linha = ibase_fetch_row($query)) {

				for ($i = 0; $i < $this->numColunas; $i++) {
					# Verifica o Tipo de Dado do Campo atual: Isto se faz necessessário
					# porque os Campos do tipo TIMESTAMP e BLOB
					# devem ter tratamentos distintos dos demais
					switch ($this->colInfo[$i]['tp']) {
						case "TIMESTAMP":
							$this->registro[$this->colInfo[$i]['nm']][$j] = $this->dateToStr($linha[$i]);
							break;
						case "BLOB":
							$this->registro[$this->colInfo[$i]['nm']][$j] = $this->memoToStr($linha[$i]);
							break;
						case "DATE":
							$this->registro[$this->colInfo[$i]['nm']][$j] = $this->dateToDate($linha[$i]);
							break;
						case "DOUBLE":
						case "NUMERIC":
							$this->registro[$this->colInfo[$i]['nm']][$j] = number_format($this->dateToDate($linha[$i]), 2, ',', '.');
							break;
						default:
							//$this->registro[$this->colInfo[$i]['nm']][$j] = $this->util->trocaacento($linha[$i]);
							$this->registro[$this->colInfo[$i]['nm']][$j] = str_replace("'","",htmlspecialchars($linha[$i]));
							break;
					}//fim switch
					$this->grid[$j+1][$this->colInfo[$i]['nm']] = $this->registro[$this->colInfo[$i]['nm']][$j];
				}//fim FOR-colCount
				$j++;
			}//fim WHILE
			$this->numLinhas = $j;
		}
	}

	/**
	 * Função para mostrar campos BLOB
	 *
	 * @return string String de campo BLOB.
	 * @param blob $memo conteúdo a ser convertido.
	 */
	public function memoToStr($memo) {
		if ($memo != null) {
			$info = ibase_blob_info($memo);
			$id = ibase_blob_open($memo);
			return (ibase_blob_get($id, $info[0]));
			ibase_blob_close($id);
		}
		else return "";
	}

	/**
	 * Função para converter timestamp para data no formato dd/mm/aaaa
	 *
	 * @return string Data no formato dd/mm/aaaa.
	 * @param string $dt Data no formato timestamp.
	 */
	public function dateToStr($dt) {
		if ($dt != "") {
			$dt_result = explode(" ",$dt);
			$tm_stamp = strtotime($dt_result[0]);
			return @date("d/m/Y", $tm_stamp);
		}
	}

	/**
	 * Função para converter date aaaa-mm-dd para data no formato dd/mm/aaaa
	 *
	 * @return string Data no formato dd/mm/aaaa.
	 * @param string $dt Data no formato date aaaa-mm-dd.
	 */
	public function dateToDate($dt) {
		if ($dt != "") {
			$dt_result = explode("-",$dt);
			return $dt_result[2] ."/". $dt_result[1] ."/". $dt_result[0];
		}
	}

} /* end of class Query */

?>
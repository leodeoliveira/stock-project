<?php

error_reporting(E_ALL);

/**
 * untitledModel - class.Query.php
 *
 * $Id: class.MyQuery.php,v 1.2 2007/09/13 20:19:30 anderson Exp $
 *
 * This file is part of untitledModel.
 *
 * Automatic generated with ArgoUML 0.22.beta2 on 19.07.2006, 13:30:09
 *
 * @author Anderson Jord�o Marques <ajm@urbanauta.com.br>
 */

if (0 > version_compare(PHP_VERSION, '5')) {
	die('This file was generated for PHP 5');
}


/**
 * include MyDataBase
 *
 * @author Anderson Jord�o Marques
 * @version 2.0
 */
require_once('class.MyDataBase.php');
require_once('class.Util.php');

/**
 * Short description of class Query
 *
 * @access public
 * @author Anderson Jord�o Marques <ajm@urbanauta.com.br>
 */
class MyQuery {
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
	 * Recebe como par�metro uma consulta, e salva ela com um identificador, e
	 * os registro na classe registro
	 *
	 * @access public
	 * @author Anderson Jord�o Marques <ajm@urbanauta.com.br
	 * @param resource $query consulta firebird/interbase.
	 * @param int $pagg n� de registros por p�gina, 0 para sem pagina��o.
	 * @param int $page n� da p�gina atual.
	 * @return void
	 * @since 19/07/2006
	 * @version 2
	 */
	public function MyQuery($query,$pagg,$page) {
		$this->numColunas = (int) mysqli_num_fields($query);
		$this->pagg["registros"] = $pagg;
		$this->pagg["pagina"] = $page;
		$this->util = new Util();

		if ($this->numColunas > 0) {

			# Faz um Loop entre as colunas e verifica o nome e tipo das colunas
			# Atualizando as propriedades correspondentes
			for ($i = 0; $i < $this->numColunas; $i++) {
				//$colInfo = mysqli_ ibase_field_info($query, $i);
				$this->colInfo[$i]['tp'] = mysqli_fetch_field_direct($query, $i)->type; // $colInfo['type'];
				$this->colInfo[$i]['nm'] = mysqli_fetch_field_direct($query, $i)->name;// $colInfo['alias'];
				$this->colInfo[$i]['rt'] = mysqli_fetch_field_direct($query, $i)->table;// $colInfo['relation'];
				$this->colInfo[mysqli_fetch_field_direct($query, $i)->name]['tp'] = mysqli_fetch_field_direct($query, $i)->type; // $colInfo['type'];
				$this->colInfo[mysqli_fetch_field_direct($query, $i)->name]['tam'] = mysqli_fetch_field_direct($query, $i)->length;// $colInfo['alias'];
				$this->colInfo[mysqli_fetch_field_direct($query, $i)->name]['rt'] = mysqli_fetch_field_direct($query, $i)->table;// $colInfo['relation'];
				$this->grid[0][$i] = mysqli_fetch_field_direct($query, $i)->name;//$colInfo['alias'];
			}

			$j = 0;

			while ($linha = mysqli_fetch_row($query)) {

				for ($i = 0; $i < $this->numColunas; $i++) {
					# Verifica o Tipo de Dado do Campo atual: Isto se faz necessess�rio
					# porque os Campos do tipo TIMESTAMP e BLOB
					# devem ter tratamentos distintos dos demais
					switch ($this->colInfo[$i]['tp']) {
						case "timestamp":
							$this->registro[$this->colInfo[$i]['nm']][$j] = $this->dateToStr($linha[$i]);
							break;
						case "blob":
							$this->registro[$this->colInfo[$i]['nm']][$j] = $this->memoToStr($linha[$i]);
							break;
						case "date":
							$this->registro[$this->colInfo[$i]['nm']][$j] = $this->dateToDate($linha[$i]);
							break;
						case "double":
						case "numeric":
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
	 * Fun��o para mostrar campos BLOB
	 *
	 * @return string String de campo BLOB.
	 * @param blob $memo conte�do a ser convertido.
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
	 * Fun��o para converter timestamp para data no formato dd/mm/aaaa
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
	 * Fun��o para converter date aaaa-mm-dd para data no formato dd/mm/aaaa
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

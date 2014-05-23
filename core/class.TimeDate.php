<?php
date_default_timezone_set("America/Sao_Paulo");
/**
 * Classe para tratamento de datas.
 * Não têm-se referência do autor dessa classe
 *
 */
class TimeDate {

	/**
	 * Construtor em branco.
	 *
	 * @return TimeDate
	 */
	function TimeDate() {
		$this->data = NULL;
	}

	/**
	 * Tratamento da data para formatos apenas numéricos
	 * Recebe uma data no formato yyyymmdd, coloca as barras e ordena em dd/mm/yyyy
	 *
	 * @param int $str_data
	 * @return string no formato dd/mm/YYYY
	 */
	function dtod($str_data) {
		$data_ano = substr($str_data,0,4);
		$data_mes = substr($str_data,4,2);
		$data_dia = substr($str_data,6,2);
		return $data_dia."/".$data_mes."/".$data_ano;
	}

	/**
	 * Converte yyyy-mm-dd hh:mm:ss em dd/mm/yyyy hh:mm:ss
	 *
	 * @param string $str_data no formato yyyy-mm-dd hh:mm:ss
	 * @return string dd/mm/yyyy hh:mm:ss
	 */
	function _stodt($str_data) {
		$aStr = explode($str_data, " ");
		$d = $aStr[0];
		$t = $aStr[1];
		$aD = explode($d,"-");
		$datetime = $aD[2] . "/" . $aD[1] . "/" . $aD[0] . " " . $t;
		return $datetime;
	}

	/**
	 * Converte dd/mm/yyyy hh:mm:ss em yyyy-mm-dd hh:mm:ss
	 *
	 * @param string $datetime no formato dd/mm/yyyy hh:mm:ss
	 * @return string no formato yyyy-mm-dd hh:mm:ss
	 */
	function _dttos($datetime) {
		$aDT = explode($datetime, " ");
		$s = $aDT[0];
		$t = $aDT[1];
		$aS = explode($s, "/");
		$str = $aS[2] . "-" . $aS[1] . "-" . $aS[0] . " " . $t;
		return $str;
	}

	/**
	 * Converte yyyy-mm-dd em dd/mm/yyyy
	 *
	 * @param string $texto no formato yyyy-mm-dd
	 * @return string no formato dd/mm/yyyy
	 */
	function stod($texto) {
		if (strlen($texto)>10) {
			return _stodt($texto);
		} else {
			$str_data = explode("-",$texto);
			return $str_data[2] . "/" . $str_data[1] . "/" . $str_data[0];
		}
	}

	/**
	 * Converte dd/mm/yyyy em yyyy-mm-dd
	 *
	 * @param string $str_data no formato dd/mm/yyyy
	 * @return string no formato yyyy-mm-dd
	 */
	function dtos($str_data) {
		if (strlen($str_data)>10) {
			return _dttos($str_data);
		} else {
			$texto = explode("/",$str_data);
			return $texto[2] . "-" . $texto[1] . "-" . $texto[0];
		}
	}

	/**
	 * Função para formatar data
	 *
	 * @param string $str_data date no formato aceito por strtotime (php core).
	 * @param string $formato = "d/m/Y" formato de retorno da data.
	 * @return string com $str_data formatado conforme $formato.
	 */
	function fdata($str_data,$formato="d/m/Y"){
		$months = array("january"=>"Janeiro","february"=>"Fevereiro","march"=>"Março","april"=>"Abril","may"=>"Maio","june"=>"Junho","july"=>"Julho","august"=>"Agosto","september"=>"Setembro","october"=>"Outubro","november"=>"Novembro","december"=>"Dezembro");
		$weeks = array("sunday"=>"Domingo","monday"=>"Segunda","tuesday"=>"Terça","wednesday"=>"Quarta","thursday"=>"Quinta","friday"=>"Sexta","saturday"=>"Sábado");
		$months3 = array("jan"=>"jan","feb"=>"fev","mar"=>"mar","apr"=>"abr","may"=>"mai","jun"=>"jun","jul"=>"jul","aug"=>"ago","sep"=>"set","oct"=>"out","nov"=>"nov","dec"=>"dez");
		$weeks3 = array("sun"=>"dom","mon"=>"seg","tue"=>"ter","wed"=>"qua","thu"=>"qui","fri"=>"sex","sat"=>"sab");

		$str_data = strtolower(date($formato,strtotime($str_data)));
		$str_data = strtr($str_data,$months);
		$str_data = strtr($str_data,$weeks);
		$str_data = strtr($str_data,$months3);
		$str_data = strtr($str_data, $weeks3);
		return $str_data;
	}

	/**
	 * Retorna a descrição do dia da semana de determinada data.
	 *
	 * @param int $ds_dia dia da semana de Sábado - Sexta
	 * @param boolean $abrev True para nomes abreviados em três algarismos.
	 * @return string Descrição do dia da semana.
	 */
	function ds_dia($ds_dia,$abrev=false) {
		if ($abrev==1) {
			$dias = array("Saturday" => "Sáb",
						"Sunday" => "Dom",
						"Monday" => "Seg",
						"Tuesday" => "Ter",
						"Wednesday" => "Qua",
						"Thursday" => "Qui",
						"Friday" => "Sex");
		} else {
			$dias = array("Saturday" => "Sábado",
					"Sunday" => "Domingo",
					"Monday" => "Segunda-feira",
					"Tuesday" => "Terça-feira",
					"Wednesday" => "Quarta-feira",
					"Thursday" => "Quinta-feira",
					"Friday" => "Sexta-feira");
		}
		return $dias[$ds_dia];
	}

	/**
	 * Retorna o nome do mês de determinada data.
	 *
	 * @param int $nr_mes Número do mês da 1 a 12.
	 * @return string Nome de mês.
	 */
	function ds_mes($nr_mes) {
		settype($nr_mes,"integer");
		$meses = array(1 => "Janeiro", 2 => "Fevereiro", 3 => "Março", 4 => "Abril", 5 => "Maio", 6 => "Junho", 7 => "Julho", 8 => "Agosto", 9 => "Setembro", 10 => "Outubro", 11 => "Novembro", 12 => "Dezembro");
		return $meses[$nr_mes];
	}


	/**
	 * converte yyyy-mm-dd para timestamp
	 *
	 * @param string $str_date no formato yyyy-mm-dd
	 * @return DateTime data gerada por mktime (php core)
	 */
	function dt2timestamp($str_date) {

		$str_date = str_replace("/", "-", $str_date);
		$str_date = str_replace(".", "-", $str_date);

		//verifica se a data passada está no formato: 2007-01-01 08:00:00
		$pos1 = stripos(" ", $str_date);
		if ($pos1 === true) {
			$tmp = explode(" ",$str_date);
			$dt = explode("-",$tmp[0]);
		} else {
			$dt = explode("-",$str_date);
		}

		list($ano, $mes, $dia) = $dt;
		$tm_stamp = mktime(0,0,0,$mes,$dia,$ano);
		return $tm_stamp;
	}

	/**
	 * recebe duas data no formato dd/mm/YYYY e retorna o número de dias.
	 *
	 * @author Anderson Jordão Marques <ajm at urbanauta com br>
	 * @param string $dt_1 com o formato "dd/mm/YYYY"
	 * @param string $dt_2 com o formato "dd/mm/YYYY"
	 * @return int número de dias
	 */
	function dateDiffDMY($dt_1,$dt_2) {
		$dt_1 = $this->dtos($dt_1);
		$dt_2 = $this->dtos($dt_2);
		return $this->datediff($dt_1,$dt_2);
	}

	/**
	 * subtrai duas datas e retorna número de dias
	 *
	 * @param string $dt_1 com o formato "yyyy-mm-dd"
	 * @param string $dt_2 com o formato "yyyy-mm-dd"
	 * @return int número de dias
	 */
	function datediff($dt_1, $dt_2) {
		$d1 = $this->dt2timestamp($dt_1);
		$d2 = $this->dt2timestamp($dt_2);

		if ($d1 > $d2)  {
			$result = ($d1-$d2)/84600;
		} else {
			$result = ($d2-$d1)/84600;
		}

		return $result;
	}

	/**
	 * Adiciona determinado número de dias a uma data.
	 *
	 * @param string $date no formato yyyy-mm-dd
	 * @param int $days Número de dias
	 * @return string yyyy-mm-dd
	 */
	function addDayIntoDate($date,$days) {
		$thisyear = substr ( $date, 0, 4 );
		$thismonth = substr ( $date, 4, 2 );
		$thisday =  substr ( $date, 6, 2 );
		$nextdate = mktime ( 0, 0, 0, $thismonth, $thisday + $days, $thisyear );
		return strftime("%Y%m%d", $nextdate);
	}

	/**
	 * Subtrai determinado número de dias a uma data.
	 *
	 * @param string $date no formato yyyy-mm-dd
	 * @param int $days Número de dias
	 * @return string yyyy-mm-dd
	 */
	function subDayIntoDate($date,$days) {
		$thisyear = substr ( $date, 0, 4 );
		$thismonth = substr ( $date, 4, 2 );
		$thisday =  substr ( $date, 6, 2 );
		$nextdate = mktime ( 0, 0, 0, $thismonth, $thisday - $days, $thisyear );
		return strftime("%Y%m%d", $nextdate);
	}


	/**
	 * Retorna a data hora do momento da execução
	 * no formato YYYY-mm-dd HH:ii:ss
	 *
	 * @return string no formato YYYY-mm-dd HH:ii:ss
	 */
	function agora() {
		return date("Y-m-d H:i:s");
	}

	/**
	 * Retorna a data do momento da execução
	 * no formato YYYY-mm-dd
	 *
	 * @return string no formato YYYY-mm-dd
	 */
	function dtHoje() {
		return date("Y-m-d");
	}

	/**
	 * Retorna a data do momento da execução
	 * no formato Joinville, dd de mmmm de YYYY
	 *
	 * @return string no formato Joinville, dd de mmmm de YYYY
	 */
	function hoje() {
		$dia = date("d");
		$mes = date("m");
		$ano = date("Y");
		$str_hoje = "Joinville, ".$dia." de ".$this->ds_mes($mes)." de ".$ano;
		return $str_hoje;
	}

	/**
	 * Obtêm a data do início do mês
	 *
	 * @param int $nr_mes Número do mês de 1 - 12
	 * @param int $nr_ano Ano
	 * @return string no formato YYYY-mm-dd
	 */
	function getIniMes($nr_mes,$nr_ano) {
		$inicio_qz = null;
		if (($nr_mes > 0) && ($nr_ano>0)) {
			$dia = 1;
			$mes = $nr_mes;
			$ano = $nr_ano;

			$inicio_mes = $ano."-".$mes."-".$dia;
			return $inicio_mes;
		}
		return $inicio_qz;
	}

	/**
	 * Obtêm a data do fim do mês
	 *
	 * @param int $nr_mes Número do mês de 1 - 12
	 * @param int $nr_ano Ano
	 * @return string no formato YYYY-mm-dd
	 */
	function getFimMes($nr_mes,$nr_ano) {
		$retorno = false;
		if (($nr_mes > 0) && ($nr_ano>0)) {
			$tm_stamp = mktime(0,0,0,$nr_mes,1,$nr_ano);
			$dia = date("t",$tm_stamp);
			$mes = $nr_mes;
			$ano = $nr_ano;

			$inicio_mes = $ano."-".$mes."-".$dia;
			return $inicio_mes;
		}
		return $inicio_qz;
	}

}
?>
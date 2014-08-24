// <![CDATA[
/* -----------------------------------------------------------------------------
 * Funcionalidades da tela Moeda
 * Version 0.1
 * Date: 25/08/2006
 * Atualizado: 23/08/2014 (atualizado par jquery)
 * Author: Leonardo Cidral, [leonardo at softin com br]
 * Author (jquery): Anderson Jordão Marques [anjomar at gmail com]
 * Requires jQuery library (http://jquery.com/)
 * -------------------------------------------------------------------------- */

function Paginacao () {
	this.p_atual = null;
	this.p = null;
	this.objPg = null;
};
Paginacao.prototype = {

	//chama uma nova página se for diferente da atual, se não pisca o nr atual
	pagina: function (p,cdTela,objJS) {
		var retorno = false;

		this.cortinaPaginas('#nrPaginasTop_'+cdTela,false);

		this.p_atual = $("#nr_pagina_atual_old_"+cdTela).val();
		$("#nr_pagina_atual_"+cdTela).addClass("pg_carregando");
		//$("nr_pagina_atual_"+cdTela).value = p;
		if (this.p_atual != p) {
			this.paginaAtualiza(p,cdTela,null,objJS);
			this.pgSeleciona(p,cdTela);
			retorno = true;
		} else {
			this.sinPgAtual(p,cdTela);
		}
		return retorno;
	},

	vaipara: function(p,cdTela,objJS){
		total = parseInt($("#nr_pagina_total_"+cdTela).html());
		if (p > total || p <= 0) {
			alert("Você não pode ir para uma página inexistente.");
			$("#nr_pagina_atual_"+cdTela).focus();
			$("#nr_pagina_atual_"+cdTela).select();
		} else {
			xpaginacao.pagina(p,cdTela,objJS);
		}
	},

	mousedown: function(obj){
		//obj.className="nr_pagina_sel";
	},

	paginaAtualiza: function(p,cdTela,objeto,objJS) {
		if (p == null || p == undefined) {
			p = ($("#nr_pagina_atual_"+cdTela).size() > 0) ? parseInt($("#nr_pagina_atual_"+cdTela).val()) : 1;
		}

		var parametros = null;
		if (xgrid.tela == cdTela) {
			parametros = {
				pagina: p,
				cdTela: cdTela,
				objJS: objJS,
				campoOrd: xgrid.campoOrd,
				ordem: xgrid.ordem,
				objeto: objeto
			};
		} else {
			parametros = {
				pagina: p,
				cdTela: cdTela,
				objJS: objJS,
				objeto: objeto
			};
		}

		$.get($("#path_paginacao").val(),parametros, function(req) {
			xpaginacao.setTabela(req,p,cdTela);
		});
	},

	//recebe resultado do ajax, atualiza nr pagina e o grid.
	setTabela: function(req,p,cdTela) {
		$("#gridtabela_"+cdTela).html(req);
		$("#nr_pagina_atual_"+cdTela).val(p);
		$("#nr_pagina_atual_old_"+cdTela).val(p);
		$("#nr_pagina_atual_"+cdTela).addClass("pg_carregada");

		$("#spanord_"+cdTela+"_"+xgrid.index).html(xgrid.ARROW);
		$("#spanord_"+cdTela+"_"+xgrid.index).attr('sortarrow',xgrid.ordem == "ASC" ? "down" : "up");
	},

	//vai para pg anterior
	primeira: function(cdTela,objJS) {
		this.pagina(1,cdTela,objJS);
	},

	//vai para pg anterior
	anterior: function(cdTela,objJS) {
		p = parseInt($("#nr_pagina_atual_"+cdTela).val());
		if (p > 1) {
			p--;
			atual = parseInt($("#nr_pagina_atual_"+cdTela).val());
			if (atual != p) xpaginacao.pagina(p,cdTela,objJS);
		} else {
			this.sinPgAtual(p,cdTela);
		}
	},

	//move para proxima pagina
	proxima: function(cdTela, objJS) {
		total = parseInt($("#nr_pagina_total_"+cdTela).html());
		p = parseInt($("#nr_pagina_atual_"+cdTela).val())+1;
		if (p <= total) {
			atual = parseInt($("#nr_pagina_atual_"+cdTela).val());
			if (atual != p) this.pagina(p,cdTela,objJS);
		} else {
			this.sinPgAtual(total,cdTela);
		}
	},

	//move para proxima pagina
	ultima: function(cdTela, objJS) {
		total = parseInt($("#nr_pagina_total_"+cdTela).html());
		this.pagina(total,cdTela,objJS);
	},


	//TODO: codigo redundante
	//remove todas as classes do numero das paginas e seta a pg inicial
	pgSeleciona: function(p,cdTela) {
		//Obtém os links do menu
		var menu=$("#nrPaginasTop_"+cdTela);
		var nrs=menu.find("span");

		//Limpa as classes do menu

		for(var i=0;i<nrs.length;i++) {
			if ((nrs[i].id != 'pg_proxima_'+cdTela) && (nrs[i].id != 'pg_anterior_'+cdTela) && (nrs[i].id != 'pg_ultima_'+cdTela) && (nrs[i].id != 'pg_primeira_'+cdTela) && (nrs[i].id != 'nr_pagina_total_'+cdTela)) {
				nrs[i].className="nr_pagina";
			}
		}

		p--;
		//Marca o selecionado
		nrs[p].className="nr_pagina_sel";

		obj = $("#nrPaginasTop_"+cdTela);
		tamanho = obj.height()+1;
		fator = parseInt(p/10);
		posY = tamanho*fator;
		obj.scrollTop(posY);
	},

	findPos: function(obj) {
		var curleft = curtop = 0;
		if (obj.offsetParent) {
			curleft = obj.offsetLeft;
			curtop = obj.offsetTop;
			while (obj = obj.offsetParent) {
				curleft += obj.offsetLeft;
				curtop += obj.offsetTop;
			}
		}
		return [curleft,curtop];
	},


	cortinaPaginas: function(obj,abre) {
		obj = $(obj);
		if ($("#ctlr_nrpaginas") != undefined){
			if (obj.css("height") != '100' && abre == true){
				obj.css("height", '100px');
				obj.css("border", '1px solid #CC8800');
				obj.css("backgroundColor", '#FFFFE7');
				$("#ctlr_nrpaginas").html("-");
			}else{
				obj.css("backgroundColor", '');
				obj.css("height",'19px');
				obj.css("border",'0px solid #CC8800');
				$("#ctlr_nrpaginas").html("+");
			}
		}
	},

	//troca a Class do obj nr_pagina_sel
	sinaliza_pagina: function(obj) {
		if (obj.className != "nr_pagina_sel") {
			if (obj.className == "nr_pagina") {
				obj.className="nr_pagina_ovr";
			} else {
				obj.className="nr_pagina";
			}
		}
	},

	//sinaliza que a pagina ja está aberta
	sinPgAtual: function(nrP,cdTela) {
		$("#pg_"+nrP+"_top_"+cdTela).css("color","red");
	},
};
var xpaginacao = new Paginacao();

function filtrar(obj) {
	url = "adm/"+obj+".php?";
	pars = "operacao=Buscar&"+$F("ds_campo_"+obj)+"="+$F("ds_criterio_"+obj);
	new Ajax.Updater("cont_lst_moeda", url, {method: 'get', parameters: pars});
};
//]]>
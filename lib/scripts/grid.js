// <![CDATA[
function Grid() {};
Grid.prototype = {

	campoOrd: null,
	ordem: null,
	objeto: null,
	arrowImg: null,

	ts_resortTable: function(lnk,index,tela,objJS,coluna,objeto) {
		if (coluna != "") {
			var span, sentido;
			var arrowImg = document.createElement("img");

		    arrowImg.src = "img/transparente.gif";
		    arrowImg.width = '25';
		    arrowImg.height = '11';

		    for (var ci=0;ci<lnk.childNodes.length;ci++) {
		        if (lnk.childNodes[ci].tagName && lnk.childNodes[ci].tagName.toLowerCase() == 'span') {
		        	span = lnk.childNodes[ci];
		        }
		    }
			var ordenacao= "ASC";

			//Pegar exemplo do sorttable Dica: span
			if (span.getAttribute("sortarrow") == 'down' || span.getAttribute("sortarrow") == null) {
				arrowImg.className = "sortUp";
				sentido = "up";
		        ordenacao = "DESC";
		    } else {
		    	arrowImg.className = "sortDown";
		        sentido = "down";
		        ordenacao = "ASC";
		    }

			xgrid.tela = tela;
			xgrid.campoOrd = coluna;
			xgrid.ordem = ordenacao;
			xgrid.objeto = objeto;
			xgrid.arrowImg = arrowImg;
			xgrid.index = index;

			new Ajax.Updater("gridTabela_"+tela,$F("path_paginacao"),{
				method: 'get',
				parameters: {
					cdTela: tela,
					objJS: objJS,
					pagina: $F("nr_pagina_atual_"+tela),
					campoOrd: coluna,
					ordem: ordenacao,
					objeto: objeto
				},
				onComplete: function(req) {
					//fazer aparecer a setinha
					$("spanord_"+xgrid.tela+"_"+xgrid.index).appendChild(arrowImg);
					$("spanord_"+xgrid.tela+"_"+xgrid.index).setAttribute('sortarrow',sentido);
					app.pngFIX();
				}
			});
		}else{
			alert("Este campo n�o possui ordena��o");
		}
	}
}
var xgrid = new Grid();
//]]>
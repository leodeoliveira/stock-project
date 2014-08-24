// <![CDATA[
/* -----------------------------------------------------------------------------
 * Classe que obtem informa��es do Browser do usu�rio
 * Version 0.1
 * Date: 11/01/2007
 * Author: Leonardo Cidral, [leonardo at softin dot com dot br]
 * Requires Prototype JavaScript library (http://prototype.conio.net/)
 * -------------------------------------------------------------------------- */
function UserBrowser() {
		var nao_suporta		= "Proprietade n�o suportada!";
		this.codinome 	= (typeof(navigator.appCodeName) != "undefined") ? navigator.appCodeName : nao_suporta;
		this.nome 		= (typeof(navigator.appName) != "undefined") ? navigator.appName : nao_suporta;
		this.versao		= (typeof(navigator.appVersion) != "undefined") ? navigator.appVersion : nao_suporta;
		this.linguagem	= (typeof(navigator.language) != "undefined") ? navigator.language : nao_suporta;
		this.plataforma	= (typeof(navigator.platform) != "undefined") ? navigator.platform : nao_suporta;
		this.agente		= (typeof(navigator.userAgent) != "undefined") ? navigator.userAgent : nao_suporta;
		this.java		= (typeof(navigator.javaEnabled()) != "undefined") ? navigator.javaEnabled() : nao_suporta;
		this.url		= (typeof(document.location) != "undefined") ? document.location : nao_suporta;
		this.protocolo	= (typeof(document.location.protocol) != "undefined") ? document.location.protocol : nao_suporta;
		this.path		= (typeof(document.referrer) != "undefined" && document.referrer != "") ? document.referrer : nao_suporta;
		this.dtAtualiza	= (typeof(document.lastModified) != "undefined") ? document.lastModified : nao_suporta;
		this.dominio	= (typeof(document.domain) != "undefined" && typeof(document.domain) != "unknown") ? document.domain : nao_suporta;
		this.charSet	= (typeof(document.charset) != "undefined") ? document.charset : nao_suporta;
		this.defCharSet	= (typeof(document.defaultCharset) != "undefined") ? document.defaultCharset : nao_suporta;
		this.cssStdMode = (typeof(document.compatMode) != "undefined") ? document.compatMode : nao_suporta;
		this.stats		= (typeof(document.readyState) != "undefined") ? document.readyState : nao_suporta;
		this.pgVisitada = (history && typeof(history.length) != "undefined") ? history.length : nao_suporta;
		this.sysLang	= (typeof(navigator.systemLanguage) != "undefined") ? navigator.systemLanguage : nao_suporta;
		this.userLang	= (typeof(navigator.userLanguage) != "undefined") ? navigator.userLanguage : nao_suporta;
		this.brwsLang	= this.browserLang();
		this.cssSuport	= (document.styleSheets) ? "Suportado!" : nao_suportado;
		this.totalCSS	= (document.styleSheets.length) ? document.styleSheets.length : nao_suportado;
		this.isIE		= this.agente.indexOf("MSIE") >=0 ? true : false;
		this.isIE6		= (parseFloat(navigator.appVersion.split("MSIE")[1]) == 6) ? true : false;
		this.isOP		= this.agente.indexOf("Opera") >=0 ? true : false;
		this.isFX		= this.agente.indexOf("Firefox") >=0 ? true : false;
		this.isGC		= this.agente.indexOf("Chrome") >=0 ? true : false;
		this.isNS		= this.agente.indexOf("Netscape6/") >=0 || this.agente.indexOf("Gecko") >=0 ? true : false;
	};
UserBrowser.prototype = {

	verObj: function() {
		strBrowser = "";
		strBrowser += "Codinome: " + this.codinome+"\n";
		strBrowser += "Nome: " + this.nome+"\n";
		strBrowser += "Vers�o: " + this.versao+"\n";
		strBrowser += "Linguagem: " + this.linguagem+"\n";
		strBrowser += "Plataforma: " + this.plataforma+"\n";
		strBrowser += "Agente: " + this.agente+"\n";
		strBrowser += "Java: " + this.java+"\n";
		strBrowser += "URL: " + this.url+"\n";
		strBrowser += "Protocolo: " + this.protocolo+"\n";
		strBrowser += "Path: " + this.path+"\n";
		strBrowser += "�ltima Atualiza��o: " + this.dtAtualiza+"\n";
		strBrowser += "Dom�nio: " + this.dominio+"\n";
		strBrowser += "CharSet: " + this.charSet+"\n";
		strBrowser += "CharSet Padr�o: " + this.defCharSet+"\n";
		strBrowser += "CSS Standards Mode: " + this.cssStdMode+"\n";
		strBrowser += "Status p�gina: " + this.stats+"\n";
		strBrowser += "Nr. P�g. visitadas: " + this.pgVisitada+"\n";
		strBrowser += "Data/Hora: " + datetime.dt_hoje+"\n";
		strBrowser += "Padr�o de Hora(GMT): " + datetime.GMT() +"\n";
		strBrowser += "Hora local: " + datetime.horaLocal() +"\n";
		strBrowser += "TimeZone: " + datetime.timeZone() +"\n";
		strBrowser += "Internet Time: " + datetime.beatTime() +"\n";
		strBrowser += "Idioma sistema: " + this.sysLang+"\n";
		strBrowser += "Idioma usu�rio: " + this.userLang+"\n";
		strBrowser += "Idioma browser: " + this.brwsLang+"\n";
		strBrowser += "Suporte CSS: " + this.cssSuport+"\n";
		strBrowser += "CSS carregados: " + this.totalCSS+"\n";
		strBrowser += "Internet Explorer: " + this.isIE+"\n";
		strBrowser += "Opera: " + this.isOP+"\n";
		strBrowser += "Netscape: " + this.isNS+"\n";

		return strBrowser;
	},


	browserLang: function() {
		if (typeof(navigator.language) != "undefined") {
			return navigator.language;
		} else if (typeof(navigator.browserLanguage) != "undefined") {
			return navigator.browserLanguage;
		} else {
			return null;
		}
	},

	 /**
		* Window size script by Tudor Barbu (tudor@it-base.ro)
		*
		* You can use it for free as long as you keep my copyright
		* notice intact. Thank you!
		*
		* returns a hashtable (array):
		* width         : the width of the viewport
		* height        : the height of the viewport
		* vScroll       : the vertical scroll
		* hScroll       : the horizontal scroll
		* totalWidth    : the width of the page
		* totalHeight   : the height of the page
		*/
	getSize: function() {
	    var width = 0, height = 0, vScroll = 0, hScroll = 0, tWidth = 0, tHeight = 0;
	    if ( typeof( window.innerWidth ) == 'number' ) {
	        width  = window.innerWidth;
	        height = window.innerHeight;
	    }
	    else {
	        if ( document.documentElement && document.documentElement.clientWidth ) {
	            width  = document.documentElement.clientWidth;
	            height = document.documentElement.clientHeight;
	        }
	        else {
	            if ( document.body && document.body.clientWidth ) {
	                width  = document.body.clientWidth;
	                height = document.body.clientHeight;
	            }
	        }
	    }
	    if( document.body && ( document.body.scrollTop || document.body.scrollLeft ) ) {
	        vScroll = document.body.scrollTop;
	        hScroll = document.body.scrollLeft;
	    }
	    else {
	        if( document.documentElement && ( document.documentElement.scrollLeft || document.documentElement.scrollTop ) ) {
	            vScroll = document.documentElement.scrollTop;
	            hScroll = document.documentElement.scrollLeft;
	        }
	        else {
	            if( window.pageYOffset ) {
	                vScroll = window.pageYOffset;
	                hScroll = window.pageXOffset;
	            }
	        }
	    }

	    var xScroll = 0, yScroll = 0;
	    if ( window.innerHeight && window.scrollMaxY ) {
	        xScroll = document.body.scrollWidth;
	        yScroll = window.innerHeight + window.scrollMaxY;
	    }
	    else {
	        if (document.body.scrollHeight > document.body.offsetHeight ) {
	            xScroll = document.body.scrollWidth;
	            yScroll = document.body.scrollHeight;
	        }
	        else {
	            xScroll = document.body.offsetWidth;
	            yScroll = document.body.offsetHeight;
	        }
	    }
	    tWidth  = Math.max( width, xScroll );
	    tHeight = Math.max( height, yScroll );
	    return { width: width, height: height, vScroll: vScroll, hScroll: hScroll, totalWidth: tWidth, totalHeight: tHeight };
	},

	compat: function() {
		retorno = false;
		if (navigator.appVersion.indexOf("MSIE")!=-1){
			version=0;
			temp=navigator.appVersion.split("MSIE");
			version=parseFloat(temp[1]);
			if (version==6) { //se n�o for IE retorna 0
				retorno = true;
			}
		}
		if (retorno == false) {
			strIE = "<br/><img id='icoAlerta' src='skin/kavo/ico/ico_alerta.gif' style=\"float:right; margin-right: 18px; margin-top: 5px;\">Seu navegador  � incompat�vel com o sistema.<br/><br/><b>Contate o suporte para maiores informa��es.</b><br/><br/>";
			strIE += "<span id='direitos'>Copyright &copy; 2006 Softin Sistemas Ltda.</span>";
			$("area_login").innerHTML = strIE;
			$("area_login").style.marginRight = "5px";
			$("area_login").style.marginTop = "5px";
			Element.remove("msg");
			//Effect.Pulsate($('icoAlerta'),{duration:50,pulses: 100});
		}
		return retorno;
	}
};
var browser = new UserBrowser();
//]]>
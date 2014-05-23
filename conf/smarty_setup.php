<?php
function comboPlus ($pars) {
	$output = null;
	$id = (string)$pars['id'];
	$opcoes = (array)$pars['opcoes'];
	$tam = (string)$pars['tamanho'];
	$inicial = (string) (isset($pars['inicial'])) ? $pars['inicial'] : null;
	$edit = (string) (isset($pars['autocomplete'])) ? ($pars['autocomplete'] != "off") ?  $pars['autocomplete'] : "OFF" : false;
	$atrAdd = (string) (isset($pars['js'])) ? $pars['js'] : null;
	$habilitado = (bool) isset($pars['disabled']) && $pars['disabled'] == "true" ? false : true;
	$title = (string) (isset($pars['title'])) ? $pars['title'] : "";
	$tabIndex = (int) (isset($pars['tabIndex'])) ? $pars['tabIndex'] : "";

	$output .= '<span style="margin: 0px; padding: 0px;">';
	$output .= '<input type="text" title="'.$title.'" id="'.$id.'_tela"';
	$output .= ' class="CampoComboCMW" style="width: '.$tam.';"';
	$output .= $atrAdd != null ? ' onChange="'.$atrAdd.'"' : null;
	$output .= $inicial == null ? null : ' value="'.$opcoes[$inicial].'"';
	$output .= ($tabIndex != null) ? 'tabindex="'.$tabIndex.'" ' : null;

	if ($edit != false) {
		$output .=  ' autocomplete="off"';
		$output .= $habilitado ? null : ' readonly="readonly"';
	} else {
		$output .= !$habilitado ? null : ' onclick="app.selectBox(\''.$id.'\',true);"' .
				'onMouseOver="$(\'img_dropCombo_'.$id.'\').src=\'skin/kavo/ico/combo-over.gif\';" ' .
				'onMouseOut="$(\'img_dropCombo_'.$id.'\').src=\'skin/kavo/ico/combo.gif\';"';
		$output .= $habilitado ? null : ' readonly="readonly"';
	}
	$output .= ' />';

	$output .= '<input type="hidden" id="'.$id.'" ';
	$output .= ($inicial != null) ? 'value="'.$inicial.'" ' : null;
	$output .= '/>';

	$output .= '<img class="img_dropCombo" id="img_dropCombo_'.$id.'" src="skin/kavo/ico/combo.gif" ' .
			'style="cursor: pointer;';
	$output .= $habilitado == false ? 'visibility: hidden;' : null;
	$output .= '" onClick="app.selectBox(\''.$id.'\',true);" ' .
			'onMouseOver="this.src=\'skin/kavo/ico/combo-over.gif\';" ' .
			'onMouseOut="this.src=\'skin/kavo/ico/combo.gif\';" />';

	$output .= '<div id="'.$id.'_values" class="comboCMW" style="display: none; overflow: auto; height: 64px;"><ul>';

	foreach($opcoes as $valor => $tela) {
		$output .= '<li id="'.$id.'_'.$valor.'" ' .
				'onMouseOver="this.style.backgroundColor=\'#316AC5\';this.style.color=\'#FFF\';" ' .
				'onMouseOut="this.style.backgroundColor=\'#FFF\';this.style.color=\'#000\';" ' .
				'onClick="app.selectedValueCombo(\''.$id.'\',\''.$valor.'\',this.innerHTML);" >';
		$output .= $tela;
		$output .= '</li>';
	}
	$output .= '</ul></div>';
	$output .= $edit != false && $edit != "OFF" ? '<div id="'.$id.'_ac" class="comboCMW" style="display: none; overflow: auto; height: 80px;"></div>' : null;
	$output .= '</span>';

	$output .= $edit != false && $edit != "OFF" ? '<script> ' .
			'new Ajax.Autocompleter("'.$id.'_tela", "'.$id.'_ac", "'.$edit.'", { paramName: "valor", afterUpdateElement: app.getSelectionId }); ' .
					'</script>' : null;
	return $output;
}
class smarty_setup extends Smarty {
	function smarty_setup() {
		// Construtor da Classe. Estes automaticamente são definidos a cada nova instância.
		parent::__construct(); 

		$this->template_dir = SMARTY_TPL_DIR.'/';
		$this->compile_dir = SMARTY_TPL_COMP.'/';
		$this->config_dir = DIR_BASE.'/conf/';
		$this->cache_dir = SMARTY_TPL_DIR.'/../cache/';

		$this->caching = SMARTY_CACHE;
		$this->debugging = SMARTY_DEBUG;

		$this->registerPlugin("function", "combo_plus","comboPlus");

		//não comentar esta linha, afeta no funcionamento do sistema.
		$this->clearAllCache();
		$this->clearCompiledTemplate();
	}
}
?>
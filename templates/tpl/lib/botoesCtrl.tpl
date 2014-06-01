<div id="fullbotoes">
	<label><span></span><b class="asterisco">*</b>{$HF_C_O}</label>

	{if $msgErro}
	<div id="msgErro">{$msgErro}</div>
	{/if}

	<label onclick="return false;"><span></span></label>
	{if NOT $HiddenBuscar}
		<button type="submit" name="{$cdTela}_btnBuscar" id="{$cdTela}_btnBuscar">{$BTN_BUSCAR}</button>
		{if NOT $HiddenExcel}<button type="button" name="{$cdTela}_btnExcel" id="{$cdTela}_btnExcel">{$BTN_EXCEL}</button>{/if}
	{/if}

	{if $inc == 1 || $alt == 1}
		{if NOT $HiddenGravar}
		<button type="button" name="{$cdTela}_btnGravar" id="{$cdTela}_btnGravar"{if $inc == 0} style="display: 'none';"{/if}>{$BTN_GRAVAR}</button>
		{/if}
	{/if}

	<button type="reset" name="{$cdTela}_btnReset" id="{$cdTela}_btnReset">{$BTN_LIMPAR}</button>

	{if NOT $HiddenBuscar && NOT $HiddenImprimir}
		<button type="button" name="{$cdTela}_btnImprimir" id="{$cdTela}_btnImprimir">{$BTN_IMPRIMIR}</button>
	{/if}

	{if $ShowGerarRelatorio}
		<button type="button" name="{$cdTela}_btnGerarRelatorio" id="{$cdTela}_btnGerarRelatorio">{$BTN_GERAR_REL}</button>
	{/if}

	{$ConteudoExtra}

	<input type="hidden" name="{$cdTela}_operacao" id="{$cdTela}_operacao" value="I" />
	<input type="hidden" name="{$cdTela}_alt" id="{$cdTela}_alt" value="{$alt}" />
	<input type="hidden" name="{$cdTela}_inc" id="{$cdTela}_inc" value="{$inc}" />
</div>
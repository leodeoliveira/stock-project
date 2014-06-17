<div id="fullbotoes">
	{if $msgErro}
		<div id="msgErro">{$msgErro}</div>
	{/if}

	{if NOT $HiddenBuscar}
		<input type="button" name="btBuscar_{$cdTela}" id="btBuscar_{$cdTela}" value="Buscar" onClick="{$objJS}.bBuscar();" />
	{/if}

	{if $inc == 'S' || $alt == 'S'}
		{if NOT $HiddenGravar}
		<input type="submit" name="btGravar_{$cdTela}" id="btGravar_{$cdTela}" value="Incluir" {if $inc == 'N'}style="display: 'none';"{/if} onClick="{$objJS}.bGravar();" />
		{/if}
	{/if}

	<input type="button" name="btLimpar_{$cdTela}" id="btLimpar_{$cdTela}" value="Limpar" onClick="{$objJS}.bLimpar('{$cdTela}');" />

	{if $ShowGerarRelatorio}
		<input type="button" name="btGerarRelatorio_{$cdTela}" id="btGerarRelatorio_{$cdTela}" value="Gerar Relat&oacute;rio" onClick="{$objJS}.btGerarRelatorio('{$cdUsuario}');" />
	{/if}

	{$ConteudoExtra}

	<input type="hidden" name="operacao" id="operacao" value="I" />
	<input type="hidden" name="alt" id="alt" value="{$alt}" />
	<input type="hidden" name="inc" id="inc" value="{$inc}" />
</div>
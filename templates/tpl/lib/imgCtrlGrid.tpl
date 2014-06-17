{config_load file="commerce.conf" section="smarty_vars"}
<div style="text-align: center;">
{if $sel=="S"}<button class="btAcao" onClick="{$objJS}.selecionar({$chave});" style="cursor: pointer; width: 21px;" title="Clique para transferir os dados deste registro."><img src="skin/{#NM_TEMA#}/ico/selecionar.gif" style="width:21px;" /></button>&nbsp;{/if}
{if $alt=="S"}<button class="btAcao" onClick="{$objJS}.alterar({$chave},this);" style="cursor: pointer;" title="Clique para editar este registro."><img src="skin/{#NM_TEMA#}/ico/acoes/editar.gif" /></button>&nbsp;{/if}
{if $inf=="S"}<button class="btAcao" onClick="{$objJS}.info({$chave});" style="cursor: pointer;" title="Clique para exibir maiores informa&ccedil;&otilde;es deste registro."><img src="skin/{#NM_TEMA#}/ico/acoes/info.gif" /></button>&nbsp;{/if}
{if $exc=="S"}<button class="btAcao" onClick="{$objJS}.excluir({$chave});" style="cursor: pointer;"><img src="skin/{#NM_TEMA#}/ico/acoes/excluir.gif" /></button>{/if}
</div>
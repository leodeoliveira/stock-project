{config_load file="supmol.conf" section="smarty_vars"}
<div style="text-align: center;">
{if $sel=="S"}<button class="btAcao" onClick="{$objJS}.selecionar({$chave});" style="cursor: pointer; width: 21px;" title="Clique para transferir os dados deste registro."><img src="resources/img/selecionar.gif" style="width:21px;" /></button>&nbsp;{/if}
{if $alt=="S"}<button class="btAcao" onClick="{$objJS}.alterar({$chave},this);" style="cursor: pointer;" title="Clique para editar este registro."><img src="resources/img/editar.gif" /></button>&nbsp;{/if}
{if $inf=="S"}<a href="#{$nmHistorico}" onClick="{$objJS}.info({$chave});"><button class="btAcao" style="cursor: pointer;" title="Clique para exibir maiores informa&ccedil;&otilde;es deste registro."><img src="resources/img/info.gif" /></button></a>&nbsp;{/if}
{if $exc=="S"}<button class="btAcao" onClick="{$objJS}.excluir({$chave});" style="cursor: pointer;"><img src="resources/img/excluir.gif" /></button>{/if}
</div>
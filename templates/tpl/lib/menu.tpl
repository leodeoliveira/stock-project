<ul class="menu_ul">
	{foreach from=$menus item=menu}
	<li class="menu_root">{if $menu.link}<a href="{$menu.link}" onclick="return view(this);">{$menu.nome}</a>{else}{$menu.nome}{/if}</li>
	{if $menu.sub}
	<ul class="submenu_ul">
	{foreach from=$menu.sub item=submenu}
		<li class="submenu"><a href="{$submenu.link}" onclick="return view(this);">{$submenu.nome}</a></li>
	{/foreach}
	</ul>
	{/if}
	{/foreach}
</ul>

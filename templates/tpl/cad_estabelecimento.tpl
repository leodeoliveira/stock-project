 <form id="formEstabelecimento" name="formEstabelecimento" class="wufoo topLabel page" autocomplete="off" enctype="multipart/form-data" method="post" action="#public">

<div class="info">
	<h2>Cadastro Estabelecimento</h2>
	<div></div>
</div>
<ul>
	
<li id="liNome" class="">
	<label class="desc" id="titleNome" for="txtNome">Nome</label>
	<input id="txtNome" name="txtNome" type="text" class="field text addr" value="" tabindex="1" size="80"/>
</li>
<li id="liSeguimento" 		class="full">
	<label class="desc" id="titleSegui" for="selSeguimento">Seguimento</label>
	<div>
		<select id="selSeguimento" 	name="selSeguimento" class="field select medium" tabindex="5" > 
			<option value="1" selected="selected">Restaurante</option>
			<option value="2">Bar</option>
			<option value="3">Loja</option>
			<option value="4">Entretenimento</option>
		</select>
	</div>
</li>
<li id="txtEndereco" class="leftHalf">
	<label class="desc" id="titleEndereco" for="txtRua">Endereço</label>
	<input id="txtRua" name="txtRua" type="text" class="field text addr" value="" tabindex="10" size="65"/>
</li>
<li id="txtCep" class="rightThird">
	<label class="desc" for="txtCep">CEP</label>
	<input id="txtCep" name="txtCep" type="text" class="field text mediun" value="" tabindex="15" size="30"/>
</li>
<li id="txtNumero" class="">
	<span>
		<label class="desc" for="txtNumero">Número</label>
		<input id="txtNumero" name="txtNumero" type="text" class="field text fn" value="" tabindex="20" size="30"/>
	</span>
	<span>
		<label class="desc" for="txtComplemento">Complemento</label>
		<input id="txtComplemento" name="txtComplemento" type="text" class="field text fn" value="" tabindex="25" size="30" />
	</span></li>

<li id="liCidade" class="leftHalf">
	<label class="desc" id="titleCidade" for="selCidade">Cidade</label>
	<div class="leftHalf">
		<select id="selCidade" name="selCidade" class="field select medium" tabindex="30"> 
			<option value="1" selected="selected">Joinville</option>
			<option value="2">Florianópolis</option>
		</select>
	</div>
</li>
<li id="liEstado" class="rightHalf" >
	<label class="desc" id="titleEstado" for="selEstado">Estado</label>
	<div class="rightHalf">
		<select id="selEstado" name="selEstado" class="field select medium" tabindex="35"> 
			<option value="1" selected="selected">SC</option>
			<option value="2">PR</option>
		</select>
	</div>
</li>
<li id="txtTelefone1" class="phone leftHalf">
	<label class="desc" id="titleFone1" for="txtFone1-1">Telefone	</label>
	<span class="symbol">(</span>
	<span>
		<input id="txtFone1-1" name="txtFone1-1" type="text" class="field text" value="" size="3" maxlength="2" tabindex="40"/>
		<label for="txtFone1-1">##</label>
	</span>
	<span class="symbol">)</span>
	<span>
		<input id="txtFone1-2" name="txtFone1-2" type="text" class="field text" value="" size="5" maxlength="4" tabindex="41"/>
		<label for="txtFone1-2">####</label>
	</span>
	<span class="symbol">-</span>
	<span>
	 	<input id="txtFone1-3" name="txtFone1-3" type="text" class="field text" value="" size="5" maxlength="4" tabindex="42"/>
		<label for="Field33-2">####</label>
	</span></li>

<li id="txtFone2" class="phone rightHalf">
	<label class="desc" id="titleFone2" for="txtFone2">Telefone</label>
	<span class="symbol">(</span>
	<span>
		<input id="txtFone2-1" name="txtFone2-1" type="text" class="field text" value="" size="3" maxlength="2" tabindex="45"/>
		<label for="txtFone2-1">##</label>
	</span>
	<span class="symbol">)</span>
	<span>
		<input id="txtFone2-2" name="txtFone2-2" type="text" class="field text" value="" size="5" maxlength="4" tabindex=46" />
		<label for="txtFone2-2">####</label>
	</span>
	<span class="symbol">-</span>
	<span>
	 	<input id="txtFone2-3" name="txtFone2-3" type="text" class="field text" value="" size="5" maxlength="4" tabindex="47" />
		<label for="txtFone2-3">####</label>
	</span></li>
<li id="liEmail" class="">
	<label class="desc" id="titleEmail" for="txtEmail">E-mail</label>
	<div>
		<input id="txtEmail" name="txtEmail" type="text" class="field text large" value="" maxlength="255" tabindex="50" onkeyup="" />
	</div>
</li>
<li id="liSite" class="">
	<label class="desc" id="titleSite" for="txtSite">Site</label>
	<div>
		<input id="txtSite" name="txtSite" type="text" class="field text large" value="" maxlength="255" tabindex="55" onkeyup=""/>
	</div>
</li>
<li id="liOrkut" class="leftHalf">
	<label class="desc" id="titleOrkut" for="txtOrkut">Orkut</label>
	<div>
		<input id="txtOrkut" name="txtOrkut" type="text" class="field text large" value="" maxlength="255" tabindex="60" onkeyup=""/>
	</div>
</li>
<li id="liTwitter" class="rightHalf">
	<label class="desc" id="titleTwitter" for="txtTwitter">Twitter</label>
	<div>
		<input id="txtTwitter" name="txtTwitter" type="text" class="field text large" value="" maxlength="255" tabindex="65" onkeyup=""/>
	</div>
</li>

<li id="liSkype" class="leftHalf">
	<label class="desc" id="titleSkype" for="txtSkype">Skype</label>
	<div>
		<input id="txtSkype" name="txtSkype" type="text" class="field text large" value="" maxlength="255" tabindex="70" onkeyup=""/>
	</div>
</li>
<li id="liMessenger" class="rightHalf">
	<label class="desc" id="titleMessenger" for="txtMessenger">Messenger</label>
	<div>
		<input id="txtMessenger" name="txtMessenger" type="text" class="field text large" value="" maxlength="255" tabindex="75" onkeyup=""/>
	</div>
</li>
<li id="liFacebook" class="leftHalf">
	<label class="desc" id="titleFacebook" for="txtFacebook">Facebook</label>
	<div>
		<input id="txtFacebook" name="txtFacebook" type="text" class="field text large" value="" maxlength="255" tabindex="80" onkeyup=""/>
	</div>
</li>
<li id="liFormspring" class="rightHalf">
	<label class="desc" id="titleFormspring" for="txtFormspring">Formspring</label>
	<div>
		<input id="txtFormspring" name="txtFormspring" type="text" class="field text large" value="" maxlength="255" tabindex="85" onkeyup=""/>
	</div>
</li>
	<li class="buttons ">
		<div>
			<input type="hidden" name="currentPage" id="currentPage" value="dB5YAYUJLThQ1vViLqkRtO8PC6nWmLuPsz2BRQNT4gw=" />
			<input id="saveForm" name="saveForm" class="btTxt submit" type="submit"  value="Enviar" tabindex="90"/>
			<input id="cleanForm" name="cleanForm" class="btTxt submit" type="reset" value="Limpar" tabindex="95"/>
		</div>
	</li>
	
	<li style="display:none">
		<label for="comment">Do Not Fill This Out</label>
		<textarea name="comment" id="comment" rows="1" cols="1"></textarea>
		<input type="hidden" id="idstamp" name="idstamp" value="" />
	  </li>
</ul>
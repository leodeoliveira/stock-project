<div class="col-md-offset-2">
<div id="form-trans">
<form role="form" class="form-horizontal" action="customer.put" method="post">
	<div class="form-group">
				<label for="id_customer" class="col-sm-2 control-label">C&oacute;digo</label>
				<div class="col-sm-5">
					<input type="text" id="id_customer" name="id_customer"
						class="form-control" value="{$id_customer}" disabled="disabled" />

					<input type="hidden" id="id" name="id" value="{$id_customer}" />
				</div>
			</div>
	<div class="form-group">
		<label for="name" class="col-sm-2 control-label">Nome *</label>
			<div class="col-sm-5">
				<input type="name" id="name" name="name" class="form-control" placeholder="Digite seu nome..." value="{$name}" required="required">
			</div>
		</div>
		<div class="form-group">
			<label for="document" class="col-sm-2 control-label">CPF *</label>
			<div class="col-sm-5">
				<input type="text" id="document" name="document" class="form-control" placeholder="Digite seu CPF..." required="required" value="{$document}"  maxlength="11">
			</div>
		</div>
		<div class="form-group">
			<label for="cep" class="col-sm-2 control-label">CEP *</label>
			<div class="col-sm-5">
				<input type="text" id="cep" value="{$zip_code}" name="cep" class="form-control" placeholder="Digite seu CEP..." required="required">
			</div>
		</div>
		<div class="form-group">
			<label for="street" class="col-sm-2 control-label">Rua *</label>
			<div class="col-sm-5">
				<input type="text" id="street" name="street" value="{$street}" class="form-control" placeholder="Digite o nome da rua..." required="required">
			</div>
		</div>
		<div class="form-group">
			<label for="number" class="col-sm-2 control-label">Número *</label>
			<div class="col-sm-5">
				<input type="number" id="number" name="number" class="form-control" value="{$address_number}" placeholder="Digite o numero..." required="required">
			</div>
		</div>
		<div class="form-group">
			<label for="city" class="col-sm-2 control-label">Cidade *</label>
			<div class="col-sm-5" required="required">
				<input type="text" id="city" name="city" class="form-control" placeholder="Digite seu telefone..." value="{$city}" required="required">
			</div>
		</div>
		<div class="form-group">
			<label for="state" class="col-sm-2 control-label">Estado *</label>
			<div class="col-sm-5">
				<input type="text" id="state" name="state" class="form-control" placeholder="Digite a UF..." value="{$state}" maxlength="2">
			</div>
		</div>

			<div class="form-group">
				<div class="col-sm-6 pull-right">
					<button class="btn btn-success" type="submit">
						Salvar
					</button>
				</div>
			</div>
		</form>
	</div>
	{$tabela}
</div>



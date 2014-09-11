<div class="col-md-offset-2">
	<div id="form-trans">
		<form role="form" class="form-horizontal" action="customer.put"
			method="post">
			<div class="form-group">
				<label for="id_customer" class="col-sm-2 control-label">C&oacute;digo</label>
				<div class="col-sm-5">
					<input type="text" id="id_customer" name="id_customer"
						class="form-control" value="{$id_customer}" disabled="disabled" />

					<input type="hidden" id="id" name="id" value="{$id_customer}" />
				</div>
			</div>
			<div class="form-group">
				<label for="name" class="col-sm-2 control-label">Nome conhecido *</label>
				<div class="col-sm-5">
					<input type="name" id="name" name="name" class="form-control" value="{$name}"
						placeholder="Primeiro nome, apelido, nome fantasia..." required="required">
				</div>
			</div>
			<div class="form-group">
				<label for="fullname" class="col-sm-2 control-label">Nome Completo / Raz&atilde;o Social</label>
				<div class="col-sm-5">
					<input type="name" id="fullname" name="fullname" class="form-control" value="{$fullname}"
						placeholder="Nome completo ou raz&atilde;o social...">
				</div>
			</div>
			<div class="form-group">
				<label for="document" class="col-sm-2 control-label">CPF / CNPJ</label>
				<div class="col-sm-5">
					<input type="text" id="document" name="document" value="{$document}"
						class="form-control" placeholder="Digite seu CPF..."
						maxlength="14">
				</div>
			</div>
			<div class="form-group">
				<label for="cep" class="col-sm-2 control-label">CEP</label>
				<div class="col-sm-5">
					<input type="text" id="cep" name="cep" class="form-control" value="{$zip_code}"
						placeholder="Digite seu CEP..." maxlength="8">
				</div>
			</div>
			<div class="form-group">
				<label for="street" class="col-sm-2 control-label">Rua</label>
				<div class="col-sm-5">
					<input type="text" id="street" name="street" class="form-control" value="{$street}"
						placeholder="Digite o nome da rua...">
				</div>
			</div>
			<div class="form-group">
				<label for="number" class="col-sm-2 control-label">N&uacute;mero</label>
				<div class="col-sm-5">
					<input type="number" id="number" name="number" class="form-control" value="{$address_number}"
						placeholder="Digite o numero...">
				</div>
			</div>
			<div class="form-group">
				<label for="city" class="col-sm-2 control-label">Cidade *</label>
				<div class="col-sm-5">
					<input type="text" id="city" name="city" class="form-control" value="{$city}"
						placeholder="Digite seu telefone..." required="required">
				</div>
			</div>
			<div class="form-group">
				<label for="state" class="col-sm-2 control-label">Estado *</label>
				<div class="col-sm-5">
					<input type="text" id="state" name="state" class="form-control" value="{$state}"
						placeholder="Digite a UF..." maxlength="2">
				</div>
			</div>

			<div class="form-group">
				<div class="col-sm-6 pull-right">
					<button class="btn btn-success" type="submit">Salvar</button>
				</div>
			</div>
		</form>
	</div>
	{$tabela} {$message}
</div>
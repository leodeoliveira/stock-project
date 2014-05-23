<div class="container">
	<div class="row">
		<div class="col-md-offset-2">
			<div id="form-trans">
				<form role="form" class="form-horizontal">
					<div class="form-group">
						<label for="nome" class="col-sm-2 control-label">Nome *</label>
							<div class="col-sm-5">
								<input type="nome" id="nome" class="form-control" placeholder="Digite seu nome..." required="required">
							</div>
						</div>
						<div class="form-group">
							<label for="email" class="col-sm-2 control-label">Email *</label>
							<div class="col-sm-5">
								<input type="email" id="email" class="form-control" placeholder="Digite seu email..." required="required">
							</div>
						</div>
						<div class="form-group">
							<label for="data_nascimento" class="col-sm-2 control-abel">Data Nasc*</label>
								<div class="col-sm-5">
									<input type="date" id="data_nascimento" class="form-control" required="required">
								</div>
							</div>
							<div class="form-group">
								<label for="cpf" class="col-sm-2 control-label">CPF *</label>
								<div class="col-sm-5">
									<input type="text" id="cpf" class="form-control" 
									pattern="\d{3}\.\d{3}\.\d{3}-\d{2}"placeholder="Digite seu CPF...">
								</div>
							</div>
							<div class="form-group">
								<label for="rg" class="col-sm-2 control-label">RG *</label>
								<div class="col-sm-5">
									<input type="text" id="rg" class="form-control" placeholder="Digite seu RG...">
								</div>
							</div>
							<div class="form-group">
								<label for="telefone" class="col-sm-2 control-label">Telefone *</label>
								<div class="col-sm-5">
									<input type="text" id="telefone" class="form-control" placeholder="Digite seu telefone...">
								</div>
							</div>
							<div class="form-group">
								<label for="rua" class="col-sm-2 control-label">Rua *</label>
								<div class="col-sm-4">
									<input type="text" id="rua" class="form-control" placeholder="Digite o nome da rua onde mora...">
								</div>
								<div class="col-sm-1">
									<input type="number" id="numero" class="form-control" placeholder="Nº...">
								</div>

							</div>
							<div class="form-group">
								<label for="complemento" class="col-sm-2 control-label">Complemento</label>
								<div class="col-sm-5">
									<input type="text" id="complemento" class="form-control" placeholder="Digite o complemento...">
								</div>
							</div>
							<div class="form-group">
								<label for="estado" class="col-sm-2 control-label">Estado *</label>
								<div class="col-sm-5">
									<select name="estado" class="form-control">
										<option value="SC">Santa Catarina</option>
										<option value="RS">Rio Grande do Sul</option>
										<option value="PR">Paraná</option>
										<option value="SP">São Paulo</option>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label for="cidade" class="col-sm-2 control-label">Cidade *</label>
								<div class="col-sm-5">
									<select name="estado" class="form-control">
										<option value="1">Joinville</option>
										<option value="2">Araquari</option>
										<option value="3">Cascavel</option>
										<option value="4">Ponta Grossa</option>
										<option value="5">São Paulo</option>
										<option value="5">São Caetano</option>
									</select>
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-6 pull-right">
									<button class="btn btn-success">
										Enviar
									</button>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
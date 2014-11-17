<div class="col-md-offset-2">
	<div id="form-trans">
		<form role="form" class="form-horizontal" action="sales_order.put"
			method="post">
			<input
				type="hidden" name="id_customer" id="id_customer" /> <input
				type="hidden" name="id_sales_order" id="id_sales_order"
				value="{$id}" />
			<div class="form-group">
				<label for="customer" class="col-sm-2 control-label">Consumidor
					*</label>
				<div class="col-sm-5">
					<input type="text" autocomplete="off" name="customer" id="customer"
						class="form-control" placeholder="Digite o Nome do Consumidor"
						required="required" />
				</div>
			</div>

			<!-- Itens table -->
			<table id="products">
				<caption>Produtos</caption>
				<thead>
					<tr>
						<th>#</th>
						<th>Produto</th>
						<th>Val. Unit.</th>
						<th>Quant.</th>
						<th colspan="2">Val. Total</th>
					</tr>
				</thead>
				<tbody class="default-template">
					<tr id="prod-tr-#sid#">
						<td>#sid#</td>
						<td><input type="hidden" id="prod-id-#sid#" value="#prod-id#" /> #name#</td>
						<td id="prod-unit-#sid#" class="prod-value">#unit#</td>
						<td><input type="number" name="qtd_#sid#" id="qtd_#sid#" value="#quant#" onchange="updateProdData(#sid#);"/></td>
						<td id="prod-total-#sid#" class="prod-value">#total#</td>
						<td><button onclick="return delProd(#sid#);">-</button></td>
					</tr>
				</tbody>
				<tfoot>
					<tr>
						<td>&nbsp;</td>
						<td>
					<input type="text" autocomplete="off" name="product" id="product"
						class="form-control"
						placeholder="Digite a descri&ccedil;&atilde;o do produto..."
						required="required" />
						<input type="hidden" id="prod-id-temp" name="prod-id-temp"/></td>
						<td id="prod-unit-temp" class="prod-value">0,00</td>
						<td><input type="number" id="qtd_temp" name="qtd_temp" onchange="updateProdData('temp');" value="1" /></td>
						<td id="prod-total-temp" class="prod-value">0,00</td>
						<td><button onclick="return addProd();">+</button></td>
					</tr>
				</tfoot>
			</table>
			<!-- Itens table - END -->

			<div class="form-group">
				<label for="total" class="col-sm-2 control-label">Valor total </label>
				<div class="col-sm-5 prod-value" id="total-itens">0,00
				</div>
			</div>

			<div class="form-group">
				<label for="payment_method" class="col-sm-2 control-label">Forma
					de pagamento *</label>
				<div class="col-sm-5">
					<select id="payment_method" name="payment_method"
						class="form-control">

					</select>
				</div>
			</div>
			<div class="form-group">
				<label for="payment_condition" class="col-sm-2 control-label">Condi&ccedil;&atilde;o
					de pagamento *</label>
				<div class="col-sm-5">
					<select id="payment_condition" name="payment_condition"
						class="form-control"></select>
				</div>
			</div>
			<div class="form-group">
				<label for="date" class="col-sm-2 control-label">Data de
					emiss&atilde;o *</label>
				<div class="col-sm-5">
					<input type="date" id="date" name="date" class="form-control">
				</div>
			</div>
			<div class="form-group">
				<label for="delivery_date" class="col-sm-2 control-label">Data
					de entrega *</label>
				<div class="col-sm-5">
					<input type="date" id="delivery_date" name="delivery_date"
						class="form-control">
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-6 pull-right">
					<button class="btn btn-success" type="submit">Salvar</button>
				</div>
			</div>
		</form>
	</div>
</div>

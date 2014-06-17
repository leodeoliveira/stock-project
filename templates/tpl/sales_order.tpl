<div class="col-md-offset-2">
<div id="form-trans">
<form role="form" class="form-horizontal" action="adm/user.put" method="post">
    <div class="form-group">
        <label for="customer" class="col-sm-2 control-label">Consumidor *</label>
            <div class="col-sm-5">
                <input type="text" id="customer" name="customer" class="form-control" placeholder="Digite seu nome..." required="required">
            </div>
        </div>
        <div class="form-group">
            <label for="product" class="col-sm-2 control-label">Produto *</label>
            <div class="col-sm-5">
                <input type="text" id="product" name="product" class="form-control" placeholder="Digite seu email..." required="required">
            </div>
        </div>
        <div class="form-group">
            <label for="payment_method" class="col-sm-2 control-label">Forma de pagamento *</label>
            <div class="col-sm-5">
                <select id="payment_method" name="payment_method" class="form-control">

                </select>
            </div>
        </div>
        <div class="form-group">
            <label for="payment_condition" class="col-sm-2 control-label">Condição de pagamento *</label>
            <div class="col-sm-5">
                <select id="payment_condition" name="payment_condition" class="form-control" placeholder="Digite seu telefone..."></select>
            </div>
        </div>
        <div class="form-group">
            <label for="date" class="col-sm-2 control-label">Data de emissão *</label>
            <div class="col-sm-5">
                <input type="date" id="date" name="date" class="form-control" >
            </div>
        </div>
        <div class="form-group">
            <label for="delivery_date" class="col-sm-2 control-label">Data de entrega *</label>
            <div class="col-sm-5">
                <input type="date" id="delivery_date" name="delivery_date" class="form-control" placeholder="Digite seu telefone...">
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
</div>


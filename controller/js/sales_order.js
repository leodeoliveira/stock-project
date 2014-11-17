Array.prototype.remove = function() {
    var what, a = arguments, L = a.length, ax;
    while (L && this.length) {
        what = a[--L];
        while ((ax = this.indexOf(what)) !== -1) {
            this.splice(ax, 1);
        }
    }
    return this;
};

function toPrint(n) {
	var c = 2,
    d = ",",
    t = ".",
    s = n < 0 ? "-" : "",
    i = parseInt(n = Math.abs(+n || 0).toFixed(c)) + "",
    j = (j = i.length) > 3 ? j % 3 : 0;
   return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
}

function fromPrint(t) {
	return new Number(t.replace(/\./, "").replace(/,/, "."));
}

var customers = new Bloodhound({
    datumTokenizer : Bloodhound.tokenizers.obj.whitespace('value'),
    queryTokenizer : Bloodhound.tokenizers.whitespace,
    prefetch : 'data/customer/prefetch/best.json',
    remote : 'data/customer/live/%QUERY.json'
});

customers.initialize();

var products = new Bloodhound({
    datumTokenizer : Bloodhound.tokenizers.obj.whitespace('value'),
    queryTokenizer : Bloodhound.tokenizers.whitespace,
    prefetch : 'data/product/prefetch/best.json',
    remote : 'data/product/live/%QUERY.json'
});

products.initialize();

function onAutocompleted($e, datum) {
    $("#prod-id-temp").val(datum.id);
    $("#prod-unit-temp").text(toPrint(datum.unit));
}

function onAutocompletedCustomer($e, datum) {
    $("#id_customer").val(datum.id);
}

$(document).ready(function() {
    $("#product").typeahead({
        highlight : true
    }, {
        name : 'product-item',
        displayKey : 'value',
        source : products.ttAdapter()
    })
    .on('typeahead:selected', onAutocompleted)
    .on('typeahead:autocompleted', onAutocompleted);

    $("#customer").typeahead({
        highlight : true
    }, {
        name : 'consumer-so',
        displayKey : 'value',
        source : customers.ttAdapter()
    })
    .on('typeahead:selected', onAutocompletedCustomer)
    .on('typeahead:autocompleted', onAutocompletedCustomer);

    var templateEl = $("#products").find(".default-template");
    template = templateEl.html();
    templateEl.removeClass("default-template");
    templateEl.html("");
});

var template = "";
var sids = [];
var orderTotal = 0;

function updateTotal(itemVal, increase) {
	if (increase)
		orderTotal += itemVal;
	else
		orderTotal -= itemVal;

	$("#total-itens").text(toPrint(orderTotal));
}

function updateProdData(sid) {
	if (sid != "temp")
		updateTotal(fromPrint($("#prod-total-" + sid).text()), false);
	var unit = fromPrint($("#prod-unit-" + sid).text());
	var total = unit * $("#qtd_" + sid).val();
	$("#prod-total-" + sid).text(toPrint(total));
	if (sid != "temp")
		updateTotal(total, true);
}

function addProd() {
	var sid = 0;
	for ( var i = 0; i < sids.length; i++) {
		if (sids[i] > sid)
			sid = sids[i];
	}
	sid = sid + 1;
	sids.push(sid);
	var unit = fromPrint($("#prod-unit-temp").text());
	var qtd = $("#qtd_temp").val();
	var tr_temp = template
		.replace(/#sid#/g, sid)
		.replace(/#prod-id#/g, $("#prod-id-temp").val())
		.replace(/#name#/g, $("#product").val())
		.replace(/#unit#/g, toPrint(unit))
		.replace(/#quant#/g, qtd)
		.replace(/#total#/g, toPrint(unit * qtd))
		;


	updateTotal(unit * qtd, true);

	$("#products tbody").append(tr_temp);
	$("#prod-id-temp").val("");
	$("#product").val("");
	$("#qtd_temp").val(1);
	$("#product").focus();
	return false;
}

function delProd(sid) {
	sids.remove(sid);
	updateTotal(fromPrint($("#prod-total-" + sid).text()), false);
	$("#prod-tr-" + sid).remove();
	return false;
}
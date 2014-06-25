var products = new Bloodhound({
	datumTokenizer : Bloodhound.tokenizers.obj.whitespace('value'),
	queryTokenizer : Bloodhound.tokenizers.whitespace,
	prefetch : 'data/product/prefetch/best.json',
	remote : 'data/product/live/%QUERY.json'
});

products.initialize();

function onAutocompleted($e, datum) {
    $("id_product").val(datum.id);
}

$(document).ready(function() {
	$("#product").typeahead({
		highlight : true
	}, {
		name : 'best-pictures',
		displayKey : 'value',
		source : products.ttAdapter()
	})
	.on('typeahead:selected', onAutocompleted)
    .on('typeahead:autocompleted', onAutocompleted);
});
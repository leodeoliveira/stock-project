var orders = new Bloodhound({
    datumTokenizer : Bloodhound.tokenizers.obj.whitespace('value'),
    queryTokenizer : Bloodhound.tokenizers.whitespace,
    prefetch : 'data/product/prefetch/best.json',
    remote : 'data/product/live/%QUERY.json'
});

orders.initialize();

function onAutocompleted($e, datum) {
    $("#id_product").val(datum.id);
}

function onAutocompletedCustomer($e, datum) {
    alert('TestePorra');
    $("#id_customer").val(datum.id);
}

$(document).ready(function() {
    $("#product").typeahead({
        highlight : true
    }, {
        name : 'best-pictures',
        displayKey : 'value',
        source : orders.ttAdapter()
    })
    .on('typeahead:selected', onAutocompleted)
    .on('typeahead:autocompleted', onAutocompleted);

    $("#customer").typeahead({
        highlight : true
    }, {
        name : 'best-pictures',
        displayKey : 'value',
        source : orders.ttAdapter()
    })
    .on('typeahead:selected', onAutocompletedCustomer)
    .on('typeahead:autocompleted', onAutocompletedCustomer);
});

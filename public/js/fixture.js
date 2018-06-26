$("#modalEliminarPartido").on('shown.bs.modal', function(event) {
	var IDPartido = $(event.relatedTarget).data('idpartido');
	var IDFecha = $(event.relatedTarget).data('idfecha');
	console.log(IDPartido);
	console.log(IDFecha);
	$('#hiddenEliminarPartido').val(IDPartido);
	$('#hiddenEliminarPartidoFecha').val(IDFecha);
});

$("#modalEliminarFecha").on('shown.bs.modal', function(event) {
	var IDFecha = $(event.relatedTarget).data('idfecha');
	console.log(IDFecha);
	$('#hiddenEliminarFecha').val(IDFecha);
});

function goBack(){
	window.history.back();
}


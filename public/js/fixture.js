$("#modalAgregarPartido").on('shown.bs.modal', function(event) {
	var IDFecha = $(event.relatedTarget).data('id');
	console.log(IDFecha);
	$('#hiddenFecha').val(IDFecha);
});

$("#modalModificarPartido").on('shown.bs.modal', function(event) {
	var IDPartido = $(event.relatedTarget).data('id');
	console.log(IDPartido);
	$('#hiddenPartido').val(IDPartido);
});


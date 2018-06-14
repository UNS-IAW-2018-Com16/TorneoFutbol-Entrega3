$("#modalAgregarJugador").on('shown.bs.modal', function(event) {
	var IDEquipo = $(event.relatedTarget).data('id');
	console.log(IDEquipo);
	$('#hiddenEquipo').val(IDEquipo);
});

$("#modalModificarJugador").on('shown.bs.modal', function(event) {
	var IDJugador = $(event.relatedTarget).data('id');
	console.log(IDJugador);
	$('#hiddenJugador').val(IDJugador);
});

$("#modalEliminarEquipo").on('shown.bs.modal', function(event) {
	var IDEquipo = $(event.relatedTarget).data('id');
	console.log(IDEquipo);
	$('#hiddenConfirmarEquipo').val(IDEquipo);
});

$("#modalEliminarJugador").on('shown.bs.modal', function(event) {
	var IDEquipo = $(event.relatedTarget).data('ide');
	var IDJugador = $(event.relatedTarget).data('idj');
	console.log(IDEquipo);
	console.log(IDJugador);
	$('#hiddenConfirmarJugadorEquipo').val(IDEquipo);
	$('#hiddenConfirmarJugador').val(IDJugador);
});

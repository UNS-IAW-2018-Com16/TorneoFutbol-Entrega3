$("#modalAgregarJugador").on('shown.bs.modal', function(event) {
	var IDEquipo = $(event.relatedTarget).data('id');
	console.log(IDEquipo);
	$('#hiddenEquipo').val(IDEquipo);
});
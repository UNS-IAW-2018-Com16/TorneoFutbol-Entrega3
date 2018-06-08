var IDPartido;

$("#modalAgregarPartido").on('shown.bs.modal', function(event) {
	IDPartido = $(event.relatedTarget).data('id');
});

$('#btnAgregarPartido').click(function (event){
	console.log(IDPartido);
	var target = event.target;

	var datosPartido = {

	};

	$.post('./api/datosPartido',
	datosPartido,
	function (data, status){
		$("#modalAgregarPartido").modal('hide');
	});
});
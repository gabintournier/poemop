$(document).ready(function() {
	$(".disable").attr('disabled','disabled');
	$(".avis").click('tr', function() {
		var cols = $(this).find("td");
		$('#id_cmde').val(cols[0].innerText);
		$('#signature').val(cols[1].innerText);
		var value = cols[2].innerText;
		if (value == "0") {
			$("#valide").val("0")
		}
		if (value == "1") {
			$("#valide").val("1")
		}
		if (value == "2") {
			$("#valide").val("2")
		}
		$('#date_clients').val(cols[3].innerText);
		$('#message').val(cols[5].innerText);
		$('#reponse').val(cols[6].innerText);
		$(".disable").removeAttr('disabled');
	});

	$('.commande_id').on('dblclick', 'tr', function() {
    	var id = $(this).find('input').val();
    	console.log(id);
    	// Utilisation de baseUrl pour gérer dev / prod automatiquement
    	window.location.href = baseUrl + '/admin/gestion_client_commande.php?id_cmd=' + id + '&return=avis';
	});
});

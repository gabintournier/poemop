$(document).ready(function() {
	var loc = window.location.href;
	var searchParams = new URLSearchParams(window.location.search);

	searchParams.has('id_crypte');
	var id_crypte = searchParams.get('id_crypte');
	
	// $('#trie_table_grp').dataTable({
	// 	"paging":   false,
	// 	"info":   false,
	// 	"searching":   false,
	// 	columnDefs: [{ 'targets': 3, type: 'date-eu' }],
	// });

	$('#trie_table_cmd').dataTable({
		"paging":   false,
		"info":   false,
		"searching":   false,
	});

	$('#trie_table_cmd_qte').dataTable({
		"paging":   false,
		"info":   false,
		"searching":   false,
	});

	$('#cmdes_termines').dataTable({
		"paging":   false,
		"info":   false,
		"searching":   false,
	});

	$('#trie_ord').dataTable({
		"paging":   false,
		"info":   false,
		"searching":   false,
	});

	$('#trie_sup').dataTable({
		"paging":   false,
		"info":   false,
		"searching":   false,
	});

	$(".regroupements").click("tr", function() {
		var value = $(this).find('input').val();
		window.location.href = 'details_groupement.php?id_crypte='+id_crypte+'&id_grp='+value+'&return=grp';
	});

	$(".zones").click("tr", function() {
		var value = $(this).find('input').val();
		window.location.href = 'zone_cotations.php?id_crypte='+id_crypte+'&id_zone='+value+'&return=zone_cot';
	});

	$(".recap").click("tr", function() {
		var value = $(this).find('input').val();
		window.location.href = 'saisie_recap.php?id_crypte='+id_crypte+'&id_grp='+value+'&return=recap';
	});

	$(".termines").click("tr", function() {
		var value = $(this).find('input').val();
		window.location.href = 'details_groupement_termine.php?id_crypte='+id_crypte+'&id_grp='+value+'&return=termines';
	});

	$(".commande").click("tr", function() {
		$(this).addClass('selected').siblings().removeClass('selected');;
	});

	$('input:checkbox').change(function () {
		if($(this).is(":checked")) {
			var name = $(this).val();
			var qte = '.qte_' + name;
			var qte_livree = $(qte).val();
			var value = '.qte_livree_' + name;
			$(value).val(qte_livree);
		}
		else {
			var name = $(this).val();
			var value = '.qte_livree_' + name;
			$(value).val("0");
		}
	});

	$('input:checkbox').change(function () {
		if($(this).is(":checked")) {
			var id = $(this).val();
			$('.saisie_'+id).show();
			$('.checkbox_'+id).hide();
		}
	});

	// $(".remove").click(function() {
	// 	$("#FormID").submit()
	// 	// var id = $(this).find('input').val();
	// 	// $('.saisie_'+id).show();
	// 	// $('.checkbox_'+id).hide();
	// });

	$(".edit").click(function() {
		var id = $(this).find('input').val();
		$('.saisie_'+id).show();
		$('.checkbox_'+id).hide();
		console.log(id);
		$('.form_'+id).focus();
	});

	$('.select-tous_actif input[type="checkbox"]').change(function() {
		var nb_commande = $('input[name="nb_commande"]').val();

		for (var i = 0; i < nb_commande; i++) {
			var is = 'id_cmde_' + i;
			var id = $("input[name=" + is + "]").val();

			if($(this).is(":checked")) {
				var qte = '.qte_' + id;
				var qte_livree = $(qte).val();
				var value = '.qte_livree_' + id;
				$(value).val(qte_livree);
			}
			else {
				var value = '.qte_livree_' + id;
				$(value).val("0");
			}
		}
  	});



	$('.select-tous_actif input[type="checkbox"]').change(function() {
	    if ($(this).is(":checked")) {
		  $('.value').val('1');
		  $('input:checkbox[value="1"]').attr('checked', true);
	    } else {
		  $(this).val('0');
		  $('.value').val('0');
		  $('input:checkbox[value="0"]').attr('checked', false);
	  }
  	});
});

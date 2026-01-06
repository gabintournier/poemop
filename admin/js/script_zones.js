$(document).ready(function() {

	$(".zone").dblclick("tr", function() {
		var value = $(this).find('input').val();
		window.location.href = '/admin/definition_zone.php?id_zone='+value;
	});

	$(".edit").click("td", function() {
		$('.ajouter').hide();
		$('.modifier').show();
		$('.remove-nom').fadeIn();
		$('.remove-option').fadeIn();

		var cols = $(this).find("input");
		$('.select_' + cols[0].defaultValue).addClass('selected').siblings().removeClass('selected');
		$('#id_zone_edit').val(cols[0].defaultValue);
		$('#nom_zone').val(cols[1].defaultValue);
		$('#option_zone').val(cols[2].defaultValue);
	});


	$(".remove-nom").click(function() {
		$("#nom_zone").val('');
		$('.remove-nom').fadeOut();
		if(!$('#option_zone').val()) {
			$('.modifier').hide();
			$('.remove-option').fadeOut();
			$('.ajouter').show();
		}
	});
	$(".remove-option").click(function() {
		$("#option_zone").val('');
		$('.remove-option').fadeOut();
		if(!$('#nom_zone').val()) {
			$('.modifier').hide();
			$('.ajouter').show();
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

	$('.select-tous_cocher input[type="checkbox"]').change(function() {
	    if ($(this).is(":checked")) {
		  $('.value').val('1');
		  $('#select_grp[value="1"]').attr('checked', true);
	    } else {
		  $(this).val('0');
		  $('.value').val('0');
		  $('#select_grp[value="0"]').attr('checked', false);
	  }
  	});

	$('#zone_table').dataTable({
		"paging":   true,
		"pageLength": 10,
		"info":   false,
		"searching":   true,
	});

});

$(document).ready(function() {
	$(".fournisseur").dblclick("tr", function() {
		var value = $(this).find('input').val();
		window.location.href = '/admin/details_fournisseur.php?id_four='+value;
	});

	$(".contact").click("tr", function() {
		$(this).addClass('selected').siblings().removeClass('selected');;
		var cols = $(this).find("td");
		var id = $(this).find("input");
		$('#id_contact').val(id[0].defaultValue);
		$('#nom_contact').val(cols[0].outerText);
		$('#prenom_contact').val(cols[1].outerText).trigger('change');
		$('#tel_contact').val(cols[2].outerText);
		$('#mail_contact').val(cols[3].outerText);
		$('#fonction_contact').val(cols[4].outerText);
		$('#com_contact').val(cols[5].outerText);
		$("input[name='ajouter_contact']").hide();
		$("input[name='modifier_contact']").show();
		$(".add").hide();
		$(".upd").show();
	});

	

	$('.vider-form').click(function() {
		$("input[name='ajouter_contact']").show();
		$("input[name='modifier_contact']").hide();
		$(".add").show();
		$(".upd").hide();
		$('#id_contact').val('');
		$('#nom_contact').val('');
		$('#prenom_contact').val('');
		$('#tel_contact').val('');
		$('#mail_contact').val('');
		$('#fonction_contact').val('');
		$('#com_contact').val('');
	})
});

$(document).ready(function() {
	$(".disable").attr('disabled','disabled');
	$(".avis").click('tr', function() {
		var cols = $(this).find("td");
		var text = $(this).find("input[name='message_avis']").val();
		var text_r = $(this).find("input[name='reponse_avis']").val();
		var user = $(this).find("input[name='user']").val();
		var valide = cols[6].innerText;
		// test = text.replace(/\\/g,'"');

		console.log(text);
		$('#id_cmde').val(cols[0].innerText);
		$('#signature').val(cols[1].innerText);
		$('#date_clients').val(cols[2].innerText);
		$('#message').val(text);
		$('#reponse').val(text_r);
		$('#user_id').val(user);
		$(".disable").removeAttr('disabled');
		if (valide == "2") {
			$('#censurer_message').prop('checked', true);
		}
	});

	$(".groupement_commande").click("tr", function() {
		var cols = $(this).find("td");
		var id = $(this).find("input");
		$(this).addClass('selected').siblings().removeClass('selected');
		console.log(id);
		$('#id_grp').val(id[0].defaultValue);
		// $('#add_id_four').val(id[0].defaultValue);
		// console.log(cols);
		// $('#four_grpt').val(cols[0].innerText);
	});

	var loc = window.location.href;
	var searchParams = new URLSearchParams(window.location.search);

	searchParams.has('user_id');
	var user_id = searchParams.get('user_id');

	searchParams.has('id_cmd');
	var id_cmd = searchParams.get('id_cmd');

	searchParams.has('user_id_1');
	var user_id_1 = searchParams.get('user_id_1');

	searchParams.has('user_id_2');
	var user_id_2 = searchParams.get('user_id_2');

	searchParams.has('id_sel');
	var id_sel = searchParams.get('id_sel');

	searchParams.has('mail');
	var mail = searchParams.get('mail');

	$(document).ready(function () {
	    const baseUrl = window.location.origin; // ex: https://plus-on-est-moins-on-paie.fr
	    const loc = window.location.href;
		
	    // --------------------- Définition des règles de modals
	    const modalRules = [
	        // Fusionner clients
	        { pattern: baseUrl + "/admin/fusionner_clients.php?popup1=oui", modal: "#ChargerClient1" },
	        { pattern: baseUrl + "/admin/fusionner_clients.php?popup2=oui", modal: "#ChargerClient2" },
	        { pattern: baseUrl + "/admin/fusionner_clients.php?user_id_1=" + user_id_1 + "&popup1=oui", modal: "#ChargerClient1" },
	        { pattern: baseUrl + "/admin/fusionner_clients.php?user_id_1=" + user_id_1 + "&popup2=oui", modal: "#ChargerClient2" },
	        { pattern: baseUrl + "/admin/fusionner_clients.php?user_id_1=" + user_id_1 + "&user_id_2=" + user_id_2 + "&popup2=oui", modal: "#ChargerClient2" },
		
	        // Recherche client
	        { pattern: baseUrl + "/admin/recherche_client.php?generer_texte=oui", modal: "#genererTexte" },
	        { pattern: baseUrl + "/admin/recherche_client.php?mail_thunder=sel&id_sel=" + id_sel, modal: "#envoyerMailThunder" },
	        { pattern: baseUrl + "/admin/recherche_client.php?mail_thunder=sel&id_sel=" + id_sel + "&mail=" + mail, modal: "#envoyerMailThunder" },
	        { pattern: baseUrl + "/admin/recherche_client.php?popup_client=oui", modal: "#CreationClient" },
	        { pattern: baseUrl + "/admin/recherche_client.php?popup_client=oui&erreur=ok", modal: "#CreationClient" },
	        { pattern: baseUrl + "/admin/recherche_client.php?popup_client=oui&succes=ok", modal: "#CreationClient" },
		
	        // Popup regroupement
	        { pattern: baseUrl + "/admin/recherche_client.php?popup_grp=oui", modal: "#AjouterGrpt", redirect: baseUrl + "/admin/recherche_client.php" },
	        { pattern: baseUrl + "/admin/recherche_client.php?popup_grp=oui&message=commande", modal: "#AjouterGrpt", redirect: baseUrl + "/admin/recherche_client.php" },
		
	        // Popup SMS
	        { pattern: baseUrl + "/admin/gestion_client.php?user_id=" + user_id + "&id_cmd=" + id_cmd + '&return=cmdes&popup_sms=oui', modal: "#EnvoyerSms", redirect: baseUrl + "/admin/gestion_client.php?user_id=" + user_id + "&id_cmd=" + id_cmd + '&return=cmdes' },
	        { pattern: baseUrl + "/admin/gestion_client.php?id_cmd=" + id_cmd + '&return=grpt&popup_sms=oui', modal: "#EnvoyerSms", redirect: baseUrl + "/admin/gestion_client.php?id_cmd=" + id_cmd + "&return=grpt" },
	        { pattern: baseUrl + "/admin/gestion_client.php&popup_sms=oui", modal: "#EnvoyerSms", redirect: baseUrl + "/admin/gestion_client.php" },
	        { pattern: baseUrl + "/admin/gestion_client.php?user_id=" + user_id + "&return=recherche&popup_sms=oui", modal: "#EnvoyerSms", redirect: baseUrl + "/admin/gestion_client.php?user_id=" + user_id + "&return=recherche" },
	        { pattern: baseUrl + "/admin/gestion_client.php?user_id=" + user_id + "&return=accueil&popup_sms=oui", modal: "#EnvoyerSms", redirect: baseUrl + "/admin/gestion_client.php?user_id=" + user_id + "&return=accueil" },
	    ];
	
	    // --------------------- Affichage des modals
	    modalRules.forEach(rule => {
	        if (loc.startsWith(rule.pattern)) {
	            $(rule.modal).modal('show');
	            if (rule.redirect) {
	                $(".fermer-modal").click(function() {
	                    window.location.href = rule.redirect;
	                });
	            }
	        }
	    });
	
	    // --------------------- Boutons fermer pour fusionner / recherche
	    $(".b-close-user1").click(() => window.location.replace(baseUrl + "/admin/fusionner_clients.php?user_id_1=" + user_id_1));
	    $(".b-close").click(() => window.location.replace(baseUrl + "/admin/fusionner_clients.php"));
	    $(".b-close-c").click(() => window.location.replace(baseUrl + "/admin/recherche_client.php"));
	
	    // --------------------- Sélections
	    $(".regroupements").click("tr", function() {
	        var id = $(this).find("input");
	        $(this).addClass('selected').siblings().removeClass('selected');
	        $('#id_grp').val(id[0].defaultValue);
	    });
	
	    $(".clients").click("tr", function() {
	        var id = $(this).find("input");
	        $(this).addClass('selected').siblings().removeClass('selected');
	        $('#n_id_client1').val(id[0].defaultValue);
	        $('#n_id_client2').val(id[0].defaultValue);
	    });
	
	    // --------------------- Double click
	    $('.commande_id').dblclick('tr', function() {
	        var id = $(this).find('input').val();
	        window.location.href = baseUrl + '/admin/gestion_client_commande.php?id_cmd=' + id + '&return=avis';
	    });
	
	    $('.gestion_clients').dblclick('tr', function() {
	        var id = $(this).find('input').val();
	        window.location.href = baseUrl + '/admin/gestion_client.php?user_id=' + id + '&return=recherche';
	    });
	});

	//Modal Ajouter client grp

	// if (loc === "https://plus-on-est-moins-on-paie.fr/admin/gestion_client.php?user_id=" + user_id + "&id_cmd=" + id_cmd + '&return=cmdes&popup=oui') {
 	//    $('#AjouterGrpt').modal('show');
	//    $(".fermer-modal").click(function() {
   	// 		window.location.href = "https://plus-on-est-moins-on-paie.fr/admin/gestion_client.php?user_id=" + user_id + "&id_cmd=" + id_cmd + '&return=cmdes';
   	// 	});
    // }

	// if (loc === "https://plus-on-est-moins-on-paie.fr/admin/gestion_client.php?id_cmd=" + id_cmd + '&return=grpt&popup=oui') {
 	//    $('#AjouterGrpt').modal('show');
	//    $(".fermer-modal").click(function() {
   	// 		window.location.href = "https://plus-on-est-moins-on-paie.fr/admin/gestion_client.php?id_cmd=" + id_cmd + "&return=grpt";
   	// 	});
    // }
	// if (loc === "https://plus-on-est-moins-on-paie.fr/admin/gestion_client.php&popup=oui") {
 	//    $('#AjouterGrpt').modal('show');
	//    $(".fermer-modal").click(function() {
   	// 		window.location.href = "https://plus-on-est-moins-on-paie.fr/admin/gestion_client.php";
   	// 	});
    // }
	// if (loc === "https://plus-on-est-moins-on-paie.fr/admin/gestion_client.php?user_id=" + user_id + "&return=recherche&popup=oui") {
 	//    $('#AjouterGrpt').modal('show');
	//    $(".fermer-modal").click(function() {
   	// 		window.location.href = "https://plus-on-est-moins-on-paie.fr/admin/gestion_client.php?user_id=" + user_id + "&return=recherche";
   	// 	});
    // }
	// if (loc === "https://plus-on-est-moins-on-paie.fr/admin/gestion_client.php?user_id=" + user_id + "&return=accueil&popup=oui") {
 	//    $('#AjouterGrpt').modal('show');
	//    $(".fermer-modal").click(function() {
   	// 		window.location.href = "https://plus-on-est-moins-on-paie.fr/admin/gestion_client.php?user_id=" + user_id + "&return=accueil";
   	// 	});
    // }

	//INSCRIPTIONS
	$("input[type=checkbox]").each(function(idx, elem) {
	   var id = $(this).val(); //On récupérer l'id de toutes les checkbox
	   // On affiche ou on cache "mes infos", "non abonnée" si la checkbox est true ou false
	   if ($('#energie_'+id).prop("checked") == true) {
		   $('.infos_'+id).show();
		   $('.abo_'+id).hide();
	   } else if ($('#energie_'+id).prop("checked") == false) {
		   $('.infos_'+id).hide();
		   $('.abo_'+id).show();
	   }
	});

});

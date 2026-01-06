$(document).ready(function() {
	$('input:checkbox[value="1"]').attr('checked', true);
	$('input:checkbox[value="0"]').attr('checked', false);

	jQuery('.message-close').click(function(e){
    	e.preventDefault();
    	var parent = $(this).parent('.toast');
    	parent.fadeOut("slow", function() { $(this).remove(); } );
  	});

	if ( window.history.replaceState ) {
        window.history.replaceState( null, null, window.location.href );
    }

	$('.det').click(function(){
		$('.toast').fadeOut();
	});

	var loc = window.location.href;
	var searchParams = new URLSearchParams(window.location.search);

	searchParams.has('id_four');
	var id_four = searchParams.get('id_four');

	searchParams.has('id_cmd');
	var id_cmd = searchParams.get('id_cmd');

	searchParams.has('cp');
	var cp = searchParams.get('cp');

	searchParams.has('id_grp');
	var id_grp = searchParams.get('id_grp');

	searchParams.has('id_zone');
	var id_zone = searchParams.get('id_zone');

	searchParams.has('user_id');
	var user_id = searchParams.get('user_id');

	searchParams.has('n_id_four');
	var n_id_four = searchParams.get('n_id_four');

	console.log(user_id);

	$(document).ready(function () {
    const baseUrl = window.location.origin; // => https://plus-on-est-moins-on-paie.fr ou https://dev.plus-on-est-moins-on-paie.fr
    const loc = window.location.href;

    const rules = [
        { pattern: baseUrl + "/admin/index.php", menu: "#dashboard" },
        { pattern: baseUrl + "/admin/liste_fournisseurs.php", menu: "#fournisseurs" },
        { pattern: baseUrl + "/admin/liste_commandes.php", menu: "#commandes" },
        { pattern: baseUrl + "/admin/details_fournisseur.php?id_four=" + id_four, menu: "#fournisseurs" },
        { pattern: baseUrl + "/admin/avis_clients.php", menu: "#avis" },
        { pattern: baseUrl + "/admin/clients_nouveaux_inscrits.php", menu: "#clients" },
        { pattern: baseUrl + "/admin/contact_fournisseur.php?id_four=" + id_four, menu: "#fournisseurs" },
        { pattern: baseUrl + "/admin/liste_regroupements.php", menu: "#regroupements" },
        { pattern: baseUrl + "/admin/gestion_client_commande.php?id_cmd=" + id_cmd + "&return=cmdes", menu: "#commandes" },
        { pattern: baseUrl + "/admin/gestion_client.php?user_id=" + user_id + "&id_cmd=" + id_cmd + "&return=cmdes", menu: "#commandes" },
        { pattern: baseUrl + "/admin/liste_fournisseurs.php?cp=" + cp, menu: "#fournisseurs" },

        // Ajouts que tu avais listés
        { pattern: baseUrl + "/admin/details_groupement.php?id_grp=" + id_grp, menu: "#regroupements" },
        { pattern: baseUrl + "/admin/plus_commande_groupement.php?id_grp=" + id_grp, menu: "#regroupements" },
        { pattern: baseUrl + "/admin/ajouter_fournisseur.php", menu: "#fournisseurs" },
        { pattern: baseUrl + "/admin/zones_fournisseur.php?id_four=" + id_four, menu: "#fournisseurs" },
        { pattern: baseUrl + "/admin/definition_zone.php?id_zone=" + id_zone, menu: "#fournisseurs" },
        { pattern: baseUrl + "/admin/recherche_client.php", menu: "#clients" },
        { pattern: baseUrl + "/admin/statistiques_commande.php", menu: "#commandes" },
        { pattern: baseUrl + "/admin/commande_par_departement.php", menu: "#commandes" },
        { pattern: baseUrl + "/admin/commande_par_fournisseur.php", menu: "#commandes" },
        { pattern: baseUrl + "/admin/fusionner_clients.php", menu: "#clients" },
        { pattern: baseUrl + "/admin/gestion_client.php", menu: "#clients" },
        { pattern: baseUrl + "/admin/gestion_client_commande.php", menu: "#clients" },
        { pattern: baseUrl + "/admin/gestion_client.php?id_cmd=" + id_cmd + "&return=grpt", menu: "#regroupements" },
        { pattern: baseUrl + "/admin/gestion_client_commande.php?id_cmd=" + id_cmd + "&return=grpt", menu: "#regroupements" },
        { pattern: baseUrl + "/admin/mail_type.php", menu: "#mails" },
        { pattern: baseUrl + "/admin/mail_modele.php", menu: "#mails" },
        { pattern: baseUrl + "/admin/envoyer_mail.php", menu: "#mails" },
        { pattern: baseUrl + "/admin/envoyer_sms.php", menu: "#mails" },
        { pattern: baseUrl + "/admin/param_sms.php", menu: "#mails" },
        { pattern: baseUrl + "/admin/ancienne_commande.php?user_id=" + user_id + "&id_cmd=" + id_cmd + "&return=cmdes", menu: "#commandes" },
        { pattern: baseUrl + "/admin/details_groupement.php?id_grp=" + id_grp + "&popup=oui", menu: "#regroupements" },
        { pattern: baseUrl + "/admin/details_groupement.php?id_grp=" + id_grp + "&n_id_four=" + n_id_four, menu: "#regroupements" },
        { pattern: baseUrl + "/admin/gestion_client.php?user_id=" + user_id + "&return=recherche", menu: "#clients" },
        { pattern: baseUrl + "/admin/gestion_client_commande.php?id_cmd=" + id_cmd + "&return=recherche", menu: "#clients" },
        { pattern: baseUrl + "/admin/ancienne_commande.php?user_id=" + user_id + "&return=recherche", menu: "#clients" },
        { pattern: baseUrl + "/admin/ancienne_commande.php?user_id=" + user_id + "&id_cmd=" + id_cmd + "&return=recherche", menu: "#clients" },
        { pattern: baseUrl + "/admin/liste_regroupements.php?id_four=" + id_four + "&return=fournisseur", menu: "#fournisseurs" },
        { pattern: baseUrl + "/admin/details_groupement.php?id_grp=" + id_grp + "&id_four=" + id_four + "&return=fournisseur", menu: "#fournisseurs" },
        { pattern: baseUrl + "/admin/gestion_client.php?user_id=" + user_id + "&return=accueil", menu: "#clients" },
        { pattern: baseUrl + "/admin/gestion_client.php?user_id=" + user_id + "&id_cmd=" + id_cmd + "&return=grpt", menu: "#regroupements" },
        { pattern: baseUrl + "/admin/ancienne_commande.php?user_id=" + user_id + "&id_cmd=" + id_cmd + "&return=grpt", menu: "#regroupements" },
        { pattern: baseUrl + "/admin/nouvelles_commandes.php", menu: "#nouvelles" },
        { pattern: baseUrl + "/admin/commandes_orphelines.php", menu: "#nouvelles" },
        { pattern: baseUrl + "/admin/groupement_possible.php", menu: "#nouvelles" },
        { pattern: baseUrl + "/admin/plusieurs_groupement_possible.php", menu: "#nouvelles" },
    ];

    rules.forEach(rule => {
        if (loc.startsWith(rule.pattern)) {
            $(rule.menu).addClass("active");
        }
    });
});

	$(".refresh").click(function() {
        location.reload(true);
    });

	$('.stats').slider();
	$("#right_t").click(function() {
		$("#total_clients").fadeOut();
		$("#clients_tel").show("slide", {direction: "right"}, 1000);
		$('#right_t').attr('id', 'right_tel');

		$("#right_tel").click(function() {
			$("#clients_tel").fadeOut();
			$("#clients_int").show("slide", {direction: "right"}, 1000);
			$('#right_tel').attr('id', 'right_int');

			$("#right_int").click(function() {
				$("#clients_int").fadeOut();
				$("#clients_coord").show("slide", {direction: "right"}, 1000);
				$('#right_int').attr('id', 'right_coord');

				$("#right_coord").click(function() {
					$("#clients_coord").fadeOut();
					$("#clients_actifs").show("slide", {direction: "right"}, 1000);
					$('#right_coord').attr('id', 'right_actifs');

					$("#right").click(function() {
						console.log('ok');
					});

					$("#right_actifs").click(function() {
						$('#right_actifs').attr('id', 'right');
						$("#clients_actifs").fadeOut();
						$("#clients_inactifs").show("slide", {direction: "right"}, 1000);
						$('.no-slide').removeClass('no-slide');
						$('.slides').addClass('no-slide');
					});

				});
			});
		});

	});
	$("#left_t").click(function() {
		$("#clients_inactifs").fadeOut();
		$("#total_clients").show("slide", {direction: "left"}, 1000);
		$('#left').addClass('no-slide');
		$('#right').removeClass('no-slide');
		$('#right_actifs').attr('id', 'right');
	});

	//Trier colonne tableau
	$('#trie_table').dataTable({
		"paging":   false,
		"info":   false,
		"searching":   false,
		order:[[3,'desc']],
		columnDefs: [{ 'targets': 3, type: 'date-eu' }],
	});

	$('#trie_table_grp2').dataTable({
		"paging":   false,
		"info":   false,
		"searching":   false,
		order:[[3,'asc']],
		columnDefs: [{ 'targets': 3, type: 'date-eu' }],
	});

	$('#trie_table_client').dataTable({
		"paging":   false,
		"info":   false,
		"searching":   false,
		// order:[[0,'asc']],
		// columnDefs: [{ 'targets': 3, type: 'date-eu' }],
	});

	$('#trie_table_client2').dataTable({
		"paging":   false,
		"info":   false,
		"searching":   false,
		order:[[0,'asc']],
		// columnDefs: [{ 'targets': 3, type: 'date-eu' }],
	});

	$('#trie_table_four').dataTable({
		"paging":   false,
		"info":   false,
		"searching":   false,
		columnDefs: [{ 'targets': 2, type: 'date-eu' }],
	});

	$('#trie_table_grp').dataTable({
		"paging":   false,
		"info":   false,
		"searching":   false,
		order:[[4,'asc']],
		columnDefs: [{ 'targets': 4, type: 'date-eu' }],
	});

	$('#trie_table_affecter').dataTable({
		"paging":   false,
		"info":   false,
		"searching":   false,
		order:[[9,'desc']],
		columnDefs: [{ 'targets': 3, type: 'date-eu' }],
	});

	var sum_sup = 0;
    $('.nb_s').each(function() {
        sum_sup += Number($(this).text());
    });
	$('.vol_sup').val(sum_sup);

	var sum_ord = 0;
    $('.nb_o').each(function() {
        sum_ord += Number($(this).text());
    });
	$('.vol_ord').val(sum_ord);
});

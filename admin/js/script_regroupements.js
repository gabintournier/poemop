$(document).ready(function() {
    var loc = window.location.href;
    var searchParams = new URLSearchParams(window.location.search);
    var id_grp = searchParams.get('id_grp');
    var n_id_cmd = searchParams.get('n_id_cmd');

    // Remplace toutes les conditions strictes par includes
    if (loc.includes('details_groupement.php') && loc.includes('popup=oui')) {
        $('#selFour').modal('show');
        $(".b-close").click(function() {
            url = "details_groupement.php?id_grp=" + id_grp;
            window.location.replace(url);
        });
    }

    if (loc.includes('details_groupement.php') && loc.includes('popup_c=oui')) {
        $('#selCom').modal('show');
        $(".b-close").click(function() {
            url = "details_groupement.php?id_grp=" + id_grp;
            window.location.replace(url);
        });
    }

    if (loc.includes('details_groupement.php') && loc.includes('n_id_cmd=' + n_id_cmd)) {
        $('#selCom').modal('show');
        $(".b-close").click(function() {
            url = "details_groupement.php?id_grp=" + id_grp;
            window.location.replace(url);
        });
    }

    if (loc.includes('details_groupement.php') && loc.includes('utilisateur=oui') && !loc.includes('message=grpt')) {
        $('#rechercheClient').modal('show');
        $(".b-close").click(function() {
            url = "details_groupement.php?id_grp=" + id_grp;
            window.location.replace(url);
        });
    }

    if (loc.includes('details_groupement.php') && loc.includes('utilisateur=oui') && loc.includes('message=grpt')) {
        $('#rechercheClient').modal('show');
        $(".b-close").click(function() {
            url = "details_groupement.php?id_grp=" + id_grp;
            window.location.replace(url);
        });
    }

    if (loc.includes('details_groupement.php') && loc.includes('popup_mail=oui')) {
        $('#selMail').modal('show');
        $(".b-close").click(function() {
            url = "details_groupement.php?id_grp=" + id_grp;
            window.location.replace(url);
        });
    }

    if (loc.includes('details_groupement.php') && loc.includes('generer_texte=oui')) {
        $('#genererTexte').modal('show');
        $(".b-close").click(function() {
            url = "details_groupement.php?id_grp=" + id_grp;
            window.location.replace(url);
        });
    }

    if (loc.includes('details_groupement.php') && loc.includes('popup_pmp=oui')) {
        $('#selPmp').modal('show');
        $(".b-close").click(function() {
            url = "details_groupement.php?id_grp=" + id_grp;
            window.location.replace(url);
        });
    }

    if (loc.includes('details_groupement.php') && (loc.includes('charger_client=oui') || loc.includes('charger_client=ok'))) {
        $('#ChargerClient').modal('show');
        $(".b-close").click(function() {
            url = "details_groupement.php?id_grp=" + id_grp;
            window.location.replace(url);
        });
    }

    if (loc.includes('details_groupement.php') && loc.includes('popup_sms=oui')) {
        $('#selSms').modal('show');
        $(".b-close").click(function() {
            url = "details_groupement.php?id_grp=" + id_grp;
            window.location.replace(url);
        });
    }

    if (loc.includes('details_groupement.php') && loc.includes('definir_zone=oui')) {
        $('#definirZone').modal('show');
        $(".b-close").click(function() {
            url = "details_groupement.php?id_grp=" + id_grp;
            window.location.replace(url);
        });
    }

    if (loc.includes('details_groupement.php') && loc.includes('verification=oui')) {
        $('#VerifCommandes').modal('show');
        $(".b-close").click(function() {
            url = "details_groupement.php?id_grp=" + id_grp;
            window.location.replace(url);
        });
    }

    if (loc.includes('details_groupement.php') && loc.includes('historique=oui')) {
        $('#Histo').modal('show');
        $(".b-close").click(function() {
            url = "details_groupement.php?id_grp=" + id_grp;
            window.location.replace(url);
        });
    }

    $('#AjouterCommande').modal('show');

	$(".commande").dblclick("tr", function() {
		var value = $(this).find('input').val();
		window.location.href = '/admin/gestion_client_commande.php?id_cmd='+value+'&return=grpt';
	});
	var test = $(".nb_commande").val();
	$(".nb_commande_plages").val(test);
	console.log(test);

	$(".fournisseur").click("tr", function() {
		var cols = $(this).find("td");
		var id = $(this).find("input");
		var comord = $(this).find(".comord");
		var comsup = $(this).find(".comsup");
		var info_four_sel = $(this).find(".info_four_sel");

		$(this).addClass('selected').siblings().removeClass('selected');
		$('#n_id_four').val(id[0].defaultValue);
		$('#add_id_four').val(id[0].defaultValue);
		$('#four_grpt').val(cols[0].innerText);
		$('.comordsel').val(comord[0].defaultValue + ",000");
		$('.comsupsel').val(comsup[0].defaultValue + ",000");
		$('.info_four').text(info_four_sel[0].defaultValue);

	});

	$(".ligne_plages").click("tr", function() {
		var id = $(this).find("input");
		$(this).addClass('selected').siblings().removeClass('selected');
		$(".id_select_plages_prix").val(id[0].defaultValue);
	});

	$(".gestion_clients").click("tr", function() {
		var id = $(this).find("input");
		$(this).addClass('selected').siblings().removeClass('selected');
		$('#user_id_client').val(id[0].defaultValue);

	});

	$(".commande").click("tr", function() {
		var id = $(this).find("input");
		$(this).addClass('selected').siblings().removeClass('selected');
		$('#n_id_cmd').val(id[0].defaultValue);
		$('#id_cmdes_supp').val(id[0].defaultValue);
		var cmdes = $(this).find("td");
		$('.cmdes_supp').text("Commande : " + cmdes[0].innerText + " " + cmdes[1].innerText + " - CP : " + cmdes[3].innerText + " - Qté : " + cmdes[6].innerText );
	});

	$(".grp_zone").click("tr", function() {
		var id = $(this).find("input");
		$(this).addClass('selected').siblings().removeClass('selected');
		$('.supp_grp_zone_id').val(id[0].defaultValue);
	});

	$(".grp_zone2").click("tr", function() {
		var id = $(this).find("input");
		$(this).addClass('selected').siblings().removeClass('selected');
		$('.supp_grp_zone_id_exclus').val(id[0].defaultValue);
	});

	$(".valider_four").click(function() {
		if(loc.includes('details_groupement.php') && loc.includes('popup=oui')) {
			id = $('#n_id_four').val();
			window.location.href = "details_groupement.php?id_grp=" + id_grp + "&n_id_four=" + id;
		}
	});

	$(".valider_cmd").click(function() {
		id = $('#n_id_cmd').val();
		window.location.href = "details_groupement.php?id_grp=" + id_grp + "&n_id_cmd=" + id;
	});

	$('.commande_add_grp').each(function() {
		var sum_litre = 0
		$(this).find('tr').each(function() {
			$(this).find('.nb_l').each(function() {
				var litre_qte = $(this).text();
				if (!isNaN(litre_qte) && litre_qte.length !== 0) {
					sum_litre += parseFloat(litre_qte);
				}
			});
		});
		$('#nb_litres').val(sum_litre);
	});

	$('#trie_table_cmd').dataTable({
		"paging":   false,
		"info":   false,
		"searching":   false,
		// columnDefs: [{ 'targets': 6, type: 'date-eu' }],
	});

	$('#trie_table_cmd_charger').dataTable({
		"paging":   false,
		"info":   false,
		"searching":   false,
		columnDefs: [{ 'targets': 6, type: 'date-eu' }],
	});

	$('.fournisseur-zone').on('change', function() {
		$('.fournisseur_id').val(this.value);
		$('#FormID').submit();
	});

	$('.fournisseur-zone2').on('change', function() {
		$('.fournisseur_id_2').val(this.value);
		$('#FormID').submit();
	});

	$('.select-tous_basculer input[type="checkbox"]').change(function() {
		const checked = $(this).is(':checked');
		$('.value').each(function() {
			$(this).val(checked ? '1' : '0');
			$(this).prop('checked', checked);
		});
	});

	// Avant soumission de la modale "Commandes déjà dans un groupement"
	$('#AjouterCommande').closest('form').on('submit', function() {
		const form = $(this);
		// force tous_basculer à 1 si la case globale est cochée
		const tousChk = form.find('.select-tous_basculer input[type="checkbox"]');
		if (tousChk.length && tousChk.is(':checked')) {
			tousChk.val('1');
		}
		// s'assurer que le back voit bien l'action basculer_commande
		if (form.find('input[name="basculer_commande"]').length === 0) {
			form.append('<input type="hidden" name="basculer_commande" value="1">');
		}
		// agrège les IDs des commandes de la modale dans id_basculer
		const ids = [];
		form.find('input[name^="cmde_id_grp_"]').each(function() {
			if ($(this).val()) {
				ids.push($(this).val());
			}
		});
		form.find('input[name="id_basculer"]').val(ids.join(';'));
	});

	// Debug formulaire pour "ajouter liste groupement"
	$('form').on('submit', function() {
		const ids = $('input[name="ids_cmd"]').val();
		const basculerChecked = $('input[type="checkbox"][name^="basculer_commande_"]:checked')
			.map(function() { return this.name; })
			.get();
		console.log('DEBUG ajouter_liste_groupement', { ids_cmd: ids, basculer_checked: basculerChecked });
	});

	// $('.nouveau_statut').on('change', function() {
	// 	var selectedText = $('.nouveau_statut').val(this.value);
	// 	console.log(selectedText);
	// });
	//
	// $('select[name="nouveau_statut"]').change(function() {
	// 	var selectedText = $(this).find("option:selected").text();
	// 	console.log(selectedText);
	// });

});

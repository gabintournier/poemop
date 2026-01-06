$(document).ready(function() {
    const baseUrl = window.location.origin;
    const loc = window.location.href;
    const searchParams = new URLSearchParams(window.location.search);

    const id_four = searchParams.get('id_four');

    // Double clic sur commandes
    $(".commande").on("dblclick", function() {
        const value = $(this).find('input').val();
        window.location.href = baseUrl + '/admin/gestion_client_commande.php?id_cmd=' + value + '&return=cmdes';
    });

    // Bouton fermer
    $(".b-close").click(() => window.location.replace(baseUrl + "/admin/liste_commandes.php"));

    // Popup selGrp
    if (loc.startsWith(baseUrl + "/admin/liste_commandes.php?popup=oui")) {
        $('#selGrp').modal('show');
    }
    
    // Double clic sur regroupements
    $(".regroupements").on("dblclick", function() {
        const value = $(this).find('input').val();
        if (loc.startsWith(baseUrl + "/admin/liste_regroupements.php?id_four=" + id_four + "&return=fournisseur")) {
            window.location.href = baseUrl + '/admin/details_groupement.php?id_grp=' + value + '&id_four=' + id_four + '&return=fournisseur';
        } else {
            window.location.href = baseUrl + '/admin/details_groupement.php?id_grp=' + value;
        }
    });

    // Sélection de groupement commande
    $(".groupement_commande").on("click", function() {
        const id = $(this).find("input").val();
        $(this).addClass('selected').siblings().removeClass('selected');
        $('#n_id_grp').val(id);
    });

    // Sélection de regroupements
    $(".regroupements").on("click", function() {
        const id = $(this).find("input").val();
        const grp = $(this).find("td");
        $(this).addClass('selected').siblings().removeClass('selected');
        $('#id_grp_duplique').val(id);
        $('.nom_grp').text(grp[1].innerText + " - " + grp[3].innerText + " au status " + grp[2].innerText);
    });

    // select2
    $('.js-example-basic-single').select2();

    // Somme des litres
    $('.table').each(function() {
        let sum_litre = 0;
        $(this).find('.nb_l').each(function() {
            sum_litre += parseFloat($(this).text()) || 0;
        });
        $('#nb_litres').val(sum_litre);
    });

    // Statut
    $('.statutSel').text("5 - Prévu");
    $('select[name="nouveau_statut"]').change(function() {
        const selectedText = $(this).find("option:selected").text();
        console.log(selectedText);
        $('.statutSel').text(selectedText);
    });
});

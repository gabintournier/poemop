$(document).ready(function() {
	if ( window.history.replaceState ) {
        window.history.replaceState( null, null, window.location.href );
    }

	$(".refresh").click(function() {
        location.reload(true);
    });

	jQuery('.message-close').click(function(e){
    	e.preventDefault();
    	var parent = $(this).parent('.toast');
    	parent.fadeOut("slow", function() { $(this).remove(); } );
  	});

	var loc = window.location.href;
	var searchParams = new URLSearchParams(window.location.search);

	searchParams.has('id_crypte');
	var id_crypte = searchParams.get('id_crypte');

	searchParams.has('id_grp');
	var id_grp = searchParams.get('id_grp');

	searchParams.has('id_zone');
	var id_zone = searchParams.get('id_zone');

	if (loc === "https://plus-on-est-moins-on-paie.fr/admin-four/index.php?id_crypte=" + id_crypte) {
	   $('#dashboard').addClass('active');
   	} else if (loc === "https://plus-on-est-moins-on-paie.fr/admin-four/liste_regroupements.php?id_crypte=" + id_crypte) {
	   $('#groupements').addClass('active');
   	} else if (loc === "https://plus-on-est-moins-on-paie.fr/admin-four/details_groupement.php?id_crypte=" + id_crypte + "&id_grp=" + id_grp + "&return=grp") {
	   $('#groupements').addClass('active');
   	} else if (loc === "https://plus-on-est-moins-on-paie.fr/admin-four/liste_zones_cotation.php?id_crypte=" + id_crypte) {
	   $('#cotations').addClass('active');
   	} else if (loc === "https://plus-on-est-moins-on-paie.fr/admin-four/zone_cotations.php?id_crypte=" + id_crypte + "&id_zone=" + id_zone + "&return=zone_cot") {
	   $('#cotations').addClass('active');
   	} else if (loc === "https://plus-on-est-moins-on-paie.fr/admin-four/liste_groupements_recap.php?id_crypte=" + id_crypte) {
	   $('#recap').addClass('active');
    } else if (loc === "https://plus-on-est-moins-on-paie.fr/admin-four/saisie_recap.php?id_crypte=" + id_crypte + "&id_grp=" + id_grp + "&return=recap") {
	   $('#recap').addClass('active');
   	} else if (loc === "https://plus-on-est-moins-on-paie.fr/admin-four/liste_regroupements_termine.php?id_crypte=" + id_crypte) {
	   $('#termines').addClass('active');
   	} else if (loc === "https://plus-on-est-moins-on-paie.fr/admin-four/details_groupement_termine.php?id_crypte=" + id_crypte + "&id_grp=" + id_grp + "&return=termines") {
	   $('#termines').addClass('active');
   	} else if (loc === "https://plus-on-est-moins-on-paie.fr/admin-four/saisie_recap.php?id_crypte=" + id_crypte + "&id_grp=" + id_grp + "&return=recap&qte=saisie") {
	   $('#recap').addClass('active');
   	} else if (loc === "https://plus-on-est-moins-on-paie.fr/admin-four/zone_cotations.php?id_crypte=" + id_crypte + "&id_zone=" + id_zone) {
	   $('#cotations').addClass('active');
   	}
});

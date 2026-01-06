$(document).ready(function() {
	if ( window.history.replaceState ) {
        window.history.replaceState( null, null, window.location.href );
    }
	$(".grey").click(function() {
		$('.coord-ko').fadeIn();
		console.log('ko');
	});
	// $("#login-bloc").css("display", "none");

	// $('html').on('DOMMouseScroll mousewheel', function (e) {
	//   if(e.originalEvent.detail > 0 || e.originalEvent.wheelDelta < 0) { //alternative options for wheelData: wheelDeltaX & wheelDeltaY
	//     //scroll down
	//     $( "#header-nav" ).addClass( "hide-nav-bar" );
	//   } else {
	//     //scroll up
	//     $( "#header-nav" ).removeClass( "hide-nav-bar" );
	//   }
	// });

	var loc = window.location.href;


	if (loc === "https://plus-on-est-moins-on-paie.fr/ma_commande.php?refuser_tarif=ok") {
		jQuery('.info').attr('style', 'display: none !important');
	}

	jQuery('.message-close').click(function(e){
    	e.preventDefault();
    	var parent = $(this).parent('.toast');
    	parent.fadeOut("slow", function() { $(this).remove(); } );
  	});

	$('#show_formulaire').click(function () {
		jQuery('.statut-commande').fadeOut();
		jQuery('.coordonnees').fadeOut();
		jQuery('.refus_tarif').fadeIn();
	});

	// $('.link-login a').click(function() {
	// 	$('#login-bloc').fadeIn();
	// 	$('#registration-bloc').css('display', 'none');
	// });
	// $('.link-signup a').click(function() {
	// 	$('#registration-bloc').fadeIn();
	// 	$('#login-bloc').css('display', 'none');
	// });
	//
	// if($(".connecte").css("display", "block")) {
	// 	$('#registration-bloc').css('display', 'none');
	// };
});

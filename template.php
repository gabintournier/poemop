<?php
// error_reporting(E_ALL);
// ini_set("display_errors", 1);

include_once __DIR__ . "/inc/pmp_co_connect.php";
include_once __DIR__ . "/inc/pmp_inc_fonctions_connexion.php";
?>
<!DOCTYPE html>
<html lang="fr" dir="ltr">
	<head>
		<!-- Tarteaucitron Init -->
		<script src="/tarteaucitron/tarteaucitron.min.js"></script>
		<script>
			tarteaucitron.init({
			  "privacyUrl": "/cookies_poemop.php",                 /* Url de la politique de confidentialité */
			  "bodyPosition": "top",                               /* Place le bandeau en tout début de <body> */
			  "hashtag": "#tarteaucitron",                        /* Hashtag pour ouvrir le panneau */
			  "cookieName": "tarteaucitron",                      /* Nom du cookie (lettres/chiffres) */
			  "orientation": "bottom",                            /* Position du bandeau */
			  "groupServices": true,                               /* Grouper par catégorie */
			  "showDetailsOnClick": true,                          /* Ouvrir la description au clic */
			  "serviceDefaultState": "wait",                      /* Etat par défaut */
			  "showAlertSmall": false,                             /* Pas de mini-bandeau */
			  "cookieslist": false,                                /* Pas de liste mini */
			  "cookieslistEmbed": false,                           /* Pas de liste dans le panneau */
			  "closePopup": true,                                  /* Afficher le X */
			  "showIcon": true,                                    /* Icône d'ouverture panel */
			  "iconPosition": "BottomRight",                      /* Position de l'icône */
			  "adblocker": false,                                  /* Alerte adblocker */
			  "DenyAllCta" : true,                                 /* Bouton Tout refuser */
			  "AcceptAllCta" : true,                               /* Bouton Tout accepter */
			  "highPrivacy": true,                                 /* Attendre le consentement */
			  "alwaysNeedConsent": false,                          /* Pas pour services privacy by design */
			  "handleBrowserDNTRequest": false,                    /* DNT navigateur */
			  "removeCredit": false,                               /* Lien de crédit */
			  "moreInfoLink": true,                                /* Lien En savoir plus */
			  "useExternalCss": true,                              /* On gère le CSS via nos <link> */
			  //"cookieDomain": ".my-multisite-domaine.fr",
			  "readmoreLink": "/cookies_poemop.php",              /* Lien En savoir plus */
			  "mandatory": true,                                   /* Message cookies obligatoires */
			  "mandatoryCta": false,                               /* Pas de CTA pour obligatoires */
			  // a11y: "customCloserId": "",
			  "googleConsentMode": true,                           /* Google Consent Mode v2 */
			  "bingConsentMode": true,                             /* Bing Consent Mode */
			  "softConsentMode": false,                            /* Consentement requis avant chargement */
			  "dataLayer": false,                                  /* Pas d'événement dataLayer */
			  "serverSide": false,                                 /* Pas de mode server-side */
			  "partnersList": true                                 /* Afficher nb partenaires */
			});
		</script>
		
		<!-- Tarteaucitron Analytics (Adwords)-->
		<script>
			tarteaucitron.user.gtagUa = 'UA-41241187-1';
			tarteaucitron.user.gtagMore = function () { /* add here your optionnal gtag() */ };
			(tarteaucitron.job = tarteaucitron.job || []).push('gtag');
		</script>

		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width">
		<meta name="description" content="<?= $desc ?>">
		<title><?= $title ?></title>
		<link rel="stylesheet" href="/css/fonts.css">
		<link rel="stylesheet" href="/css/style.css?<?php echo time(); ?>" media="screen">
		<link rel="stylesheet" href="/tarteaucitron/css/tarteaucitron.css?<?php echo time(); ?>" media="screen">
		<link rel="stylesheet" href="/css/tarteaucitron-poemop.css?<?php echo time(); ?>" media="screen">
		<link rel="stylesheet" href="/css/tarteaucitron-poemop-override.css?<?php echo time(); ?>" media="screen">
		<link rel="stylesheet" href="/css/responsive20230602092810.css" media="screen">
		<!-- <link rel="stylesheet" href="/css/mobile.css" media="screen and (max-width: 800px)"> -->
		<link rel="stylesheet" href="/bootstrap/5.0.1/css/bootstrap.min.css" media="screen">
		<link rel="stylesheet" href="/fontawesome-6.3.0/css/all.min.css">
		<link rel="stylesheet" href="/fontawesome-6.3.0/css/regular.min.css">
		<link rel="stylesheet" href="/fontawesome-6.3.0/css/fontawesome.min.css">
		<link rel="apple-touch-icon" sizes="57x57" href="/favicon/apple-icon-57x57.png">
		<link rel="apple-touch-icon" sizes="60x60" href="/favicon/apple-icon-60x60.png">
		<link rel="apple-touch-icon" sizes="72x72" href="/favicon/apple-icon-72x72.png">
		<link rel="apple-touch-icon" sizes="76x76" href="/favicon/apple-icon-76x76.png">
		<link rel="apple-touch-icon" sizes="114x114" href="/favicon/apple-icon-114x114.png">
		<link rel="apple-touch-icon" sizes="120x120" href="/favicon/apple-icon-120x120.png">
		<link rel="apple-touch-icon" sizes="144x144" href="/favicon/apple-icon-144x144.png">
		<link rel="apple-touch-icon" sizes="152x152" href="/favicon/apple-icon-152x152.png">
		<link rel="apple-touch-icon" sizes="180x180" href="/favicon/apple-icon-180x180.png">
		<link rel="icon" type="image/png" sizes="192x192"  href="/favicon/android-icon-192x192.png">
		<link rel="icon" type="image/png" sizes="32x32" href="/favicon/favicon-32x32.png">
		<link rel="icon" type="image/png" sizes="96x96" href="/favicon/favicon-96x96.png">
		<link rel="icon" type="image/png" sizes="16x16" href="/favicon/favicon-16x16.png">
		<!-- <link rel="manifest" href="manifest.json"> -->
		<meta name="msapplication-TileColor" content="#ffffff">
		<meta name="msapplication-TileImage" content="/favicon/ms-icon-144x144.png">
		<meta name="theme-color" content="#ffffff">
		<?php if (strpos($_SERVER['HTTP_HOST'], 'dev.') !== false) { ?>
		  <meta name="robots" content="noindex, nofollow">
		<?php } ?>
		<?php if (!empty($FORCE_BASE_ROOT)) { echo '<base href="/">'; } ?>
	</head>
	<body>
		<div class="content">
<?php
			include 'modules/back_to_top.php';
?>
			<div class="background">
				<?= $content; ?>
			</div>
<?php
			include 'modules/footer.php';
?>
		</div>

		<!-- Google adsense -->
		<script src="/js/jquery-3.6.0.min.js"></script>
		<script src="/js/popper.min.js"></script>
		<script src="/bootstrap/5.0.1/js/bootstrap.min.js"></script>
		<script src="/admin/js/javascript.util.min.js"></script>
		<script src="/js/back_to_top.js"></script>
		<script src="/js/menu.js"></script>
		<script src='/js/raphael-min.js'></script>
		<script src='/js/jquery.mapael.js'></script>
		<script src='/js/france_departments.js'></script>
		<script src='/js/appelcarte_groupement.js'></script>
		<script src="https://www.google.com/recaptcha/api.js" async defer></script>
		<script src="/js/notifications.js"></script>
	</body>
</html>

<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
$cacheBuster = time();
?>
<!DOCTYPE html>
<html lang="fr" dir="ltr">

<head>
	<meta charset="utf-8">
	<link rel="stylesheet" href="bootstrap/4.0.0/css/bootstrap.min.css">
	<link rel="stylesheet" href="../css/fonts.css">
	<link rel="stylesheet" href="css/styles.css?4" type="text/css" />
	<link rel="stylesheet" href="css/charger-client-notifications.css?cb=<?= $cacheBuster; ?>">
	<link rel="stylesheet" href="../fontawesome-6.3.0/css/all.min.css" />
	<link rel="stylesheet" href="../fontawesome-6.3.0/css/fontawesome.min.css" />
	<link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" />
	<link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/fontawesome.css" />
	<link rel="stylesheet" type="text/css" href="css/datatables.min.css" />
	<link rel="apple-touch-icon" sizes="57x57" href="../favicon/apple-icon-57x57.png">
	<link rel="apple-touch-icon" sizes="60x60" href="../favicon/apple-icon-60x60.png">
	<link rel="apple-touch-icon" sizes="72x72" href="../favicon/apple-icon-72x72.png">
	<link rel="apple-touch-icon" sizes="76x76" href="../favicon/apple-icon-76x76.png">
	<link rel="apple-touch-icon" sizes="114x114" href="../favicon/apple-icon-114x114.png">
	<link rel="apple-touch-icon" sizes="120x120" href="../favicon/apple-icon-120x120.png">
	<link rel="apple-touch-icon" sizes="144x144" href="../favicon/apple-icon-144x144.png">
	<link rel="apple-touch-icon" sizes="152x152" href="../favicon/apple-icon-152x152.png">
	<link rel="apple-touch-icon" sizes="180x180" href="../favicon/apple-icon-180x180.png">
	<link rel="icon" type="image/png" sizes="192x192" href="../favicon/android-icon-192x192.png">
	<link rel="icon" type="image/png" sizes="32x32" href="../favicon/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="96x96" href="../favicon/favicon-96x96.png">
	<link rel="icon" type="image/png" sizes="16x16" href="../favicon/favicon-16x16.png">
	<!-- <link rel="manifest" href="manifest.json"> -->
	<meta name="msapplication-TileColor" content="#ffffff">
	<meta name="msapplication-TileImage" content="../favicon/ms-icon-144x144.png">
	<meta name="theme-color" content="#ffffff">

	<style>
		/* User menu dropdown */
		.user-menu {
			position: relative;
			display: inline-block
		}

		.user-btn {
			background: transparent;
			border: none;
			color: #0f393a;
			font-weight: 600;
			cursor: pointer;
			display: inline-flex;
			align-items: center;
			gap: 8px
		}

		.user-btn:focus {
			outline: 2px solid rgba(255, 255, 255, .4);
			outline-offset: 2px
		}

		.user-dropdown {
			position: absolute;
			right: 0;
			top: 110%;
			min-width: 200px;
			background: #ffffff;
			border: 1px solid #e3e6ea;
			border-radius: 8px;
			box-shadow: 0 8px 20px rgba(0, 0, 0, .08);
			padding: 8px;
			z-index: 1000;
			display: none
		}

		.user-dropdown.show {
			display: block;
			animation: fadeIn .12s ease-out
		}

		.user-dropdown .dropdown-item {
			display: flex;
			align-items: center;
			gap: 8px;
			color: #0f393a;
			text-decoration: none;
			padding: 10px 12px;
			border-radius: 6px
		}

		.user-dropdown .dropdown-item:hover {
			background: #f5f7f9
		}

		@keyframes fadeIn {
			from {
				opacity: 0;
				transform: translateY(-4px)
			}

			to {
				opacity: 1;
				transform: translateY(0)
			}
		}
	</style>

	<script src="js/jquery-3.6.0.min.js"></script>
	<script src="js/jquery-ui.min.js"></script>
	<script src="/admin/js/charger_client_toast.js?cb=<?= $cacheBuster; ?>"></script>
	<script src="/admin/js/export_job_toast.js?cb=<?= $cacheBuster; ?>"></script>

	<title><?= $title; ?></title>
</head>

<body
	onload="document.getElementsByClassName('content')[0].style.display = 'block';document.getElementsByClassName('loader')[0].style.display = 'none';">
	<div class="test">
		<div class="row">
			<div class="col-sm-2">
				<div class="content-menu" style="width: 320px;">
					<div class="logo">
						<a href="index.php">
							<img src="images/logo-plus-on-est-moins-on-paie-blc.svg" alt="Appli POEMOP"
								style="width: 90%;">
						</a>
					</div>
					<hr class="separe-menu">
					<div class="sidenav" id="dashboard">
						<a href="index.php"><i class="far fa-chart-bar"></i>
							Tableau de bord
							<?php
							$host = $_SERVER['HTTP_HOST'];
							$isDev = str_contains($host, 'dev') || str_contains($host, 'localhost'); // adapte selon ton NDD dev
							if ($isDev) {
								echo " - <b style='color:#ff0000;'>DEV</b>";
							} ?>
						</a>
					</div>
					<div class="sidenav" id="fournisseurs">
						<a href="liste_fournisseurs.php"><i class="fal fa-id-card-alt"></i> Fournisseurs</a>
					</div>
					<div class="sidenav" id="commandes">
						<a href="liste_commandes.php"><i class="fal fa-list-ul"></i> Commandes</a>
					</div>
					<div class="sidenav" id="regroupements">
						<a href="liste_regroupements.php"><i class="fal fa-truck"></i> Regroupements</a>
					</div>
					<div class="sidenav" id="nouvelles">
						<a href="commandes_a_affecter.php"><i class="fal fa-cart-plus"></i> Commandes à affecter</a>
					</div>
					<div class="sidenav" id="clients">
						<a href="recherche_client.php"><i class="fal fa-user-edit"></i> Clients</a>
					</div>
					<div class="sidenav" id="avis">
						<a href="avis_clients.php"><i class="fal fa-heart"></i> Avis clients</a>
					</div>
					<div class="sidenav" id="mails">
						<a href="mail_modele.php"><i class="fal fa-envelope-open-text"></i> Mails & SMS</a>
					</div>
					<hr class="separe-menu">
					<div class="sidenav">
						<a href="../index.php"><i class="far fa-arrow-left"></i> Retour au site</a>
					</div>
				</div>
			</div>
			<div class="col-sm-10" style="padding-right:0;">
				<div class="menu-haut">
					<div class="row">
						<?php
						if (isset($return) == true) {
							?>
							<div class="col-sm-1 align-self-center return-bloc">
								<a href="<?php if (isset($link)) {
									echo $link;
								} ?>" class="return"><i class="fas fa-arrow-left"></i></a>
							</div>

							<?php
						}
						?>
						<div class="col-sm-7 align-self-center menu">
							<h1>
								<?php $host = $_SERVER['HTTP_HOST'];
								$isDev = str_contains($host, 'dev') || str_contains($host, 'localhost'); // adapte selon ton NDD dev
								if ($isDev) {
									echo $title_page . " - <b style='color:#ff0000;'>DEV</b>";
								} else {
									echo $title_page;
								}
								?>
							</h1>
							<div class="ligne-menu"></div>
						</div>
						<?php
						if (isset($button) == true) {
							?>
							<div class="col-sm-1 align-self-center bouton-ajouter ml-4">
								<a href="<?= $link_button; ?>" class="btn btn-outline-primary"><?php if (isset($icon)) {
									  echo $icon;
								  } ?>
									<?= $button_name; ?></a>
							</div>

							<?php
						}
						?>
						<div class="col-sm-2 align-self-center text-right">
							<span class="date-stat"><i class="far fa-clock"></i> <?= date("d M y"); ?></span>
						</div>
						<div class="col-sm-1 align-self-center text-right" style="position:relative;">
							<span class="refresh"><i class="fas fa-sync-alt"></i></span>
							<div id="chargerBell" class="charger-bell" title="Suivi chargement clients">
								<i class="far fa-bell"></i>
								<span class="charger-badge" id="chargerBadge"></span>
							</div>
							<div id="chargerPanel" class="charger-panel" aria-hidden="true">
								<div class="charger-panel-title">Chargement des clients</div>
								<div class="charger-panel-progress">
									<div class="charger-panel-progress-meta">
										<span id="chargerPanelStatus">Aucun traitement en cours.</span>
										<span class="charger-panel-progress-percent" id="chargerPanelPercent">0%</span>
									</div>
									<div class="charger-panel-progress-bar">
										<div class="charger-panel-progress-fill" id="chargerPanelProgressFill"></div>
									</div>
								</div>
								<button type="button" class="btn btn-sm btn-outline-primary"
									id="chargerPanelReopen">Afficher le chargement</button>
						<div class="charger-panel-actions">
							<div class="charger-notifs-title">
								Actions terminées
								<span class="charger-notifs-count" id="chargerPanelDoneCount">0</span>
							</div>
							<button type="button" id="chargerPanelClearAll" class="charger-panel-clear" disabled>Effacer tout</button>
						</div>
						<div id="chargerPanelNotifications" class="charger-panel-sub charger-notifs-empty">
							Aucune action terminée.</div>
							</div>
						</div>
						<div class="col-sm-2 align-self-center text-right">
							<div class="user-menu" aria-haspopup="true" aria-expanded="false">
								<button id="userMenuBtn" class="user-btn" aria-controls="userDropdown">
									<i class="far fa-user-circle"></i> <?= $_SESSION['user']; ?> <i
										class="far fa-chevron-down" aria-hidden="true"></i>
								</button>
								<div id="userDropdown" class="user-dropdown" role="menu" aria-hidden="true">
									<a class="dropdown-item" href="parametres.php"><i class="fas fa-cog"></i>
										Paramètres</a>
									<a class="dropdown-item" href="inc/pmp_inc_deconnexion.php"><i
											class="fas fa-sign-out-alt"></i> Déconnexion</a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="content" style="display:none;">
			<?= $content; ?>
		</div>
		<span class="loader" style="display:block;margin:170px auto 0 1000px;">
		</span>
	</div>
	<script src="/js/popper.min.js"></script>
	<script src="/bootstrap/5.0.1/js/bootstrap.min.js"></script>
	<script src="js/javascript.util.min.js"></script>
	<script src="js/datatables.min.js"></script>
	<script src="js/date-eu.js"></script>
	<script src="js/menu.js"></script>
	<div class="modal fade" id="confirmActionModal" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title confirm-action-title">Confirmer</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<p class="confirm-action-body mb-1">Confirmer cette action ?</p>
					<p class="confirm-action-detail text-muted mb-0" style="display:none;"></p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
					<button type="button" class="btn btn-primary confirm-action-yes">Oui, appliquer</button>
				</div>
			</div>
		</div>
	</div>
	<script src="js/confirm-actions.js"></script>
	<script src="js/charger-client-notifications.js?cb=<?= $cacheBuster; ?>"></script>
	<script>
		(function () {
			document.addEventListener('DOMContentLoaded', function () {
				const userMenuBtn = document.getElementById('userMenuBtn');
				const userDropdown = document.getElementById('userDropdown');
				if (!userMenuBtn || !userDropdown) {
					return;
				}

				const closeDropdown = function () {
					userDropdown.classList.remove('show');
					userDropdown.setAttribute('aria-hidden', 'true');
				};

				userMenuBtn.addEventListener('click', function (event) {
					event.preventDefault();
					event.stopPropagation();
					const nextState = !userDropdown.classList.contains('show');
					if (nextState) {
						userDropdown.classList.add('show');
						userDropdown.setAttribute('aria-hidden', 'false');
					} else {
						closeDropdown();
					}
				});

				document.addEventListener('click', function (event) {
					if (!userMenuBtn.contains(event.target) && !userDropdown.contains(event.target)) {
						closeDropdown();
					}
				});
			});
		})();
	</script>
	<script>
		(function () {
			if (typeof jQuery === "undefined") {
				return;
			}
			var $ = jQuery;
			$(function () {
				var $form = $("#FormID");
				if (!$form.length) {
					return;
				}
				var $actionField = $form.find('input[name="pmp_action"]').first();
				if (!$actionField.length) {
					$actionField = $('<input>', { type: "hidden", name: "pmp_action", value: "" });
					$form.prepend($actionField);
				}
				$form.on("click", 'input[type="submit"], button[type="submit"]', function () {
					var name = $(this).attr("name");
					if (name) {
						$actionField.val(name);
					}
				});
			});
		})();
	</script>

</body>

</html>

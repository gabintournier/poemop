<style media="screen">
	.ligne-menu {
		width: 26% !important;
	}

	.menu>h1,
	.ligne-menu {
		margin-left: 6%;
	}
</style>
<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

session_start();
if (!isset($_SESSION['user'])) {
	header('Location: /admin/connexion.php');
	die();
}

$title = 'Gestion d\'un client';
$title_page = 'Gestion d\'un client';
$return = true;
ob_start();




include_once __DIR__ . "/../inc/pmp_co_connect.php";
include_once __DIR__ . "/inc/pmp_inc_fonctions_commandes.php";
include_once __DIR__ . "/inc/pmp_inc_fonctions_clients.php";
unset($_SESSION['facture_saisie']);
$pmp_commande = GetCommandeClient($co_pmp, $_GET["user_id"]);
if (!empty($_POST["charger_cacher"])) {
	$cacher = isset($_POST["cacher_annulees"]) ? "1" : "0";
	if ($cacher == 1) {
		$res_cmdes = getAncienneCommande($co_pmp, $_GET["user_id"]);
	} else {
		$res_cmdes = getAncienneCommandeToutes($co_pmp, $_GET["user_id"]);
	}
} else {
	$res_cmdes = getAncienneCommande($co_pmp, $_GET["user_id"]);

}

if (isset($_GET["return"])) {
	if ($_GET["return"] == 'cmdes') {
		$link = '/admin/liste_commandes.php';
	} elseif ($_GET["return"] == 'avis') {
		$link = '/admin/avis_clients.php?get=avis';
	} elseif ($_GET["return"] == 'grpt') {
		$link = 'details_groupement.php?id_grp=' . $pmp_commande["groupe_cmd"];
	} elseif ($_GET["return"] == 'recherche') {
		$link = '/admin/recherche_client.php';
	}
} else {
	$link = '/admin/recherche_client.php';
}
?>
<div class="bloc">
	<?php
	if (isset($_GET["id_cmd"])) {
		if ($_GET["return"] == 'cmdes') {
			?>
			<div class="menu-bloc">
				<a
					href="gestion_client.php?id_cmd=<?= $_GET["id_cmd"]; ?>&user_id=<?= $_GET["user_id"] ?>&return=cmdes">Client</a>
				<a href="gestion_client_commande.php?id_cmd=<?= $_GET["id_cmd"]; ?>&return=cmdes">Commande</a>
				<a href="#" class="active">Anciennes commandes</a>
			</div>
			<?php
		} elseif ($_GET["return"] == 'grpt') {
			?>
			<div class="menu-bloc">
				<a
					href="gestion_client.php?id_cmd=<?= $_GET["id_cmd"]; ?>&user_id=<?= $_GET["user_id"] ?>&return=grpt">Client</a>
				<a href="gestion_client_commande.php?id_cmd=<?= $_GET["id_cmd"]; ?>&return=grpt">Commande</a>
				<a href="#" class="active">Anciennes commandes</a>
			</div>
			<?php
		} elseif ($_GET["return"] == 'recherche') {
			?>
			<div class="menu-bloc">
				<a href="clients_nouveaux_inscrits.php"><i class="fas fa-arrow-left" style="font-size: 14px;"></i></a>
				<a href="gestion_client.php?user_id=<?= $_GET["user_id"] ?>&return=recherche">Client</a>
				<a href="gestion_client_commande.php?id_cmd=<?= $_GET["id_cmd"]; ?>&return=recherche">Commande</a>
				<a href="#" class="active">Anciennes commandes</a>
			</div>
			<?php
		} elseif ($_GET["return"] == 'avis') {
			?>
			<div class="menu-bloc">
				<a href="avis_clients.php"><i class="fas fa-arrow-left" style="font-size: 14px;"></i></a>
				<a
					href="gestion_client.php?user_id=<?= $_GET["user_id"] ?>&id_cmd=<?= $_GET["id_cmd"]; ?>&return=avis">Client</a>
				<a href="gestion_client_commande.php?id_cmd=<?= $_GET["id_cmd"]; ?>&return=avis">Commande</a>
				<a href="#" class="active">Anciennes commandes</a>
			</div>
			<?php
		}
	} else {
		if ($_GET["return"] == 'recherche') {
			?>
			<div class="menu-bloc">
				<a href="clients_nouveaux_inscrits.php"><i class="fas fa-arrow-left" style="font-size: 14px;"></i></a>
				<a href="gestion_client.php?user_id=<?= $_GET["user_id"] ?>&return=recherche">Client</a>
				<?php
				if (isset($pmp_commande["id"])) {
					?>
					<a href="gestion_client_commande.php?id_cmd=<?= $pmp_commande["id"] ?>&return=recherche">Commande</a>
					<?php
				} else {
					?>
					<a href="" style="color: #0b242469;cursor: default;">Commande</a>
					<?php
				}
				?>
				<a href="#" class="active">Anciennes commandes</a>
			</div>
			<?php
		} else {
			?>
			<div class="menu-bloc">
				<a href="clients_nouveaux_inscrits.php"><i class="fas fa-arrow-left" style="font-size: 14px;"></i></a>
				<a href="gestion_client.php">Client</a>
				<a href="gestion_client_commande.php">Commande</a>
				<a href="#" class="active">Anciennes commandes</a>
			</div>
			<?php
		}
	}
	?>
	<form method="post">
		<div class="row">
			<div class="col-sm-2">
				<label for="cacher_annulees" class="col-form-label">
					<input type="checkbox" name="cacher_annulees" id="cacher_annulees" class="switch value check" <?php echo ((!isset($_POST['cacher_annulees']) && isset($_POST['charger_cacher'])) ? '' : 'checked="checked"'); ?>>
					Cacher annulées
				</label>

			</div>
			<div class="col-sm-6">
				<input type="submit" name="charger_cacher" class="btn btn-primary" value="CHARGER" style="width:20%">
			</div>
		</div>
		<div class="tableau">
			<table class="table">
				<thead>
					<tr>
						<th></th>
						<th>Date</th>
						<th>Qté</th>
						<th>Type</th>
						<th>Statut</th>
						<th>Prix Ord</th>
						<th>Prix Sup</th>
						<th>Grp</th>
						<th>Note</th>
						<th>Fournisseur - Commentaire</th>
					</tr>
				</thead>
				<tbody>
					<?php
					while ($cmd = mysqli_fetch_array($res_cmdes)) {

						$avis = getAvisCommande($co_pmp, $cmd["id_cmd"]);


						if ($cmd["cmd_status"] == 0) {
							$status = " 0 - Pas de commande";
						}
						if ($cmd["cmd_status"] == 10) {
							$status = " 10 - Utilisateur";
						}
						if ($cmd["cmd_status"] == 12) {
							$status = " 12 - Attaché";
						}
						if ($cmd["cmd_status"] == 13) {
							$status = " 13 - Proposé";
						}
						if ($cmd["cmd_status"] == 15) {
							$status = " 15 - Groupée";
						}
						if ($cmd["cmd_status"] == 17) {
							$status = " 17 - P. Proposé";
						}
						if ($cmd["cmd_status"] == 20) {
							$status = " 20 - Validé";
						}
						if ($cmd["cmd_status"] == 25) {
							$status = " 25 - Livrable";
						}
						if ($cmd["cmd_status"] == 30) {
							$status = " 30 - Livré";
						}
						if ($cmd["cmd_status"] == 40) {
							$status = " 40 - Terminée";
						}
						if ($cmd["cmd_status"] == 50) {
							$status = " 50 - Annulée";
						}
						if ($cmd["cmd_status"] == 52) {
							$status = " 52 - Annulée/livraison";
						}
						if ($cmd["cmd_status"] == 55) {
							$status = " 55 - Annulée/prix";
						}
						if ($cmd["cmd_status"] == 99) {
							$status = " 99 - Annulée/compte désactivé";
						}

						$prix_sup = $cmd["cmd_prix_sup"] / 1000;
						$prix_sup = number_format($prix_sup, 3, '.', '');

						$prix_ord = $cmd["cmd_prix_ord"] / 1000;
						$prix_ord = number_format($prix_ord, 3, '.', '');

						if ($cmd["cmd_typefuel"] == 1) {
							$type = 'O';
							$total = $prix_ord * $cmd["cmd_qtelivre"];
						}
						if ($cmd["cmd_typefuel"] == 2) {
							$type = 'S';
							$total = $prix_sup * $cmd["cmd_qtelivre"];
						}
						if ($cmd["cmd_typefuel"] == 3) {
							$type = 'GNR';
						}

						?>
						<tr class="select ancienne_commande">
							<input type="hidden" name="id_cmd" value="<?= $cmd["id_cmd"] ?>">
							<td><i class="fas fa-arrow-right"></i></td>
							<td><?= $cmd["cmd_dt"] ?></td>
							<td><?= $cmd["cmd_qte"] ?></td>
							<td><?= $type; ?></td>
							<td><?= $status; ?></td>
							<td><?= $cmd["cmd_prix_ord"]; ?></td>
							<td><?= $cmd["cmd_prix_sup"]; ?></td>
							<td><?php
							if (isset($cmd["cmd_status"]) && intval($cmd["cmd_status"]) === 99 && intval($cmd["groupe_cmd"]) === 0 && !empty($cmd["ancien_grp"])) {
								echo htmlspecialchars($cmd["ancien_grp"]);
							} else {
								echo htmlspecialchars($cmd["groupe_cmd"]);
							}
							?></td>
							<td><?php if (isset($avis["note"])) {
								echo $avis["note"];
							} ?></td>
							<td> <?php if (isset($cmd["nom"])) {
								echo $cmd["nom"];
							} ?>
								<?php if (isset($avis["message"])) {
									echo " - LIVRE D'OR : " . $avis["message"];
								} ?>
							</td>
						</tr>
						<?php
					}
					?>
				</tbody>
			</table>
		</div>
		<?php include 'form/modal_ancienne_commande.php'; ?>
	</form>
</div>
<?php
$content = ob_get_clean();
require('template.php');
?>
<script src="admin/js/script_commandes.js" charset="utf-8"></script>
<?php
$host = $_SERVER['HTTP_HOST'];
$isDev = str_contains($host, 'dev') || str_contains($host, 'localhost'); // adapte si besoin

$baseUrl = $isDev
	? "https://dev.plus-on-est-moins-on-paie.fr"
	: "https://plus-on-est-moins-on-paie.fr";
?>

<script type="text/javascript">
	$(document).ready(function () {
		var params = new URLSearchParams(window.location.search);

		$(".ancienne_commande").dblclick("tr", function () {
			var value = $(this).find('input').val();
			var newloc = params.toString();
			window.location.href = "<?= $baseUrl ?>/admin/ancienne_commande.php?" + newloc + '&details_cmd=' + value;
		});
	});
</script>

<?php if (isset($_GET["details_cmd"])): ?>
	<script type="text/javascript">
		$(document).ready(function () {
			$('#detailsCmd').modal('show');

			$(".fermer-modal").click(function () {
				var params = new URLSearchParams(window.location.search);
				params.delete('details_cmd');
				var newloc = params.toString();
				window.location.href = "<?= $baseUrl ?>/admin/ancienne_commande.php?" + newloc;
			});
		});
	</script>
<?php endif; ?>

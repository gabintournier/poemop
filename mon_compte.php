<?php
session_start();
if(!isset($_SESSION['id']))
{
    header('Location: /creer_un_compte_poemop.php?connexion=1');
	die();
}


$desc = 'Commandes groupées de fioul domestique partout en France. Poemop groupe gratuitement votre commande de fioul avec celles de vos voisins.';
$title = 'Mon compte POEMOP';
ob_start();

include_once __DIR__ . "/inc/pmp_co_connect.php";
include_once __DIR__ . "/inc/pmp_inc_fonctions_mail.php";
include_once __DIR__ . "/inc/pmp_inc_fonctions.php";
include_once __DIR__ . "/inc/pmp_inc_fonctions_compte.php";

$jjj_users = ChargeCompteJoomla($co_pmp, $_SESSION['id']);
$utilisateur = ChargeCompteFioul($co_pmp, $_SESSION['id']);

if ($_GET["type"] == 'fioul')
{
	include 'modules/menu_fioul.php';
}
elseif ($_GET["type"] == 'elec')
{
	include 'modules/menu_elec.php';
}
elseif ($_GET["type"] == 'gaz')
{
	include 'modules/menu_gaz.php';
}
elseif ($_GET["type"] == 'artisan')
{
	include 'modules/menu_artisan.php';
}
else {
	header('Location: /404.php');
}


?>
<div class="container-fluid">
	<div class="header">
		<div class="row">
			<div class="col-sm-9" style="margin-top: 5%">
				<div class="text-center">
					<h1>Mon compte Poemop</h1>
					<p>Pour valider votre abonnement renseignez vos informations.<br>Par défaut, l'adresse mail renseignée pour la création de votre compte sera utilisée pour toutes les énergies</p>
					<hr class="separe">
				</div>
<?php
				if(isset($message))
				{
?>
				<div class="toast <?= $message_type; ?>">
					<div class="message-icon <?= $message_type; ?>-icon">
						<i class="fa-solid <?= $message_icone; ?>"></i>
					</div>
					<div class="message-content ">
						<div class="message-type" style="text-align:left;">
							<?= $message; ?>
						</div>
						<div class="message" style="text-align:left;">
<?php
						if($message != 'Erreur')
						{
							if(isset($message_m)) { echo "- " . $message_m . "<br>"; }
							if(isset($message_a)) { echo "- " . $message_a . "<br>"; }
						}
						else
						{
							if(isset($message_nom)) { echo "- " . $message_nom . "<br>"; }
							if(isset($message_cp)) { echo "- " . $message_cp . "<br>"; }
							if(isset($message_prenom)) { echo "- " . $message_prenom . "<br>"; }
							if(isset($message_adresse)) { echo "- " . $message_adresse . "<br>"; }
							if(isset($message_telp)) { echo "- " . $message_telp . "<br>"; }
							if(isset($message_telf)) { echo "- " . $message_telf . "<br>"; }
							if(isset($message_tel3)) { echo "- " . $message_tel3 . "<br>"; }
							if(isset($message_com_u)) { echo "- " . $message_com_u . "<br>"; }
							if(isset($message_com)) { echo "- " . $message_com . "<br>"; }
							if(isset($message_mdp)) { echo "- " . $message_mdp . "<br>"; }
							if(isset($message_mdp2)) { echo "- " . $message_mdp2 . "<br>"; }
							if(isset($message_mdpa)) { echo "- " . $message_mdpa . "<br>"; }
							if(isset($message_mail)) { echo "- " . $message_mail . "<br>"; }
							if(isset($message_commune)) { echo "- " . $message_commune . "<br>"; }
						}
?>
						</div>
					</div>
				</div>
<?php
				}
?>

				<div class="diff-groupements">


<?php
				if ($_GET["type"] == 'fioul')
				{
?>
					<div class="text-center">
						<h2>Mes informations</h2>
					</div>
<?php
					include 'form/mon_compte_fioul.php';
				}
				elseif ($_GET["type"] == 'elec')
				{
?>
					<div class="text-center">
						<h2>Mes informations éléctricité</h2>
					</div>
<?php
					include 'form/mon_compte_elec.php';
				}
				elseif ($_GET["type"] == 'gaz')
				{
?>
					<div class="text-center">
						<h2>Mes informations Gaz</h2>
					</div>
<?php
					include 'form/mon_compte_gaz.php';
				}
				elseif ($_GET["type"] == 'artisan')
				{
?>
					<div class="text-center">
						<h2>Aidez nous à mettre en place<br>ce réseau d'artisans de confiance</h2>
					</div>
<?php
					include 'form/mon_compte_artisans.php';
				}
?>

				</div>
			</div>
			<div class="col-sm-3" style="margin-top: 5%">
<?php
				include 'modules/connexion.php';
				include 'modules/activites.php';
				include 'modules/avis_clients.php';
?>
			</div>
		</div>
	</div>
</div>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<?php
$content = ob_get_clean();
require('template.php');
?>
<script>
$(document).ready(function() {
	$('input[name="code_postal"]').blur(function(){
		if($(this).val()) {
			code_postal =  $(this).val();
			$.ajax({
				method: 'POST',
				url: 'inc/pmp_ajax_mon_compte.php',
				dataType : 'html',
				data: {
					code_postal: code_postal, //valeur de la checkbox cliqué
					user_id: <?php echo json_encode( $_SESSION['id']); ?> // Le user_id est récupéré au début dans les entetes de chaque fichier
				},
				success : function(reponse)
				{
					$( ".code" ).empty();
					$('.code').append('<option value="">'+ reponse +'</option>');
					$(".code option[value='']").remove();
     			},
			});
		}
	});
});

document.addEventListener("DOMContentLoaded", function () {
    const modalEl = document.getElementById('modalConfirmDesac');
    const btnOpen = document.getElementById('btnDesactiverCompte');
    const btnConfirm = document.getElementById('confirmDesacBtn');
    const form = document.getElementById('formDesacCompte');

    btnOpen.addEventListener('click', function () {
        const modal = new bootstrap.Modal(modalEl);
        modal.show();
    });

    btnConfirm.addEventListener('click', function () {
        form.submit();
    });
});
</script>
<?php
session_start();
if(!isset($_SESSION['id']))
{
    header('Location: /creer_un_compte_poemop.php?connexion=1');
	die();
}


$desc = 'Commandes groupées de fioul domestique partout en France. Poemop groupe gratuitement votre commande de fioul avec celles de vos voisins.';
$title = 'Paramètres de mon compte POEMOP';
ob_start();

include_once __DIR__ . "/inc/pmp_co_connect.php";
include_once __DIR__ . "/inc/pmp_inc_fonctions_mail.php";
include_once __DIR__ . "/inc/pmp_inc_fonctions.php";
include_once __DIR__ . "/inc/pmp_inc_fonctions_compte.php";

$jjj_users = ChargeCompteJoomla($co_pmp, $_SESSION['id']);
$utilisateur = ChargeCompteFioul($co_pmp, $_SESSION['id']);

include 'modules/menu_fioul.php';

?>
<div class="container-fluid">
	<div class="header">
		<div class="row">
			<div class="col-sm-9" style="margin-top: 5%">
				<div class="text-center">
					<h1>Mes paramètres</h1>
					<p>Vous pouvez ici paramétrer votre compte : notifications de groupements, désactivation du compte.</p>
					<hr class="separe">
				</div>
				<div class="informations-perso newsletter text-center">
				    <h2>Offres de prix et informations groupements</h2>
				    <div class="ligne-center jaune"></div>
				    <?php 
				        $isDesinscrit = ($utilisateur["bloquemail"] == 1);
				    ?>

				    <form id="formMailGroupement" method="post" action="mail_groupement.php" data-is-desinscrit="<?= $isDesinscrit ? '1' : '0'; ?>">
				        <input type="hidden" name="actionNotifGroupement" id="actionNotifGroupement" value="">

				        <p class="center">
				            <?php if ($isDesinscrit): ?>
				                <strong style="color:red">Vous êtes actuellement désinscrit à nos informations liées aux opérations groupements, notamment nos <b>offres de prix.</b></strong> <br>
				            <?php else: ?>
				                <strong>Vous êtes actuellement inscrit à nos informations liées aux opérations groupements, notamment nos <b>offres de prix.</b></strong><br>
				            <?php endif; ?>
				        </p>
							
				        <div class="text-center" style="margin-top:15px; color: #0f393a;">
				            <label for="toggleMail" style="cursor:pointer; font-weight:500;">
				                <input type="checkbox" id="toggleMailNotifGroupement">
				                <?= $isDesinscrit ? "Je souhaite recevoir les informations liées aux opérations groupements, dont les offres de prix négociées près de chez moi." : "Je ne souhaite pas recevoir les informations liées aux opérations groupements, dont les offres de prix négociées près de chez moi."; ?>
				            </label>
				        </div>
							
				        <div class="text-center" style="margin-top:20px;">
				            <button type="submit" id="btnValiderNotifGroupement" class="btn btn-primary" disabled>
				                VALIDER
				            </button>
				        </div>

				    </form>
				</div>

				<!-- Modal confirmation notifications groupement -->
				<div class="modal fade" id="modalConfirmNotifGroupement" tabindex="-1" aria-labelledby="modalNotifLabel" aria-hidden="true">
				    <div class="modal-dialog modal-dialog-centered">
				        <div class="modal-content" style="border-radius:10px;">
				            <div class="modal-header" style="border:none;">
				                <h5 class="modal-title" id="modalNotifLabel" style="font-weight:700;color:#0f393a;">
				                    Confirmation de désactivation des notifications
				                </h5>
				                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
				            </div>
				            <div class="modal-body" style="font-size:15px;line-height:1.6;color:#333;">
				                <p>
				                    Attention, vous êtes sur le point de désactiver les notifications de groupement.
									<br><br>
									<b>Vous ne recevrez plus nos mails d'opérations de groupement et les offres négociées près de chez vous.</b>
									<br>
									Vous pouvez rendre cette désactivation temporaire et réactiver les notifications de groupement à tout moment. 
									<br><br>
									Voulez-vous continuer ?
				                </p>

				            </div>
				            <div class="modal-footer" style="border:none;display:flex;justify-content:space-between;">
				                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="font-weight:600;">
				                    Non, annuler
				                </button>
				                <button type="button" id="confirmNotifGroupementBtn" class="btn btn-primary" style="font-weight:500;">
				                    Oui, continuer
				                </button>
				            </div>
				        </div>
				    </div>
				</div>

				<div class="text-center" style="margin-top:20px;">
				    <form id="formDesacCompte" method="post" style="display:inline;">
				        <button type="button" id="btnDesactiverCompte"
				            style="background:none;border:none;padding:0;
				                   color:#0f393a;text-decoration:none;
				                   cursor:pointer;font-size:15px;">
				            Pour désactiver votre compte, veuillez cliquer ici
				        </button>
				        <input type="hidden" name="desac_compte" value="1">
				    </form>
				</div>

				<!-- Modal désactivation -->
				<div class="modal fade" id="modalConfirmDesac" tabindex="-1" aria-labelledby="modalDesacLabel" aria-hidden="true">
				    <div class="modal-dialog modal-dialog-centered">
				        <div class="modal-content" style="border-radius:10px;">
				            <div class="modal-header" style="border:none;">
				                <h5 class="modal-title" id="modalDesacLabel" style="font-weight:700;color:#842029;">
				                    Confirmation de désactivation
				                </h5>
				                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
				            </div>
				            <div class="modal-body" style="font-size:15px;line-height:1.6;color:#333;">
				                <p>Vous êtes sur le point de <strong>désactiver votre compte POEMOP</strong>.</p>
				                <ul style="text-align:left;margin-left:15px;">
				                    <li>Vous ne serez plus ajouté à aucun groupement.</li>
				                    <li>Vous ne recevrez plus de notifications ou d’e-mails.</li>
				                    <li>Vos données seront effacées dans 3 ans si vous ne réactivez pas votre compte.</li>
				                </ul>
				                <p style="margin-top:15px;">Souhaitez-vous vraiment continuer ?</p>
				            </div>
				            <div class="modal-footer" style="border:none;display:flex;justify-content:space-between;">
				                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" 
				                        style="font-weight:600;">
				                    Non, annuler
				                </button>
				                <button type="button" id="confirmDesacBtn" class="btn btn-outline-danger" 
				                        style="font-weight:500;">
				                    Oui, désactiver
				                </button>
				            </div>
				        </div>
				    </div>
				</div>
			</div>

			<!-- Modal blocage désactivation (commande en cours statut 20/25) -->
			<?php
			    $desac_block = isset($_GET['desac_block']);
			    $statut_cmd = isset($_GET['statut']) ? (int)$_GET['statut'] : 0;
			    $statut_cmd_texte = '';
			    if ($statut_cmd === 25) { $statut_cmd_texte = 'Livrable'; }
			    elseif ($statut_cmd === 20) { $statut_cmd_texte = 'Prix validé'; }
			?>
			<div class="modal fade" id="modalBlockDesac" tabindex="-1" aria-labelledby="modalBlockDesacLabel" aria-hidden="true">
			    <div class="modal-dialog modal-dialog-centered">
			        <div class="modal-content" style="border-radius:10px;">
			            <div class="modal-header" style="border:none;">
			                <h5 class="modal-title" id="modalBlockDesacLabel" style="font-weight:700;">Désactivation impossible</h5>
			                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
			            </div>
			            <div class="modal-body" style="font-size:15px;line-height:1.6;color:#333;text-align:center;">
			                <p>
			                    Attention, vous avez une commande en cours au statut :<br>« <strong><?= htmlspecialchars($statut_cmd_texte ?: '') ?></strong> ».
			                    <br><br>
			                    Vous ne pouvez pas désactiver votre compte tant que cette commande reste dans ce statut.
			                    <br><br>
			                    Vous pouvez contacter les administrateurs du site via notre page <a href="/contacter_poemop.php" class="link-primary" style="color:#ef8351 !important; text-decoration: none !important;">Contact</a>.
			                </p>
			            </div>
			            <div class="modal-footer" style="border:none;display:flex;justify-content:flex-end;">
			                <button type="button" class="btn btn-primary" data-bs-dismiss="modal" style="font-weight:500;">Compris</button>
			            </div>
			        </div>
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

    // Notifications groupement: logique de confirmation lorsque désinscription
    const formMail = document.getElementById('formMailGroupement');
    const isDesinscrit = formMail ? (formMail.getAttribute('data-is-desinscrit') === '1') : null;
    const checkboxNotif = document.getElementById('toggleMailNotifGroupement');
    const actionInput = document.getElementById('actionNotifGroupement');
    const btnValiderNotif = document.getElementById('btnValiderNotifGroupement');
    const modalNotifEl = document.getElementById('modalConfirmNotifGroupement');
    const btnConfirmNotif = document.getElementById('confirmNotifGroupementBtn');

    if (formMail && checkboxNotif && actionInput && btnValiderNotif) {
        // Active le bouton uniquement quand l'utilisateur a coché sa décision
        checkboxNotif.addEventListener('change', function () {
            if (this.checked) {
                actionInput.value = isDesinscrit ? 'inscription' : 'desinscription';
                btnValiderNotif.disabled = false;
            } else {
                actionInput.value = '';
                btnValiderNotif.disabled = true;
            }
        });

        // Intercepte la soumission si désinscription pour demander confirmation
        formMail.addEventListener('submit', function (e) {
            if (actionInput.value === 'desinscription') {
                e.preventDefault();
                const modal = new bootstrap.Modal(modalNotifEl);
                modal.show();
            }
        });

        // Confirmation depuis la modal => on soumet le formulaire
        if (btnConfirmNotif) {
            btnConfirmNotif.addEventListener('click', function () {
                formMail.submit();
            });
        }
    }
    // Affiche la modal d'information si le serveur a bloqué la désactivation
    const modalBlockEl = document.getElementById('modalBlockDesac');
    const isDesacBlocked = <?= $desac_block ? 'true' : 'false' ?>;
    if (isDesacBlocked && modalBlockEl) {
        const blockModal = new bootstrap.Modal(modalBlockEl);
        blockModal.show();
    }
});
</script>

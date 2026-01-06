<style media="screen">
.ligne-menu {width: 390px !important;}
</style>
<?php
session_start();

//*** Securisation du formulaire
// On detecte la recharge par F5 (par exemple) dans une meme session
$recharge = TRUE;
$RequestSignature = md5($_SERVER['REQUEST_URI'].$_SERVER['QUERY_STRING'].print_r($_POST, true));
if($_SESSION['LastRequest'] != $RequestSignature)
{
	$_SESSION['LastRequest'] = $RequestSignature;
	$recharge = FALSE;
}
// On detecte le token du form et le token de la session sont identique
if(isset($_SESSION['token']) AND isset($_POST['token']) AND !empty($_SESSION['token']) AND !empty($_POST['token']))
{
    if ($_SESSION['token'] == $_POST['token'])
	{
		$recharge = FALSE;
    }
}

$title = 'Mes groupements en cours';
$title_page = 'Mes groupements en cours';

ob_start();

// INC global
include_once "../inc/pmp_co_connect.php";
// INC interne ADM Fournisseur
include_once "inc/pmp_inc_fonctions_groupements.php";

$res = getListeGroupements($co_pmp, $_SESSION["four_id"]);

?>
<div class="bloc">
	<form method="post">
		<label class="label-title" style="margin: 0;">Liste des regroupements</label>
		<div class="ligne"></div>
		<div class="tableau" style="height: 680px;">
			<table class="table" id="trie_table_grp">
				<thead>
					<th>N°</th>
					<th class="text-center" >Etat</th>
					<th >Nom</th>
					<th class="text-center" >date</th>
				</thead>
				<tbody>
<?php

				while ($regroupement = mysqli_fetch_array($res))
				{
					$cmdes = getCommandesGroupements($co_pmp, $regroupement["id"]);
					$num_cmdes = mysqli_num_rows($cmdes);
					if($num_cmdes > 0)
					{
?>
					<tr class="select regroupements">
						<input type="hidden" name="n_grp" value="<?= $regroupement["id"]; ?>">
						<td><?= $regroupement["id"]; ?></td>
						<td class="text-center">Envoyé</td>
						<td><?= $regroupement["libelle"]; ?></td>
						<td class="text-center"><?= $regroupement["date_grp"]; ?></td>
					</tr>
<?php
					}
				}
?>
				</tbody>
			</table>
		</div>
	</form>
</div>
<?php
$content = ob_get_clean();
require('template.php');
?>
<script src="js/script_groupements.js" charset="utf-8"></script>

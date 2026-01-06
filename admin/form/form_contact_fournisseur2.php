<?php
$res = getContactsFournisseurs($co_pmp, $_GET["id_four"]);
?>
<label class="label-title" style="margin: 0;">Liste des contacts</label>
<div class="ligne"></div>
<div class="tableau" style="height:450px;margin-top: 1%;">
	<table class="table" id="trie_table2">
		<thead>
			<tr>
				<th></th>
				<th>id</th>
				<th>Nom</th>
				<th>Prenom</th>
				<th>Tel</th>
				<th>Mail</th>
				<th>Fonction</th>
				<th>Commentaire</th>
			</tr>
		</thead>
		<tbody>
<?php
			$i = 0;
			$select_contact = getMailToZone($co_pmp, $zones['id']);
			$id_contact_cc = explode(";", $select_contact["mail_cc"]);
			while ($contact = mysqli_fetch_array($res))
			{

				$value = compare($contact["id"], $id_contact_cc);
?>
			<tr class="select contact">
				<input type="hidden" name="contact_id_cc[]" value="<?= $contact["id"]; ?>">
				<td style="padding: 0.6rem;">
					<input type="checkbox" name="select_contact_cc_<?php print $i++; ?>" id="select_contact" class="switch value check" <?php if(isset($value)) { if($value == $contact["id"]) { echo "value='1'"; } } ?>>
				</td>
				<td style="padding: 0.6rem;"><?= $contact['id']; ?></td>
				<td style="padding: 0.6rem;"><?= $contact['nom']; ?></td>
				<td style="padding: 0.6rem;"><?= $contact['prenom']; ?></td>
				<td style="padding: 0.6rem;"><?= $contact['tel']; ?></td>
				<td style="padding: 0.6rem;"><?= $contact['mail']; ?></td>
				<td style="padding: 0.6rem;"><?= $contact['fonction']; ?></td>
				<td style="padding: 0.6rem;"><?= $contact['commentaire']; ?></td>
			</tr>
<?php
		}
?>
		</tbody>
	</table>
</div>
<script src="/admin/js/script_fournisseurs.js" charset="utf-8"></script>

<script type="text/javascript">
	$('#energie_1').removeProp('checked');
</script>
<div class="row justify-content-md-center">
	<div class="col-sm-12" style="margin-bottom: 2%;">
		<label class="label-title" style="margin: 0;">Groupements principaux</label>
		<div class="ligne" style="width: 2.5%;"></div>
	</div>
	<!-- <div class="col-sm-4 inputGroup input_1">
		<input class="check-energie" type="checkbox" name="abo_energie[]" value="1" id="energie_1" <?php if (isset($client[0])) {
			if ($client['inscrit'] == 1)
				echo 'checked';
		} ?>>
		<label for="energie_1"><i class="fas fa-tint icon" aria-hidden="true"></i>Fioul</label>
		<p class="abo infos_1" >Abonné</p>
		<p class="abo abo_1">Non abonné</p>
	</div> -->
	<div class="col-sm-4 inputGroup input_1">
		<input class="check-energie" type="checkbox" name="abo_energie[]" value="01" id="energie_01" <?php if (isset($client[0])) {
			if ($client['inscrit'] == 1)
				echo 'checked';
		} ?>>
		<label for="energie_01"><i class="fas fa-tint icon" aria-hidden="true"></i>Fioul</label>
		<p class="abo infos_01">Abonné</p>
		<p class="abo abo_01">Non abonné</p>
	</div>
	<div class="col-sm-4 inputGroup input_2">
		<input class="check-energie" type="checkbox" name="abo_energie[]" value="2" id="energie_2" <?php if (isset($pmp_electricite[0])) {
			if ($pmp_electricite['inscrit'] == 1)
				echo 'checked';
		} ?>>
		<label for="energie_2"><i class="fas fa-plug icon" aria-hidden="true"></i>Électricité</label>
		<p class="abo abo_2">Non abonné</p>
		<p class="abo infos_2">Abonné</p>
	</div>
	<!--
	<div class="col-sm-4 inputGroup input_3">
		<input class="check-energie" type="checkbox" name="abo_energie[]" value="3" id="energie_3" <?php if (isset($pmp_gaz[0])) {
			if ($pmp_gaz['inscrit'] == 1)
				echo 'checked';
		} ?>>
		<label for="energie_3"><i class="fas fa-fire icon" aria-hidden="true"></i>Gaz</label>
		<p class="abo abo_3">Non abonné</p>
		<p class="abo infos_3" >Abonné</p>
	</div>
	<div class="col-sm-12" style="margin-bottom: 2%;">
		<hr class="separe" style="margin-bottom: 2%;">
		<label class="label-title" style="margin: 0;">Autres groupements</label>
		<div class="ligne" style="width: 2.5%;"></div>
	</div>
	<div class="col-sm-4 inputGroup input_4">
		<input class="check-energie" type="checkbox" name="abo_energie[]" value="4" id="energie_4" <?php if (isset($pmp_compte[0])) {
			if ($pmp_compte['artisan'] == 1)
				echo 'checked';
		} ?>>
		<label for="energie_4"><i class="fas fa-briefcase icon" aria-hidden="true"></i>Artisans</label>
		<p class="abo abo_4">non abonné</p>
		<p class="abo infos_4" >Abonné</p>
	</div>
	<div class="col-sm-4 inputGroup input_5">
		<input class="check-energie" type="checkbox" name="abo_energie[]" value="5" id="energie_5" <?php if (isset($pmp_compte[0])) {
			if ($pmp_compte['produit_2'] == 1)
				echo 'checked';
		} ?>>
		<label for="energie_5"><i class="fas fa-credit-card icon" aria-hidden="true"></i>Compte bancaire</label>
		<p class="abo abo_5">non abonné</p>
		<p class="abo infos_5" >Abonné</p>
	</div>
	<div class="col-sm-4 inputGroup input_6">
		<input class="check-energie" type="checkbox" name="abo_energie[]" value="6" id="energie_6" <?php if (isset($pmp_compte[0])) {
			if ($pmp_compte['produit_3'] == 1)
				echo 'checked';
		} ?>>
		<label for="energie_6"><i class="fas fa-file-invoice icon" aria-hidden="true"></i>Assurance</label>
		<p class="abo abo_6">non abonné</p>
		<p class="abo infos_6" >Abonné</p>
	</div>
	<div class="col-sm-4 inputGroup input_7">
		<input class="check-energie" type="checkbox" name="abo_energie[]" value="7" id="energie_7" <?php if (isset($pmp_compte[0])) {
			if ($pmp_compte['produit_4'] == 1)
				echo 'checked';
		} ?>>
		<label for="energie_7"><i class="fas fa-phone-alt icon" aria-hidden="true"></i>Abo téléphonique</label>
		<p class="abo abo_7">non abonné</p>
		<p class="abo infos_7" >Abonné</p>
	</div>
	<div class="col-sm-4 inputGroup input_8">
		<input class="check-energie" type="checkbox" name="abo_energie[]" value="8" id="energie_8" <?php if (isset($pmp_compte[0])) {
			if ($pmp_compte['produit_5'] == 1)
				echo 'checked';
		} ?>>
		<label for="energie_8"><i class="fas fa-wifi icon" aria-hidden="true"></i>Abo internet</label>
		<p class="abo abo_8">non abonné</p>
		<p class="abo infos_8" >Abonné</p>
	</div>
-->
	<div class="col-sm-4 inputGroup input_9">
		<input class="check-energie" type="checkbox" name="abo_energie[]" value="9" id="energie_9" <?php if (isset($pmp_compte[0])) {
			if ($pmp_compte['produit_6'] == 1)
				echo 'checked';
		} ?>>
		<label for="energie_9"><i class="fas fa-tree icon" aria-hidden="true"></i>Bois ou pellets</label>
		<p class="abo abo_9">non abonné</p>
		<p class="abo infos_9">Abonné</p>
	</div>
	<!--
	<div class="col-sm-4 inputGroup input_10">
		<input class="check-energie" type="checkbox" name="abo_energie[]" value="10" id="energie_10" <?php if (isset($pmp_compte[0])) {
			if ($pmp_compte['produit_7'] == 1)
				echo 'checked';
		} ?>>
		<label for="energie_10"><i class="fas fa-tools icon" aria-hidden="true"></i>chaudière</label>
		<p class="abo abo_10">non abonné</p>
		<p class="abo infos_10" >Abonné</p>
	</div>
	<div class="col-sm-4 inputGroup input_11">
		<input class="check-energie" type="checkbox" name="abo_energie[]" value="11" id="energie_11" <?php if (isset($pmp_compte[0])) {
			if ($pmp_compte['produit_8'] == 1)
				echo 'checked';
		} ?>>
		<label for="energie_11"><i class="fas fa-tv icon" aria-hidden="true"></i>Abo télé</label>
		<p class="abo abo_11">non abonné</p>
		<p class="abo infos_11" >Abonné</p>
	</div>
	<div class="col-sm-4 inputGroup input_12">
		<input class="check-energie" type="checkbox" name="abo_energie[]" value="12" id="energie_12" <?php if (isset($pmp_compte[0])) {
			if ($pmp_compte['produit_9'] == 1)
				echo 'checked';
		} ?>>
		<label for="energie_12"><i class="fas fa-archive icon" aria-hidden="true"></i>Autres produits</label>
		<p class="abo abo_12">non abonné</p>
		<p class="abo infos_12" >Abonné</p>
	</div>
-->
</div>
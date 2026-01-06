<?php
//COMMANDES
//Afficher les commandes
function getFiltreCommandes(&$co_pmp)
{
	if(isset($_POST["date_min_co"]))
	{
		$date_min = date_format(new DateTime($_POST["date_min_co"]), 'Y-m-d H:i:s' );
		$date_max = date_format(new DateTime($_POST["date_max_co"]), 'Y-m-d H:i:s' );
	}
	else
	{
		$date_max_d = date("Y-m-d");
		$date_min_d = date('Y-m-d',strtotime('-16 month',strtotime($date_max_d)));
		$date = date('Y-m-d',strtotime('+1 month',strtotime($date_max_d)));

		$date_min = date_format(new DateTime($date_min_d), 'Y-m-d H:i:s' );
		$date_max = date_format(new DateTime($date), 'Y-m-d H:i:s' );
	}

	$etat_1 = $_SESSION["etat_1"];
	$etat_2 = $_SESSION["etat_2"];
	$n_dep = $_SESSION["n_dep_cmd"];

	if($etat_2 == 0) { $etat_2 = "10"; }

	if(isset($_GET["id_grp"]))
	{
		$id_grp = $_GET["id_grp"];

		if(isset($_SESSION["n_cmd"]))
		{
			$cmd_id = $_SESSION["n_cmd"];
			$query = "  SELECT pmp_commande.id AS num_cmd, pmp_commande.cmd_qte,jjj_users.name, pmp_commande.cmd_typefuel, pmp_commande.cmd_dt, pmp_commande.cmd_status, pmp_utilisateur.nom, pmp_utilisateur.prenom, pmp_utilisateur.code_postal, pmp_utilisateur.ville, jjj_users.email, pmp_utilisateur.tel_fixe, pmp_utilisateur.tel_port, pmp_utilisateur.user_id, pmp_commande.groupe_cmd, pmp_fournisseur.nom AS nom_four
						FROM pmp_commande, pmp_utilisateur, jjj_users, pmp_regroupement, pmp_fournisseur
						WHERE pmp_utilisateur.user_id = pmp_commande.user_id
						AND pmp_utilisateur.user_id = jjj_users.id
						AND pmp_commande.groupe_cmd = pmp_regroupement.id
						AND pmp_regroupement.id_four = pmp_fournisseur.id
						AND pmp_commande.id = '$cmd_id'
						AND (pmp_commande.groupe_cmd = '0' OR pmp_commande.groupe_cmd = '' OR pmp_commande.groupe_cmd IS NULL)
						-- AND  pmp_commande.groupe_cmd = ''
						ORDER BY jjj_users.name ";
			$res = my_query($co_pmp, $query);
			return $res;
		}
		else
		{
			$query = "  SELECT pmp_commande.id AS num_cmd, pmp_commande.cmd_qte,jjj_users.name, pmp_commande.cmd_typefuel, pmp_commande.cmd_dt, pmp_commande.cmd_status, pmp_utilisateur.nom, pmp_utilisateur.prenom, pmp_utilisateur.code_postal, pmp_utilisateur.ville, jjj_users.email, pmp_utilisateur.tel_fixe, pmp_utilisateur.tel_port, pmp_utilisateur.user_id, pmp_commande.groupe_cmd, pmp_fournisseur.nom AS nom_four
						FROM pmp_commande, pmp_utilisateur, jjj_users, pmp_regroupement, pmp_fournisseur
						WHERE pmp_utilisateur.user_id = pmp_commande.user_id
						AND pmp_utilisateur.user_id = jjj_users.id
						AND pmp_commande.groupe_cmd = pmp_regroupement.id
						AND pmp_regroupement.id_four = pmp_fournisseur.id
						AND pmp_commande.cmd_status BETWEEN '$etat_1' AND '$etat_2'
						AND pmp_utilisateur.code_postal LIKE '$n_dep%'
						AND pmp_commande.cmd_dt BETWEEN '$date_min' AND '$date_max'
						AND (pmp_commande.groupe_cmd = '0' OR pmp_commande.groupe_cmd = '' OR pmp_commande.groupe_cmd IS NULL)
						-- AND  pmp_commande.groupe_cmd = ''
						ORDER BY jjj_users.name ";
			$res = my_query($co_pmp, $query);
			return $res;
		}
	}
	else
	{
		$query = "  SELECT pmp_commande.id AS num_cmd, pmp_commande.cmd_qte,jjj_users.name, pmp_commande.cmd_typefuel, pmp_commande.cmd_dt, pmp_commande.cmd_status, pmp_utilisateur.nom, pmp_utilisateur.prenom, pmp_utilisateur.code_postal, pmp_utilisateur.ville, jjj_users.email, pmp_utilisateur.tel_fixe, pmp_utilisateur.tel_port, pmp_utilisateur.user_id, pmp_commande.groupe_cmd, pmp_fournisseur.nom AS nom_four
					FROM pmp_commande, pmp_utilisateur, jjj_users, pmp_regroupement, pmp_fournisseur
					WHERE pmp_utilisateur.user_id = pmp_commande.user_id
					AND pmp_utilisateur.user_id = jjj_users.id
					AND pmp_commande.groupe_cmd = pmp_regroupement.id
					AND pmp_regroupement.id_four = pmp_fournisseur.id
					AND pmp_commande.cmd_status BETWEEN '$etat_1' AND '$etat_2'
					AND pmp_utilisateur.code_postal LIKE '$n_dep%'
					AND pmp_commande.cmd_dt BETWEEN '$date_min' AND '$date_max'
					ORDER BY jjj_users.name ";
		$res = my_query($co_pmp, $query);
		return $res;
	}


}


function getFiltreCommandesActifs(&$co_pmp)
{
	if(isset($_POST["date_min_co"]))
	{
		$date_min = date_format(new DateTime($_POST["date_min_co"]), 'Y-m-d H:i:s' );
		$date_max = date_format(new DateTime($_POST["date_max_co"]), 'Y-m-d H:i:s' );
	}
	else
	{
		$date_max_d = date("Y-m-d");
		$date_min_d = date('Y-m-d',strtotime('-16 month',strtotime($date_max_d)));
		$date = date('Y-m-d',strtotime('+1 month',strtotime($date_max_d)));

		$date_min = date_format(new DateTime($date_min_d), 'Y-m-d H:i:s' );
		$date_max = date_format(new DateTime($date), 'Y-m-d H:i:s' );
	}

	$etat_1 = $_SESSION["etat_1"];
	$etat_2 = $_SESSION["etat_2"];
	$n_dep = $_SESSION["n_dep_cmd"];

	if($etat_2 == 0) { $etat_2 = "10"; }

	if(isset($_GET["id_grp"]))
	{
		$id_grp = $_GET["id_grp"];

		if(isset($_SESSION["n_cmd"]))
		{
			$cmd_id = $_SESSION["n_cmd"];
			$query = "  SELECT pmp_commande.id AS num_cmd, pmp_commande.cmd_qte,jjj_users.name, pmp_commande.cmd_typefuel, pmp_commande.cmd_dt, pmp_commande.cmd_status, pmp_utilisateur.nom, pmp_utilisateur.prenom, pmp_utilisateur.code_postal, pmp_utilisateur.ville, jjj_users.email, pmp_utilisateur.tel_fixe, pmp_utilisateur.tel_port, pmp_utilisateur.user_id, pmp_commande.groupe_cmd, pmp_fournisseur.nom AS nom_four
						FROM pmp_commande, pmp_utilisateur, jjj_users, pmp_regroupement, pmp_fournisseur
						WHERE pmp_utilisateur.user_id = pmp_commande.user_id
						AND pmp_utilisateur.user_id = jjj_users.id
						AND pmp_commande.groupe_cmd = pmp_regroupement.id
						AND pmp_regroupement.id_four = pmp_fournisseur.id
						AND pmp_commande.id = '$cmd_id'
						AND (pmp_commande.groupe_cmd = '0' OR pmp_commande.groupe_cmd = '' OR pmp_commande.groupe_cmd IS NULL)
						AND COALESCE(pmp_utilisateur.disabled_account, 0) = 0
						-- AND  pmp_commande.groupe_cmd = ''
						ORDER BY jjj_users.name ";
			$res = my_query($co_pmp, $query);
			return $res;
		}
		else
		{
			$query = "  SELECT pmp_commande.id AS num_cmd, pmp_commande.cmd_qte,jjj_users.name, pmp_commande.cmd_typefuel, pmp_commande.cmd_dt, pmp_commande.cmd_status, pmp_utilisateur.nom, pmp_utilisateur.prenom, pmp_utilisateur.code_postal, pmp_utilisateur.ville, jjj_users.email, pmp_utilisateur.tel_fixe, pmp_utilisateur.tel_port, pmp_utilisateur.user_id, pmp_commande.groupe_cmd, pmp_fournisseur.nom AS nom_four
						FROM pmp_commande, pmp_utilisateur, jjj_users, pmp_regroupement, pmp_fournisseur
						WHERE pmp_utilisateur.user_id = pmp_commande.user_id
						AND pmp_utilisateur.user_id = jjj_users.id
						AND pmp_commande.groupe_cmd = pmp_regroupement.id
						AND pmp_regroupement.id_four = pmp_fournisseur.id
						AND pmp_commande.cmd_status BETWEEN '$etat_1' AND '$etat_2'
						AND pmp_utilisateur.code_postal LIKE '$n_dep%'
						AND pmp_commande.cmd_dt BETWEEN '$date_min' AND '$date_max'
						AND (pmp_commande.groupe_cmd = '0' OR pmp_commande.groupe_cmd = '' OR pmp_commande.groupe_cmd IS NULL)
						AND COALESCE(pmp_utilisateur.disabled_account, 0) = 0
						-- AND  pmp_commande.groupe_cmd = ''
						ORDER BY jjj_users.name ";
			$res = my_query($co_pmp, $query);
			return $res;
		}
	}
	else
	{
		$query = "  SELECT pmp_commande.id AS num_cmd, pmp_commande.cmd_qte,jjj_users.name, pmp_commande.cmd_typefuel, pmp_commande.cmd_dt, pmp_commande.cmd_status, pmp_utilisateur.nom, pmp_utilisateur.prenom, pmp_utilisateur.code_postal, pmp_utilisateur.ville, jjj_users.email, pmp_utilisateur.tel_fixe, pmp_utilisateur.tel_port, pmp_utilisateur.user_id, pmp_commande.groupe_cmd, pmp_fournisseur.nom AS nom_four
					FROM pmp_commande, pmp_utilisateur, jjj_users, pmp_regroupement, pmp_fournisseur
					WHERE pmp_utilisateur.user_id = pmp_commande.user_id
					AND pmp_utilisateur.user_id = jjj_users.id
					AND pmp_commande.groupe_cmd = pmp_regroupement.id
					AND pmp_regroupement.id_four = pmp_fournisseur.id
					AND pmp_commande.cmd_status BETWEEN '$etat_1' AND '$etat_2'
					AND pmp_utilisateur.code_postal LIKE '$n_dep%'
					AND pmp_commande.cmd_dt BETWEEN '$date_min' AND '$date_max'
					AND COALESCE(pmp_utilisateur.disabled_account, 0) = 0
					ORDER BY jjj_users.name ";
		$res = my_query($co_pmp, $query);
		return $res;
	}


}

//Afficher les commandes selon le fournisseur et la zone
function getCommandesFournisseurZone(&$co_pmp, $id, $etat_1, $etat_2)
{
	if(isset($_GET["id_grp"]))
	{
		$id_grp = $_GET["id_grp"];
		$query = "  SELECT pmp_commande.id AS num_cmd, pmp_commande.cmd_qte, pmp_commande.cmd_typefuel,jjj_users.name, pmp_commande.cmd_dt, pmp_commande.cmd_status, pmp_utilisateur.nom, pmp_utilisateur.prenom, pmp_utilisateur.code_postal, pmp_utilisateur.ville, jjj_users.email, pmp_utilisateur.tel_fixe, pmp_utilisateur.tel_port, pmp_utilisateur.user_id, pmp_commande.groupe_cmd
					FROM pmp_utilisateur, pmp_commande,  pmp_zone_cp, pmp_code_postal, jjj_users
					WHERE pmp_utilisateur.internet = 0
					AND jjj_users.id = pmp_utilisateur.user_id
					AND pmp_utilisateur.inscrit = 1
				    AND pmp_utilisateur.user_id = pmp_commande.user_id
				    AND pmp_utilisateur.user_id > 1000010
					AND pmp_commande.cmd_status BETWEEN '$etat_1' AND '$etat_2'
				    AND pmp_zone_cp.zone_id = '$id'
					AND (pmp_commande.groupe_cmd = '0' OR pmp_commande.groupe_cmd = '' OR pmp_commande.groupe_cmd IS NULL)
				    AND pmp_zone_cp.code_postal_id = pmp_code_postal.id
				    AND pmp_utilisateur.code_postal = pmp_code_postal.code_postal
				    AND  pmp_zone_cp.actif = 1
					AND COALESCE(pmp_utilisateur.disabled_account, 0) = 0
					UNION
					 	SELECT pmp_commande.id AS num_cmd, pmp_commande.cmd_qte, pmp_commande.cmd_typefuel,jjj_users.name, pmp_commande.cmd_dt, pmp_commande.cmd_status, pmp_utilisateur.nom, pmp_utilisateur.prenom, pmp_utilisateur.code_postal, pmp_utilisateur.ville, jjj_users.email, pmp_utilisateur.tel_fixe, pmp_utilisateur.tel_port, pmp_utilisateur.user_id, pmp_commande.groupe_cmd
						FROM pmp_utilisateur, pmp_commande,  pmp_zone_cp, pmp_code_postal, jjj_users
						WHERE jjj_users.id = pmp_utilisateur.user_id
						and jjj_users.id = pmp_commande.user_id
					   and pmp_utilisateur.inscrit = 1
					   and pmp_utilisateur.internet = 1
					   AND pmp_commande.cmd_status BETWEEN '$etat_1' AND '$etat_2'
					   and pmp_utilisateur.user_id > 77
					   and pmp_zone_cp.zone_id = '$id'
					   AND (pmp_commande.groupe_cmd = '0' OR pmp_commande.groupe_cmd = '' OR pmp_commande.groupe_cmd IS NULL)
					   and pmp_zone_cp.code_postal_id = pmp_code_postal.id
					   and pmp_utilisateur.code_postal = pmp_code_postal.code_postal
					   and  pmp_zone_cp.actif=1
					   AND COALESCE(pmp_utilisateur.disabled_account, 0) = 0
					   order by name
				   ";

		$res = my_query($co_pmp, $query);
		return $res;
	}
	else
	{
		$query = "  SELECT pmp_commande.id AS num_cmd, pmp_commande.cmd_qte, pmp_commande.cmd_typefuel,jjj_users.name, pmp_commande.cmd_dt, pmp_commande.cmd_status, pmp_utilisateur.nom, pmp_utilisateur.prenom, pmp_utilisateur.code_postal, pmp_utilisateur.ville, jjj_users.email, pmp_utilisateur.tel_fixe, pmp_utilisateur.tel_port, pmp_utilisateur.user_id, pmp_commande.groupe_cmd
					FROM pmp_utilisateur, pmp_commande,  pmp_zone_cp, pmp_code_postal, jjj_users
					WHERE pmp_utilisateur.internet = 0
					AND jjj_users.id = pmp_utilisateur.user_id
					AND pmp_utilisateur.inscrit = 1
				    AND pmp_utilisateur.user_id = pmp_commande.user_id
				    AND pmp_utilisateur.user_id > 1000010
					AND pmp_commande.cmd_status BETWEEN '$etat_1' AND '$etat_2'
				    AND pmp_zone_cp.zone_id = '$id'
				    AND pmp_zone_cp.code_postal_id = pmp_code_postal.id
				    AND pmp_utilisateur.code_postal = pmp_code_postal.code_postal
				    AND  pmp_zone_cp.actif = 1
					AND COALESCE(pmp_utilisateur.disabled_account, 0) = 0
					UNION
					 	SELECT pmp_commande.id AS num_cmd, pmp_commande.cmd_qte, pmp_commande.cmd_typefuel,jjj_users.name, pmp_commande.cmd_dt, pmp_commande.cmd_status, pmp_utilisateur.nom, pmp_utilisateur.prenom, pmp_utilisateur.code_postal, pmp_utilisateur.ville, jjj_users.email, pmp_utilisateur.tel_fixe, pmp_utilisateur.tel_port, pmp_utilisateur.user_id, pmp_commande.groupe_cmd
						FROM pmp_utilisateur, pmp_commande,  pmp_zone_cp, pmp_code_postal, jjj_users
						WHERE jjj_users.id = pmp_utilisateur.user_id
						and jjj_users.id = pmp_commande.user_id
					   	and pmp_utilisateur.inscrit = 1
					   	and pmp_utilisateur.internet = 1
					   	AND pmp_commande.cmd_status BETWEEN '$etat_1' AND '$etat_2'
					   	and pmp_utilisateur.user_id > 77
					   	and pmp_zone_cp.zone_id = '$id'
					   	and pmp_zone_cp.code_postal_id = pmp_code_postal.id
					   	and pmp_utilisateur.code_postal = pmp_code_postal.code_postal
					   	and  pmp_zone_cp.actif=1
						AND COALESCE(pmp_utilisateur.disabled_account, 0) = 0
					   order by name
				   ";

		$res = my_query($co_pmp, $query);
		return $res;
	}

}


function getCommandesFournisseurZonePlusCmd(&$co_pmp, $id, $etat_1, $etat_2)
{
		if (isset($_GET["id_grp"])) {
		$id_grp = $_GET["id_grp"];

		if ($etat_1 == '13' && $etat_2 == '13') {
			$query = "
				SELECT pmp_commande.id AS num_cmd, pmp_commande.cmd_qte, pmp_commande.cmd_typefuel, jjj_users.name,
					pmp_commande.cmd_dt, pmp_commande.cmd_status, pmp_utilisateur.nom, pmp_utilisateur.prenom,
					pmp_utilisateur.code_postal, pmp_utilisateur.ville, jjj_users.email, 
					pmp_utilisateur.tel_fixe, pmp_utilisateur.tel_port, pmp_utilisateur.user_id, pmp_commande.groupe_cmd
				FROM pmp_utilisateur, pmp_commande, pmp_zone_cp, pmp_code_postal, jjj_users
				WHERE pmp_utilisateur.internet = 0
					AND jjj_users.id = pmp_utilisateur.user_id
					AND pmp_utilisateur.inscrit = 1
					AND pmp_utilisateur.disabled_account = 0
					AND pmp_utilisateur.user_id = pmp_commande.user_id
					AND pmp_utilisateur.user_id > 1000010
					AND pmp_commande.cmd_status BETWEEN '$etat_1' AND '$etat_2'
					AND pmp_zone_cp.zone_id = '$id'
					AND pmp_commande.groupe_cmd != '$id_grp'
					AND pmp_zone_cp.code_postal_id = pmp_code_postal.id
					AND pmp_utilisateur.code_postal = pmp_code_postal.code_postal
					AND pmp_zone_cp.actif = 1

				UNION

				SELECT pmp_commande.id AS num_cmd, pmp_commande.cmd_qte, pmp_commande.cmd_typefuel, jjj_users.name,
					pmp_commande.cmd_dt, pmp_commande.cmd_status, pmp_utilisateur.nom, pmp_utilisateur.prenom,
					pmp_utilisateur.code_postal, pmp_utilisateur.ville, jjj_users.email, 
					pmp_utilisateur.tel_fixe, pmp_utilisateur.tel_port, pmp_utilisateur.user_id, pmp_commande.groupe_cmd
				FROM pmp_utilisateur, pmp_commande, pmp_zone_cp, pmp_code_postal, jjj_users
				WHERE jjj_users.id = pmp_utilisateur.user_id
					AND jjj_users.id = pmp_commande.user_id
					AND pmp_utilisateur.inscrit = 1
					AND pmp_utilisateur.disabled_account = 0
					AND pmp_utilisateur.internet = 1
					AND pmp_commande.cmd_status BETWEEN '$etat_1' AND '$etat_2'
					AND pmp_utilisateur.user_id > 77
					AND pmp_zone_cp.zone_id = '$id'
					AND pmp_commande.groupe_cmd != '$id_grp'
					AND pmp_zone_cp.code_postal_id = pmp_code_postal.id
					AND pmp_utilisateur.code_postal = pmp_code_postal.code_postal
					AND pmp_zone_cp.actif = 1
				ORDER BY name
			";
		} else {
			$query = "
				SELECT pmp_commande.id AS num_cmd, pmp_commande.cmd_qte, pmp_commande.cmd_typefuel, jjj_users.name,
					pmp_commande.cmd_dt, pmp_commande.cmd_status, pmp_utilisateur.nom, pmp_utilisateur.prenom,
					pmp_utilisateur.code_postal, pmp_utilisateur.ville, jjj_users.email, 
					pmp_utilisateur.tel_fixe, pmp_utilisateur.tel_port, pmp_utilisateur.user_id, pmp_commande.groupe_cmd
				FROM pmp_utilisateur, pmp_commande, pmp_zone_cp, pmp_code_postal, jjj_users
				WHERE pmp_utilisateur.internet = 0
					AND jjj_users.id = pmp_utilisateur.user_id
					AND pmp_utilisateur.inscrit = 1
					AND pmp_utilisateur.disabled_account = 0
					AND pmp_utilisateur.user_id = pmp_commande.user_id
					AND pmp_utilisateur.user_id > 1000010
					AND pmp_commande.cmd_status BETWEEN '$etat_1' AND '$etat_2'
					AND pmp_commande.cmd_status != '13'
					AND pmp_zone_cp.zone_id = '$id'
					AND pmp_commande.groupe_cmd != '$id_grp'
					AND pmp_zone_cp.code_postal_id = pmp_code_postal.id
					AND pmp_utilisateur.code_postal = pmp_code_postal.code_postal
					AND pmp_zone_cp.actif = 1

				UNION

				SELECT pmp_commande.id AS num_cmd, pmp_commande.cmd_qte, pmp_commande.cmd_typefuel, jjj_users.name,
					pmp_commande.cmd_dt, pmp_commande.cmd_status, pmp_utilisateur.nom, pmp_utilisateur.prenom,
					pmp_utilisateur.code_postal, pmp_utilisateur.ville, jjj_users.email, 
					pmp_utilisateur.tel_fixe, pmp_utilisateur.tel_port, pmp_utilisateur.user_id, pmp_commande.groupe_cmd
				FROM pmp_utilisateur, pmp_commande, pmp_zone_cp, pmp_code_postal, jjj_users
				WHERE jjj_users.id = pmp_utilisateur.user_id
					AND jjj_users.id = pmp_commande.user_id
					AND pmp_utilisateur.inscrit = 1
					AND pmp_utilisateur.disabled_account = 0
					AND pmp_utilisateur.internet = 1
					AND pmp_commande.cmd_status BETWEEN '$etat_1' AND '$etat_2'
					AND pmp_commande.cmd_status != '13'
					AND pmp_utilisateur.user_id > 77
					AND pmp_zone_cp.zone_id = '$id'
					AND pmp_commande.groupe_cmd != '$id_grp'
					AND pmp_zone_cp.code_postal_id = pmp_code_postal.id
					AND pmp_utilisateur.code_postal = pmp_code_postal.code_postal
					AND pmp_zone_cp.actif = 1
				ORDER BY name
			";
		}

	} else {
		$query = "
			SELECT pmp_commande.id AS num_cmd, pmp_commande.cmd_qte, pmp_commande.cmd_typefuel, jjj_users.name,
				pmp_commande.cmd_dt, pmp_commande.cmd_status, pmp_utilisateur.nom, pmp_utilisateur.prenom,
				pmp_utilisateur.code_postal, pmp_utilisateur.ville, jjj_users.email, 
				pmp_utilisateur.tel_fixe, pmp_utilisateur.tel_port, pmp_utilisateur.user_id, pmp_commande.groupe_cmd
			FROM pmp_utilisateur, pmp_commande, pmp_zone_cp, pmp_code_postal, jjj_users
			WHERE pmp_utilisateur.internet = 0
				AND jjj_users.id = pmp_utilisateur.user_id
				AND pmp_utilisateur.inscrit = 1
				AND pmp_utilisateur.disabled_account = 0
				AND pmp_utilisateur.user_id = pmp_commande.user_id
				AND pmp_utilisateur.user_id > 1000010
				AND pmp_commande.cmd_status BETWEEN '$etat_1' AND '$etat_2'
				AND pmp_zone_cp.zone_id = '$id'
				AND pmp_zone_cp.code_postal_id = pmp_code_postal.id
				AND pmp_utilisateur.code_postal = pmp_code_postal.code_postal
				AND pmp_zone_cp.actif = 1

			UNION

			SELECT pmp_commande.id AS num_cmd, pmp_commande.cmd_qte, pmp_commande.cmd_typefuel, jjj_users.name,
				pmp_commande.cmd_dt, pmp_commande.cmd_status, pmp_utilisateur.nom, pmp_utilisateur.prenom,
				pmp_utilisateur.code_postal, pmp_utilisateur.ville, jjj_users.email, 
				pmp_utilisateur.tel_fixe, pmp_utilisateur.tel_port, pmp_utilisateur.user_id, pmp_commande.groupe_cmd
			FROM pmp_utilisateur, pmp_commande, pmp_zone_cp, pmp_code_postal, jjj_users
			WHERE jjj_users.id = pmp_utilisateur.user_id
				AND jjj_users.id = pmp_commande.user_id
				AND pmp_utilisateur.inscrit = 1
				AND pmp_utilisateur.disabled_account = 0
				AND pmp_utilisateur.internet = 1
				AND pmp_commande.cmd_status BETWEEN '$etat_1' AND '$etat_2'
				AND pmp_utilisateur.user_id > 77
				AND pmp_zone_cp.zone_id = '$id'
				AND pmp_zone_cp.code_postal_id = pmp_code_postal.id
				AND pmp_utilisateur.code_postal = pmp_code_postal.code_postal
				AND pmp_zone_cp.actif = 1
			UNION
			";

	}

	$res = my_query($co_pmp, $query);
	return $res;
}

function getCommandesFournisseurZoneStatus(&$co_pmp, $id, $status)
{
	$query = "  SELECT SUM(pmp_commande.cmd_qte) AS vol
				FROM pmp_utilisateur, pmp_commande,  pmp_zone_cp, pmp_code_postal, jjj_users
				WHERE pmp_utilisateur.internet = 0
				AND jjj_users.id = pmp_utilisateur.user_id
				AND pmp_utilisateur.inscrit = 1
			    AND pmp_utilisateur.user_id = pmp_commande.user_id
			    AND  pmp_commande.cmd_status = $status
			    AND pmp_utilisateur.user_id > 1000010
			    AND pmp_zone_cp.zone_id = '$id'
			    AND pmp_zone_cp.code_postal_id = pmp_code_postal.id
			    AND pmp_utilisateur.code_postal = pmp_code_postal.code_postal
			    AND  pmp_zone_cp.actif = 1
			   ";

	$res = my_query($co_pmp, $query);
	$vol = mysqli_fetch_array($res);
	return $vol;
}

function getCommandesFournisseurZoneStatus2(&$co_pmp, $id, $status)
{
	$query = " SELECT SUM(pmp_commande.cmd_qte) AS vol
	FROM pmp_utilisateur, pmp_commande,  pmp_zone_cp, pmp_code_postal, jjj_users
	WHERE jjj_users.id = pmp_utilisateur.user_id
	and jjj_users.id = pmp_commande.user_id
	and pmp_utilisateur.inscrit = 1
	and pmp_utilisateur.internet = 1
	and  pmp_commande.cmd_status = $status
	and pmp_utilisateur.user_id > 77
	and pmp_zone_cp.zone_id = '$id'
	and pmp_zone_cp.code_postal_id = pmp_code_postal.id
	and pmp_utilisateur.code_postal = pmp_code_postal.code_postal
	and  pmp_zone_cp.actif=1	";
	$res = my_query($co_pmp, $query);
	$vol = mysqli_fetch_array($res);
	return $vol;
}

function getCommandeNumero(&$co_pmp, $id_cmd)
{
	$query = "  SELECT pmp_commande.id AS num_cmd, pmp_commande.cmd_qte,jjj_users.name, pmp_commande.cmd_typefuel, pmp_commande.cmd_dt, pmp_commande.cmd_status, pmp_utilisateur.nom, pmp_utilisateur.prenom, pmp_utilisateur.code_postal, pmp_utilisateur.ville, jjj_users.email, pmp_utilisateur.tel_fixe, pmp_utilisateur.tel_port, pmp_utilisateur.user_id
				FROM pmp_commande, pmp_utilisateur, jjj_users
				WHERE pmp_utilisateur.user_id = pmp_commande.user_id
				AND pmp_utilisateur.user_id = jjj_users.id
				AND pmp_commande.id = '$id_cmd'				 ";
	$res = my_query($co_pmp, $query);
	return $res;
}

//Afficher les commandes d'une recherche rapide depuis le tableau de bord
function getCommandeRapide(&$co_pmp)
{
	$filtres = "";
	if(isset($_SESSION['etat_1']))
	{
		$client = $_SESSION['n_client'];
		$etat_1 = $_SESSION["etat_1"];
		$etat_2 = $_SESSION["etat_2"];
		if($etat_2 == 0) { $etat_2 = "10"; }
		$filtres .= "AND pmp_commande.cmd_status BETWEEN '$etat_1' AND '$etat_2'";
	}
	if(isset($_SESSION['n_client'])) { if(strlen($_SESSION['n_client']) > 0) { $client = $_SESSION['n_client']; $filtres .= "AND pmp_utilisateur.user_id = '$client'"; } }
	if(isset($_SESSION['cp_client'])) { if(strlen($_SESSION['cp_client']) > 0) { $client = $_SESSION['cp_client']; $filtres .= "AND pmp_utilisateur.code_postal = '$client'"; } }
	if(isset($_SESSION['nom_client'])) { if(strlen($_SESSION['nom_client']) > 0) { $client = strtoupper($_SESSION['nom_client']); $filtres .= "AND jjj_users.name = '$client'"; } }
	if(isset($_SESSION['p_client'])) { if(strlen($_SESSION['p_client']) > 0) { $client = $_SESSION['p_client']; $filtres .= "AND pmp_utilisateur.prenom = '$client'"; } }
	if(isset($_SESSION['date_min_co']))
	{
		if(isset($_SESSION['date_max_co']))
		{
			$date_min = date_format(new DateTime($_SESSION["date_min_co"]), 'Y-m-d H:i:s' );
			$date_max = date_format(new DateTime($_SESSION["date_max_co"]), 'Y-m-d H:i:s' );
			$filtres .= "AND pmp_commande.cmd_dt BETWEEN '$date_min' AND '$date_max'";
		}
	}
	if(isset($_SESSION['tel_client']))
	{
		if(strlen($_SESSION['tel_client']) > 0)
		{
			$res = $_SESSION["tel_client"];
			$client = substr($res,0,2) . '.' . substr($res,2,2) . '.' . substr($res,4,2) . '.' . substr($res,6,2) . '.' . substr($res,8,2);
			$filtres = "AND pmp_utilisateur.tel_fixe='$client' OR pmp_utilisateur.tel_port='$client'";
		}
	}

	if(isset($_SESSION['email_client']))
	{
		if(strlen($_SESSION['email_client']) > 0) { $client = $_SESSION['email_client']; $filtres .= "AND jjj_users.email = '$client'"; }
	}

	$query = "  SELECT pmp_commande.id AS num_cmd, pmp_commande.cmd_qte, pmp_commande.cmd_qtelivre, jjj_users.name, pmp_commande.cmd_typefuel, pmp_commande.cmd_dt, pmp_commande.cmd_status, pmp_utilisateur.nom, pmp_utilisateur.prenom, pmp_utilisateur.code_postal, pmp_utilisateur.ville, jjj_users.email, pmp_utilisateur.tel_fixe, pmp_utilisateur.tel_port, pmp_utilisateur.user_id, pmp_fournisseur.nom AS nom_four
				FROM pmp_commande, pmp_utilisateur, jjj_users, pmp_regroupement, pmp_fournisseur
				WHERE pmp_utilisateur.user_id = pmp_commande.user_id
				AND pmp_utilisateur.user_id = jjj_users.id
				AND pmp_commande.groupe_cmd = pmp_regroupement.id
				AND pmp_regroupement.id_four = pmp_fournisseur.id
				$filtres
				ORDER BY jjj_users.name ";
	$res = my_query($co_pmp, $query);
	return $res;
}

function getCommandeRapideActifs(&$co_pmp)
{
	$filtres = "";
	if(isset($_SESSION['etat_1']))
	{
		$client = $_SESSION['n_client'];
		$etat_1 = $_SESSION["etat_1"];
		$etat_2 = $_SESSION["etat_2"];
		if($etat_2 == 0) { $etat_2 = "10"; }
		$filtres .= "AND pmp_commande.cmd_status BETWEEN '$etat_1' AND '$etat_2'";
	}
	if(isset($_SESSION['n_client'])) { if(strlen($_SESSION['n_client']) > 0) { $client = $_SESSION['n_client']; $filtres .= "AND pmp_utilisateur.user_id = '$client'"; } }
	if(isset($_SESSION['cp_client'])) { if(strlen($_SESSION['cp_client']) > 0) { $client = $_SESSION['cp_client']; $filtres .= "AND pmp_utilisateur.code_postal = '$client'"; } }
	if(isset($_SESSION['nom_client'])) { if(strlen($_SESSION['nom_client']) > 0) { $client = strtoupper($_SESSION['nom_client']); $filtres .= "AND jjj_users.name = '$client'"; } }
	if(isset($_SESSION['p_client'])) { if(strlen($_SESSION['p_client']) > 0) { $client = $_SESSION['p_client']; $filtres .= "AND pmp_utilisateur.prenom = '$client'"; } }
	if(isset($_SESSION['date_min_co']))
	{
		if(isset($_SESSION['date_max_co']))
		{
			$date_min = date_format(new DateTime($_SESSION["date_min_co"]), 'Y-m-d H:i:s' );
			$date_max = date_format(new DateTime($_SESSION["date_max_co"]), 'Y-m-d H:i:s' );
			$filtres .= "AND pmp_commande.cmd_dt BETWEEN '$date_min' AND '$date_max'";
		}
	}
	if(isset($_SESSION['tel_client']))
	{
		if(strlen($_SESSION['tel_client']) > 0)
		{
			$res = $_SESSION["tel_client"];
			$client = substr($res,0,2) . '.' . substr($res,2,2) . '.' . substr($res,4,2) . '.' . substr($res,6,2) . '.' . substr($res,8,2);
			$filtres = "AND pmp_utilisateur.tel_fixe='$client' OR pmp_utilisateur.tel_port='$client'";
		}
	}

	if(isset($_SESSION['email_client']))
	{
		if(strlen($_SESSION['email_client']) > 0) { $client = $_SESSION['email_client']; $filtres .= "AND jjj_users.email = '$client'"; }
	}

	$query = "  SELECT pmp_commande.id AS num_cmd, pmp_commande.cmd_qte, pmp_commande.cmd_qtelivre, jjj_users.name, pmp_commande.cmd_typefuel, pmp_commande.cmd_dt, pmp_commande.cmd_status, pmp_utilisateur.nom, pmp_utilisateur.prenom, pmp_utilisateur.code_postal, pmp_utilisateur.ville, jjj_users.email, pmp_utilisateur.tel_fixe, pmp_utilisateur.tel_port, pmp_utilisateur.user_id, pmp_fournisseur.nom AS nom_four
				FROM pmp_commande, pmp_utilisateur, jjj_users, pmp_regroupement, pmp_fournisseur
				WHERE pmp_utilisateur.user_id = pmp_commande.user_id
				AND pmp_utilisateur.user_id = jjj_users.id
				AND pmp_commande.groupe_cmd = pmp_regroupement.id
				AND pmp_regroupement.id_four = pmp_fournisseur.id
				AND COALESCE(pmp_utilisateur.disabled_account, 0) = 0
				$filtres
				ORDER BY jjj_users.name ";
	$res = my_query($co_pmp, $query);
	return $res;
}

// Exporter les commandes en exels
function exporterListeCommandes($co_pmp, $res)
{
	$fichier = fopen('export/export-commandes.csv', 'w');
	fclose($fichier);

	$fichier = fopen('export/export-commandes.csv', 'w+');
	fwrite($fichier, chr(239) . chr(187) . chr(191));
	$chaine = "";
	$col = "Nb Litre;Livrée;Type Fuel;Date;CP;Ville;Nom;Prenom;Mail;Tel;Portable;N° Client;N° CMD;Etat CMD;Fournisseur";
	fwrite($fichier,$col."\r\n");

	while($export = mysqli_fetch_array($res))
	{
		if ($export["cmd_typefuel"] == 1){ $fuel = 'O';}
		if ($export["cmd_typefuel"] == 2){ $fuel = 'S';}
		if ($export["cmd_typefuel"] == 3){ $fuel = 'GNR';}
		$date = date_format(new DateTime($export['cmd_dt']), 'd/m/Y' );

		if($export["nom"] == "") { $nom = $export["name"]; } else { $nom = $export["nom"]; }

		$chaine = '"' . $export["cmd_qte"] . '";"' . $export["cmd_qtelivre"] . '";"' . $fuel . '";"' . $date . '";"' . $export["code_postal"] . '";"' . $export["ville"] . '";"' . $nom
		. '";"' . $export["prenom"] . '";"' . $export["email"] . '";"' . $export["tel_fixe"] . '";"' . $export["tel_port"] . '";"' . $export["user_id"] . '";"' . $export["num_cmd"]
		. '";"' . $export["cmd_status"] . '";"' . $export["nom_four"] . '"';

		fwrite($fichier,$chaine."\r\n");
	}

	fclose($fichier);

	header('Location: /admin/export/export-commandes.csv');
	header('Content-Encoding: UTF-8');
	header('Content-type: text/csv; charset=UTF-8');
}

//Détails d'une commande client
function getCommandeDetailsClients(&$co_pmp, $id)
{
	$query = "  SELECT pmp_commande.id AS num_cmd, pmp_commande.cmd_qte, pmp_commande.cmd_typefuel, pmp_commande.cmd_dt,pmp_commande.cmd_prix_ord,pmp_commande.cmd_prix_sup, pmp_commande.cmd_status, pmp_commande.cmd_comment, pmp_utilisateur.nom, pmp_utilisateur.prenom, pmp_utilisateur.code_postal, pmp_utilisateur.ville, pmp_utilisateur.email, pmp_utilisateur.tel_fixe, pmp_utilisateur.tel_port, pmp_utilisateur.user_id,
				pmp_commande.cmd_commentfour, pmp_commande.cmd_qtelivre, pmp_fournisseur.id AS four_id, pmp_commande.groupe_cmd, jjj_users.name, jjj_users.email, pmp_commande.cmd_comment_du_four, pmp_fournisseur.nom AS nom_four, pmp_regroupement.libelle
				FROM pmp_commande
				LEFT JOIN pmp_utilisateur
				ON pmp_utilisateur.user_id = pmp_commande.user_id
				LEFT JOIN pmp_regroupement
				ON pmp_commande.groupe_cmd = pmp_regroupement.id
				LEFT JOIN pmp_fournisseur
				ON pmp_regroupement.id_four = pmp_fournisseur.id
				LEFT JOIN jjj_users
				ON pmp_utilisateur.user_id = jjj_users.id
				WHERE pmp_commande.id = '$id' ";
	$res = my_query($co_pmp, $query);
	$cmd_details = mysqli_fetch_array($res);
	return $cmd_details;
}


function getCommandeDetailsClientsActifs(&$co_pmp, $id)
{
	$query = "  SELECT pmp_commande.id AS num_cmd, pmp_commande.cmd_qte, pmp_commande.cmd_typefuel, pmp_commande.cmd_dt,pmp_commande.cmd_prix_ord,pmp_commande.cmd_prix_sup, pmp_commande.cmd_status, pmp_commande.cmd_comment, pmp_utilisateur.nom, pmp_utilisateur.prenom, pmp_utilisateur.code_postal, pmp_utilisateur.ville, pmp_utilisateur.email, pmp_utilisateur.tel_fixe, pmp_utilisateur.tel_port, pmp_utilisateur.user_id,
				pmp_commande.cmd_commentfour, pmp_commande.cmd_qtelivre, pmp_fournisseur.id AS four_id, pmp_commande.groupe_cmd, jjj_users.name, jjj_users.email, pmp_commande.cmd_comment_du_four, pmp_fournisseur.nom AS nom_four, pmp_regroupement.libelle
				FROM pmp_commande
				LEFT JOIN pmp_utilisateur
				ON pmp_utilisateur.user_id = pmp_commande.user_id
				LEFT JOIN pmp_regroupement
				ON pmp_commande.groupe_cmd = pmp_regroupement.id
				LEFT JOIN pmp_fournisseur
				ON pmp_regroupement.id_four = pmp_fournisseur.id
				LEFT JOIN jjj_users
				ON pmp_utilisateur.user_id = jjj_users.id
				WHERE pmp_commande.id = '$id'
				AND COALESCE(pmp_utilisateur.disabled_account, 0) = 0";
	$res = my_query($co_pmp, $query);
	$cmd_details = mysqli_fetch_array($res);
	return $cmd_details;
}

//Commentaire historique d'une commande client
function getCommentaireHisto(&$co_pmp,$id)
{
	$query = "  SELECT *
				FROM pmp_commande_histo
				WHERE cmd_id = '$id'
				ORDER BY his_date DESC";
	$res = my_query($co_pmp, $query);
	return $res;
}

function TraceHisto(&$co_pmp, $id, $param1, $param2)
{
	$user = $_SESSION['user'];
	$param1 = mysqli_real_escape_string($co_pmp, $param1);
	$param2 = mysqli_real_escape_string($co_pmp, $param2);
	$query = "  INSERT INTO pmp_commande_histo (cmd_id, his_intervenant, his_date, his_action, his_valeur)
				VALUES ('$id', '$user', NOW(), '$param1', '$param2') ";
	$res = my_query($co_pmp, $query);
}

function TraceHistoGrpt(&$co_pmp, $id, $param1, $param2)
{
    $user = $_SESSION['user'];

    // S'assurer que ce sont des strings
    $param1 = is_array($param1) ? json_encode($param1) : $param1;
    $param2 = is_array($param2) ? json_encode($param2) : $param2;

    // Échappement sécurisé
    $param1 = mysqli_real_escape_string($co_pmp, $param1);
    $param2 = mysqli_real_escape_string($co_pmp, $param2);

    $query = "INSERT INTO pmp_regroupement_histo 
              (grp_id, hisg_intervenant, hisg_date, hisg_action, hisg_valeur)
              VALUES ('$id', '$user', NOW(), '$param1', '$param2')";

    $res = my_query($co_pmp, $query);
}

function checkSMSEnvoye($co_pmp, $id)
{
	$query = "  SELECT id
	 			FROM pmp_sms
				WHERE cmd_id = '" . mysqli_real_escape_string($co_pmp, $id) . "' ";
	$res = my_query($co_pmp, $query);
	return mysqli_fetch_array($res);
}
function AnnulerSMS(&$co_pmp, $utilisateur, $commande)
{
    // Si $commande est un tableau avec 'id', on prend la valeur, sinon si c'est déjà une string, on la garde
    if (is_array($commande) && isset($commande['id'])) {
        $cmd_id = $commande['id'];
    } elseif (is_string($commande)) {
        $cmd_id = $commande;
    } else {
        throw new Exception('Commande invalide');
    }

    $cmd_id = mysqli_real_escape_string($co_pmp, $cmd_id);

    $query = "UPDATE pmp_sms SET etat = '2' WHERE etat = '0' AND cmd_id = '$cmd_id'";
    $res = my_query($co_pmp, $query);

    // Téléphone
    $tel = '';
    if (is_array($utilisateur)) {
        $tel = $utilisateur['tel_port'] ?? $utilisateur['tel_fixe'] ?? '';
    }

    $tel = mysqli_real_escape_string($co_pmp, $tel);
    TraceHisto($co_pmp, $cmd_id, 'SMS Annulé', $tel);
}

if (!empty($_POST["modifier_commande"]) || !empty($_POST["valide_modifier_commande"]))
{
	$id_cmd = $_GET['id_cmd'];
	$qte_livree = $_POST["qte_livree"];
	$cmd_qte = $_POST["cmd_qt"];
	$type_fioul = $_POST["cmd_fioul"];
	$etat = $_POST["etat_1"];
	$cmt_four = $_POST["com_four"];
	$commande_details = getCommandeDetailsClients($co_pmp, $id_cmd);
	$com_client = $_POST["com_client"];

	if ($type_fioul == "Ordinaire"){$type = "1";}
	if ($type_fioul == "Supérieur"){$type = "2";}
	if ($type_fioul == "GNR"){$type = "3";}

	if (!empty($_POST["com_histo"]))
	{
		$com_histo = $_POST["com_histo"];
		$com_histo = mysqli_real_escape_string($co_pmp, $com_histo);
		TraceHisto($co_pmp, $id_cmd, 'Commentaire', $com_histo);
		$message_type = "success";
		$message_icone = "fa-check";
		$message_titre = "Succès";
		$message = "Le commentaire a été ajouté à l'historique.";
	}

	if ($qte_livree != $commande_details["cmd_qtelivre"])
	{
		$updateCmd = "  UPDATE pmp_commande SET cmd_qtelivre = '$qte_livree' WHERE id = '$id_cmd' ";
		$res = my_query($co_pmp, $updateCmd);
		TraceHisto($co_pmp, $id_cmd, 'Quantité Livrée', $qte_livree . " L");
		$message_type = "success";
		$message_icone = "fa-check";
		$message_titre = "Succès";
		$message = "La commande a été modifiée.";
	}

	if($etat != $commande_details["cmd_status"])
	{
		$updateCmd = "  UPDATE pmp_commande SET cmd_status = '$etat' WHERE id = '$id_cmd' ";
		$sms = checkSMSEnvoye($co_pmp, $id_cmd);

		$res = my_query($co_pmp, $updateCmd);
		if($etat == '10') { $statut = "10 - Utilisateur"; }
		if($etat == '12') { $statut = "12 - Attachée"; }
		if($etat == '13') { $statut = "13 - Proposée"; }
		if($etat == '15') { $statut = "15 - Groupée"; }
		if($etat == '17') { $statut = "17 - Prix proposé"; }
		if($etat == '20') { $statut = "20 - Prix validé"; }
		if($etat == '25') { $statut = "25 - Livrable"; }
		if($etat == '30') { $statut = "30 - Livrée"; }
		if($etat == '40') { $statut = "40 - Terminée"; }
		if($etat == '50') { $statut = "50 - Annulée"; if(isset($sms["id"])) { AnnulerSMS($co_pmp, $commande_details, $id_cmd); } }
		if($etat == '52') { $statut = "52 - Annulée / Livraison"; if(isset($sms["id"])) { AnnulerSMS($co_pmp, $commande_details, $id_cmd); } }
		if($etat == '55') { $statut = "55 - Annulée / Prix"; if(isset($sms["id"])) { AnnulerSMS($co_pmp, $commande_details, $id_cmd); } }
		if($etat == '99') { $statut = "99 - Annulée / Compte désactivé"; if(isset($sms["id"])) { AnnulerSMS($co_pmp, $commande_details, $id_cmd); } }
		TraceHisto($co_pmp, $id_cmd, 'Statut', $statut);

		$message_type = "success";
		$message_icone = "fa-check";
		$message_titre = "Succès";
		$message = "La commande a été modifiée.";
	}

	if ($cmt_four != $commande_details["cmd_commentfour"])
	{
		$cmt_four = mysqli_real_escape_string($co_pmp, $cmt_four);
		$updateCmd = "  UPDATE pmp_commande SET cmd_commentfour = '$cmt_four' WHERE id = '$id_cmd' ";
		$res = my_query($co_pmp, $updateCmd);
		TraceHisto($co_pmp, $id_cmd, 'Commentaire pour Fournisseur', $cmt_four);
		$message_type = "success";
		$message_icone = "fa-check";
		$message_titre = "Succès";
		$message = "La commande a été modifiée.";
	}

	if ($com_client != $commande_details["cmd_comment"])
	{
		$com_client = mysqli_real_escape_string($co_pmp, $com_client);
		$updateCmd = "  UPDATE pmp_commande SET cmd_comment = '$com_client' WHERE id = '$id_cmd' ";
		$res = my_query($co_pmp, $updateCmd);
		TraceHisto($co_pmp, $id_cmd, 'Commentaire client', $com_client);
		$message_type = "success";
		$message_icone = "fa-check";
		$message_titre = "Succès";
		$message = "La commande a été modifiée.";
	}

	if ($cmd_qte != $commande_details["cmd_qte"])
	{
		$updateCmd = "  UPDATE pmp_commande SET cmd_qte = '$cmd_qte' WHERE id = '$id_cmd' ";
		$res = my_query($co_pmp, $updateCmd);
		TraceHisto($co_pmp, $id_cmd, 'Quantité', $cmd_qte . " L");
		$message_type = "success";
		$message_icone = "fa-check";
		$message_titre = "Succès";
		$message = "La commande a été modifiée.";
	}

	if ($type != $commande_details["cmd_typefuel"])
	{
		$updateCmd = "  UPDATE pmp_commande SET cmd_typefuel = '$type' WHERE id = '$id_cmd' ";
		$res = my_query($co_pmp, $updateCmd);
		TraceHisto($co_pmp, $id_cmd, 'Type Fuel', $type_fioul);
		$message_type = "success";
		$message_icone = "fa-check";
		$message_titre = "Succès";
		$message = "La commande a été modifiée.";
	}

	if($commande_details['cmd_prix_ord'] > 0 && $commande_details['cmd_prix_ord'] != NULL && $cmd_qte > 0)
	{
		$plages = ChargePlagesPrix($co_pmp, $commande_details["groupe_cmd"], $cmd_qte);
		if(($commande_details['cmd_prix_ord'] != $plages['prix_ord']) || ($commande_details['cmd_prix_sup'] != $plages['prix_sup']))
		{
			$prixLitre = number_format($plages['prix_ord'] / 1000,3,","," ");
			TraceHisto($co_pmp, $id_cmd, 'Prix litre Ord', $prixLitre);
			$prixLitre = number_format($plages['prix_sup'] / 1000,3,","," ");
			TraceHisto($co_pmp, $id_cmd, 'Prix litre Sup', $prixLitre);

			$updateCmd = "  UPDATE pmp_commande SET cmd_prix_ord='" . $plages['prix_ord'] . "', cmd_prix_sup='" . $plages['prix_sup'] . "' WHERE id = '$id_cmd' ";
			$res = my_query($co_pmp, $updateCmd);
		}
	}
}

//Afficher la fiche détails d'un groupement
function getGroupementDetails(&$co_pmp, $id)
{
	$query = "  SELECT pmp_regroupement.id, pmp_regroupement.libelle, pmp_regroupement.date_grp, pmp_regroupement.id_four, pmp_regroupement.statut, pmp_regroupement.commentaire, pmp_regroupement.mtcomordht, pmp_regroupement.mtcomsupht,pmp_regroupement.mtcomord, pmp_regroupement.mtcomsup, pmp_regroupement.responsable, pmp_regroupement.volord, pmp_regroupement.volsup, pmp_regroupement.numfact,
				pmp_regroupement.planning, pmp_regroupement.infofour, pmp_regroupement.mtfactht, pmp_regroupement.mtfact
				FROM pmp_regroupement
				WHERE pmp_regroupement.id = '$id' ";
	$res = my_query($co_pmp, $query);
	$grp = mysqli_fetch_array($res);
	return $grp;
}

function getGroupementFour(&$co_pmp, $id)
{
	$query = "  SELECT pmp_regroupement.id, pmp_regroupement.libelle, pmp_regroupement.date_grp, pmp_regroupement.id_four, pmp_fournisseur.nom, pmp_regroupement.statut, pmp_regroupement.commentaire, pmp_regroupement.mtcomordht, pmp_regroupement.mtcomsupht,pmp_regroupement.mtcomord, pmp_regroupement.mtcomsup, pmp_regroupement.responsable, pmp_regroupement.volord, pmp_regroupement.volsup, pmp_regroupement.numfact,
				pmp_regroupement.planning, pmp_regroupement.infofour, pmp_regroupement.mtfactht, pmp_regroupement.mtfact, pmp_fournisseur.grp_email as fact_email, pmp_fournisseur.tel_fixe
				FROM pmp_regroupement, pmp_fournisseur
				WHERE pmp_fournisseur.id = pmp_regroupement.id_four
				AND pmp_regroupement.id = '$id' ";
	$res = my_query($co_pmp, $query);
	$grp = mysqli_fetch_array($res);
	return $grp;
}

// Statistiques commandes
function getStatistiquesGroupementsDate(&$co_pmp)
{
	$date_jour = date("Y-m-d");
	$date_max = date_format(new DateTime($date_jour), 'Y-m-d H:i:s' );
	$date_min = date_format(new DateTime($_POST["stats_date"]), 'Y-m-d H:i:s' );

	$query = "  SELECT MONTH(date_grp) AS mois, YEAR(date_grp) AS annee
				FROM pmp_regroupement
				WHERE date_grp BETWEEN '$date_min' AND '$date_max'
				GROUP BY mois ORDER BY date_grp DESC";
	$res = my_query($co_pmp, $query);
	return $res;
}

function getMHTNb(&$co_pmp, $mois, $annee)
{
	$query = "  SELECT sum(MTFACTHT) AS ht, count(*) AS nb
				FROM pmp_regroupement
				WHERE month(date_grp) = '$mois'
				AND year(date_grp) = '$annee'
				AND statut IN (5,10,15,30,33,37,40)  ";
	$res = my_query($co_pmp, $query);
	$res = mysqli_fetch_array($res);
	return $res;
}

function getGroupementsFini(&$co_pmp, $mois, $annee)
{
	$query = "  SELECT count(*) AS nb_fini
                FROM pmp_regroupement
				WHERE month(date_grp) = '$mois'
				AND year(date_grp) = '$annee'
                AND statut = 40 ";
	$res = my_query($co_pmp, $query);
	$res = mysqli_fetch_array($res);
	return $res;
}

function getVolEnvoyeVolLivre(&$co_pmp, $mois, $annee)
{
	$query = "  SELECT sum(CMD_QTE)/1000 AS envoye, sum(CMD_QTELIVRE)/1000 AS livre
    			FROM pmp_commande, pmp_regroupement
                WHERE pmp_commande.groupe_cmd = pmp_regroupement.id
				AND month(date_grp) = '$mois'
				AND year(date_grp) = '$annee'
                AND cmd_status in (25, 30, 40) ";
	$res = my_query($co_pmp, $query);
	$res = mysqli_fetch_array($res);
	return $res;
}

function getVolAnnule(&$co_pmp, $mois, $annee)
{
	$query = "  SELECT sum(CMD_QTE)/1000 AS annule
    			FROM pmp_commande, pmp_regroupement
                WHERE pmp_commande.groupe_cmd = pmp_regroupement.id
				AND month(date_grp) = '$mois'
				AND year(date_grp) = '$annee'
                AND statut in (50, 52, 55) ";
	$res = my_query($co_pmp, $query);
	$res = mysqli_fetch_array($res);
	return $res;
}

function getStatistiquesAnnuelle(&$co_pmp, $annee1)
{
	$query = "  SELECT YEAR(date_grp) AS annee
				FROM pmp_regroupement
				WHERE YEAR(date_grp) BETWEEN '2011' AND '$annee1'
				GROUP BY annee ORDER BY date_grp DESC
				";
	$res = my_query($co_pmp, $query);
	return $res;
}

function getVolumeInf1000(&$co_pmp)
{
	$query = "  SELECT year(date_grp) as annee, sum(CMD_QTE)/1000 as envoye, sum(CMD_QTELIVRE)/1000 as livre, count(*) as nb
    			from pmp_commande, pmp_regroupement
    			where pmp_commande.groupe_cmd = pmp_regroupement.id
    			and cmd_status in (25, 30, 40)
    			and cmd_qte <1000
  				group by year(date_grp) ";
  $res = my_query($co_pmp, $query);
  return $res;
}

function getVolumeEntre1000Et2000(&$co_pmp)
{
	$query = "  SELECT year(date_grp) as annee, sum(CMD_QTE)/1000 as envoye, sum(CMD_QTELIVRE)/1000 as livre, count(*) as nb
    			from pmp_commande, pmp_regroupement
    			where pmp_commande.groupe_cmd = pmp_regroupement.id
    			and cmd_status in (25, 30, 40)
    			and cmd_qte BETWEEN 1000 AND 2000
  				group by year(date_grp) ";
	$res = my_query($co_pmp, $query);
	return $res;
}

function getVolumeEntre2000Et3000(&$co_pmp)
{
	$query = "  SELECT year(date_grp) as annee, sum(CMD_QTE)/1000 as envoye, sum(CMD_QTELIVRE)/1000 as livre, count(*) as nb
    			from pmp_commande, pmp_regroupement
    			where pmp_commande.groupe_cmd = pmp_regroupement.id
    			and cmd_status in (25, 30, 40)
    			and cmd_qte BETWEEN 2000 AND 3000
  				group by year(date_grp) ";
				$res = my_query($co_pmp, $query);
				return $res;
}

function getVolumeSup3000(&$co_pmp)
{
	$query = "  SELECT year(date_grp) as annee, sum(CMD_QTE)/1000 as envoye, sum(CMD_QTELIVRE)/1000 as livre, count(*) as nb
    			from pmp_commande, pmp_regroupement
    			where pmp_commande.groupe_cmd = pmp_regroupement.id
    			and cmd_status in (25, 30, 40)
    			and cmd_qte >3000
  				group by year(date_grp) ";
				$res = my_query($co_pmp, $query);
				return $res;
}
//Calcul nombre de client par département
//25 premier
function getNbClientDep25(&$co_pmp)
{
	$query = "  SELECT COUNT(*) AS nb, LEFT(code_postal, 2) AS dep
				FROM pmp_utilisateur
				WHERE ( code_postal LIKE '01%'
					OR code_postal LIKE '02%'
					OR code_postal LIKE '03%'
					OR code_postal LIKE '04%'
					OR code_postal LIKE '05%'
					OR code_postal LIKE '06%'
					OR code_postal LIKE '07%'
					OR code_postal LIKE '08%'
					OR code_postal LIKE '09%'
					OR code_postal LIKE '10%'
					OR code_postal LIKE '11%'
					OR code_postal LIKE '12%'
					OR code_postal LIKE '13%'
					OR code_postal LIKE '14%'
					OR code_postal LIKE '15%'
					OR code_postal LIKE '16%'
					OR code_postal LIKE '17%'
					OR code_postal LIKE '18%'
					OR code_postal LIKE '19%'
					OR code_postal LIKE '20%'
					OR code_postal LIKE '21%'
					OR code_postal LIKE '22%'
					OR code_postal LIKE '23%'
					OR code_postal LIKE '24%'
					OR code_postal LIKE '25%' )
				GROUP BY dep ";
	$res = my_query($co_pmp, $query);
	return $res;
}
function getTotalClientDep25(&$co_pmp)
{
	$query = "  SELECT COUNT(*) AS total
				FROM pmp_utilisateur
				WHERE ( code_postal LIKE '01%'
					OR code_postal LIKE '02%'
					OR code_postal LIKE '03%'
					OR code_postal LIKE '04%'
					OR code_postal LIKE '05%'
					OR code_postal LIKE '06%'
					OR code_postal LIKE '07%'
					OR code_postal LIKE '08%'
					OR code_postal LIKE '09%'
					OR code_postal LIKE '10%'
					OR code_postal LIKE '11%'
					OR code_postal LIKE '12%'
					OR code_postal LIKE '13%'
					OR code_postal LIKE '14%'
					OR code_postal LIKE '15%'
					OR code_postal LIKE '16%'
					OR code_postal LIKE '17%'
					OR code_postal LIKE '18%'
					OR code_postal LIKE '19%'
					OR code_postal LIKE '20%'
					OR code_postal LIKE '21%'
					OR code_postal LIKE '22%'
					OR code_postal LIKE '23%'
					OR code_postal LIKE '24%'
					OR code_postal LIKE '25%' ) ";
	$res = my_query($co_pmp, $query);
	$res = mysqli_fetch_array($res);
	return $res;
}
//50
function getNbClientDep50(&$co_pmp)
{
	$query = "  SELECT COUNT(*) AS nb, LEFT(code_postal, 2) AS dep
				FROM pmp_utilisateur
				WHERE ( code_postal LIKE '26%'
					OR code_postal LIKE '27%'
					OR code_postal LIKE '28%'
					OR code_postal LIKE '29%'
					OR code_postal LIKE '30%'
					OR code_postal LIKE '31%'
					OR code_postal LIKE '32%'
					OR code_postal LIKE '33%'
					OR code_postal LIKE '34%'
					OR code_postal LIKE '35%'
					OR code_postal LIKE '36%'
					OR code_postal LIKE '37%'
					OR code_postal LIKE '38%'
					OR code_postal LIKE '39%'
					OR code_postal LIKE '40%'
					OR code_postal LIKE '41%'
					OR code_postal LIKE '42%'
					OR code_postal LIKE '43%'
					OR code_postal LIKE '44%'
					OR code_postal LIKE '45%'
					OR code_postal LIKE '46%'
					OR code_postal LIKE '47%'
					OR code_postal LIKE '48%'
					OR code_postal LIKE '49%'
					OR code_postal LIKE '50%' )
				GROUP BY dep ";
	$res = my_query($co_pmp, $query);
	return $res;
}
function getTotalClientDep50(&$co_pmp)
{
	$query = "  SELECT COUNT(*) AS total
				FROM pmp_utilisateur
				WHERE ( code_postal LIKE '26%'
					OR code_postal LIKE '27%'
					OR code_postal LIKE '28%'
					OR code_postal LIKE '29%'
					OR code_postal LIKE '30%'
					OR code_postal LIKE '31%'
					OR code_postal LIKE '32%'
					OR code_postal LIKE '33%'
					OR code_postal LIKE '34%'
					OR code_postal LIKE '35%'
					OR code_postal LIKE '36%'
					OR code_postal LIKE '37%'
					OR code_postal LIKE '38%'
					OR code_postal LIKE '39%'
					OR code_postal LIKE '40%'
					OR code_postal LIKE '41%'
					OR code_postal LIKE '42%'
					OR code_postal LIKE '43%'
					OR code_postal LIKE '44%'
					OR code_postal LIKE '45%'
					OR code_postal LIKE '46%'
					OR code_postal LIKE '47%'
					OR code_postal LIKE '48%'
					OR code_postal LIKE '49%'
					OR code_postal LIKE '50%' ) ";
	$res = my_query($co_pmp, $query);
	$res = mysqli_fetch_array($res);
	return $res;
}
//75
function getNbClientDep75(&$co_pmp)
{
	$query = "  SELECT COUNT(*) AS nb, LEFT(code_postal, 2) AS dep
				FROM pmp_utilisateur
				WHERE ( code_postal LIKE '51%'
					OR code_postal LIKE '52%'
					OR code_postal LIKE '53%'
					OR code_postal LIKE '54%'
					OR code_postal LIKE '55%'
					OR code_postal LIKE '56%'
					OR code_postal LIKE '57%'
					OR code_postal LIKE '58%'
					OR code_postal LIKE '59%'
					OR code_postal LIKE '60%'
					OR code_postal LIKE '61%'
					OR code_postal LIKE '62%'
					OR code_postal LIKE '63%'
					OR code_postal LIKE '64%'
					OR code_postal LIKE '65%'
					OR code_postal LIKE '66%'
					OR code_postal LIKE '67%'
					OR code_postal LIKE '68%'
					OR code_postal LIKE '69%'
					OR code_postal LIKE '70%'
					OR code_postal LIKE '71%'
					OR code_postal LIKE '72%'
					OR code_postal LIKE '73%'
					OR code_postal LIKE '74%'
					OR code_postal LIKE '75%' )
				GROUP BY dep ";
	$res = my_query($co_pmp, $query);
	return $res;
}
function getTotalClientDep75(&$co_pmp)
{
	$query = "  SELECT COUNT(*) AS total
				FROM pmp_utilisateur
				WHERE ( code_postal LIKE '51%'
					OR code_postal LIKE '52%'
					OR code_postal LIKE '53%'
					OR code_postal LIKE '54%'
					OR code_postal LIKE '55%'
					OR code_postal LIKE '56%'
					OR code_postal LIKE '57%'
					OR code_postal LIKE '58%'
					OR code_postal LIKE '59%'
					OR code_postal LIKE '60%'
					OR code_postal LIKE '61%'
					OR code_postal LIKE '62%'
					OR code_postal LIKE '63%'
					OR code_postal LIKE '64%'
					OR code_postal LIKE '65%'
					OR code_postal LIKE '66%'
					OR code_postal LIKE '67%'
					OR code_postal LIKE '68%'
					OR code_postal LIKE '69%'
					OR code_postal LIKE '70%'
					OR code_postal LIKE '71%'
					OR code_postal LIKE '72%'
					OR code_postal LIKE '73%'
					OR code_postal LIKE '74%'
					OR code_postal LIKE '75%' ) ";
	$res = my_query($co_pmp, $query);
	$res = mysqli_fetch_array($res);
	return $res;
}
//99
function getNbClientDep99(&$co_pmp)
{
	$query = "  SELECT COUNT(*) AS nb, LEFT(code_postal, 2) AS dep
				FROM pmp_utilisateur
				WHERE ( code_postal LIKE '76%'
					OR code_postal LIKE '77%'
					OR code_postal LIKE '78%'
					OR code_postal LIKE '79%'
					OR code_postal LIKE '80%'
					OR code_postal LIKE '81%'
					OR code_postal LIKE '82%'
					OR code_postal LIKE '83%'
					OR code_postal LIKE '84%'
					OR code_postal LIKE '85%'
					OR code_postal LIKE '86%'
					OR code_postal LIKE '87%'
					OR code_postal LIKE '88%'
					OR code_postal LIKE '89%'
					OR code_postal LIKE '90%'
					OR code_postal LIKE '91%'
					OR code_postal LIKE '92%'
					OR code_postal LIKE '93%'
					OR code_postal LIKE '94%'
					OR code_postal LIKE '95%'
					OR code_postal LIKE '96%'
					OR code_postal LIKE '97%'
					OR code_postal LIKE '98%'
					OR code_postal LIKE '99%' )
				GROUP BY dep ";
	$res = my_query($co_pmp, $query);
	return $res;
}
function getTotalClientDep90(&$co_pmp)
{
	$query = "  SELECT COUNT(*) AS total
				FROM pmp_utilisateur
				WHERE ( code_postal LIKE '76%'
					OR code_postal LIKE '77%'
					OR code_postal LIKE '78%'
					OR code_postal LIKE '79%'
					OR code_postal LIKE '80%'
					OR code_postal LIKE '81%'
					OR code_postal LIKE '82%'
					OR code_postal LIKE '83%'
					OR code_postal LIKE '84%'
					OR code_postal LIKE '85%'
					OR code_postal LIKE '86%'
					OR code_postal LIKE '87%'
					OR code_postal LIKE '88%'
					OR code_postal LIKE '89%'
					OR code_postal LIKE '90%'
					OR code_postal LIKE '91%'
					OR code_postal LIKE '92%'
					OR code_postal LIKE '93%'
					OR code_postal LIKE '94%'
					OR code_postal LIKE '95%'
					OR code_postal LIKE '96%'
					OR code_postal LIKE '97%'
					OR code_postal LIKE '98%'
					OR code_postal LIKE '99%' ) ";
	$res = my_query($co_pmp, $query);
	$res = mysqli_fetch_array($res);
	return $res;
}
//Qte commande departement
function getNbFioulDep25(&$co_pmp)
{
	$query = "  SELECT substr(code_postal,1,2) as dep, sum(cmd_qte) as nb, max(cmd_qte) as cmd_qte
           		from pmp_commande, pmp_utilisateur
          		where cmd_status = 10
             	and pmp_commande.user_id = pmp_utilisateur.user_id
      			group by substr(code_postal,1,2)
					union
      				select LPAD(CAST(pf_departement.id AS CHAR), 2, '0'),0,0
      				from pf_departement
      				where not exists (select 1 from pmp_commande, pmp_utilisateur where cmd_status = 10
                         			  and pmp_commande.user_id = pmp_utilisateur.user_id
                  					  and LPAD(CAST(pf_departement.id AS CHAR), 2, '0') = substr(code_postal,1,2))

      			order by 1
				LIMIT 24";
	$res = my_query($co_pmp, $query);
	return $res;
}

function getTotalFioulDep25(&$co_pmp)
{
	$query = "  SELECT SUM(pmp_commande.cmd_qte) AS total
				FROM pmp_commande, pmp_utilisateur
				WHERE pmp_utilisateur.user_id = pmp_commande.user_id
				AND ( pmp_utilisateur.code_postal LIKE '01%'
					OR pmp_utilisateur.code_postal LIKE '02%'
					OR pmp_utilisateur.code_postal LIKE '03%'
					OR pmp_utilisateur.code_postal LIKE '04%'
					OR pmp_utilisateur.code_postal LIKE '05%'
					OR pmp_utilisateur.code_postal LIKE '06%'
					OR pmp_utilisateur.code_postal LIKE '07%'
					OR pmp_utilisateur.code_postal LIKE '08%'
					OR pmp_utilisateur.code_postal LIKE '09%'
					OR pmp_utilisateur.code_postal LIKE '10%'
					OR pmp_utilisateur.code_postal LIKE '11%'
					OR pmp_utilisateur.code_postal LIKE '12%'
					OR pmp_utilisateur.code_postal LIKE '13%'
					OR pmp_utilisateur.code_postal LIKE '14%'
					OR pmp_utilisateur.code_postal LIKE '15%'
					OR pmp_utilisateur.code_postal LIKE '16%'
					OR pmp_utilisateur.code_postal LIKE '17%'
					OR pmp_utilisateur.code_postal LIKE '18%'
					OR pmp_utilisateur.code_postal LIKE '19%'
					OR pmp_utilisateur.code_postal LIKE '20%'
					OR pmp_utilisateur.code_postal LIKE '21%'
					OR pmp_utilisateur.code_postal LIKE '22%'
					OR pmp_utilisateur.code_postal LIKE '23%'
					OR pmp_utilisateur.code_postal LIKE '24%'
					OR pmp_utilisateur.code_postal LIKE '25%' )
				AND pmp_commande.cmd_status = '10' ";
	$res = my_query($co_pmp, $query);
	$res = mysqli_fetch_array($res);
	return $res;
}
//50
function getNbFioulDep50(&$co_pmp)
{
	$query = "  SELECT substr(code_postal,1,2) as dep, sum(cmd_qte) as nb, max(cmd_qte) as cmd_qte
           		from pmp_commande, pmp_utilisateur
          		where cmd_status = 10
             	and pmp_commande.user_id = pmp_utilisateur.user_id
      			group by substr(code_postal,1,2)
					union
      				select LPAD(CAST(pf_departement.id AS CHAR), 2, '0'),0,0
      				from pf_departement
      				where not exists (select 1 from pmp_commande, pmp_utilisateur where cmd_status = 10
                         			  and pmp_commande.user_id = pmp_utilisateur.user_id
                  					  and LPAD(CAST(pf_departement.id AS CHAR), 2, '0') = substr(code_postal,1,2))

      			order by 1
				LIMIT 25 OFFSET 24 ";
	$res = my_query($co_pmp, $query);
	return $res;
}

function getTotalFioulDep50(&$co_pmp)
{
	$query = "  SELECT SUM(pmp_commande.cmd_qte) AS total
				FROM pmp_commande, pmp_utilisateur
				WHERE pmp_utilisateur.user_id = pmp_commande.user_id
				AND ( pmp_utilisateur.code_postal LIKE '26%'
					OR pmp_utilisateur.code_postal LIKE '27%'
					OR pmp_utilisateur.code_postal LIKE '28%'
					OR pmp_utilisateur.code_postal LIKE '29%'
					OR pmp_utilisateur.code_postal LIKE '30%'
					OR pmp_utilisateur.code_postal LIKE '31%'
					OR pmp_utilisateur.code_postal LIKE '32%'
					OR pmp_utilisateur.code_postal LIKE '33%'
					OR pmp_utilisateur.code_postal LIKE '34%'
					OR pmp_utilisateur.code_postal LIKE '35%'
					OR pmp_utilisateur.code_postal LIKE '36%'
					OR pmp_utilisateur.code_postal LIKE '37%'
					OR pmp_utilisateur.code_postal LIKE '38%'
					OR pmp_utilisateur.code_postal LIKE '39%'
					OR pmp_utilisateur.code_postal LIKE '40%'
					OR pmp_utilisateur.code_postal LIKE '41%'
					OR pmp_utilisateur.code_postal LIKE '42%'
					OR pmp_utilisateur.code_postal LIKE '43%'
					OR pmp_utilisateur.code_postal LIKE '44%'
					OR pmp_utilisateur.code_postal LIKE '45%'
					OR pmp_utilisateur.code_postal LIKE '46%'
					OR pmp_utilisateur.code_postal LIKE '47%'
					OR pmp_utilisateur.code_postal LIKE '48%'
					OR pmp_utilisateur.code_postal LIKE '49%'
					OR pmp_utilisateur.code_postal LIKE '50%' )
				AND pmp_commande.cmd_status = '10' ";
	$res = my_query($co_pmp, $query);
	$res = mysqli_fetch_array($res);
	return $res;
}
//75
function getNbFioulDep75(&$co_pmp)
{
	$query = "  SELECT substr(code_postal,1,2) as dep, sum(cmd_qte) as nb, max(cmd_qte) as cmd_qte
           		from pmp_commande, pmp_utilisateur
          		where cmd_status = 10
             	and pmp_commande.user_id = pmp_utilisateur.user_id
      			group by substr(code_postal,1,2)
					union
      				select LPAD(CAST(pf_departement.id AS CHAR), 2, '0'),0,0
      				from pf_departement
      				where not exists (select 1 from pmp_commande, pmp_utilisateur where cmd_status = 10
                         			  and pmp_commande.user_id = pmp_utilisateur.user_id
                  					  and LPAD(CAST(pf_departement.id AS CHAR), 2, '0') = substr(code_postal,1,2))

      			order by 1
				LIMIT 25 OFFSET 49 ";
	$res = my_query($co_pmp, $query);
	return $res;
}
function getTotalFioulDep75(&$co_pmp)
{
	$query = "  SELECT SUM(pmp_commande.cmd_qte) AS total
				FROM pmp_commande, pmp_utilisateur
				WHERE pmp_utilisateur.user_id = pmp_commande.user_id
				AND ( pmp_utilisateur.code_postal LIKE '51%'
					OR pmp_utilisateur.code_postal LIKE '52%'
					OR pmp_utilisateur.code_postal LIKE '53%'
					OR pmp_utilisateur.code_postal LIKE '54%'
					OR pmp_utilisateur.code_postal LIKE '55%'
					OR pmp_utilisateur.code_postal LIKE '56%'
					OR pmp_utilisateur.code_postal LIKE '57%'
					OR pmp_utilisateur.code_postal LIKE '58%'
					OR pmp_utilisateur.code_postal LIKE '59%'
					OR pmp_utilisateur.code_postal LIKE '60%'
					OR pmp_utilisateur.code_postal LIKE '61%'
					OR pmp_utilisateur.code_postal LIKE '62%'
					OR pmp_utilisateur.code_postal LIKE '63%'
					OR pmp_utilisateur.code_postal LIKE '64%'
					OR pmp_utilisateur.code_postal LIKE '65%'
					OR pmp_utilisateur.code_postal LIKE '66%'
					OR pmp_utilisateur.code_postal LIKE '67%'
					OR pmp_utilisateur.code_postal LIKE '68%'
					OR pmp_utilisateur.code_postal LIKE '69%'
					OR pmp_utilisateur.code_postal LIKE '70%'
					OR pmp_utilisateur.code_postal LIKE '71%'
					OR pmp_utilisateur.code_postal LIKE '72%'
					OR pmp_utilisateur.code_postal LIKE '73%'
					OR pmp_utilisateur.code_postal LIKE '74%'
					OR pmp_utilisateur.code_postal LIKE '75%' )
				AND pmp_commande.cmd_status = '10'  ";
	$res = my_query($co_pmp, $query);
	$res = mysqli_fetch_array($res);
	return $res;
}
//99
function getNbFioulDep99(&$co_pmp)
{
	$query = "  SELECT substr(code_postal,1,2) as dep, sum(cmd_qte) as nb, max(cmd_qte) as cmd_qte
           		from pmp_commande, pmp_utilisateur
          		where cmd_status = 10
             	and pmp_commande.user_id = pmp_utilisateur.user_id
      			group by substr(code_postal,1,2)
					union
      				select LPAD(CAST(pf_departement.id AS CHAR), 2, '0'),0,0
      				from pf_departement
      				where not exists (select 1 from pmp_commande, pmp_utilisateur where cmd_status = 10
                         			  and pmp_commande.user_id = pmp_utilisateur.user_id
                  					  and LPAD(CAST(pf_departement.id AS CHAR), 2, '0') = substr(code_postal,1,2))

      			order by 1
				LIMIT 24 OFFSET 78 ";
	$res = my_query($co_pmp, $query);
	return $res;
}

function getTotalFioulDep90(&$co_pmp)
{
	$query = "  SELECT SUM(pmp_commande.cmd_qte) AS total
				FROM pmp_commande, pmp_utilisateur
				WHERE pmp_utilisateur.user_id = pmp_commande.user_id
				AND ( pmp_utilisateur.code_postal LIKE '76%'
					OR pmp_utilisateur.code_postal LIKE '77%'
					OR pmp_utilisateur.code_postal LIKE '78%'
					OR pmp_utilisateur.code_postal LIKE '79%'
					OR pmp_utilisateur.code_postal LIKE '80%'
					OR pmp_utilisateur.code_postal LIKE '81%'
					OR pmp_utilisateur.code_postal LIKE '82%'
					OR pmp_utilisateur.code_postal LIKE '83%'
					OR pmp_utilisateur.code_postal LIKE '84%'
					OR pmp_utilisateur.code_postal LIKE '85%'
					OR pmp_utilisateur.code_postal LIKE '86%'
					OR pmp_utilisateur.code_postal LIKE '87%'
					OR pmp_utilisateur.code_postal LIKE '88%'
					OR pmp_utilisateur.code_postal LIKE '89%'
					OR pmp_utilisateur.code_postal LIKE '90%'
					OR pmp_utilisateur.code_postal LIKE '91%'
					OR pmp_utilisateur.code_postal LIKE '92%'
					OR pmp_utilisateur.code_postal LIKE '93%'
					OR pmp_utilisateur.code_postal LIKE '94%'
					OR pmp_utilisateur.code_postal LIKE '95%'
					OR pmp_utilisateur.code_postal LIKE '96%'
					OR pmp_utilisateur.code_postal LIKE '97%'
					OR pmp_utilisateur.code_postal LIKE '98%'
					OR pmp_utilisateur.code_postal LIKE '99%' )
				AND pmp_commande.cmd_status = '10'  ";
	$res = my_query($co_pmp, $query);
	$res = mysqli_fetch_array($res);
	return $res;
}
//Nb client region parisienne
function getNbClientRegion(&$co_pmp)
{
	$query = "  SELECT COUNT(*) AS nb
				FROM pmp_utilisateur
				WHERE ( code_postal LIKE '75%'
					OR code_postal LIKE '77%'
					OR code_postal LIKE '78%'
					OR code_postal LIKE '91%'
					OR code_postal LIKE '92%'
					OR code_postal LIKE '93%'
					OR code_postal LIKE '94%'
					OR code_postal LIKE '95%' ) ";
	$res = my_query($co_pmp, $query);
	$res = mysqli_fetch_array($res);
	return $res;
}

//Qte fioul region parisienne
function getNbFioulRegion(&$co_pmp)
{
	$query = "  SELECT SUM(pmp_commande.cmd_qte) AS nb
				FROM pmp_commande, pmp_utilisateur
				WHERE pmp_utilisateur.user_id = pmp_commande.user_id
				AND ( pmp_utilisateur.code_postal LIKE '75%'
					OR pmp_utilisateur.code_postal LIKE '77%'
					OR pmp_utilisateur.code_postal LIKE '78%'
					OR pmp_utilisateur.code_postal LIKE '91%'
					OR pmp_utilisateur.code_postal LIKE '92%'
					OR pmp_utilisateur.code_postal LIKE '93%'
					OR pmp_utilisateur.code_postal LIKE '94%'
					OR pmp_utilisateur.code_postal LIKE '95%' )
				AND pmp_commande.cmd_status = '10'	";
	$res = my_query($co_pmp, $query);
	$res = mysqli_fetch_array($res);
	return $res;
}

//Nb client total
function getNbClientTotal(&$co_pmp)
{
	$query = "  SELECT COUNT(*) AS nb
				FROM pmp_utilisateur ";
	$res = my_query($co_pmp, $query);
	$res = mysqli_fetch_array($res);
	return $res;
}

//Qte fioul total
function getNbFioulTotal(&$co_pmp)
{
	$query = "  SELECT SUM(cmd_qte) AS nb
				FROM pmp_commande
				WHERE cmd_status = '10' ";
	$res = my_query($co_pmp, $query);
	$res = mysqli_fetch_array($res);
	return $res;
}

// Ancienne commandes
function getAncienneCommande(&$co_pmp, $user_id)
{
	$query = "   SELECT pmp_commande.id AS id_cmd, pmp_commande.groupe_cmd, pmp_commande.cmd_dt, pmp_commande.cmd_qte, pmp_commande.cmd_qtelivre, pmp_commande.cmd_status, pmp_commande.cmd_typefuel, pmp_commande.cmd_prix_ord, pmp_commande.cmd_prix_sup, pmp_regroupement.libelle, pmp_fournisseur.nom,  pmp_commande.cmd_commentfour
				 FROM pmp_commande, pmp_regroupement, pmp_fournisseur
				 WHERE  pmp_commande.user_id = '$user_id'
				 AND pmp_regroupement.id = pmp_commande.groupe_cmd
				 AND pmp_fournisseur.id = pmp_regroupement.id_four
				 AND pmp_commande.cmd_status != 55
				 AND pmp_commande.cmd_status != 52
				 AND pmp_commande.cmd_status != 50
				 AND pmp_commande.cmd_status != 99
				 ORDER BY pmp_commande.cmd_dt DESC	";
	$res = my_query($co_pmp, $query);
	return $res;
}

function getAvisCommande(&$co_pmp, $id)
{
	$query = "   SELECT pmp_livre_or.note, pmp_livre_or.message, pmp_regroupement.id_four
				 FROM pmp_commande, pmp_livre_or, pmp_regroupement, pmp_fournisseur
				 WHERE pmp_livre_or.commande_id = pmp_commande.id
				 AND pmp_regroupement.id = pmp_commande.groupe_cmd
				 AND pmp_fournisseur.id = pmp_regroupement.id_four
				 AND pmp_commande.id = '$id'
				 AND pmp_commande.cmd_status != 55
				 AND pmp_commande.cmd_status != 52
				 AND pmp_commande.cmd_status != 50
				 AND pmp_commande.cmd_status != 99
				 ";
	$res = my_query($co_pmp, $query);
	$res = mysqli_fetch_array($res);
	return $res;
}

function getAncienneCommandeAnnulee(&$co_pmp, $user_id) 
{
    $user_id = mysqli_real_escape_string($co_pmp, $user_id);

    $query = "
        SELECT 
            c.id AS id_cmd,
            c.groupe_cmd,
            c.cmd_dt,
            c.cmd_qte,
            c.cmd_qtelivre,
            c.cmd_status,
            c.cmd_typefuel,
            c.cmd_prix_ord,
            c.cmd_prix_sup,
            r.libelle,
            f.nom,
            c.cmd_commentfour,
            h.his_valeur AS ancien_grp
        FROM pmp_commande c
        LEFT JOIN pmp_regroupement r ON r.id = c.groupe_cmd
        LEFT JOIN pmp_fournisseur f ON f.id = r.id_four
        LEFT JOIN (
            SELECT cmd_id, MAX(his_date) AS last_date
            FROM pmp_commande_histo
            WHERE his_action = 'Ancien groupement'
            GROUP BY cmd_id
        ) hx ON hx.cmd_id = c.id
        LEFT JOIN pmp_commande_histo h ON h.cmd_id = c.id AND h.his_action = 'Ancien groupement' AND h.his_date = hx.last_date
        WHERE c.user_id = '$user_id'
          AND c.cmd_status IN (50, 52, 55, 99)
        ORDER BY c.cmd_dt DESC
    ";

    $res = my_query($co_pmp, $query);
    return $res;
}

function getAncienneCommandeToutes(&$co_pmp, $user_id)
{
    $user_id = mysqli_real_escape_string($co_pmp, $user_id);

    $query = "
        SELECT 
            c.id AS id_cmd,
            c.groupe_cmd,
            c.cmd_dt,
            c.cmd_qte,
            c.cmd_qtelivre,
            c.cmd_status,
            c.cmd_typefuel,
            c.cmd_prix_ord,
            c.cmd_prix_sup,
            r.libelle,
            f.nom,
            c.cmd_commentfour,
            h.his_valeur AS ancien_grp
        FROM pmp_commande c
        LEFT JOIN pmp_regroupement r ON r.id = c.groupe_cmd
        LEFT JOIN pmp_fournisseur f ON f.id = r.id_four
        LEFT JOIN (
            SELECT cmd_id, MAX(his_date) AS last_date
            FROM pmp_commande_histo
            WHERE his_action = 'Ancien groupement'
            GROUP BY cmd_id
        ) hx ON hx.cmd_id = c.id
        LEFT JOIN pmp_commande_histo h ON h.cmd_id = c.id AND h.his_action = 'Ancien groupement' AND h.his_date = hx.last_date
        WHERE c.user_id = '$user_id'
        ORDER BY c.cmd_dt DESC
    ";

    $res = my_query($co_pmp, $query);
    return $res;
}

function getHistoDetailsCommande(&$co_pmp, $id_cmd)
{
	$query = "  SELECT *
				FROM pmp_commande_histo
				WHERE cmd_id = '$id_cmd'
				ORDER BY his_date DESC ";
	$res = my_query($co_pmp, $query);
	return $res;
}

if(!empty($_POST["ajouter_liste_grp"]))
{
	$id_grp = $_GET["id_grp"];

	if (!empty($_SESSION["n_cmd"]))
	{
		$res_cmd = getCommandeNumero($co_pmp, $_SESSION["n_cmd"]);
	}
	elseif (!empty($_SESSION["fournisseurs"]) && !empty($_SESSION["zone_fournisseur"]))
	{
		$res_cmd = getCommandesFournisseurZone($co_pmp, $_SESSION["zone_fournisseur"], $_SESSION["etat_1"], $_SESSION["etat_1"]);
	}
	else
	{
		$res_cmd = getFiltreCommandes($co_pmp);
	}

	while ($cmd = mysqli_fetch_array($res_cmd))
	{
		$id = $cmd["num_cmd"];
		$query = "  UPDATE pmp_commande
					SET groupe_cmd = '$id_grp', cmd_status = '12'
					WHERE id = '$id' ";
		$res = my_query($co_pmp, $query);
		if($res)
		{
			TraceHisto($co_pmp, $id, 'Statut', "12 - Attachée");
			TraceHisto($co_pmp, $id, 'Groupement', $id_grp);
			header('Location: /admin/details_groupement.php?id_grp='. $_GET["id_grp"]);
		}
	}
}

//volume par fournisseur
function getVolumeFournisseursZone(&$co_pmp, $id)
{
	$partenaires = isset($_POST["four_partenaires"])? "1" : "0";
	$query = "  SELECT pmp_fournisseur.nom, pmp_fournisseur_zone.libelle, pmp_fournisseur_zone.id
				FROM pmp_fournisseur, pmp_fournisseur_zone
				WHERE pmp_fournisseur.id = pmp_fournisseur_zone.fournisseur_id
				AND pmp_fournisseur.id = '$id'
				AND pmp_fournisseur.etat = '$partenaires'
				ORDER BY 1,2 ";
	$res = my_query($co_pmp, $query);
	return $res;
}

function getVolume(&$co_pmp, $zone_id)
{
	// $query = "  SELECT SUM(pmp_commande.cmd_qte) AS cmd_qte
	// 			FROM pmp_utilisateur,  pmp_zone_cp, pmp_code_postal, pmp_commande
	// 			WHERE pmp_zone_cp.zone_id = '$zone_id'
	// 			AND pmp_zone_cp.code_postal_id = pmp_code_postal.id
	// 			AND pmp_utilisateur.code_postal = pmp_code_postal.code_postal
	// 			AND pmp_commande.user_id = pmp_utilisateur.user_id
	// 			AND pmp_commande.cmd_status = 10
	// 			AND pmp_zone_cp.actif = 1
	// 			GROUP BY pmp_commande.user_id ";

	$query = " SELECT    pmp_fournisseur.nom, pmp_fournisseur_zone.libelle, sum(cmd_qte) AS cmd_qte
            from    pmp_fournisseur, pmp_fournisseur_zone, pmp_utilisateur,  pmp_zone_cp, pmp_commande
            where     pmp_fournisseur_zone.id='$zone_id'
      and pmp_fournisseur_zone.fournisseur_id = pmp_fournisseur.id
      and pmp_fournisseur_zone.id = pmp_zone_cp.zone_id
      and pmp_zone_cp.code_postal_id = pmp_utilisateur.code_postal_id
            and pmp_commande.user_id = pmp_utilisateur.user_id
            and pmp_commande.cmd_status = 10
            and  pmp_zone_cp.actif=1
            -- group by  pmp_fournisseur.nom, pmp_fournisseur_zone.libelle
      union
      select    pmp_fournisseur.nom, pmp_fournisseur_zone.libelle, 0
            from    pmp_fournisseur, pmp_fournisseur_zone
            where     pmp_fournisseur_zone.id='$zone_id'
      and pmp_fournisseur_zone.fournisseur_id = pmp_fournisseur.id
      and not exists (select 1 from  pmp_utilisateur,  pmp_zone_cp, pmp_commande
                      where pmp_commande.user_id = pmp_utilisateur.user_id
                            and pmp_commande.cmd_status = 10
                      and pmp_zone_cp.code_postal_id = pmp_utilisateur.code_postal_id
                            and pmp_fournisseur_zone.id = pmp_zone_cp.zone_id
                            and  pmp_zone_cp.actif=1) ";

	$res = my_query($co_pmp, $query);
	$res = mysqli_fetch_array($res);
	return $res;
}

function getDerniereCommande(&$co_pmp, $id)
{
	$query = "	SELECT id, cmd_status, groupe_cmd, cmd_typefuel, cmd_qte, cmd_prix_ord, cmd_prix_sup, cmd_comment, user_id
			FROM pmp_commande
			WHERE user_id= '" . mysqli_real_escape_string($co_pmp, $id) . "'
			ORDER BY id DESC LIMIT 1";
	$res = my_query($co_pmp, $query);
	$cmd = mysqli_fetch_array($res);
	return $cmd;
}

if(!empty($_POST["update_ancienne_cmd"]))
{
	$cmd_details = getCommandeDetailsClients($co_pmp, $_GET["details_cmd"]);

	$id_cmd = $_GET["details_cmd"];
	$status = $_POST["status"];
	$com_client = $_POST["com_client"];
	$type_fioul = $_POST["cmd_fioul"];

	if ($type_fioul == "Ordinaire"){$type = "1";}
	if ($type_fioul == "Supérieur"){$type = "2";}

	if($status != $cmd_details["cmd_status"])
	{
		$updateCmd = "  UPDATE pmp_commande SET cmd_status = '$status' WHERE id = '$id_cmd' ";
		$res = my_query($co_pmp, $updateCmd);
		if($status == '10') { $status = "10 - Utilisateur"; }
		if($status == '12') { $status = "12 - Attachée"; }
		if($status == '13') { $status = "13 - Proposée"; }
		if($status == '15') { $status = "15 - Groupée"; }
		if($status == '17') { $status = "17 - Prix proposé"; }
		if($status == '20') { $status = "20 - Prix validé"; }
		if($status == '25') { $status = "25 - Livrable"; }
		if($status == '30') { $status = "30 - Livrée"; }
		if($status == '40') { $status = "40 - Terminée"; }
		if($status == '50') { $status = "50 - Annulée"; }
		if($status == '52') { $status = "52 - Annulée / Livraison"; }
		if($status == '55') { $status = "55 - Annulée / Prix"; }
		if($status == '99') { $status = "99 - Annulée / Compte désactivé"; }

		if($res)
		{
			TraceHisto($co_pmp, $id_cmd, 'Statut', $status);
			$message_type = "success";
			$message_icone = "fa-check";
			$message_titre = "Succès";
			$message = "La commande a été modifiée.";
		}

	}

	if ($type != $cmd_details["cmd_typefuel"])
	{
		$updateCmd = "  UPDATE pmp_commande SET cmd_typefuel = '$type' WHERE id = '$id_cmd' ";
		$res = my_query($co_pmp, $updateCmd);
		if($res)
		{
			TraceHisto($co_pmp, $id_cmd, 'Type Fuel', $type_fioul);
			$message_type = "success";
			$message_icone = "fa-check";
			$message_titre = "Succès";
			$message = "La commande a été modifiée.";
		}
	}

	if ($com_client != $cmd_details["cmd_comment"])
	{
		$com_client = mysqli_real_escape_string($co_pmp, $com_client);
		$updateCmd = "  UPDATE pmp_commande SET cmd_comment = '$com_client' WHERE id = '$id_cmd' ";
		$res = my_query($co_pmp, $updateCmd);
		if($res)
		{
			TraceHisto($co_pmp, $id_cmd, 'Commentaire client', $com_client);
			$message_type = "success";
			$message_icone = "fa-check";
			$message_titre = "Succès";
			$message = "La commande a été modifiée.";
		}
	}
}

function basculerListeUtilisateur($co_pmp, $client, $id_grp)
{
	$actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	foreach ($client as $client)
	{
		$user_id = $client["user_id"];
		$cmd = getDerniereCommande($co_pmp, $user_id);
		$id_cmd = $cmd["id"];
		$query = "  UPDATE pmp_commande
					SET groupe_cmd = '$id_grp'
					WHERE user_id = '$user_id'
					AND id = '$id_cmd'
					  ";
		$res = my_query($co_pmp, $query);
		if($res)
		{
			TraceHisto($co_pmp, $id_cmd, 'Basculé au groupement -> ', $id_grp);

		}
	}
	header('Location: ' . $actual_link . '&message=basculer');
}

function ajouterListeGroupement($co_pmp, $client, $id_grp)
{
	$actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	foreach ($client as $client)
	{
		$user_id = $client["user_id"];
		$cmd = getDerniereCommande($co_pmp, $user_id);
		if(isset($cmd["cmd_status"]))
		{
			if($cmd["cmd_status"] >= 10 && $cmd["cmd_status"] <= 20)
			{
				if($cmd["groupe_cmd"] == '0')
				{
					$id_cmd = $cmd["id"];
					$query = "  UPDATE pmp_commande
								SET groupe_cmd = '$id_grp'
								WHERE user_id = '$user_id'
								AND groupe_cmd = '0'
								AND id = '$id_cmd'
								  ";
					$res = my_query($co_pmp, $query);
					if($res)
					{
						TraceHisto($co_pmp, $id_cmd, 'Ajout Groupement', $id_grp);

					}
				}
				else
				{
					header('Location: ' . $actual_link . '&message=grpt');
				}
			}
			else
			{
				$query = "  INSERT INTO pmp_commande (id, user_id, groupe_cmd, cmd_dt, cmd_typefuel, cmd_qte, cmd_qtelivre, cmd_prixfm, cmd_prixfr, cmd_prixfmc, cmd_prixpmp, cmd_prixaf, cmd_status, cmd_comment, cmd_commentfour, cmd_nbcuve, cmd_prix_ord, cmd_prix_sup, cmd_comment_du_four )
							VALUES ('', '$user_id', '$id_grp', NOW(), '1', '0', '0', NULL, NULL, NULL, NULL, NULL, '13', NULL, NULL, NULL, NULL, NULL, NULL) ";
				$res = my_query($co_pmp, $query);
				if($res)
				{
					$last_id = mysqli_insert_id($co_pmp);
					TraceHisto($co_pmp, $last_id, 'Statut', '13 - Groupement ' . $id_grp);
				}
			}
		}
	}
	// header('Location: ' . $actual_link . '&message=commande');
	// while ($client = mysqli_fetch_array($res))
	// {
	// 	$actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	// 	$user_id = $client["user_id"];
	// 	$cmd = getDerniereCommande($co_pmp, $user_id);
		// if(isset($cmd["cmd_status"]))
		// {
		// 	if($cmd["cmd_status"] >= 10 && $cmd["cmd_status"] <= 20)
		// 	{
		// 		if($cmd["groupe_cmd"] == '0')
		// 		{
		// 			$id_cmd = $cmd["id"];
		// 			$query = "  UPDATE pmp_commande
		// 						SET groupe_cmd = '$id_grp'
		// 						WHERE user_id = '$user_id'
		// 						  ";
		// 			$res = my_query($co_pmp, $query);
		// 			if($res)
		// 			{
		// 				TraceHisto($co_pmp, $id_cmd, 'Ajout Groupement', $id_grp);
		//
		// 			}
		// 		}
		// 	}
		// }
	// 	// if($cmd["cmd_status"] >= 10 && $cmd["cmd_status"] <= 20)
	// 	// {
	// 	// 	if($cmd["groupe_cmd"] == '0')
	// 	// 	{
	// 	// 		$id_cmd = $cmd["id"];
	// 	// 		echo $id_cmd . "<br>";
	// 	// 	}
	// 	// }
	// 	// else
	// 	// {
	// 	// 	// echo $cmd["cmd_status"];
	// 	//
	// 	// 	// if($cmd["groupe_cmd"] != $id)
	// 	// 	// {
			// 	$query = "  INSERT INTO pmp_commande (id, user_id, groupe_cmd, cmd_dt, cmd_typefuel, cmd_qte, cmd_qtelivre, cmd_prixfm, cmd_prixfr, cmd_prixfmc, cmd_prixpmp, cmd_prixaf, cmd_status, cmd_comment, cmd_commentfour, cmd_nbcuve, cmd_prix_ord, cmd_prix_sup, cmd_comment_du_four )
			// 				VALUES ('', '$user_id', '$id', NOW(), '1', '0', '0', NULL, NULL, NULL, NULL, NULL, '13', NULL, NULL, NULL, NULL, NULL, NULL) ";
			// 	$res = my_query($co_pmp, $query);
		// 	// 	if($res)
		// 	// 	{
		// 	// 		$last_id = mysqli_insert_id($co_pmp);
		// 	// 		TraceHisto($co_pmp, $last_id, 'Statut', '13 - Groupement ' . $id);
		// 	// 	}
	// 	// 	// }
	// 	// }
	// }
	// header('Location: ' . $actual_link . '&message=commande');
}

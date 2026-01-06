<?php
	include_once __DIR__ . "/../../inc/pmp_co_connect.php";

	if(isset($_POST["volume"]))
	{
		$volume = $_POST["volume"];
		$id = $_POST["id"];

		$query = "  UPDATE pmp_regrp_plages
					SET volume = '$volume'
					WHERE id = '$id' ";
		$res = my_query($co_pmp, $query);
	}

	if(isset($_POST["prix_ord"]))
	{
		$id = $_POST["id"];
		$prix_ord = $_POST["prix_ord"];

		$prix_ord = str_replace(',', '', $prix_ord);
		$prix_ord = str_replace('.', '', $prix_ord);
		$prix_ord = str_replace(' ', '', $prix_ord);

		if(strlen($prix_ord) == 2)
		{
			$prix_ord = $prix_ord . "00";
		}
		elseif (strlen($prix_ord) == 1)
		{
			$prix_ord = $prix_ord . "000";
		}
		elseif (strlen($prix_ord) == 3)
		{
			$prix_ord = $prix_ord . "0";
		}

		$query = "  UPDATE pmp_regrp_plages
					SET prix_ord = '$prix_ord'
					WHERE id = '$id' ";
		$res = my_query($co_pmp, $query);
		if($res)
		{
			echo $prix_ord;
		}
	}

	if(isset($_POST["prix_sup"]))
	{
		$id = $_POST["id"];
		$prix_sup = $_POST["prix_sup"];

		$prix_sup = str_replace(',', '', $prix_sup);
		$prix_sup = str_replace('.', '', $prix_sup);
		$prix_sup = str_replace(' ', '', $prix_sup);

		if(strlen($prix_sup) == 2)
		{
			$prix_sup = $prix_sup . "00";
		}
		elseif (strlen($prix_sup) == 1)
		{
			$prix_sup = $prix_sup . "000";
		}
		elseif (strlen($prix_sup) == 3)
		{
			$prix_sup = $prix_sup . "0";
		}

		$query = "  UPDATE pmp_regrp_plages
					SET prix_sup = '$prix_sup'
					WHERE id = '$id' ";
		$res = my_query($co_pmp, $query);
		if($res)
		{
			echo $prix_sup;
		}
	}
?>

<?php
	if(!mysqli_close($co_pmp))
		AfficheMessage("Impossible de fermer la connexion $co_pmp");
?>

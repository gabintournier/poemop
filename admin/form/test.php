<?php
$res_mail = getMailModeleThunder($co_pmp);
if (!empty($_POST["envoyer_mail_thunder"])) {
    var_dump($_POST["envoyer_mail_thunder"]);
    echo "ok";
}
?>
<form id="myForm" method="post" action="">
    <div class="modal fade" id="envoyerMailThunder" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 40%; text-align:left">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Envoyer un mail modèle Thunder</h5>
                    <button type="button" class="btn-close fermer-modal b-close-c" data-bs-dismiss="modal" aria-label="Close">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <label class="label-title" style="margin: 0;">Choix du Mail</label>
                    <div class="ligne"></div>
                    <div class="row">
                        <div class="col-sm-9">
                            <div class="form-inline">
                                <label for="sms_type" class="col-sm-6 col-form-label" style="padding-left:0;">Choisissez le type de MAIL à envoyer</label>
                                <div class="col-sm-6" style="padding:0">
                                    <select class="form-control" name="mail_thunder" style="width:100%">
                                        <?php
                                        while ($mail = mysqli_fetch_array($res_mail)) {
                                        ?>
                                        <option value="<?php echo isset($mail["id"]) ? $mail["id"] : ''; ?>"><?php echo isset($mail["sujet"]) ? $mail["sujet"] : ''; ?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3 align-self-center">
                            <input type="submit" name="generer_mail" value="GÉNÉRER LE MAIL" class="btn btn-primary" style="width:100%;">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
					<input type="submit" name="envoyer_mail_thunder" id="envoyer_mail_thunder" class="btn btn-primary" value="Envoyer">
                </div>
            </div>
        </div>
    </div>
</form>

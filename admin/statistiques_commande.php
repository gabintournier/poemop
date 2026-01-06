<style media="screen">
.ligne-menu {width: 19%!important;}
</style>
<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

session_start();
if(!isset($_SESSION['user']))
{
    header('Location: /admin/connexion.php');
    die();
}

$title = 'Statistiques';
$title_page = 'Statistiques';
ob_start();


include_once __DIR__ . "/../inc/pmp_co_connect.php";
include_once __DIR__ . "/inc/pmp_inc_fonctions_commandes.php";

$date_jour = date("Y-m-d");
$date = date('Y-m-01',strtotime('-3 month',strtotime($date_jour)));
$annee1 = date('Y');
unset($_SESSION['facture_saisie']);
?>
<div class="bloc">
    <div class="menu-bloc">
        <a href="liste_commandes.php">Liste</a>
        <a href="commande_par_departement.php">Calcul par département</a>
        <a href="commande_par_fournisseur.php">Calcul par fournisseur</a>
        <a href="#" class="active">Statistiques</a>
    </div>
    <form method="post">
        <div class="row">
            <div class="col-sm-12">
                <label class="label-title" style="margin: 0;">Mensuelle sur les groupements</label>
                <div class="ligne"></div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-inline">
                            <label for="stats_date" class="col-sm-5 col-form-label" style="padding-left:0;">Recherche les groupements depuis le</label>
                            <div class="col-sm-3" style="padding:0">
                                <input type="date" name="stats_date" value="<?php if(isset($_POST["stats_date"])) { echo $_POST["stats_date"]; } else { echo $date; } ?>" class="form-control" style="width:80%;">
                            </div>
                            <div class="col-sm-3" style="padding:0">
                                <input type="submit" name="charger_stats" value="CHARGER" class="btn btn-primary" style="width:70%;">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 text-right">
                        <input type="submit" name="exporter_stats" value="EXPORTER" class="btn btn-secondary" style="width:20%;">
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <div class="tableau tableau-stats" style="height:180px">
            <table class="table">
                <thead>
                    <tr>
                        <th style="width:5%;">Période</th>
                        <th class="text-center">Nb Grp</th>
                        <th class="text-center">% Fini</th>
                        <th class="text-center">Montant HT (€)</th>
                        <th class="text-center">Vol Envoyé</th>
                        <th class="text-center">Vol Livré</th>
                        <th class="text-center">Liv / Cde %</th>
                        <th class="text-center">Vol Annulé</th>
                        <th class="text-center">Annulé / Cde %</th>
                    </tr>
                </thead>
                <tbody>
<?php
                if(!empty($_POST["charger_stats"]))
                {
                    $res_stats = getStatistiquesGroupementsDate($co_pmp);

                    while ($stats = mysqli_fetch_array($res_stats))
                    {
                        $mht_nb = getMHTNb($co_pmp, $stats["mois"], $stats["annee"]);
                        $fini = getGroupementsFini($co_pmp, $stats["mois"], $stats["annee"]);
                        if(isset($fini["nb_fini"])) { $fini = $fini["nb_fini"]; } else { $fini = 0; }
                        $pourcent_fini = $fini * 100 / ($mht_nb["nb"] ?? 1);
                        $vol = getVolEnvoyeVolLivre($co_pmp, $stats["mois"], $stats["annee"]);
                        if(!($vol["livre"] ?? 0) || !($vol["envoye"] ?? 0))
                        {
                            $pourcent_livre = 0;
                        }
                        else
                        {
                            $pourcent_livre = ($vol["livre"] ?? 0) * 100 / ($vol["envoye"] ?? 1);
                        }
                        $annule = getVolAnnule($co_pmp, $stats["mois"], $stats["annee"]);
                        if(!($annule["annule"] ?? 0) || !($vol["envoye"] ?? 0))
                        {
                            $pourcent_annule = 0;
                        }
                        else
                        {
                            $pourcent_annule = ($annule["annule"] ?? 0) * 100 / ($vol["envoye"] ?? 1);
                        }
?>
                    <tr>
                        <td><?= $stats["mois"]; ?>-<?= $stats["annee"]; ?></td>
                        <td class="text-center"><?= number_format($mht_nb["nb"] ?? 0,0,',',' '); ?></td>
                        <td class="text-center"><?= number_format($pourcent_fini ?? 0,0,',',' '); ?> %</td>
                        <td class="text-center"><?= number_format($mht_nb["ht"] ?? 0,0,',',' '); ?></td>
                        <td class="text-center"><?= number_format($vol["envoye"] ?? 0,0,',',' '); ?></td>
                        <td class="text-center"><?= number_format($vol["livre"] ?? 0,0,',',' '); ?></td>
                        <td class="text-center"><?= number_format($pourcent_livre ?? 0,0,',',' '); ?> %</td>
                        <td class="text-center"><?= number_format($annule["annule"] ?? 0,0,',',' '); ?></td>
                        <td class="text-center"><?= number_format($pourcent_annule ?? 0,0,',',' '); ?> %</td>
                    </tr>
<?php
                    }
                }
?>
                </tbody>
            </table>
        </div>
        <hr>
        <div class="row">
            <div class="col-sm-6">
                <label class="label-title" style="margin: 0;">Annuelle sur les volumes</label>
                <div class="ligne"></div>
            </div>
            <div class="col-sm-6 text-right">
                <input type="submit" name="charger_stats_annuelle" value="CHARGER" class="btn btn-primary">
            </div>
        </div>
        <hr>
        <div class="row">
            <?php
            $tables_annuelles = [
                ["titre" => "< 1 000", "fonction" => "getVolumeInf1000"],
                ["titre" => "1 000 à 2 000", "fonction" => "getVolumeEntre1000Et2000"],
                ["titre" => "2 000 à 3 000", "fonction" => "getVolumeEntre2000Et3000"],
                [ "titre" => "> 3 000", "fonction" => "getVolumeSup3000"]
            ];

            foreach ($tables_annuelles as $table)
            {
                ?>
                <div class="col-sm-3">
                    <p style="text-align: center;font-family: 'Goldplay Alt Medium';font-weight: 500;font-size: 14px;"><?= $table["titre"]; ?></p>
                    <div class="tableau tableau-stats" style="height:250px">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th style="width:5%;">Période</th>
                                    <th class="text-center">Envoyé</th>
                                    <th class="text-center">Livré</th>
                                    <th class="text-center">Nb Cde</th>
                                </tr>
                            </thead>
                            <tbody>
<?php
                if(!empty($_POST["charger_stats_annuelle"]))
                {
                    $res_vol = $table["fonction"]($co_pmp);
                    while ($vol = mysqli_fetch_array($res_vol))
                    {
?>
                                <tr>
                                    <td><?= $vol["annee"]; ?></td>
                                    <td class="text-center"><?= number_format($vol["envoye"] ?? 0,0,',',' '); ?></td>
                                    <td class="text-center"><?= number_format($vol["livre"] ?? 0,0,',',' '); ?></td>
                                    <td class="text-center"><?= number_format($vol["nb"] ?? 0,0,',',' '); ?></td>
                                </tr>
<?php
                    }
                }
?>
                            </tbody>
                        </table>
                    </div>
                </div>
<?php
            }
?>
        </div>
    </form>
</div>
<?php
$content = ob_get_clean();
require('template.php');
?>
<script src="/admin/js/select2.min.js"></script>
<script src="/admin/js/script_commandes.js" charset="utf-8"></script>

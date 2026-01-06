$(document).ready(function() {
    const baseUrl = window.location.origin; // prod ou dev automatique
    const loc = window.location.href;
    const searchParams = new URLSearchParams(window.location.search);
    const mail_id = searchParams.get('mail_id');

    // --------------------- Sélection dans les tableaux
    $(".select").on("click", "tr", function() {
        $(this).addClass('selected').siblings().removeClass('selected');
        const cols = $(this).find("td");
        $('#id_mail').val(cols[0].innerText);
        $('#sujet_mail').val(cols[1].innerText);
        $('#nom_fichier').val(cols[3].innerText);
        $('#descriptif').val(cols[5].innerText);
    });

    $(".select_client").on("click", "tr", function() {
        $(this).addClass('selected').siblings().removeClass('selected');
        const cols = $(this).find("td");
        const id = $(this).find("input").val();
        $('#code_client').val(id);
        $('#code_client_supp').val(id);
        $('#code_postal_client_modifier').val(cols[0].innerText);
        $('#email_client_modifier').val(cols[1].innerText);
        $('#email_client_supp').val(cols[1].innerText);
    });

    // --------------------- Affichage modal paramMail
    if (loc.startsWith(baseUrl + "/admin/alerte_pf.php?modal_param=oui")) {
        $('#paramMail').modal('show');
    }

    // --------------------- Bouton fermer
    $(".b-close").click(() => window.location.replace(baseUrl + "/admin/alerte_pf.php"));

    // --------------------- Sélection d'un mail
    $(".select_mail").on("click", "tr", function() {
        const cols = $(this).find("td");
        const selectedMailId = cols[0].innerText;
        window.location.replace(baseUrl + "/admin/alerte_pf.php?modal_param=oui&mail_id=" + selectedMailId);
    });
});

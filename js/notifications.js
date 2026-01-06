/* Script gestion des notifications mail clients */

/* Désinscription aux groupements */
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('formMailGroupement');
    const checkbox = document.getElementById('toggleMailNotifGroupement');
    const btnValider = document.getElementById('btnValiderNotifGroupement');
    const actionInput = document.getElementById('actionNotifGroupement');
    
    // On récupère la valeur du PHP depuis le data-attribute
    const isDesinscrit = form.dataset.isDesinscrit === '1';

    checkbox.addEventListener('change', () => {
        if (checkbox.checked) {
            // Si on coche → on veut changer d’état
            btnValider.disabled = false;
            actionInput.value = isDesinscrit ? 'inscription' : 'desinscription';
        } else {
            // Si on décoche → retour à l’état initial
            btnValider.disabled = true;
            actionInput.value = '';
        }
    });
});

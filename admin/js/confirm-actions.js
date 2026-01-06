// Gestion générique d'une modal de confirmation pour les boutons marqués data-confirm-modal
(function () {
  var modalEl = document.getElementById("confirmActionModal");
  if (!modalEl || typeof bootstrap === "undefined") {
    return;
  }

  var modal = new bootstrap.Modal(modalEl);
  var titleEl = modalEl.querySelector(".confirm-action-title");
  var bodyEl = modalEl.querySelector(".confirm-action-body");
  var detailEl = modalEl.querySelector(".confirm-action-detail");
  var confirmBtn = modalEl.querySelector(".confirm-action-yes");
  var activeBtn = null;

  function openModal(btn) {
    activeBtn = btn;
    var title = btn.dataset.confirmTitle || "Confirmer";
    var message = btn.dataset.confirmMessage || "Confirmer cette action ?";
    var detail = btn.dataset.confirmDetail || "";

    titleEl.textContent = title;
    bodyEl.textContent = message;
    detailEl.textContent = detail;
    detailEl.style.display = detail ? "block" : "none";

    modal.show();
  }

  function handleClick(event) {
    var btn = event.currentTarget;
    if (btn.dataset.confirmed === "1") {
      btn.dataset.confirmed = "";
      return;
    }
    event.preventDefault();
    openModal(btn);
  }

  document.querySelectorAll("[data-confirm-modal]").forEach(function (btn) {
    btn.addEventListener("click", handleClick);
  });

  confirmBtn.addEventListener("click", function () {
    if (!activeBtn) {
      return;
    }
    modal.hide();
    activeBtn.dataset.confirmed = "1";

    if (activeBtn.form) {
      var temp = document.createElement("input");
      temp.type = "hidden";
      temp.name = activeBtn.name || "action";
      temp.value = activeBtn.value || "1";
      activeBtn.form.appendChild(temp);
      activeBtn.form.submit();
    } else {
      activeBtn.click();
    }
  });
})();

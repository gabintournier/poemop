(function () {
	const API_URL = "/admin/ajax_export_xls.php";
	const MODAL_SELECTOR = "#exportXls";
	const TRIGGER_SELECTOR = "#triggerExportXls";
	const CHECKBOX_NAMES = [
		"nom_prenom",
		"adresse",
		"cp_ville",
		"date",
		"quantite",
		"quantite_livree",
		"les_prix",
		"type_fioul",
		"statut_exp",
		"aspiration_prix",
		"mail",
		"commentaire_cmd",
		"tel"
	];
	let toastEl;
	let toastStatusEl;
	let toastExtraEl;
	let toastBarEl;
	let toastCloseBtn;
	let isRunning = false;
	let originalButtonText = null;
	let lastVariant = "";

	const initialStatus = "Téléchargement de l'export Excel en attente.";

	function $(selector, context = document) {
		return context.querySelector(selector);
	}

	function ensureToastElements() {
		if (toastEl) {
			return;
		}

		const style = document.createElement("style");
		style.textContent = `
			.export-toast {
				position: fixed;
				right: 24px;
				bottom: 24px;
				width: 330px;
				background: #ffffff;
				border: 1px solid #dae0e5;
				box-shadow: 0 12px 35px rgba(15, 33, 36, 0.2);
				border-radius: 12px;
				display: none;
				z-index: 9999;
				font-family: 'Montserrat', Arial, sans-serif;
			}
			.export-toast.show {
				display: block;
			}
			.export-toast .toast-header {
				display: flex;
				align-items: center;
				justify-content: space-between;
				padding: 12px 16px;
				border-bottom: 1px solid #eef2f4;
				font-weight: 600;
				font-size: 14px;
				color: #0f393a;
			}
			.export-toast .toast-header .toast-close {
				background: transparent;
				border: none;
				font-size: 18px;
				line-height: 1;
				cursor: pointer;
				color: #6c7a86;
			}
			.export-toast .toast-body {
				padding: 12px 16px 16px;
			}
			.export-toast .toast-status {
				font-size: 13px;
				color: #0f393a;
				margin-bottom: 10px;
				line-height: 1.5;
			}
			.export-toast .toast-extra {
				font-size: 12px;
				color: #4a5b67;
			}
			.export-toast .toast-progress {
				height: 6px;
				background: #ecf1f4;
				border-radius: 999px;
				overflow: hidden;
				margin-bottom: 8px;
			}
			.export-toast .toast-progress-bar {
				height: 100%;
				width: 0%;
				background: linear-gradient(90deg, #22a6f0, #4cd4c7);
				transition: width 0.3s ease;
			}
			.export-toast.done .toast-progress-bar {
				background: #2ecc71;
			}
			.export-toast.error .toast-progress-bar {
				background: #e74c3c;
			}
		`;
		document.head.appendChild(style);

		toastEl = document.createElement("div");
		toastEl.className = "export-toast";
		toastEl.setAttribute("aria-live", "polite");
		toastEl.innerHTML = `
			<div class="toast-header">
				<span>Téléchargement export Excel</span>
				<button type="button" class="toast-close" aria-label="Fermer">&times;</button>
			</div>
			<div class="toast-body">
				<div class="toast-status" id="exportToastStatus">${initialStatus}</div>
				<div class="toast-progress">
					<div class="toast-progress-bar" id="exportToastBar"></div>
				</div>
				<div class="toast-extra" id="exportToastExtra"></div>
			</div>
		`;
		document.body.appendChild(toastEl);

		toastStatusEl = $("#exportToastStatus");
		toastExtraEl = $("#exportToastExtra");
		toastBarEl = $("#exportToastBar");
		toastCloseBtn = toastEl.querySelector(".toast-close");

		toastCloseBtn.addEventListener("click", () => {
			hideToast(true);
		});

		toastEl.addEventListener("click", () => {
			if (lastVariant === "done" || lastVariant === "error") {
				hideToast(true);
			}
		});
	}

	function showToast() {
		ensureToastElements();
		toastEl.classList.add("show");
	}

	function hideToast(forceClear = false) {
		if (!toastEl) {
			return;
		}
		toastEl.classList.remove("show");
		if (forceClear) {
			setToastProgress(0);
			updateToastState(initialStatus, "", "");
		}
	}

	function setVariant(variant) {
		if (!toastEl) {
			return;
		}
		toastEl.classList.remove("done", "error", "running");
		if (variant) {
			toastEl.classList.add(variant);
			lastVariant = variant;
		} else {
			lastVariant = "";
		}
	}

	function updateToastState(statusText, extraText, variant) {
		if (!toastEl) {
			return;
		}
		toastStatusEl.textContent = statusText;
		toastExtraEl.textContent = extraText || "";
		setVariant(variant);
	}

	function setToastProgress(percent) {
		if (!toastBarEl) {
			return;
		}
		toastBarEl.style.width = `${percent}%`;
	}

	function toggleButton(disabled, text) {
		const button = document.querySelector(TRIGGER_SELECTOR);
		if (!button) {
			return;
		}
		if (originalButtonText === null) {
			originalButtonText = button.textContent;
		}
		button.disabled = disabled;
		button.textContent = text || originalButtonText;
	}

	function collectPayload() {
		const modal = document.querySelector(MODAL_SELECTOR);
		if (!modal) {
			return null;
		}
		const groupId = modal.dataset.groupId;
		if (!groupId) {
			return null;
		}

		const params = new URLSearchParams();
		params.set("id_grp", groupId);
		const statut1 = modal.querySelector('[name="statut_1_export"]');
		const statut2 = modal.querySelector('[name="statut_2_export"]');
		if (statut1) {
			params.set("statut_1_export", statut1.value || "10");
		}
		if (statut2) {
			params.set("statut_2_export", statut2.value || "0");
		}

		CHECKBOX_NAMES.forEach((name) => {
			const input = modal.querySelector(`[name="${name}"]`);
			if (input && input.checked) {
				params.set(name, "1");
			}
		});

		return params;
	}

	function closeModal() {
		const modalEl = document.querySelector(MODAL_SELECTOR);
		if (!modalEl) {
			return;
		}
		const bootstrapGlobal = window.bootstrap;
		if (!bootstrapGlobal || !bootstrapGlobal.Modal) {
			return;
		}
		const instance = bootstrapGlobal.Modal.getInstance(modalEl);
		if (instance) {
			instance.hide();
		}
	}

	function downloadFile(url) {
		if (!url) {
			return;
		}
		const anchor = document.createElement("a");
		anchor.href = url;
		anchor.setAttribute("download", "");
		anchor.setAttribute("target", "_blank");
		anchor.style.display = "none";
		document.body.appendChild(anchor);
		anchor.click();
		setTimeout(() => {
			document.body.removeChild(anchor);
		}, 1500);
	}

	async function startExport() {
		if (isRunning) {
			return;
		}
		const payload = collectPayload();
		if (!payload) {
			alert("Impossible de déterminer le groupement pour l'export.");
			return;
		}

		isRunning = true;
		toggleButton(true, "Traitement en cours...");
		showToast();
		updateToastState("Téléchargement de l'export Excel en cours...", "Veuillez patienter pendant la génération.", "running");
		setToastProgress(60);
		closeModal();

		try {
			const response = await fetch(API_URL, {
				method: "POST",
				body: payload.toString(),
				headers: {
					"Content-Type": "application/x-www-form-urlencoded; charset=UTF-8"
				},
				credentials: "same-origin"
			});
			const rawBody = await response.text();
			let data = null;
			try {
				data = rawBody ? JSON.parse(rawBody) : null;
			} catch (err) {
				console.error("Impossible de parser la réponse JSON de l'export", rawBody);
			}
			if (!response.ok || !data || !data.success) {
				const message = (data && data.message)
					? data.message
					: rawBody
						? rawBody
						: "Le serveur a refusé l'export.";
				throw new Error(message);
			}
			if (!data.downloadUrl) {
				throw new Error("Aucun fichier export n'a été généré.");
			}

			updateToastState("Export terminé — téléchargement en cours.", "Cliquez pour fermer cette confirmation.", "done");
			setToastProgress(100);
			downloadFile(data.downloadUrl);
		} catch (err) {
			const trace = err?.message || "Une erreur est survenue.";
			updateToastState("Impossible de générer l'export.", trace, "error");
			setToastProgress(100);
		} finally {
			isRunning = false;
			toggleButton(false);
		}
	}

	document.addEventListener("DOMContentLoaded", () => {
		const trigger = document.querySelector(TRIGGER_SELECTOR);
		if (!trigger) {
			return;
		}
		trigger.addEventListener("click", startExport);
	});
})();

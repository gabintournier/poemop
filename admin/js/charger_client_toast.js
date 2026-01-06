(function () {
	const API_URL = "/admin/ajax_charger_client.php";
	const STORAGE_KEY = "pmpChargerClientJob";
	const POLL_INTERVAL = 2500;

	let pollTimer = null;
	let pollInFlight = false;
	let toastEl;
	let toastStatusEl;
	let toastExtraEl;
	let toastBarEl;
	let toastCloseBtn;
	let toastVisible = false;

const initialState = {
	jobId: null,
	groupId: null,
	status: null,
	processed: 0,
	total: 0,
	percent: 0,
	message: "",
	lastError: "",
	toastDismissed: false
};

function getActiveGroupId() {
	if (window.chargerClientIdGrp) {
		const parsed = parseInt(window.chargerClientIdGrp, 10);
		if (!Number.isNaN(parsed) && parsed > 0) {
			return parsed;
		}
	}
	const search = new URLSearchParams(window.location.search);
	const fallback = parseInt(search.get("id_grp"), 10);
	return !Number.isNaN(fallback) && fallback > 0 ? fallback : null;
}

	let jobState = Object.assign({}, initialState);

	function $(selector) {
		return document.querySelector(selector);
	}

	function ensureToastElements() {
		if (toastEl) {
			return;
		}

		const style = document.createElement("style");
		style.textContent = `
			.charger-toast {
				position: fixed;
				right: 24px;
				bottom: 24px;
				width: 320px;
				background: #ffffff;
				border: 1px solid #d9e1e5;
				box-shadow: 0 10px 35px rgba(15, 33, 36, 0.18);
				border-radius: 12px;
				display: none;
				z-index: 9999;
				font-family: 'Montserrat', Arial, sans-serif;
			}
			.charger-toast.show { display: block; }
			.charger-toast .toast-header {
				display: flex;
				align-items: center;
				justify-content: space-between;
				padding: 12px 16px;
				border-bottom: 1px solid #eef2f4;
				font-weight: 600;
				font-size: 14px;
				color: #0f393a;
			}
			.charger-toast .toast-close {
				background: transparent;
				border: none;
				font-size: 18px;
				line-height: 1;
				cursor: pointer;
				color: #6c7a86;
			}
			.charger-toast .toast-body { padding: 12px 16px 16px; }
			.charger-toast .toast-status {
				font-size: 13px;
				color: #0f393a;
				margin-bottom: 8px;
				line-height: 1.5;
			}
			.charger-toast .toast-extra {
				font-size: 12px;
				color: #4a5b67;
				margin-top: 6px;
			}
			.charger-toast .toast-progress {
				height: 6px;
				background: #ecf1f4;
				border-radius: 999px;
				overflow: hidden;
			}
			.charger-toast .toast-progress-bar {
				height: 100%;
				width: 0%;
				background: linear-gradient(90deg, #22a6f0, #4cd4c7);
				transition: width 0.3s ease;
			}
			.charger-toast.done .toast-progress-bar {
				background: #2ecc71;
			}
			.charger-toast.error .toast-progress-bar {
				background: #e74c3c;
			}
			.charger-toast .toast-link-primary {
				margin-top: 10px;
				display: inline-flex;
				align-items: center;
				justify-content: center;
				background: #0f6b82;
				border: none;
				color: #fff;
				padding: 6px 10px;
				border-radius: 6px;
				font-size: 12px;
				cursor: pointer;
			}
			.charger-toast .toast-link-primary:hover {
				background: #0c5466;
			}
		`;
		document.head.appendChild(style);

		toastEl = document.createElement("div");
		toastEl.className = "charger-toast";
		toastEl.setAttribute("aria-live", "polite");
		toastEl.innerHTML = `
			<div class="toast-header">
				<span>Chargement des clients</span>
				<button type="button" class="toast-close" aria-label="Fermer">&times;</button>
			</div>
			<div class="toast-body">
				<div class="toast-status" id="chargerToastStatus"></div>
				<div class="toast-progress">
					<div class="toast-progress-bar" id="chargerToastBar"></div>
				</div>
				<div class="toast-extra" id="chargerToastExtra"></div>
			</div>
		`;
		document.body.appendChild(toastEl);

		toastStatusEl = toastEl.querySelector("#chargerToastStatus");
		toastExtraEl = toastEl.querySelector("#chargerToastExtra");
		toastBarEl = toastEl.querySelector("#chargerToastBar");
		toastCloseBtn = toastEl.querySelector(".toast-close");

		toastCloseBtn.addEventListener("click", () => {
			hideToast();
		});
	}

	function showToast() {
		ensureToastElements();
		toastVisible = true;
		toastEl.classList.add("show");
	}

	function hideToast(forceClear = false) {
		if (!toastEl) {
			return;
		}
		toastVisible = false;
		toastEl.classList.remove("show");
		if (forceClear) {
			clearState();
			return;
		}
		if (jobState.status === "done") {
			jobState.toastDismissed = true;
			saveState();
		}
	}

	function setToastState(stateClass) {
		if (!toastEl) {
			return;
		}
		toastEl.classList.remove("done", "error");
		if (stateClass) {
			toastEl.classList.add(stateClass);
		}
	}

	function updateToastUI(messageOverride) {
		if (!toastEl) {
			return;
		}
		const percent = jobState.percent ? Math.min(100, Math.max(0, jobState.percent)) : 0;
		const total = jobState.total || 0;
		const processed = Math.min(jobState.processed || 0, total || jobState.processed || 0);

		let statusText = messageOverride;
		if (!statusText) {
			if (jobState.status === "done") {
				const fallbackTotal = jobState.message
					? jobState.message
					: `Termin\u00e9 : ${total || processed} clients charg\u00e9s dans le groupement`;
				statusText = fallbackTotal;
			} else if (jobState.status === "error") {
				statusText = jobState.lastError || "Erreur.";
			} else {
				statusText = `Progression : ${processed}/${total || "?"} (${percent}%)`;
			}
		}
		toastStatusEl.textContent = statusText;
		toastBarEl.style.width = percent + "%";

		toastExtraEl.innerHTML = "";
		if (jobState.lastError) {
			toastExtraEl.textContent = jobState.lastError;
		} else if (jobState.message) {
			toastExtraEl.textContent = jobState.message;
		}

		if (jobState.status === "done") {
			setToastState("done");
		} else if (jobState.status === "error") {
			setToastState("error");
		} else {
			setToastState("");
		}
	}

	function saveState() {
		if (jobState.jobId) {
			sessionStorage.setItem(STORAGE_KEY, JSON.stringify(jobState));
		} else {
			sessionStorage.removeItem(STORAGE_KEY);
		}
	}

	function loadState() {
		const raw = sessionStorage.getItem(STORAGE_KEY);
		if (!raw) {
			return;
		}
		try {
			const parsed = JSON.parse(raw);
			jobState = Object.assign({}, initialState, parsed || {});
		} catch (e) {
			jobState = Object.assign({}, initialState);
		}
	}

	function clearState() {
		jobState = Object.assign({}, initialState);
		saveState();
	}

	function request(action, data) {
		const params = new URLSearchParams();
		params.set("action", action);
		Object.keys(data || {}).forEach((key) => {
			if (data[key] !== undefined && data[key] !== null) {
				params.set(key, data[key]);
			}
		});

	return fetch(API_URL, {
		method: "POST",
		body: params.toString(),
		headers: {
			"Content-Type": "application/x-www-form-urlencoded; charset=UTF-8"
		},
		credentials: "same-origin"
	}).then(async (res) => {
		const text = await res.text();
		if (!text) {
			return null;
		}
		try {
			return JSON.parse(text);
		} catch (err) {
			// Invalid JSON responses should be ignored to avoid spurious network errors.
			return null;
		}
	});
}

	function startPolling(immediate = true) {
		if (pollTimer) {
			return;
		}
		if (immediate) {
			pollJob();
		}
		pollTimer = setInterval(pollJob, POLL_INTERVAL);
	}

	function stopPolling() {
		if (pollTimer) {
			clearInterval(pollTimer);
			pollTimer = null;
		}
		pollInFlight = false;
	}

	function pollJob() {
		if (!jobState.jobId || pollInFlight) {
			return;
		}
		pollInFlight = true;
		request("process", { job_id: jobState.jobId })
			.then((payload) => {
				pollInFlight = false;
				if (!payload) {
					return;
				}
				if (payload.status === "error") {
					jobState.lastError = payload.message || "Erreur inconnue.";
					jobState.status = "error";
					updateToastUI(jobState.lastError);
					stopPolling();
					saveState();
					return;
				}

				jobState.status = payload.status || jobState.status || "in_progress";
				jobState.processed = payload.processed !== undefined ? payload.processed : jobState.processed;
				jobState.total = payload.total !== undefined ? payload.total : jobState.total;
				jobState.percent = payload.percent !== undefined ? payload.percent : jobState.percent;
				jobState.message = payload.message || "";
				jobState.groupId = payload.group_id || jobState.groupId;
				jobState.lastError = "";
				saveState();
				updateToastUI();

				if (payload.status === "done") {
					const completedGroup = jobState.groupId || payload.group_id;
				if (completedGroup) {
					const doneLabel =
						jobState.message ||
						`Terminé : ${jobState.total || jobState.processed || 0} clients chargés`;
					const detail = {
						groupId: parseInt(completedGroup, 10),
						total: jobState.total,
						processed: jobState.processed,
						percent: jobState.percent,
						message: jobState.message,
						label: doneLabel
					};
					const event = new CustomEvent("charger-client-job-done", { detail });
					document.dispatchEvent(event);
					try {
						localStorage.setItem("pmpChargerClientLastDone", JSON.stringify(detail));
					} catch (e) {}
				}
					stopPolling();
					focusButtonDuringJob(false);
				}
			})
			.catch((err) => {
				pollInFlight = false;
				jobState.lastError = "Erreur réseau : " + err.message;
				jobState.status = "error";
				updateToastUI(jobState.lastError);
				stopPolling();
				saveState();
				focusButtonDuringJob(false);
			});
	}

	function focusButtonDuringJob(disabled) {
		const btn = document.querySelector('input[name="charger_client"]');
		if (btn) {
			btn.disabled = !!disabled;
		}
	}

	function startJob() {
		const id_grp = getActiveGroupId();
		if (!id_grp) {
			alert("Impossible de déterminer le groupement.");
			return;
		}

		if (jobState.jobId && jobState.status !== "done" && parseInt(jobState.groupId, 10) === id_grp) {
			showToast();
			updateToastUI("Chargement déjà en cours...");
			return;
		}

		showToast();
		updateToastUI("Initialisation du chargement...");
		setToastState("");
		jobState = Object.assign({}, initialState, { groupId: id_grp, toastDismissed: false });
		saveState();
		focusButtonDuringJob(true);

		request("start", { id_grp })
			.then((payload) => {
				if (!payload) {
					jobState.status = "error";
					jobState.lastError = "Réponse inattendue du serveur.";
					updateToastUI(jobState.lastError);
					setToastState("error");
					focusButtonDuringJob(false);
					return;
				}
				if (payload.status === "empty") {
					jobState = Object.assign({}, initialState);
					saveState();
					updateToastUI(payload.message || "Aucun utilisateur à charger.");
					setToastState("done");
					focusButtonDuringJob(false);
					setTimeout(hideToast, 4000);
					return;
				}

				if (payload.status !== "ok" || !payload.job_id) {
					const message = payload.message || "Impossible de lancer le chargement.";
					jobState.status = "error";
					jobState.lastError = message;
					saveState();
					updateToastUI(message);
					setToastState("error");
					focusButtonDuringJob(false);
					return;
				}

				jobState.jobId = payload.job_id;
				jobState.total = payload.total_display || payload.total || 0;
				jobState.processed = 0;
				jobState.percent = 0;
				jobState.message = "";
				jobState.status = "in_progress";
				saveState();
				updateToastUI();
				startPolling(true);
			})
			.catch((err) => {
				jobState.status = "error";
				jobState.lastError = err.message || "Erreur réseau.";
				saveState();
				updateToastUI(jobState.lastError);
				setToastState("error");
				focusButtonDuringJob(false);
			});
	}

	function initFromStorage() {
		loadState();
		if (jobState.jobId) {
			const shouldShowToast = !(jobState.status === "done" && jobState.toastDismissed);
			if (shouldShowToast) {
				showToast();
			}
			updateToastUI();
			if (jobState.status !== "done" && jobState.status !== "error") {
				startPolling(false);
			} else if (jobState.status === "done") {
				setToastState("done");
				focusButtonDuringJob(false);
			}
		}
	}

	// Expose globals used dans template.php
	window.startChargerClientJob = function () {
		startJob();
	};

	window.getChargerClientJobStatus = function () {
		return Object.assign({}, jobState);
	};

	window.ensureChargerClientPolling = function () {
		if (jobState.jobId && jobState.status !== "done" && jobState.status !== "error" && !pollTimer) {
			startPolling(false);
		}
	};

	window.reopenChargerToast = function () {
		if (!jobState.jobId || jobState.status === "done" || jobState.status === "error") {
			return;
		}
		jobState.toastDismissed = false;
		saveState();
		showToast();
		updateToastUI();
		if (jobState.status !== "done" && jobState.status !== "error" && !pollTimer) {
			startPolling(false);
		}
	};

	window.clearChargerClientJobState = function () {
		clearState();
		if (toastEl) {
			toastVisible = false;
			toastEl.classList.remove("show");
		}
	};

	document.addEventListener("visibilitychange", () => {
		if (!document.hidden && jobState.jobId && jobState.status !== "done") {
			pollJob();
		}
	});

	document.addEventListener("DOMContentLoaded", () => {
		ensureToastElements();
		initFromStorage();
	});
})();

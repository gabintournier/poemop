(function () {

	const STORAGE_KEY = 'pmpChargerClientNotifications';

	const NOTIF_STORE_KEY = 'pmpChargerClientLastDone';

	const bell = document.getElementById('chargerBell');

	const badge = document.getElementById('chargerBadge');

	const panel = document.getElementById('chargerPanel');

	const panelStatus = document.getElementById('chargerPanelStatus');

	const panelReopen = document.getElementById('chargerPanelReopen');

	const panelNotifs = document.getElementById('chargerPanelNotifications');
	const panelClearAll = document.getElementById('chargerPanelClearAll');

	const panelPercent = document.getElementById('chargerPanelPercent');

	const panelFill = document.getElementById('chargerPanelProgressFill');

	const doneCountEl = document.getElementById('chargerPanelDoneCount');

	if (!bell || !badge || !panel || !panelStatus || !panelReopen || !panelNotifs || !panelPercent || !panelFill) {

		return;

	}

	let notifications = [];
	let dismissedIds = new Set();

	function loadNotifications() {

		try {

			const raw = localStorage.getItem(STORAGE_KEY);

			const parsed = raw ? JSON.parse(raw) : [];

			notifications = Array.isArray(parsed) ? parsed : [];

		} catch (e) {

			notifications = [];

		}

	}

	function persistNotifications() {

		try {

			localStorage.setItem(STORAGE_KEY, JSON.stringify(notifications));

		} catch (e) {}

	}

	function consumePersistedNotification() {

		try {

			const raw = localStorage.getItem(NOTIF_STORE_KEY);

			if (!raw) {

				return null;

			}

			return JSON.parse(raw);

		} catch (e) {

			return null;

		}

	}

	const DISMISSED_KEY = 'pmpChargerClientDismissed';

	function persistDismissedIds() {
		try {
			localStorage.setItem(DISMISSED_KEY, JSON.stringify([...dismissedIds]));
		} catch (e) {}
	}

	function loadDismissedIds() {
		try {
			const stored = localStorage.getItem(DISMISSED_KEY);
			const parsed = stored ? JSON.parse(stored) : [];
			if (Array.isArray(parsed)) {
				dismissedIds = new Set(parsed);
			}
		} catch (e) {
			dismissedIds = new Set();
		}
	}

	function resetDismissedIds() {
		if (!dismissedIds.size) {
			return;
		}
		dismissedIds.clear();
		persistDismissedIds();
	}

	function hydrateNotificationFromJobState() {

		if (typeof window.getChargerClientJobStatus !== 'function') {

			return;

		}

		const state = window.getChargerClientJobStatus();

		if (state && state.status === 'done') {

			queueNotificationFromState(state);

		}

		const persisted = consumePersistedNotification();

		if (persisted && queueNotificationFromState(persisted)) {

			try {

				localStorage.removeItem(NOTIF_STORE_KEY);

			} catch (e) {}

		}

	}

	function queueNotificationFromState(state) {

	if (!state || !state.groupId) {
		return false;
	}

	const id = parseInt(state.groupId, 10);
	if (!id) {
		return false;
	}

	if (notifications.some((notif) => notif.groupId === id) || dismissedIds.has(id)) {
		return false;
	}

	const processedValue = state.processed || state.total || 0;
	const formattedLabel = state.label || state.message || `${processedValue} commande${processedValue > 1 ? 's' : ''} import�e${processedValue > 1 ? 's' : ''} dans le groupement ${id}`;

	notifications.push({
		groupId: id,
		label: formattedLabel,
		count: processedValue
	});

	persistNotifications();
	return true;
}

	function removeNotification(groupId) {

		const id = parseInt(groupId, 10);

	if (!id) {

		return false;

	}

	const beforeLength = notifications.length;

	notifications = notifications.filter((notif) => notif.groupId !== id);

	if (notifications.length !== beforeLength) {

		persistNotifications();

		dismissedIds.add(id);
		persistDismissedIds();

		return true;

	}

		return false;

	}

function clearAllNotifications() {
	if (!notifications.length) {
		return false;
	}
	notifications = [];
	persistNotifications();
	dismissedIds.clear();
	persistDismissedIds();
	return true;
}

	function updateClearButtonState(state) {
		if (!panelClearAll) {
			return;
		}
		panelClearAll.disabled = notifications.length === 0;
	}

	function updateBadge(st) {

		const state = st || (typeof window.getChargerClientJobStatus === 'function' ? window.getChargerClientJobStatus() : null);

		if (state && state.jobId && state.status !== 'done' && state.status !== 'error') {

			badge.classList.add('charger-badge--progress');

			badge.classList.remove('charger-badge--done');

			const pct = state.percent ? Math.min(100, Math.round(state.percent)) : 0;

			badge.textContent = pct + '%';

			badge.style.display = 'inline-block';

		} else if (notifications.length) {

			badge.classList.add('charger-badge--done');

			badge.classList.remove('charger-badge--progress');

			badge.textContent = notifications.length;

			badge.style.display = 'inline-block';

		} else {

			badge.style.display = 'none';

			badge.textContent = '';

			badge.classList.remove('charger-badge--progress', 'charger-badge--done');

		}

	}

	function renderNotifications(state) {

	if (!notifications.length) {

		panelNotifs.textContent = 'Aucune action terminée.';

		panelNotifs.classList.add('charger-notifs-empty');

		if (doneCountEl) {

			doneCountEl.textContent = '0';

		}

	} else {

		panelNotifs.classList.remove('charger-notifs-empty');

		panelNotifs.innerHTML = notifications
			.map((notif) => `
			<div class="charger-notif-item" data-group-id="${notif.groupId}">
				<div class="charger-notif-content">
					<strong>Groupement #${notif.groupId}</strong>
					${notif.label ? `<span class="charger-notif-meta">${notif.label}</span>` : ''}
				</div>
				<div class="charger-notif-actions">
					<button type="button" class="charger-notif-dismiss" data-action="dismiss" aria-label="Supprimer la notification">&times;</button>
					<button type="button" class="charger-notif-link" data-action="open">Ouvrir</button>
				</div>
			</div>
		`)
			.join('');

		panelNotifs.querySelectorAll('.charger-notif-item').forEach((item) => {

			const groupId = parseInt(item.dataset.groupId, 10);

			const handler = (evt) => {

				if (evt) {

					evt.preventDefault();

					evt.stopPropagation();

				}

				handleOpenGroup(groupId);

			};

			item.addEventListener('click', handler);

			const link = item.querySelector('[data-action="open"]');

			if (link) {

				link.addEventListener('click', handler);

			}

			const dismiss = item.querySelector('[data-action="dismiss"]');

			if (dismiss) {

				dismiss.addEventListener('click', (evt) => {

					evt.stopPropagation();

					evt.preventDefault();

					if (removeNotification(groupId)) {

						renderNotifications(typeof window.getChargerClientJobStatus === 'function' ? window.getChargerClientJobStatus() : null);

					}

				});

			}

		});

		if (doneCountEl) {

			doneCountEl.textContent = `${notifications.length}`;

		}

	}

	updateBadge(state);
	updateClearButtonState(state);

}
function renderStatus(content, clickHandler) {

		panelStatus.innerHTML = content;

		if (clickHandler) {

			panelStatus.classList.add('charger-status-clickable');

			panelStatus.onclick = clickHandler;

		} else {

			panelStatus.classList.remove('charger-status-clickable');

			panelStatus.onclick = null;

		}

	}

	function updateProgress(st) {

		const pct = st && st.percent ? Math.min(100, Math.round(st.percent)) : 0;

		panelPercent.textContent = pct + '%';

		panelFill.style.width = pct + '%';

	}

function handleOpenGroup(groupId) {

	const id = parseInt(groupId, 10);

	if (!id) {
		return;
	}

	const state = typeof window.getChargerClientJobStatus === 'function' ? window.getChargerClientJobStatus() : null;

	if (typeof window.clearChargerClientJobState === 'function') {
		window.clearChargerClientJobState();
	}

	if (removeNotification(id)) {
		renderNotifications(state);
	}

	if (panel) {
		panel.classList.remove('show');
		panel.setAttribute('aria-hidden', 'true');
	}

	window.location.href = '/admin/details_groupement.php?id_grp=' + id;
}

function updateUI() {

		if (typeof window.getChargerClientJobStatus !== 'function') return;

		if (typeof window.ensureChargerClientPolling === 'function') {

			window.ensureChargerClientPolling();

		}

		const st = window.getChargerClientJobStatus();

		if (st && st.status && st.status !== 'done') {
			resetDismissedIds();
		}

		if (!st || !st.jobId) {

			renderStatus('Aucun traitement en cours.', null);

			updateProgress(null);

			if (panelReopen) panelReopen.style.display = 'none';

			updateBadge(st);

			return;

		}

		if (st.status === 'done') {

			queueNotificationFromState(st);

			renderStatus('Aucun traitement en cours.', null);

			updateProgress(null);

			if (panelReopen) panelReopen.style.display = 'none';

			renderNotifications(st);

		} else if (st.status === 'error') {

			renderStatus(st.lastError || 'Erreur lors du chargement.', null);

			updateProgress({ percent: 0 });

			if (panelReopen) panelReopen.style.display = 'none';

			updateBadge(st);

		} else {

			const totalText = st.total ? st.total : '...';

			renderStatus(`En cours : ${st.processed || 0}/${totalText} (${st.percent ? Math.min(100, Math.round(st.percent)) : 0}%).`, null);

			updateProgress(st);

			if (panelReopen) panelReopen.style.display = 'block';

			updateBadge(st);

		}

	}

	function togglePanel() {

		const show = !panel.classList.contains('show');

		if (show) {

			panel.classList.add('show');

			panel.setAttribute('aria-hidden', 'false');

			updateUI();

		} else {

			panel.classList.remove('show');

			panel.setAttribute('aria-hidden', 'true');

		}

	}

	bell.addEventListener('click', function (e) {

		e.stopPropagation();

		togglePanel();

	});

	document.addEventListener('click', function (e) {

		if (!panel.contains(e.target) && !bell.contains(e.target)) {

			panel.classList.remove('show');

			panel.setAttribute('aria-hidden', 'true');

		}

	});

	if (panelReopen) {

		panelReopen.addEventListener('click', function (e) {

			e.preventDefault();

			if (typeof window.reopenChargerToast === 'function') {

				window.reopenChargerToast();

				panel.classList.remove('show');

			}

		});

	}

	if (panelClearAll) {

		panelClearAll.addEventListener('click', function (e) {

			e.preventDefault();

			e.stopPropagation();

			if (clearAllNotifications()) {

				renderNotifications(typeof window.getChargerClientJobStatus === 'function' ? window.getChargerClientJobStatus() : null);

			}

		});

	}

document.addEventListener('charger-client-job-done', function (event) {

		if (!event || !event.detail) {

			return;

		}

		if (queueNotificationFromState(event.detail)) {

			renderNotifications(typeof window.getChargerClientJobStatus === 'function' ? window.getChargerClientJobStatus() : null);

			try {

				localStorage.removeItem(NOTIF_STORE_KEY);

			} catch (e) {}

		}

	updateUI();

});

document.addEventListener('charger-mail-job-done', function (event) {

	if (!event || !event.detail || !event.detail.groupId) {

		return;

	}

	if (queueNotificationFromState({

		groupId: event.detail.groupId,

		label: event.detail.label || 'Mails envoyés',

		message: event.detail.message || event.detail.label || ''

	})) {

		renderNotifications(typeof window.getChargerClientJobStatus === 'function' ? window.getChargerClientJobStatus() : null);

	}

	updateUI();

});

	window.dismissChargerClientNotification = function (groupId) {

		if (removeNotification(groupId)) {

			renderNotifications(typeof window.getChargerClientJobStatus === 'function' ? window.getChargerClientJobStatus() : null);

		}

	};

	function init() {

		loadNotifications();

		loadDismissedIds();

		hydrateNotificationFromJobState();

		renderNotifications(typeof window.getChargerClientJobStatus === 'function' ? window.getChargerClientJobStatus() : null);

		setInterval(updateUI, 1500);

		document.addEventListener('visibilitychange', function () {

			if (!document.hidden) updateUI();

		});

	}

	if (document.readyState === 'loading') {

		document.addEventListener('DOMContentLoaded', init);

	} else {

		init();

	}

})();

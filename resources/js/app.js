import './bootstrap';

const bell = document.querySelector('[data-notification-bell]');
const badge = document.querySelector('[data-notification-count]');
const list = document.querySelector('[data-notification-list]');
const panel = document.querySelector('[data-notification-panel]');
const pubSubList = document.querySelector('[data-pubsub-list]');
const csrfToken = document
    .querySelector('meta[name="csrf-token"]')
    ?.getAttribute('content');

const updateCount = (nextCount) => {
    if (!badge) {
        return;
    }

    badge.textContent = String(nextCount);
    badge.classList.toggle('hidden', nextCount === 0);
};

const createNotificationItem = (notification) => {
    const item = document.createElement('li');
    item.className = 'rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3';
    item.innerHTML = `
        <p class="text-sm font-medium text-slate-800">${notification.message}</p>
        <p class="mt-1 text-xs text-slate-500">${notification.created_at ?? 'Just now'}</p>
    `;

    return item;
};

const renderNotifications = (notifications) => {
    if (!list) {
        return;
    }

    list.innerHTML = '';

    if (!notifications.length) {
        const item = document.createElement('li');
        item.dataset.notificationEmpty = 'true';
        item.className = 'rounded-2xl border border-dashed border-slate-200 px-4 py-6 text-center text-sm text-slate-500';
        item.textContent = 'No notifications yet.';
        list.appendChild(item);
        return;
    }

    notifications.forEach((notification) => {
        list.appendChild(createNotificationItem(notification));
    });
};

const createPubSubItem = (payload) => {
    const item = document.createElement('li');
    item.className = 'rounded-2xl border border-amber-200 bg-amber-50 px-4 py-3';
    item.innerHTML = `
        <div class="flex items-center justify-between gap-3">
            <p class="text-sm font-semibold text-amber-950">${payload.channel}</p>
            <p class="text-xs text-amber-700">${payload.received_at ?? 'Just now'}</p>
        </div>
        <p class="mt-2 text-sm text-amber-900">${payload.message}</p>
        <p class="mt-1 text-xs uppercase tracking-[0.2em] text-amber-700">${payload.source ?? 'live'}</p>
        <details class="mt-3 rounded-xl bg-white/70 p-3">
            <summary class="cursor-pointer text-xs font-semibold uppercase tracking-[0.2em] text-amber-800">Edit Message</summary>
            <form method="POST" action="/pubsub/messages/${payload.id}" class="mt-3 space-y-2">
                <input type="hidden" name="_token" value="${csrfToken ?? ''}">
                <input type="hidden" name="_method" value="PATCH">
                <input type="text" name="channel" value="${payload.channel}" class="w-full rounded-lg border border-amber-200 px-3 py-2 text-sm">
                <textarea name="message" rows="2" class="w-full rounded-lg border border-amber-200 px-3 py-2 text-sm">${payload.message}</textarea>
                <button class="rounded-lg bg-amber-500 px-3 py-2 text-xs font-semibold text-white">Save</button>
            </form>
        </details>
        <form method="POST" action="/pubsub/messages/${payload.id}" class="mt-3">
            <input type="hidden" name="_token" value="${csrfToken ?? ''}">
            <input type="hidden" name="_method" value="DELETE">
            <button class="rounded-lg border border-rose-200 px-3 py-2 text-xs font-semibold text-rose-600">Delete</button>
        </form>
    `;

    return item;
};

const renderPubSubMessages = (messages) => {
    if (!pubSubList) {
        return;
    }

    pubSubList.innerHTML = '';

    if (!messages.length) {
        const item = document.createElement('li');
        item.dataset.pubsubEmpty = 'true';
        item.className = 'rounded-2xl border border-dashed border-amber-200 px-4 py-6 text-center text-sm text-amber-700';
        item.textContent = 'No pub/sub messages yet.';
        pubSubList.appendChild(item);
        return;
    }

    messages.forEach((message) => {
        pubSubList.appendChild(createPubSubItem(message));
    });
};

const syncDashboard = async () => {
    try {
        const { data } = await window.axios.get('/dashboard-data');
        updateCount(data.notificationCount ?? 0);
        renderNotifications(data.notifications ?? []);
        renderPubSubMessages(data.pubSubMessages ?? []);
    } catch (error) {
        console.error('Dashboard sync failed.', error);
    }
};

if (bell && window.Echo) {
    bell.addEventListener('click', () => {
        panel.classList.toggle('hidden');
    });

    document.addEventListener('click', (event) => {
        if (!panel.contains(event.target) && !bell.contains(event.target)) {
            panel.classList.add('hidden');
        }
    });

    window.Echo.channel('notifications').listen('.notification.sent', (notification) => {
        const nextCount = Number(badge?.textContent || 0) + 1;
        updateCount(nextCount);
        syncDashboard();
    });
}

if (pubSubList && window.Echo) {
    window.Echo.channel('pubsub').listen('.redis.message.received', (payload) => {
        syncDashboard();
    });
}

if (bell || pubSubList) {
    window.setInterval(syncDashboard, 5000);
}

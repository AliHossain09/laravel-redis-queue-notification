import './bootstrap';

const bell = document.querySelector('[data-notification-bell]');

if (bell && window.Echo) {
    const badge = document.querySelector('[data-notification-count]');
    const list = document.querySelector('[data-notification-list]');
    const empty = document.querySelector('[data-notification-empty]');
    const panel = document.querySelector('[data-notification-panel]');

    const updateCount = (nextCount) => {
        badge.textContent = String(nextCount);
        badge.classList.toggle('hidden', nextCount === 0);
    };

    const createItem = (notification) => {
        const item = document.createElement('li');
        item.className = 'rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3';
        item.innerHTML = `
            <p class="text-sm font-medium text-slate-800">${notification.message}</p>
            <p class="mt-1 text-xs text-slate-500">${notification.created_at ?? 'Just now'}</p>
        `;

        return item;
    };

    bell.addEventListener('click', () => {
        panel.classList.toggle('hidden');
    });

    document.addEventListener('click', (event) => {
        if (!panel.contains(event.target) && !bell.contains(event.target)) {
            panel.classList.add('hidden');
        }
    });

    window.Echo.channel('notifications').listen('.notification.sent', (notification) => {
        if (empty) {
            empty.remove();
        }

        list.prepend(createItem(notification));

        while (list.children.length > 5) {
            list.removeChild(list.lastElementChild);
        }

        updateCount(Number(badge.textContent || 0) + 1);
    });
}

const pubSubList = document.querySelector('[data-pubsub-list]');

if (pubSubList && window.Echo) {
    const pubSubEmpty = document.querySelector('[data-pubsub-empty]');

    const createPubSubItem = (payload) => {
        const item = document.createElement('li');
        item.className = 'rounded-2xl border border-amber-200 bg-amber-50 px-4 py-3';
        item.innerHTML = `
            <div class="flex items-center justify-between gap-3">
                <p class="text-sm font-semibold text-amber-950">${payload.channel}</p>
                <p class="text-xs text-amber-700">${payload.received_at ?? 'Just now'}</p>
            </div>
            <p class="mt-2 text-sm text-amber-900">${payload.message}</p>
        `;

        return item;
    };

    window.Echo.channel('pubsub').listen('.redis.message.received', (payload) => {
        if (pubSubEmpty) {
            pubSubEmpty.remove();
        }

        pubSubList.prepend(createPubSubItem(payload));

        while (pubSubList.children.length > 8) {
            pubSubList.removeChild(pubSubList.lastElementChild);
        }
    });
}

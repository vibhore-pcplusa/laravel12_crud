import './bootstrap';
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';
import Alpine from 'alpinejs';

window.Alpine = Alpine;
Alpine.start();

window.Pusher = Pusher;

// Echo (Reverb) initialization
window.Echo = new Echo({
    broadcaster: 'reverb',
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: window.location.hostname,
    wsPort: 6001,
    wssPort: 443,
    forceTLS: false,
    encrypted: false,
    enabledTransports: ['ws'],
});

// DEBUG: dump every event arriving on the socket so you can see the exact event name
if (window.Echo && window.Echo.connector && window.Echo.connector.pusher) {
    const pusher = window.Echo.connector.pusher;
    if (typeof pusher.logToConsole !== 'undefined') pusher.logToConsole = true;

    if (typeof pusher.bind_global === 'function') {
        pusher.bind_global((eventName, data) => {
            console.log('[PUSHER GLOBAL EVENT]', eventName, data);

            // If this is an app event that carries a message, forward to the same handler
            // used by Echo.listen — this ensures the UI appends the message.
            const isAppEvent = (
                !!data &&
                typeof data === 'object' &&
                typeof data.message !== 'undefined'
            );

            if (isAppEvent && !eventName.startsWith('pusher:')) {
                try {
                    handleMessage(data);
                } catch (err) {
                    console.error('Error calling handleMessage from bind_global:', err);
                }
            }
        });
    }
}

// Minimal connection logs
if (window.Echo && window.Echo.connector && window.Echo.connector.pusher) {
    const conn = window.Echo.connector.pusher.connection;
    conn.bind('connected', () => console.log('WebSocket connected'));
    conn.bind('error', (err) => console.error('WebSocket error:', err));
    conn.bind('disconnected', () => console.warn('WebSocket disconnected'));
}

// Central event handler
const handleMessage = (event) => {
    console.log("vj app.js at line33");
    try {
        if (!event || !event.message) {
            console.warn('MessageSent missing payload:', event);
            return;
        }

        // If this client recently sent the same message, don't append again
        try {
            const last = JSON.parse(sessionStorage.getItem('last_sent') || 'null');
            if (last && last.message === event.message && (Date.now() - last.ts) < 5000) {
                // clear the marker so future identical messages are handled normally
                sessionStorage.removeItem('last_sent');
                return;
            }
        } catch (e) {
            // ignore parse errors
        }

        console.log('Message received:', event.message);
        const box = document.getElementById('messages');
        if (!box) return;
        box.innerHTML += `<div><b>Other:</b> ${event.message}</div>`;
        box.scrollTop = box.scrollHeight;
    } catch (err) {
        console.error('Error processing incoming event:', err);
    }
};

// Subscribe to channel (public). If you use PrivateChannel, switch to Echo.private(...)
const subscribeToChannel = () => {
    window.Echo.channel('chat-channel')
        .subscribed(() => console.log("Subscribed to 'chat-channel'"))
        .error((err) => console.error('Subscription error:', err))
        .listen('MessageSent', handleMessage);

    // If your server uses PrivateChannel, uncomment this line:
    // window.Echo.private('chat-channel').listen('MessageSent', handleMessage);
};

subscribeToChannel();

// Send message UI handler
document.addEventListener('DOMContentLoaded', () => {
    const sendBtn = document.getElementById('send-btn');
    if (!sendBtn) return;

    sendBtn.onclick = () => {
        const input = document.getElementById('message-input');
        if (!input) return;
        const msg = input.value.trim();
        if (!msg) return;

        console.log('Sending:', msg);

        // mark this message as last sent (used to deduplicate when our broadcast returns)
        try {
            sessionStorage.setItem('last_sent', JSON.stringify({ message: msg, ts: Date.now() }));
        } catch (e) { /* ignore */ }

        fetch('/index.php/send-message', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: JSON.stringify({ message: msg }),
        })
        .then(res => {
            console.log('Send status:', res.status);
            if (!res.ok) console.error('Send failed:', res);
            return res.json().catch(() => ({}));
        })
        .catch(err => console.error('Send error:', err));

        const box = document.getElementById('messages');
        if (box) {
            box.innerHTML += `<div><b>You:</b> ${msg}</div>`;
            box.scrollTop = box.scrollHeight;
        }
        input.value = '';
    };
});

// Client count updater (kept minimal)
function updateClientCount() {
    fetch('/api/channel-count')
        .then(r => { if (!r.ok) throw new Error(r.status); return r.json(); })
        .then(data => {
            const el = document.getElementById('client-count');
            if (el) el.innerHTML = `Connected Clients: ${data.count}`;
        })
        .catch(err => {
            console.error('Client count error:', err);
            const el = document.getElementById('client-count');
            if (el) el.innerHTML = `Connected Clients: Error`;
        });
}

// Run initial update when connected
if (window.Echo && window.Echo.connector && window.Echo.connector.pusher) {
    window.Echo.connector.pusher.connection.bind('connected', updateClientCount);
}


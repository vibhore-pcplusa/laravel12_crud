import './bootstrap';
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';
import Alpine from 'alpinejs';

window.Alpine = Alpine;
Alpine.start();

window.Pusher = Pusher;

// Initialize Echo correctly
window.Echo = new Echo({
    broadcaster: 'reverb',
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: window.location.hostname,
    wsPort: 6001,
    wssPort: 6001,
    forceTLS: false,
    encrypted: false,
    enabledTransports: ['ws'],  //,'wss' is removed for now. 
});

// --- 💡 Connection Status Logging ---

// Log when successfully connected
window.Echo.connector.pusher.connection.bind('connected', () => {
    console.log("✅ WebSocket Connected! (Reverb/Pusher)");
});

// Log when attempting to connect/reconnect
window.Echo.connector.pusher.connection.bind('connecting', () => {
    console.log("🟡 WebSocket Attempting to Connect...");
});

// Log when disconnected (might be temporary or permanent)
window.Echo.connector.pusher.connection.bind('disconnected', () => {
    console.warn("❌ WebSocket Disconnected.");
});

// Log any connection errors
window.Echo.connector.pusher.connection.bind('error', (err) => {
    console.error("🚨 WebSocket Connection Error:", err);
});

// Listen for events
window.Echo.channel('chat-channel')
    .subscribed(() => {
        // --- 🟢 NEW LOG ---
        console.log("🟢 Successfully Subscribed to 'chat-channel'!");
    })
    .error((error) => {
        // --- 🔴 NEW LOG ---
        console.error("🔴 Channel Subscription Error on 'chat-channel':", error);
    })
    .listen('MessageSent', (event) => {
        //console.log("Received:", event.message);
        const box = document.getElementById('messages');
        box.innerHTML += `<div><b>Other:</b> ${event.message}</div>`;
    });

// Send message
document.addEventListener('DOMContentLoaded', () => {
    document.getElementById('send-btn').onclick = function () {
        const msg = document.getElementById('message-input').value;

        // Log the message being sent
        console.log(`📤 Attempting to send message to /index.php/send-message: "${msg}"`);

        fetch('/index.php/send-message', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: JSON.stringify({ message: msg }),
        }).then(response => {
            console.log(`📡 HTTP POST Request Status: ${response.status}`);
            if (!response.ok) {
                console.error("HTTP Request Failed:", response);
            }
            return response.json(); // Assuming your endpoint returns JSON
        })
        .catch(error => {
            console.error("🚫 Error during message send (fetch API):", error);
        });

        const box = document.getElementById('messages');
        box.innerHTML += `<div><b>You:</b> ${msg}</div>`;

        document.getElementById('message-input').value = "";
    };
});


// Function to fetch the client count from the Laravel backend
function updateClientCount() {
    fetch('/api/channel-count')
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            const countElement = document.getElementById('client-count');
            if (countElement) {
                // The 'count' property is returned from the PHP endpoint
                countElement.innerHTML = `Connected Clients: ${data.count}`;
                console.log(`📊 Updated Client Count: ${data.count}`);
            }
        })
        .catch(error => {
            console.error('Failed to fetch client count:', error);
            const countElement = document.getElementById('client-count');
            if (countElement) {
                 countElement.innerHTML = `Connected Clients: Error`;
            }
        });
}

// ----------------------------------------------------
// ⚡️ Run the update function initially and then periodically
// ----------------------------------------------------

// Run once on connect success
window.Echo.connector.pusher.connection.bind('connected', () => {
    console.log("✅ WebSocket Connected! (Reverb/Pusher)");
    updateClientCount(); // Initial update
});

// Run every 5 seconds to keep the count fresh
// Note: This creates polling traffic. Adjust the interval as needed.
setInterval(updateClientCount, 8000);


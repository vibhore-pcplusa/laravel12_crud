import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

window.Echo.channel('chat')
    .listen('MessageSent', (data) => {
        console.log("Received WS:", data.message);
    });

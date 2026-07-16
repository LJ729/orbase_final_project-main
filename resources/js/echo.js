import Echo from 'laravel-echo';

import Pusher from 'pusher-js';
window.Pusher = Pusher;

const reverb = window.Laravel?.reverb ?? {};
const key = reverb.key ?? import.meta.env.VITE_REVERB_APP_KEY;

if (!key) {
    window.Echo = {
        channel: () => ({
            listen: () => {},
        }),
    };
} else {
    window.Echo = new Echo({
        broadcaster: 'reverb',
        key,
        wsHost: reverb.host ?? import.meta.env.VITE_REVERB_HOST,
        wsPort: reverb.port ?? import.meta.env.VITE_REVERB_PORT ?? 80,
        wssPort: reverb.port ?? import.meta.env.VITE_REVERB_PORT ?? 443,
        forceTLS: (reverb.scheme ?? import.meta.env.VITE_REVERB_SCHEME ?? 'https') === 'https',
        enabledTransports: ['ws', 'wss'],
    });
}
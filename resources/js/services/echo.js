import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

const echo = new Echo({
    broadcaster: 'pusher',
    key: "4e3c4cc918d0bd280397",
    cluster: "eu",
    forceTLS: true
});

export default echo;

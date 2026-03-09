/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

import axios from "axios";
window.axios = axios;

window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

import Echo from "laravel-echo";

import Pusher from "pusher-js";
window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: "pusher",
    key: PUSHER_APP_KEY,
    cluster: PUSHER_APP_CLUSTER,
    wsHost: `ws-${PUSHER_APP_CLUSTER}.pusher.com`,
    wsPort: import.meta.env.VITE_PUSHER_PORT ?? 80,
    wssPort: import.meta.env.VITE_PUSHER_PORT ?? 443,
    forceTLS: (import.meta.env.VITE_PUSHER_SCHEME ?? "https") === "https",
    enabledTransports: ["ws", "wss"],
    authEndpoint: DYNAMIC_URL,
});

if (auth_id) {
    //chat
    window.Echo.private(`client-chat.${auth_id}`).listen(
        "ClientChatMessage",
        (e) => {
            appendMessage(e.data, `user-${auth_id}`,auth_id,'client');
        }
    );
}
if (doc_id) {
    //chat
    window.Echo.private(`lawyer-chat.${doc_id}`).listen(
        "LawyerChatMessage",
        (e) => {
            appendMessage(e.data, `lawyer-${doc_id}`,doc_id,'lawyer');
        }
    );
}
window.Echo.join('online')
.here(users => {
    users.forEach(user => {
        $(`.profile-wrapper[data-id="${user.id}"] span.status`).addClass('active');
    });
})
.joining(user => {
    $(`.profile-wrapper[data-id="${user.id}"] span.status`).addClass('active');
})
.leaving(user => {
    const userSpan = $(`.profile-wrapper[data-id="${user.id}"] span.status`);
    userSpan.removeClass('active').addClass('inactive');
});


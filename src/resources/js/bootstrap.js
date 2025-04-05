import "bootstrap";
import axios from "axios";
import Echo from "laravel-echo";
import Pusher from "pusher-js";

window.axios = axios;
window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";

// Enable Pusher debugging
Pusher.logToConsole = true;

window.Pusher = Pusher;

// Get Pusher credentials from environment
const pusherKey = import.meta.env.VITE_PUSHER_APP_KEY;
const pusherCluster = import.meta.env.VITE_PUSHER_APP_CLUSTER;

console.log("Pusher Key:", pusherKey);
console.log("Pusher Cluster:", pusherCluster);

window.Echo = new Echo({
    broadcaster: "pusher",
    key: pusherKey,
    cluster: pusherCluster,
    forceTLS: true,
});

// Log when Echo is ready
window.Echo.connector.pusher.connection.bind("connected", () => {
    console.log("Echo connected to Pusher successfully");
});

// Log any connection errors
window.Echo.connector.pusher.connection.bind("error", (error) => {
    console.error("Echo connection error:", error);
});

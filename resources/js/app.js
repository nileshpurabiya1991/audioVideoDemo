import './bootstrap';
import { createApp } from 'vue';
import Echo from 'laravel-echo';
import { io } from 'socket.io-client';

// Socket.IO client setup
window.io = io;
window.Echo = new Echo({
    broadcaster: 'socket.io',
    host: 'http://192.168.1.246:6001' // Laravel Echo Server
});

// Vue setup
const app = createApp({});

// Example component
import VideoCall from './components/VideoCall.vue';
app.component('video-call', VideoCall);
// import ExampleComponent from './components/ExampleComponent.vue';
// app.component('example-component', ExampleComponent);


app.mount('#app');

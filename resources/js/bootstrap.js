import 'bootstrap';
import Echo from 'laravel-echo';
import { io } from 'socket.io-client';

window.io = io;

window.Echo = new Echo({
    broadcaster: 'socket.io',
    host: 'http://192.168.1.246:6001', // Echo server
});

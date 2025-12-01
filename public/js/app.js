import Echo from 'laravel-echo';
import Pusher from 'pusher-js';
import './bootstrap';

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'reverb',
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: import.meta.env.VITE_REVERB_HOST ?? window.location.hostname,
    wsPort: import.meta.env.VITE_REVERB_PORT ?? 8080,
    wssPort: import.meta.env.VITE_REVERB_PORT ?? 8080,
    forceTLS: false,
    enabledTransports: ['ws', 'wss'],
});

window.Echo.connector.pusher.connection.bind('connected', () => {
    console.log('✅ Reverb connected!');
});
window.Echo.connector.pusher.connection.bind('error', (err) => {
    console.error('❌ Reverb error:', err);
});

window.updateSeatColor = function (seatIDs, status) {
    if (!Array.isArray(seatIDs)) seatIDs = [seatIDs];

    seatIDs.forEach(id => {
        const el = document.querySelector(`[data-seat-id="${id}"]`);
        if (!el) return;

        el.classList.remove('selected', 'held', 'booked');

        switch (status) {
            case 'held': el.classList.add('held'); break;          // 🟡 Giữ chỗ
            case 'unavailable': 
            case 'booked': el.classList.add('booked'); break;      // 🔴 Đã đặt
            case 'available': default:
                el.classList.remove('booked', 'held'); break;      // ⚪ Trống
        }
    });
};

window.initSeatRealtime = function (showtimeID) {
    console.log(`🎧 Listening to channel: showtime.${showtimeID}`);

    const channel = window.Echo.channel(`showtime.${showtimeID}`);

    channel.listen('.SeatStatusUpdated', (e) => {
        console.log('📡 Event nhận từ server:', e);
        if (e && e.seats && e.status) {
            window.updateSeatColor(e.seats, e.status);
        }
    });
};

import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

// Register Service Worker for PWA
if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
        navigator.serviceWorker.register('/sw.js')
            .then((reg) => {
                console.log('PWA Service Worker registered successfully:', reg.scope);
            })
            .catch((err) => {
                console.error('PWA Service Worker registration failed:', err);
            });
    });
}


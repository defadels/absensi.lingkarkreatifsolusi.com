const CACHE_NAME = 'absensi-lks-cache-v1';
const OFFLINE_URL = '/offline';

const ASSETS_TO_CACHE = [
    OFFLINE_URL,
    '/Logo-Links.png',
    '/links-logo-new.png',
    '/favicon.ico',
    '/icons/icon-192x192.png',
    '/icons/icon-512x512.png',
];

self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME).then((cache) => {
            return cache.addAll(ASSETS_TO_CACHE);
        })
    );
    self.skipWaiting();
});

self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys().then((cacheNames) => {
            return Promise.all(
                cacheNames.map((cache) => {
                    if (cache !== CACHE_NAME) {
                        return caches.delete(cache);
                    }
                })
            );
        })
    );
    self.clients.claim();
});

self.addEventListener('fetch', (event) => {
    // Only cache GET requests
    if (event.request.method !== 'GET') return;

    // For HTML navigation requests, try network first, then fall back to offline page
    if (event.request.mode === 'navigate') {
        event.respondWith(
            fetch(event.request)
                .catch(() => {
                    return caches.open(CACHE_NAME).then((cache) => {
                        return cache.match(OFFLINE_URL);
                    });
                })
        );
        return;
    }

    // For other assets (CSS, JS, images, fonts), use Stale-While-Revalidate
    event.respondWith(
        caches.match(event.request).then((cachedResponse) => {
            if (cachedResponse) {
                // Fetch in background to update cache
                fetch(event.request).then((networkResponse) => {
                    if (networkResponse.status === 200) {
                        caches.open(CACHE_NAME).then((cache) => {
                            cache.put(event.request, networkResponse);
                        });
                    }
                }).catch(() => {/* ignore background fetch errors */});
                return cachedResponse;
            }

            return fetch(event.request).then((networkResponse) => {
                // If response is valid, clone and cache it (only static assets)
                const url = new URL(event.request.url);
                const isStaticAsset = url.pathname.match(/\.(js|css|png|jpg|jpeg|gif|svg|ico|woff|woff2|ttf)$/);
                
                if (networkResponse.status === 200 && isStaticAsset) {
                    const responseToCache = networkResponse.clone();
                    caches.open(CACHE_NAME).then((cache) => {
                        cache.put(event.request, responseToCache);
                    });
                }
                return networkResponse;
            }).catch(() => {
                // Return cached fallback if available
                return caches.match(event.request);
            });
        })
    );
});

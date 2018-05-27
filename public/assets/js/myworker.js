
// in myworker.js
// cache files under the my-site-cache-v1 namespace
var CACHE_NAME = 'my-site-cache-v1';
var urlsToCache = [
  '/',
  '/assets/css/main.css',
  '/assets/js/main.js'
];

self.addEventListener('install', function (event) {
  // Perform install steps — cache given URLs
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then(function (cache) {
        console.log('Opened cache');
        return cache.addAll(urlsToCache);
      })
  );
});

// define proxy to return cached files when app requests them
self.addEventListener('fetch', function (event) {
  event.respondWith(
    caches.match(event.request)
      .then(function (response) {
          // Cache hit - return response
          if (response) {
            return response;
          }

          return fetch(event.request);
        }
      )
  );
});
const CACHE_NAME = 'cio-pointage';

const ASSETS_TO_CACHE = [
  './dist/',
  './dist/index.php',
  './dist/css/style.css',
  //  './dist/js/app.js',      
  './dist/manifest.json'
];
self.addEventListener('install', (event) => {
  event.waitUntil(
    caches.open(CACHE_NAME).then((cache) => {
      return Promise.all(
        ASSETS_TO_CACHE.map(url => {
          return cache.add(url).catch(err => console.error('Failed to cache:', url, err));
        })
      );
    })
  );
});
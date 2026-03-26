console.log = function () { };
console.info = function () { };
console.warn = function () { };
console.error = function () { };

function showTeamsNotification(title, message) {
    if (!("Notification" in window)) {
        console.log("This browser does not support desktop notification");
        return;
    }

    if (Notification.permission === "granted") {
        createNotification(title, message);
    } else if (Notification.permission !== "denied") {
        Notification.requestPermission().then(permission => {
            if (permission === "granted") {
                createNotification(title, message);
            }
        });
    }
}

function createNotification(title, message) {
    const options = {
        body: message,
        icon: './img/C.png',
        silent: false,
        requireInteraction: true
    };

    new Notification(title, options);
}

// showTeamsNotification("Pointage Update", 'Test');
//getnotifications

function initPWA(swPath = '../sw.js') {
    if ('serviceWorker' in navigator) {
        window.addEventListener('load', () => {
            navigator.serviceWorker.register(swPath)
                // .then(reg => console.log('PWA Service Worker Registered!', reg))
                // .catch(err => console.error('PWA Registration Failed:', err));
        });
    }
}
console.log = function () { };
console.info = function () { };
console.warn = function () { };
console.error = function () { };

function initPWA(swPath = '../sw.js') {
    if ('serviceWorker' in navigator) {
        window.addEventListener('load', () => {
            navigator.serviceWorker.register(swPath)
            // .then(reg => console.log('PWA Service Worker Registered!', reg))
            // .catch(err => console.error('PWA Registration Failed:', err));
        });
    }
}


/**
 * DB-DRIVEN NOTIFICATION ENGINE
 * isSent = 1 (Delivered to browser)
 * isSeen = 1 (User closed/clicked)
 */

async function checkMyNotifications() {

    try {
        // Get items where isSent is 0
        const res = await fetch(`../api/notifications.php?action=get_notifs`);
        const notifications = await res.json();

        if (notifications && notifications.length > 0) {
            for (const notif of notifications) {
                // STEP 1: Immediately tell DB this is now "Sent" (isSent = 1)
                // This stops the notification from popping up again in the next poll
                await fetch(`../api/notifications.php?action=mark_sent&id=${notif.id}`);

                // STEP 2: Trigger the Browser Notification
                showTeamsNotification(notif.sender, notif.title + "\n" + notif.content, notif.id, notif.senderPic);
            }
        }
    } catch (e) {
        console.error("Polling error:", e);
    }
}

function showTeamsNotification(title, message, notifId, senderPic) {
    if (!("Notification" in window)) return;

    if (Notification.permission === "granted") {
        createNotification(title, message, notifId, senderPic);
    } else if (Notification.permission !== "denied") {
        Notification.requestPermission().then(permission => {
            if (permission === "granted") createNotification(title, message, notifId, senderPic);
        });
    }
}

function createNotification(title, message, notifId, senderPic) {
    const baseUrl = window.location.origin;
    const iconPath = baseUrl + '/cio/dist/assets/img/C.png';
    const options = {
        body: message,
        icon: senderPic ?? generateLocalAvatar(title, 100, 'circle') ?? iconPath, // Use the full URL
        badge: iconPath,
        requireInteraction: true
    };

    const n = new Notification(title, options);

    // If user clicks the notification itself
    n.onclick = function () {
        window.focus();
        if (notifId) {
            fetch(`../api/notifications.php?action=mark_read&id=${notifId}`);
        }
        this.close();
    };

    // If user clicks "Fermer" (Close)
    n.onclose = function () {
        if (notifId) {
            // STEP 3: User interacted, set isSeen = 1
            fetch(`../api/notifications.php?action=mark_read&id=${notifId}`);
        }
    };
}




initPWA()
checkMyNotifications();
setInterval(checkMyNotifications, 15000);
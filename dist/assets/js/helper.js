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
            refreshNotifications();
            loadInbox()
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
        // if (notifId) {
        //     fetch(`../api/notifications.php?action=mark_read&id=${notifId}`);
        // }
        window.location.href = "Inbox.php";
        // this.close();
    };

    // If user clicks "Fermer" (Close)
    n.onclose = function () {
        if (notifId) {
            // STEP 3: User interacted, set isSeen = 1
            fetch(`../api/notifications.php?action=mark_read&id=${notifId}`);
        }
    };
}

function generateLocalAvatar(name, size = 100, shape = 'circle') {
    const canvas = document.createElement('canvas');
    canvas.width = size;
    canvas.height = size;
    const context = canvas.getContext('2d');

    // 1. Determine Background Color
    const colors = ["#1abc9c", "#2ecc71", "#3498db", "#9b59b6", "#34495e", "#f1c40f", "#e67e22", "#e74c3c"];
    const charCodeSum = name.split('').reduce((sum, char) => sum + char.charCodeAt(0), 0);
    const color = colors[charCodeSum % colors.length];

    // 2. Draw the Shape
    context.fillStyle = color;
    if (shape === 'circle') {
        context.beginPath();
        context.arc(size / 2, size / 2, size / 2, 0, Math.PI * 2);
        context.fill();
    } else {
        // Default to Rectangle
        context.fillRect(0, 0, size, size);
    }

    // 3. Draw Initials
    const initials = name.split(' ').filter(n => n).map(n => n[0]).join('').toUpperCase().substring(0, 2);
    context.font = `bold ${size / 2.2}px Arial`;
    context.fillStyle = "#FFFFFF";
    context.textAlign = "center";
    context.textBaseline = "middle";
    context.fillText(initials, size / 2, size / 2);

    return canvas.toDataURL();
}

async function refreshNotifications() {
    try {
        const response = await fetch('../api/notifications.php?action=get_notifications_nav');
        const data = await response.json();

        console.log(data)
        if (data.status === 'success') {
            const badge = document.getElementById('notifCount');
            const dropMenu = document.getElementById('notifDrop');

            // 1. Update Badge
            if (badge) {
                badge.innerText = data.unreadCount;
                badge.style.display = data.unreadCount > 0 ? 'block' : 'none';
            }

            if (dropMenu) {
                /** * 2. RESET THE MENU 
                 * We overwrite the entire menu with just the static Header and Footer.
                 * This automatically "clears" all previous notifications and dividers.
                 **/
                dropMenu.innerHTML = `
                    <span class="dropdown-item dropdown-header" id="unrededCount">
                        ${data.unreadCount} Notifications non lues
                    </span>
                    <div class="dropdown-divider"></div>
                    <div id="notifItemsContainer" style="max-height:300px;overflow-y:auto"></div>
                    <a href="Inbox.php" class="dropdown-item dropdown-footer">Afficher toutes les notifications</a>
                `;

                const container = document.getElementById('notifItemsContainer');

                // 3. Inject new notifications
                if (data.notifications.length === 0) {
                    container.innerHTML = '<a href="#" class="dropdown-item text-center text-muted">Aucune notification</a>';
                } else {
                    let htmlBuffer = '';
                    data.notifications.forEach(notif => {
                        // Determine if the notification is unread
                        const isUnread = notif.isSeen == 0;
                        // Added 'is-unread' here so the JS can find it
                        const unreadClass = isUnread ? 'bg-light is-unread' : '';

                        htmlBuffer += `
                            <a href="Inbox.php" class="dropdown-item d-flex align-items-center p-3 ${unreadClass} notif-link" 
                                data-id="${notif.id}"
                                title="Envoyé par ${notif.sender}" 
                                style="border-left: ${isUnread ? '4px solid #007bff' : '4px solid transparent'}; transition: all 0.2s;">
                                
                                <div class="me-3 position-relative">
                                    <img src="https://ui-avatars.com/api/?name=${encodeURIComponent(notif.sender)}&background=random&color=fff"
                                        class="img-circle elevation-1"
                                        alt="User Image"
                                        style="width: 40px; height: 40px; min-width: 40px; object-fit: cover; border-radius: 50%;">
                                    
                                    ${isUnread ? '<span class="unread-dot position-absolute top-0 start-100 translate-middle p-1 bg-primary border border-light rounded-circle"></span>' : ''}
                                </div>

                                <div class="flex-grow-1 overflow-hidden">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="${isUnread ? 'fw-bolder' : 'fw-bold'} text-dark text-sm">${notif.sender}</span>
                                        <span class="text-secondary fs-8">${formatRelativeTime(notif.createdAt)}</span>
                                    </div>
                                    <div class="text-muted fs-6 text-truncate">${notif.title}</div>
                                    <div class="text-muted fs-7 text-truncate" style="white-space: nowrap;">${notif.content}</div>
                                </div>
                            </a>
                            <div class="dropdown-divider m-0"></div>
                        `;
                    });
                    container.innerHTML = htmlBuffer;
                }
            }
        }
    } catch (error) {
        console.error("Erreur notifications:", error);
    }
}
/**
 * Formats date to short strings like 5m, 2h, 1j
 */

function formatRelativeTime(dateString) {
    const date = new Date(dateString);
    const now = new Date();
    const diff = Math.floor((now - date) / 1000);
    if (diff < 60) return "Maintenant";
    if (diff < 3600) return Math.floor(diff / 60) + "m";
    if (diff < 86400) return Math.floor(diff / 3600) + "h";
    return Math.floor(diff / 86400) + "j";
}

async function loadInbox() {
    if (!document.getElementById('Inbox')) {
        return;
    }
    try {
        const response = await fetch('../api/notifications.php?action=get_notifications_nav');
        const data = await response.json();

        if (data.status === 'success') {
            const list = document.getElementById('inbox-list');

            if (data.notifications.length === 0) {
                list.innerHTML = '<div class="p-5 text-center text-muted">Aucune notification trouvée.</div>';
                return;
            }

            list.innerHTML = data.notifications.map(n => {
                const isUnread = n.isSeen == 0;
                const unreadClass = isUnread ? 'is-unread fw-bold bg-light' : 'text-secondary';
                const avatarUrl = `https://ui-avatars.com/api/?name=${encodeURIComponent(n.sender)}&background=random&color=fff&rounded=true`;

                return `
                    <div class="inbox-item p-3 border-bottom ${unreadClass}" 
                         role="button" 
                         data-id="${n.id}"
                         data-full='${JSON.stringify(n).replace(/'/g, "&apos;")}'
                         style="cursor: pointer; position: relative; border-left: ${isUnread ? '4px solid #007bff' : '4px solid transparent'}; transition: all 0.2s;">
                        
                        <div class="d-flex align-items-center">
                            <img src="${avatarUrl}" class="img-circle me-3" style="width: 35px; height: 35px;">
                            <div class="flex-grow-1 overflow-hidden">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-truncate text-dark" style="max-width: 150px;">${n.sender}</span>
                                    <small class="text-muted" style="font-size: 0.7rem;">${formatRelativeTime(n.createdAt)}</small>
                                </div>
                                <div class="text-truncate text-muted" style="font-size: 0.85rem;">${n.title}</div>
                            </div>
                            ${isUnread ? '<span class="unread-dot-small"></span>' : ''}
                        </div>
                    </div>
                `;
            }).join('');
        }
    } catch (err) {
        console.error("Failed to load inbox:", err);
    }
}

function formatDate(dateStr) {
    const [year, month, day] = dateStr.split("-");
    return `${day}/${month}/${year}`;
}

initPWA()
document.addEventListener('DOMContentLoaded', () => {
    if (document.getElementById('loginPart')) {
        return;
    }
    checkMyNotifications();
    refreshNotifications();
    setInterval(() => {
        checkMyNotifications();
        // refreshNotifications();
        // loadInbox()
    }, 15000);
})
<!doctype html>
<html lang="fr">
<?php
ob_start();
include '../api/helpers.php';
include '../api/session_info.php';
isAuthPath();
isAdminPath();
$tables = getTableSize();
$size = getDatabaseSize();
include_once '../api/get_selects.php';
ob_end_flush();
?>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>CIO | Notifications</title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes" />
    <meta name="color-scheme" content="light dark" />
    <meta name="theme-color" content="#007bff" media="(prefers-color-scheme: light)" />
    <meta name="theme-color" content="#1a1a1a" media="(prefers-color-scheme: dark)" />

    <meta name="title" content="CIO v4 | Paramètres" />
    <meta name="author" content="ColorlibHQ" />

    <meta name="keywords"
        content="bootstrap 5, bootstrap, bootstrap 5 admin dashboard, bootstrap 5 dashboard, bootstrap 5 charts, bootstrap 5 calendar, bootstrap 5 datepicker, bootstrap 5 tables, bootstrap 5 datatable, vanilla js datatable, colorlibhq, colorlibhq dashboard, colorlibhq admin dashboard, accessible admin panel, WCAG compliant" />
    <link rel="manifest" href="../manifest.json">
    <meta name="theme-color" content="#6264a7">
    <meta name="supported-color-schemes" content="light dark" />
    <link rel="icon" type="image/png" sizes="32x32" href="./assets/img/C.png">
    <link rel="preload" href="./assets/css/adminlte.css" as="style" />
    <link rel="stylesheet" href="./assets/css/index.css" media="print" onload="this.media = 'all'" />
    <link rel="stylesheet" href="./assets/css/overlayscrollbars.min.css" />
    <link rel="stylesheet" href="./assets/css/bootstrap-icons.min.css" />
    <link rel="stylesheet" href="./assets/css/adminlte.css" />
    <link rel="stylesheet" href="./assets/css/apexcharts.css" />
    <link rel="stylesheet" href="./assets/css/jsvectormap.min.css" />
    <!-- <link rel="stylesheet" href="./assets/css/handsontable.full.min.css"> -->
    <script src="./assets/js/helper.js"></script>

    <style>
        /* @import url('./assets/css/css2.css'); */
        #custom-tabs .nav-link:hover {
            color: #0d6efd !important;
        }

        .showing {
            color: #0d6efd !important;
        }

        .list-group-item {
            transition: background-color 0.2s ease;
            border-left: 3px solid transparent;
        }

        .list-group-item:hover {
            background-color: #f8f9fa;
            border-left: 3px solid #0d6efd;
            /* Red accent matching your active tab */
        }

        .btn-group .btn-default {
            background-color: white;
            border: 1px solid #ddd;
        }
    </style>
</head>

<body class="fixed-header layout-fixed sidebar-expand-lg bg-body-tertiary">
    <!--  -->
    <?php
    if ($_SESSION['needReset']) {
        include './components/resetModal.php';
    } else {
        include './components/splashscreen.php';
    }
    ?>
    <!--  -->
    <div class="app-wrapper">
        <nav class="app-header navbar navbar-expand bg-body">
            <div class="container-fluid">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
                            <i class="bi bi-list"></i>
                        </a>
                    </li>
                </ul>
                <?php
                $currentPage = 'notifications';
                include './components/profileNav.php';
                ?>
            </div>
        </nav>
        <?php
        $userRole = $role;
        $currentPage = 'notifications';
        include './components/sidebar.php';
        ?>
        <main class="app-main">
            <div class="app-content-header">
                <div class="container-fluid">
                </div>
            </div>
            <div class="app-content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-4 col-lg-4">
                            <div class="card card-primary card-outline shadow-none">
                                <div class="card-header">
                                    <h3 class="card-title">Destinataires</h3>
                                </div>
                                <div class="card-body p-0">
                                    <div class="p-2 border-bottom">
                                        <input type="text" id="agentSearch" class="form-control form-control-sm"
                                            placeholder="Chercher un destinataire..." onkeyup="filterListAgents()">
                                        <button class="btn btn-xs btn-primary w-100 mt-1" onclick="selectAllUsers()">Tout sélectionner</button>
                                    </div>

                                    <div id="userListContainer" style="height: 500px; overflow-y: auto;">
                                        <ul class="list-group list-group-flush" id="user-notifications-list">
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-8">
                            <div class="card card-primary card-outline shadow-none">
                                <div class="card-header">
                                    <h3 class="card-title">Message</h3>
                                </div>
                                <div class="card-body p-2">
                                    <form id="notificationForm">
                                        <div class="form-group">
                                            <label class="small text-muted font-weight-bold">OBJET</label>
                                            <input type="text" class="form-control" id="notifTitle"
                                                placeholder="Titre de la notification..." required>
                                        </div>

                                        <div class="form-group">
                                            <label class="small text-muted font-weight-bold">MESSAGE</label>
                                            <textarea class="form-control" id="notifContent" rows="12"
                                                placeholder="Tapez votre contenu ici..." style="resize: none;" required></textarea>
                                        </div>

                                        <div class="text-right mt-2" style="display: flex;justify-content: end;gap:5px">
                                            <button type="button" class="btn btn-secondary mr-2" onclick="clearForm()">Annuler</button>
                                            <button type="button" class="btn btn-primary px-4" onclick="sendNotif()"> Envoyer </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
    <script src="./assets/js/overlayscrollbars.browser.es6.min.js"
        crossorigin="anonymous"></script>
    <script src="./assets/js/popper.min.js"></script>
    <script src="./assets/js/bootstrap.min.js" crossorigin="anonymous"></script>
    <script src="./assets/js/adminlte.js"></script>
    <script src="./assets/js/jquery-3.7.0.min.js"></script>
    <script src="./assets/js/sweetalert2@11.js"></script>

    <script>
        const SELECTOR_SIDEBAR_WRAPPER = '.sidebar-wrapper';
        const Default = {
            scrollbarTheme: 'os-theme-light',
            scrollbarAutoHide: 'leave',
            scrollbarClickScroll: true,
        };

        document.addEventListener('DOMContentLoaded', function() {
            const sidebarWrapper = document.querySelector(SELECTOR_SIDEBAR_WRAPPER);
            const body = document.body;

            // --- New Logic to handle collapse ---
            function handleSidebar() {
                const width = window.innerWidth;

                if (width < 992) {
                    // Force collapse on small screens
                    body.classList.add('sidebar-collapse');
                    body.classList.remove('sidebar-open');
                } else {
                    // Optional: remove collapse when going back to desktop
                    body.classList.remove('sidebar-collapse');
                }
            }

            // Run on load
            handleSidebar();

            // Run on resize
            window.addEventListener('resize', handleSidebar);
            // --- End of New Logic ---

            const isMobile = window.innerWidth <= 992;

            if (
                sidebarWrapper &&
                OverlayScrollbarsGlobal?.OverlayScrollbars !== undefined &&
                !isMobile
            ) {
                OverlayScrollbarsGlobal.OverlayScrollbars(sidebarWrapper, {
                    scrollbars: {
                        theme: Default.scrollbarTheme,
                        autoHide: Default.scrollbarAutoHide,
                        clickScroll: Default.scrollbarClickScroll,
                    },
                });
            }
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const listContainer = document.getElementById('user-notifications-list');

            fetch('../api/get_agents.php?from=notifications')
                .then(res => res.json())
                .then(response => {
                    // Check if status is success and data is an array
                    if (response.status !== "success" || !Array.isArray(response.data)) {
                        listContainer.innerHTML = '<li class="list-group-item text-center text-muted">Erreur de formatage des données</li>';
                        return;
                    }

                    const agents = response.data;

                    if (agents.length === 0) {
                        listContainer.innerHTML = '<li class="list-group-item text-center text-muted">Aucun destinataire trouvé</li>';
                        return;
                    }

                    let html = '';
                    agents.forEach((agent, index) => {
                        // Formatting name for the avatar (cleaning up extra spaces)
                        const fullName = `${agent.nom.trim()} ${agent.prenom.trim()}`;

                        // Use profilePic if available, otherwise fallback to UI-Avatars
                        const avatarUrl = agent.profilePic ?
                            agent.profilePic :
                            `https://ui-avatars.com/api/?name=${encodeURIComponent(fullName)}&background=random&color=fff`;

                        html += `
                <li class="list-group-item d-flex align-items-center py-2 border-bottom clickable-user" style="cursor: pointer; gap:10px" data-id="${agent.id}">
                    <div class="custom-control custom-checkbox mr-3">
                        <input class="custom-control-input user-checkbox" type="checkbox" id="user${agent.id}">
                        <label for="user${agent.id}" class="custom-control-label"></label>
                    </div>

                    <div class="avatar-container mr-3">
                        <img src="${avatarUrl}"
                            class="img-circle elevation-1"
                            alt="User Image"
                            style="width: 40px; height: 40px; object-fit: cover; border-radius: 50%;">
                    </div>

                    <div class="user-info">
                        <p class="mb-0 font-weight-bold" style="font-size: 0.95rem; line-height: 1.2;">${fullName}</p>
                        <small class="text-muted" style="font-size: 0.8rem;">Immat: ${agent.idFiscal} ${agent.idProx ? `| Prox: ${agent.idProx}` : ''}</small>
                    </div>
                </li>`;
                    });

                    listContainer.innerHTML = html;
                })
                .catch(err => {
                    console.error(err);
                    Swal.fire({
                        icon: "error",
                        title: "Erreur",
                        text: 'Erreur réseau: ' + err,
                    });
                });
        });
    </script>
    <script>
        const listContainer = document.getElementById('user-notifications-list');

        function selectAllUsers() {
            // Get all checkboxes inside your specific list
            const checkboxes = document.querySelectorAll('#user-notifications-list .user-checkbox');

            // Check if at least one is NOT checked
            const areSomeUnchecked = Array.from(checkboxes).some(cb => !cb.checked);

            checkboxes.forEach(cb => {
                // If some are unchecked, check them all. Otherwise (if all are checked), uncheck them all.
                cb.checked = areSomeUnchecked;

                // Optional: Trigger a change event if you have other listeners 
                // watching for checkbox changes
                cb.dispatchEvent(new Event('change'));
            });

            // Optional: Change button text dynamically
            const btn = event.currentTarget;
            btn.textContent = areSomeUnchecked ? "Tout désélectionner" : "Tout sélectionner";
        }

        function getSelectedAgentIds() {
            const selected = [];
            const checkedBoxes = document.querySelectorAll('#user-notifications-list .user-checkbox:checked');

            checkedBoxes.forEach(cb => {
                // We go up to the <li> parent to grab the data-id we stored earlier
                const li = cb.closest('li');
                selected.push(li.getAttribute('data-id'));
            });

            return selected;
        }

        function filterListAgents() {
            // 1. Get the search string and convert to lowercase for case-insensitive matching
            const input = document.getElementById('agentSearch');
            const filter = input.value.toLowerCase();

            // 2. Get all the agent list items
            const listItems = document.querySelectorAll('#user-notifications-list li.clickable-user');

            listItems.forEach(li => {
                // 3. Get the text content (Name + ID)
                const textContent = li.textContent || li.innerText;

                // 4. Toggle visibility: if the filter exists in the text, show it; otherwise hide it
                if (textContent.toLowerCase().indexOf(filter) > -1) {
                    li.style.setProperty('display', 'flex', 'important');
                } else {
                    li.style.setProperty('display', 'none', 'important');
                }

                // Check if any items are visible
                const visibleItems = document.querySelectorAll('#user-notifications-list li.clickable-user[style*="display: flex"]');
                let noResultsMsg = document.getElementById('no-results-found');

                if (visibleItems.length === 0 && filter !== "") {
                    if (!noResultsMsg) {
                        const msg = document.createElement('li');
                        msg.id = 'no-results-found';
                        msg.className = 'list-group-item text-center text-muted border-0';
                        msg.innerText = 'Aucun destinataire ne correspond à votre recherche';
                        document.getElementById('user-notifications-list').appendChild(msg);
                    }
                } else {
                    if (noResultsMsg) noResultsMsg.remove();
                }
            });
        }

        // Add this inside your DOMContentLoaded fetch, after listContainer.innerHTML = html;
        listContainer.addEventListener('click', function(e) {
            // If the click was on the checkbox itself, let it happen naturally
            if (e.target.classList.contains('user-checkbox')) return;

            // Find the closest list item
            const li = e.target.closest('.clickable-user');
            if (li) {
                const checkbox = li.querySelector('.user-checkbox');
                checkbox.checked = !checkbox.checked;
                checkbox.dispatchEvent(new Event('change'));
            }
        });

        async function sendNotif() {
            const title = document.getElementById('notifTitle').value;
            const message = document.getElementById('notifContent').value;

            // Get checked IDs
            const selectedIds = Array.from(document.querySelectorAll('#user-notifications-list .user-checkbox:checked'))
                .map(cb => cb.closest('li').getAttribute('data-id'));

            if (!title || !message || selectedIds.length === 0) {
                return Swal.fire("Champs requis", "Veuillez remplir le message et choisir au moins un destinataire.", "info");
            }

            try {
                const response = await fetch('../api/notifications.php?action=send_notif', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        title: title,
                        message: message,
                        agents: selectedIds
                    })
                });

                const result = await response.json();

                if (result.status === "success") {
                    Swal.fire("Envoyé !", result.message, "success");
                    clearForm();
                } else {
                    Swal.fire("Erreur", result.message, "error");
                }
            } catch (error) {
                Swal.fire("Erreur", "Connexion au serveur impossible.", "error");
            }
        }


        function clearForm() {
            // 1. Reset the text inputs and textarea
            const form = document.getElementById('notificationForm');
            form.reset();

            // 2. Uncheck all agents in the list
            const checkboxes = document.querySelectorAll('#user-notifications-list .user-checkbox');
            checkboxes.forEach(cb => {
                cb.checked = false;
            });

            // 3. Reset the "Select All" button text if you used the toggle logic
            const selectAllBtn = document.querySelector('button[onclick="selectAllUsers()"]');
            if (selectAllBtn) {
                selectAllBtn.textContent = "Tout sélectionner";
            }

            // 4. Optional: Clear the search filter to show all agents again
            const searchInput = document.getElementById('agentSearch');
            if (searchInput) {
                searchInput.value = '';
                filterListAgents(); // Run the filter to restore visibility
            }

            // Small toast notification to confirm action
            console.log("Formulaire et sélections réinitialisés.");
        }
    </script>
    <script src="./assets/js/Sortable.min.js"></script>
    <script src="./assets/js/apexcharts.min.js"></script>
    <script src="./assets/js/jsvectormap.min.js"></script>
    <script src="./assets/js/world.js"></script>

</body>

</html>
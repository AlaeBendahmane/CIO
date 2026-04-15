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
    <title>CIO | Envoie en masse</title>

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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

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

        .drop-zone {
            border: 2px dashed #ddd;
            border-radius: 8px;
            padding: 10px;
            text-align: center;
            background: #fdfdfd;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .drop-zone.dragover {
            background: #f0f1ff;
            border-color: #6264a7;
            transform: scale(1.01);
        }

        .clickable {
            cursor: pointer;
        }

        .drop-zone-content i {
            transition: transform 0.3s ease;
        }

        .drop-zone:hover .drop-zone-content i {
            transform: translateY(-5px);
        }

        #file-info .card {
            border-left: 4px solid #6264a7 !important;
            /* Teams purple accent */
        }

        .btn-link:hover {
            background: #ffe5e5;
            border-radius: 50%;
        }

        /* Ensure the dropzone doesn't look empty when a file is ready */
        .drop-zone.has-file {
            padding: 15px;
            border-style: solid;
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
                $currentPage = 'massSend';
                include './components/profileNav.php';
                ?>
            </div>
        </nav>
        <?php
        $userRole = $role;
        $currentPage = 'massSend';
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

                                    <div id="userListContainer" style="height: 500px;overflow: hidden ; overflow-y: auto;">
                                        <ul class="list-group list-group-flush" id="user-notifications-list">
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-8">
                            <div class="card card-primary card-outline shadow-none">
                                <div class="card-header">
                                    <h3 class="card-title">Document</h3>
                                </div>
                                <div class="card-body p-2">
                                    <form id="notificationForm">
                                        <div class="form-group">
                                            <label class="small text-muted font-weight-bold">Nom du document</label>
                                            <input type="text" class="form-control" id="docTitle"
                                                placeholder="Nom du document..." required>
                                        </div>

                                        <div class="form-group mt-2">
                                            <label class="small text-muted font-weight-bold">Fichier à envoyer</label>
                                            <div id="drop-zone" class="drop-zone">
                                                <div class="drop-zone-content">
                                                    <i class="bi bi-cloud-arrow-up-fill" style="font-size: 2.5rem; color: #6264a7;"></i>
                                                    <p class="mb-0 mt-2">Glissez votre fichier ici ou <strong>cliquez pour choisir</strong></p>
                                                    <span class="text-muted small">PDF, Doc, Excel...</span>
                                                </div>
                                                <input type="file" id="fileInput" accept=".pdf,.doc,.docx,.xls,.xlsx,.csv,.txt" style="display: none;">
                                                <div id="file-info" class="d-none">
                                                    <div class="card shadow-sm border-0 bg-light">
                                                        <div class="card-body p-2 d-flex align-items-center" style="height: 116px;">
                                                            <div id="preview-container" class="mr-3" style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; background: #fff; border-radius: 4px; overflow: hidden; border: 1px solid #eee;">
                                                            </div>

                                                            <div class="flex-grow-1 min-width-0">
                                                                <p id="file-name" class="mb-0 font-weight-bold text-truncate" style="font-size: 0.9rem;"></p>
                                                                <small id="file-size" class="text-muted"></small>
                                                            </div>

                                                            <button type="button" class="btn btn-link text-danger p-2" onclick="clearFile()">
                                                                <i class="bi bi-trash3-fill"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="text-right mt-2" style="display: flex;justify-content: end;gap:5px">
                                            <button type="button" class="btn btn-secondary mr-2" onclick="clearForm()">Annuler</button>
                                            <button type="button" class="btn btn-primary px-4" onclick="sendDoc()"> Envoyer </button>
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
        let selectedFile = null;
        const listContainer = document.getElementById('user-notifications-list');
        const dropZone = document.getElementById('drop-zone');
        const fileInput = document.getElementById('fileInput');
        const fileInfo = document.getElementById('file-info');
        const fileNameDisplay = document.getElementById('file-name');
        const dropZoneContent = document.querySelector('.drop-zone-content');

        // Open file dialog on click
        dropZone.addEventListener('click', () => fileInput.click());

        // Drag and drop events
        ['dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, e => {
                e.preventDefault();
                e.stopPropagation();
            });
        });

        dropZone.addEventListener('dragover', () => dropZone.classList.add('dragover'));
        dropZone.addEventListener('dragleave', () => dropZone.classList.remove('dragover'));

        dropZone.addEventListener('drop', (e) => {
            dropZone.classList.remove('dragover');
            handleFiles(e.dataTransfer.files[0]);
        });

        fileInput.addEventListener('change', (e) => handleFiles(e.target.files[0]));

        function handleFiles(file) {
            if (!file) return;

            selectedFile = file;
            const previewContainer = document.getElementById('preview-container');
            const fileNameDisplay = document.getElementById('file-name');
            const fileSizeDisplay = document.getElementById('file-size');

            fileNameDisplay.textContent = file.name;
            fileSizeDisplay.textContent = (file.size / 1024).toFixed(1) + ' KB';

            // Reset Preview Container
            previewContainer.innerHTML = '';

            // Generate Preview
            if (file.type.startsWith('image/')) {
                const img = document.createElement('img');
                img.src = URL.createObjectURL(file);
                img.style.width = '100%';
                img.style.height = '100%';
                img.style.objectFit = 'cover';
                previewContainer.appendChild(img);
            } else {
                // Fallback icon for PDF/Excel/Docs
                let iconClass = 'bi-file-earmark-fill';
                if (file.name.endsWith('.pdf')) iconClass = 'fa-file-pdf';
                if (file.name.endsWith('.xlsx') || file.name.endsWith('.csv') || name.endsWith('.xls')) iconClass = 'fas fa-file-excel';
                if (file.name.endsWith('.docx') || name.endsWith('.docx')) iconClass = 'fa-file-word icon';

                previewContainer.innerHTML = `<i class="fas ${iconClass}" style="font-size: 2rem; color: #6264a7;"></i>`;
            }

            // UI Transitions
            dropZoneContent.classList.add('d-none');
            fileInfo.classList.remove('d-none');

            // Auto-fill Title
            // const titleInput = document.getElementById('notifTitle');
            // if (!titleInput.value) {
            //     titleInput.value = file.name.split('.').slice(0, -1).join('.');
            // }
        }

        function clearFile() {
            selectedFile = null;
            fileInput.value = '';
            dropZoneContent.classList.remove('d-none');
            fileInfo.classList.add('d-none');
        }

        function clearForm() {
            // 1. Reset the standard form fields (Title, etc.)
            const form = document.getElementById('notificationForm');
            form.reset();

            // 2. Call our specific file clearing logic
            clearFile();
        }

        function sendDoc() {
            const title = document.getElementById('docTitle').value;
            const selectedUsers = Array.from(document.querySelectorAll('.user-checkbox:checked'))
                .map(cb => cb.id.replace('user', ''));

            if (!title || selectedUsers.length === 0) {
                Swal.fire("Attention", "Veuillez choisir un titre et au moins un destinataire", "warning");
                return;
            }

            if (!selectedFile) {
                Swal.fire("Fichier manquant", "Veuillez glisser un fichier avant d'envoyer", "warning");
                return;
            }

            const formData = new FormData();
            formData.append('title', title);
            formData.append('file', selectedFile);


            if (selectedUsers.length == 1) {
                formData.append('ref_user_id', selectedUsers);
                formData.append('sendType', 'single');

            } else {
                formData.append('ref_user_ids', JSON.stringify(selectedUsers));
                formData.append('sendType', 'multiple');
            }

            if (selectedUsers.length > 10) {
                Swal.fire({
                    title: 'Envoi en cours...',
                    didOpen: () => Swal.showLoading()
                });
            }


            fetch('../api/upload_pdf.php', {
                    method: 'POST',
                    body: formData,
                    contentType: false,
                    processData: false,
                })
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'success') {
                        Swal.fire("Succès", "Document envoyé avec succès", "success");
                        clearForm();
                        clearFile();
                    } else {
                        Swal.fire("Erreur", data.message, "error");
                    }
                });
        }

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
    </script>
    <script src="./assets/js/Sortable.min.js"></script>
    <script src="./assets/js/apexcharts.min.js"></script>
    <script src="./assets/js/jsvectormap.min.js"></script>
    <script src="./assets/js/world.js"></script>

</body>

</html>
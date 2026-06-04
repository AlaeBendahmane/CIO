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
    <title>CIO | Planing</title>

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
                $currentPage = 'planing';
                include './components/profileNav.php';
                ?>
            </div>
        </nav>
        <?php
        $userRole = $role;
        $currentPage = 'planing';
        include './components/sidebar.php';
        ?>
        <main class="app-main">
            <div class="app-content-header">
                <div class="container-fluid">
                </div>
            </div>
            <div class="app-content">
                <div class="container-fluid">
                    <div class="row mb-3">
                        <div class="col-12">
                            <?php include './components/Agenda.php' ?>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        <!--  -->
        <div class="offcanvas offcanvas-end" style="--bs-offcanvas-width: 350px;" data-bs-scroll="true" tabindex="-1" id="offcanvasWithBothOptions" aria-labelledby="offcanvasWithBothOptionsLabel">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="offcanvasWithBothOptionsLabel">Détails de l'événement</h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>

            <div class="offcanvas-body" style="overflow: hidden; display: flex; flex-direction: column; height: calc(100vh - 60px);">
                <!-- Hidden inputs to track state -->
                <form id="eventForm" class="flex-shrink-0 pb-2">
                    <input type="hidden" id="event_id">
                    <input type="hidden" id="start_date">
                    <input type="hidden" id="end_date">

                    <div class="mb-3">
                        <label class="form-label">Titre</label>
                        <input type="text" class="form-control" id="event_title" required placeholder="ex: Shift Matin">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Période</label>
                        <div style="display: flex;gap:10px">
                            <input type="time" min="07:00" max="21:00" class="form-control" id="event_start_time" required>
                            <input type="time" min="07:00" max="21:00" class="form-control" id="event_end_time" required>
                        </div>
                    </div>

                    <div class="d-grid gap-2 mt-4">
                        <button type="button" class="btn btn-primary w-100" id="btnSaveEvent">Enregistrer</button>
                        <button type="button" class="btn btn-outline-danger" id="btnDeleteEvent" style="display: none;">
                            <i class="bi bi-trash"></i> Supprimer l'événement
                        </button>
                    </div>
                </form>

                <div class="mt-3 flex-grow-1  border-top " id="shiftHistrory" style="display: none; overflow-y: auto; padding-right: 5px;">
                    <label class="form-label text-muted small fw-bold sticky-top bg-white w-100 pb-2">
                        Historique des modifications
                    </label>

                    <div id="timelineLoader" class="text-center my-3">
                        <div class="spinner-border spinner-border-sm text-primary" role="status">
                            <span class="visually-hidden">Chargement...</span>
                        </div>
                        <span class="ms-2 small text-muted">Chargement de l'historique...</span>
                    </div>

                    <div class="timeline" id="timelineContainer" style="display: none;">
                        <div id="timelineEndClock">
                            <i class="timeline-icon bi bi-clock-fill text-bg-secondary"></i>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <!--  -->
        <div class="offcanvas offcanvas-end" style="--bs-offcanvas-width: 350px;" data-bs-scroll="true" tabindex="-1" id="offcanvasHistorique" aria-labelledby="offcanvasHistoriqueLabel">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="offcanvasHistoriqueLabel"> <i class="fas fa-history me-2 text-secondary"></i>Historique des changements</h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>

            <div class="offcanvas-body">
                aaa
            </div>
        </div>
        <!--  -->
        <div class="offcanvas offcanvas-end" style="--bs-offcanvas-width: 350px;" data-bs-scroll="true" tabindex="-1" id="offcanvasMasse" aria-labelledby="offcanvasMasseLabel">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="offcanvasMasseLabel"> <i class="fas fa-upload me-2 text-secondary"></i>Import massif</h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>

            <div class="offcanvas-body">
                <form id="massUploadForm" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="csv_file" class="form-label font-weight-bold">Fichier CSV des horaires</label>
                        <input class="form-control" type="file" id="csv_file" name="csv_file" accept=".csv" required>
                        <div class="form-text">Veuillez enregistrer votre fichier Excel au format .csv avant de l'importer.</div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100" id="btnUploadMasse">
                        <i class="fas fa-upload me-1"></i> Importer les horaires
                    </button>
                </form>

                <hr class="my-4  d-none" id="spliiiit">

                <div id="uploadResult" class="d-none"></div>
            </div>
        </div>
        <!--  -->
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

    <script src='./assets/js/index.global.min.js'></script>

    <script>
        // 1. Move the helper function outside or keep it accessible
        function getResponsiveHeight() {
            // 140px accounts for your header and card padding
            return window.innerHeight - 215;
        }

        document.addEventListener('DOMContentLoaded', function() {
            fetch('../api/get_agents.php?from=planing')
                .then(res => res.json())
                .then(
                    response => {
                        const $select = $('#agentSelect'); // Ensure your <select> has id="agentSelect"
                        // 1. Clear existing hardcoded options (Alabama, Alaska, etc.)
                        $select.empty();

                        // 2. Add an "All" or Placeholder option
                        $select.append(new Option("Sélectionnez un agent", "", true, true));

                        // 3. Loop through your data
                        response.data.forEach(agent => {
                            // Concatenate NOM and PRENOM for the display text
                            const fullName = `${agent.nom} ${agent.prenom} (${agent.idProx? agent.idProx :'----'})`;


                            // Create new Option: new Option(text, value, defaultSelected, selected)
                            const newOption = new Option(fullName, agent.id, false, agent.selected);

                            $select.append(newOption);
                        });

                        // 4. Refresh Select2 UI
                        $select.trigger('change');
                    })
                .catch(err => console.error('Erreur lors du chargement des agents:', err));

            // Add this inside your DOMContentLoaded, after calendar.render();

            $('#agentSelect').on('change', function() {
                const selectedAgentId = $(this).val();
                if (selectedAgentId == '') {
                    return
                }

                // 1. Remove the old event source
                calendar.getEventSources().forEach(source => source.remove());

                // 2. Add the new event source with the agent_id parameter
                calendar.addEventSource({
                    url: '../api/getShifts.php',
                    extraParams: {
                        agent_id: selectedAgentId
                    }
                });
            });

            const eventOffcanvas = new bootstrap.Offcanvas('#offcanvasWithBothOptions');
            const btnSave = document.getElementById('btnSaveEvent');
            const btnDelete = document.getElementById('btnDeleteEvent');

            const calendarEl = document.getElementById('calendar');
            if (!calendarEl) {
                return
            }
            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'fr',
                noEventsContent: "Aucun événement à afficher",
                firstDay: 1,
                editable: true,
                selectable: true,
                height: getResponsiveHeight(),
                expandRows: true,
                handleWindowResize: true,
                eventOverlap: false,
                displayEventTime: false,
                eventDisplay: 'block',
                dayMaxEvents: 1,
                moreLinkContent: function(args) {
                    return "+ " + args.num + " en plus";
                },
                moreLinkClick: "popover",
                // Initial Height
                height: getResponsiveHeight(),
                aspectRatio: 1.5,
                expandRows: true,
                handleWindowResize: true,

                eventOverlap: false,
                displayEventTime: false,
                eventDisplay: 'block',
                dayMaxEvents: true,

                slotMinTime: '07:00:00',
                slotMaxTime: '21:00:00',
                slotDuration: '00:15:00',




                headerToolbar: {
                    left: 'prev today next',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay,listDay'
                },
                buttonText: {
                    today: "Aujourd'hui",
                    month: "Mois",
                    week: "Semaine",
                    day: "Jour",
                    listDay: "Planing"
                },

                views: {
                    dayGridMonth: {
                        displayEventTime: false,
                        allDaySlot: false,
                    },
                    timeGridWeek: {
                        slotDuration: '00:30:00', // 30 min slots
                        slotLabelInterval: '01:00:00', // Only show the hour label (08:00, 09:00) to keep it clean
                        displayEventTime: false,
                        slotEventOverlap: false, // THIS IS THE KEY: No more merging/overlapping
                        eventMaxStack: 2,
                        allDaySlot: true,
                        allDayText: 'Toute la journée',
                        // aspectRatio: 1.2,
                        // contentWidth: '900px'
                    },
                    timeGridDay: {
                        displayEventTime: false,
                        slotEventOverlap: false,
                        slotDuration: '00:30:00', // 30 min slots
                        slotLabelInterval: '01:00:00',
                        allDaySlot: true,
                        allDayText: 'Toute la journée',
                    }
                },
                eventDrop: (info) => updateEvent(info.event),
                eventResize: (info) => updateEvent(info.event),
                eventClick: function(info) {
                    const event = info.event;

                    // 1. Reset UI & History Section Panel State
                    const historySection = document.getElementById('shiftHistrory');
                    const loader = document.getElementById('timelineLoader');
                    const timelineContainer = document.getElementById('timelineContainer');
                    const endClock = document.getElementById('timelineEndClock');

                    historySection.style.display = 'block';
                    loader.style.display = 'block';
                    timelineContainer.style.display = 'none';

                    // Clear any previously generated dynamic items (keep only the end clock anchor)
                    const oldItems = timelineContainer.querySelectorAll('.dynamic-timeline-item');
                    oldItems.forEach(item => item.remove());

                    // 2. Fetch History Log Payload Stream
                    fetch(`../api/getShiftHistory.php?shift_id=${event.id}`)
                        .then(res => res.json())
                        .then(response => {
                            loader.style.display = 'none';
                            timelineContainer.style.display = 'block';

                            if (response.status === 'success' && response.data.length > 0) {
                                let currentGroupDate = "";

                                response.data.forEach(log => {
                                    // Extract Date and Time components from "YYYY-MM-DD HH:mm:ss"
                                    const datetimeParts = log.created_at.split(' ');
                                    const rawDate = datetimeParts[0];
                                    const rawTime = datetimeParts[1].substring(0, 5);
                                    console.log(rawTime, rawDate)

                                    // Format date beautifully for labels (e.g., "15 mai 2026")
                                    const dateObj = new Date(rawDate);
                                    const formattedDate = dateObj.toLocaleDateString('fr-FR', {
                                        day: 'numeric',
                                        month: 'short',
                                        year: 'numeric'
                                    });

                                    // A. Insert Date Section Header if it's a new day group
                                    if (currentGroupDate !== rawDate) {
                                        currentGroupDate = rawDate;
                                        const labelHtml = `
                            <div class="time-label dynamic-timeline-item ">
                                <span class="text-bg-danger small fw-bold">${formattedDate}</span>
                            </div>`;
                                        endClock.insertAdjacentHTML('beforebegin', labelHtml);
                                    }

                                    // B. Parse JSON strings safely
                                    const newData = log.new_data ? JSON.parse(log.new_data) : null;
                                    const oldData = log.old_data ? JSON.parse(log.old_data) : null;

                                    const adminName = (log.prenom || log.nom) ? `${log.prenom} ${log.nom}` : `Utilisateur #${log.changed_by}`;

                                    // C. Run contextual data validation diff engines
                                    let iconClass = "bi bi-info-circle text-bg-secondary";
                                    let actionBody = "";

                                    if ((log.action_type === 'CREATE' || log.action_type === 'MASSECREATE') && newData) {
                                        iconClass = "bi bi-plus-square text-bg-success text-dark";

                                        const start = newData.start ? formatDate(newData.start.substring(0, 16).split('T')[0]) + ' à ' + newData.start.substring(0, 16).split('T')[1] : 'Non définie';

                                        const end = newData.end ? formatDate(newData.end.substring(0, 16).split('T')[0]) + ' à ' + newData.end.substring(0, 16).split('T')[1] : 'Non définie';

                                        actionBody = `
                            <div class="p-2 bg-success-subtle rounded text-success-emphasis border border-success-subtle">
                                <strong>Création du shift :</strong> ${newData.title || 'Sans titre'}<br>
                                <small>Horaires : du ${start} au ${end}</small>
                            </div>`;
                                    } else if (log.action_type === 'UPDATE' && oldData && newData) {
                                        iconClass = "bi bi-pencil-square text-bg-warning";
                                        let changes = [];

                                        // Compare Shift Types / Titles directly
                                        const oldTitle = oldData.shift_type || 'Sans titre';
                                        const newTitle = newData.shift_type || 'Sans titre';
                                        if (oldTitle !== newTitle) {
                                            changes.push(`Type de shift : <del class="text-danger">${oldTitle}</del> &rarr; <span class="text-success fw-bold">${newTitle}</span>`);
                                        }

                                        // Compare Start Time
                                        const oldStart = oldData.start_time ? formatDate(oldData.start_time.substring(0, 16).split(' ')[0]) + ' à ' + oldData.start_time.substring(0, 16).split(' ')[1] : 'Non définie';
                                        const newStart = newData.start_time ? formatDate(newData.start_time.substring(0, 16).split(' ')[0]) + ' à ' + newData.start_time.substring(0, 16).split(' ')[1] : 'Non définie';
                                        if (oldStart !== newStart) {
                                            changes.push(`Début : <del class="text-danger">${oldStart}</del> &rarr; <span class="text-success fw-bold">${newStart}</span>`);
                                        }

                                        // Compare End Time
                                        const oldEnd = oldData.end_time ? formatDate(oldData.end_time.substring(0, 16).split(' ')[0]) + ' à ' + oldData.end_time.substring(0, 16).split(' ')[1] : 'Non définie';
                                        const newEnd = newData.end_time ? formatDate(newData.end_time.substring(0, 16).split(' ')[0]) + ' à ' + newData.end_time.substring(0, 16).split(' ')[1] : 'Non définie';
                                        if (oldEnd !== newEnd) {
                                            changes.push(`Fin : <del class="text-danger">${oldEnd}</del> &rarr; <span class="text-success fw-bold">${newEnd}</span>`);
                                        }

                                        // Render updates if structural differences exist
                                        if (changes.length > 0) {
                                            actionBody = `<ul class="mb-0 ps-3">${changes.map(c => `<li class="my-1">${c}</li>`).join('')}</ul>`;
                                        } else {
                                            actionBody = `<span class="text-muted italic small">Mise à jour globale effectuée sans modification visible des horaires ou du type.</span>`;
                                        }
                                    } else if (log.action_type === 'DELETE' && oldData) {
                                        iconClass = "bi bi-trash-fill text-bg-danger text-dark";

                                        const oldTitle = oldData.shift_type || 'Inconnu';
                                        const oldStart = oldData.start_time ? oldData.start_time.substring(0, 10) : '';

                                        actionBody = `
                                        <div class="p-2 bg-danger-subtle rounded text-danger-emphasis border border-danger-subtle">
                                            Le shift <strong>"${oldTitle}"</strong> du ${oldStart} a été supprimé.
                                        </div>`;
                                    }

                                    // D. Append clean row directly to layout view
                                    const itemHtml = `
                                            <div class="dynamic-timeline-item" style="margin-right: 0px !important;">
                                                <i class="timeline-icon ${iconClass}"></i>
                                                <div class="timeline-item shadow-none border-start-0 bg-light-subtle">
                                                    <span class="time text-muted small">
                                                        <i class="bi bi-clock-fill"></i> ${formatDate(rawDate)+' '+rawTime}
                                                    </span>
                                                    <h3 class="timeline-header font-semibold text-sm m-0" style="font-size:0.875rem;">
                                                    <a href="javascript:void(0);"><strong>${adminName}</strong></a>
                                                    </h3>
                                                    <div class="timeline-body small text-secondary py-2">
                                                        ${actionBody}
                                                    </div>
                                                </div>
                                            </div>`;

                                    endClock.insertAdjacentHTML('beforebegin', itemHtml);
                                });
                            } else {
                                endClock.insertAdjacentHTML('beforebegin', `
                    <div class="text-center text-muted small py-3 dynamic-timeline-item">
                        Aucune modification n'a été enregistrée pour ce planning.
                    </div>
                `);
                            }
                        })
                        .catch(err => {
                            console.error('Timeline error:', err);
                            loader.style.display = 'none';
                        });

                    // 3. Populate Form Core Structure Input Values Safely
                    document.getElementById('offcanvasWithBothOptionsLabel').innerText = "Modifier l'événement";
                    document.getElementById('event_id').value = event.id;

                    const reformat = event?.title?.replace(/\s*\b\d{1,2}:\d{2}\b/g, "").trim().toUpperCase();
                    document.getElementById('event_title').value = reformat;
                    console.log('--', event.title);

                    // Format dates to HH:mm for the time inputs
                    const startTime = event.start.toTimeString().substring(0, 5);
                    const endTime = event.end ? event.end.toTimeString().substring(0, 5) : "";

                    document.getElementById('event_start_time').value = startTime;
                    document.getElementById('event_end_time').value = endTime;

                    // Store raw dates for reference
                    document.getElementById('start_date').value = event.startStr.split('T')[0];
                    document.getElementById('end_date').value = event.endStr ? event.endStr.split('T')[0] : event.startStr.split('T')[0];

                    btnDelete.style.display = 'block';
                    eventOffcanvas.show();
                },
                select: function(info) {
                    const agentId = $('#agentSelect').val();
                    if (!agentId) {
                        Swal.fire('Attention', "Veuillez sélectionner un agent d'abord.", 'info');
                        calendar.unselect();
                        return;
                    }

                    document.getElementById('shiftHistrory').style.display = 'none';
                    document.getElementById('eventForm').reset();
                    document.getElementById('offcanvasWithBothOptionsLabel').innerText = "Nouvel événement";
                    document.getElementById('event_id').value = ""; // Empty ID

                    // Set dates
                    document.getElementById('start_date').value = info.startStr.split('T')[0];
                    document.getElementById('end_date').value = info.endStr.split('T')[0];

                    btnDelete.style.display = 'none'; // Hide delete button
                    eventOffcanvas.show();
                    calendar.unselect();
                },
                datesSet: function(info) {
                    const titleEl = document.querySelector('.fc-toolbar-title');
                    const viewBody = document.querySelector('.fc-timegrid-body');
                    const viewHeader = document.querySelector('.fc-col-header');

                    // 1. Detect View and apply 900px Logic
                    if (info.view.type === 'dayGridMonth') {
                        console.log('dayGridMonth')
                        calendarEl.style.setProperty('--day-height', '300px');
                    } else if (info.view.type === 'timeGridWeek') {
                        console.log('timeGridWeek')
                        calendarEl.style.setProperty('--day-height', '50px');
                    } else if (info.view.type === 'timeGridDay') {
                        console.log('timeGridDay')
                        calendarEl.style.setProperty('--day-height', '50px');
                    } else if (info.view.type === 'listDay') {
                        console.log('listDay')
                        calendarEl.style.removeProperty('--day-height');
                    }


                    // const stuckTooltips = document.querySelectorAll('.tooltip');
                    // stuckTooltips.forEach(t => t.remove());
                },
                // eventMouseEnter: function(info) {
                //     // info.el.setAttribute('title', info.event.title + "Début :" + info.event.startStr + "Fin :" + info.event.endStr);
                //     new bootstrap.Tooltip(info.el, {
                //         title: info.event.title,
                //         placement: 'top',
                //         trigger: 'hover',
                //         container: 'body'
                //     });
                // },
                eventDidMount: function(info) {
                    if (info.view.type == 'listDay') {
                        return
                    }
                    info.el.addEventListener('mouseenter', () => {
                        // 1. Force hide ANY existing tooltips on the page
                        const existingTooltips = document.querySelectorAll('.tooltip');
                        existingTooltips.forEach(t => t.remove());

                        // 2. Initialize the tooltip for the current element
                        const tooltip = new bootstrap.Tooltip(info.el, {
                            title: info.event.title,
                            placement: 'top',
                            trigger: 'manual', // We trigger it manually for total control
                            container: 'body',
                            animation: false // Disabling animation makes it "snappier" and prevents ghosting
                        });

                        tooltip.show();

                        // 3. Hide it when leaving
                        info.el.addEventListener('mouseleave', () => {
                            tooltip.dispose();
                        }, {
                            once: true
                        });
                    });
                },

                //     eventClick: function(info) {
                //         // 1. Remove any existing popovers first to avoid stacking
                //         const oldPopover = bootstrap.Popover.getInstance(info.el);
                //         if (oldPopover) {
                //             oldPopover.dispose();
                //         }

                //         // 2. Initialize and show the popover
                //         const popover = new bootstrap.Popover(info.el, {
                //             title: info.event.title,
                //             placement: 'top',
                //             trigger: 'manual', // We trigger it manually on click
                //             html: true,
                //             container: 'body',
                //             content: `
                //     <div class="p-1">
                //         <strong>Début:</strong> ${info.event.start.toLocaleTimeString('fr-FR', {hour: '2-digit', minute:'2-digit'})}<br>
                //         <strong>Fin:</strong> ${info.event.end ? info.event.end.toLocaleTimeString('fr-FR', {hour: '2-digit', minute:'2-digit'}) : 'N/A'}<br>
                //         <hr class="my-2">
                //         <button class="btn btn-sm btn-primary w-100 mt-1">Modifier</button>
                //     </div>
                // `
                //         });

                //         popover.show();

                //         // 3. Close the popover if user clicks anywhere else
                //         document.addEventListener('click', function(e) {
                //             if (!info.el.contains(e.target)) {
                //                 popover.hide();
                //             }
                //         }, {
                //             once: true
                //         });
                //     },
                events: '../api/getShifts.php'
            });

            calendar.render();
            btnSave.addEventListener('click', function() {
                const id = document.getElementById('event_id').value;
                const agent_id = $('#agentSelect').val();
                let title = document.getElementById('event_title').value;
                title = title?.replace(/\s*\b\d{1,2}:\d{2}\b/g, "").trim().toUpperCase();
                const dateStart = document.getElementById('start_date').value;
                const timeStart = document.getElementById('event_start_time').value;
                const timeEnd = document.getElementById('event_end_time').value;

                if (!title || !timeStart || !timeEnd) {
                    Swal.fire('Erreur', 'Veuillez remplir tous les champs', 'error');
                    return;
                }

                const payload = {
                    id: id,
                    agent_id: agent_id,
                    title: title,
                    start: `${dateStart}T${timeStart}:00`,
                    end: `${dateStart}T${timeEnd}:00`
                };

                const url = id ? '../api/updateShift.php' : '../api/addShift.php';

                fetch(url, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(payload)
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            calendar.refetchEvents(); // Refresh UI
                            eventOffcanvas.hide();
                            Swal.fire('Succès', 'Calendrier mis à jour', 'success');
                        }
                    });
            });

            // 
            document.getElementById('massUploadForm').addEventListener('submit', function(e) {
                e.preventDefault(); // Stop native redirect page reloads

                const fileInput = document.getElementById('csv_file');
                const resultDiv = document.getElementById('uploadResult');
                const btnSubmit = document.getElementById('btnUploadMasse');
                const spliiiit = document.getElementById('spliiiit');

                // Clear previous dynamic response states
                resultDiv.className = 'd-none';
                resultDiv.innerHTML = '';

                // Package file content natively
                const formData = new FormData();
                formData.append('csv_file', fileInput.files[0]);

                // Update Button visually during data transfers
                btnSubmit.disabled = true;
                btnSubmit.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Importation en cours...';

                // Dispatch asynchronous connection request directly to backend endpoint 
                fetch('../api/masseShifts.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        resultDiv.classList.remove('d-none');
                        spliiiit.classList.remove('d-none');
                        if (data.success) {

                            resultDiv.className = 'alert alert-success';
                            let msg = `<strong>Succès !</strong> ${data.data.message}<br>`;
                            msg += `<small>• Enregistrements importés: ${data.data.imported_records_count}</small><br>`;
                            if (data.data.duplicate_records_count > 0) {
                                msg += `<small class="text-warning">• Ignorés (Shift dupliqué): ${data.data.skipped_records_count}</small>`;
                            }
                            if (data.data.skipped_records_count > 0) {
                                msg += `<small class="text-danger">• Ignorés (Logon ID inconnu): ${data.data.skipped_records_count}</small>`;
                            }
                            resultDiv.innerHTML = msg;

                            // Clear input box on genuine uploads
                            fileInput.value = '';
                        } else {
                            spliiiit.classList.remove('d-none');
                            resultDiv.className = 'alert alert-danger';
                            resultDiv.innerHTML = `<strong>Erreur !</strong> ${data.message || 'Une erreur est survenue lors de l\'importation.'}`;
                        }
                    })
                    .catch(error => {
                        spliiiit.classList.remove('d-none');
                        resultDiv.classList.remove('d-none');
                        resultDiv.className = 'alert alert-danger';
                        resultDiv.innerHTML = '<strong>Erreur !</strong> Impossible de contacter le serveur d\'API.';
                    })
                    .finally(() => {
                        // Restore standard button layout statuses
                        btnSubmit.disabled = false;
                        btnSubmit.innerHTML = '<i class="fas fa-upload me-1"></i> Importer les horaires';
                    });
            });
            // 
            // DELETE ACTION
            btnDelete.addEventListener('click', function() {
                const id = document.getElementById('event_id').value;

                Swal.fire({
                    title: 'Êtes-vous sûr ?',
                    text: "Cette action est irréversible !",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: "#1ed760",
                    confirmButtonText: 'Oui, supprimer',
                    cancelButtonColor: "#d33",
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch('../api/deleteShift.php', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json'
                                },
                                body: JSON.stringify({
                                    id: id
                                })
                            })
                            .then(res => res.json())
                            .then(data => {
                                calendar.refetchEvents();
                                eventOffcanvas.hide();
                                Swal.fire('Supprimé !', 'L\'événement a été supprimé.', 'success');
                            });
                    }
                });
            });

            function updateEvent(event) {
                const reformat = event?.title?.replace(/\s*\b\d{1,2}:\d{2}\b/g, "").trim().toUpperCase();
                fetch('../api/updateShift.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        id: event.id,
                        start: event.startStr,
                        end: event.endStr,
                        title: reformat
                    })
                });
            }
            // 2. Handle Window Resize (Height)
            window.addEventListener('resize', () => {
                calendar.setOption('height', getResponsiveHeight());
            });

            // 3. Handle Sidebar Toggle (Width)
            const resizeObserver = new ResizeObserver(() => {
                setTimeout(() => {
                    calendar.updateSize();
                }, 50);
            });

            // Observing the card-body or parent ensures width fills correctly
            resizeObserver.observe(calendarEl.parentElement);
        });

        function openAndResetMasse() {
            // 1. Reset the upload form and wipe old API response alerts
            const uploadForm = document.getElementById('massUploadForm');
            const resultDiv = document.getElementById('uploadResult');
            const spliiiit = document.getElementById('spliiiit');

            if (uploadForm) {
                uploadForm.reset();
            }

            if (resultDiv) {
                resultDiv.className = 'd-none';
                resultDiv.innerHTML = '';
            }

            if (spliiiit) {
                spliiiit.className = 'd-none';
            }

            // 2. Natively look up and display the Bootstrap offcanvas instance
            const offcanvasElement = document.getElementById('offcanvasMasse');
            if (offcanvasElement) {
                // Get existing instance or create a new one safely
                let bsOffcanvas = bootstrap.Offcanvas.getInstance(offcanvasElement);
                if (!bsOffcanvas) {
                    bsOffcanvas = new bootstrap.Offcanvas(offcanvasElement);
                }
                // Open the panel
                bsOffcanvas.show();
            }
        }
    </script>

    <script src="./assets/js/Sortable.min.js"></script>
    <script src="./assets/js/apexcharts.min.js"></script>
    <script src="./assets/js/jsvectormap.min.js"></script>
    <script src="./assets/js/world.js"></script>

</body>

</html>
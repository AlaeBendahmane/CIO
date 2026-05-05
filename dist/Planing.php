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
            return window.innerHeight - 187;
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
                            const newOption = new Option(fullName, agent.id, false, false);

                            $select.append(newOption);
                        });

                        // 4. Refresh Select2 UI
                        $select.trigger('change');
                    })
                .catch(err => console.error('Erreur lors du chargement des agents:', err));

            // Add this inside your DOMContentLoaded, after calendar.render();

            $('#agentSelect').on('change', function() {
                const selectedAgentId = $(this).val();

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


            const calendarEl = document.getElementById('calendar');
            if (!calendarEl) {
                return
            }
            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'fr',
                noEventsContent: "Aucun événement à afficher",
                firstDay: 1,
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
    </script>

    <script src="./assets/js/Sortable.min.js"></script>
    <script src="./assets/js/apexcharts.min.js"></script>
    <script src="./assets/js/jsvectormap.min.js"></script>
    <script src="./assets/js/world.js"></script>

</body>

</html>
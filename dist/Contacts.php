<!doctype html>
<html lang="fr">
<?php
ob_start();
include '../api/helpers.php';
include '../api/session_info.php';
isAuthPath();
// isAdminPath();

// include_once '../api/get_selects.php';
ob_end_flush();
?>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>CIO | Contacts</title>

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

        /* Communication Buttons - Brand Colors */

        /* Teams: Royal Purple */
        .btn-teams {
            background-color: #6264A7;
            border-color: #6264A7;
            color: white !important;
        }

        .btn-teams:hover {
            background-color: #4B4D8A;
            border-color: #4B4D8A;
        }

        /* WhatsApp: Teal Green */
        .btn-whatsapp {
            background-color: #25D366;
            border-color: #25D366;
            color: white !important;
        }

        .btn-whatsapp:hover {
            background-color: #128C7E;
            border-color: #128C7E;
        }

        /* Gmail: Google Red */
        .btn-gmail {
            background-color: #EA4335;
            border-color: #EA4335;
            color: white !important;
        }

        .btn-gmail:hover {
            background-color: #C5221F;
            border-color: #C5221F;
        }

        /* Outlook: Microsoft Blue */
        .btn-outlook {
            background-color: #0078D4;
            border-color: #0078D4;
            color: white !important;
        }

        .btn-outlook:hover {
            background-color: #005A9E;
            border-color: #005A9E;
        }

        /* General button styling for smooth transitions */
        .btn-sm {
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            /* Space between icon and text */
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
                $currentPage = 'contacts';
                include './components/profileNav.php';
                ?>
            </div>
        </nav>
        <?php
        $userRole = $role;
        $currentPage = 'contacts';
        include './components/sidebar.php';
        ?>
        <main class="app-main">
            <div class="app-content-header">
                <div class="container-fluid">
                </div>
            </div>
            <div class="app-content">
                <div class="container-fluid">

                    <div class="row" id="containerContacts">
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
    <script src="./assets/js/Sortable.min.js"></script>
    <script src="./assets/js/apexcharts.min.js"></script>
    <script src="./assets/js/jsvectormap.min.js"></script>
    <script src="./assets/js/world.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.getElementById('containerContacts');

            fetch('../api/get_contacts.php')
                .then(res => res.json())
                .then(response => {
                    const data = response.data;
                    // Since your API returns the array directly:
                    if (Array.isArray(data) && data.length > 0) {
                        container.innerHTML = ''; // Clear static placeholder

                        data.forEach(contact => {
                            const fullName = `${contact.nom} ${contact.prenom}`;
                            const email = contact.email || '';
                            const imgSrc = (contact.profilePic && contact.profilePic.trim() !== '') ?
                                contact.profilePic :
                                `https://ui-avatars.com/api/?name=${encodeURIComponent(contact.prenom)}+${encodeURIComponent(contact.nom)}&background=random&color=fff`;
                            const cardHtml = `
                        <div class="col-12 col-sm-6 col-md-4 d-flex align-items-stretch flex-column mb-3">
                            <div class="card bg-light d-flex flex-fill shadow-sm">
                                <div class="card-header text-muted border-bottom-0">
                                    <!--<span class="badge badge-info">${contact.campagne || 'N/A'}</span> 
                                    <small class="float-right">${contact.role}</small>-->
                                </div>
                                <div class="card-body pt-0">
                                    <div class="row">
                                        <div class="col-7">
                                            <h2 class="lead"><b>${fullName}</b></h2>
                                            <p class="text-muted text-sm mb-1"><b>STE: </b> ${contact.ste || '---'} </p>
                                              <p class="text-muted text-sm mb-1"><b>Role: </b> ${contact.role || '---'} </p>
                                            <p class="text-muted text-sm" style="display: flex;"><b>Email: </b>  ${'&nbsp;'+email} </p>                                                                                 
                                        </div>
                                        <div class="col-5 text-center">
                                            <img src="${imgSrc}" 
                                                 style="border-radius: 50%; width: 90px; height: 90px; object-fit: cover; border: 2px solid #ddd;">
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer" style="background: rgba(0,0,0,0.03);">
                                    <div class="text-center">
                                        <a href="https://teams.microsoft.com/l/chat/0/0?users=${email}" target="_blank" class="btn btn-xs btn-outline-primary m-1">
                                            <i class="bi bi-microsoft-teams"></i> Teams
                                        </a>
                                        <a href="mailto:${email}" class="btn btn-xs btn-outline-secondary m-1">
                                            <i class="bi bi-envelope"></i> Outlook
                                        </a>
                                        <a href="https://mail.google.com/mail/?view=cm&fs=1&to=${email}" target="_blank" class="btn btn-xs btn-outline-danger m-1">
                                            <i class="bi bi-google"></i> Gmail
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>`;

                            container.insertAdjacentHTML('beforeend', cardHtml);
                        });
                    } else {
                        container.innerHTML = '<div class="col-12 text-center p-5"><h5>Aucun contact trouvé.</h5></div>';
                    }
                })
                .catch(err => {
                    console.error("Error loading contacts:", err);
                    container.innerHTML = '<div class="col-12 text-center text-danger p-5">Erreur de connexion au serveur.</div>';
                });
        });
    </script>
</body>

</html>
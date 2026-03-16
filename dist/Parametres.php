<!doctype html>
<html lang="fr">
<?php
ob_start();
include '../api/helpers.php';
include '../api/session_info.php';
isAuthPath();
isAdminPath();
include_once '../api/get_selects.php';
ob_end_flush();
?>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>CIO | Paramètres</title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes" />
    <meta name="color-scheme" content="light dark" />
    <meta name="theme-color" content="#007bff" media="(prefers-color-scheme: light)" />
    <meta name="theme-color" content="#1a1a1a" media="(prefers-color-scheme: dark)" />

    <meta name="title" content="CIO v4 | Paramètres" />
    <meta name="author" content="ColorlibHQ" />

    <meta name="keywords"
        content="bootstrap 5, bootstrap, bootstrap 5 admin dashboard, bootstrap 5 dashboard, bootstrap 5 charts, bootstrap 5 calendar, bootstrap 5 datepicker, bootstrap 5 tables, bootstrap 5 datatable, vanilla js datatable, colorlibhq, colorlibhq dashboard, colorlibhq admin dashboard, accessible admin panel, WCAG compliant" />

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
    include './components/splashscreen.php';
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
                $currentPage = 'parametres';
                include './components/profileNav.php';
                ?>
            </div>
        </nav>
        <?php
        $userRole = $role;
        $currentPage = 'parametres';
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
                        <div class="col-md-3">
                            <div class="card card-primary card-outline shadow-none">
                                <div class="card-header">
                                    <h3 class="card-title">Paramètres</h3>
                                </div>
                                <div class="card-body" style="padding: 0px !important;">
                                    <div class="" style="padding: 0px !important;">
                                        <ul class="nav flex-column" id="custom-tabs">
                                            <li class="nav-item">
                                                <a class="nav-link active js-tab-link text-black showing" href="#panel-password">Mot de passe</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link js-tab-link text-black" href="#panel-ste">Sociétés</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link js-tab-link text-black" href="#panel-campagne">Compagnes</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-9">
                            <div class="card card-primary card-outline shadow-sm">
                                <div class="card-header">
                                    <h3 class="card-title">Contenu</h3>
                                </div>
                                <div class="card-body" style="padding: 0px !important;">
                                    <div class="direct-chat-messages" style="height: 500px !important;">
                                        <div class="tab-content">
                                            <div class="tab-pane fade show active" id="panel-password">
                                                <h4>Configuration Mot de passe</h4>
                                                <p>Contenu 1...</p>
                                            </div>
                                            <div class="tab-pane fade" id="panel-ste">

                                                <div class="d-flex justify-content-between align-items-center mb-3">
                                                    <h4 class="mb-0">Configuration Société</h4>
                                                    <button class="btn btn-sm btn-success" onclick="addCampagne()">
                                                        <i class="fas fa-plus"></i> Nouveau
                                                    </button>
                                                </div>
                                                <ul class="list-group list-group-unbordered">
                                                    <?php if (empty($steList)): ?>
                                                        <li class="list-group-item text-center text-muted">
                                                            Aucune Société trouvée.
                                                        </li>
                                                    <?php else: ?>
                                                    <?php endif; ?>
                                                    <?php foreach ($steList as $ste): ?>
                                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                                            <span style="display: flex;">
                                                                <i class="bi bi-collection text-muted" style="margin-right:5px ;"></i>
                                                                <strong style="display: flex;justify-content: center;align-items: center;flex-direction: row;gap: 5px;">
                                                                    <?= htmlspecialchars($ste['nomSte']) ?>
                                                                    <i class="bi bi-arrow-right-circle"></i>
                                                                    <?= htmlspecialchars($ste['abreviation']) ?>
                                                                </strong>
                                                            </span>
                                                            <div class="btn-group">
                                                                <button type="button" class="btn btn-default btn-sm"
                                                                    onclick="editSte('<?= $ste['abreviation'] ?>')" title="Modifier">
                                                                    <i class="bi bi-pencil-square text-primary"></i>
                                                                </button>
                                                                <button type="button" class="btn btn-default btn-sm"
                                                                    onclick="deleteSte('<?= $ste['abreviation'] ?>')" title="Supprimer">
                                                                    <i class="bi bi-trash text-danger"></i>
                                                                </button>
                                                            </div>
                                                        </li>
                                                    <?php endforeach; ?>
                                                </ul>
                                            </div>

                                            <div class="tab-pane fade" id="panel-campagne">
                                                <div class="d-flex justify-content-between align-items-center mb-3">
                                                    <h4 class="mb-0">Configuration Campagne</h4>
                                                    <button class="btn btn-sm btn-success" onclick="addCampagne()">
                                                        <i class="fas fa-plus"></i> Nouveau
                                                    </button>
                                                </div>
                                                <ul class="list-group list-group-unbordered">
                                                    <?php if (empty($compagneList)): ?>
                                                        <li class="list-group-item text-center text-muted">
                                                            Aucune campagne trouvée.
                                                        </li>
                                                    <?php else: ?>
                                                    <?php endif; ?>
                                                    <?php foreach ($compagneList as $compagne): ?>
                                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                                            <span>
                                                                <i class="bi bi-collection text-muted" style="margin-right:5px ;"></i>
                                                                <strong><?= htmlspecialchars($compagne['nomCompagne']) ?></strong>
                                                            </span>
                                                            <div class="btn-group">
                                                                <button type="button" class="btn btn-default btn-sm"
                                                                    onclick="editCampagne('<?= $compagne['abreviation'] ?>')" title="Modifier">
                                                                    <i class="bi bi-pencil-square text-primary"></i>
                                                                </button>
                                                                <button type="button" class="btn btn-default btn-sm"
                                                                    onclick="deleteCampagne('<?= $compagne['abreviation'] ?>')" title="Supprimer">
                                                                    <i class="bi bi-trash text-danger"></i>
                                                                </button>
                                                            </div>
                                                        </li>
                                                    <?php endforeach; ?>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
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
        $(document).ready(function() {
            $('.js-tab-link').on('click', function(e) {
                e.preventDefault(); // Stop page from jumping

                // 1. Handle the Link UI (Blue/Black)
                $('.js-tab-link').removeClass('active showing');
                $(this).addClass('active showing');

                // 2. Handle the Content Panels
                const target = $(this).attr('href'); // Get #panel-xxx

                // Hide all panes
                $('.tab-pane').removeClass('show active');

                // Show the clicked one
                $(target).addClass('show active');

            });
        });

        function deleteCampagne(id) {
            Swal.fire({
                title: "Êtes-vous sûr ?",
                text: "Cette campagne sera définitivement supprimée.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#dc3545",
                cancelButtonColor: "#6c757d",
                confirmButtonText: "Oui, supprimer",
                cancelButtonText: "Annuler",
                backdrop: true
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: "Supprimé !",
                        icon: "success",
                        timer: 1500,
                        showConfirmButton: false,
                        backdrop: false
                    });
                }
            });
        }
    </script>
    <script src="./assets/js/Sortable.min.js"></script>
    <script src="./assets/js/apexcharts.min.js"></script>
    <script src="./assets/js/jsvectormap.min.js"></script>
    <script src="./assets/js/world.js"></script>

</body>

</html>
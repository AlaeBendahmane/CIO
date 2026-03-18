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
                                                <div class="d-flex justify-content-between align-items-center mb-3">
                                                    <h4 class="mb-0">Configuration Mot de passe</h4>
                                                </div>
                                                <button class="btn btn-outline-secondary btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#collapseRulesPassword" aria-expanded="false">
                                                    Configuration des règles de sécurité ↓
                                                </button>
                                                <div class="collapse mt-2" id="collapseRulesPassword" data-bs-parent="#panel-password">
                                                    <p class="text-muted" style="margin-bottom: 7px;">Sélectionnez les règles de sécurité à appliquer :</p>
                                                    <div class="mb-3">
                                                        <div class="form-check">
                                                            <input class="form-check-input pw-rule" type="checkbox" id="rule-min-length" checked>
                                                            <label class="form-check-label" for="rule-min-length">Minimum 8 caractères</label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input pw-rule" type="checkbox" id="rule-upper">
                                                            <label class="form-check-label" for="rule-upper">Au moins une majuscule (A-Z)</label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input pw-rule" type="checkbox" id="rule-lower">
                                                            <label class="form-check-label" for="rule-lower">Au moins une minuscule (a-z)</label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input pw-rule" type="checkbox" id="rule-number">
                                                            <label class="form-check-label" for="rule-number">Au moins un chiffre (0-9)</label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input pw-rule" type="checkbox" id="rule-special">
                                                            <label class="form-check-label" for="rule-special">Au moins un caractère spécial (@$!%*?&)</label>
                                                        </div>
                                                    </div>

                                                    <div class="mt-2">
                                                        <label for="test-pw" class="form-label">Tester un mot de passe :</label>
                                                        <input type="text" class="form-control" id="test-pw" placeholder="Saisissez un texte pour tester...">
                                                        <div id="test-result" class="mt-2 fw-bold"></div>
                                                    </div>
                                                    <div style="display: flex;justify-content: end;">
                                                        <button class="btn btn-sm btn-success" onclick="saveLogic()">
                                                            <i class="fas fa-plus"></i> Sauvegarder
                                                        </button>
                                                    </div>
                                                </div>

                                                <hr style="border: none; border-top: 1px solid black;margin: 8px;">

                                                <button class="btn btn-outline-secondary btn-sm mb-2" type="button" data-bs-toggle="collapse" data-bs-target="#collapseDefaultPassword" aria-expanded="false">
                                                    Configuration de réinitialisation ↓
                                                </button>

                                                <div class="collapse" id="collapseDefaultPassword" data-bs-parent="#panel-password">
                                                    <div class="">
                                                        <p class="text-muted" style="margin-bottom: 7px;">Mot de passe par défaut :</p>
                                                        <input type="text" class="form-control mb-2" id="default-pw-input" placeholder="Ex: ChangeMe123">
                                                        <p class="text-muted small mb-0">
                                                            Ce mot de passe sera attribué automatiquement lors d'une réinitialisation. Assurez-vous qu'il respecte les règles de sécurité configurées ci-dessus. </p>
                                                    </div>
                                                    <div style="display: flex;justify-content: end;">
                                                        <button class="btn btn-sm btn-success" onclick="saveDefPW()">
                                                            <i class="fas fa-plus"></i> Sauvegarder
                                                        </button>
                                                    </div>
                                                </div>

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
    <script>
        let currentGeneratedRegex = "";
        document.addEventListener('DOMContentLoaded', async function() {
            const rules = document.querySelectorAll('.pw-rule');
            const testInput = document.getElementById('test-pw');
            const testResult = document.getElementById('test-result');
            // 1. Function to fetch from DB AND sync the checkboxes
            async function initPasswordConfig() {
                try {
                    const formData = new FormData();
                    formData.append('key', 'PasswordRegex');
                    const response = await fetch('../api/getParams.php', {
                        method: 'POST',
                        body: formData
                    });

                    const data = await response.json();

                    if (data.success && data.message) {
                        const dbRegex = data.message;
                        currentGeneratedRegex = dbRegex.slice(1, -1)
                        // Show the DB regex in the <code> block

                        // --- SYNC CHECKBOXES ---
                        // We check if specific patterns exist in the string
                        document.getElementById('rule-upper').checked = dbRegex.includes('A-Z');
                        document.getElementById('rule-lower').checked = dbRegex.includes('a-z');
                        document.getElementById('rule-number').checked = dbRegex.includes('\\d');
                        document.getElementById('rule-special').checked = dbRegex.includes('@$!%*?&');
                        document.getElementById('rule-min-length').checked = dbRegex.includes('{8,');
                    }
                } catch (error) {
                    console.error("Fetch failed:", error);
                }
            }

            // 2. Function to build regex from checkboxes
            function updateRegex() {
                let regexParts = "^";

                if (document.getElementById('rule-upper').checked) regexParts += "(?=.*[A-Z])";
                if (document.getElementById('rule-lower').checked) regexParts += "(?=.*[a-z])";
                if (document.getElementById('rule-number').checked) regexParts += "(?=.*\\d)";
                if (document.getElementById('rule-special').checked) regexParts += "(?=.*[@$!%*?&])";

                const minLength = document.getElementById('rule-min-length').checked ? "8" : "1";
                regexParts += `.{${minLength},}$`;

                // Update the display with slashes
                // const finalRegexStr = "/" + regexParts + "/";
                // regexDisplay.textContent = finalRegexStr;
                currentGeneratedRegex = regexParts;
                validateTest(new RegExp(regexParts));
            }

            // 3. Validation Logic
            function validateTest(regex) {
                const val = testInput.value;
                if (val === "") {
                    testResult.textContent = "";
                    testInput.classList.remove('is-valid', 'is-invalid');
                    return;
                }

                if (regex.test(val)) {
                    testResult.textContent = "✅ Mot de passe valide !";
                    testResult.style.color = "green";
                    testInput.classList.replace('is-invalid', 'is-valid') || testInput.classList.add('is-valid');
                } else {
                    testResult.textContent = "❌ Ne correspond pas aux critères.";
                    testResult.style.color = "red";
                    testInput.classList.replace('is-valid', 'is-invalid') || testInput.classList.add('is-invalid');
                }
            }

            // --- EVENT LISTENERS ---
            rules.forEach(checkbox => checkbox.addEventListener('change', updateRegex));

            testInput.addEventListener('input', () => {
                // Safe regex extraction: remove starting/ending slashes
                // const currentRegexStr = regexDisplay.textContent.replace(/^\/|\/$/g, '');
                validateTest(new RegExp(currentGeneratedRegex));
            });

            // --- EXECUTION ---
            await initPasswordConfig();

        });

        function saveDefPW() {
            const inputVal = document.getElementById('default-pw-input').value;
            console.log('object', inputVal)
        }

        function saveLogic() {
            if (!currentGeneratedRegex) {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Veuillez d'abord configurer le mot de passe.",
                });
                return;
            }
            var mine = "/" + currentGeneratedRegex + "/";
            $.ajax({
                url: '../api/setParams.php',
                method: 'POST',
                data: {
                    key: 'PasswordRegex',
                    value: mine
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: "Configuration enregistrée!",
                            showConfirmButton: false,
                            timer: 3000
                        });
                    }
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
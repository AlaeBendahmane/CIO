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
    <title>CIO | Paramètres</title>

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
                        <div class="col-md-3 mb-3">
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
                                                <a class="nav-link js-tab-link text-black" href="#panel-campagne">Campagnes</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link js-tab-link text-black" href="#panel-shift">Planing</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link js-tab-link text-black" href="#panel-DB">Base de données</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-9 mb-3">
                            <div class="card card-primary card-outline shadow-sm">
                                <div class="card-header">
                                    <h3 class="card-title">Contenu</h3>
                                </div>
                                <div class="card-body" style="padding: 0px !important;">
                                    <div class="direct-chat-messages" style="height: 500px !important;padding-top:0px !important ;">
                                        <div class="tab-content">
                                            <div class="tab-pane fade show active" id="panel-password">
                                                <div class="d-flex justify-content-between align-items-center mb-3 sticky-top" style="top: 0; z-index: 1020;background-color: white;padding-top: 15px !important;padding-bottom: 15px;margin-bottom: 0px !important">
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
                                                        <input type="text" class="form-control mb-2" id="default-pw-input" placeholder="Mot de passe">
                                                        <p class="text-muted small mb-0">
                                                            Ce mot de passe sera attribué automatiquement lors d'une réinitialisation. Assurez-vous qu'il respecte les règles de sécurité configurées ci-dessus. </p>
                                                    </div>
                                                    <div id="password-requirements" class="mt-2 p-3 border rounded bg-light" style="font-size: 0.85rem; display:none;">
                                                        <div id="dynamic-list"></div>
                                                    </div>
                                                    <div style="display: flex;justify-content: end;">
                                                        <button class="btn btn-sm btn-success mt-1" onclick="saveDefPW()" id="btnChange" disabled>
                                                            <i class="fas fa-plus"></i> Sauvegarder
                                                        </button>
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="tab-pane fade" id="panel-ste">

                                                <div class="d-flex justify-content-between align-items-center mb-3 sticky-top" style="top: 0; z-index: 1020;background-color: white;padding-top: 15px !important;padding-bottom: 15px;margin-bottom: 0px !important">
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
                                                <div class="d-flex justify-content-between align-items-center mb-3 sticky-top" style="top: 0; z-index: 1020;background-color: white;padding-top: 15px !important;padding-bottom: 15px;margin-bottom: 0px !important">
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

                                            <!--  -->
                                            <div class="tab-pane fade" id="panel-DB">
                                                <div class="d-flex justify-content-between align-items-center mb-3 sticky-top" style="top: 0; z-index: 1020;background-color: white;padding-top: 15px !important;padding-bottom: 15px;margin-bottom: 0px !important">
                                                    <h4 class="mb-0">Configuration de la base de données</h4>
                                                </div>
                                                <ul class="list-group list-group-unbordered">
                                                    <?php if (empty($tables)): ?>
                                                        <li class="list-group-item text-center text-muted">
                                                            Aucune Table trouvée.
                                                        </li>
                                                    <?php else: ?>
                                                    <?php endif; ?>
                                                    <?php foreach ($tables as $table):
                                                        // Calcul du pourcentage d'occupation par rapport à la taille totale ($size)
                                                        $percentage = ($size > 0) ? ($table['Taille_MB'] / $size) * 100 : 0;
                                                        // Déterminer la couleur du badge de taille
                                                        $badgeClass = ($percentage > 70) ? 'bg-danger' : (($percentage > 40) ? 'bg-warning' : 'bg-primary');
                                                    ?>
                                                        <li class="list-group-item d-flex justify-content-between align-items-center py-3">
                                                            <div class="d-flex align-items-center flex-grow-1">
                                                                <div class="icon-box me-3 text-muted">
                                                                    <i class="bi bi-database-fill-gear fs-4"></i>
                                                                </div>

                                                                <div class="flex-grow-1">
                                                                    <div class="d-flex align-items-center">
                                                                        <h6 class="mb-0 me-1"><?= reformatNameTables($table['Table']) ?></h6>
                                                                        <small class="text-muted">(<?= htmlspecialchars($table['Table']) ?>)</small>
                                                                    </div>

                                                                    <div class="progress mt-2" style="height: 4px; width: 200px;">
                                                                        <div class="progress-bar <?= $badgeClass ?>" style="width: <?= $percentage ?>%"></div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="d-flex align-items-center gap-4">
                                                                <div class="text-end d-none d-md-block">
                                                                    <div class="fw-bold"><?= number_format($table['Lignes'], 0, '.', ' ') ?></div>
                                                                    <small class="text-muted text-uppercase" style="font-size: 0.65rem;">Enregistrements</small>
                                                                </div>

                                                                <div class="text-end me-3">
                                                                    <span class="badge <?= $badgeClass ?> rounded-pill">
                                                                        <?= htmlspecialchars($table['Taille_MB']) ?> MB
                                                                    </span>
                                                                </div>

                                                                <button type="button" class="btn btn-outline-danger btn-sm border-0"
                                                                    onclick="clearTable('<?= reformatNameTables($table['Table']) ?>', '<?= $table['Table'] ?>')"
                                                                    title="Vider la table">
                                                                    <i class="bi bi-eraser-fill"></i>
                                                                </button>
                                                            </div>
                                                        </li>
                                                    <?php endforeach; ?>
                                                </ul>

                                            </div>

                                            <!--  -->
                                            <div class="tab-pane fade" id="panel-shift">
                                                <div class="d-flex justify-content-between align-items-center mb-3 sticky-top" style="top: 0; z-index: 1020;background-color: white;padding-top: 15px !important;padding-bottom: 15px;margin-bottom: 0px !important">
                                                    <h4 class="mb-0">Configuration du planning</h4>
                                                    <button class="btn btn-sm btn-success" id="btnAddType" onclick="addType()">
                                                        <i class="fas fa-plus"></i> Nouveau
                                                    </button>
                                                </div>
                                                <div id="shift-config-container" class="row g-3">
                                                </div>
                                            </div>
                                            <!--  -->
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
                confirmButtonColor: "#1ed760",
                cancelButtonColor: "#d33",
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
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: "Une erreur s'est produite.",
                    });
                }
            });
        }
    </script>
    <script>
        let currentGeneratedRegex = "";
        let activeRules = [];
        let passwordPattern = /./;

        document.addEventListener('DOMContentLoaded', async function() {
            const rules = document.querySelectorAll('.pw-rule');
            const testInput = document.getElementById('test-pw');
            const testResult = document.getElementById('test-result');
            const defaultPwInput = document.getElementById('default-pw-input');
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

                    if (data.success && data.data) {
                        const dbRegex = data.data;
                        currentGeneratedRegex = dbRegex.replace(/^\/|\/$/g, ''); //dbRegex.slice(1, -1)

                        // --- SYNC CHECKBOXES ---
                        // We check if specific patterns exist in the string
                        document.getElementById('rule-upper').checked = dbRegex.includes('A-Z');
                        document.getElementById('rule-lower').checked = dbRegex.includes('a-z');
                        document.getElementById('rule-number').checked = dbRegex.includes('\\d');
                        document.getElementById('rule-special').checked = dbRegex.includes('@$!%*?&');
                        document.getElementById('rule-min-length').checked = dbRegex.includes('{8,');
                    }

                    // Forcer la mise à jour globale après le fetch
                    updateRegex();
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
                passwordPattern = new RegExp(regexParts); // Crucial : on met à jour l'objet RegExp ici

                // On rafraîchit l'UI des petites icônes (check/cross)
                buildUI(currentGeneratedRegex);

                // Si le champ test ou le champ mot de passe contient déjà du texte, on re-valide
                validateTest();
                validateFinal();
            }

            // --- VALIDATION VISUELLE (Petites listes) ---
            function validateFinal() {
                const pw = defaultPwInput.value;
                activeRules.forEach(rule => {
                    const el = document.getElementById(rule.id);
                    if (el) {
                        const isValid = rule.test(pw);
                        el.className = isValid ? 'text-success fw-bold' : 'text-danger';
                        el.innerHTML = (isValid ? '✔ ' : '✖ ') + rule.label;
                    }
                });

                const isValidGlobal = passwordPattern.test(pw);
                const btnChange = document.getElementById("btnChange");
                if (btnChange) btnChange.disabled = !isValidGlobal;
            }

            // --- VALIDATION TEST (Input secondaire) ---
            function validateTest() {
                const val = testInput.value;
                if (val === "") {
                    testResult.textContent = "";
                    testInput.classList.remove('is-valid', 'is-invalid');
                    return;
                }

                if (passwordPattern.test(val)) {
                    testResult.textContent = "✅ Mot de passe valide !";
                    testResult.style.color = "green";
                    testInput.classList.remove('is-invalid');
                    testInput.classList.add('is-valid');
                } else {
                    testResult.textContent = "❌ Ne correspond pas aux critères.";
                    testResult.style.color = "red";
                    testInput.classList.remove('is-valid');
                    testInput.classList.add('is-invalid');
                }
            }

            // --- EVENT LISTENERS ---
            rules.forEach(checkbox => checkbox.addEventListener('change', updateRegex));

            testInput.addEventListener('input', validateTest);
            defaultPwInput.addEventListener('input', validateFinal);

            // --- EXECUTION ---
            await initPasswordConfig();

        });

        // --- FONCTIONS EXTERNES (Globales) ---
        function buildUI(cleanRegex) {
            const listContainer = document.getElementById('dynamic-list');
            if (!listContainer) return;

            listContainer.innerHTML = "";
            activeRules = [];

            const ruleMap = [{
                    regex: /[A-Z]/,
                    id: 'req-upper',
                    label: 'Une majuscule (A-Z)'
                },
                {
                    regex: /[a-z]/,
                    id: 'req-lower',
                    label: 'Une minuscule (a-z)'
                },
                {
                    regex: /\d/,
                    id: 'req-number',
                    label: 'Un chiffre (0-9)'
                },
                {
                    regex: /[@$!%*?&]/,
                    id: 'req-special',
                    label: 'Un caractère spécial (@$!%*?&)'
                }
            ];

            let lengthMatch = cleanRegex.match(/\{(\d+),/);
            let minLength = lengthMatch ? lengthMatch[1] : 1;

            activeRules.push({
                id: 'req-length',
                label: `Au moins ${minLength} caractères`,
                test: (v) => v.length >= minLength
            });

            ruleMap.forEach(rule => {
                if (cleanRegex.includes(rule.regex.source)) {
                    activeRules.push({
                        id: rule.id,
                        label: rule.label,
                        test: (v) => rule.regex.test(v)
                    });
                }
            });

            activeRules.forEach(rule => {
                listContainer.innerHTML += `<div id="${rule.id}" class="text-danger">✖ ${rule.label}</div>`;
            });
            document.getElementById('password-requirements').style.display = 'block';
        }

        function saveDefPW() {
            const inputVal = document.getElementById('default-pw-input').value;
            if (inputVal.trim() === "") {
                Swal.fire({
                    icon: "info",
                    title: "Oops...",
                    text: "Veuillez saisir un mot de passe.",
                });
                return;
            }
            $.ajax({
                url: '../api/setParams.php',
                method: 'POST',
                data: {
                    key: 'DefPassword',
                    value: inputVal,
                    crypt: 1
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
                    } else {
                        Swal.fire({
                            icon: "error",
                            title: "Oops...",
                            text: "Une erreur s'est produite.",
                        });
                    }
                }
            });
        }
        // 
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
                        buildUI(currentGeneratedRegex);
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: "Configuration enregistrée!",
                            showConfirmButton: false,
                            timer: 3000
                        });
                    } else {
                        Swal.fire({
                            icon: "error",
                            title: "Oops...",
                            text: "Une erreur s'est produite.",
                        });
                    }
                }
            });
        }

        function clearTable(namepurify, table) {
            Swal.fire({
                title: `Êtes-vous sûr de vouloir vider la table : ${namepurify +' ('+table+')'} ?`,
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#1ed760",
                cancelButtonColor: "#d33",
                confirmButtonText: "Oui, supprimer",
                cancelButtonText: "Annuler"
            }).then(async (resultconfirm) => {
                //truly clear
            })
        }
    </script>

    <script>
        $(document).ready(function() {
            const hash = window.location.hash;

            if (hash) {
                // 2. On cherche le lien qui a cet "href" spécifique
                const $targetLink = $(`.js-tab-link[href="${hash}"]`);

                if ($targetLink.length > 0) {
                    // 3. On déclenche simplement un "click" sur l'élément
                    // Cela va exécuter tout votre code (active showing, show active, etc.)
                    $targetLink.trigger('click');

                    // 4. NETTOYAGE : On retire le hash de l'URL pour que ce soit propre
                    window.history.replaceState(null, null, window.location.pathname);
                }
            }
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', async function() {
            // Initial fetch of shift configurations
            await getColorsPlaning();
        });

        /**
         * Fetch and Render Existing Shifts
         */
        async function getColorsPlaning() {
            const container = document.getElementById('shift-config-container');
            if (!container) return;

            try {
                const formData = new FormData();
                formData.append('key', 'shiftsColors');

                const response = await fetch('../api/getParams.php', {
                    method: 'POST',
                    body: formData
                });

                const data = await response.json();

                if (data.success && data.data) {
                    const shifts = JSON.parse(data.data);
                    container.innerHTML = ''; // Clear spinner

                    Object.entries(shifts).forEach(([key, values]) => {
                        const shiftHtml = renderShiftCard(key, values);
                        container.insertAdjacentHTML('beforeend', shiftHtml);
                    });
                }
            } catch (error) {
                console.error("Fetch failed:", error);
                container.innerHTML = `<div class="alert alert-danger">Erreur lors du chargement des données.</div>`;
            }
        }

        /**
         * Card Template Generator
         */
        function renderShiftCard(key, values) {
            return `
        <div class="col-md-6 col-lg-5 col-xl-4 mb-3 shift-card" data-shift-key="${key}">
            <div class="card shadow-sm border-1">
                <div class="card-body" style="padding:10px">
                    <div class="d-flex align-items-center p-3 rounded" 
                        id="preview-${key}"
                        style="background-color: ${values.color}; color: ${values.textColor}; border: 1px solid #dee2e6; overflow: hidden;">
                        
                        <div class="me-auto" style="min-width: 0;">
                            <h6 class="small fw-bold mb-0 editable-label text-truncate" 
                                title="${key}" 
                                style="color: ${values.textColor} !important; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; display: block;"
                                data-key="${key}">${key}</h6>
                        </div>

                        <i class="bi bi-brush btn-edit-shift ms-2" 
                           style="cursor:pointer; flex-shrink: 0;" 
                           onclick="enableEditing(this, '${key}')"></i>
                    </div>

                    <div class="mt-2 row g-2 edit-controls d-none" style="justify-content: center; gap:8px">
                        <div class="col-5">
                            <label class="small text-muted d-block">Fond</label>
                            <div class="d-flex align-items-center position-relative">
                                <div class="rounded-circle me-2 border shadow-sm color-trigger" 
                                    style="width:18px; height:18px; background:${values.color}; cursor:pointer; z-index: 2;"
                                    onclick="this.nextElementSibling.click()"></div>
                                <input type="color" 
                                    style="opacity: 0; position: absolute; left: 0; width: 18px; height: 18px; pointer-events: none;" 
                                    value="${values.color}" 
                                    oninput="updateLivePreview(this, 'bg', '${key}')">
                                <code class="small text-muted hex-label">${values.color}</code>
                            </div>
                        </div>
                        <div class="col-5">
                            <label class="small text-muted d-block">Texte</label>
                            <div class="d-flex align-items-center position-relative">
                                <div class="rounded-circle me-2 border shadow-sm color-trigger" 
                                    style="width:18px; height:18px; background:${values.textColor}; cursor:pointer; z-index: 2;"
                                    onclick="this.nextElementSibling.click()"></div>
                                <input type="color" 
                                    style="opacity: 0; position: absolute; left: 0; width: 18px; height: 18px; pointer-events: none;" 
                                    value="${values.textColor}" 
                                    oninput="updateLivePreview(this, 'text', '${key}')">
                                <code class="small text-muted hex-label">${values.textColor}</code>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>`;
        }

        /**
         * Handle UI Transitions (Edit mode vs View mode)
         */
        function enableEditing(iconElement, key) {
            const currentCard = iconElement.closest('.shift-card');
            const label = currentCard.querySelector('.editable-label');
            const controls = currentCard.querySelector('.edit-controls');
            const addBtn = document.getElementById("btnAddType");
            const isEditing = label.getAttribute('contenteditable') === 'true';

            if (!isEditing) {
                // Close all other open editors
                document.querySelectorAll('.shift-card').forEach(card => {
                    const otherLabel = card.querySelector('.editable-label');
                    const otherControls = card.querySelector('.edit-controls');
                    const otherIcon = card.querySelector('.btn-edit-shift');

                    if (otherLabel.getAttribute('contenteditable') === 'true') {
                        otherLabel.setAttribute('contenteditable', 'false');
                        otherControls.classList.add('d-none');
                        otherControls.classList.remove('d-flex');
                        otherIcon.classList.replace('bi-check-lg', 'bi-brush');
                        otherIcon.classList.remove('text-light');
                    }
                });

                // Open current editor
                label.setAttribute('contenteditable', 'true');
                label.focus();

                // Move cursor to end
                const range = document.createRange();
                const sel = window.getSelection();
                range.selectNodeContents(label);
                range.collapse(false);
                sel.removeAllRanges();
                sel.addRange(range);

                controls.classList.remove('d-none');
                controls.classList.add('d-flex');
                iconElement.classList.replace('bi-brush', 'bi-check-lg');
                iconElement.classList.add('text-light');

                if (addBtn) addBtn.disabled = true;
            } else {
                // Save and Close
                label.setAttribute('contenteditable', 'false');
                controls.classList.add('d-none');
                controls.classList.remove('d-flex');
                iconElement.classList.replace('bi-check-lg', 'bi-brush');
                iconElement.classList.remove('text-light');

                savePlanningConfig();
            }
        }

        /**
         * Live Color Updates
         */
        function updateLivePreview(input, type, key) {
            const previewBox = document.getElementById(`preview-${key}`);
            if (!previewBox) return;

            const label = previewBox.querySelector('.editable-label');
            const circularPreview = input.previousElementSibling;
            const hexLabel = input.nextElementSibling;

            if (circularPreview) circularPreview.style.backgroundColor = input.value;
            if (hexLabel) hexLabel.innerText = input.value;

            if (type === 'bg') {
                previewBox.style.backgroundColor = input.value;
            } else {
                previewBox.style.color = input.value;
                if (label) label.style.setProperty('color', input.value, 'important');
            }
        }

        /**
         * Validation and API Persistence
         */
        async function savePlanningConfig() {
            const updatedData = {};
            const cards = document.querySelectorAll('.shift-card');
            const addBtn = document.getElementById("btnAddType");
            let isValid = true;

            cards.forEach(card => {
                const label = card.querySelector('.editable-label');
                const bgColorInput = card.querySelector('input[oninput*="bg"]');
                const textColorInput = card.querySelector('input[oninput*="text"]');
                const shiftKey = label.innerText.trim();

                if (shiftKey === "NOUVEAU_TYPE" || shiftKey === "") {
                    card.querySelector('.card').classList.add('border-danger');
                    isValid = false;
                } else {
                    card.querySelector('.card').classList.remove('border-danger');
                    updatedData[shiftKey] = {
                        color: bgColorInput.value,
                        textColor: textColorInput.value
                    };
                }
            });

            if (!isValid) {
                Swal.fire({
                    icon: "info",
                    title: "Oops...",
                    text: "Veuillez donner un nom valide au type de shift."
                });
                if (addBtn) addBtn.disabled = false;
                return;
            }

            try {
                const formData = new FormData();
                formData.append('key', 'shiftsColors');
                formData.append('value', JSON.stringify(updatedData));

                const response = await fetch('../api/setParams.php', {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();
                if (!result.success) {
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: "Erreur : " + result.message,
                    });
                }
            } catch (error) {
                console.error("Save failed:", error);
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Erreur réseau lors de la sauvegarde."
                });
            } finally {
                if (addBtn) addBtn.disabled = false;
            }
        }

        /**
         * Add New Type Utility
         */
        function addType() {
            const tempKey = "NOUVEAU_TYPE_" + Date.now();
            const defaultValues = {
                color: "#cccccc",
                textColor: "#000000"
            };

            const container = document.getElementById('shift-config-container');
            const newHtml = renderShiftCard(tempKey, defaultValues);

            container.insertAdjacentHTML('afterbegin', newHtml);

            // Auto-rename placeholder
            const newCard = container.querySelector(`[data-shift-key="${tempKey}"]`);
            const label = newCard.querySelector('.editable-label');
            label.innerText = "NOUVEAU_TYPE";

            // Trigger edit mode immediately
            const brush = newCard.querySelector('.btn-edit-shift');
            enableEditing(brush, tempKey);
        }

        /**
         * Global Keyboard Listeners
         */
        document.addEventListener('keydown', function(e) {
            if (e.target.classList.contains('editable-label') && e.key === 'Enter') {
                e.preventDefault();
                e.target.blur();

                // Trigger the save by simulating the check-icon click
                const card = e.target.closest('.shift-card');
                const icon = card.querySelector('.btn-edit-shift');
                if (icon.classList.contains('bi-check-lg')) {
                    enableEditing(icon, e.target.dataset.key);
                }
            }
        });
    </script>

    <script src="./assets/js/Sortable.min.js"></script>
    <script src="./assets/js/apexcharts.min.js"></script>
    <script src="./assets/js/jsvectormap.min.js"></script>
    <script src="./assets/js/world.js"></script>

</body>

</html>
<!doctype html>
<html lang="fr">
<?php
ob_start();
include '../api/helpers.php';
include '../api/session_info.php';
isAuthPath();
ob_end_flush();

?>


<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
   <title>
    CIO | 
    <?php 
        $view = isset($_GET['view']) ? $_GET['view'] : 'multiple';
        echo ($view === 'single') ? 'Mes Documents' : 'Bibliothèque';
    ?>
</title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes" />
    <meta name="color-scheme" content="light dark" />
    <meta name="theme-color" content="#007bff" media="(prefers-color-scheme: light)" />
    <meta name="theme-color" content="#1a1a1a" media="(prefers-color-scheme: dark)" />

    <meta name="title" content="CIO v4 | Dashboard" />
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <script src="./assets/js/helper.js"></script>
    <style>
        #file-manager {
            border: 1px solid #ccc;
            margin: 10px;
            padding: 15px;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        #current-path {
            padding: 10px;
            background-color: #e0f7fa;
            border-radius: 4px;
            font-weight: bold;
            color: #00796b;
            height: 31.6px;
            width: 100%;
            display: flex;
            align-items: center;
        }

        #file-view {
            display: flex;
            flex-wrap: wrap;
            gap: 25px;
            padding: 10px;
            min-height: 100px;
        }

        .file-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 100px;
            cursor: pointer;
            padding: 8px;
            border-radius: 6px;
            transition: background-color 0.15s, transform 0.1s;
        }

        .file-item:hover {
            background-color: #e0e0e0;
            transform: scale(1.03);
        }

        .icon {
            font-size: 3.5em;
            margin-bottom: 5px;
        }

        .folder .icon {
            color: #ffb300;
        }

        .document .icon {
            color: #3f51b5;
        }

        .file-name {
            user-select: none;
            font-size: 0.8em;
            text-align: center;
            word-break: break-word;
            overflow: hidden;
            line-height: 1.2;
        }

        /* #search-container {
            display: flex;
            gap: 10px;
            padding: 5px;
            background-color: #f9f9f9;
            border-radius: 6px;
            border: 1px solid #eee;
        } */

        #search-input {
            flex-grow: 1;
            padding: 10px 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 1em;
            transition: border-color 0.3s, box-shadow 0.3s;
            height: 32px;
        }

        #search-input:focus {
            border-color: #00796b;
            box-shadow: 0 0 5px rgba(0, 121, 107, 0.3);
            outline: none;
        }

        .search-btn {
            padding: 10px 18px;
            border: none;
            border-radius: 4px;
            font-size: 1em;
            cursor: pointer;
            color: #fff;
            transition: background-color 0.2s;
        }

        #search-button {
            background-color: #00796b;
        }

        #search-button:hover {
            background-color: #005a4e;
        }

        #clear-search-button {
            background-color: #607d8b;
        }

        #clear-search-button:hover {
            background-color: #455a64;
        }

        /* #upload-btn {
            display: none;
            padding: 8px 14px;
            background: linear-gradient(135deg, #007bff, #0056b3);
            color: #fff;
            border: none;
            border-radius: 6px;
            font-size: 14px;
            cursor: pointer;
            font-weight: 600;
            transition: 0.25s ease-in-out;
            box-shadow: 0 3px 6px rgba(0, 123, 255, 0.25);
            width: 232px;
            height: 43px;
        } */

        /* #upload-btn:hover {
            background: linear-gradient(135deg, #0056b3, #004298);
            transform: translateY(-2px);
            box-shadow: 0 5px 12px rgba(0, 91, 187, 0.45);
        } */

        /* #upload-btn:active {
            transform: translateY(0);
            box-shadow: 0 3px 6px rgba(0, 91, 187, 0.30);
        } */

        /* #upload-btn:hover::before {
            animation: pop 0.3s ease-in-out;
        } */

        @keyframes pop {
            0% {
                transform: scale(0.6);
                opacity: 0;
            }

            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        #statt {
            position: sticky;
            top: 55px
                /*60px*/
            ;
            background: #fff;
            z-index: 1020;
            padding: 10px 0;
            border-bottom: 1px solid #ddd;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }


        .context-menu {
            position: absolute;
            background: #fff;
            border: 1px solid #ccc;
            display: none;
            z-index: 10000;
            min-width: 150px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
            border-radius: 0px 10px 10px 10px;
            overflow: hidden;
        }

        .context-menu .menu-item {
            padding: 10px;
            cursor: pointer;
        }

        .context-menu .menu-item:hover {
            background: #f0f0f0;
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
    $user_role = $_SESSION['role'];
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
                $view = isset($_GET['view']) ? $_GET['view'] : 'multiple';
                $currentPage = $view == 'single' ? 'documentsOne' : 'documents';
                include './components/profileNav.php';
                ?>
            </div>
        </nav>
        <?php
        $userRole = $role;
        $view = isset($_GET['view']) ? $_GET['view'] : 'multiple';
        $currentPage = $view == 'single' ? 'documentsOne' : 'documents';
        include './components/sidebar.php';
        ?>
        <main class="app-main">
            <div class="app-content-header">
                <div class="container-fluid">
                </div>
            </div>
            <div class="app-content">
                <div class="container-fluid">
                    <!--  -->

                    <div id="file-manager">

                        <div id="statt" style="gap:<?= $user_role == 'A' &&  $view == 'multiple' ? '40px' : '0' ?>;">
                            <div id="current-path">/</div>
                            <div>
                                <div id="search-container" style="display: <?= $user_role == 'A' &&  $view == 'multiple' ? 'block' : 'none' ?>;">
                                    <input type="text" id="search-input" placeholder="Chercher ...">
                                    <!-- 
                                    <button id="upload-btn">Upload
                                        PDF</button> -->



                                    <button type="button" id="upload-btn" class="btn btn-secondary mb-2" style="margin-bottom: 0px !important;display: flex;flex-direction: row;justify-content: center; align-items: center; gap: 5px;width: max-content;">Ajouter un document</button>




                                    <input type="file" id="pdf-upload" accept=".pdf,.doc,.docx,.xls,.xlsx,.csv,.txt" multiple
                                        style="display:none">
                                </div>
                            </div>
                        </div>
                        <div id="file-view" style="height: 25%; overflow: hidden;">
                            <div style="text-align:center; padding: 20px; width: 100%;"><i class="fas fa-spinner fa-spin"></i>
                                Chargement...
                            </div>


                        </div>
                        <div id="customMenu" class="context-menu" style="visibility: <?= $user_role == 'A' &&  $view == 'multiple' ? 'visible !important' : 'hidden !important' ?>;">
                            <div class="menu-item" id="switchVisibility">Switch visibility</div>
                            <div class="menu-item" id="deleteFile">Delete</div>
                        </div>
                    </div>

                    <!--  -->
                </div>

            </div>
        </main>

    </div>
    <script src="./assets/js/overlayscrollbars.browser.es6.min.js"
        crossorigin="anonymous"></script>
    <script src="./assets/js/popper.min.js"
        crossorigin="anonymous"></script>
    <script src="./assets/js/bootstrap.min.js" crossorigin="anonymous"></script>
    <script src="./assets/js/adminlte.js"></script>
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
            // 

        });
    </script>
    <script src="./assets/js/jquery-3.7.0.min.js"></script>

    <script>
        let fileSystem = {};
        let currentDirectory = fileSystem;
        let currentPath = '/';

        const fileView = $('#file-view');
        const pathElement = $('#current-path');

        const API_ENDPOINT = '../api/get_personnel.php';

        const allowedTypes = [
            "application/pdf",
            "application/msword",
            "application/vnd.openxmlformats-officedocument.wordprocessingml.document",
            "application/vnd.ms-excel",
            "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
            "text/plain"
        ];

        const allowedExtensions = ["pdf", "doc", "docx", "xls", "xlsx", "txt"];
        const view = new URLSearchParams(window.location.search).get('view');

        function fetchRootDirectory() {
            $.getJSON(API_ENDPOINT, {
                    'view': view
                })
                // .done(function(data) {
                //     fileSystem = data;
                //     currentDirectory = fileSystem;
                //     renderDirectory();
                // })
                .done(function(response) {
                    console.log(response)
                    if (response.status === "success") {
                        fileSystem = response.data;
                        currentDirectory = fileSystem;
                    } else if (response.status == "error" && response.message == "Invalid view parameter") {
                        window.location.href = "../dist/404.html";
                    } else {
                        fileSystem = {};
                        currentDirectory = {};
                    }

                    renderDirectory();
                })
                .fail(function(jqXHR, textStatus, errorThrown) {
                    fileView.html('<div style="color:red; text-align:center; padding: 20px; width: 100%;">🚫 Erreur lors du chargement des dossiers utilisateur ...</div>');
                });
        }

        function navigateInto(name, itemData) {
            if (itemData.type === 'folder') {

                if (itemData.contents && Object.keys(itemData.contents).length > 0) {
                    currentDirectory = itemData.contents;
                    currentPath += name + '/';
                    renderDirectory();
                    return;
                }

                if (itemData.user_id) {
                    fileView.html('<div style="text-align:center; padding: 20px; width: 100%;"><i class="fas fa-spinner fa-spin fa-2x"></i><p>Loading documents...</p></div>');

                    $.getJSON('../api/get_documents.php', {
                            ref_user_id: itemData.user_id
                        })
                        // .done(function(contents) {

                        //     itemData.contents = contents;
                        //     currentDirectory = contents;
                        //     currentPath += name + '/';
                        //     renderDirectory();
                        // })
                        .done(function(response) {
                            if (response.status === "success") {
                                itemData.contents = response.data;
                            } else {
                                itemData.contents = {};
                            }

                            currentDirectory = itemData.contents;
                            currentPath += name + '/';
                            renderDirectory();
                        })
                        .fail(function(jqXHR, textStatus, errorThrown) {
                            // alert(`Impossible de charger les fichiers pour ${name}. Vérifier le réseau.`);
                            Swal.fire({
                                icon: "error",
                                title: "Oops...",
                                text: `Impossible de charger les fichiers pour ${name}. Vérifier le réseau.`,
                            });
                            itemData.contents = {};
                            currentDirectory = itemData.contents;
                            currentPath += name + '/';
                            renderDirectory();
                        });
                    return;
                }

                if (itemData.contents) {
                    currentDirectory = itemData.contents;
                    currentPath += name + '/';
                    renderDirectory();
                }
            }
        }

        function navigateUp() {
            $('#search-input').val('');
            const pathSegments = currentPath.split('/').filter(s => s.length > 0);

            if (pathSegments.length > 0) {
                pathSegments.pop();
                currentPath = '/' + pathSegments.join('/') + (pathSegments.length > 0 ? '/' : '');

                let newDir = fileSystem;
                for (const segment of pathSegments) {
                    if (newDir[segment] && newDir[segment].contents) {
                        newDir = newDir[segment].contents;
                    } else {
                        newDir = fileSystem;
                        currentPath = '/';
                        break;
                    }
                }

                currentDirectory = newDir;
                renderDirectory();
            }
        }

        function createItemElement(name, type, iconClass = 'fa-folder', visibility = 1) {

            const finalIcon = (type === 'folder' || name === '..') ? (name === '..' ? 'fa-arrow-up' : 'fa-folder') : iconClass;

            const iconHtml = `<i class="fas ${finalIcon} icon"></i>`;
            const nameHtml = `<span class="file-name">${name}</span>`;

            const itemEl = $('<div>', {
                class: `file-item ${type}`,
                'data-name': name,
                html: iconHtml + nameHtml
            });

            if (visibility == 0) {
                itemEl.css('opacity', '0.4');
            } else {
                itemEl.css('opacity', '1');
            }
            return itemEl;
        }

        function renderDirectory() {
            fileView.empty();

            let isUserFolder = false;
            const pathSegments = currentPath.split('/').filter(s => s.length > 0);
            let testDir = fileSystem;

            for (const segment of pathSegments) {
                if (testDir[segment] && testDir[segment].user_id) {
                    isUserFolder = true;
                    break;
                }
                if (testDir[segment] && testDir[segment].contents) {
                    testDir = testDir[segment].contents;
                }
            }

            if (isUserFolder) {
                $('#upload-btn').show();
                $('#search-input').hide();
            } else {
                $('#upload-btn').hide();
                $('#search-input').show();
            }

            const nameWithoutBr = currentPath.replace(/<br\s*\/?>/gi, ' ');
            pathElement.text(`Chemin actuel: ${nameWithoutBr}`);

            if (currentPath !== '/') {
                const upEl = createItemElement('..', 'folder', 'fa-arrow-up');
                upEl.on('dblclick', navigateUp);
                fileView.append(upEl);
            }

            const currentKeys = Object.keys(currentDirectory);

            if (currentKeys.length === 0 && currentPath !== '/') {
                // fileView.html('<div style="text-align:center; color:#555; padding: 20px; width: 100%;">This folder is currently empty.</div>');
            } else {
                for (const name in currentDirectory) {

                    const item = currentDirectory[name];

                    const displayName = item.filename || name;
                    const itemElement = createItemElement(displayName, item.type, item.icon, item.visibility);

                    if (item.type === 'folder') {
                        itemElement.on('dblclick', () => {
                            navigateInto(name, item)
                        });
                    } else {
                        itemElement.on('dblclick', () => {
                            const displayName = item.filename || name;

                            if (item.base64_data && item.mime_type) {
                                const link = document.createElement('a');
                                link.href = `data:${item.mime_type};base64,${item.base64_data}`;
                                link.download = displayName;
                                document.body.appendChild(link);
                                link.click();
                                document.body.removeChild(link);
                            } else {
                                // alert(`Impossible de télécharger ${displayName}. Données de fichier manquantes.`);
                                Swal.fire({
                                    icon: "error",
                                    title: "Oops...",
                                    text: `Impossible de télécharger ${displayName}. Données de fichier manquantes.`,
                                });
                            }
                        });
                        itemElement.on('contextmenu', (e) => {
                            e.preventDefault();
                            $('#customMenu')
                                .css({
                                    position: 'fixed',
                                    top: e.pageY + 'px',
                                    left: e.pageX + 'px',
                                    display: 'block'
                                })
                                .data('file', item.id);
                        });
                    }
                    fileView.append(itemElement);
                }
            }
        }
        $('#upload-btn').on('click', function() {
            $('#pdf-upload').click();
        });

        $('#pdf-upload').on('change', function(e) {
            const files = this.files;
            if (!files.length) return;

            const pathSegments = currentPath.split('/').filter(s => s.length > 0);
            let testDir = fileSystem;
            let userId = null;

            for (const segment of pathSegments) {
                if (testDir[segment] && testDir[segment].user_id) {
                    userId = testDir[segment].user_id;
                    break;
                }
                if (testDir[segment] && testDir[segment].contents) {
                    testDir = testDir[segment].contents;
                }
            }

            if (!userId) {
                // alert("❌ Cannot upload here!");
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Cannot upload here!",
                });
                return;
            }

            for (let file of files) {
                const ext = file.name.split('.').pop().toLowerCase();
                if (!allowedTypes.includes(file.type) && !allowedExtensions.includes(ext)) {
                    // alert(`❌ "${file.name}" is not allowed!`);
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: `"${file.name}" is not allowed!`,
                    });
                    return;
                }
            }

            const uploadNext = (index = 0) => {
                if (index >= files.length) {
                    // alert("🎉 All files uploaded successfully!");
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: ` All files uploaded successfully!`,
                        showConfirmButton: false,
                        timer: 3000
                    });
                    reloadCurrentDirectory()
                    // navigateInto(
                    //     pathSegments[pathSegments.length - 1],
                    //     testDir[pathSegments[pathSegments.length - 1]]
                    // );
                    return;
                }

                let file = files[index];
                let formData = new FormData();
                formData.append('file', file);
                formData.append('ref_user_id', userId);
                formData.append('sendType', 'single');

                $.ajax({
                    url: '../api/upload_pdf.php',
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    beforeSend: () => {
                        console.log(`⏳ Uploading: ${file.name}`);
                    },
                    success: () => {
                        console.log(`✔ Uploaded: ${file.name}`);
                        uploadNext(index + 1);
                    },
                    error: () => {
                        // alert(`🚫 Upload failed: ${file.name}`);
                        Swal.fire({
                            icon: "error",
                            title: "Oops...",
                            text: `Upload failed: ${file.name}`,
                        });
                    }
                });
            };

            uploadNext();
        });


        $(document).ready(function() {
            fetchRootDirectory();
        });


        function localSearch() {
            const searchTerm = $('#search-input').val().toLowerCase();
            let itemsFound = 0;
            $('#no-results-message').remove();
            $('#file-view .file-item').each(function() {
                const $item = $(this);
                const itemName = $item.data('name');

                if (itemName === '..') {
                    $item.show();
                    return true;
                }

                if (searchTerm === "") {
                    $item.show();
                    itemsFound++;
                } else if (itemName.toLowerCase().includes(searchTerm)) {
                    $item.show();
                    itemsFound++;
                } else {
                    $item.hide();
                }
            });

            if (itemsFound === 0 && searchTerm !== "") {
                const noResultsMessage = $('<div>', {
                    id: 'no-results-message',
                    style: 'text-align: center; color: #cc0000; padding: 20px; width: 100%;margin-top: 15px;',
                    html: '<i class="fas fa-exclamation-triangle"></i> Aucun dossier correspondant trouvé pour **' + searchTerm + '**.'
                });
                $('#file-view').append(noResultsMessage);
            }
        }

        $(document).on('click', function() {
            $('#customMenu').hide();
        });

        $(document).on('contextmenu', function(e) {
            e.preventDefault();
        });

        $("#switchVisibility").on('click', function() {
            const file = $('#customMenu').data('file');

            $.post("../api/toggle_visibility.php", {
                id: file
            }, function(response) {
                console.log(response);

                if (response.status === "success") {
                    // alert("Visibility switched!");
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: "Visibility switched!",
                        showConfirmButton: false,
                        timer: 3000
                    });
                    reloadCurrentDirectory();
                }
            }, "json");

            $('#customMenu').hide();
        });

        $("#deleteFile").on('click', function() {
            const file = $('#customMenu').data('file');

            // if (!confirm("Are you sure you want to delete this file?")) return;

            // $.post("../api/delete_file.php", {
            //     id: file
            // }, function(response) {
            //     console.log(response);

            //     if (response.status === "success") {
            //         // alert("File deleted!");
            //         Swal.fire({
            //             toast: true,
            //             position: 'top-end',
            //             icon: 'success',
            //             title: "File deleted!",
            //             showConfirmButton: false,
            //             timer: 3000
            //         });
            //         reloadCurrentDirectory();
            //     }
            // }, "json");




            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to undo this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: "#1ed760",
                cancelButtonColor: "#d33",
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (!result.isConfirmed) return;

                $.post("../api/delete_file.php", {
                    id: file
                }, function(response) {
                    console.log(response);

                    if (response.status === "success") {
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: "File deleted!",
                            showConfirmButton: false,
                            timer: 3000
                        });

                        reloadCurrentDirectory();
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Failed to delete file'
                        });
                    }
                }, "json");
            });

            $('#customMenu').hide();
        });

        function reloadCurrentDirectory() {
            const pathSegments = currentPath.split('/').filter(s => s.length > 0);

            let dir = fileSystem;

            const fetchNext = (index) => {
                if (index >= pathSegments.length) {
                    currentDirectory = dir;
                    renderDirectory();
                    return;
                }

                const segment = pathSegments[index];

                if (dir[segment] && dir[segment].user_id) {
                    $.getJSON('../api/get_documents.php', {
                            ref_user_id: dir[segment].user_id
                        })
                        // .done(function(contents) {
                        //     dir[segment].contents = contents;
                        //     dir = contents;
                        //     fetchNext(index + 1);
                        // })
                        .done(function(response) {
                            if (response.status === "success") {
                                dir[segment].contents = response.data;
                                dir = response.data;
                            } else {
                                dir[segment].contents = {};
                                dir = {};
                            }

                            fetchNext(index + 1);
                        })
                        .fail(function() {
                            // alert(`Impossible de charger les fichiers pour ${segment}.`);
                            Swal.fire({
                                icon: "error",
                                title: "Oops...",
                                text: `Impossible de charger les fichiers pour ${segment}.`,
                            });
                            dir[segment].contents = {};
                            dir = dir[segment].contents;
                            fetchNext(index + 1);
                        });
                } else if (dir[segment] && dir[segment].contents) {
                    dir = dir[segment].contents;
                    fetchNext(index + 1);
                } else {
                    dir = {};
                    currentDirectory = dir;
                    renderDirectory();
                }
            };

            fetchNext(0);
        }

        $(document).ready(function() {
            if (typeof fileSystemData !== 'undefined') {
                renderDirectory();
            }
            $('#search-input').on('keyup', function(e) {
                localSearch();
            });
        });
    </script>
    <script src="./assets/js/sweetalert2@11.js"></script>
    <script src="./assets/js/Sortable.min.js"></script>
    <script src="./assets/js/apexcharts.min.js"></script>
    <script src="./assets/js/jsvectormap.min.js"></script>
    <script src="./assets/js/world.js"></script>


</body>

</html>
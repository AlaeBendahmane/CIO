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
  <title>CIO | Inbox</title>

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

  <script src="./assets/js/helper.js"></script>
  <style>
    /* Sidebar Active Selection */
    .active-selection {
      background-color: #e2e6ea !important;
      /* border-right: 3px solid #007bff; */
    }

    /* Unread Dot */
    .unread-dot-small {
      height: 10px;
      width: 10px;
      background-color: #007bff;
      border-radius: 50%;
      margin-left: 10px;
      flex-shrink: 0;
    }

    /* Unread background tint */
    .inbox-item.is-unread {
      background-color: #f4faff !important;
    }

    /* Smooth hover transition */
    .inbox-item:hover {
      background-color: #f8f9fa;
    }

    /* Small text utility */
    .fs-7 {
      font-size: 0.85rem;
    }

    .fs-8 {
      font-size: 0.75rem;
    }

    /* Modern Thin Scrollbar */
    .custom-scrollbar::-webkit-scrollbar {
      width: 6px;
    }

    .custom-scrollbar::-webkit-scrollbar-track {
      background: #f1f1f1;
      border-radius: 10px;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb {
      background: #c1c1c1;
      border-radius: 10px;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
      background: #a8a8a8;
    }

    /* Ensure the text doesn't hug the edges when scrolling */
    #detail-content {
      line-height: 1.6;
      color: #444;
      scrollbar-width: thin;
      /* For Firefox */
      scrollbar-color: #c1c1c1 #f1f1f1;
      /* For Firefox */
    }

    /* Make the selected item look "pressed" and distinct */
    .active-selection {
      background-color: #ffffff !important;
      border-left: 4px solid #007bff !important;
      /* Thick blue bar on selected */
      box-shadow: inset 0 0 10px rgba(0, 0, 0, 0.05);
      z-index: 1;
    }

    .inbox-item {
      border-left: 4px solid transparent;
      transition: all 0.15s ease-in-out;
    }

    .border-dashed {
      border: 2px dashed #dee2e6 !important;
      background-color: #f8f9fa;
    }
  </style>
</head>

<body class="fixed-header layout-fixed sidebar-expand-lg bg-body-tertiary" id="Inbox">
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
        $currentPage = 'inbox';
        include './components/profileNav.php';
        ?>
      </div>
    </nav>
    <?php
    $userRole = $role;
    $currentPage = 'inbox';
    include './components/sidebar.php';
    ?>
    <main class="app-main" style="overflow-y: hidden;">
      <div class="app-content-header">
        <div class="container-fluid">
        </div>
      </div>
      <div class="app-content">
        <div class="container-fluid">
          <div class="row">
            <div class="col-md-4">
              <div class="card card-primary card-outline shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                  <h3 class="card-title fw-bold">Notifications</h3>
                </div>
                <div class="card-body p-0 custom-scrollbar" id="inbox-list" style="height: 643px;max-height: 643px; overflow-y: auto;">
                </div>
              </div>
            </div>

            <div class="col-md-8">
              <div class="card card-primary card-outline shadow-sm" id="details-view" style="display: none; height: 700px;">
                <div class="card-header bg-white">
                  <h3 class="card-title fw-bold" id="detail-title">...</h3>
                  <div class="card-tools text-secondary fs-7" id="detail-time"></div>
                </div>
                <div class="card-body p-0 d-flex flex-column">
                  <div class="mailbox-read-info p-3 border-bottom d-flex align-items-center bg-light-subtle">
                    <img id="detail-avatar" src="" class="img-circle border shadow-sm me-3" style="width: 50px; height: 50px;">
                    <div>
                      <h6 class="mb-0">De: <span id="detail-sender" class="fw-bold text-primary">...</span></h6>
                      <!-- <small class="text-muted">Destinataire: <span class="badge badge-pill bg-secondary-subtle text-dark">Moi</span></small> -->
                    </div>
                  </div>
                  <div class="mailbox-read-message p-4 custom-scrollbar flex-grow-1"
                    id="detail-content"
                    style="white-space: pre-wrap;overflow-y: auto;max-height: 550px; background-color: #fff;">
                  </div>
                </div>
              </div>

              <div id="details-empty" class="card card-outline card-primary h-100 d-flex align-items-center justify-content-center border-dashed" > <!--style="height: 700px !important;"-->
                <div class="text-center text-muted">
                  <i class="bi bi-envelope-paper-fill fs-1 text-primary opacity-25"></i>
                  <p class="mt-3 fw-medium">Sélectionnez une notification pour l'ouvrir</p>
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
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      loadInbox();

      // Delegate click events to the inbox list
      document.getElementById('inbox-list').addEventListener('click', function(e) {
        const item = e.target.closest('.inbox-item');
        if (!item) return;

        // 1. Visual Active State (Selection)
        document.querySelectorAll('.inbox-item').forEach(el => el.classList.remove('active-selection'));
        item.classList.add('active-selection');

        // 2. Extract Data
        const data = JSON.parse(item.getAttribute('data-full'));

        // 3. Mark as read visually & via API
        if (item.classList.contains('is-unread')) {
          item.classList.remove('is-unread', 'fw-bold', 'bg-light');
          item.style.borderLeftColor = 'transparent';
          const dot = item.querySelector('.unread-dot-small');
          if (dot) dot.remove();

          // Call API to update database
          fetch(`../api/notifications.php?action=mark_read&id=${data.id}`);
          refreshNotifications()
        }

        // 4. Populate Right Panel
        document.getElementById('details-view').style.display = 'block';
        document.getElementById('details-empty').style.visibility = 'hidden';

        document.getElementById('detail-title').innerText = data.title;
        document.getElementById('detail-sender').innerText = data.sender;
        document.getElementById('detail-content').innerText = data.content;
        const d = new Date(data.createdAt);

        const formatted = d.toLocaleDateString('en-GB') + ' ' +
          d.toLocaleTimeString('en-GB', {
            hour: '2-digit',
            minute: '2-digit',
            hour12: false
          });
        document.getElementById('detail-time').innerText = formatted; /*new Date(data.createdAt).toLocaleString();*/

        // Update Detail Avatar
        document.getElementById('detail-avatar').src = `https://ui-avatars.com/api/?name=${encodeURIComponent(data.sender)}&background=random&color=fff&size=128&rounded=true`;
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
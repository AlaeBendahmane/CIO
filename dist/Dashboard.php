<!doctype html>
<html lang="fr">
<?php
ob_start();
include '../api/helpers.php';
include '../api/session_info.php';
isAuthPath();
$size = getDatabaseSize();
ob_end_flush();
?>

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>CIO | Dashboard</title>

  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes" />
  <meta name="color-scheme" content="light dark" />
  <meta name="theme-color" content="#007bff" media="(prefers-color-scheme: light)" />
  <meta name="theme-color" content="#1a1a1a" media="(prefers-color-scheme: dark)" />

  <meta name="title" content="CIO v4 | Dashboard" />
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
  <!-- 3ad zedt -->
  <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
  <script src="./assets/js/helper.js"></script>

  <style>
    /* This class will be added by JS when you click the gear */
    .editing-mode {
      position: relative;
      z-index: 1040 !important;
      /* Higher than the backdrop */
      box-shadow: 0 0 20px rgba(0, 0, 0, 0.3);
    }

    .ui-resizable-handle {
      display: none;
    }

    .ui-resizable-e {
      /* background: #dc3545 !important; */
      width: 8px !important;
      /* cursor: col-resize; */
      cursor: default;
    }
  </style>
  <!--  -->
  <style>
    /* @import url('./assets/css/css2.css'); */
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
        $currentPage = 'dashboard';
        include './components/profileNav.php';
        ?>
      </div>
      <div id="resize-backdrop-nav" style="display: none; position: absolute; top: 0px; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1040;"></div>
    </nav>
    <?php
    $userRole = $role;
    $currentPage = 'dashboard';
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
            <!--begin::Col-->
            <div class="col-lg-3 col-6 ">
              <!--begin::Small Box Widget 1-->
              <div class="small-box text-bg-primary "> <!--resizable-card-->
                <div class="inner">
                  <h3>150</h3>
                  <p>New Orders</p>
                </div>
                <svg class="small-box-icon" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"
                  aria-hidden="true">
                  <path
                    d="M2.25 2.25a.75.75 0 000 1.5h1.386c.17 0 .318.114.362.278l2.558 9.592a3.752 3.752 0 00-2.806 3.63c0 .414.336.75.75.75h15.75a.75.75 0 000-1.5H5.378A2.25 2.25 0 017.5 15h11.218a.75.75 0 00.674-.421 60.358 60.358 0 002.96-7.228.75.75 0 00-.525-.965A60.864 60.864 0 005.68 4.509l-.232-.867A1.875 1.875 0 003.636 2.25H2.25zM3.75 20.25a1.5 1.5 0 113 0 1.5 1.5 0 01-3 0zM16.5 20.25a1.5 1.5 0 113 0 1.5 1.5 0 01-3 0z">
                  </path>
                </svg>
                <a href="#"
                  class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover redirect-dash">
                  Plus d'informations <i class="bi bi-link-45deg"></i>
                </a>
              </div>
              <!--end::Small Box Widget 1-->
            </div>
            <!--end::Col-->
            <div class="col-lg-3 col-6 ">
              <!--begin::Small Box Widget 2-->
              <div class="small-box text-bg-success"> <!--resizable-card-->
                <div class="inner">
                  <h3>53<sup class="fs-5">%</sup></h3>

                  <p>Bounce Rate</p>
                </div>
                <svg class="small-box-icon" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"
                  aria-hidden="true">
                  <path
                    d="M18.375 2.25c-1.035 0-1.875.84-1.875 1.875v15.75c0 1.035.84 1.875 1.875 1.875h.75c1.035 0 1.875-.84 1.875-1.875V4.125c0-1.036-.84-1.875-1.875-1.875h-.75zM9.75 8.625c0-1.036.84-1.875 1.875-1.875h.75c1.036 0 1.875.84 1.875 1.875v11.25c0 1.035-.84 1.875-1.875 1.875h-.75a1.875 1.875 0 01-1.875-1.875V8.625zM3 13.125c0-1.036.84-1.875 1.875-1.875h.75c1.036 0 1.875.84 1.875 1.875v6.75c0 1.035-.84 1.875-1.875 1.875h-.75A1.875 1.875 0 013 19.875v-6.75z">
                  </path>
                </svg>
                <a href="#"
                  class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover redirect-dash">
                  Plus d'informations <i class="bi bi-link-45deg"></i>
                </a>
              </div>
              <!--end::Small Box Widget 2-->
            </div>
            <!--end::Col-->
            <div class="col-lg-3 col-6 ">
              <!--begin::Small Box Widget 3-->
              <div class="small-box text-bg-warning"> <!--resizable-card-->
                <div class="inner">
                  <h3 id="nbr_all" style="color:#fff !important">-</h3>

                  <p style="color:#fff !important">Utilisateurs</p>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="small-box-icon" viewBox="0 0 16 16" aria-hidden="true">
                  <path d="M7 14s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1zm4-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6m-5.784 6A2.24 2.24 0 0 1 5 13c0-1.355.68-2.75 1.936-3.72A6.3 6.3 0 0 0 5 9c-4 0-5 3-5 4s1 1 1 1zM4.5 8a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5" />
                </svg>
                <a href="Utilisateurs.php" class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover redirect-dash" style="color:#fff !important">
                  Plus d'informations <i class="bi bi-link-45deg"></i>
                </a>
              </div>
              <!--end::Small Box Widget 3-->
            </div>
            <!--end::Col-->
            <div class="col-lg-3 col-6">
              <!--begin::Small Box Widget 4-->
              <div class="small-box text-bg-danger"><!--resizable-card-->
                <div class="inner">
                  <h3>
                    <?= $size . " MB"; ?>
                  </h3>
                  <p>Utilisation de la base de données</p>
                </div>
                <svg class="small-box-icon" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"
                  aria-hidden="true">
                  <path clip-rule="evenodd" fill-rule="evenodd"
                    d="M2.25 13.5a8.25 8.25 0 018.25-8.25.75.75 0 01.75.75v6.75H18a.75.75 0 01.75.75 8.25 8.25 0 01-16.5 0z">
                  </path>
                  <path clip-rule="evenodd" fill-rule="evenodd"
                    d="M12.75 3a.75.75 0 01.75-.75 8.25 8.25 0 018.25 8.25.75.75 0 01-.75.75h-7.5a.75.75 0 01-.75-.75V3z">
                  </path>
                </svg>
                <a href="#"
                  class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover redirect-dash">
                  Plus d'informations <i class="bi bi-link-45deg"></i>
                </a>
              </div>
              <!--end::Small Box Widget 4-->
            </div>
            <!--end::Col-->

          </div>
          <div class="row" id="roooow">
            <div class="col-6">
              <?php include './components/agentsPERcompagne.php' ?>
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
  <!-- 3ad zedtha -->
  <script src="./assets/js/jquery-3.7.0.min.js"></script>
  <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>

  <script>
    $(document).ready(function() {
      // 1. Render the Chart
      var options = {
        series: <?php echo json_encode($agentCounts); ?>,
        labels: <?php echo json_encode($campagneNames); ?>,
        chart: {
          type: 'donut',
          height: 300
        },
        plotOptions: {
          pie: {
            donut: {
              size: '70%',
              labels: {
                show: true,
                total: {
                  show: true,
                  label: 'Total Utilisateurs',
                  color: '#000000',
                  formatter: function(w) {
                    return w.globals.seriesTotals.reduce((a, b) => a + b, 0)
                  }
                }
              }
            }
          }
        },
        legend: {
          position: 'bottom'
        }
      };
      var chart = new ApexCharts(document.querySelector("#agentDonutChart"), options);
      chart.render();

      // 2. Initialize Resizable but keep it DISABLED by default
      var $card = $(".resizable-card").resizable({
        disabled: true, // Start locked
        handles: "e, w",
        minWidth: 300,
        // maxWidth: 1200,
        containment: "#roooow",
        // animate: true,
        // helper: "ui-resizable-helper",
        grid: 100,
        stop: function(event, ui) {
          window.dispatchEvent(new Event('resize'));
        }
      });

      $('.js-toggle-resize').on('click', function(e) {
        e.preventDefault();
        console.log('Mode Edition: Toggled,zid resizable-card');

        var $icon = $('#iconexpand');
        var $backdropNav = $('#resize-backdrop-nav');
        var $backdropAside = $('#resize-backdrop-aside');

        var isCurrentlyDisabled = $card.resizable("option", "disabled");
        console.log(isCurrentlyDisabled)
        if (isCurrentlyDisabled) {
          // --- ENTER EDIT MODE ---
          $backdropNav.fadeIn(300);
          $backdropAside.fadeIn(300);
          console.log('445')
          $icon.removeClass('bi-gear').addClass('bi-check-circle'); // Change to green check
          $('.redirect-dash').css('visibility', 'hidden');
          console.log('00')
          $card.resizable("enable");
          $card.addClass('editing-mode'); // Adds shadow/focus 
          $(".ui-resizable-handle").show();
        } else {
          // --- EXIT EDIT MODE ---
          $backdropNav.fadeOut(300);
          $backdropAside.fadeOut(300);
          $icon.removeClass('bi-check-circle').addClass('bi-gear'); // Change back to gear
          console.log('object')
          $('.redirect-dash').css('visibility', 'visible');
          console.log('+++')

          $card.resizable("disable");
          $card.removeClass('editing-mode');
          $(".ui-resizable-handle").hide();
        }
      });
    });
  </script>
  <!--  -->
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


    document.onreadystatechange = function() {
      loadDashboardStats()

    }


    async function loadDashboardStats() {
      try {
        const response = await fetch('../api/get_number_users.php');

        const data = await response.json();

        if (data.status === 'success') {
          console.log(data)
          // On met à jour le HTML avec les données reçues
          document.getElementById('nbr_all').innerText = data.message.total;

          // document.getElementById('total-users').innerText = data.message.total_u;
          // document.getElementById('total-admins').innerText = data.message.total_a;
          // document.getElementById('total-clients').innerText = data.message.total_c;
        } else {
          Swal.fire({
            icon: "error",
            title: "Oops...",
            text: "Erreur API:" + data.message,
          });
        }
      } catch (error) {
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: "Erreur lors de la récupération des stats:" + error
        });
      }
    }


    // Appeler la fonction au chargement de la page
  </script>
  <script src="./assets/js/Sortable.min.js"></script>
  <script src="./assets/js/apexcharts.min.js"></script>
  <script src="./assets/js/jsvectormap.min.js"></script>
  <script src="./assets/js/world.js"></script>


</body>

</html>
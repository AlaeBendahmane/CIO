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
  <link rel="manifest" href="../manifest.json">
  <meta name="theme-color" content="#6264a7">
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
  <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
  <script src="./assets/js/helper.js"></script>

  <style>
    .editing-mode {
      position: relative;
      z-index: 1040 !important;
      box-shadow: 0 0 20px rgba(0, 0, 0, 0.3);
    }

    .ui-resizable-handle {
      display: none;
    }

    .ui-resizable-e {
      width: 8px !important;
      cursor: default;
    }

    #checkboxList .agent-item {
      display: flex;
      align-items: center;
      padding: 5px;
      cursor: pointer;
      transition: background 0.2s;
    }

    #checkboxList .agent-item:hover {
      background: #f8f9fa;
    }

    #checkboxList input[type="checkbox"] {
      margin-right: 10px;
      accent-color: #6264a7;
      cursor: pointer;
    }

    #checkboxList label {
      margin-bottom: 0;
      cursor: pointer;
      font-weight: normal;
      width: 100%;
    }

    .btn-tool:focus {
      box-shadow: none !important;
      color: #fff;
    }
  </style>
</head>

<body class="fixed-header layout-fixed sidebar-expand-lg bg-body-tertiary">
  <?php
  if ($_SESSION['needReset']) {
    include './components/resetModal.php';
  } else {
    include './components/splashscreen.php';
  }
  ?>
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
      <?php if ($role == 'A'): ?>
        <div class="app-content-header">
          <div class="container-fluid">
          </div>
        </div>
      <?php endif; ?>
      <div class="app-content">
        <div class="container-fluid">
          <?php include './components/cardStats.php' ?>
          <div class="row mb-3" id="roooow">
            <div class="col-6">
              <?php if ($role == 'A'): ?>
                <?php include './components/agentsPERcompagne.php' ?>
              <?php endif; ?>
            </div>
            <div class="col-6">
              <?php if ($role == 'A'): ?>
                <?php include './components/soon.php' ?>
              <?php endif; ?>
            </div>
          </div>
          <div class="row mb-3">
            <div class="col-12">
              <?php if ($role == 'A'): ?>
                <?php include './components/cumulAgents.php' ?>
              <?php endif; ?>
            </div>
          </div>
          <div class="row mb-3">
            <div class="col-12">
              <?php if ($role == 'U'): ?>
                <?php include './components/Agenda.php' ?>
              <?php endif; ?>
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
  <script src="./assets/js/jquery-3.7.0.min.js"></script>
  <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>

  <script>
    $(document).ready(async function() {
      if (document.getElementById('donutId')) {
        const chartElement = document.getElementById('agentDonutChart');
        if (!chartElement) return

        const response = await fetch('../api/agentsPerCompagnes.php');
        const data = await response.json();

        if (data.error) {
          console.error("API Error:", data.error);
          return;
        }
        // 1. Render the Chart
        var options = {
          series: data.series,
          labels: data.labels,
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
        var chart = new ApexCharts(chartElement, options);
        chart.render();
      }
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
      const statsUsers = document.getElementById('statsUsers');
      if (!statsUsers) return
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

  <script>
    const setPickerDefault = () => {
      const now = new Date();
      const year = now.getFullYear();
      // getMonth() is 0-indexed (Jan is 0), so we add 1
      const month = String(now.getMonth() + 1).padStart(2, '0');

      const defaultValue = `${year}-${month}`; // Becomes "2026-03"
      document.getElementById('chartPeriod').value = defaultValue;
    };
    if (document.getElementById('cumulId')) {
      setPickerDefault()
    }
  </script>
  <script>
    let fullChartData = [];
    let myChart = null;


    async function loadAndInitChart() {
      const picker = document.getElementById('chartPeriod');
      const dateVal = picker.value;
      if (!dateVal) return;

      // 2. Save selected date to localStorage
      // localStorage.setItem('CumulsUtilisateursPeriod', dateVal);

      const [year, month] = dateVal.split('-');
      const response = await fetch(`../api/cumul_agents.php?mois=${parseInt(month)}&annee=${year}`);
      fullChartData = await response.json();

      // 3. Retrieve saved agent selections
      const savedAgents = JSON.parse( /*localStorage.getItem('CumulsUtilisateursAgents') ||*/ "[]");

      fullChartData.forEach(agent => {
        // If we have saved selections, use them; otherwise, default to true
        if (savedAgents.length > 0) {
          agent.selected = savedAgents.includes(`${agent.nom} ${agent.prenom}`);
        } else {
          agent.selected = true;
        }
      });



      // Update chart with only the selected agents
      const filteredData = fullChartData.filter(agent => agent.selected !== false);
      updateChart(filteredData);
      renderCheckboxList();
    }

    function filterList() {
      const searchTerm = document.getElementById('agentSearch').value.toLowerCase();
      renderCheckboxList(searchTerm);
    }

    function renderCheckboxList(searchTerm = "") {
      const container = document.getElementById('checkboxList');
      container.innerHTML = '';

      fullChartData.forEach((agent, index) => {
        const fullName = `${agent.nom} ${agent.prenom} (${agent.idProx})`.toLowerCase();
        if (searchTerm && !fullName.includes(searchTerm)) return;

        const item = document.createElement('div');
        item.className = 'agent-item';
        item.innerHTML = `
                <input type="checkbox" id="chk_${index}" ${agent.selected !== false ? 'checked' : ''} value="${index}" onchange="handleFilterChange()">
                <label for="chk_${index}">${agent.nom} ${agent.prenom} (${agent.idProx? agent.idProx :'----'})</label>
            `;
        item.onclick = (e) => e.stopPropagation();
        container.appendChild(item);
      });
    }

    function selectAllAgents(val) {
      document.querySelectorAll('#checkboxList input').forEach(cb => cb.checked = val);
      handleFilterChange();
    }

    function handleFilterChange() {
      const selectedAgentNames = [];

      document.querySelectorAll('#checkboxList input').forEach(cb => {
        const index = cb.value;
        const isChecked = cb.checked;
        fullChartData[index].selected = isChecked;

        // Collect names of selected agents for storage
        if (isChecked) {
          selectedAgentNames.push(`${fullChartData[index].nom} ${fullChartData[index].prenom}`);
        }
      });

      // 4. Save selected agent names to localStorage
      // localStorage.setItem('CumulsUtilisateursAgents', JSON.stringify(selectedAgentNames));

      const filteredData = fullChartData.filter(agent => agent.selected !== false);
      updateChart(filteredData);
    }

    function updateChart(data) {
      const categories = data.map(item => `${item.nom} ${item.prenom}`);
      const seriesData = data.map(item => item.total_h);

      const options = {
        series: [{
          name: 'Heures',
          data: seriesData
        }],
        chart: {
          type: 'bar',
          height: 300,
          toolbar: {
            show: true
          }
        },
        colors: ['#6264a7'],
        plotOptions: {
          bar: {
            horizontal: false,
            columnWidth: '55%',
            dataLabels: {
              position: 'top'
            }
          }
        },
        xaxis: {
          categories: categories,
          labels: {
            show: false
          },
        },
        dataLabels: {
          enabled: false,
          formatter: val => val + "h",
          offsetY: -20
        }
      };

      if (myChart) {
        myChart.updateOptions({
          xaxis: {
            categories: categories
          },
          series: [{
            data: seriesData
          }]
        });
      } else {
        myChart = new ApexCharts(document.querySelector("#cumulChart"), options);
        myChart.render();
      }
    }
    // });

    document.addEventListener('DOMContentLoaded', () => {
      // const picker = document.getElementById('chartPeriod');

      // // 1. Get saved date or set current month as default
      // const savedDate = localStorage.getItem('CumulsUtilisateursPeriod');
      // if (savedDate) {
      //   picker.value = savedDate;
      // } else {
      //   const now = new Date();
      //   const year = now.getFullYear();
      //   const month = String(now.getMonth() + 1).padStart(2, '0');
      //   picker.value = `${year}-${month}`;
      // }

      if (document.getElementById('cumulId')) {
        loadAndInitChart();
        renderCheckboxList();
      }
    });
  </script>
  <script src='./assets/js/index.global.min.js'></script>

  <script>
    // 1. Move the helper function outside or keep it accessible
    function getResponsiveHeight() {
      // 140px accounts for your header and card padding
      return window.innerHeight - 145;
    }

    document.addEventListener('DOMContentLoaded', function() {

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
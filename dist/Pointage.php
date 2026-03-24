<!-- old at 25/02 -->

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
  <title>CIO | Pointage</title>

  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes" />
  <meta name="color-scheme" content="light dark" />
  <meta name="theme-color" content="#007bff" media="(prefers-color-scheme: light)" />
  <meta name="theme-color" content="#1a1a1a" media="(prefers-color-scheme: dark)" />

  <meta name="title" content="CIO | Dashboard" />
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
  <link rel="stylesheet" href="./assets/css/handsontable.full.min.css">
  <script src="./assets/js/handsontable.full.min.js"></script>
  <script src="./assets/js/helper.js"></script>

  <style>
    @import url('./assets/css/css2.css');

    body {
      background: white;
      margin: 0px;
      color: #334155;
    }

    .container {
      height: max-content;
      padding: 0px 10px;
    }

    .header-controls {
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .actions {
      display: flex;
      gap: 10px;
    }

    .btn-add {
      background: #2563eb;
      color: white;
      border: none;
      padding: 10px 20px;
      border-radius: 8px;
      cursor: pointer;
      font-weight: 600;
    }

    .bg-red {
      color: #fff !important;
      background-color: #fe0001 !important;
      font-weight: bold;
    }

    .bg-purple {
      color: #fff !important;
      background-color: #71309f !important;
      font-weight: bold;
    }

    .bg-light-green {
      color: #000 !important;
      background-color: #ffff01 !important;
      font-weight: bold;
    }

    .col-s {
      background-color: #97cdff !important;
      color: #1e3a8a !important;
      font-weight: bold;
    }

    .col-d {
      background-color: #f7af7e !important;
      color: #7c2d12 !important;
      font-weight: bold;
    }

    .sun-cell {
      background: #f4b083 !important;
      color: #991b1b !important;
    }

    .white-cell {
      background: #fff !important;
      color: #000 !important;
    }

    .sat-cell {
      background: #97cdff !important;
      color: #475569 !important;
    }

    .handsontable td,
    .handsontable th {
      border: 1px solid #000 !important;
    }

    .modal-overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(15, 23, 42, 0.6);
      display: none;
      align-items: center;
      justify-content: center;
      z-index: 1000;
    }

    .modal-content {
      background: white;
      /* padding: 30px; */
      /* border-radius: 12px; */
      /* width: 400px; */
    }

    .form-group {
      margin-bottom: 15px;
    }

    .form-group label {
      display: block;
      margin-bottom: 5px;
      font-size: 13px;
      font-weight: 600;
    }

    .form-group input {
      width: 100%;
      padding: 8px;
      border: 1px solid #e2e8f0;
      border-radius: 6px;
      box-sizing: border-box;
    }

    .modal-actions {
      display: flex;
      justify-content: flex-end;
      gap: 10px;
      margin-top: 20px;
    }

    .btn-save {
      background: #10b981;
      color: white;
      border: none;
      padding: 8px 16px;
      border-radius: 6px;
      cursor: pointer;
    }

    .btn-cancel {
      background: #94a3b8;
      color: white;
      border: none;
      padding: 8px 16px;
      border-radius: 6px;
      cursor: pointer;
    }

    .htCore .htDimmed {
      color: #000000 !important;
    }

    .handsontable thead th {
      position: sticky;
      top: 0;
      z-index: 10;
    }

    .handsontable .ht_clone_left {
      z-index: 15;
    }

    .handsontable .htCommentCell:after {
      border-top: 8px solid red !important;
      border-left: 8px solid transparent !important;
    }

    .handsontable td.htCommentCell[style*="background-color: red"]:after,
    .handsontable td.htCommentCell[style*="background: red"]:after,
    .handsontable td.htCommentCell.bg-red:after {
      border-top: 8px solid #00FF00 !important;
    }

    /*  */
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
    <nav class="app-header navbar navbar-expand bg-body ">
      <div class="container-fluid">
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
              <i class="bi bi-list"></i>
            </a>
          </li>
        </ul>
        <?php
        $currentPage = 'pointage';
        include './components/profileNav.php';
        ?>
      </div>
    </nav>
    <?php
    $userRole = $role;
    $currentPage = 'pointage';
    include './components/sidebar.php';
    ?>
    <!--  -->
    <input type="hidden" id="sessionV" value="<?= $userRole  ?>">
    <!--  -->
    <main class="app-main">
      <div class="app-content-header">
        <div class="container-fluid">
        </div>
      </div>
      <div class="app-content">
        <div class="container-fluid">
          <div class="row">
            <div class="col-lg-12">
              <div class="card mb-4 card-primary card-outline">
                <div class="card-header border-0">
                  <div class="d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Pointage</h3>
                    <div class="d-flex gap-2">
                      <input id="periodePicker" type="month" class="form-control" min="2020-01" max="2030-12">
                      <?php if ($userRole == 'A'): ?>
                        <button type="button" id="export-file" class="btn btn-secondary mb-2"
                          style="margin-bottom: 0px !important;display: flex;flex-direction: row;justify-content: center; align-items: center; gap: 5px;">Exporter</button>
                      <?php endif; ?>
                    </div>
                  </div>
                </div>

                <div class="card-body " style="padding-top: 0px !important;">
                  <!-- d-flex flex-column -->
                  <div id="grid" style="flex: 3;"></div>

                  <!-- <div style="flex: 1; height: 200px; overflow-y: scroll;" >
                    <div class="timeline mt-4"> </div>
                  </div> -->

                </div>



              </div>


              <?php if ($userRole == 'A'): ?>
                <div class="card mb-4 card-primary card-outline collapsed-card">
                  <div class="card-header border-0">
                    <div class="d-flex justify-content-between align-items-center">
                      <h3 class="card-title">Historique</h3>
                      <button type="button" class="btn btn-tool" data-lte-toggle="card-collapse">
                        <i data-lte-icon="expand" class="bi bi-plus-lg"></i>
                        <i data-lte-icon="collapse" class="bi bi-dash-lg"></i>
                      </button>
                    </div>
                  </div>

                  <div class="card-body pt-0" style="padding-right: 5px !important;padding-left: 10px !important;padding-top: 0px !important;">
                    <!-- d-flex flex-column -->
                    <!-- <div id="grid" style="flex: 3;"></div> -->

                    <div style="flex: 1; height: 300px; overflow-y: scroll;">
                      <div class="timeline mt-0"> </div>
                    </div>

                  </div>

                </div>
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

      function handleSidebar() {
        const width = window.innerWidth;

        if (width < 992) {
          body.classList.add('sidebar-collapse');
          body.classList.remove('sidebar-open');
        } else {
          body.classList.remove('sidebar-collapse');
        }
      }
      handleSidebar();
      window.addEventListener('resize', handleSidebar);
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

      document.getElementById('periodePicker').addEventListener('change', function() {
        loadData();
      });

      function getSelectedPeriod() {
        const val = document.getElementById('periodePicker').value;
        const [year, month] = val.split('-');
        return {
          year: parseInt(year),
          month: parseInt(month)
        };
      }




      const picker = document.getElementById('periodePicker');

      const now = new Date();
      const year = now.getFullYear();
      const month = String(now.getMonth() + 1).padStart(2, '0');

      const currentPeriod = `${year}-${month}`;
      picker.value = currentPeriod;

      loadData();
      loadTimeline();
    });
  </script>
  <script src="./assets/js/Sortable.min.js" crossorigin="anonymous"></script>
  <script src="./assets/js/apexcharts.min.js"></script>
  <script src="./assets/js/jsvectormap.min.js"></script>
  <script src="./assets/js/world.js"></script>
  <!------->
  <script src="./assets/js/xlsx.full.min.js"></script>
  <script src="./assets/js/jquery-3.7.0.min.js"></script>
  <!------->
  <script src="./assets/js/sweetalert2@11.js"></script>





  <script>
    /**
     * DYNAMIC CALENDAR HELPERS
     */
    function getDaysInMonth(year, month) {
      // month is 1-based (1 = Jan, 2 = Feb...)
      return new Date(year, month, 0).getDate();
    }

    function getDayLetter(year, month, day) {
      const date = new Date(year, month - 1, day);
      const days = ["D", "L", "M", "M", "J", "V", "S"];
      return days[date.getDay()];
    }

    function getSelectedPeriod() {
      const picker = document.getElementById('periodePicker');
      let val = picker.value;
      if (!val) {
        const now = new Date();
        const currentYear = now.getFullYear();
        const currentMonth = String(now.getMonth() + 1).padStart(2, '0');
        val = `${currentYear}-${currentMonth}`;
        picker.value = val;
      }
      const [year, month] = val.split('-');
      return {
        year: parseInt(year),
        month: parseInt(month),
      };
    }

    function generateHeaders() {
      const {
        year,
        month,
      } = getSelectedPeriod();

      // This trick gets the last day of the month
      const numDays = new Date(year, month, 0).getDate();

      const headerRow1 = ['', '', {
        label: 'Agent',
        colspan: 2
      }, ''];
      for (let i = 1; i <= numDays; i++) {
        headerRow1.push({
          label: String(i),
          colspan: 1
        });
      }

      const headerRow2 = ['Sté', 'ID', 'Nom', 'Prénom', 'Campagne'];
      for (let i = 1; i <= numDays; i++) {
        headerRow2.push(getDayLetter(year, month, i));
      }

      // These columns now follow whatever numDays is
      headerRow2.push('Jours', 'Assiduité', 'Avance', 'Prime', 'CDP', 'Remarque');

      return {
        headerRow1,
        headerRow2,
        numDays
      };
    }

    /**
     * RENDERER: Dynamic Styles & ReadOnly Logic
     */
    function pointageRenderer(instance, td, row, col, prop, value, cellProperties) {
      Handsontable.renderers.TextRenderer.apply(this, arguments);

      const {
        year,
        month
      } = getSelectedPeriod();
      const numDays = getDaysInMonth(year, month);
      const physicalRow = instance.toPhysicalRow(row);
      const rowData = instance.getSourceDataAtRow(physicalRow);
      const lastColumnsStart = 5 + numDays;

      if (!rowData) return td;

      // Lock existing Agent info (Col 0-4) jdida
      // if (col < 5 && rowData[1]) {
      //   cellProperties.readOnly = true;
      // } else {
      //   cellProperties.readOnly = false;
      // }
      //old one 
      const ste = rowData[0];
      const idFiscal = rowData[1];
      const nom = rowData[2];
      const prenom = rowData[3];
      const comp = rowData[4];
      const isRowEmpty = (!ste || ste == '') ||
        (!idFiscal || idFiscal == '') ||
        (!nom || nom == '') ||
        (!prenom || prenom == '') ||
        (!comp || comp == '');

      if (col < 5 && !isRowEmpty) {
        cellProperties.readOnly = true;
      } else {
        cellProperties.readOnly = false;
      }

      const sessionV = document.getElementById('sessionV').value;
      if (sessionV === 'U' || sessionV === 'C' || sessionV === 'M') {
        cellProperties.readOnly = true;
      }
      // const isRowEmpty = !rowData[1];
      // if (col < 5) {
      //   cellProperties.readOnly = !isRowEmpty;
      // }
      //
      let classes = ['htCenter', 'htMiddle'];
      if (col >= 5 && col < lastColumnsStart) {
        // Color Logic for Status
        if (value === 'A') classes.push('bg-red');
        else if (value === 'SB') classes.push('bg-purple');
        else if (value === 'C') classes.push('bg-light-green');

        // Weekend Styling (Saturday/Sunday)
        else if (col >= 5 && col < (5 + numDays)) {
          const day = col - 4;
          const dayOfWeek = new Date(year, month - 1, day).getDay();
          if (dayOfWeek === 0) classes.push('sun-cell');
          else if (dayOfWeek === 6) classes.push('sat-cell');
          else classes.push('white-cell');
        }
      } else {
        classes.push('white-cell');
      }

      td.className = classes.join(' ');
      return td;
    }

    /**
     * INITIALIZATION
     */
    let isLoadingComments = false;
    const sessionV = document.getElementById('sessionV').value;
    const initialHeaders = generateHeaders();

    const hot = new Handsontable(document.getElementById('grid'), {
      id: 'main_pointage_table',
      data: [],
      width: '100%',
      height: sessionV === 'A' ? 400 : 510,
      minSpareRows: 1,
      rowHeaders: true,
      colHeaders: true,
      nestedHeaders: [initialHeaders.headerRow1, initialHeaders.headerRow2],
      fixedColumnsLeft: 5,
      className: 'htCenter htMiddle',
      licenseKey: 'non-commercial-and-evaluation',
      columnSorting: true,
      filters: true,
      comments: true,
      persistentState: true, //wash n7iydha?
      manualColumnResize: true,
      manualRowResize: false,
      search: true,
      autoColumnSize: true,
      renderer: pointageRenderer,
      dropdownMenu: [
        'filter_by_condition',
        'filter_by_value',
        'filter_operators',
        '---------',
        'filter_action_bar',
      ],
      contextMenu: {
        items: {
          "row_above": {
            name: "Insérer ligne au-dessus"
          },
          "row_below": {
            name: "Insérer ligne au-dessous"
          },
          "sp1": "---------",
          "commentsAddEdit": {
            name: "Ajouter une note",
            disabled() {
              const selection = this.getSelectedLast();
              return selection && selection[1] < 5;
            }
          },
          "commentsRemove": {
            name: "Effacer la note",
            disabled() {
              const selection = this.getSelectedLast();
              return selection && selection[1] < 5;
            }
          },
          "sp2": "---------",
          "remove_row": {
            name: "Supprimer la ligne"
          },
        }
      },


      colWidths(index) {
        if (index === 0) return 10;
        if (index === 1) return 30;
        if (index === 4) return 110;
        if (index >= 5 && index < 36 /*35 selon mois*/ ) return 35;
        return 100;
      },

      afterColumnSort(currentSortConfig) {
        if (currentSortConfig && currentSortConfig.length > 0) {
          localStorage.setItem('grid_sort_settings', JSON.stringify(currentSortConfig[0]));
        } else {
          localStorage.removeItem('grid_sort_settings');
        }
        this.render();
      },

      afterFilter(conditionsStack) {
        localStorage.setItem('grid_filter_settings', JSON.stringify(conditionsStack));
        this.render();
      },
      // Weekend Header Coloring
      afterGetColHeader(col, TH) {
        const {
          year,
          month
        } = getSelectedPeriod();
        const numDays = getDaysInMonth(year, month);
        if (col >= 5 && col < 5 + numDays) {
          const day = col - 4;
          const dayOfWeek = new Date(year, month - 1, day).getDay();
          if (dayOfWeek === 6) TH.classList.add('col-s');
          if (dayOfWeek === 0) TH.classList.add('col-d');
        }
      },

      // Data Synchronization
      async afterChange(changes, source) {
        if (['loadData', 'updateCalcul'].includes(source) || !changes) return;

        const isBulk = changes.length > 1;
        const {
          year,
          month
        } = getSelectedPeriod();
        const numDays = getDaysInMonth(year, month);
        const bulkData = [];

        for (const [row, col, oldVal, newVal] of changes) {
          if (oldVal === newVal) continue;

          const idFiscal = this.getDataAtCell(row, 1);
          if (!idFiscal) continue;

          // If editing pointage days
          if (col >= 5 && col < numDays + 5) {
            const total = calculateTotalJours(row);
            this.setDataAtCell(row, numDays + 5, total, 'updateCalcul'); // Note: index shifted based on numDays

            if (isBulk) {
              // Collect data for Bulk
              bulkData.push({
                agent_id_fiscal: idFiscal,
                jour_index: col - 4,
                valeur: newVal
              });
            } else {
              await fetch(`../api/api.php?action=save_pointage&mois=${month}&annee=${year}`, {
                method: 'POST',
                body: JSON.stringify({
                  idFiscal,
                  day: col - 4,
                  valeur: newVal
                })
              });
            }
          } else {
            await syncAgentRow(row, [row, col, oldVal, newVal]);
          }
        }

        // 2. BULK FETCH: This must be OUTSIDE the for loop
        if (isBulk && bulkData.length > 0) {
          await fetch(`../api/api.php?action=save_bulk_pointage&mois=${month}&annee=${year}`, {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json'
            },
            body: JSON.stringify({
              updates: bulkData,
              count: bulkData.length
            })
          });
        }

        loadTimeline();
      },

      afterSetCellMeta: async function(row, col, key, value) {
        if (isLoadingComments) return;
        if (key === 'comment') {
          const idFiscal = this.getDataAtCell(row, 1);
          if (!idFiscal) return;

          const period = getSelectedPeriod();
          const commentText = (value && value.value) ? value.value : null;
          await fetch(`../api/api.php?action=save_comment&mois=${period.month}&annee=${period.year}`, {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json'
            },
            body: JSON.stringify({
              idFiscal: idFiscal,
              day: col - 4,
              comment: commentText
            })
          });

          // loadTimeline();
        }
      },

      beforeRemoveRow: async function(index, amount) {
        const idsToDelete = [];
        for (let i = 0; i < amount; i++) {
          const id = this.getDataAtCell(index + i, 1);
          if (id) idsToDelete.push(id);
        }

        if (idsToDelete.length === 0) return true;

        const msg = idsToDelete.length === 1 ?
          `Voulez-vous vraiment supprimer l'agent ${idsToDelete[0]} ?` :
          `Voulez-vous vraiment supprimer ces ${idsToDelete.length} Utilisateurs ?`;

        if (confirm(msg)) {
          try {
            const requests = idsToDelete.map(id =>
              fetch(`../api/api.php?action=delete_agent&idFiscal=${id}`)
            );
            await Promise.all(requests);
            return true;
          } catch (err) {
            Swal.fire({
              icon: "error",
              title: "Oops...",
              text: "Erreur lors de la suppression groupée." + err,
            });
            return false;
          }
        }
        return false;
      }

    });

    /**
     * DATA LOADING
     */
    async function loadData() {
      isLoadingComments = true;
      const {
        year,
        month
      } = getSelectedPeriod();

      // 1. Refresh Dynamic Headers
      const newHeaders = generateHeaders();
      hot.updateSettings({
        nestedHeaders: [newHeaders.headerRow1, newHeaders.headerRow2]
      });


      // 2. Fetch Data
      const res = await fetch(`../api/api.php?action=fetch&annee=${year}&mois=${month}`);
      const data = await res.json();
      // if (!data || data.length === 0) {
      //   data = [new Array(newHeaders.headerRow2.length).fill('')];
      // }
      let tableData = data.map(item => item.data);


      if (tableData.length === 0) {
        //zdtha
        const numDays = getDaysInMonth(year, month);
        tableData.push(new Array(numDays + 11).fill(''));
      }

      hot.loadData(tableData);

      // 3. Load Comments
      const commentsPlugin = hot.getPlugin('comments');
      data.forEach((item, rowIndex) => {
        item.comments.forEach((commentText, dayIdx) => {
          if (commentText && commentText.trim() !== "") {
            commentsPlugin.setCommentAtCell(rowIndex, dayIdx + 5, commentText);
          }
        });
      });

      hot.render();
      setTimeout(() => {
        isLoadingComments = false;
      }, 100);

      //hadu 3ad rj3thom
      const savedFilters = localStorage.getItem('grid_filter_settings');
      if (savedFilters) {
        const filtersPlugin = hot.getPlugin('filters');
        const stack = JSON.parse(savedFilters);
        filtersPlugin.removeConditions();
        stack.forEach(item => {
          item.conditions.forEach(cond => {
            filtersPlugin.addCondition(item.column, cond.name, cond.args);
          });
        });
        filtersPlugin.filter();
      }

      const savedSort = localStorage.getItem('grid_sort_settings');
      if (savedSort) {
        const sortConfig = JSON.parse(savedSort);
        hot.getPlugin('columnSorting').sort(sortConfig);
      }
      //////
    }


    /**
     * CALCULATIONS
     */
    function calculateTotalJours(row) {
      const {
        year,
        month
      } = getSelectedPeriod();
      const numDays = getDaysInMonth(year, month);
      let total = 0;

      for (let i = 0; i < numDays; i++) {
        const valRaw = hot.getDataAtCell(row, 5 + i);
        const val = parseFloat(String(valRaw).replace(',', '.')) || 0;

        // Logic: Weekends usually 4h, Weekdays 8h? 
        // Adjust divisor based on your company rules
        const dayOfWeek = new Date(year, month - 1, i + 1).getDay();
        const divisor = (dayOfWeek === 0 || dayOfWeek === 6) ? 4 : 8;
        total += (val / divisor);
      }
      return total.toFixed(2);
    }

    //old
    // function calculateTotalJours(row) {
    //       const data = hot.getSourceDataAtRow(row);
    //       let total = 0;
    //       for (let i = 0; i < numDays; i++) {
    //         const valRaw = hot.getDataAtCell(row, 5 + i);
    //         const val = parseFloat(String(valRaw).replace(',', '.')) || 0;
    //         const divisor = ((i % 7) === 0) ? 4 : 8;
    //         total += (val / divisor);
    //       }

    //       const period = getSelectedPeriod();
    //       const agent = {
    //         ste: data[0],
    //         idFiscal: data[1],
    //         nom: data[2],
    //         prenom: data[3],
    //         campagne: data[4],
    //         totalJours: total,
    //         assiduite: data[36],
    //         avance: data[37],
    //         prime: data[38],
    //         cdp: data[39],
    //         remarque: data[40],
    //         mois: period.month,
    //         annee: period.year,
    //         updatedRow: 'totalJours'
    //       };

    //       fetch(`../api/api.php?action=add_agent&mois=${period.month}&annee=${period.year}`, {
    //         method: 'POST',
    //         body: JSON.stringify(agent),
    //         headers: {
    //           'Content-Type': 'application/json'
    //         }
    //       })
    //         .then(res => res.json())
    //       return total.toFixed(2);
    //     }

    // Event Listener for the Period Picker
    // document.getElementById('periodePicker').addEventListener('change', loadData);

    // Initial Load
    // loadData();
    // loadTimeline();

    // Helper for syncing
    async function syncAgentRow(row, change) {
      const {
        year,
        month
      } = getSelectedPeriod();
      const numDays = getDaysInMonth(year, month);

      const columnNames = {
        0: 'STE',
        1: 'ID Fiscal',
        2: 'Nom',
        3: 'Prénom',
        4: 'Campagne',
        [numDays + 5 + 0]: 'totalJours',
        [numDays + 5 + 1]: 'Assiduité',
        [numDays + 5 + 2]: 'Avance',
        [numDays + 5 + 3]: 'Prime',
        [numDays + 5 + 4]: 'CDP',
        [numDays + 5 + 5]: 'Remarque'
      };
      const [row_, col, oldVal, newVal] = change;
      const columnName = columnNames[col] || `Colonne ${col}`;
      const data = hot.getDataAtRow(row);
      const agent = {
        ste: data[0] || '',
        idFiscal: data[1] || '',
        nom: (data[2] || '').toUpperCase(),
        prenom: data[3] || '',
        campagne: data[4] || '',
        totalJours: data[numDays + 5] || 0, // Adjusted index Alae checkkk
        assiduite: data[numDays + 6] || 0,
        avance: data[numDays + 7] || 0,
        prime: data[numDays + 8] || 0,
        cdp: data[numDays + 9] || 0,
        remarque: data[numDays + 10] || 0,
        updatedRow: columnName,
        mois: month,
        annee: year
      };
      // return
      if (agent.idFiscal) {
        await fetch(`../api/api.php?action=add_agent&mois=${month}&annee=${year}`, {
          method: 'POST',
          body: JSON.stringify(agent)
        });
      }
    }
    //old
    //  async function syncAgentRow(row, changes) {
    //   const columnNames = {
    //     0: 'STE',
    //     1: 'ID Fiscal',
    //     2: 'Nom',
    //     3: 'Prénom',
    //     4: 'Campagne',
    //     36: 'Assiduité',
    //     37: 'Avance',
    //     38: 'Prime',
    //     39: 'CDP',
    //     40: 'Remarque'
    //   };

    //   const [row_, col, oldVal, newVal] = changes;
    //   const columnName = columnNames[col] || `Colonne ${col}`;
    //   const period = getSelectedPeriod();

    //   const data = hot.getDataAtRow(row);
    //   const agent = {
    //     ste: data[0] || '',
    //     idFiscal: data[1] || '',
    //     nom: data[2] ? data[2].toUpperCase() : '',
    //     prenom: data[3] || '',
    //     campagne: data[4] || '',
    //     totalJours: data[35] || 0,
    //     assiduite: data[36] || '',
    //     avance: data[37] || '',
    //     prime: data[38] || '',
    //     cdp: data[39] || '',
    //     remarque: data[40] || '',
    //     mois: period.month,
    //     annee: period.year,
    //     updatedRow: columnName
    //   };
    //   if (agent.idFiscal) {
    //     await fetch(`../api/api.php?action=add_agent&mois=${period.month}&annee=${period.year}`, {
    //       method: 'POST',
    //       body: JSON.stringify(agent)
    //     });
    //   }
    // }

    async function loadTimeline(idFiscal = null) {
      const container = document.querySelector('.timeline');
      if (!container) return;

      let url = '../api/api.php?action=fetch_history';
      if (idFiscal) url += `&idFiscal=${encodeURIComponent(idFiscal)}`;

      try {
        const response = await fetch(url);
        if (!response.ok) throw new Error('Network response was not ok');

        const logs = await response.json();

        let html = '';
        let lastDate = '';


        // let unwantedL = logs.filter(log =>
        //   log.action === "update_agent_Prénom" ||
        //   log.action === "update_agent_Nom" ||
        //   log.action === "update_agent_ID Fiscal" ||
        //   log.action === "update_agent_Campagne" ||
        //   log.action === "update_agent_STE"
        // ).length

        if (logs.length === 0 /*|| unwantedL == logs.length*/ ) {
          container.innerHTML = '<div class="text-center p-3">Aucun historique trouvé.</div>';
          return;
        }

        logs.forEach(log => {
          const authorName = `${log.author_nom || ''} ${log.author_prenom || ''}`.trim() || log.updated_by;
          const targetName = `${log.target_nom || ''} ${log.target_prenom || ''}`.trim() || log.idFiscal;

          const dateOptions = {
            day: 'numeric',
            month: 'short',
            year: 'numeric'
          };
          const currentDate = new Date(log.created_at).toLocaleDateString('fr-FR', dateOptions);

          if (currentDate !== lastDate) {
            html += `
      <div class="time-label">
        <span class="text-bg-danger">${currentDate}</span>
      </div>`;
            lastDate = currentDate;
          }

          const periode = `${String(log.mois).padStart(2, '0')}/${log.annee}`;

          let detailsObj = {};
          try {
            detailsObj = JSON.parse(log.details);
          } catch (e) {
            detailsObj = {};
          }

          let config = {};

          if (log.action === 'save_pointage') {
            const day = detailsObj.day || '?';
            const val = detailsObj.valeur || '';

            config = {
              icon: 'bi-calendar-check',
              color: 'text-bg-warning',
              message: `<a href="javascript:void(0);"><strong>${authorName}</strong></a> a mis à jour le pointage de <strong>${targetName}</strong> pour la periode du
                        <small class="badge text-bg-secondary">${periode}</small>
                        <span class="text-muted">Jour ${day} pointé à : </span>
                        <small class="badge text-bg-secondary ms-1">${val}</small>`
            };
          } else if (log.action == "export_pointage") {
            const day = detailsObj.day || '?';
            const val = detailsObj.valeur || '';
            config = {
              icon: 'bi-download',
              color: 'text-bg-success',
              message: `<a href="javascript:void(0);"><strong>${authorName}</strong></a> a téléchargé le pointage de la periode <small class="badge text-bg-secondary">${periode}</small>`
            };
          } else if (log.action === 'save_bulk_pointage') {
            const From = detailsObj.From || '';
            const To = detailsObj.To || '';
            const Count = detailsObj.Count || '';

            config = {
              icon: 'bi-calendar-range',
              color: 'text-bg-warning',
              message: `<a href="javascript:void(0);"><strong>${authorName}</strong></a> a mis à jour <strong>${Count} jours</strong> de pointage pour <strong>${targetName}</strong> pour la periode du
                        <small class="badge text-bg-secondary">${periode}</small>
                        <span class="text-muted">Du Jour  <small class="badge text-bg-secondary">${From}</small> au  <small class="badge text-bg-secondary">${To}</small></span>`
            };
          }
          // else if (log.action == "update_agent_Prénom" || log.action == "update_agent_Nom" || log.action == "update_agent_Campagne" || log.action == "update_agent_ID Fiscal" || log.action == "update_agent_STE") {
          //   // console.log(log.action,'-----')
          //   return
          // }
          // else if (log.action == "update_agent_totalJours") {
          //   // console.log(log.action,'-----')
          //   return
          // }
          else {
            const actionText = log.action === 'add_agent' ? 'créé' : 'mis à jour';
            const color = log.action === 'add_agent' ? 'text-bg-success' : 'text-bg-info';
            const icon = log.action === 'add_agent' ? 'bi-person-plus' : 'bi-person-gear';
            let last = log?.action?.split('_').pop().toLowerCase() ?? '_invalide';
            let val;
            //= last == "assiduité" ? detailsObj['assiduite'] : "commentaire" ? detailsObj['comment'] : detailsObj[last];
            if (last == "assiduité") {
              val = detailsObj['assiduite']
            } else if (last == "commentaire") {
              val = detailsObj['comment']
            } else {
              val = detailsObj[last]
            }
            const capitalized = last.charAt(0).toUpperCase() + last.slice(1);
            let _text = '';
            if (last == 'assiduité' || last == 'avance') {
              _text = 'l\'' + last
            } else if (last == 'prime' || last == 'remarque') {
              _text = 'la ' + last
            } else {
              _text = 'le ' + last
            }
            config = {
              icon: icon,
              color: color,
              message: `<a href="javascript:void(0);"><strong>${authorName}</strong></a> a ${actionText + ' ' + _text} de l'agent <strong>${targetName}</strong> pour la periode du
                      <small class="badge text-bg-secondary ms-1">${periode}</small>
                      <span class="text-muted">${capitalized} : </span>
                      <small class="badge text-bg-secondary ms-1">${val}</small>`
            };
          }

          html += `
        <div>
          <i class="timeline-icon bi ${config.icon} ${config.color} mt-1"></i>
          <div class="timeline-item shadow-sm">
            <span class="time"><i class="bi bi-clock-fill"></i> ${log.log_time.substring(0, 5)}</span>
            <div class="timeline-header no-border">
              ${config.message}
            </div>
          </div>
        </div>`;
        });

        html += `
      <div>
        <i class="timeline-icon bi bi-clock-fill text-bg-secondary"></i>
      </div>`;

        container.innerHTML = html;

      } catch (error) {
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: "Erreur lors du chargement de la timeline:" + error,
        });
        container.innerHTML = '<div class="text-danger p-3">Erreur de chargement de l\'historique.</div>';
      }
    }

    // async function saveAgent() {
    //   const period = getSelectedPeriod();
    //   const agent = {
    //     ste: document.getElementById('m_ste').value,
    //     idFiscal: document.getElementById('m_id').value,
    //     nom: document.getElementById('m_nom').value.toUpperCase(),
    //     prenom: document.getElementById('m_prenom').value,
    //     campagne: document.getElementById('m_camp').value
    //   };
    //   if (!agent.idFiscal) return

    //   Swal.fire({
    //     icon: "error",
    //     title: "Oops...",
    //     text: "ID requis",
    //   });


    //   await fetch(`../api/api.php?action=add_agent&mois=${period.month}&annee=${period.year}`, {
    //     method: 'POST',
    //     body: JSON.stringify(agent)
    //   });
    //   closeModal();
    //   // loadData();
    // }

    // function openModal() {
    //   document.getElementById('modal').style.display = 'flex';
    // }

    // function closeModal() {
    //   document.getElementById('modal').style.display = 'none';
    //   ['m_ste', 'm_id', 'm_nom', 'm_prenom', 'm_camp'].forEach(id => document.getElementById(id).value = '');
    // }

    // function toggleMenu() {
    //   const menu = document.getElementById('dropdown');
    //   menu.style.display = menu.style.display === 'none' ? 'block' : 'none';
    // }
    ///
    const exportBtn = document.getElementById('export-file');
    //old jdid lih jeda wl9dima la tfrt fih 
    // exportBtn.addEventListener('click', () => {
    //   // const tableData = hot.getData();old
    //   //-----------------
    //   exportBtn.disabled = true;
    //   const originalContent = exportBtn.innerHTML;
    //   exportBtn.innerHTML = `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Exportation...`;
    //   //-----------
    //   const rawData = hot.getData();

    //   // Map the grid values and check internal Cell Meta for comments
    //   const tableDataWithComments = rawData.map((row, rowIndex) => {
    //     return row.map((cellValue, colIndex) => {
    //       // This is the most reliable way to get cell-specific data
    //       const cellMeta = hot.getCellMeta(rowIndex, colIndex);
    //       const commentText = (cellMeta && cellMeta.comment && cellMeta.comment.value) ?
    //         cellMeta.comment.value :
    //         null;

    //       if (commentText && commentText.trim() !== "") {
    //         return {
    //           value: cellValue,
    //           comment: commentText
    //         };
    //       }
    //       return cellValue;
    //     });
    //   });


    //   //-----------------

    //   // Get both rows from your initialHeaders object
    //   const initialHeaders = generateHeaders();
    //   const nestedHeaders = {
    //     row1: initialHeaders.headerRow1,
    //     row2: initialHeaders.headerRow2
    //   };
    //   const form = document.createElement('form');
    //   form.method = 'POST';
    //   form.action = '../api/export_excel.php';

    //   const dataInput = document.createElement('input');
    //   dataInput.type = 'hidden';
    //   dataInput.name = 'table_data';
    //   dataInput.value = JSON.stringify(tableDataWithComments); //tableData old

    //   const headerInput = document.createElement('input');
    //   headerInput.type = 'hidden';
    //   headerInput.name = 'nested_headers'; // Changed name to reflect nested structure
    //   headerInput.value = JSON.stringify(nestedHeaders);

    //   form.appendChild(dataInput);
    //   form.appendChild(headerInput);
    //   ////
    //   const {
    //     year,
    //     month
    //   } = getSelectedPeriod();

    //   const monthInput = document.createElement('input');
    //   monthInput.type = 'hidden';
    //   monthInput.name = 'month';
    //   monthInput.value = month; // e.g., "Janvier"
    //   form.appendChild(monthInput);

    //   const yearInput = document.createElement('input');
    //   yearInput.type = 'hidden';
    //   yearInput.name = 'year';
    //   yearInput.value = year; // e.g., "2026"
    //   form.appendChild(yearInput);
    //   ////
    //   document.body.appendChild(form);
    //   form.submit();
    //   document.body.removeChild(form);

    //   //-----
    //   setTimeout(() => {
    //     exportBtn.disabled = false;
    //     exportBtn.innerHTML = originalContent;
    //   }, 3000);
    // });
    // // // 

    //---------------new 
    exportBtn.addEventListener('click', async () => {
      // 1. Get the settings from localStorage
      // const sortSettings = JSON.parse(localStorage.getItem('grid_sort_settings'));
      // const filterSettings = JSON.parse(localStorage.getItem('grid_filter_settings'));

      // 2. Check if they are active
      // We check if sortSettings exists/has length, and if filterSettings has keys
      // const isSorted = sortSettings && Object.keys(sortSettings).length > 0;
      // const isFiltered = filterSettings && Object.keys(filterSettings).length > 0;
      let timerInterval;
      Swal.fire({
        title: 'Génération de l\'export',
        html: '<span id="swal-status-text">Initialisation...</span>',
        allowOutsideClick: false,
        showConfirmButton: false,
        didOpen: () => {
          Swal.showLoading();
          const statusText = document.getElementById('swal-status-text');
          const messages = [
            "Chargement des données...",
            "Traitement des informations...",
            "Astuce : vous pouvez ouvrir une nouvelle fenêtre et continuer votre travail pendant le chargement...",
            "Calcul des heures en cours...",
            "Astuce : ce traitement peut prendre quelques minutes selon le volume des données...",
            "Structuration du tableau...",
            "Astuce : évitez de fermer la page pendant le traitement...",
            "Application des styles...",
            "Astuce : vous pouvez revenir plus tard pour consulter le résultat...",
            "Fusion des en-têtes...",
            "Ajout des finitions...",
            "Astuce : vous pouvez continuer à utiliser la plateforme pendant la génération...",
            "Vérification finale...",
            "Finalisation en cours...",
            "C'est presque fini, merci de patienter...",
            "Encore quelques secondes..."
          ];
          let i = 0;

          // Change message every 3 seconds
          timerInterval = setInterval(() => {
            statusText.innerText = messages[i];
            i = (i + 1) % messages.length;
          }, 6000);
        },
        willClose: () => {
          clearInterval(timerInterval);
        }
      });
      // 1. UI: Disable button and show loader
      const originalContent = exportBtn.innerHTML;
      exportBtn.disabled = true;
      exportBtn.innerHTML = `Exportation... <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>`;

      try {
        // 2. Prepare Data (Same logic you had)
        const rawData = hot.getData();
        const tableDataWithComments = rawData.map((row, rowIndex) => {
          return row.map((cellValue, colIndex) => {
            const cellMeta = hot.getCellMeta(rowIndex, colIndex);
            const commentText = (cellMeta && cellMeta.comment && cellMeta.comment.value) ?
              cellMeta.comment.value : null;

            if (commentText && commentText.trim() !== "") {
              return {
                value: cellValue,
                comment: commentText
              };
            }
            return cellValue;
          });
        });

        const initialHeaders = generateHeaders();
        const {
          year,
          month
        } = getSelectedPeriod();

        // 3. Send Request via Fetch
        const response = await fetch('../api/export_excel.php', /*export_excelV2*/ {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
          },
          body: new URLSearchParams({
            'table_data': JSON.stringify(tableDataWithComments),
            'nested_headers': JSON.stringify({
              row1: initialHeaders.headerRow1,
              row2: initialHeaders.headerRow2
            }),
            'month': month,
            'year': year
          })
        });
        if (!response.ok) throw new Error('Network response was not ok');
        //Alae
        let suffix = "";
        // if (isSorted) suffix += "_Sorted";
        // if (isFiltered) suffix += "_Filtered";
        let fileName = `Pointage_${month}_${year}${suffix}.xlsx`; // Fallback name
        const disposition = response.headers.get('Content-Disposition');

        if (disposition && disposition.indexOf('attachment') !== -1) {
          const filenameRegex = /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/;
          const matches = filenameRegex.exec(disposition);
          if (matches != null && matches[1]) {
            // let rawName = matches[1].replace(/['"]/g, '');
            // fileName = rawName.replace('.xlsx', `${suffix}.xlsx`);
            // Remove quotes if present
            fileName = matches[1].replace(/['"]/g, '');
          }
        }


        //
        // // 4. Handle File Download (Blob)
        const blob = await response.blob();
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        // Dynamically name the file (Optional: pull from response headers if you prefer)
        a.download = fileName; //`Pointage_CIO_${month}_${year}.xlsx`;

        document.body.appendChild(a);
        a.click();

        Swal.fire({
          icon: 'success',
          title: 'Export Prêt !',
          text: "Le processus d'exportation s'est terminé avec succès",
          timer: 2000,
          showConfirmButton: false
        });
        // Cleanup
        window.URL.revokeObjectURL(url);
        document.body.removeChild(a);
        loadTimeline();

      } catch (error) {
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: "Une erreur est survenue lors de l'exportation." + error,
        });
      } finally {
        // 5. UI: Re-enable button immediately when finished
        exportBtn.disabled = false;
        exportBtn.innerHTML = originalContent;
      }




      /////
    });
    //-----------
    function clearAllSettings() {
      localStorage.removeItem('grid_filter_settings');
      localStorage.removeItem('grid_sort_settings');
      localStorage.clear();
      location.reload();
    }

    // loadData();
    // loadTimeline();
  </script>
</body>

</html>
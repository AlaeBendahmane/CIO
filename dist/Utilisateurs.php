<!doctype html>
<html lang="fr">
<?php
ob_start();
include '../api/helpers.php';
include '../api/session_info.php';
isAuthPath();
isAdminPath();
ob_end_flush();
?>

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>CIO | Utilisateurs</title>

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
  <link rel="stylesheet" href="./assets/css/dataTables.bootstrap5.min.css">

  <link rel="stylesheet" href="./assets/css/responsive.bootstrap5.min.css">
  <script src="./assets/js/helper.js"></script>


  <style>
    /* @import url('./assets/css/css2.css'); */
    /* Specifically target the DataTables search input */
    .dataTables_filter input[type="search"] {
      /* background-color: transparent; */
      /* border: 2px solid transparent; */
      /* AdminLTE Blue */
      /* border-radius: 20px; */
      /* Rounded pill shape */
      padding: 0px 5px 0px 5px;
      color: #333;
      outline: none;
      transition: all 0.3s ease;
    }

    /* Change style when the user clicks inside */
    .dataTables_filter input[type="search"]:focus {
      /* width: 300px; */
      /* Expands on focus */
      /* background-color: #ffffff; */
      box-shadow: none;
      /* border-color: transparent; */
    }

    /* Style the "X" clear button that appears in Chrome/Safari */
    .dataTables_filter input[type="search"]::-webkit-search-cancel-button {
      -webkit-appearance: none;
      height: 14px;
      width: 14px;
      background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="%236c757d"><path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/></svg>') no-repeat 50% 50%;
      cursor: pointer;
    }

    .card-body.loading-state {
      height: 500px !important;
      overflow: hidden !important;
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
        $currentPage = 'Utilisateurs';
        include './components/profileNav.php';
        ?>
      </div>
    </nav>
    <?php
    $userRole = $role;
    $currentPage = 'Utilisateurs';
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
            <div class="col-md-12">
              <div class="card mb-4 card-primary card-outline">
                <div class="card-header">
                  <div class="d-flex justify-content-between">
                    <h3 class="card-title">Utilisateurs</h3>
                    <a href="javascript:void(0);" onclick="hideerror();changerole()"
                      class="link-primary link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover" data-bs-toggle="modal" data-bs-target="#staticBackdrop">Ajouter
                      un agent</a>
                  </div>
                </div>
                <div class="card-body">
                  <!-- <table class="table table-bordered table-striped" id="agentsTable"> -->
                  <table class="table table-bordered table-striped nowrap" id="agentsTable" style="width:100%">
                    <thead>
                      <tr>
                        <th>Immatricule</th>
                        <th>Id Proximus</th>
                        <th>Ste</th>
                        <th>Campagne</th>
                        <th>Nom</th>
                        <th>Prenom</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Actions</th>
                      </tr>
                    </thead>
                    <tbody id="tbodyagents">
                    </tbody>
                  </table>
                </div>
                <!-- <div class="card-footer clearfix" id="custom-footer">
                  <ul class="pagination pagination-sm m-0 float-end">
                    <li class="page-item">
                      <a class="page-link" href="#">&laquo;</a>
                    </li>
                    <li class="page-item">
                      <a class="page-link" href="#">1</a>
                    </li>
                    <li class="page-item">
                      <a class="page-link" href="#">2</a>
                    </li>
                    <li class="page-item">
                      <a class="page-link" href="#">3</a>
                    </li>
                    <li class="page-item">
                      <a class="page-link" href="#">&raquo;</a>
                    </li>
                  </ul>
                </div> -->
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>
  </div>
  <!--  -->

  <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="staticBackdropLabel">Modal title</h1>
          <!-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> -->
        </div>
        <div class="modal-body">

          <!--  -->
          <div class="row">
            <div class="col input-group mb-2">
              <div class="form-floating">
                <input id="idFiscal" type="number" class="form-control" value="" placeholder="" required />
                <label for="idFiscal">idFiscal</label>
              </div>
            </div>

            <div class="col input-group mb-2">
              <div class="form-floating">
                <input type="number" class="form-control" id="idProx" placeholder="Enter email">
                <label for="idProx">idProx</label>
              </div>
            </div>
          </div>

          <!--  -->
          <div class="row">
            <div class="col input-group mb-2">
              <div class="form-floating">
                <input type="text" class="form-control" id="nom" placeholder="Enter email" required>
                <label for="nom">nom </label>
              </div>
            </div>
            <div class="col input-group mb-2">
              <div class="form-floating">
                <input type="text" class="form-control" id="prenom" placeholder="Enter email" required>
                <label for="prenom">prenom</label>
              </div>
            </div>
          </div>
          <!--  -->
          <div class="row">
            <div class="col input-group mb-2">
              <div class="form-floating">
                <input type="email" class="form-control" id="email" placeholder="Enter email">
                <label for="email">email</label>
              </div>
            </div>
          </div>
          <!--  -->
          <div class="row">
            <?php include_once '../api/get_selects.php'; ?>
            <div class="col input-group mb-2">
              <div class="form-floating">
                <select class="form-select form-select-lg" id="ste" name="ste">
                  <option value="-1">---Select---</option>
                  <?php if (!empty($steList)): ?>
                    <?php foreach ($steList as $ste): ?>
                      <option value="<?= htmlspecialchars($ste['abreviation']) ?>">
                        <?= htmlspecialchars($ste['nomSte']) ?>
                      </option>
                    <?php endforeach; ?>
                  <?php endif; ?>
                </select>
                <label for="ste">Société</label>
              </div>
            </div>

            <div class="col input-group mb-2">
              <div class="form-floating">
                <select class="form-select form-select-lg" id="campagne" name="campagne">
                  <option value="-1">---Select---</option>
                  <?php if (!empty($compagneList)): ?>
                    <?php foreach ($compagneList as $cp): ?>
                      <option value="<?= htmlspecialchars($cp['abreviation']) ?>">
                        <?= htmlspecialchars($cp['nomCompagne']) ?>
                      </option>
                    <?php endforeach; ?>
                  <?php endif; ?>
                </select>
                <label for="campagne">Campagne</label>
              </div>
            </div>

            <div class="col input-group mb-2">
              <div class="form-floating">
                <select class="form-select form-select-lg" name="role" id="role-select">
                  <option value="-1">---Select---</option>
                  <?php if (!empty($rolesList)): ?>
                    <?php foreach ($rolesList as $role): ?>
                      <option value="<?= htmlspecialchars($role['abreviation']) ?>">
                        <?= htmlspecialchars($role['nomRole']) ?>
                      </option>
                    <?php endforeach; ?>
                  <?php endif; ?>
                </select>
                <label for="role-select">Rôle</label>
              </div>
            </div>
            <div style="display: flex;justify-content: center; align-items: center; color: red;" id="errorinsert"></div>
          </div>
          <!--  -->
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="clearinputs()">Fermer</button>
          <button type="button" class="btn btn-primary" onclick="saveAgent()" id="actionTodo" data-action="save">Enregistrer</button>
        </div>
      </div>
    </div>
  </div>
  <!--  -->
  <script src="./assets/js/overlayscrollbars.browser.es6.min.js"
    crossorigin="anonymous"></script>
  <script src="./assets/js/popper.min.js"
    crossorigin="anonymous"></script>
  <script src="./assets/js/bootstrap.min.js" crossorigin="anonymous"></script>
  <script src="./assets/js/adminlte.min.js"></script>
  <script src="./assets/js/jquery-3.7.0.min.js"></script>
  <script src="./assets/js/jquery.dataTables.min.js"></script>
  <script src="./assets/js/dataTables.bootstrap5.min.js"></script>

  <script src="./assets/js/dataTables.responsive.min.js"></script>
  <script src="./assets/js/responsive.bootstrap5.min.js"></script>
  <!--  -->
  <script src="./assets/js/sweetalert2@11.js"></script>
  <!--  -->
  <script>
    const SELECTOR_SIDEBAR_WRAPPER = '.sidebar-wrapper';
    const Default = {
      scrollbarTheme: 'os-theme-light',
      scrollbarAutoHide: 'leave',
      scrollbarClickScroll: true,
    };




    document.addEventListener('DOMContentLoaded', function() {
      fetch('../api/get_agents.php')
        .then(res => res.json())
        .then(data => {
          if (data.status === 'success') {
            const tbody = document.querySelector('#agentsTable tbody');
            const cardBody = document.querySelector('.card-body');
            cardBody.classList.add('loading-state');
            tbody.innerHTML = '';

            data.data.forEach((agent) => {
              // Note: Fixed the typo agent?.idFiscals -> agent?.idFiscal
              const row = `
            <tr class="align-middle">
              <td>${agent?.idFiscal || ''}</td>
              <td>${agent?.idProx || ''}</td>
              <td>${agent?.ste || ''}</td>
              <td>${agent?.campagne || ''}</td>
              <td>${agent?.nom || ''}</td>
              <td>${agent?.prenom || ''}</td>
              <td>${agent?.email || ''}</td>
              <td>${agent?.role || ''}</td>
              <td>
                <button class="btn btn-sm btn-primary me-1 edit-btn" data-bs-toggle="modal" data-bs-target="#staticBackdrop" data-id="${agent.id}">
                  <i class="bi bi-pencil"></i>
                </button>
                <button class="btn btn-sm btn-warning btn-reset" data-id="${agent?.id}" data-name="${agent?.nom} ${agent?.prenom}" style="color:#fff">
                 <i class="bi bi-shield-lock"></i>
                </button>  
                <button class="btn btn-sm btn-danger btn-delete" data-id="${agent?.id}" data-name="${agent?.nom} ${agent?.prenom}">
                  <i class="bi bi-trash"></i>
                </button>
              </td>
            </tr>`;
              tbody.insertAdjacentHTML('beforeend', row);
            });

            $('#agentsTable').DataTable({
              "responsive": true,
              "pageLength": 8,
              "drawCallback": function(settings) {
                // This forces the responsive plugin to recalculate whenever 
                // the table "draws" (pagination, length change, or search)
                $(this).DataTable().responsive.recalc();
              },
              "initComplete": function(settings, json) {
                // Use the API instance to adjust columns
                cardBody.classList.remove('loading-state');
                this.api().columns.adjust().responsive.recalc();
              },
              "lengthMenu": [
                [8, 25, 50, -1],
                [9, 25, 50, "Tout"]
              ],
              "language": {
                "url": "./assets/js/fr-FR.json"
              },
              "columnDefs": [{
                  "responsivePriority": 1,
                  "targets": 0
                }, // Always show Immatricule
                {
                  "responsivePriority": 2,
                  "targets": 6
                }, // Always show Nom
                {
                  "responsivePriority": 3,
                  "targets": -1
                } // Always show Actions
              ]
            });

          } else {
            Swal.fire({
              icon: "error",
              title: "Oops...",
              text: 'Error fetching agents:' + data.message,
            });
          }
        })
        .catch(err =>
          Swal.fire({
            icon: "error",
            title: "Oops...",
            text: 'Fetch error:' + err,
          })
        );


      // loadRoles()

      // ... rest of your sidebar/resize logic ...
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
    });
    let updatedAgent = 0;
    $(document).on('click', '.edit-btn', function() {

      // Correct
      document.getElementById('actionTodo').setAttribute('data-action', 'edit');


      const id = $(this).data('id');
      updatedAgent = id
      const table = $('#agentsTable').DataTable();

      // Find the row data using the DataTables API
      const agent = table.row($(this).closest('tr')).data();



      // Check if the data is an array or object (depends on how you loaded the table)
      // If you loaded as objects:
      document.getElementById('idFiscal').value = agent[0];
      document.getElementById('idProx').value = agent[1];
      document.getElementById('ste').value = agent[2];
      document.getElementById('campagne').value = agent[3];
      document.getElementById('nom').value = agent[4];
      document.getElementById('prenom').value = agent[5];
      document.getElementById('email').value = agent[6];
      const roleMap = {
        "Agent": "U",
        "Coach": "C",
        "Administrateur": "A",
        "Mex": "M"
      };
      let finalValue = roleMap[agent[7]] || "-1";
      document.getElementById('role-select').value = finalValue;

      // Make idFiscal read-only since it's the primary key
      // document.getElementById('idFiscal').readOnly = true;
    });

    $(document).on('click', '.btn-delete', async function() {
      const idFiscal = $(this).data('id');
      const name = $(this).data('name');
      const buttonElement = $(this);
      Swal.fire({
        title: `Êtes-vous sûr de vouloir supprimer l'agent : ${name} ?`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#1ed760",
        cancelButtonColor: "#d33",
        confirmButtonText: "Oui, supprimer",
        cancelButtonText: "Annuler"
      }).then(async (resultconfirm) => {
        if (resultconfirm.isConfirmed) {
          try {
            // 2. Call the Soft Delete API
            const response = await fetch(`../api/delete_agent.php?id=${idFiscal}`);
            const result = await response.json();

            if (result.success) {
              // 3. Remove from DataTables without refreshing
              const table = $('#agentsTable').DataTable();

              // // We use the button element to find the specific row (tr) it belongs to
              table.row($(buttonElement).parents('tr'))
                .remove()
                .draw(false);

              Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: `Agent ${name} déplacé vers la corbeille.`,
                showConfirmButton: false,
                timer: 3000
              });
            } else {
              Swal.fire({
                icon: "error",
                title: "Oops...",
                text: result.message,
              });
            }
          } catch (error) {
            Swal.fire({
              icon: "error",
              title: "Oops...",
              text: "Impossible de contacter le serveur.",
            });
          }
        } else {
          return
        }

      });
    });

    $(document).on('click', '.btn-reset', async function() {
      const idFiscal = $(this).data('id');
      const name = $(this).data('name');
      Swal.fire({
        title: `Êtes-vous sûr de vouloir restaurer le mot de passe de l'agent : ${name} ?`,
        // text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#1ed760",
        cancelButtonColor: "#d33",
        confirmButtonText: "Oui, restaurer",
        cancelButtonText: "Annuler"
      }).then(async (resultConfirm) => {
        if (resultConfirm.isConfirmed) {

          try {
            // 2. Call the Soft Delete API
            const response = await fetch(`../api/reset_pw.php?id=${idFiscal}`);
            const result = await response.json();

            if (result.success) {

              Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: 'Le mot de passe a été réinitialisé.',
                showConfirmButton: false,
                timer: 3000
              });


            } else {
              Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'error',
                title: result.message,
                showConfirmButton: false,
                timer: 3000
              });

            }
          } catch (error) {
            Swal.fire({
              toast: true,
              position: 'top-end',
              icon: 'error',
              title: 'Connexion au serveur perdue.',
              showConfirmButton: false,
              timer: 3000
            });
          }
        }
      });

    });

    function hideerror() {
      document.getElementById('errorinsert').innerHTML = '';
    }

    function changerole() {
      document.getElementById('actionTodo').setAttribute('data-action', 'save');
    }

    function clearinputs() {
      document.getElementById('idFiscal').value = '',
        document.getElementById('idProx').value = '',
        document.getElementById('nom').value = '',
        document.getElementById('prenom').value = '',
        document.getElementById('email').value = '',
        document.getElementById('ste').value = '-1',
        document.getElementById('campagne').value = '-1',
        document.getElementById('role-select').value = '-1'
    }
    async function saveAgent() {
      let action = document.getElementById('actionTodo').dataset.action;
      // 1. Collect data from inputs
      let agentData = {
        idFiscal: document.getElementById('idFiscal').value,
        idProx: document.getElementById('idProx').value,
        nom: document.getElementById('nom').value,
        prenom: document.getElementById('prenom').value,
        email: document.getElementById('email').value,
        ste: document.getElementById('ste').value,
        campagne: document.getElementById('campagne').value,
        role: document.getElementById('role-select').value
      };

      // 2. Simple Validation
      if (!agentData.idFiscal || !agentData.nom || !agentData.email || agentData.ste == -1 || agentData.campagne == -1 || agentData.role == -1) {
        document.getElementById('errorinsert').innerHTML = "Veuillez remplir les champs obligatoires";
        return;
      }

      try {
        let response;
        // 3. Send to PHP API
        if (action == "save") {
          response = await fetch('../api/save_agent.php', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json'
            },
            body: JSON.stringify(agentData)
          });
        } else if (action == "edit") {

          agentData.updatedAgent = updatedAgent;

          response = await fetch('../api/edit_agent.php', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json'
            },
            body: JSON.stringify(agentData)
          });
        }
        const result = await response.json();

        if (result.success) {
          Swal.fire({
            title: "Succès !",
            text: "Agent enregistré avec succès.",
            icon: "success",
            backdrop: false,
            confirmButtonText: "OK"
          }).then((result) => {
            if (result.isConfirmed) {
              location.reload(); // Refresh the page after clicking OK
            }
          });
        } else {
          document.getElementById('errorinsert').innerHTML = "Erreur: " + result.message;
        }
      } catch (error) {
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: "Erreur lors de l'envoi:" + error,
        });
        document.getElementById('errorinsert').innerHTML = "Une erreur critique est survenue.";
      }
    }
  </script>
  <script src="./assets/js/Sortable.min.js" crossorigin="anonymous"></script>
  <script src="./assets/js/apexcharts.min.js"
    integrity="sha256-+vh8GkaU7C9/wbSLIcwq82tQ2wTf44aOHA8HlBMwRI8=" crossorigin="anonymous"></script>
  <script src="./assets/js/jsvectormap.min.js"
    integrity="sha256-/t1nN2956BT869E6H4V1dnt0X5pAQHPytli+1nTZm2Y=" crossorigin="anonymous"></script>
  <script src="./assets/js/world.js"
    integrity="sha256-XPpPaZlU8S/HWf7FZLAncLg2SAkP8ScUTII89x9D3lY=" crossorigin="anonymous"></script>

</body>

</html>
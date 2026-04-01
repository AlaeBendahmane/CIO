<!doctype html>
<html lang="fr">
<?php
ob_start();
include '../api/helpers.php';
include '../api/session_info.php';
isAuthPath();
ob_end_flush();
$finall = ""; //

if ($role == "U") {
  $finall = $campagne;
} else if ($role == "C") {
  $finall = "Coach " . $campagne;
} else if ($role == "A") {
  $finall = "Administrateur";
} else if ($role == "M") {
  $finall = "Mex";
} else {
  $finall = "";
}


$finallSTE = ""; //

if ($ste == "DC") {
  $finallSTE = "Deal Call";
} else if ($ste == "CBC") {
  $finallSTE = "Core Business Center";
} else if ($ste == "CIO") {
  $finallSTE = "Call in Out";
} else if ($ste == "M") {
  $finallSTE = "Mex";
} else {
  $finallSTE = "";
}
?>


<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>CIO | Profile</title>

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
  <style>
    /* @import url('./assets/css/css2.css'); */
    /* Card Container */
    .profile-card {
      /* width: 380px; */
      border-radius: 15px;
      overflow: hidden;
      box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
      border: none;
    }

    /* The Blue/Primary cover at the top */
    .card-header-cover {
      height: 130px;
      /* background: linear-gradient(45deg, #636e75, #353a40); */
      background-image: url('./assets/img/Proximus_logo.png');
      background-repeat: no-repeat;
      background-size: 95% 95%;
      background-position: center;
      object-fit: cover;
    }

    /* Image Wrapper for the centering */
    .profile-img-wrapper {
      position: relative;
      margin-top: -60px;
      /* Pulls image up into the cover */
      display: inline-block;
    }

    #profile-preview {
      width: 150px;
      height: 150px;
      object-fit: cover;
      border: 5px solid #fff;
      /* White ring around the image */
      background-color: #fff;
    }

    /* Small Camera Button on the image */
    .btn-edit-photo {
      position: absolute;
      bottom: 5px;
      right: 5px;
      border-radius: 50%;
      width: 32px;
      height: 32px;
      border: 1px solid #ddd;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
      cursor: pointer;
      transition: 0.2s;
    }

    .btn-edit-photo:hover {
      background: #f8f9fa;
      transform: scale(1.1);
    }

    .text-bold {
      font-weight: 700;
      color: #495057;
    }
  </style>
  <script src="./assets/js/helper.js"></script>

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
        $currentPage = 'profile';
        include './components/profileNav.php';
        ?>
      </div>
    </nav>
    <?php
    $userRole = $role;
    $currentPage = 'profile';
    include './components/sidebar.php';
    ?>
    <main class="app-main">
      <div class="app-content-header">
        <div class="container-fluid">
        </div>
      </div>
      <div class="app-content">
        <div class="container-fluid">
          <div class="card profile-card">
            <div class="card-header-cover"></div>

            <div class="card-body text-center">
              <div style="margin-top: -35px !important;">
                <div class="profile-img-wrapper">
                  <img id="profile-preview"
                    src="<?= decodeBase64ToImage($profilePic) ?>"
                    class="rounded-circle img-thumbnail"
                    alt="User Profile">
                  <button class="btn-edit-photo" onclick="document.getElementById('profile-upload').click()">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-camera" viewBox="0 0 16 16">
                      <path d="M15 12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1h1.172a3 3 0 0 0 2.12-.879l.83-.828A1 1 0 0 1 6.827 3h2.344a1 1 0 0 1 .707.293l.828.828A3 3 0 0 0 12.828 5H14a1 1 0 0 1 1 1zM2 4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2h-1.172a2 2 0 0 1-1.414-.586l-.828-.828A2 2 0 0 0 9.172 2H6.828a2 2 0 0 0-1.414.586l-.828.828A2 2 0 0 1 3.172 4z" />
                      <path d="M8 11a2.5 2.5 0 1 1 0-5 2.5 2.5 0 0 1 0 5m0 1a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7M3 6.5a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0" />
                    </svg>
                  </button>
                </div>
              </div>

              <h3 class="mt-3 mb-0"><?= $nom . ' ' . $prenom ?></h3>
              <p class="text-muted"><?= $finall ?></p>

              <hr>

              <div class="row text-left px-3">
                <div class="col-6 mb-3">
                  <small class="text-uppercase text-bold">ID STE</small>
                  <p>#<?= $idFiscal ?></p>
                </div>
                <div class="col-6 mb-3">
                  <small class="text-uppercase text-bold">ID Proximus</small>
                  <p>ID<?= $idProx ?></p>
                </div>
                <div class="col-6 mb-3">
                  <small class="text-uppercase text-bold">Entreprise</small>
                  <p><?= $finallSTE  ?></p>
                </div>

                <div class="col-6 mb-3">
                  <small class="text-uppercase text-bold">Campagne</small>
                  <p><?= $campagne ?></p>
                </div>

                <div class="col-12 mb-3">
                  <small class="text-uppercase text-bold">Email Address</small>
                  <p><?= $email ?></p>
                </div>
              </div>

              <div class="d-flex gap-2 mt-2 justify-content-end">
                <!-- <button class="btn btn-warning btn-sm rounded-pill px-4">Modifier le profile</button> -->
                <!-- <button class="btn btn-secondary btn-sm rounded-pill px-4" onchange="editPW()">Modifier le mot de passe</button> -->
                <button type="button"
                  class="btn btn-secondary btn-sm rounded-pill px-4"
                  data-bs-toggle="modal"
                  data-bs-target="#modalPassword"
                  onclick="clearmodal()">
                  Modifier le mot de passe
                </button>
              </div>
            </div>
          </div>

          <input type="file" id="profile-upload" hidden accept="image/*" onchange="previewImage(this)">
        </div>

        <!-- <input type="file" id="profile-upload" hidden accept="image/*" onchange="previewImage(this)"> -->
      </div>
    </main>
    <div class="modal fade" id="modalPassword" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Changer le mot de passe</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label class="form-label">Ancien mot de passe</label>
              <input type="password" id="old_pw" class="form-control" placeholder="******">
            </div>
            <div class="mb-3">
              <label class="form-label">Nouveau mot de passe</label>
              <input type="password" id="new_pw" class="form-control" placeholder="******">
            </div>
            <div class="mb-3">
              <label class="form-label">Confirmer le nouveau mot de passe</label>
              <input type="password" id="confirm_pw" class="form-control" placeholder="******">
            </div>

            <div id="password-requirements" class="mt-2 p-3 border rounded bg-light" style="font-size: 0.85rem; display:none;">
              <div class="fw-bold mb-1 text-muted">Critères requis :</div>
              <div id="dynamic-list"></div>
              <div id="req-match" class="text-danger">✖ Les mots de passe ne correspondent pas</div>
            </div>

            <div id="erreurPW" class="mt-2 mb-3" style="display: flex;justify-content: center;align-items: center;color:red;">

            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
            <button type="button" class="btn btn-primary" id="btnChange" onclick="submitPasswordChange()" disabled>Enregistrer</button>
          </div>
        </div>
      </div>
    </div>
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

    function previewImage(input) {
      if (input.files && input.files[0]) {
        const reader = new FileReader();

        reader.onload = function(e) {
          const base64Data = e.target.result;

          // 1. Update UI Preview
          document.getElementById('profile-preview').src = base64Data;
          document.getElementById('image_user_nav').src = base64Data;
          document.getElementById('image_user_nav_info').src = base64Data;
          // 2. Call the API to save to Database
          updateProfilePic(base64Data);
        };
        reader.readAsDataURL(input.files[0]);
      }
    }

    async function updateProfilePic(base64String) {
      try {
        const response = await fetch('../api/profile_updates.php?action=update_photo', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({
            image: base64String
          })
        });

        const result = await response.json();
        if (result.success) {
          console.log("Photo updated successfully!");
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
          text: "API Error:" + error,
        });
      }
    }

    function clearmodal() {
      document.getElementById('erreurPW').innerHTML = '';

    }

    async function submitPasswordChange() {
      const oldPw = document.getElementById('old_pw').value;
      const newPw = document.getElementById('new_pw').value;
      const confirmPw = document.getElementById('confirm_pw').value;
      if (!newPw || !confirmPw || !newPw) {
        document.getElementById('erreurPW').innerHTML = "Données incomplètes !";
        return;
      }
      if (!oldPw || !newPw) {
        document.getElementById('erreurPW').innerHTML = "Veuillez remplir tous les champs";
        return;
      }

      if (newPw !== confirmPw) {
        document.getElementById('erreurPW').innerHTML = "Les nouveaux mots de passe ne correspondent pas !";
        return;
      }

      const response = await fetch('../api/profile_updates.php?action=change_password', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({
          oldPw,
          newPw,
          'from': 'profile'
        })
      });

      const result = await response.json();
      if (result.success) {
        // Close modal using Bootstrap 5 syntax
        const modal = bootstrap.Modal.getInstance(document.getElementById('modalPassword'));
        modal.hide();
      } else {
        document.getElementById('erreurPW').innerHTML = result.message;
      }
    }
  </script>

  <script>
    let currentGeneratedRegex = "";
    let activeRules = []; // Store globally for the listener
    let passwordPattern;

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
          const dbRegex = data.message; // "/^(?=.*[A-Z]).{12,}$/"

          // 1. Prepare Regex
          let cleanRegex = dbRegex.replace(/^\/|\/$/g, '');
          passwordPattern = new RegExp(cleanRegex);

          // 2. Build the UI Rules
          buildDynamicUI(cleanRegex);
        }
      } catch (error) {
        console.error("Fetch failed:", error);
      }
    }

    function buildDynamicUI(cleanRegex) {
      if (!cleanRegex) return;
      const listContainer = document.getElementById('dynamic-list');
      listContainer.innerHTML = ""; // Clear previous
      activeRules = [];

      // Map regex parts to Human Readable labels
      const ruleMap = [{
          regex: /[A-Z]/,
          id: 'req-upper',
          label: 'Au moins une majuscule (A-Z)'
        },
        {
          regex: /[a-z]/,
          id: 'req-lower',
          label: 'Au moins une minuscule (a-z)'
        },
        {
          regex: /\d/, ///[0-9]|\d/,
          id: 'req-number',
          label: 'Au moins un chiffre (0-9)'
        },
        {
          regex: /[@$!%*?&]/,
          id: 'req-special',
          label: 'Au moins un caractère spécial (@$!%*?&)'
        },
      ];

      // Extract length
      let lengthMatch = cleanRegex.match(/\{(\d+),/);
      let minLength = lengthMatch ? lengthMatch[1] : 1;

      // Add length rule to activeRules array
      activeRules.push({
        id: 'req-length',
        label: `Au moins ${minLength} caractères`,
        test: (v) => v.length >= minLength
      });

      // Check which other rules exist in the dynamic regex string
      ruleMap.forEach(rule => {
        // We check the source of the regex against the database string
        if (cleanRegex.includes(rule.regex.source)) {
          activeRules.push({
            id: rule.id,
            label: rule.label,
            test: (v) => rule.regex.test(v)
          });
        }
      });

      // Inject into HTML
      activeRules.forEach(rule => {
        listContainer.innerHTML += `<div id="${rule.id}" class="text-danger">✖ ${rule.label}</div>`;
      });

      document.getElementById('password-requirements').style.display = 'block';
    }

    // --- LISTENERS ---

    document.getElementById('new_pw').addEventListener('input', function() {
      const val = this.value;

      // Update the visual status for each active rule
      activeRules.forEach(rule => {
        const el = document.getElementById(rule.id);
        if (!el) return;

        const isValid = rule.test(val);
        el.className = isValid ? 'text-success' : 'text-danger';
        el.innerHTML = (isValid ? '✔ ' : '✖ ') + rule.label;
      });

      validateFinal();
    });

    document.getElementById('confirm_pw').addEventListener('input', validateFinal);
    document.getElementById('old_pw').addEventListener('input', validateFinal);

    function validateFinal() {
      const pw = document.getElementById('new_pw').value;
      const confirm = document.getElementById('confirm_pw').value;
      const old_pw = document.getElementById('old_pw').value;
      const matchEl = document.getElementById('req-match');

      const isMatch = (pw === confirm && pw !== "");
      matchEl.className = isMatch ? 'text-success' : 'text-danger';
      matchEl.innerHTML = (isMatch ? '✔ ' : '✖ ') + "Confirmation identique";

      // Only allow submission if the global Regex AND the match are perfect
      const isValidGlobal = passwordPattern.test(pw);
      const isDifferent = (pw !== old_pw);
      document.getElementById("btnChange").disabled = !(isValidGlobal && isMatch && old_pw !== "" && isDifferent);
    }

    // Launch the process
    initPasswordConfig();
  </script>
  <script src="./assets/js/Sortable.min.js"></script>
  <script src="./assets/js/apexcharts.min.js"></script>
  <script src="./assets/js/jsvectormap.min.js"></script>
  <script src="./assets/js/world.js"></script>

</body>

</html>
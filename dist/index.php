<!doctype html>
<html lang="en">
<!--begin::Head-->
<?php
include '../api/helpers.php';
include '../api/session_info.php';
isAlreadyAuth();
?>

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>CIO | Login</title>
  <!--begin::Accessibility Meta Tags-->
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes" />
  <meta name="color-scheme" content="light dark" />
  <meta name="theme-color" content="#007bff" media="(prefers-color-scheme: light)" />
  <meta name="theme-color" content="#1a1a1a" media="(prefers-color-scheme: dark)" />
  <!--end::Accessibility Meta Tags-->

  <!--begin::Primary Meta Tags-->
  <meta name="title" content="AdminLTE 4 | Login Page v2" />
  <meta name="author" content="ColorlibHQ" />
  <meta name="description"
    content="AdminLTE is a Free Bootstrap 5 Admin Dashboard, 30 example pages using Vanilla JS. Fully accessible with WCAG 2.1 AA compliance." />
  <meta name="keywords"
    content="bootstrap 5, bootstrap, bootstrap 5 admin dashboard, bootstrap 5 dashboard, bootstrap 5 charts, bootstrap 5 calendar, bootstrap 5 datepicker, bootstrap 5 tables, bootstrap 5 datatable, vanilla js datatable, colorlibhq, colorlibhq dashboard, colorlibhq admin dashboard, accessible admin panel, WCAG compliant" />
  <!--end::Primary Meta Tags-->

  <!--begin::Accessibility Features-->
  <!-- Skip links will be dynamically added by accessibility.js -->
  <meta name="supported-color-schemes" content="light dark" />
  <link rel="icon" type="image/png" sizes="32x32" href="./assets/img/C.png">

  <link rel="preload" href="./assets/css/adminlte.css" as="style" />
  <!--end::Accessibility Features-->

  <!--begin::Fonts-->
  <link rel="stylesheet" href="./assets/css/index.css" media="print" onload="this.media = 'all'" />
  <!--end::Fonts-->

  <!--begin::Third Party Plugin(OverlayScrollbars)-->
  <link rel="stylesheet" href="./assets/css/overlayscrollbars.min.css" crossorigin="anonymous" />
  <!--end::Third Party Plugin(OverlayScrollbars)-->

  <!--begin::Third Party Plugin(Bootstrap Icons)-->
  <link rel="stylesheet" href="./assets/css/bootstrap-icons.min.css" crossorigin="anonymous" />
  <!--end::Third Party Plugin(Bootstrap Icons)-->

  <!--begin::Required Plugin(AdminLTE)-->
  <link rel="stylesheet" href="./assets/css/adminlte.css" />
  <!--end::Required Plugin(AdminLTE)-->
  <script src="./assets/js/helper.js"></script>

</head>
<!--end::Head-->
<!--begin::Body-->

<body class="login-page bg-body-secondary">
  <div class="login-box">
    <div class="card card-outline card-primary">
      <div class="card-header">
        <h1 class="mb-0 link-dark text-center "><b>CIO</b> Pointage
        </h1>
      </div>
      <div class="card-body login-card-body">
        <p class="login-box-msg">Connectez-vous pour démarrer votre session</p>

        <form action="../index3.html" method="post" id="loginForm">
          <div class="input-group mb-1">
            <div class="form-floating">
              <input id="loginEmail" type="email" class="form-control" value="" placeholder="" />
              <label for="loginEmail">Email</label>
            </div>
            <div class="input-group-text">
              <span class="bi bi-envelope"></span>
            </div>
          </div>
          <div class="input-group mb-1">
            <div class="form-floating">
              <input id="loginPassword" type="password" class="form-control" placeholder="" />
              <label for="loginPassword">Mot de passe</label>
            </div>
            <div class="input-group-text">
              <span class="bi bi-lock-fill"></span>
            </div>
          </div>
          <!--begin::Row-->
          <div class="row">
            <div class="col-8 d-inline-flex align-items-center">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault" />
                <label class="form-check-label" for="flexCheckDefault"> Se souvenir de moi </label>
              </div>
            </div>
          </div>
          <!--end::Row-->
          <div class="social-auth-links text-center mb-3 d-grid gap-2">
            <button type="submit" class="btn btn-primary"> <i class="bi bi-box-arrow-in-right"></i> Se connecter
            </button>
          </div>
        </form>



        <!-- <p class="mb-1">
            <a href="forgot-password.html">I forgot my password</a>
          </p> -->
      </div>
      <!-- /.login-card-body -->
    </div>
  </div>
  <!-- /.login-box -->

  <!--begin::Third Party Plugin(OverlayScrollbars)-->
  <script src="./assets/js/overlayscrollbars.browser.es6.min.js" crossorigin="anonymous"></script>
  <!--end::Third Party Plugin(OverlayScrollbars)--><!--begin::Required Plugin(popperjs for Bootstrap 5)-->
  <script src="./assets/js/popper.min.js" crossorigin="anonymous"></script>
  <!--end::Required Plugin(popperjs for Bootstrap 5)--><!--begin::Required Plugin(Bootstrap 5)-->
  <script src="./assets/js/bootstrap.min.js" crossorigin="anonymous"></script>
  <!--end::Required Plugin(Bootstrap 5)--><!--begin::Required Plugin(AdminLTE)-->
  <script src="./assets/js/adminlte.js"></script>
  <!--end::Required Plugin(AdminLTE)--><!--begin::OverlayScrollbars Configure-->
  <script src="./assets/js/sweetalert2@11.js"></script>

  <script>
    const SELECTOR_SIDEBAR_WRAPPER = '.sidebar-wrapper';
    const Default = {
      scrollbarTheme: 'os-theme-light',
      scrollbarAutoHide: 'leave',
      scrollbarClickScroll: true,
    };
    document.addEventListener('DOMContentLoaded', function() {

      const savedEmail = localStorage.getItem('rememberedEmail');
      const emailInput = document.getElementById('loginEmail');
      const rememberCheckbox = document.getElementById('flexCheckDefault');
      if (savedEmail) {
        emailInput.value = savedEmail;
        rememberCheckbox.checked = true;

        // Optionnel : Mettre le focus sur le mot de passe directement
        document.getElementById('loginPassword').focus();
      }



      const sidebarWrapper = document.querySelector(SELECTOR_SIDEBAR_WRAPPER);

      // Disable OverlayScrollbars on mobile devices to prevent touch interference
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
    let pattern = "";
    document.getElementById('loginForm').addEventListener('submit', async function(e) {
      e.preventDefault();
      const email = document.getElementById('loginEmail').value;
      const password = document.getElementById('loginPassword').value;
      const remember = document.getElementById('flexCheckDefault').checked;
      const myRegex = new RegExp(pattern);
      if (!email || email == "" || !password || password == "") {
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: "Veuillez remplir tous les champs",
        });
        return
      }

      if (!myRegex.test(password)) {
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: "Vérifiez votre mot de passe",
        });
        return
      }

      if (remember) {
        localStorage.setItem('rememberedEmail', email);
      } else {
        localStorage.removeItem('rememberedEmail');
      }

      try {
        const response = await fetch('../api/auth.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({
            email,
            password
          })
        });
        const result = await response.json();
        if (result.success) {
          window.location.href = './Dashboard.php';
        } else {
          Swal.fire({
            icon: "error",
            title: "Oops...",
            text: result.message || "Identifiants incorrects",
          });
        }
      } catch (error) {
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: "Impossible de contacter le serveur.",
        });
      }
    });

    //pw
    document.addEventListener('DOMContentLoaded', async function() {
      async function loadPasswordConfig() {
        try {
          // We call our PHP API
          const response = await fetch('../api/getParams.php');
          const data = await response.json();

          if (data.success) {
            pattern = data?.message?.slice(1, -1);
          } else {
            console.error("Error:", data.message);
          }
        } catch (error) {
          console.error("Fetch failed:", error);
        }
      }

      await loadPasswordConfig()
    });
  </script>
</body>

</html>
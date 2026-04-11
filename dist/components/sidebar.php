<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
    <div id="resize-backdrop-aside" style="display: none; position: absolute; top: 0px; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999;"></div>
    <div class="sidebar-brand">
        <a href="./Dashboard.php" class="brand-link">
            <img src="./assets/img/C.png" alt="AdminLTE Logo" class="brand-image opacity-75 shadow" />
            <span class="brand-text fw-light">CIO Pointage</span>
        </a>
    </div>
    <div class="sidebar-wrapper">
        <nav class="mt-2">
            <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="navigation" aria-label="Main navigation" data-accordion="false" id="navigation">

                <li class="nav-item">
                    <a href="./Dashboard.php" class="nav-link <?php echo ($currentPage == 'dashboard') ? 'active' : ''; ?>">
                        <i class="nav-icon bi bi-house-door-fill"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="./Pointage.php" class="nav-link <?php echo ($currentPage == 'pointage') ? 'active' : ''; ?>">
                        <i class="nav-icon bi bi-calendar2-week-fill"></i>
                        <p>Pointage</p>
                    </a>
                </li>

                <?php if ($userRole == 'A'): ?>
                    <li class="nav-item">
                        <a href="./Utilisateurs.php" class="nav-link <?php echo ($currentPage == 'Utilisateurs') ? 'active' : ''; ?>">
                            <i class="nav-icon bi bi-people-fill"></i>
                            <p>Utilisateurs</p>
                        </a>
                    </li>
                <?php endif; ?>

                <?php if ($userRole == 'A'): ?>
                    <li class="nav-item">
                        <a href="./Notifications.php" class="nav-link <?php echo ($currentPage == 'notifications') ? 'active' : ''; ?>">
                            <i class="bi bi-bell"></i>
                            <p>Notifications</p>
                        </a>
                    </li>
                <?php endif; ?>

                <?php if ($userRole == 'A'): ?>
                    <li class="nav-item">
                        <a href="./Parametres.php" class="nav-link <?php echo ($currentPage == 'parametres') ? 'active' : ''; ?>">
                            <i class="bi bi-sliders"></i>
                            <p>Paramètres</p>
                        </a>
                    </li>
                <?php endif; ?>

                <li class="nav-item">
                    <a href="./Documents.php" class="nav-link <?php echo ($currentPage == 'documents') ? 'active' : ''; ?>">
                        <i class="bi bi-archive"></i>
                        <p>Documents</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="./Contacts.php" class="nav-link <?php echo ($currentPage == 'contacts') ? 'active' : ''; ?>">
                        <i class="bi bi-person-rolodex"></i>
                        <p>Contacts</p>
                    </a>
                </li>

            </ul>
        </nav>
    </div>
</aside>
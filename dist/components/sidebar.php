<?php
$version = getParam($pdo, 'Version');
?>
<style>
    .sidebar-version {
        border-top: 1px solid var(--bs-border-color);
        color: #c2c7d0;
        font-size: 16px;
        background: transparent;
        cursor: default;
    }
</style>
<aside class="app-sidebar bg-body-secondary shadow d-flex flex-column" data-bs-theme="dark">
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
                        <a href="./Utilisateurs.php" class="nav-link <?php echo ($currentPage == 'utilisateurs') ? 'active' : ''; ?>">
                            <i class="nav-icon bi bi-people-fill"></i>
                            <p>Utilisateurs</p>
                        </a>
                    </li>
                <?php endif; ?>

                <?php if ($userRole == 'A'): ?>
                    <li class="nav-item">
                        <a href="./Planing.php" class="nav-link <?php echo ($currentPage == 'planing') ? 'active' : ''; ?>">
                            <i class="nav-icon bi bi-calendar-plus-fill"></i>
                            <p>Planing</p>
                        </a>
                    </li>
                <?php endif; ?>

                <?php if ($userRole == 'A'): ?>
                    <li class="nav-item">
                        <a href="./Notifications.php" class="nav-link <?php echo ($currentPage == 'notifications') ? 'active' : ''; ?>">
                            <i class="nav-icon bi bi-bell-fill"></i>
                            <p>Notifications</p>
                        </a>
                    </li>
                <?php endif; ?>

                <?php if ($userRole === 'A'): ?>
                    <?php $isDocumentsSection = in_array($currentPage, ['documents', 'massSend', 'documentsOne', 'fiches']); ?>
                    <li class="nav-item <?= $isDocumentsSection ? 'menu-open' : '' ?>">
                        <a href="#" class="nav-link" <?= $isDocumentsSection ? 'style="background: transparent !important;"' : '' ?>>
                            <i class="nav-icon bi bi-archive-fill"></i>
                            <p>
                                Documents
                                <i class="nav-arrow bi bi-chevron-right"></i>
                            </p>
                        </a>

                        <ul class="nav nav-treeview mb-1">
                            <li class="nav-item">
                                <a href="./Documents.php?view=single" class="nav-link <?= ($currentPage === 'documentsOne') ? 'active' : '' ?>">
                                    <i class="nav-icon bi bi-folder-fill"></i>
                                    <p>Mes documents</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="./Documents.php?view=multiple" class="nav-link <?= ($currentPage === 'documents') ? 'active' : '' ?>">
                                    <i class="nav-icon bi bi-window-dock"></i>
                                    <p>Bibliothèque</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="./Envoie.php" class="nav-link <?= ($currentPage === 'massSend') ? 'active' : '' ?>">
                                    <i class="nav-icon bi bi-folder-symlink"></i>
                                    <p>Envoi en masse</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="./Fiches.php" class="nav-link <?= ($currentPage === 'fiches') ? 'active' : '' ?>">
                                    <i class="nav-icon bi bi-journal-text"></i>
                                    <p>Fiches</p>
                                </a>
                            </li>
                        </ul>
                    </li>

                <?php else: ?>

                    <li class="nav-item">
                        <a href="./Documents.php" class="nav-link <?= ($currentPage === 'documents') ? 'active' : '' ?>">
                            <i class="nav-icon bi bi-folder-fill"></i>
                            <p>Mes Documents</p>
                        </a>
                    </li>

                <?php endif; ?>

                <li class="nav-item">
                    <a href="./Contacts.php" class="nav-link <?php echo ($currentPage == 'contacts') ? 'active' : ''; ?>">
                        <i class="nav-icon bi bi-person-rolodex"></i>
                        <p>Contacts</p>
                    </a>
                </li>

                <?php if ($userRole == 'A'): ?>
                    <li class="nav-item">
                        <a href="./Parametres.php" class="nav-link <?php echo ($currentPage == 'parametres') ? 'active' : ''; ?>">
                            <i class="nav-icon bi bi-sliders"></i>
                            <p>Paramètres</p>
                        </a>
                    </li>
                <?php endif; ?>

                <?php if ($userRole === 'A'): ?>
                    <?php $isAssets = in_array($currentPage, ['ordinateurs', 'ecrans', 'accessoires', 'licenses', 'composants']); ?>
                    <li class="nav-item <?= $isAssets ? 'menu-open' : '' ?>">
                        <a href="#" class="nav-link" <?= $isAssets ? 'style="background: transparent !important;"' : '' ?>>
                            <i class="nav-icon bi bi-box2-fill"></i>
                            <p>
                                Equipements
                                <i class="nav-arrow bi bi-chevron-right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview mb-1">
                            <li class="nav-item">
                                <a href="./Ordinateurs.php" class="nav-link <?= ($currentPage === 'ordinateurs') ? 'active' : '' ?>">
                                    <i class="nav-icon bi bi-pc"></i>
                                    <p>Ordinateurs</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="./Ecrans.php" class="nav-link <?= ($currentPage === 'ecrans') ? 'active' : '' ?>">
                                    <i class="nav-icon bi bi-pc-display-horizontal"></i>
                                    <p>Ecrans</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="./Accessoires.php" class="nav-link <?= ($currentPage === 'accessoires') ? 'active' : '' ?>">
                                    <i class="nav-icon bi bi-keyboard"></i>
                                    <p>Accessoires</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="./Licenses.php" class="nav-link <?= ($currentPage === 'licenses') ? 'active' : '' ?>">
                                    <i class="nav-icon bi bi-key-fill"></i>
                                    <p>Licenses</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="./Composants.php" class="nav-link <?= ($currentPage === 'composants') ? 'active' : '' ?>">
                                    <i class="nav-icon bi bi-memory"></i>
                                    <p>Composants</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                <?php endif; ?>

            </ul>
        </nav>
    </div>
    <div class="sidebar-version text-center py-2">
        <?= $version ?>
    </div>
</aside>
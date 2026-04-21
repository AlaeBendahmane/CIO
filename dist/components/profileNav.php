<?php
// include '../api/helpers.php';
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

?>
<style>
    .dropdown-item.bg-light {
        background-color: #f4f6f9 !important;
        /* Slightly darker than pure white */
    }

    .dropdown-item.bg-light:hover {
        background-color: #e9ecef !important;
    }

    /* Styling for read notifications to make them look "dimmed" */
    .notif-link:not(.is-unread) {
        opacity: 0.8;
    }

    .notif-link:not(.is-unread) .fw-bold {
        color: #6c757d !important;
        /* Force muted color for read names */
    }

    @media (max-width: 576px) {
        #notifDrop {
            position: fixed !important;
            top: 60px;
            left: 10px;
            right: 10px;
            width: auto;
            max-width: none;
        }
    }
</style>

<ul class="navbar-nav ms-auto">
    <!-- to add -->
    <li class="nav-item dropdown">
        <a class="nav-link" data-bs-toggle="dropdown" href="#">
            <i class="bi bi-bell"></i>
            <span class="navbar-badge badge text-bg-warning text-white" style="right: 5px;border-radius: 5px;top: 8px;min-width: 17px;" id="notifCount">0</span>
        </a>
        <div class="dropdown-menu dropdown-menu-xl dropdown-menu-end" id="notifDrop">
            <span class="dropdown-item dropdown-header" id="unrededCount"></span>
        </div>
    </li>
    <!--  -->
    <?php if ($currentPage == "dashboard"): ?>
        <li class="nav-item" style="z-index: 9999 !important;background-color: #fff;border-radius: 5px;">
            <a class="nav-link js-toggle-resize" href="javascript:void(0);" role="button">
                <i id="iconexpand" class="bi bi-gear"></i>
            </a>
        </li>
    <?php endif; ?>
    <!--  -->
    <li class="nav-item dropdown user-menu">
        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" style="align-items: center;display: flex;">
            <img id="image_user_nav" src="<?= decodeBase64ToImage($profilePic) ?>" class="user-image rounded-circle shadow" alt="User Image" />
            <span class="d-none d-md-inline"> <?= $nom . ' ' . $prenom    ?> </span>
        </a>
        <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
            <li class="user-header text-bg-primary">
                <img id="image_user_nav_info" src="<?= decodeBase64ToImage($profilePic) ?>" class="rounded-circle shadow" alt="User Image" style="border-color: #fff !important;border-width: 3px !important;" />
                <p>
                    <?= $nom . ' ' . $prenom    ?> - <?= $finall ?>
                    <small><?= $email ?> </small>
                </p>
            </li>
            <!-- <li class="user-body">
                <div class="row">
                  <div class="col-4 text-center">
                    <a href="#">Followers</a>
                  </div>
                  <div class="col-4 text-center">
                    <a href="#">Sales</a>
                  </div>
                  <div class="col-4 text-center">
                    <a href="#">Friends</a>
                  </div>
                </div>
              </li> -->
            <li class="user-footer">
                <a href="../dist/Profile.php" class="btn btn-outline-secondary">Profile</a>
                <a href="../api/logout.php" class="btn btn-outline-danger float-end">Sign out</a>
            </li>
        </ul>
    </li>
</ul>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const notifDrop = document.getElementById('notifDrop');

        if (notifDrop) {
            notifDrop.addEventListener('click', async function(e) {
                const link = e.target.closest('.notif-link');
                if (!link) return;

                // Stop dropdown from closing and page from jumping
                e.preventDefault();
                e.stopPropagation();

                const notifId = link.getAttribute('data-id');
                const isUnread = link.classList.contains('is-unread');

                if (isUnread) {
                    try {
                        const response = await fetch(`../api/notifications.php?action=mark_read&id=${notifId}`);
                        const result = await response.json();
                        //old
                        // if (result.status === 'success') {
                        //     // 1. Remove visual "unread" markers
                        //     link.classList.remove('bg-light', 'is-unread');
                        //     link.style.borderLeftColor = 'transparent';

                        //     const dot = link.querySelector('.unread-dot');
                        //     if (dot) dot.remove();

                        //     // 2. Decrement the Yellow Badge
                        //     const badge = document.getElementById('notifCount');
                        //     if (badge) {
                        //         let count = parseInt(badge.innerText) || 0;
                        //         if (count > 0) {
                        //             badge.innerText = count - 1;
                        //             if (count - 1 === 0) badge.style.display = 'none';
                        //         }
                        //     }

                        //     // 3. Decrement the Header Count ("15 Notifications")
                        //     const header = document.getElementById('unrededCount');
                        //     if (header) {
                        //         let count = parseInt(header.innerText) || 0;
                        //         if (count > 0) {
                        //             header.innerText = `${count - 1} Notifications non lues`;
                        //         }
                        //     }
                        // }
                        if (result.status === 'success') {
                            // 1. Remove background and unread status
                            link.classList.remove('bg-light', 'is-unread');
                            link.style.borderLeftColor = 'transparent';

                            // 2. Remove the blue dot
                            const dot = link.querySelector('.unread-dot');
                            if (dot) dot.remove();

                            // --- NEW: Change Typography from Unread to Read ---

                            // Change Sender name from bolder to bold/normal
                            const senderName = link.querySelector('.fw-bolder');
                            if (senderName) {
                                senderName.classList.replace('fw-bolder', 'fw-bold');
                            }

                            // Change Title/Content colors from dark to muted
                            const titleText = link.querySelector('.text-dark.fs-6');
                            if (titleText) {
                                titleText.classList.replace('text-dark', 'text-secondary');
                            }

                            // ------------------------------------------------

                            // 3. Update Badge Count
                            const badge = document.getElementById('notifCount');
                            if (badge) {
                                let count = parseInt(badge.innerText) || 0;
                                if (count > 1) {
                                    badge.innerText = count - 1;
                                } else {
                                    badge.style.display = 'none';
                                }
                            }

                            // 4. Update Header Count
                            const header = document.getElementById('unrededCount');
                            if (header) {
                                let count = parseInt(header.innerText) || 0;
                                header.innerText = `${Math.max(0, count - 1)} Notifications non lues`;
                            }
                        }
                    } catch (err) {
                        console.error("Mark read failed:", err);
                    }
                }
            });
        }
    });
</script>
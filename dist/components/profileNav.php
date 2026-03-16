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

<ul class="navbar-nav ms-auto">
    <!-- to add -->
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
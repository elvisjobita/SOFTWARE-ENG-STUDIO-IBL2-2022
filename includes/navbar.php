<?php require_once ROOT_PATH . "/controllers/cart.php" ?>

<nav class="navbar navbar-expand-lg bg-dark shadow-sm py-3" id="main-nav">
  <div class="container">

    <!-- Navbar Brand -->
    <a class="navbar-brand" href=<?php echo BASE_URL ?>>
      <img src=<?php echo BASE_URL . "static/img/logo.png" ?> width="40" height="40" alt="Estore logo" />
    </a>

    <!-- Navbar Toggler -->
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar" aria-controls="navbar" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon text-white">
        <i class="fas fa-2x fa-bars"></i>
      </span>
    </button>

    <!-- Navbar Menu -->
    <div class="collapse navbar-collapse" id="navbar">
      <ul class="navbar-nav ml-auto mt-2">

        <?php if (isset($_SESSION["is_authenticated"])) { ?>

          <!-- Check admin access -->
          <?php if (isset($_SESSION["is_admin"])) { ?>
            <li class="nav-item w-max my-1">
              <a class="nav-link p-2 text-white btn btn-outline-primary mr-3" href=<?php echo BASE_URL . "admin.php" ?>>
                Admin Dashboard
              </a>
            </li>
          <?php } ?>

          <li class="nav-item w-max my-1">
            <a class="nav-link p-2 mr-3 btn btn-danger text-white" href=<?php echo BASE_URL . "logout.php" ?>>
              <i class="fas fa-sign-out-alt"></i> Logout
            </a>
          </li>

        <?php } else { ?>

          <li class="nav-item w-max my-1">
            <a class="nav-link p-2 btn btn-outline-primary text-white mr-3" href=<?php echo BASE_URL . "signup.php" ?>>
              Signup
            </a>
          </li>

          <li class="nav-item w-max my-1">
            <a class="nav-link p-2 mr-3 btn btn-outline-primary text-white" href=<?php echo BASE_URL . "login.php" ?>>
              Login
            </a>
          </li>

        <?php } ?>

        <!-- Catalog Link -->
        <li class="nav-item w-max my-1">
          <a class="nav-link p-2 btn mr-3 btn-outline-primary" href=<?php echo BASE_URL . "catalog.php" ?>>
            <span class="badge badge-secondary">Catalog</span>
          </a>
        </li>

        <!-- Show Cart Button (with items) -->
        <li class="nav-item w-max my-1">
          <a class="nav-link p-2 btn btn-outline-primary" href=<?php echo BASE_URL . "cart.php" ?>>
            <i class="fas fa-cart-plus"></i> Cart
            <span class="badge badge-secondary">
              <?php echo getTotalCartItems() ?>
            </span>
          </a>
        </li>

      </ul>
    </div>
  </div>
</nav>

<!-- Display Error or Success messages -->
<?php
if (isset($_SESSION["errors"])) {
  echo "<div class='container shadow mt-2'>";
  foreach ($_SESSION["errors"] as $key => $value) {
    echo <<<_ERROR
    <p class="text-danger py-2 mb-1"><b>$value</b></p>
    _ERROR;
  }
  echo "</div>";
  unset($_SESSION["errors"]);
} elseif (isset($_SESSION["success"])) {
  echo "<div class='container shadow mt-2'>";
  foreach ($_SESSION["success"] as $key => $value) {
    echo <<<_SUCCESS
      <p class="text-success py-2 mb-1"><b>$value</b></p>
      _SUCCESS;
  }
  echo "</div>";
  unset($_SESSION["success"]);
}
?>

<!-- ======== End of Navbar ======= -->
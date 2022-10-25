<?php
require_once realpath(dirname(__FILE__)) . "/config.php";
require_once ROOT_PATH . "/includes/header.php";
require_once ROOT_PATH . "/utils/functions.php";
require_once ROOT_PATH . "/controllers/cart.php";

$cart = getCart();
if (getTotalCartItems() == 0) {
  $_SESSION["errors"] = ["Checkout not possible!"];
  exitToLocation("");
}

if (isset($_POST["checkout_btn"])) {
  return handleCheckout($_POST);
}
?>

<title>Estore | Cart Checkout</title>
</head>

<body>
  <!-- ============ Navbar ========== -->
  <?php require_once ROOT_PATH . "/includes/navbar.php" ?>

  <!-- =========== App Container ============ -->
  <main class="container" id="main">
    <section id="cart-section" class="my-4">
      <!-- Section Title -->
      <h2 class="text-muted">Checkout</h2>

      <!-- Checkout Container -->
      <div class="cart-container row">
        <div class="cart-details col-md-8 d-flex flex-column p-3 mb-5">

          <!-- Ask for login before checkout -->
          <?php if (!isset($_SESSION["is_authenticated"])) { ?>
            <a href=<?php echo BASE_URL . "login.php" ?> class="nav-link border rounded p-2 w-max">
              Login to continue
            </a>

          <?php } else { ?>
            <form action="" class="w-100 shadow rounded pb-3 px-4" method="POST">
              <!-- User ID hidden field -->
              <input type="hidden" name="user_id" value=<?php echo $_SESSION["id"] ?>>

              <!-- Email field (disabled) -->
              <div class="form-group">
                <label class="h5 text-muted" for="email">
                  Email address
                </label>
                <input type="email" class="form-control disabled-link" name="email" aria-describedby="emailHelp">
                <small id="emailHelp" class="form-text text-info mt-2">
                  Go to account to change your email
                </small>
              </div>

              <!-- Address field -->
              <label class="h5 mt-2 text-muted" for="address_1">
                Shipping Address
              </label>
              <textarea name="address_1" id="address_1" class="form-control" cols="12" rows="4" required></textarea>

              <!-- Payment Method field -->
              <div class="input-group mt-4">
                <label class="h5 w-100 text-muted" for="pay_method">
                  Payment Method
                </label>
                <div class="input-group mb-3">
                  <div class="input-group-prepend">
                    <label class="input-group-text" for="pay_method">
                      Choose One
                    </label>
                  </div>
                  <select class="custom-select" name="pay_method" id="pay_method" required>
                  </select>
                </div>
              </div>

              <!-- Submit Button -->
              <button type="submit" name="checkout_btn" class="btn btn-secondary btn-lg shadow my-4 py-3 px-5">
                Place Order
              </button>
            </form>
          <?php } ?>
        </div>

        <!-- Cart Summary -->
        <?php require_once ROOT_PATH . "/includes/cart-summary.php" ?>
      </div>

    </section>
  </main>
  <!-- ========= End of Container ======= -->

  <!-- =========== Footer =========== -->
  <?php require_once ROOT_PATH . "/includes/footer.php" ?>
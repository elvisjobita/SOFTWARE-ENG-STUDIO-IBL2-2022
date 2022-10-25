<?php

require_once realpath(dirname(__FILE__)) . "/config.php";
require_once ROOT_PATH . "/includes/header.php";
require_once ROOT_PATH . "/utils/functions.php";
require_once ROOT_PATH . "/controllers/cart.php";

$cart = [];

// Handle POST method different from GET method
if ($_SERVER["REQUEST_METHOD"] === "POST") {

  // Cart actions requiring form data
  if (isset($_POST["cart_btn_action"], $_POST["slug"])) {
    switch ($_POST["cart_btn_action"]) {
      case "cart-btn-add":
      case "cart-btn-update":
        $cart = addToCart($_POST);
        break;
      case "cart-btn-remove":
        $cart = removeFromCart($_POST["slug"]);
        break;
      default:
        break;
    }
  } elseif (isset($_POST["cart-btn-clear"])) {
    // Clear the cart and redirect
    clearCart();
  }
} elseif ($_SERVER["REQUEST_METHOD"] === "GET") {
  $cart = getCart();
}
?>

<title>Estore | Cart</title>
</head>

<body>
  <!-- ============ Navbar ========== -->
  <?php require_once ROOT_PATH . "/includes/navbar.php" ?>

  <!-- =========== App Container ============ -->
  <main class="container" id="main">
    <section id="cart-section" class="my-4">
      <!-- Conditionaly Perform cart actions if cart has items -->
      <?php if (getTotalCartItems() > 0) { ?>

        <!-- Section Title -->
        <h2 class="text-muted">
          Shopping Cart
          <span class="badge badge-primary">
            <?php echo getTotalCartItems() ?>
          </span>
        </h2>

        <!-- Actual Cart Container -->
        <div class="cart-container row mb-5">
          <div class="cart-details col-md-8 d-flex flex-column p-3">
            <article class="cart-item" style="max-width: 600px">

              <!-- Loop through cart items and render cart items -->
              <?php if (count($cart) > 0 && count($cart["cart_items"])) { ?>
                <?php
                foreach ($cart["cart_items"] as $key => $arr) { ?>

                  <!-- Cart Item -->
                  <div class="card mb-2">
                    <div class="row no-gutters">
                      <!-- Cart Item - Image -->
                      <div class="col-md-4 p-1">
                        <img src=<?php echo $arr["image"] ?> class="card-img" alt=<?php echo $arr["name"] ?>>
                      </div>

                      <!-- Cart Item Details -->
                      <div class="col-md-8">
                        <div class="card-body p-1">
                          <!-- Cart Item - Title -->
                          <a href=<?php
                                  $id = $arr["id"];
                                  echo BASE_URL .
                                    "item.php?id=$id"
                                  ?> class="nav-link px-0">
                            <h5 class="card-title mb-1">
                              <?php echo $arr["name"] ?>
                            </h5>
                          </a>

                          <!-- Product Price -->
                          <h5 class="d-block mt-4">
                            $<?php
                              echo calcDiscountedPrice(
                                $arr["price"],
                                $arr["discount"]
                              )
                              ?>
                          </h5>

                          <!-- Cart Item - Footer -->
                          <div class="d-flex justify-content-between border-top pt-2">
                            <!-- Cart Item - Update Form-->
                            <form action="" method="post" data-item-id=<?php echo $arr["id"] ?> data-item-slug=<?php echo $arr["slug"] ?> class="form-inline cart-update-form">

                              <input type="hidden" name="cart_btn_action" value="cart-btn-update">

                              <input type="number" class="form-control mr-2 w-max" name="cart_qty" value=<?php echo getQtyInCart($arr["slug"]); ?> min="1" max=<?php echo $arr["stock_qty"] ?> required>

                              <button type="submit" name="cart-btn" class="btn btn-sm btn-primary">
                                Update
                              </button>
                            </form>

                            <!-- Cart Item - Remove Form-->
                            <form action="" method="post" class="">

                              <input type="hidden" name="cart_btn_action" value="cart-btn-remove">

                              <input type="hidden" name="slug" value=<?php echo $arr["slug"] ?>>

                              <button type="submit" name="cart-btn-remove" id="cart-btn-remove" class="btn btn-sm btn-danger">
                                Remove
                              </button>
                            </form>
                          </div>
                        </div>

                      </div>
                    </div>
                  </div>
                <?php } ?>
              <?php } ?>

            </article>
          </div>

          <!-- Cart Summary -->
          <?php require_once ROOT_PATH . "/includes/cart-summary.php" ?>

          <!-- Cart Summary - Checkout -->
          <p class="d-block w-100 pt-5 pl-3 mt-3">
            <a href=<?php echo BASE_URL . "checkout.php" ?> class="btn btn-lg btn-secondary py-3 px-5">
              Checkout
            </a>
          </p>
        </div>

      <?php } else { ?>

        <div class="jumbotron">
          <h3 class="font-weight-light text-muted">
            No items in cart
          </h3>
          <a href=<?php echo BASE_URL . "catalog.php" ?> class="nav-link p-2 border bg-light rounder w-max">
            Start Shopping
          </a>
        </div>

      <?php } ?>
    </section>
  </main>
  <!-- ========= End of Container ======= -->

  <!-- =========== Footer =========== -->
  <?php require_once ROOT_PATH . "/includes/footer.php" ?>
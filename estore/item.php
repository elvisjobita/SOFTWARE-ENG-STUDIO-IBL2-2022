<?php
// Config file MUST come first
require_once realpath(dirname(__FILE__)) . "/config.php";
require_once ROOT_PATH . "/includes/header.php";
require_once ROOT_PATH . "/controllers/items.php";
require_once ROOT_PATH . "/utils/functions.php";

// This page cannot be viewed without item id
if (!isset($_GET["id"]) || empty($_GET["id"])) {
  $_SESSION["errors"] = ["Item id required to view item page"];
  exitToLocation("");
} elseif (!is_numeric(($_GET["id"]))) {
  $_SESSION["errors"] = ["Invalid id"];
  exitToLocation("");
}

// Use lists short-hand to destructure values
$item = getItemById($_GET["id"]);
if (count($item) == 0) {
  exitToLocation("");
}

[
  "id"          => $id,
  "slug"        => $slug,
  "name"        => $name,
  "price"       => $price,
  "image"       => $image,
  "discount"    => $discount,
  "stock_qty"   => $stock_qty,
  "description" => $description,
  "is_featured" => $is_featured,
] = $item;

?>

<title>Estore | <?php echo $name ?></title>
</head>

<body>
  <!-- ============ Navbar ========== -->
  <?php require_once ROOT_PATH . "/includes/navbar.php" ?>

  <!-- =========== App Container ============ -->
  <main class="container pt-3" id="main">

    <!-- Container for product detail view -->
    <article class="product-detail-container">
      <div class="card mb-5">
        <div class="row no-gutters">
          <div class="col-md-4 p-2">
            <!-- Conditionaly show on sale text -->
            <?php if (intval($discount) > 30) { ?>
              <p class="badge badge-warning position-absolute shadow p-3">
                On SALE!!
              </p>
            <?php } ?>

            <!-- Product Image -->
            <img src=<?php echo $image ?> class="card-img pd-image" alt="img">
          </div>

          <!-- Product Details Sidebar -->
          <div class="col-md-8">
            <div class="card-body p-2">
              <!-- Product Title -->
              <h3 class="card-title mb-3 pd-title">
                <?php echo $name ?>
              </h3>

              <!-- Product Stock Quantity -->
              <h5 class="d-block my-4 font-weight-light">
                In Stock
                <span class="font-weight-bold pd-stock-qty">
                  <?php echo $stock_qty ?>
                </span>
              </h5>

              <!-- Product Discount Status -->
              <?php if ($discount > 0) { ?>
                <p class="font-weight-bold border rounded p-1">
                  Original Price
                  <del class="text-danger">$
                    <?php echo $price ?>
                  </del>
                </p>
              <?php } ?>

              <!-- Product Price -->
              <h4 class="d-block my-4 font-weight-bold pd-price">
                $ <?php echo calcDiscountedPrice($price, $discount) ?>
              </h4>

              <!-- Conditionaly render Add to Cart Button -->
              <?php if ($stock_qty == 0) { ?>
                <p class="h5 border rounded px-1 py-2 text-danger">
                  Out of Stock
                </p>
              <?php } else { ?>
                <!-- Product Add To Cart Form -->
                <form action=<?php echo BASE_URL . "cart.php" ?> method="post" class="cart-add-form form-inline pt-4 border-top" data-item-slug=<?php echo $slug ?>>

                  <!-- Cart Quantity (Updated client side)-->
                  <input type="number" class="form-control pd-cart-qty mr-4 " name="cart_qty" value=1 min="1" max=<?php echo $stock_qty ?> required>
                  <button type="submit" name="cart-btn" class="btn btn-primary pd-add-to-cart">
                    Add To Cart
                  </button>
                </form>
              <?php } ?>

            </div>

          </div>
        </div>
      </div>

      <!-- Product Description -->
      <section id="product-detail-section">
        <h3 class="text-muted">Product Description</h3>

        <div class="border rounded shadow px-2 py-3 my-4 pd-description">
          <?php echo decodeHtml($description) ?>
        </div>
      </section>
    </article>

    <ul class="nav my-5">
      <li class="nav-item border">
        <a class="nav-link" href=<?php echo BASE_URL . "catalog.php" ?>>
          View More Products
        </a>
      </li>
    </ul>
  </main>
  <!-- ========= End of Container ======= -->

  <!-- =========== Footer =========== -->
  <?php require_once ROOT_PATH . "/includes/footer.php" ?>
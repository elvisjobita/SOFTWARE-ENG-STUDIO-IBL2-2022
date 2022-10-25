<?php
// Config file MUST come first
require_once realpath(dirname(__FILE__)) . "/config.php";
require_once ROOT_PATH . "/includes/header.php";
require_once ROOT_PATH . "/controllers/items.php";

$items_arr = getItems();
?>

<title>Estore | Catalog</title>
</head>

<body>
  <!-- ============ Navbar ========== -->
  <?php require_once ROOT_PATH . "/includes/navbar.php" ?>

  <!-- =========== App Container ============ -->
  <main class="container" id="main">
    <!-- Conditionaly render items -->
    <?php if (count($items_arr) != 0) { ?>
      <!-- Available Products Section -->
      <section id="available" class="my-4">
        <h2 class="text-muted my-5">Available Products</h2>

        <div class="border shadow p-2" id="available-grid">
          <?php foreach ($items_arr as $key => $value) {
            // We use lists short-hand to destructure values
            [
              "id"       => $id,
              "slug"     => $slug,
              "name"     => $name,
              "price"    => $price,
              "image"    => $image,
              "discount" => $discount,
            ] = $value;
            $image ?: $image = BASE_URL . "/static/img/default.png";

            showPreview($image, $name, $id, $price, $discount, $slug);
          } ?>
        </div>
      </section>

      <div class="jumbotron my-4 py-4">
        <p>
          Thank you for wanting to shop with us. We have the best
          deals for Building and Construction Equipment.
        </p>
        <p>To view more products, go to
          <a href=<?php echo BASE_URL . "catalog.php" ?>> catalog</a>
        </p>
      </div>
    <?php } else { ?>
      <div class="jumbotron">
        <p class="h4 font-weight-light">
          No Items Found
        </p>
      </div>
    <?php } ?>
  </main>
  <!-- ========= End of Container ======= -->

  <!-- =========== Footer =========== -->
  <?php require_once ROOT_PATH . "/includes/footer.php" ?>
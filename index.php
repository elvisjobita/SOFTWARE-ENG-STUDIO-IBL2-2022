<?php
// Config file MUST come first
require_once realpath(dirname(__FILE__)) . "/config.php";
require_once ROOT_PATH . "/includes/header.php";
require_once ROOT_PATH . "/controllers/items.php";

$items_arr = getItems();
?>

<title>Estore | Home</title>
</head>

<body>
  <!-- ============ Navbar ========== -->
  <?php require_once ROOT_PATH . "/includes/navbar.php" ?>

  <!-- ============= Hero Section  =============== -->
  <section id="hero-section">
    <div class="container">
      <h1 class="hero-title">
      DELTA HARDWARE ESTORE
      </h1>

      <p class="hero-subtitle">
        Your one stop shop for <br />
        <kbd>Building and Construction Materials</kbd>
      </p>

      <p class="hero-text">
          Your safety is our priority
          Your satisfaction our dream.
          <br />  Construct your dream home with us.
          <br />  Our aim is that you will be safe.
          <br />  Best quality construction material with low cost!!

      </p>
      <a href="#featured" class="hero-btn shadow">Start Shopping</a>
    </div>
  </section>

  <!-- =========== End of Hero Section ============ -->

  <!-- =========== App Container ============ -->
  <main class="container" id="main">
    <!-- Conditionaly render items -->
    <?php if (count($items_arr) == 0) { ?>
      <div class="jumbotron">
        <p class="h4 font-weight-light">
          No Items Found
        </p>
      </div>

    <?php } else { ?>
      <?php $count = 0; ?>

      <!-- Featured Products Section -->
      <section id="featured" class="my-4">
        <h2 class="text-muted my-5">Featured Products</h2>

        <div class="border shadow p-2" id="featured-grid">
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
            if ($value["is_featured"] == 1 && $count < 4) {
              showPreview($image, $name, $id, $price, $discount, $slug);
              $count++;
            }
          } ?>
        </div>
      </section>


      <!-- Available Products Section -->
      <section id="available" class="my-4">
        <h2 class="text-muted my-5">Recomended Products</h2>

        <div class="border shadow p-2" id="available-grid">
          <?php $count = 0; ?>
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

            if ($count < 12) {
              showPreview($image, $name, $id, $price, $discount, $slug);
              $count++;
            }
          } ?>
        </div>
      </section>

      <?php if (count($items_arr) > 12) { ?>
        <a class="nav-link d-inline-block border my-4" href=<?php echo BASE_URL . "catalog.php" ?>>
          View More Products
        </a>
      <?php } ?>

      <div class="jumbotron my-4 py-4">
        <p>
          Thank you for wanting to shop with us.We made your dreams real.
            Construct a safe world with the World's best place for your building material.
            We give guarantee for your safety.
            New dreams, new home with us!</p>
        <p>To view more products, go to
          <a href=<?php echo BASE_URL . "catalog.php" ?>> catalog</a>
        </p>
      </div>

    <?php } ?>
  </main>
  <!-- ========= End of Container ======= -->

  <!-- =========== Footer =========== -->
  <?php require_once ROOT_PATH . "/includes/footer.php" ?>
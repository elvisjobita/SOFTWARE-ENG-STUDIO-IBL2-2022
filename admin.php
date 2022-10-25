<?php
require_once realpath(dirname(__FILE__)) . "/config.php";
require_once ROOT_PATH . "/includes/header.php";
require_once ROOT_PATH . "/utils/auth.php";
require_once ROOT_PATH . "/controllers/pay-modes.php";
require_once ROOT_PATH . "/controllers/items.php";

// Check access level
verifyAdmin();

// Handle form POST methods
if (isset($_POST["pay_mode_btn"])) {
  validatePayMode($_POST);
} elseif (isset($_POST["item_btn"])) {
  validateItem($_POST);
}

// Use null coalesce operator to check values
$name = $_SESSION["form_data"]["name"] ?? '';
$description = $_SESSION["form_data"]["description"] ?? '';
$price = $_SESSION["form_data"]["price"] ?? '';
$stock_qty = $_SESSION["form_data"]["stock_qty"] ?? '';
$image = $_SESSION["form_data"]["image"] ?? '';
$discount = $_SESSION["form_data"]["discount"] ?? '';
$pay_mode = $_SESSION["form_data"]["pay_mode"] ?? '';

?>

<title>Estore | Admin</title>
</head>

<body>
  <!-- ============ Navbar ========== -->
  <?php require_once ROOT_PATH . "/includes/navbar.php" ?>

  <!-- =========== App Container ============ -->
  <main class="container my-3" id="login-main">
    <!-- Admin Actions Section -->
    <div class="row border rounded shadow p-3">

      <!-- Admin Info -->
      <div class="col-12 jumbotron">
        Currently, you can only perform the actions below via the
        admin dashboard. More features are coming soon.
      </div>

      <!-- Add Pay Mode form -->
      <div class="col-sm-6 mb-4">
        <form action="" class="border rounded p-3 h-max" method="POST">
          <h4 class="my-4 text-muted">Add Pay Mode</h4>

          <!-- Name field -->
          <div class="form-group">
            <input type="text" class="form-control" name="pay_mode" value="<?php echo $pay_mode ?>" placeholder="PayMode Name" minlength="4" required>
          </div>

          <!-- Submit Button -->
          <button type="submit" name="pay_mode_btn" class="btn btn-primary btn-lg shadow">
            Add Pay Mode
          </button>
        </form>
      </div>

      <!-- Add Item form -->
      <div class="col-sm-6">
        <form action="" class="border rounded p-3 " method="POST">
          <h4 class="my-4 text-muted">Add Item</h4>

          <!-- Item Name field -->
          <div class="form-group">
            <input type="text" class="form-control" name="name" value="<?php echo $name ?>" placeholder="Item Name" minlength="4" required>
          </div>

          <!-- Item Description field -->
          <div class="form-group">
            <textarea name="description" class="form-control" cols="30" rows="10" placeholder="Item Description" required><?php echo $description ?></textarea>
          </div>

          <!-- Item Image field -->
          <div class="form-group">
            <input type="text" class="form-control" name="image" value="<?php echo $image ?>" placeholder="Item Image Link">
          </div>

          <!-- Item Stock Quantity Field -->
          <div class="form-group">
            <input type="number" class="form-control" name="stock_qty" value="<?php echo $stock_qty ?>" placeholder="Stock Quantity" min="1" required>
          </div>

          <!-- Item Price Field -->
          <div class="form-group">
            <input type="number" class="form-control" name="price" value="<?php echo $price ?>" placeholder="Item Price" min="1" required>
          </div>

          <!-- Item Discount Field -->
          <div class="form-group">
            <input type="number" class="form-control" name="discount" value="<?php echo $discount ?>" placeholder="Item Discount" required>
          </div>

          <!-- Item Featured Field -->
          <div class="form-check mb-3 border text-muted py-1">
            <input class="form-check-input" name="is_featured" type="checkbox" id="is_featured">
            <label class="form-check-label" for="is_featured">
              Is Featured
            </label>
          </div>

          <!-- Submit Button -->
          <button type="submit" name="item_btn" class="btn btn-primary btn-lg shadow">
            Add Item
          </button>
        </form>
      </div>

    </div>
  </main>
  <!-- ========= End of Container ======= -->

  <!-- =========== Footer =========== -->
  <?php require_once ROOT_PATH . "/includes/footer.php" ?>
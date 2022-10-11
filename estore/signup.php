<?php
require_once realpath(dirname(__FILE__)) . "/config.php";
require_once ROOT_PATH . "/includes/header.php";
require_once ROOT_PATH . "/utils/functions.php";

// Redirect if is_authenticated is set to true
if (isset($_SESSION["is_authenticated"])) {
  $_SESSION["errors"] = ["You can't signup while logged in"];
  exitToLocation("");
}

// Get form_data if available
$email = $_SESSION["form_data"]["email"] ?? '';
$username = $_SESSION["form_data"]["username"] ?? '';
$address_1 = $_SESSION["form_data"]["address_1"] ?? '';
$email_confirm = $_SESSION["form_data"]["email_confirm"] ?? '';
?>

<title>Estore | Signup</title>
</head>

<body>
  <!-- ============ Navbar ========== -->
  <?php require_once ROOT_PATH . "/includes/navbar.php" ?>

  <!-- =========== App Container ============ -->
  <main class="container my-5" id="signup-main">
    <div class="row">

      <!-- Signup section -->
      <div class="col-sm-6">
        <!-- Signup form -->
        <form action=<?php echo BASE_URL . "controllers/signup-login.php" ?> class="shadow-lg rounded py-3 px-4" method="POST" id="signup-form">
          <h1 class="my-4">Create Estore Account</h1>

          <!-- Username field -->
          <div class="form-group">
            <input type="text" class="form-control" name="username" value="<?php echo $username ?>" placeholder="Username" minlength="4" maxlength="30" required>
          </div>

          <!-- Email field -->
          <div class="form-group">
            <input type="email" class="form-control" name="email" value="<?php echo $email ?>" placeholder="Email Address" maxlength="90" required>
          </div>

          <!-- Email Confirmation field -->
          <div class="form-group">
            <input type="email" class="form-control" name="email_confirm" value="<?php echo $email_confirm ?>" placeholder="Confirm Email Address" maxlength="90" required>
          </div>

          <!-- Address Field -->
          <div class="form-group">
            <textarea name="address_1" id="address_1" class="form-control" cols="12" rows="4" placeholder="Address" required><?php echo $address_1 ?></textarea>
          </div>

          <!-- Password field -->
          <div class="form-group">
            <input type="password" class="form-control" name="password" placeholder="Password" minlength="6" maxlength="45" required>
          </div>

          <!-- Submit button -->
          <button type="submit" name="signup_btn" class="btn btn-lg btn-primary shadow">
            Signup
          </button>

          <!-- Login Link -->
          <p class="mt-4">
            Already a member?
            <a href=<?php echo BASE_URL . "login.php" ?>>Login</a>
          </p>

          <!-- Contact toggle Link -->
          <p class="h5">
            Need some help?
            <a href="#" id="contact-toggle" class="">Contact us</a>
          </p>
        </form>
      </div>

      <!-- Contact Section -->
      <div class="col-sm-6 mb-4 hide" id="contact-form">
        <div class="d-flex flex-column py-3 px-4 shadow-lg rounded">
          <p class="h1 my-4">Contact Us</p>

          <p class="h4 mb-4">
            <span class="float-left text-muted">Email </span>
            <span class="float-right font-weight-bold">
              contact@estore.com
            </span>
          </p>

          <p class="h4 mb-4">
            <span class="float-left text-muted">Phone </span>
            <span class="float-right">
              +254 707 522 411
            </span>
          </p>

          <p class="h4 mb-4">
            <span class="float-left text-muted">Office Address </span>
            <span class="float-right">
              Nyalenda, KISUMU - KENYA
            </span>
          </p>
        </div>
      </div>
    </div>
  </main>
  <!-- ========= End of Container ======= -->

  <!-- =========== Footer =========== -->
  <?php require_once ROOT_PATH . "/includes/footer.php" ?>
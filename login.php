<?php
require_once realpath(dirname(__FILE__)) . "/config.php";
require_once ROOT_PATH . "/includes/header.php";
require_once ROOT_PATH . "/utils/functions.php";

// Redirect if is_authenticated is set to true
if (isset($_SESSION["is_authenticated"])) {
  $_SESSION["errors"] = ["You are already logged in"];
  exitToLocation("");
}

// Get form_data if available
$username = "";
if (isset($_SESSION["form_data"],
$_SESSION["form_data"]["username"])) {
  $username = $_SESSION["form_data"]["username"];
}
?>

<title>Estore | Login</title>
</head>

<body>
  <!-- ============ Navbar ========== -->
  <?php require_once ROOT_PATH . "/includes/navbar.php" ?>

  <!-- =========== App Container ============ -->
  <main class="container my-5" id="login-main">
    <!-- Login section -->
    <section class="">

      <!-- Login form -->
      <form action=<?php echo BASE_URL . "controllers/signup-login.php" ?> class="w-100 shadow-lg rounded py-3 px-4" method="POST" id="login-form">
        <h1 class="my-4">Login to Estore</h1>

        <!-- Username field -->
        <div class="form-group">
          <input type="text" class="form-control" name="username" value="<?php echo $username ?>" placeholder="Username" minlength="4" maxlength="30" required>
        </div>

        <!-- Password field -->
        <div class="form-group">
          <input type="password" class="form-control" name="password" placeholder="Password" minlength="6" maxlength="45" required>
        </div>

        <!-- Submit Button -->
        <button type="submit" name="login_btn" class="btn btn-primary btn-lg shadow">
          Login
        </button>

        <!-- Signup link -->
        <p class="mt-4">
          Need an account?
          <a href=<?php echo BASE_URL . "signup.php" ?>>
            Signup
          </a>
        </p>
      </form>
    </section>
  </main>
  <!-- ========= End of Container ======= -->

  <!-- =========== Footer =========== -->
  <?php require_once ROOT_PATH . "/includes/footer.php" ?>
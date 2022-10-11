<?php

/* ========================================================
    Signup and Login process is initiated in this file.
    Invalid fields are handled and the invalid form is 
    re-rendered with user submitted data.
  ======================================================== */

require_once realpath(dirname(__FILE__)) . "/../config.php";
require_once ROOT_PATH . "/utils/functions.php";
require_once ROOT_PATH . "/utils/validate.php";

if (isset($_POST["signup_btn"])) {
  // Step 1. Validate required fields
  validateRequiredFields($_POST, "signup.php", "signup_btn");

  // Confirm if email matches
  if ($_POST["email"] !== $_POST["email_confirm"]) {
    $_SESSION["errors"] = ["Emails do not match"];
    $_SESSION["form_data"] = $_POST;
    exitToLocation("signup.php");
  }

  // Step 2. Sanitize user data
  $email    = sanitizeString($_POST["email"]);
  $username = sanitizeString($_POST["username"]);
  $address_1 = sanitizeString($_POST["address_1"]);
  $password = $_POST["password"];

  // Step 3. Call signup method if validation passes
  authSignup($username, $email, $password, $address_1);
}

if (isset($_POST["login_btn"])) {
  // Step 1. Validate required fields
  validateRequiredFields($_POST, "login.php", "login_btn");

  // Step 2. Sanitize user data
  $username = sanitizeString($_POST["username"]);
  $password = $_POST["password"];

  // Step 4. Call login method if validation passes
  authLogin($username, $password);
}

function authSignup($username, $email, $password, $address_1)
{
  $form_data = $_POST;

  $query =
    'SELECT email username FROM members WHERE email=? OR username=?';
  $stmt = tryQueryStmt($query);

  // Bind params and execute statement
  $stmt->bind_param('ss', $email, $username);
  $stmt->execute();
  $stmt->store_result();

  // Return if user exists
  if ($stmt->num_rows > 0) {
    $_SESSION["errors"] = ["Email $email or Username $username is taken"];
    $_SESSION["form_data"] = $form_data;
    exitToLocation("signup.php");
  }

  // Prepare statement and create user (with hashed password)
  $query = <<<_QUERY
    INSERT INTO members (email, username, password, address_1) 
    VALUES (?,?,?,?);
  _QUERY;
  $stmt = tryQueryStmt($query);
  $hashed_pwd = password_hash($password, PASSWORD_DEFAULT);
  $stmt->bind_param('ssss', $email, $username, $hashed_pwd, $address_1);
  $stmt->execute();

  // Always check rows affected
  if ($stmt->affected_rows < 1) {
    $_SESSION["errors"] = ["Could not place your order at this time"];
    $_SESSION["form_data"] = $form_data;
    exitToLocation("signup.php");
  }
  $stmt->close();

  // Redirect to login page
  unset($_SESSION["form_data"]);
  $_SESSION["success"] = ["Account created. Login to continue."];
  exitToLocation("login.php");
}

function authLogin($username, $password)
{
  $form_data = $_POST;

  $query = 'SELECT id, password, is_admin FROM members WHERE username=?';
  $stmt = tryQueryStmt($query);

  // Bind params and execute statement
  $stmt->bind_param('s', $username);
  $stmt->execute();
  $stmt->store_result();

  // No user found
  if ($stmt->num_rows == 0) {
    $_SESSION["errors"] = ["No account found!"];
    $_SESSION["form_data"] = $form_data;
    exitToLocation("login.php");
  }

  // Bind variables to prepared statement, then verify password
  $stmt->bind_result($id, $password, $is_admin);
  $stmt->fetch();
  if (!password_verify($_POST["password"], $password)) {
    $_SESSION["errors"] = ["Incorrect username or password"];
    $_SESSION["form_data"] = $form_data;
    exitToLocation("login.php");
  }

  // Password verified, now login
  session_regenerate_id();
  $_SESSION["id"] = $id;
  $_SESSION["is_authenticated"] = true;
  $is_admin && $_SESSION["is_admin"] = $is_admin;

  $stmt->close();

  unset($_SESSION["form_data"]);
  exitToLocation("");
}

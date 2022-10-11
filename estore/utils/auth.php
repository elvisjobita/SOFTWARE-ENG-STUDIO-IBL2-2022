<?php

require_once realpath(dirname(__FILE__)) . "/../config.php";
require_once ROOT_PATH . "/utils/functions.php";

// Grant access to admin users only
function verifyAdmin()
{
  verifyAuth();

  if (
    !isset($_SESSION["is_admin"]) ||
    $_SESSION["is_admin"] == false
  ) {
    $_SESSION["errors"] = ["Unauthorized: Access denied"];
    exitToLocation("");
  }
  return true;
}

// Grant access to members only
function verifyAuth()
{
  if (!isset($_SESSION["is_authenticated"])) {
    $_SESSION["errors"] = ["Unauthenticated: Access denied\tLogin to view that page"];
    exitToLocation("login.php");
  }
  return true;
}

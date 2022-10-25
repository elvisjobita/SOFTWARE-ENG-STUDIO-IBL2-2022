<?php
/* ========================================================
    Only logged-in members are able to logout. An attempt 
    from non logged-in members will be redirected to index page
  ======================================================== */

require_once realpath(dirname(__FILE__)) . "/config.php";
require_once ROOT_PATH . "/utils/functions.php";

if (isset($_SESSION["is_authenticated"])) {
  $_SESSION = [];

  if (session_id() != "" || isset($_COOKIE[session_name()])) {
    setcookie(session_name(), "", time() - 2592000, "/");
  }
  session_destroy();

  exitToLocation("");
} else {
  $_SESSION["errors"] = ["You can't log out if not logged in"];
  exitToLocation("");
}

<?php
require_once realpath(dirname(__FILE__)) . "/../config.php";
require_once ROOT_PATH . "/utils/functions.php";
require_once ROOT_PATH . "/utils/validate.php";


function validatePayMode(array $form_data)
{
  $location = "admin.php";
  validateRequiredFields($form_data, $location, "pay_mode_btn");

  if (
    strlen(validateLength("PayMode Name", $form_data["pay_mode"], 1, 49)) > 0
  ) {
    $_SESSION["errors"] = ["PayMode Name must be 1 to 49 characters"];
    $_SESSION["form_data"] = $form_data;
    exitToLocation($location);
  }
  $pay_mode = sanitizeString($form_data["pay_mode"]);
  addPayMode($form_data, $pay_mode, $location);
}

function addPayMode(array $form_data, string $name, string $location)
{
  $find_stmt = 'SELECT id, name FROM pay_modes WHERE name=?';
  $stmt = tryQueryStmt($find_stmt, $location);

  // Bind params and execute statement
  $stmt->bind_param('s', $name);
  $stmt->execute();
  $stmt->store_result();

  // Re render form with errors if payment mode exists
  if ($stmt->num_rows > 0) {
    $_SESSION["errors"] = ["PayMode $name already exists"];
    $_SESSION["form_data"] = $form_data;
    exitToLocation($location);
  }

  // Prepare statement, bind params then execute to create category
  $query = 'INSERT INTO pay_modes (name) VALUES (?)';
  $stmt = tryQueryStmt($query, $location);
  $stmt->bind_param('s', $name);
  $stmt->execute();

  // Always check rows affected
  if ($stmt->affected_rows < 1) {
    $_SESSION["errors"] = ["Error adding pay mode"];
    $_SESSION["form_data"] = $form_data;
    exitToLocation($location);
  }
  $stmt->close();
  unset($_SESSION["form_data"]);
  $_SESSION["success"] = ["Payment Mode $name added!"];
  exitToLocation($location);
}

if (isset($_GET["api"])) {
  getPayModes();
}

// Send available pay modes in json format
function getPayModes()
{
  // Only fetch available pay modes
  $query = <<<_QUERY
      SELECT id, name
      FROM pay_modes
      WHERE is_available=?
    _QUERY;
  $stmt = tryApiQuery($query);
  if ($stmt === null) return serverError();

  $status = 1;
  $stmt->bind_param('i', $status);
  $stmt->execute();
  $stmt->store_result();

  if ($stmt->num_rows == 0) {
    return clientError(404, "Pay Mode not found");
  }
  $stmt->bind_result($id, $name);
  $total_rows = $stmt->num_rows;
  $count = 0;
  $data = ["total_rows" => $total_rows];

  while (($stmt->fetch()) != false) {
    $data[$count] = ["id" => $id, "name" => $name];
    $count++;
  }
  $stmt->close();

  return jsonResponse($data);
}

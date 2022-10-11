<?php

require_once realpath(dirname(__FILE__)) . "/../config.php";

// Global response object
$json_res_arr =
  ["success" => true, "message" => "Okay", "payload" => []];

/* 
  Strips HTML,XML and PHP tags from a string and 
  converts all applicable characters to HTML entities
*/
function sanitizeString(string $str)
{
  $stripped_str = strip_tags($str);
  return htmlentities($stripped_str);
}

// Convert special characters to HTML entities
function encodeHtml(string $html_string)
{
  return htmlspecialchars($html_string);
}

// Convert special HTML entities back to characters
function decodeHtml(string $html_string)
{
  return htmlspecialchars_decode($html_string);
}

// Converts a string to a slug (good for url use)
function slugify(string $string)
{
  $slug = strtolower(
    trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $string))
  );
  return $slug;
}


// Create a table with given $name and $columns.
function createTable(string $name, string $columns)
{
  queryMySQL(
    "CREATE TABLE IF NOT EXISTS $name($columns)"
  );
  echo "Table <b>$name</b> created if it didn't exist<br/>";
}

// Runs a MySQL query against a database
function queryMySQL($query)
{
  global $connection;
  $result = $connection->query($query);

  if (!$result) {
    $connection->close();
    die("Database query error!<br/>");
  }
  return $result;
}

/* 
  Handle repeated database queries using prepared statements.
  Exit the program if connection fails
*/
function tryQueryStmt(string $query)
{
  global $connection;
  if (!$stmt = $connection->prepare($query)) {
    $connection->close();
    die("Database connection failed");
  }
  return $stmt;
}

/* 
  Handle repeated API database queries using prepared statements.
  Returns connection object on success or null on failure
*/
function tryApiQuery(string $query)
{
  global $connection;
  if (!$stmt = $connection->prepare($query)) {
    $connection->close();
    return null;
  }
  return $stmt;
}

// Calculate disounted price
function calcDiscountedPrice(int $price, int $disc)
{
  // Add 0 to make sure it's converted to int
  $price + 0;
  $disc + 0;
  return $disc > 0 ? $price - ($price * $disc) / 100 : $price;
}

// These parameters are used by item-preview.php
function showPreview($image, $name, $id, $price, $discount, $slug)
{
  include ROOT_PATH . "/includes/item-preview.php";
}

// Exits the program and redirects to location provided
function exitToLocation(string $location)
{
  global $connection;
  $connection->close();
  header("Location: " . BASE_URL . "$location");
  exit;
}


// Returns on server and database errors
function serverError()
{
  global $json_res_arr;
  header("Content-type: application/json");
  http_response_code(500);
  $json_res_arr["success"] = false;
  $json_res_arr["message"] = "Server error occured!";
  echo json_encode($json_res_arr);
  exit;
}


// Returns client side error (4xx)
function clientError(int $status, string $msg)
{
  global $json_res_arr;
  header("Content-type: application/json");
  http_response_code($status);
  $json_res_arr["success"] = false;
  $json_res_arr["message"] = $msg;
  echo json_encode($json_res_arr);
  exit;
}

// Returns a json formated response with payload
function jsonResponse($data)
{
  global $json_res_arr;
  header("Content-type: application/json");
  $json_res_arr["payload"] = $data;
  echo json_encode($json_res_arr);
  exit;
}

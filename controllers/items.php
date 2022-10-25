<?php

require_once realpath(dirname(__FILE__)) . "/../config.php";
require_once ROOT_PATH . "/utils/functions.php";
require_once ROOT_PATH . "/utils/validate.php";

function validateItem(array $form_data)
{
  $location = "admin.php";

  validateRequiredFields($form_data, $location, "item_btn", "discount", "featured", "image");

  // Field length validation
  $errors = [];
  foreach ($form_data as $field => $value) {
    switch ($field) {
      case "name":
        $invalid = validateLength($field, $value, 4, 95);
        if (strlen($invalid) > 0) array_unshift($errors, $invalid);
        break;
      case "description":
        $invalid = validateLength($field, $value, 50, 4090);
        if (strlen($invalid) > 0) array_unshift($errors, $invalid);
        break;
      default:
        break;
    }
  }

  // Re render if $errors is not empty
  if (count($errors) > 0) {
    $_SESSION["errors"] = $errors;
    $_SESSION["form_data"] = $form_data;
    exitToLocation($location);
  }

  // Sanitize input data
  foreach ($form_data as $field => $value) {
    if ($field == "description" || $field == "name") {
      $form_data[$field] = htmlspecialchars($value);
      continue;
    }
    $form_data[$field] = sanitizeString($value);
  }

  addItem($form_data, $location);
}

$get_item_query = <<<_GET_ITEM_QUERY
  SELECT id, name, slug, image, price, discount, description,
         stock_qty, is_featured
  FROM items
_GET_ITEM_QUERY;

function addItem(array $form_data, string $location)
{
  $query = 'SELECT id, slug, name FROM items WHERE slug=?';
  $stmt = tryQueryStmt($query, $location);

  // Extract data
  $is_featured = isset($form_data["is_featured"]) ? 1 : 0;
  $description = $form_data["description"];
  $discount    = $form_data["discount"];
  $price       = $form_data["price"];
  $image       = $form_data["image"];
  $name        = $form_data["name"];
  $stock_qty   = $form_data["stock_qty"];

  // Bind params and execute statement
  $slug = slugify($name);
  $stmt->bind_param('s', $slug);
  $stmt->execute();
  $stmt->store_result();

  // Re render form with errors if product exists
  if ($stmt->num_rows > 0) {
    $_SESSION["errors"] = ["Product with that title already exists"];
    $_SESSION["form_data"] = $form_data;
    exitToLocation($location);
  }

  // Prepare statement, bind params then execute to create category
  $query = <<<_QUERY
    INSERT INTO items (name, slug, description, image, price, discount, 
                        stock_qty, is_featured)
    VALUES (?,?,?,?,?,?,?,?)
  _QUERY;
  $stmt = tryQueryStmt($query, $location);
  $stmt->bind_param(
    'ssssiiii',
    $name,
    $slug,
    $description,
    $image,
    $price,
    $discount,
    $stock_qty,
    $is_featured
  );
  $stmt->execute();

  // Always check rows affected
  if ($stmt->affected_rows < 1) {
    $_SESSION["errors"] = ["Error adding item"];
    $_SESSION["form_data"] = $form_data;
    exitToLocation($location);
  }
  $stmt->close();
  unset($_SESSION["form_data"]);
  $_SESSION["success"] = ["Item $name added!"];
  exitToLocation($location);
}

/* 
  We could use get_result() to get all fields, but since it
  depends on MySQL native driver which might not be available, 
  we just store_result(). Thats why we have to bind_result()
  afterwards, making this function rather long
*/
function getItemById(int $id)
{
  global $get_item_query;

  // Return if id is not numeric
  if (!is_numeric($id)) {
    $_SESSION["errors"] = ["Invalid id"];
    exitToLocation("");
  }

  $query = $get_item_query .= " WHERE id=? LIMIT 1";
  $stmt = tryQueryStmt($query);

  // Bind params and execute statement
  $stmt->bind_param('i', $id);
  $stmt->execute();

  $stmt->store_result();

  if ($stmt->num_rows == 0) {
    $_SESSION["errors"] = ["Item not found"];
    return [];
  }

  // Bind variables to prepared statement, then fetch
  $stmt->bind_result(
    $id,
    $name,
    $slug,
    $image,
    $price,
    $discount,
    $description,
    $stock_qty,
    $is_featured,
  );

  $stmt->fetch();
  $stmt->close();

  $image ?: $image = BASE_URL . "/static/img/default.png";

  return [
    "id"            => $id,
    "name"          => decodeHtml($name),
    "slug"          => $slug,
    "image"         => $image,
    "discount"      => $discount,
    "is_featured"   => $is_featured,
    "price"         => $price,
    "stock_qty"     => $stock_qty,
    "description"   => decodeHtml($description)
  ];
}

function getItems()
{
  global $get_item_query;

  $stmt = tryQueryStmt($get_item_query);
  $result = queryMySQL($get_item_query);

  if ($result->num_rows == 0) {
    $_SESSION["errors"] = ["No items found"];
    return [];
  }

  // Loop through result set
  $items = [];
  while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
    $row["image"] ?: $row["image"] = BASE_URL . "/static/img/default.png";
    array_push($items, $row);
  }

  $stmt->close();
  return $items;
}

// Handle AJAX/API queries
if (isset($_GET["api"]) && $_GET["api"] == "items") {
  return jsonResponse(getItems());
}

if (isset($_GET["api-id"]) && is_numeric($_GET["api-id"])) {
  return jsonResponse(getItemById($_GET["api-id"]));
}

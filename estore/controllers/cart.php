<?php
require_once realpath(dirname(__FILE__)) . "/../config.php";
require_once ROOT_PATH . "/utils/functions.php";
require_once ROOT_PATH . "/utils/validate.php";

// Cart variables
$cart       = $_SESSION["cart"];
$totals     = $cart["cart_totals"];
$items      = $cart["cart_items"];
$amounts    = $cart["cart_items_amount"];
$quantities = $cart["cart_items_quantity"];

// Calls updateCart function, then returns updated cart values
function getCart()
{
  global $totals, $items, $amounts, $quantities;
  return updateCart($totals, $items, $amounts, $quantities);
}

// Updates the cart variable in session
function updateCart($totals, $items, $amounts, $quantities)
{
  $_SESSION["cart"]["cart_items_quantity"] = $quantities;
  $_SESSION["cart"]["cart_items_amount"] = $amounts;
  $_SESSION["cart"]["cart_totals"] = $totals;
  $_SESSION["cart"]["cart_items"] = $items;

  return $_SESSION["cart"];
}

// Returns total `number` (int) of individual items in cart
function getTotalCartItems()
{
  global $quantities;
  $items_in_cart = 0;
  if (count($quantities) > 0) {
    foreach ($quantities as $key => $arr) {
      foreach ($arr as $slug => $value) {
        $items_in_cart += $value;
      }
    }
  }
  return $items_in_cart;
}

// Returns total `number` (int) in cart, of the item with given slug
function getQtyInCart(string $slug)
{
  global $quantities;
  $qty = 0;
  foreach ($quantities as $key => $arr) {
    key($arr) === $slug && $qty = $arr[$slug];
  }
  return $qty;
}

// Returns total `amounts` (int) of individual items in cart
function calcCartTotals(array $amounts)
{
  $totals = 0;
  foreach ($amounts as $key => $arr) {
    foreach ($arr as $slug => $value) {
      $totals += $value;
    }
  }
  return $totals;
}

// Check if two arrays are `stricty equal`
function areArraysEqual(array $arr1, array $arr2)
{
  array_multisort($arr1);
  array_multisort($arr2);
  return (serialize($arr1) === serialize($arr2));
}

// Add item to cart, then update the cart
function addToCart(array $post_arr)
{
  global $totals, $items, $amounts, $quantities;

  /* 
    We can optionaly check if item is in database.
    TODO: This is a planned, feature!
  */

  if (intval($post_arr["stock_qty"]) == 0) {
    $_SESSION["errors"] = ["Item out of stock"];
    $_SESSION["form_data"] = $post_arr;
    exitToLocation("");
  }

  if (intval($post_arr["cart_qty"]) < 1) {
    $_SESSION["error"] = ["Invalid Quantity"];
    $_SESSION["form_data"] = $post_arr;
    exitToLocation("cart.php");
  }

  $slug = $post_arr["slug"];
  $qty = $post_arr["cart_qty"];
  $match_found = FALSE;

  $final_price = calcDiscountedPrice(
    $post_arr["price"],
    $post_arr["discount"]
  );

  // Remove some fields from $post_arr
  $post_arr = array_diff_key(
    $post_arr,
    ["cart-btn" => "", "cart_btn_action" => ""]
  );

  // Case 1: Items array is empty
  if (count($items) == 0) {
    array_push($items, $post_arr);
  } else {
    // Case 2: Conditionally add item to array
    foreach ($items as $key => $arr) {
      if (areArraysEqual($arr, $post_arr)) {
        $match_found = TRUE;
      } elseif ($arr["slug"] == $slug && $arr["cart_qty"] != $qty) {
        $items[$key]["cart_qty"] = $qty;
        $match_found = TRUE;
      }
    }
    $match_found === FALSE && array_push($items, $post_arr);
  }

  // Set or increment cart_items_quantity
  if (count($quantities) == 0) {
    array_push($quantities, [$slug => $qty]);
  } else {
    $match_found === FALSE &&
      array_push($quantities, [$slug => $qty]);

    foreach ($quantities as $key => $arr) {
      if (key($arr) === $slug) $quantities[$key][$slug] = $qty;
    }
  }

  // Set or increment cart_items_amount
  if (count($amounts) == 0) {
    array_push($amounts, [$slug => $final_price]);
  } else {
    $match_found === FALSE &&
      array_push($amounts, [$slug => $final_price]);

    foreach ($amounts as $key => $arr) {
      if (key($arr) === $slug) {
        $amounts[$key][$slug] = $final_price * ($qty + 0);
      }
    }
  }

  // Calculate total cart price
  $totals = calcCartTotals($amounts);

  return updateCart($totals, $items, $amounts, $quantities);
}

// Remove item from cart, then updates the cart
function removeFromCart(string $slug)
{
  global $items, $quantities, $amounts;

  // Remove from items array
  foreach ($items as $key => $arr) {
    if ($arr["slug"] == $slug) unset($items[$key]);
  }

  // Remove from quantities array
  foreach ($quantities as $key => $arr) {
    if (key($arr) == $slug) unset($quantities[$key]);
  }

  // Remove from amounts array
  foreach ($amounts as $key => $arr) {
    if (key($arr) == $slug) unset($amounts[$key]);
  }

  // Calculate cart totals
  $totals = calcCartTotals($amounts);

  return updateCart($totals, $items, $amounts, $quantities);
}

// Clear the cart and redirect to homepage
function clearCart()
{
  $_SESSION["cart"] = [
    "cart_totals" => 0,
    "cart_items" => [],
    "cart_items_amount" => [],
    "cart_items_quantity" => [],
  ];
  $_SESSION["success"] = ["Your Shopping cart has been cleared"];
  exitToLocation("");
}

// Hanlde checkout and create order
function handleCheckout($post_arr)
{
  validateRequiredFields(
    $post_arr,
    "checkout.php",
    "checkout_btn"
  );
  $query = 'SELECT id, name FROM pay_modes WHERE id=?';
  $stmt = tryQueryStmt($query);

  // Bind params and execute statement
  $stmt->bind_param('i', $post_arr["pay_method"]);
  $stmt->execute();
  $stmt->store_result();

  // Return if paymode not available
  if ($stmt->num_rows == 0) {
    $_SESSION["errors"] = ["Pay Mode not available currently"];
    $_SESSION["form_data"] = $post_arr;
    exitToLocation("");
  }
  $stmt->bind_result($id, $name);
  $stmt->fetch();
  $stmt->close();

  // First we create the order then we create the order_details
  $order_id = createOrder($post_arr["user_id"], $id);

  // Create order details and redirect to home/success page
  createOrderDetails(intval($order_id));
}

// Create an order
function createOrder(int $member_id, int $pay_mode_id)
{
  $query = <<<_QUERY
      INSERT INTO orders (member_id, pay_mode_id)
      VALUES (?,?)
    _QUERY;
  $stmt = tryQueryStmt($query);
  $stmt->bind_param('ii', $member_id, $pay_mode_id);
  $stmt->execute();

  // Always check rows affected
  if ($stmt->affected_rows < 1) {
    $_SESSION["errors"] = ["Could not place your order at this time"];
    exitToLocation("checkout.php");
  }
  $lastId = $stmt->insert_id;
  $stmt->close();

  return $lastId;
}

// Create order details
function createOrderDetails(int $order_id)
{
  global $items;

  foreach ($items as $key => $item_arr) {
    // We use lists short-hand to destructure values
    [
      "id"       => $item_id,
      "price"    => $price,
      "discount" => $discount,
      "cart_qty" => $quantity,
    ] = $item_arr;
    $total_price = calcDiscountedPrice($price, $discount) * $quantity;

    $query = <<<_QUERY
        INSERT INTO order_details (
          item_id, order_id, quantity, discount, price, total_price
        )
        VALUES (?,?,?,?,?,?)
      _QUERY;
    $stmt = tryQueryStmt($query);
    $stmt->bind_param(
      'iiiiii',
      $item_id,
      $order_id,
      $quantity,
      $discount,
      $price,
      $total_price
    );
    $stmt->execute();

    // Always check rows affected
    if ($stmt->affected_rows < 1) {
      $_SESSION["errors"] = ["Could not place your order at this time"];
      exitToLocation("checkout.php");
    }
  }

  // Add order_number to session and clear cart from session
  $_SESSION["order_number"] = $order_id;
  unset($_SESSION["cart"]);

  $_SESSION["success"] = ["Order $order_id created!"];
  exitToLocation("");
}

// Handle AJAX/API calls
if (isset($_GET["slug"])) {
  return jsonResponse(getQtyInCart($_GET["slug"]));
}

if (isset($_GET["fetch"])) {
  return jsonResponse(getCart());
}

<?php

/* ======================================================
  This file sends members data to client side via ajax
 ====================================================== */

require_once realpath(dirname(__FILE__)) . "/../config.php";
require_once ROOT_PATH . "/utils/functions.php";

// Check auth
if (!isset($_SESSION["is_authenticated"], $_SESSION["id"])) {
  return clientError(401, "Unauthenticated: Access Denied");
}

getMember();

function getMember()
{
  $query = <<<_QUERY
      SELECT username, email, address_1
      FROM members
      WHERE members.id=?
    _QUERY;
  $stmt = tryApiQuery($query);
  if ($stmt === null) return serverError();

  // Bind params and execute statement
  $id = $_SESSION["id"];
  $stmt->bind_param('i', $id);
  $stmt->execute();
  $stmt->store_result();

  if ($stmt->num_rows == 0) {
    return clientError(404, "User not found");
  }

  // Bind variables to prepared statement, then fetch
  $stmt->bind_result($username, $email, $address_1,);
  $stmt->fetch();
  $stmt->close();

  $data = [
    "email" => $email,
    "username" => $username,
    "address1" => $address_1,
  ];

  return jsonResponse($data);
}

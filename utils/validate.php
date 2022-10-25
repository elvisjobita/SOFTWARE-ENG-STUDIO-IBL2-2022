<?php

require_once realpath(dirname(__FILE__)) . "/../config.php";

/**
 * Check if required fields are in array and ignores 
 * the values in $ignored array. 
 * 
 * Each value in the array is trimmed before validation.
 * If validation fa.ils, you are redirected to
 * $location with $fields as error payload.
 * @param array $fields
 * @param string $location
 */
function validateRequiredFields($fields, $location, ...$ignored)
{
  $missing = [];

  foreach ($fields as $field => $value) {
    if (in_array($field, $ignored)) continue;

    // Check if $value is empty
    empty(trim($value)) &&
      array_push($missing, "The field <b>$field</b> is required");

    // Validate email $field
    if (
      $field === "email" &&
      !filter_var($value, FILTER_VALIDATE_EMAIL)
    ) {
      array_push($missing, "$value is invalid email");
    }

    // Validate username $field (alpha numeric only)
    if (
      $field === "username" &&
      (preg_match("/[a-zA-Z0-9]+/", $value) == 0)
    ) {
      array_push($missing, "$field cannot contain special characters");
    }

    // Validate password $field (min length 6, max length 50)
    if (
      $field === "password" &&
      (strlen($value) < 6 || strlen($value) > 50)
    ) {
      array_push(
        $missing,
        "Password must have at least 6 chars and maximum 50 chars"
      );
    }
  }

  if (count($missing) > 0) {
    $_SESSION["errors"] = $missing;
    $_SESSION["form_data"] = $fields;
    return exitToLocation($location);
  }
}

/* 
  Check if the $field contains at least $min characters
  and does not exceed $max characters.
*/
function validateLength(string $field, string $val, int $min, int $max)
{
  $invalid = "";
  $val = trim($val);
  if (
    empty($val) ||
    (strlen($val) < $min) ||
    (strlen($val) > $max)
  ) {
    $invalid = "<b>$field</b> must be $min to $max chars";
  }
  return $invalid;
}

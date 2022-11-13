<?php

/* ========================================================
    Setup/Configuration instructuons. (READ CAREFULLY!!)

    1.  Always start the session for Auth purposes.
    2.  Take note of the ROOT_PATH. It is set to the current 
        project directory.
        Any PHP file that imports this file can use ROOT_PATH.
    3.  BASE_URL is a web address that will appear on the 
        browser url tab. On my machine it is set as
        http://localhost/estore/
    4.  Always initialize the cart into session if it doesn't exist
    5.  Always connect to database in this file (make sure
        you have created the database before connecting).
    6.  Always include this file AT THE TOP wherever it's used.

    !!!!!!!!!!!! IMPORTANT !!!!!!!!!!!!!!
    =>  After creating the database with the correct credentials,
        YOU MUST RUN `init-tables.php` ONLY ONCE.

    =>  If you change the BASE_URL pathname to something other than 
        `/estore/`, YOU MUST also change the constant `base_url` 
        difined in the `scripts.js` file inside `static/js/` folder.
  ======================================================== */

session_start();
define("ROOT_PATH", realpath(dirname(__FILE__)));
define("BASE_URL", "http://localhost:8081/");

if (!isset($_SESSION["cart"])) {
  $_SESSION["cart"] = [
    "cart_totals" => 0,
    "cart_items" => [],
    "cart_items_amount" => [],
    "cart_items_quantity" => [],
  ];
}

$host = "localhost";
$user = "root";
$pwd  = "";
$name = "estore";

$connection = new mysqli($host, $user, $pwd, $name);

if ($connection->connect_error) {
  die("Database connection error!\n"
    . $connection->connect_error);
}

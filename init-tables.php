<?php

/* ========================================================
    Tables creation initialzation instructions:

    1.  Make sure to bring in the utils/functions.php file.
    2.  Change or adjust table columns as needed.
    3.  This file MUST ONLY BE RUN ONE TIME BEFORE CREATING
        ANYTHING ON THE DATABASE.
  ======================================================== */

require_once realpath(dirname(__FILE__)) . "/utils/functions.php";

// You can change this to anything
$default_img = BASE_URL . "/static/img/default.png";

// 1. Members Table
createTable(
  "members",
  "id INT(10) PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(100) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(100) NOT NULL,
    is_admin TINYINT(1) NOT NULL DEFAULT 0,
    address_1 VARCHAR(100),
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP"
);

// 2. Items Table
createTable(
  "items",
  "id INT(10) PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) NOT NULL UNIQUE,
    description VARCHAR(4096) NOT NULL,
    image VARCHAR(255) NOT NULL DEFAULT '$default_img',
    price DECIMAL(8,2) NOT NULL,
    discount DECIMAL(8,2) NOT NULL,
    stock_qty INT(10) NOT NULL,
    is_featured TINYINT(1) NOT NULL DEFAULT 0,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP"
);

// 3. Payment Modes Table
createTable(
  "pay_modes",
  "id INT(10) PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(50) NOT NULL UNIQUE,
    is_available TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP"
);

// 4. Orders Table
createTable(
  "orders",
  "id INT(10) PRIMARY KEY AUTO_INCREMENT,
    member_id INT NOT NULL,
    pay_mode_id INT NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (member_id) REFERENCES members (id),
    FOREIGN KEY (pay_mode_id) REFERENCES pay_modes (id)"
);

// 5. Order_Details Table
createTable(
  "order_details",
  "id INT(10) PRIMARY KEY AUTO_INCREMENT,
    item_id INT NOT NULL,
    order_id INT NOT NULL,
    quantity INT(5) NOT NULL,
    discount DECIMAL(8,2) NOT NULL,
    price DECIMAL(8,2) NOT NULL,
    total_price DECIMAL(8,2) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (item_id) REFERENCES items (id),
    FOREIGN KEY (order_id) REFERENCES orders (id)"
);

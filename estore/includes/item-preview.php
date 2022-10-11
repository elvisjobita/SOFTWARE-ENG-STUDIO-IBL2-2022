<?php

require_once realpath(dirname(__FILE__)) . "/../config.php";
require_once ROOT_PATH . "/utils/functions.php";
?>

<div class="card my-2" style="width: 16.5rem; margin: auto;">
  <!-- Conditionaly show on sale text -->
  <?php if (intval($discount) > 30) { ?>
    <p class="badge badge-warning position-absolute shadow p-3">
      On SALE!!
    </p>
  <?php } ?>

  <!-- Item Image -->
  <a href=<?php echo BASE_URL . "item.php?id=$id&item-slug=$slug" ?>>
    <img src=<?php echo "$image" ?> class="card-img-top border border-bottom p-2" alt=<?php echo $name ?>>
  </a>

  <div class="card-body px-2">
    <!-- Item Title -->
    <a href=<?php echo BASE_URL . "item.php?id=$id&item-slug=$slug" ?> class="border-top">
      <h5 class="card-title">
        <?php echo $name ?>
      </h5>
    </a>
    <!-- Item Price -->
    <h4 class="d-block my-3 font-weight-bold">
      $ <?php echo calcDiscountedPrice($price, $discount) ?>
    </h4>
  </div>

  <div class="card-footer p-2">
    <form action=<?php echo BASE_URL . "cart.php" ?> method="post" class="form-inline cart-add-form" data-item-id=<?php echo $id ?> data-item-slug=<?php echo $slug ?>>

      <a href=<?php echo BASE_URL . "item.php?id=$id&item-slug=$slug" ?> class="btn btn-outline-primary mr-3">
        View Item
      </a>

      <input type="hidden" name="cart_qty" value="1">

      <input type="hidden" name="cart_btn_action" value="cart-btn-add">

      <button type="submit" name="cart-btn" id="cart-btn" class="btn btn-primary pd-add-to-cart">
        Add to cart
      </button>
    </form>
  </div>
</div>
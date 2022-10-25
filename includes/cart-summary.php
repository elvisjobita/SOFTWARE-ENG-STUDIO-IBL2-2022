<!-- Cart Summary -->
<aside class="cart-summary col-md-4 d-flex flex-column border rounded py-3 shadow-sm h-max pl-3">
  <!-- Cart Summary Title -->
  <h4 class="text-muted border-bottom pb-2">Order Summary</h4>

  <!-- Cart Summary - Order SubTotal -->
  <p class="font-weight-light">
    <span class="float-left">Subtotoal </span>
    <strong class="order-subtotal float-right">
      $ <?php echo $cart["cart_totals"] ?>
    </strong>
  </p>

  <!-- Cart Summary - Order Coupon -->
  <p class="text-muted">
    <span class="float-left">Coupon </span>
    <strong class="order-coupon float-right">$ 0.00</strong>
  </p>

  <!-- Cart Summary - Order Total -->
  <h4 class="my-3 border-top border-bottom py-2">
    <span class="float-left">Total </span>
    <span class="order-total float-right">
      $ <?php echo $cart["cart_totals"] ?>
    </span>
  </h4>

  <!-- Cart Actions -->
  <div class="d-flex w-100">
    <!-- Clear Cart Form -->
    <form action=<?php echo BASE_URL . "cart.php" ?> method="post">
      <button type="submit" name="cart-btn-clear" class="btn btn-sm btn-secondary p-2 mr-3">
        Clear Cart
      </button>
    </form>

    <!-- Continue Shopping Link -->
    <a href=<?php echo BASE_URL . "catalog.php" ?> class="nav-link border rounded p-2">
      Continue Shopping
    </a>
  </div>
</aside>
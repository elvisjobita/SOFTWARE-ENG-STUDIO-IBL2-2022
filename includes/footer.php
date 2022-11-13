    <!-- Return Policy Modal -->
    <div class="modal fade" id="return-policy" tabindex="-1" aria-labelledby="return-policy" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">

          <!-- Policy Title -->
          <div class="modal-header bg-dark text-white">
            <h5 class="modal-title" id="return-policy">
              Our Return Policy
            </h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>

          <!-- Policy Text -->
          <div class="modal-body">
            <p class="h5">
              30 day free returns policy: Subject to Terms and Conditions.
            </p>

            <div class="font-weight-light">
              <p class="h6">Requirement for a valid procedure</p>

              <ul>
                <li>Proof of purchase</li>
                <li>Original packaging</li>
                <li>Any free promotional items</li>
                <li>Valid return reason</li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="container">
        <!-- Footer Information -->
        <div class="row">
          <!-- Estore Brand Logo and Infomation-->
          <div class="col-md-4 mb-4">
            <a class="navbar-brand" href=<?php echo BASE_URL ?>>
              <img src=<?php echo BASE_URL . "static/img/logo.png" ?> width="40" height="40" alt="Estore logo" />
            </a>
            <p class="mt-2 mb-4">
              At Delta Estore, we strive to provide you with the best services.
              Our clients love us and they keep coming back form more. So,
              what are you waiting for? Start shopping with us today for
              unbeatable offers.
            </p>
            <a href=<?php echo BASE_URL ?> class="nav-link border p-2 w-max">
              Start shopping
            </a>
          </div>

          <!-- Quick Links Section -->
          <div class="col-md-4 mb-4">
            <p class="h3 mb-4">
              Quick Links
            </p>

            <a href=<?php echo BASE_URL . "cart.php" ?> class="nav-link py-2 px-0 border-bottom w-max">
              View shopping cart
            </a>
            <a href="" id="return-policy" class="nav-link mt-3 py-2 px-0 border-bottom w-max" data-toggle="modal" data-target="#return-policy">
              View Our Return Policy
            </a>
          </div>

          <!-- Contact and Newsletter Section -->
          <div class="col-md-4">
            <p class="h3 mb-4">
              Get in touch
            </p>

            <a href="" class="mr-4 py-2 px-0 w-max">
              <i class="fab fa-3x fa-facebook"></i>
            </a>
            <a href="" class="mr-4 py-2 px-0 w-max">
              <i class="fab fa-3x fa-twitter"></i>
            </a>
            <a href="" class="mr-4 py-2 px-0 w-max">
              <i class="fab fa-3x fa-youtube"></i>
            </a>
            <a href="" class="mr-4 py-2 px-0 w-max">
              <i class="fas fa-3x fa-phone"></i>
            </a>

            <p class="h3 mt-5 mb-2">
              Subscribe to Newsletter
            </p>

            <small class="form-text text-muted mb-3">
              Get latest information about new stock straight to your inbox
            </small>

            <form class="form-inline" method="POST">
              <div class="input-group mb-2 mr-sm-2">
                <div class="input-group-prepend">
                  <div class="input-group-text">@</div>
                </div>
                <input type="email" class="form-control" id="email" placeholder="Email">
              </div>
              <button type="submit" class="btn btn-primary mb-2">
                Subscribe
              </button>
            </form>
          </div>
        </div>
      </div>
    </footer>
    <!-- ======== End of Footer ======= -->

    
    <script src=<?php echo BASE_URL . "static/js/functions.js" ?>></script>
    <script src=<?php echo BASE_URL . "static/js/scripts.js" ?>></script>
    </body>

    </html>
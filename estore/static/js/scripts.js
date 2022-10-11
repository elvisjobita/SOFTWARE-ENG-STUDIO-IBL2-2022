/* ==========================================================
  Remmeber to ALWAYS include `functions.js` before this file.
  Also, define constants before functions.

  !!!!!!!! IMPORTANT !!!!!!!!!!!
  IF THE PATHNAME IS CHANGED to something other than /
  in the `config.php` file, YOU MUST also change the base_url
  and home_path constants to reflect that change. 

  Also, IF THE PATHNAME IS CHANGED, YOU MUST search and replace 
  ALL OCCURENCES of the word "estore" in this file with the 
  new value of the changed pathname.
 ========================================================== */

const base_url = `${location.origin}/`;
const home_paths = ["/", "/index.php"];

// Endpoints available
const endpoints = {
  items: "controllers/items.php?api=items",
  item: "controllers/items.php?api-id",
  pay_modes: "controllers/pay-modes.php?api=get",
  members: "controllers/members.php",
  cart: "cart.php?fetch",
  orders: "orders.php",
};

/* 
  Fields needed before adding item to cart.
  Each key represent an action to be performed
 */
const requiredCartFields = {
  add_or_update: [
    "id",
    "slug",
    "name",
    "price",
    "image",
    "discount",
    "stock_qty",
    "cart_qty",
    "cart_btn_action",
  ],
  remove: ["id", "slug", "cart_btn_action"],
};

// Perform Ajax functions when page is ready
$(document).ready(async function () {
  let is_home = home_paths.includes(location.pathname);

  // Handle home and catalog pages
  if (is_home || location.pathname == "/catalog.php") {
    updateAddToCartForm();
    await setCartQtyField();
  }

  // Handle `cart.php` page
  if (location.pathname == "/cart.php") {
    await handleCartPage();
  }

  // Handle `item.php` page
  if (location.pathname.startsWith("/item.php")) {
    updateAddToCartForm();
    await setCartQtyField();
    hadleItemPage();
  }

  // Hanlde `checkout.php` page
  if (location.pathname == "/checkout.php") {
    handleCheckoutPage();
  }

  // Attach listener to `contact-us` toggler
  $("#contact-toggle").click(function (e) { 
    e.preventDefault();
    $("#contact-form").toggle();
  });
});

// Get member data and pay modes data then update form fields
function handleCheckoutPage() {
  $.get(`${base_url}${endpoints.members}`).done(function (data) {
    // Append profile data to profile checkout form
    $("input[name='email']").attr("value", data.payload["email"]);
    $("textarea#address_1").val(data.payload["address1"]);
  });

  $.get(`${base_url}${endpoints.pay_modes}`).done(function (data) {
    // Append pay mode data to pay mode select element
    for (const key in data.payload) {
      // Remember to handle item with key=0
      if (key == "0" || parseInt(key, 10)) {
        const name = data.payload[key]["name"];
        $("#pay_method").append(new Option(name, data.payload[key]["id"]));
      }
    }
  });
}

// Get item from API then update fields
function hadleItemPage() {
  const id = searchUrlParam("id");
  $.get(`${base_url}${endpoints.item}=${id}`).done(function (data) {
    // We mutate the data.payload to suit cart fields
    const item = data.payload;
    delete item.description;
    item.cart_btn_action = "cart-btn-add";

    for (const key in item) {
      key == "cart_qty"
        ? ""
        : $(`[data-item-slug="${item.slug}"]`).append(
            createHiddenInput(key, item[key])
          );
    }
  });
}

// Adds fields to cart items
async function handleCartPage() {
  const response = await fetch(
    `${base_url}controllers/${endpoints.cart}`
  ).catch((err) => alert("An error occured"));
  if (response.status !== 200) alert("Error getting cart");
  const data = await response.json();

  // Create required cart input fields for each cart item
  payloadToArray(data.payload.cart_items).map((item) => {
    for (const key in item) {
      key == "cart_qty"
        ? ""
        : $(`[data-item-slug="${item.slug}"]`).append(
            createHiddenInput(key, item[key])
          );
    }
  });
}

// Handles setting 'cart_qty' hidden field
async function setCartQtyField() {
  // Get cart values
  const response = await fetch(
    `${base_url}controllers/${endpoints.cart}`
  ).catch((err) => alert("An error occured"));
  if (response.status !== 200) alert("Error getting cart");
  const data = await response.json();

  // Check items
  if (checkItemsQuantities(data.payload)) {
    checkItemsQuantities(data.payload).map((item) => {
      // Update cart_qty for items in cart
      for (const slug in item) {
        let form_selector = `[data-item-slug="${slug}"]`;
        let input_selector = "input[name=cart_qty]";
        updateQtyField(form_selector, input_selector, item[slug]);
      }
    });
  }
}

// Set 'cart_qty' input value in the form provided
function updateQtyField(form_selector, input_selector, input_value) {
  const handleAddToCart = function (event) {
    // Always prevent submit before hand
    event.preventDefault();

    // Get the input and increment by 1
    let curr_val = parseInt($(form_selector).find(input_selector).val());
    let max_val = parseInt(
      $(form_selector).find("input[name=stock_qty]").val()
    );

    // Do not update `cart_qty` if it's equal to `stock_qty`
    if (curr_val == max_val) {
      $(form_selector).find(input_selector).attr("value", curr_val);
    } else {
      $(form_selector)
        .find(input_selector)
        .attr("value", curr_val + 1);
    }

    // Remove listener to form submit, then submit form
    $(this).off("submit", handleAddToCart);
    $(this).submit();
  };

  // Only update the value if it's value is different from the
  // value in the cart
  if ($(form_selector).find(input_selector).val() != input_value) {
    $(form_selector).find(input_selector).attr("value", input_value);
  } else {
    $(form_selector).append(
      createHiddenInput("cart_qty", parseInt(input_value))
    );
  }
  $(form_selector).on("submit", handleAddToCart);
}

// Add fields from items array to (Add To Cart) form
function updateAddToCartForm() {
  $.get(`${base_url}${endpoints.items}`).done(function (data) {
    // Proceed only if data.payload has items
    if (payloadToArray(data.payload).length < 1) return;

    // Add fields to item previews
    payloadToArray(data.payload).map((item) => {
      const fields = extractFields(item);
      fields.cart_btn_action = "cart-btn-add";
      handleCartFields(`[data-item-id=${item.id}]`, "add", fields);
    });
  });
}

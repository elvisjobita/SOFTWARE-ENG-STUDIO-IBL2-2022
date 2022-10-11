/**
 * Handles creation of hidden input fields
 * based on the action provided, then append
 * to the DOM using the selector. Check the
 * requiredCartFields constant for action types
 * @param {String} selector
 * @param {String} action
 * @param {object} fields
 */
function handleCartFields(selector, action, fields) {
  switch (action) {
    case "add":
    case "update":
      for (const key in fields) {
        if (requiredCartFields.add_or_update.includes(key)) {
          $(selector).append(createHiddenInput(key, fields[key]));
        }
      }
      break;
    case "remove":
      for (const key in fields) {
        if (requiredCartFields.remove.includes(key)) {
          $(selector).append(createHiddenInput(key, fields[key]));
        }
      }
      break;
    default:
      break;
  }
}

/**
 * Extract key value pair from payload
 * @param {object} obj
 */
function extractFields(obj) {
  return {
    id: obj["id"],
    slug: obj["slug"],
    name: obj["name"],
    image: obj["image"],
    price: obj["price"],
    discount: obj["discount"],
    stock_qty: obj["stock_qty"],
    is_featured: obj["id_featured"],
  };
}

/**
 * Create hidden form input field
 * @param {String} value
 * @param {String} name
 * @returns {Element}
 */
function createHiddenInput(name, value) {
  return $("<input>")
    .attr("type", "hidden")
    .attr("name", name)
    .attr("value", value);
}

/**
 * Convert object payload to array
 * @param {object} payload
 * @returns {Array}
 */
function payloadToArray(payload) {
  const final_arr = [];
  for (const key in payload) {
    if (key == "0" || parseInt(key, 10)) {
      final_arr.push(payload[key]);
    }
  }
  return final_arr;
}

// Check if payload.cart_items_quantity has items
function checkItemsQuantities(payload) {
  const { cart_items_quantity } = payload;
  return cart_items_quantity.length > 0 ? cart_items_quantity : null;
}

// Get the value of a url parameter
function searchUrlParam(string) {
  let href = location.href;
  let url = new URL(href);
  return url.searchParams.get(string);
}

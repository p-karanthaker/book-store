function showBasket() {
  "use strict";
  var xmlhttp;
  xmlhttp = new XMLHttpRequest();

  xmlhttp.onreadystatechange = function () {
    if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
      $(".basket-items").html(xmlhttp.responseText);
    }
  };
  xmlhttp.open("post", "/resources/php/Basket.php", true);
  xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  xmlhttp.send("showBasket=" + true);
}

function getBasketDetails() {
  var data = [];
  var dataRows = $('.basket-items > tr#items');
  dataRows.each(function (index, value) {
    data.push($(this).attr("data-book-id"));
    data.push($(this).find("#quantity").val());
  });
  return data;
}

$(document).on('click', '.removeItem', function () {
  var data = $(this).closest('#items').attr('data-book-id');
  removeItem(data);
});

function removeItem(data) {
    "use strict";
    var xmlhttp;
    xmlhttp = new XMLHttpRequest();

    xmlhttp.onreadystatechange = function () {
      if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
        $("#basket-alert-section").append("<div id=alert" + uid + ">" + xmlhttp.responseText + "</div>");
        $("#basket-alert-section > div#alert".concat(uid)).show();
        $("#basket-alert-section > div#alert".concat(uid)).fadeOut(3000, function () {
          $(this).remove(); 
        });
        showBasket();
      }
    };
    xmlhttp.open("post", "/resources/php/Basket.php", true);
    xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xmlhttp.send("removeItem=" + data);
  }

var uid = 0;
$(document).on('click', '#updateBasket', function () {
  updateBasket();
  uid++;
});

function updateBasket() {
  "use strict";
  var xmlhttp;
  xmlhttp = new XMLHttpRequest();

  xmlhttp.onreadystatechange = function () {
    if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
      $("#basket-alert-section").append("<div id=alert" + uid + ">" + xmlhttp.responseText + "</div>");
      $("#basket-alert-section > div#alert".concat(uid)).show();
      $("#basket-alert-section > div#alert".concat(uid)).fadeOut(3000, function () {
        $(this).remove(); 
      });
      showBasket();
    }
  };
  var data = getBasketDetails();
  xmlhttp.open("post", "/resources/php/Basket.php", true);
  xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  xmlhttp.send("updateBasket=" + data);
}

$(document).on('click', '#emptyBasket', function () {
  emptyBasket();
  uid++;
});

function emptyBasket() {
  "use strict";
  var xmlhttp;
  xmlhttp = new XMLHttpRequest();

  xmlhttp.onreadystatechange = function () {
    if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
      $("#basket-alert-section").append("<div id=alert" + uid + ">" + xmlhttp.responseText + "</div>");
      $("#basket-alert-section > div#alert".concat(uid)).show();
      $("#basket-alert-section > div#alert".concat(uid)).fadeOut(3000, function () {
        $(this).remove(); 
      });
      showBasket();
    }
  };
  xmlhttp.open("post", "/resources/php/Basket.php", true);
  xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  xmlhttp.send("emptyBasket=" + true);
}

$(document).on('click', '#checkoutNow', function () {
  checkout();
});

function checkout() {
  "use strict";
  var xmlhttp;
  xmlhttp = new XMLHttpRequest();

  xmlhttp.onreadystatechange = function () {
    if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
      $('#orderDetails').html(xmlhttp.responseText);
      showBasket();
    }
  };
  xmlhttp.open("post", "/resources/php/Order.php", true);
  xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  xmlhttp.send("placeOrder=" + true);
}

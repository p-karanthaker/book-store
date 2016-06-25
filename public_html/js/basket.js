function showBasket() {
  "use strict";
  var xmlhttp;
  xmlhttp = new XMLHttpRequest();

  xmlhttp.onreadystatechange = function () {
    if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
      $('tbody').html(xmlhttp.responseText);
    }
  };
  xmlhttp.open("post", "/resources/php/Basket.php", true);
  xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  xmlhttp.send("showBasket");
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
      $("#basket-alert-section > div#alert".concat(uid)).fadeOut(3000);
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
      $("#basket-alert-section > div#alert".concat(uid)).fadeOut(3000);
      showBasket();
    }
  };
  xmlhttp.open("post", "/resources/php/Basket.php", true);
  xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  xmlhttp.send("emptyBasket");
}
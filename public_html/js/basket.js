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
  xmlhttp.send("DispBasket=true");
}

$(document).on('click', '#addToBasket', function () {
  addToBasket();
});
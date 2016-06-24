/**
 * Live filtering of the table while typing
 */
function filterTable() {
  "use strict";
  var $rows = $('#table tbody tr');
  $('#search').keyup(function () {
    var val = $.trim($(this).val()).replace(/ +/g, ' ').toLowerCase();

    $rows.show().filter(function () {
      var text = $(this).text().replace(/\s+/g, ' ').toLowerCase();
      return !~text.indexOf(val);
    }).hide();
  });
}

function showBookDetails() {
  "use strict";
  $('tr#books').click(function () {
    var bookId = $(this).find('td').attr('data-book-id'), xmlhttp;
    if (bookId === "") {
      // display error 
    } else {
      xmlhttp = new XMLHttpRequest();
    }

    xmlhttp.onreadystatechange = function () {
      if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
        $('#bookDetails').html(xmlhttp.responseText);
        $('html, body').animate({ scrollTop: 0 }, 'slow');
      }
    };
    xmlhttp.open("get", "/resources/php/ShowBooks.php?Book=" + bookId, true);
    xmlhttp.send();
  }).hover(function () {
    $(this).toggleClass('hover');
  });
}

/**
 * Filter the table by category using an AJAX request
 */
function categoryFilter() {
  "use strict";
  var category = $('#category').find(":selected").text(), xmlhttp;
  if (category === "") {
    // display error 
  } else {
    xmlhttp = new XMLHttpRequest();
  }

  xmlhttp.onreadystatechange = function () {
    if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
      $('tbody').html(xmlhttp.responseText);
      filterTable(); // Reload filterTable so live search continues.showBookDetails()
      showBookDetails();
    }
  };
  xmlhttp.open("get", "/resources/php/ShowBooks.php?Category=" + category, true);
  xmlhttp.send();
}

$(document).on('click', '#addToBasket', function () {
  addToBasket();
});

function addToBasket() {
  var bookId = $('#addToBasket').attr('data-book-id'), xmlhttp;
  if (bookId === "") {
    // display error
    alert("empty");
  } else {
    xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function () {
      if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
        $('#basket-alert-section').show().html(xmlhttp.responseText);
        $('#basket-alert-section').fadeOut(3000);
      }
    };
    xmlhttp.open("post", "/resources/php/Basket.php", true);
    xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xmlhttp.send("Book=" + bookId);
  }
}

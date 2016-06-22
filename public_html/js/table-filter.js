/**
 * Live filtering of the table while typing
 */
function filterTable() {
  var $rows = $('#table tbody tr');
  $('#search').keyup(function () {
    var val = $.trim($(this).val()).replace(/ +/g, ' ').toLowerCase();

    $rows.show().filter(function () {
        var text = $(this).text().replace(/\s+/g, ' ').toLowerCase();
        return !~text.indexOf(val);
    }).hide();
  });
};

/**
 * Filter the table by category using an AJAX request
 */
function categoryFilter() {
  var category = $('#category').find(":selected").text();
  if(category == "") {
    // display error 
  } else {
    xmlhttp = new XMLHttpRequest();
  }

  xmlhttp.onreadystatechange = function () {
    if(xmlhttp.readyState == 4 && xmlhttp.status == 200) {
      $('tbody').html(xmlhttp.responseText);
      filterTable(); // Reload filterTable so live search continues.showBookDetails()
      showBookDetails();
    }
  };
  xmlhttp.open("get", "resources/php/ShowBooks.php?Category="+category, true);
  xmlhttp.send();
}

function showBookDetails() {
  $('tr#books').click( function() {
    var bookId = $(this).find('td').attr('data-book-id');
    if(bookId == "") {
      // display error 
    } else {
      xmlhttp = new XMLHttpRequest();
    }

    xmlhttp.onreadystatechange = function () {
      if(xmlhttp.readyState == 4 && xmlhttp.status == 200) {
        $('#bookDetails').html(xmlhttp.responseText);
        $('html, body').animate({ scrollTop: 0 }, 'slow');
      }
    };
  xmlhttp.open("get", "resources/php/ShowBooks.php?Book="+bookId, true);
  xmlhttp.send();
  }).hover( function() {
      $(this).toggleClass('hover');
  });
}
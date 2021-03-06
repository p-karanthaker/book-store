/**
  * Hides both panels for user orders and adding books on load
  */
$(document).ready(function () {
  
});

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

/**
  * On Click event for showing the Users Pane
  */
$(document).on('click', '#users', function () {
  var toLoad = $(this).attr('href') + " #content";
  $('#controlPanel').hide('fast',loadContent);
  
  function loadContent () {
    $('#controlPanel').load(toLoad, '', showNewContent);
  }
  
  function showNewContent () {
    $('#controlPanel').show('normal', function () {
      loadUsers();
    });
  }

  return false;
});

/**
  * On Click event for showing the User Order Pane
  */
$(document).on('click', '#orders', function () {
  var toLoad = $(this).attr('href') + " #content";
  $('#controlPanel').hide('fast',loadContent);
  
  function loadContent () {
    $('#controlPanel').load(toLoad, '', showNewContent);
  }
  
  function showNewContent () {
    $('#controlPanel').show('normal', function () {
      loadOrders();
    });
  }

  return false;
});

/**
  * On Click event for showing the AddBookPane
  */
$(document).on('click', '#addBook', function () {
  var toLoad = $(this).attr('href') + " #content";
  $('#controlPanel').hide('fast', loadContent);
  
  function loadContent () {
    $('#controlPanel').load(toLoad, '', showNewContent);
  }
  
  function showNewContent () {
    $('#controlPanel').show('normal', function () {
      loadCategories();
    });
  }
  return false;
});


  
function loadCategories() {
  "use strict";
  var xmlhttp;
  xmlhttp = new XMLHttpRequest();
  xmlhttp.onreadystatechange = function () {
  if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
      $('#category').append(xmlhttp.responseText);
    }
  };
  xmlhttp.open("post", "/resources/php/Books.php", true);
  xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  xmlhttp.send("loadCategories=" + true);
}

function loadOrders() {
  "use strict";
  var xmlhttp;
  xmlhttp = new XMLHttpRequest();
  xmlhttp.onreadystatechange = function () {
  if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
      $('tbody').html(xmlhttp.responseText);
      filterTable();
    }
  };
  xmlhttp.open("post", "/resources/php/Order.php", true);
  xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  xmlhttp.send("loadOrders=" + true);
}

function loadUsers() {
  "use strict";
  var xmlhttp;
  xmlhttp = new XMLHttpRequest();
  xmlhttp.onreadystatechange = function () {
  if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
      $('tbody').html(xmlhttp.responseText);
      filterTable();
    }
  };
  xmlhttp.open("post", "/resources/php/Users.php", true);
  xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  xmlhttp.send("loadUsers=" + true);
}

$(document).on({
    click: function () {
      showOrderDetails(this);
    },
    mouseenter: function () {
      $(this).toggleClass('hover');
    },
    mouseleave: function () {
      $(this).toggleClass('hover');
    }
}, 'tr.clickableRow.orders');

$(document).on({
    click: function () {
      showUserDetails(this);
    },
    mouseenter: function () {
      $(this).toggleClass('hover');
    },
    mouseleave: function () {
      $(this).toggleClass('hover');
    }
}, 'tr.clickableRow.users');

function showOrderDetails(row) {
  "use strict";
  var orderId = $(row).find('td').attr('data-order-id'), xmlhttp;
  if (orderId === "") {
    // display error 
  } else {
    xmlhttp = new XMLHttpRequest();
  }
  xmlhttp.onreadystatechange = function () {
    if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
      $('#orderDetails').html(xmlhttp.responseText);
      //$('html, body').animate({ scrollTop: 0 }, 'slow');
    }
  };
  xmlhttp.open("get", "/resources/php/Order.php?Order=" + orderId, true);
  xmlhttp.send();
}

/**
  * On Click event for adding balance to a user account
  */
var uid = 0;
$(document).on('click', '#increaseBalance', function () {
  addToUserBalance(uid);
  uid++;
});

function addToUserBalance(uid) {
  "use strict";
  var amount = $('#addBalance').val();
  var userId = $('#increaseBalance').attr('data-user-id');
  var xmlhttp;
  if (amount === "") {
    // display error 
  } else {
    xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function () {
      if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
        $("#alert-section").append("<div id=alert" + uid + ">" + xmlhttp.responseText + "</div>");
        $("#alert-section > div#alert".concat(uid)).show();
        $("#alert-section > div#alert".concat(uid)).fadeOut(3000, function () {
          $(this).remove(); 
        });
        loadUsers();
        showUserDetails();
      }
    };
    xmlhttp.open("post", "/resources/php/Users.php", true);
    xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xmlhttp.send("addBalance=" + amount + "&userId=" + userId);
  }
}

function showUserDetails(row) {
  "use strict";
  var userId = $(row).find('td').attr('data-user-id'), xmlhttp;
  if (userId === "") {
    // display error 
  } else {
    xmlhttp = new XMLHttpRequest();
  }
  xmlhttp.onreadystatechange = function () {
    if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
      $('#userDetails').html(xmlhttp.responseText);
      //$('html, body').animate({ scrollTop: 0 }, 'slow');
    }
  };
  xmlhttp.open("get", "/resources/php/Users.php?User=" + userId, true);
  xmlhttp.send();
}

$(document).on('change', '#category', function () {
  addCategory();
  function addCategory() {
    var category = $("#category option:selected").text();
    if(category != "-") {
      var categoryList = $("#categoryList");
      if(categoryList.val() == "") {
        categoryList.val(categoryList.val() + category);
      } else if (categoryList.val().indexOf(category) === -1){
        categoryList.val(categoryList.val() + ", " + category);
      }
    }
    $("#category").val("none");
  }
});

/**
  * On Click event for removing a category from the list of categories
  */
$(document).on('click', '#removeCategory', function () {
    removeLastCategory();
});

/**
  * Removes the last author that was added to the author list
  */
function removeLastCategory() {
  var categoryList = $("#categoryList");
  var lastAuthorAdded = categoryList.val().substr(0, categoryList.val().lastIndexOf(","));
  categoryList.val(lastAuthorAdded);
}

var uid = 0;
$(document).on('click', '#addNewBook', function () {
  addNewBook();
  uid++;
});

/**
  * On Click event for adding an author to the list of authors
  */
$(document).on('click', '#addAuthor', function () {
  addAuthor();
});

/**
  * On Click event for removing an author from the list of authors
  */
$(document).on('click', '#removeAuthor', function () {
    removeLastAuthor();
});

/**
  * Keyup event for 'enter' key. Will add an author to the author list
  * when pressed instead of clicking the (+) button
  */
$(document).on('keyup', '#newAuthor', function (e) {
  var code = e.which;
  if(code===13) {
    addAuthor();
  }
});

/**
  * Adds an author to a comma separated author list
  */
function addAuthor() {
  var author = $("#newAuthor").val();
  if(author != "") {
    var authorList = $("#authorList");
    if(authorList.val() == "") {
      authorList.val(authorList.val() + author);
    } else {
      authorList.val(authorList.val() + ", " + author);
    }
  }
  $("#newAuthor").val("");
}

/**
  * Removes the last author that was added to the author list
  */
function removeLastAuthor() {
  var authorList = $("#authorList");
  var lastAuthorAdded = authorList.val().substr(0, authorList.val().lastIndexOf(","));
  authorList.val(lastAuthorAdded);
}

function addNewBook(uid) {
  var book = [
      {
        "title": $('#bookTitle').val(),
        "authors": $('#authorList').val(),
        "description": $('#description').val(),
        "categories": $('#categoryList').val(),
        "quantity": $('#quantity').val(),
        "price": $('#price').val()
      }
    ];
  
  $.ajax({
    url: "/resources/php/Books.php",
    type: "post",
    dataType: "text",
    success: function (data) {
      $('html, body').animate({ scrollTop: 0 }, 'slow');
      $("#book-alert-section").append("<div id=alert" + uid + ">" + data + "</div>");
      $("#book-alert-section > div#alert".concat(uid)).show();
      $("#book-alert-section > div#alert".concat(uid)).fadeOut(6000, function () {
        $(this).remove(); 
      });
    },
    data: {book : book}
  });
  
  document.getElementById("bookForm").reset();
}

$(document).on('click', '#completeOrder', function () {
  var order_id = $(this).attr('data-order-id');
  $.ajax({
    url: "/resources/php/Order.php",
    type: "get",
    dataType: "text",
    success: function (data) {
      $('html, body').animate({ scrollTop: 0 }, 'slow');
      $("#order-alert-section").append("<div id=alert" + uid + ">" + data + "</div>");
      $("#order-alert-section > div#alert".concat(uid)).show();
      $("#order-alert-section > div#alert".concat(uid)).fadeOut(6000, function () {
        $(this).remove();
      });
      loadOrders();
      showOrderDetails();
    },
    data: {CompleteOrder: order_id}
  });
});
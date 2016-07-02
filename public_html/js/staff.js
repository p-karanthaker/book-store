/**
  * Hides both panels for user orders and adding books on load
  */
$(document).ready(function () {
  
});

/**
  * On Click event for showing the AddBookPane
  */
$(document).on('click', '#addBook', function () {
  var toLoad = $(this).attr('href');
  $('#controlPanel').hide('fast',loadContent);
  
  function loadContent () {
    $('#controlPanel').load(toLoad, '', showNewContent());
    loadCategories();
  }
  
  function showNewContent () {
    $('#controlPanel').show('normal');
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
  xmlhttp.open("post", "/resources/php/ShowBooks.php", true);
  xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  xmlhttp.send("loadCategories=" + true);
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

/**
  * On Click event for showing the UserOrderPane
  */
$(document).on('click', '#userOrders', function () {
  var toLoad = $(this).attr('href');
  $('#controlPanel').hide('fast',loadContent);
  
  function loadContent () {
    $('#controlPanel').load(toLoad, '', showNewContent());
  }
  
  function showNewContent () {
    $('#controlPanel').show('normal');
  }
  return false;
});

$(document).on('click', '#addNewBook', function () {
  addNewBook();
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

function addNewBook() {
  var bookTitle = $('#bookTitle').val();
  var authors = $('#authorList').val();
  var description = $('#description').val();
  var categories = $('#categoryList').val();
  var quantity = $('#quantity').val();
  var price = $('#price').val();
}
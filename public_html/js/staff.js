/**
  * Hides both panels for user orders and adding books on load
  */
$(document).ready(function () {
  $('#addBookPanel').hide();
  $('#userOrdersPanel').hide();
});

/**
  * On Click event for showing the AddBookPane
  */
$(document).on('click', '#addBook', function () {
  showAddBookPane();
});

/**
  * On Click event for showing the UserOrderPane
  */
$(document).on('click', '#userOrders', function () {
  showOrdersPane();
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
  * Shows the form for adding books
  */
function showAddBookPane() {
  $("#userOrdersPanel").hide();
  $("#addBookPanel").show();
}

/**
  * Shows the user orders panel
  */
function showOrdersPane() {
  $("#addBookPanel").hide();
  $("#userOrdersPanel").show();
}

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
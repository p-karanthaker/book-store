$(document).on('click', '#addBook', function () {
  addNewBook();
});

function addNewBook() {
  
}

$(document).on('click', '#addAuthor', function () {
  if($("#newAuthor").val() != "") {
    addAuthor($("#newAuthor").val());
  }
  $("#newAuthor").val("");
});

function addAuthor(author) {
  var authorList = $("#authorList");
  if(authorList.val() == "") {
    authorList.val(authorList.val() + author);
  } else {
    authorList.val(authorList.val() + ", " + author);
  }
}

$(document).on('click', '#removeAuthor', function () {
    removeLastAuthor();
});

function removeLastAuthor() {
  var authorList = $("#authorList");
  var lastAuthorAdded = authorList.val().substr(0, authorList.val().lastIndexOf(","));
  authorList.val(lastAuthorAdded);
}
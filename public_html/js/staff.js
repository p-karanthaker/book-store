$(document).on('click', '#addBook', function () {
  addNewBook();
});

function addNewBook() {
  
}

$(document).on('click', '#addAuthor', function () {
  addAuthor();
});

$(document).on('keyup', '#newAuthor', function (e) {
  var code = e.which;
  if(code===13) {
    addAuthor();
  }
});

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

$(document).on('click', '#removeAuthor', function () {
    removeLastAuthor();
});

function removeLastAuthor() {
  var authorList = $("#authorList");
  var lastAuthorAdded = authorList.val().substr(0, authorList.val().lastIndexOf(","));
  authorList.val(lastAuthorAdded);
}
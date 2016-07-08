<!-- Form to add new books for Staff.php page -->
<div id="content">
  <div class="row">
    <div class="twelve columns">
      <?php
        session_start();
        if(!isset($_SESSION["user_session"]) || (isset($_SESSION["user_session"]) && $_SESSION["user_session"]["user_type"] != "STAFF"))
        {
          require_once($_SERVER["DOCUMENT_ROOT"]."/resources/php/CommonObjects.php");
          header("Location: ".$config["paths"]["host"].$config["paths"]["index"], true, 303);
          die();
        }
      ?>
    </div>
  </div>
  
  <div id="book-alert-section" class="u-full-width"></div>

  <div class="row">
    <form id="bookForm">
      <div id="addBookPanel">
        <h2>Add New Book</h2>
        <label>Title</label>
        <input id="bookTitle" class="u-full-width" type="text" placeholder="Title">
        <label>Authors</label>
        <div class="row">
          <div class="one-third column">
            <input id="newAuthor" type="text" placeholder="Author">
            <!-- <button id="addAuthor" type="button"><i class="fa fa-plus fa-lg" aria-hidden="true"></i></button> -->
          </div>
          <div class="two-thirds column alert alert-info">
            <strong><i class='fa fa-info-circle fa-lg' aria-hidden='true'></i></strong> Press <b>'Enter'</b> to add <b>1-</b><i class="fa fa-asterisk" aria-hidden="true"></i> authors into the author list.
          </div>
        </div>
        <textarea id="authorList" class="u-full-width" placeholder="Authors..." style="resize:none" disabled></textarea>
        <button id="removeAuthor" class="u-full-width" type="button"><i class="fa fa-trash fa-lg"></i> Remove Last Author</button>
        <label>Description</label>
        <textarea id="description" class="u-full-width" placeholder="Description..." style="resize:none"></textarea>
        <!-- Placeholder for Category selection -->
        <div id="categorySection">
          <label>Categories</label>
          <div class="row">
            <div class="one-third column">
              <select id="category" name="category">
                <option value="none" selected>-</option>
              </select>
            </div>
            <div class="two-thirds column alert alert-info">
              <strong><i class='fa fa-info-circle fa-lg' aria-hidden='true'></i></strong> Add <b>1-</b><i class="fa fa-asterisk" aria-hidden="true"></i> categories to the category list.
            </div>
          </div>
          <textarea id="categoryList" class="u-full-width" placeholder="Categories..." style="resize:none" disabled></textarea>
          <button id="removeCategory" class="u-full-width" type="button"><i class="fa fa-trash fa-lg"></i> Remove Last Category</button>
        </div>
        <div class="row">
        <div class="one-half column">
          <label>Quantity</label>
          <input id="quantity" class="u-full-width" type="number" min="1" max="100" value="1"/>
        </div>
        <div class="one-half column">
          <label>Price <i class="icon-gbp icon"></i></label>
          <input id="price" class="u-full-width" type="number" min="1" max="100" value="1"/>
        </div>
        <input id="addNewBook" class="button-primary u-full-width" type="button" name="register" value="Add New Book">
        </div>
      </div>
    </form>
  </div>
</div>

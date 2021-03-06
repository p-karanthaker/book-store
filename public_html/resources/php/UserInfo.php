<!-- User Info view for the Staff.php page -->
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

      <div id="alert-section" class="u-full-width"></div>

      <div id="userDetails" class="w3-card-4">
        <header class="w3-container-header w3-blue"><h3 id="bookTitle">Details</h3></header>
        <div class="w3-container-central">
        <p id="bookDescription">Click on a user to view and update balance.</p>
        </div>
      </div>
      <br />

      <label>Search: </label><input class="u-full-width" type="text" id="search" placeholder="Start typing to search">
      <table class="u-full-width" id="table">
        <thead>
          <tr>
            <th>Username</th>
            <th>Type</th>
            <th>Balance</th>
          </tr>
        </thead>
        <tbody>
        </tbody>
      </table>
    </div>
  </div>
</div>
  
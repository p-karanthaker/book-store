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

      <div id="basket-alert-section" class="u-float-top u-full-width u-text-center"></div>

      <div id="orderDetails" class="w3-card-4">
        <header class="w3-container-header w3-blue"><h3 id="bookTitle">Details</h3></header>
        <div class="w3-container-central">
        <p id="bookDescription">Order details will appear here.</p>
        </div>
      </div>
      <br />

      <label>Search: </label><input class="u-full-width" type="text" id="search" placeholder="Start typing to search">
      <table class="u-full-width" id="table">
        <thead>
          <tr>
            <th>Order Id</th>
            <th>User</th>
            <th>Date</th>
          </tr>
        </thead>
        <tbody>
        </tbody>
      </table>
    </div>
  </div>
</div>

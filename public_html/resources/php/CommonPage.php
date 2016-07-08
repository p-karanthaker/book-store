<?php 
  require_once($_SERVER["DOCUMENT_ROOT"]."/resources/php/CommonObjects.php");
  $commonPage = new CommonPage();

  /**
   * This class is allows you to create the HTML structures
   * which are common across the BookStore website.It includes
   * page access restriction, a common HTML <head> content creator,
   * a common header for the <body> tag, a common navigation bar,
   * and a common header bar.
   *
   * @author Prem-Karan Thaker
   */
  class CommonPage
  {
    /**
     * The database connection object.
     */
    private $db;
    
    /**
     * The BookStore configuration array.
     */
    private $config;
    
    /**
     * Constructs the CommonPage class by instantiating 
     * the configuration file array, and the database connection helper.
     */
    public function __construct()
    {
      global $config;
      $this->config = $config;
      $this->db = new DatabaseHelper();
    }
    
    /**
     * Restricts access to pages to only users or only staff.
     *
     * @param Boolean   $staffOnlyPage Is the page accessible only by staff?
     */
    public function restrictAccess($staffOnlyPage)
    {
      $messages = new Messages();
      if($staffOnlyPage)
      {
        if(!isset($_SESSION["user_session"]) || (isset($_SESSION["user_session"]) && $_SESSION["user_session"]["user_type"] != "STAFF"))
        {
          echo $messages->createMessage("<i class='fa fa-lock'></i>", array("Only members of staff can access this page."), "error", ["inSessionVar" => false, "dismissable" => false]);
          die();
        }
      } else
      {
        if(!isset($_SESSION["user_session"]))
        {
          echo $messages->createMessage("<i class='fa fa-lock'></i>", array("You must be logged in to view this page."), "error", ["inSessionVar" => false, "dismissable" => false]);
          die();
        }
      }
    }
    
    /**
     * Creates the common HTML code to go within the <head></head> tags.
     * This includes all CSS links, JavaScript links, Font links, and Favicon links.
     *
     * @param String    $pageTitle  The title of the web page.
     */
    public function makeHTMLHead($pageTitle)
    {
      echo "
        <!-- Basic Page Needs
        –––––––––––––––––––––––––––––––––––––––––––––––––– -->
        <meta charset='utf-8'>
        <title>$pageTitle</title>
        <meta name='description' content='Aston Book Store Project'>
        <meta name='author' content='Karan Thaker'>

        <!-- Mobile Specific Metas
        –––––––––––––––––––––––––––––––––––––––––––––––––– -->
        <meta name='viewport' content='width=device-width, initial-scale=1'>

        <!-- FONT
        –––––––––––––––––––––––––––––––––––––––––––––––––– -->
        <link href=".$this->config['css']['raleway']." rel='stylesheet' type='text/css'>

        <!-- CSS
        –––––––––––––––––––––––––––––––––––––––––––––––––– -->
        <link rel='stylesheet' href=".$this->config['css']['normalize'].">
        <link rel='stylesheet'href=".$this->config['css']['skeleton'].">

        <!-- JavaScript
        –––––––––––––––––––––––––––––––––––––––––––––––––– -->
        <script src=".$this->config['javascript']['jquery']."></script>
        <script src=".$this->config['javascript']['bootstrap']."></script>
        <script src=".$this->config['javascript']['fontawesome']."></script>

        <!-- Favicon
        –––––––––––––––––––––––––––––––––––––––––––––––––– -->
        <link rel='icon' href=".$this->config['paths']['baseurl'].$this->config['images']['favicon'].">";
    }
    
    /**
     * Creates the common HTML code to go within the <body></body> tags.
     *
     * @param String    $headerTitle  The title of the header banner.
     */
    public function makeBodyHeader($headerTitle)
    {
      if(isset($_SESSION["user_session"]))
      {
         $this->makeHeader();
      }
      
      echo 
      "
        <div class='row'>
          <!-- Title -->
          <div class='nine columns' style='margin-top: 5%'>
            <h1>$headerTitle</h1>
          </div>
          <!-- End Title -->
          <!-- Sign In/Sign Out -->
          <div class='three columns' style='margin-top: 5%'>
      ";
          if(isset($_SESSION["user_session"]))
          {
            echo "
              <form id='logoutForm' action=".$this->config['php']['account_login']." method='post'>
                <input class='button-primary u-full-width' type='submit' name='logout' value='Sign Out'>
              </form>";
          } else
          {
            echo "<a class='button button-primary u-full-width' href=".$this->config['content']['login'].">Sign In</a>";
          }
        echo 
        "
            </div>
            <!-- End -->
          </div>
        ";
    }
    
    /**
     * Creates the common HTML code for the navigation bar.
     *
     * @param String    $activePage Changes the navigation bar style based
     *                              on a choice of content pages - home, shop,
     *                              basket, staff.
     */
    public function makeNavBar($activePage)
    {
      // <li class='active'>Home</li>
      echo
      "
        <div class='row'>
          <div class='twelve columns'>
            <ol class='breadcrumb'>
      ";
      $li;
      switch($activePage)
      {
        case "home":
          $li = 
          "
            <li class='active'>Home</li>
            <li><a href=".$this->config['content']['shop'].">Shop</a></li>
            <li><a href=".$this->config['content']['basket'].">Basket</a></li>
            <li><a href=".$this->config['content']['staff'].">Staff</a></li>
          ";
          break;
        case "shop":
          $li = 
          "
            <li><a href=".$this->config['content']['index'].">Home</a></li>
            <li class='active'>Shop</li>
            <li><a href=".$this->config['content']['basket'].">Basket</a></li>
            <li><a href=".$this->config['content']['staff'].">Staff</a></li>
          ";
          break;
        case "basket":
          $li = 
          "
            <li><a href=".$this->config['content']['index'].">Home</a></li>
            <li><a href=".$this->config['content']['shop'].">Shop</a></li>
            <li class='active'>Basket</li>
            <li><a href=".$this->config['content']['staff'].">Staff</a></li>
          ";
          break;
        case "staff":
          $li = 
          "
            <li><a href=".$this->config['content']['index'].">Home</a></li>
            <li><a href=".$this->config['content']['shop'].">Shop</a></li>
            <li><a href=".$this->config['content']['basket'].">Basket</a></li>
            <li class='active'>Staff</li>
          ";
          break;
      }
      echo
      "       $li
            </ol>
          </div>
        </div>
      ";
    }
    
    /**
     * Creates the common HTML code for the header bar which displays
     * user information (name, and account balance).
     */
    public function makeHeader()
    {
      try
        {
          $user_id = $_SESSION["user_session"]["user_id"];
          $this->db->openConnection();
          $connection = $this->db->getConnection();
          $statement = $connection->prepare("SELECT user_id, username, `type`, balance FROM `user` WHERE user_id = :user_id");
          $statement->bindParam(":user_id", $user_id);
          if($statement->execute())
          {
            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
            echo "<div class='row'>
                    <div class='twelve columns infobar bg-color-skeleton-blue'>
                      <div class='u-pull-left'>
                        <i class='fa fa-user fa-lg' aria-hidden='true' style='color:#000;'></i> ".$result[0]["username"]."
                      </div>
                      <div class='u-pull-right'>
                        <i class='fa fa-gbp fa-lg' aria-hidden='true' style='color:#000;'></i>".$result[0]["balance"]."
                      </div>
                    </div>
                  </div>";
            return true;
          }
        } catch (PDOException $ex)
        {
          $this->db->showError($ex, false);
        } finally
        {
          $this->db->closeConnection();
        }
    }
    
  }
?>
<?php
  /**
   * The CommonObjects.php file includes PHP objects which
   * are commonly used across various other PHP files in
   * the BookStore website.
   */

  /**
   * The apache server document root
   */
  $doc_root = $_SERVER["DOCUMENT_ROOT"];

  /**
   * Parses the config.ini file into an associative array.
   */
  $config = parse_ini_file($doc_root."/resources/configs/config.ini", true);

  /**
   * Allows creation of a Messages object within any PHP file which uses CommonObjects.php
   */
  require_once($doc_root.$config["php"]["messages"]);

  /**
   * Allows creation of a DatabaseHelper object within any PHP file which uses CommonObjects.php
   */
  require_once($doc_root.$config["database"]["db_helper"]);
?>
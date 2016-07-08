<?php
  $doc_root = $_SERVER["DOCUMENT_ROOT"];
  $config = parse_ini_file($doc_root."/resources/configs/config.ini", true);
  require_once($doc_root.$config["php"]["messages"]);
  require_once($doc_root.$config["database"]["db_helper"]);
?>
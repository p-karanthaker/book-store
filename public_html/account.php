<?php
  $doc_root = $_SERVER["DOCUMENT_ROOT"];
  $config = parse_ini_file($doc_root."book-store/public_html/resources/configs/config.ini", true);
  header("Location: http://localhost/".$config["paths"]["index"], true, 303);
?>

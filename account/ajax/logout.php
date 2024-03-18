<?php
  session_start();
  require_once realpath($_SERVER["DOCUMENT_ROOT"])."/res/secret.php";
  require_once realpath($_SERVER["DOCUMENT_ROOT"])."/account/lib.php";

  logout();
?>
<?php
  // Log errors to console for testing
  // error_reporting(E_ALL);
  // ini_set('display_errors', '1');

  session_start();
  require_once realpath($_SERVER["DOCUMENT_ROOT"])."/res/secret.php";
  require_once realpath($_SERVER["DOCUMENT_ROOT"])."/account/lib.php";

  // Get function string
  $funStr = $_POST["funStr"];

  if($funStr === "logout") {
    Account::logout();
    exit();
  }

  if($funStr === "login") {
    $username = $_POST["username"];
    $password = $_POST["password"];
    $obj = Account::login($username, $password, false);
    echo json_encode($obj);
  }
?>
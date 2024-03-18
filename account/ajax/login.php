<?php
  session_start();
  require_once realpath($_SERVER["DOCUMENT_ROOT"])."/res/secret.php";
  require_once realpath($_SERVER["DOCUMENT_ROOT"])."/account/lib.php";

  // Grab user information for loging
  $time = time();
  $userIP = $_SERVER['REMOTE_ADDR'];

  $codes = [
    -1=> "Argument missmatch",
    0 => "Success",
    1 => "Username or password is invalid"
  ];

  // Get information from post
  $input_username = addslashes($_POST["username"]);
  $input_password = $_POST["password"];
  $input_sli = (isset($_POST["sli"]))? $_POST["sli"] : 0;

  // Check if any rows are found
  if(!loginUser($input_username, $input_password, $input_sli)) {
    returnCode(1);
  }

  // Grab user to return
  $user = getCurrentUser();
  returnCode(0, $user);
?>
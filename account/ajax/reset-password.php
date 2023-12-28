<?php
  // This file need to be overhalled
  exit();

  session_start();
  require_once realpath($_SERVER["DOCUMENT_ROOT"])."/res/secure/database.php";
  require_once realpath($_SERVER["DOCUMENT_ROOT"])."/res/lib.php";
  require_once "../lib.php";

  // Check if all values exist
  if(
    !isset($_POST["uname"]) ||
    !isset($_POST["rcode"]) ||
    !isset($_POST["pword"])
  ) exit();

  // Grab values
  $uname = addslashes($_POST["uname"]);
  $rcode = addslashes($_POST["rcode"]);
  $pword = $_POST["pword"];

  // Check if rcode is in database, this may be removed later
  $result = runQuery($account_conn, "SELECT rcode, cookieValue FROM User WHERE username='$uname'");
  if( $result->num_rows > 0 ) exit();

  // Update user's password
  checkPassword($pword);
  $hash = createPasswordHash($pword);
  $result = runQuery($account_conn, "UPDATE User SET rcode=null, pword='$hash' FROM User WHERE username='$uname'");
?>
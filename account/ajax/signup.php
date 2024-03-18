<?php
  session_start();
  require_once realpath($_SERVER["DOCUMENT_ROOT"])."/res/secret.php";
  require_once realpath($_SERVER["DOCUMENT_ROOT"])."/account/lib.php";

  // error_reporting(E_ALL);
  // ini_set('display_errors', '1');

  function generateRandomString($length = 50) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';

    for ($i = 0; $i < $length; $i++) {
      $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }

    return $randomString;
  }

  $codes = [
    // Internal Errors
    -3=> "Argument missing",
    -2=> "Link code undefined",
    -1=> "Argument missmatch",

    // Success
    0 => "Success",

    // User errors
    1 => "Username has already been taken",
    2 => "Email is already registered",
    3 => "Failure to add user",
    4 => "Passwords do not match",
    5 => "Password must be 8 character long",
    6 => "Password must contain a capital letter",
    7 => "Password must contain a lower case letter",
    8 => "Password must contain a number",
    9 => "Password must contain a special character",
    10=> "Password can not be your username",
    11=> "Password can not be your email",
    12=> "Password strength is to low"
  ];

  if(
    !isset($_POST["username"]) ||
    !isset($_POST["email"]) ||
    !isset($_POST["password"]) ||
    !isset($_POST["repeat"])
  ) returnCode(-3);

  // Set all required vars
  $uname = addslashes($_POST["username"]);
  $email = addslashes($_POST["email"]);
  $pword = $_POST["password"];
  $rword = $_POST["repeat"];

  // Check values

  // Check if the username is taken
  $result = $account_conn->query("SELECT username FROM User WHERE username='$uname'");
  if( $result->num_rows > 0 ) returnCode(1);

  // Check if the email is already used
  $result = $account_conn->query("SELECT email FROM User WHERE email='$email'");
  if( $result->num_rows > 0 ) returnCode(2);

  // Check password match
  if( strcmp( $pword, $rword) != 0 ) returnCode(4);

  // Check password requirements
  // Length requirement
  if( strlen($pword) < 8 ) returnCode(5);
  // if( preg_match('/[A-Z]/', $pword) ) returnCode(6);
  // if( preg_match('/[a-z]/', $pword) ) returnCode(7);
  // if( preg_match('/[0-9]/', $pword) ) returnCode(8);


  // Create the user
  $cookieValue = generateRandomString();
  $saltedHash = password_hash($pword . $cookieValue, PASSWORD_BCRYPT);

  $query = "INSERT INTO User (username, password, email, cookie) VALUE ('$uname', '$saltedHash', '$email', '$cookieValue')";

  if( !$account_conn->query($query) ) returnCode(3);
  $uid = $account_conn->insert_id;

  // Login the user
  $_SESSION[$cookie_authentication] = $cookieValue;

  // Check linkCode
  if( !isset($_POST["linkCode"]) ) returnCode(0);

  // The link code allows the user to link their account to a site rather than
  // just use a global account
  $linkCode = $_POST["linkCode"];

  // Omegaball
  if( strcmp($linkCode, "omegaball") == 0) {
    $conn = connectDB("newOmegaball");
    $query = "INSERT INTO User (uid) VALUE ($uid)";
    if( !$conn->query($query) ) returnCode(3);
    returnCode(0);
  }

  // UpDawg
  if($linkCode === "updawg") {
    $conn = connectDB("updawg");
    $query = "INSERT INTO User (uid) VALUE ($uid)";
    if( !$conn->query($query) ) returnCode(3);
    returnCode(0);
  }

  // No link code found return a link code error
  echo $linkCode;
  returnCode(-2);
?>
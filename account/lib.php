<?php
  require_once realpath($_SERVER["DOCUMENT_ROOT"])."/account/config.php";

  // Log errors to console for testing
  // error_reporting(E_ALL);
  // ini_set('display_errors', '1');

  if (session_status() == PHP_SESSION_NONE) {
    session_start();
  }

  function returnCode($code, $extra=[]) {
    global $codes;

    $json = ["code"=>$code, "message"=>$codes[$code], "user"=>$extra];
    // $json = array_merge($json, $extra);

    echo json_encode( $json );
    exit();
  }


  /**
   * Fetches the user object of the current user logged in
   */
  function getCurrentUser() {
    global $cookie_authentication, $account_conn;
    $message = "";
    
    // Check if there is a cookie stored, if they is we use it
    
    if(isset($_COOKIE[$cookie_authentication])) {
      $message = $cookie_authentication." : ".$_COOKIE[$cookie_authentication] . "\n";
      $message .= "Found data in sli cookie.\n";
      $cookie = $_COOKIE[$cookie_authentication];

      // Set logged in for the session
      $_SESSION[$cookie_authentication] = $cookie;
    }

    if(isset($_SESSION[$cookie_authentication])) {
      $message .=  "Found data in session cookie.\n";
      $cookie = $_SESSION[$cookie_authentication];
    }

    if(isset($cookie) == false) {
      $message .=  "Tried to get user but no cookie was found.\n";
      return ["message" => $message];
    }

    $query = "SELECT uid, username FROM User WHERE cookie='$cookie' LIMIT 1";
    $result = $account_conn->query($query);
    $row = $result->fetch_assoc();

    $row["message"] = $message;

    if($row["uid"] == -1)
      return ["message" => $message];
    return $row;
  }

  /**
   * Fetches a user by user id, if no user id is provided then we fetch the user that is logged in
   */
  function getUser($uid = null) {
    if($uid != null) {
      return;
    }

    return getCurrentUser();
  }


  /**
   * Check to see if the user is logged in and them
   * If they are not accepted then remove all login cookies
   *
   * @return bool True if accepted, False if not accepted
   */
  function verifyUser($uid=null) {
    global $cookie_authentication, $account_conn;

    if($uid == null) {
      $uid = getCurrentUser();
    }

    $query = "SELECT cookieValue FROM User WHERE uid=$uid LIMIT 1";
    $result = $account_conn->query($query);
    $row = $result->fetch_assoc();
    if(isset($_COOKIE[$cookie_authentication])) {
      if(strcmp($row["cookieValue"], $_COOKIE[$cookie_authentication]) == 0)
        return True;
      unset($_COOKIE[$cookie_authentication]);
    }
    return False;
  }


  /**
   * Checks to see if the password fits our criteria
   */
  function checkPassword($pword) {
    $codes = [];

    // Length requirement
    $minLength = 8;
    if(strlen($pword) < $minLength)
      $codes[1] = "Password must be $minLength characters long";

    // Check for special character
    // Check for capital letter
    // Check for lower case letter
    // Check if the password is the user's name
    // Check if the password is the user's email
    // Check password strength

    return $codes;
  }

  function logout() {
    global $cookie_authentication, $cookie_domain;

    session_destroy();
    setcookie($cookie_authentication, "", time() - 3600, '/', $cookie_domain);
  }


  function createPasswordHash($pword, $cookie) {
    $hash = password_hash($pword . $cookie, PASSWORD_BCRYPT);
    return $hash;
  }

  function loginUser($username, $password, $sli) {
    global $account_conn, $cookie_authentication, $cookie_domain;

    // Check if any usernames that match the data
    $result = $account_conn->query("SELECT uid, username, password, cookie FROM User WHERE username='$username' LIMIT 1");
    $row = $result->fetch_assoc();
    if( !$row ) { /*echo "NO MATCH";//*/ return false; }

    // Pull data out of query
    $uid = $row["uid"];
    $cookie = $row["cookie"];
    $storedPassword = $row["password"];

    // Check if any rows are found
    if(!$result || $result->num_rows <= 0) {
      return false;
    }

    // Failed to login due to password
    // echo "Set? ". isset($password);
    // echo "   Defined? ". ($password !== "undefined");
    // echo "   Null? ". ($password != NULL);
    // echo "   Verify? ". (password_verify($password . $cookie, $storedPassword));
    // echo "\n";
    
    do {
      // If the stored password is null and no password is provide (undefined,
      // null, not set) then it is good
      if($storedPassword == null) {
        if( isset($password) == false ) break;
        if($password === "undefined") break;
        if($password == null) break;
      }

      // Check if the password matches
      if(password_verify($password . $cookie, $storedPassword)) break;

      return false;
    } while(false);

    // User info is correct, lets check if we need to update the account in anyway

    // Set the user as logged in

    // Set logged in for the session
    $_SESSION[$cookie_authentication] = $cookie;

    //
    if((int)$sli == 1) {
      // Calculate the Unix timestamp for one month in the future (days * 24 hours * 60 minutes * 60 seconds)
      $expiration = time()+(24*60 *60)* 30;

      setcookie($cookie_authentication, $cookie, time() + $expiration, $cookie_domain);
    }

    return true;
  }
?>
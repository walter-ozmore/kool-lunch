<?php
  session_start();
  require_once realpath($_SERVER["DOCUMENT_ROOT"])."/res/secret.php";
  require_once realpath($_SERVER["DOCUMENT_ROOT"])."/account/lib.php";

  $user = getCurrentUser();
  if($user == null || isset($user["uid"]) == false) {
    // echo "NO USER";
    echo NULL;
  } else {
    echo json_encode($user);
  }
?>
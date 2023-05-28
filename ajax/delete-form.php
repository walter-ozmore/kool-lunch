<?php
  require_once realpath($_SERVER["DOCUMENT_ROOT"])."/res/lib.php";
  require_once realpath($_SERVER["DOCUMENT_ROOT"])."/account/version-3/lib.php";

  // Check to see if the user is valid
  checkUser();

  // Runner Code
  $args = json_decode($_POST["q"], true);

  $formId = $args["formId"];

  if( is_numeric($formId) == false ) exit();

  $query = "DELETE FROM Individual WHERE FormId=$formId";
  $db_conn->query($query);

  $query = "DELETE FROM Form WHERE FormId=$formId";
  $db_conn->query($query);

  echo "Form $formId has been deleted";
?>
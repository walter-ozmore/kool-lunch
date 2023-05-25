<?php
  require_once realpath($_SERVER["DOCUMENT_ROOT"])."/res/lib.php";
  require_once realpath($_SERVER["DOCUMENT_ROOT"])."/account/version-3/lib.php";

  checkUser();

  $args = json_decode( $_POST["q"], true );
  $formId = $args["formId"];
  unset($args["formId"]);

  $set = "";
  foreach ($args as $key => $value) {
    $set .= "$key=$value";
  }

  $query = "UPDATE Form SET $set WHERE FormId=$formId";
  // echo $query;
  $result = $db_conn->query($query);
  // while ($row = $result->fetch_assoc()) {}

  // echo json_encode( $data );
?>
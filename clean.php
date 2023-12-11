<?php
  error_reporting(E_ALL);
  ini_set('display_errors', '1');

  require_once realpath($_SERVER["DOCUMENT_ROOT"])."/res/lib.php";

  $query = "SELECT * FROM Form";
  $result = $db_conn->query($query);
  while ($row = $result->fetch_assoc()) {
    
  }
?>
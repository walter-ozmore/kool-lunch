<?php
  require_once realpath($_SERVER["DOCUMENT_ROOT"])."/res/lib.php";
  require_once realpath($_SERVER["DOCUMENT_ROOT"])."/account/version-3/lib.php";

  // error_reporting(E_ALL);
  // ini_set('display_errors', '1');

  $args = json_decode($_POST["q"], 1);
  checkUser();

  $formId = $args["formId"];
  $hasPickedUp = $args["hasPickedUp"];

  // Grab the current date
  $now = strtotime('now');
  $today = strtotime('today');
  // echo $currentDayUnixTime;

  // Grab the number of rows that match
  $query = "SELECT * FROM Pickup WHERE FormId=$formId AND pickupTime>$today";
  $result = $db_conn->query($query);
  $rowCount = $result->num_rows;

  // Nothing to do
  if($hasPickedUp == 1 && $rowCount > 0) {
    exit();
  }

  // Add a row
  if($hasPickedUp == 1 && $rowCount <= 0) {
    $query = "INSERT INTO Pickup (formId, pickupTime, amount) VALUES ($formId, $now, 1)";
    $db_conn->query($query);
  }

  // Delete a row
  if($hasPickedUp == 0 && $rowCount > 0) {
    $query = "DELETE FROM Pickup WHERE FormId=$formId AND pickupTime>$today";
    $db_conn->query($query);
  }
?>
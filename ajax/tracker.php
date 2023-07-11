<?php
  require_once realpath($_SERVER["DOCUMENT_ROOT"])."/res/lib.php";
  require_once realpath($_SERVER["DOCUMENT_ROOT"])."/account/version-3/lib.php";

  // error_reporting(E_ALL);
  // ini_set('display_errors', '1');

  /**
   * Calculates how many lunches need to be picked up by either using the lunch
   * overide or counting how many children are on the form
   */
  function getNumberOfLunches($formId) {
    global $db_conn;

    // Get the number of lunches needed
    $query = "SELECT lunchOverideAmount FROM Form WHERE FormId=$formId";
    $result = $db_conn->query($query);
    while ($row = $result->fetch_assoc()) {
      $numberOfLunchesNeeded = $row["lunchOverideAmount"];

      if($numberOfLunchesNeeded != NULL || $numberOfLunchesNeeded > 0) {
        return $numberOfLunchesNeeded;
      }
    }

    // Count how many lunches they need by using children
    $query = "SELECT IndividualId FROM Individual WHERE IsAdult=0 AND FormId=$formId";
    $result = $db_conn->query($query);
    $numberOfLunchesNeeded = $result->num_rows;

    return $numberOfLunchesNeeded;
  }

  $txt = isset($_POST["q"])? $_POST["q"]: file_get_contents('php://input');
  $args = json_decode($txt, true);
  checkUser();

  $formId = $args["formId"];

  // A numeric value that indicates how the user want to interact with the data
  // 0: Delete the pickup
  // 1: Add a pickup
  $hasPickedUp = $args["hasPickedUp"];


  if( isset($args["date"]) && strtotime($args["date"]) != strtotime('today')) {
    // Try and use date
    $selectedDay = strtotime( $args["date"] );

    $now = $selectedDay;
    $today = $selectedDay;
    echo "using selected day\n";
  } else {
    // Grab the current date
    $now = strtotime('now'); // Current time on the selected day
    $today = strtotime('today'); // Selected day

    echo "using current day info\n";
  }

  // exit();

  // Grab the number of rows that have the time of today and the same form id
  $query = "SELECT * FROM Pickup WHERE FormId=$formId AND pickupTime>=$today";
  $result = $db_conn->query($query);
  $rowCount = $result->num_rows;

  // Nothing to do if there is already a row and the user is trying to select
  // pickup
  if($hasPickedUp == 1 && $rowCount > 0) {
    echo "Doing nothing\n";
    exit();
  }

  // Add a new pickup
  if($hasPickedUp == 1 && $rowCount <= 0) {
    echo "Adding new pickup\n";
    $numberOfLunchesNeeded = getNumberOfLunches( $formId );
    $query = "INSERT INTO Pickup (formId, pickupTime, amount) VALUES ($formId, $now, $numberOfLunchesNeeded)";
    $db_conn->query($query);
  }

  // Delete the pickup
  if($hasPickedUp == 0 && $rowCount > 0) {
    echo "Deleting pickup\n";
    $query = "DELETE FROM Pickup WHERE FormId=$formId AND pickupTime>=$today";
    $db_conn->query($query);
  }
?>
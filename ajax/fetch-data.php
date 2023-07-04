<?php
  require_once realpath($_SERVER["DOCUMENT_ROOT"])."/res/lib.php";
  require_once realpath($_SERVER["DOCUMENT_ROOT"])."/account/version-3/lib.php";

  /**
   * Error messages
   *
   * 0 - Success
   * 1 - User is not valid
   * 2 - No user is logged in
   */

  error_reporting(E_ALL);
  ini_set('display_errors', '1');

  /**
   * Counts the number of lunches picked up after the given string date and
   * returns it
   *
   * @param str Date in the form of a string ie. "last Monday"
   *
   * @return number Amount of lunches that were picked up on that given day
   */
  function getPickupCount($str) {
    global $db_conn;

      $min = strtotime($str);
      $max = strtotime("+1 day", $min);
      $query = "SELECT SUM(amount) AS count FROM Pickup WHERE pickupTime>$min AND pickupTime<$max";
      $result = $db_conn->query($query);
      while ($row = $result->fetch_assoc()) {
        return $row["count"];
      }
   }


  function getPickupCounts() {
    global $db_conn, $data;

    // Grab unique dates
    $query = "SELECT pickupTime FROM Pickup";
    $result = $db_conn->query($query);

    // Make an array of unique dates
    $uniqueDates = array();
    while ($row = $result->fetch_assoc()) {
      $date = date('F d', $row["pickupTime"]);
      $uniqueDates[$date] = 1; // Using an associative array to store unique dates
    }

    // Convert associative array to normal array
    $datesList = array_keys($uniqueDates);

    // Counts the amount of lunches per day
    $data["counts"] = [];
    foreach($datesList as $date) {
      $count = getPickupCount($date);

      $data["counts"][] = ["date"=>$date, "count"=>$count];
    }
  }

  function fetchSummary() {
    global $db_conn;

  }


  /**
   * Makes the form query of the forms that should be recived using the
   * arguments that were given
   *
   * @return string The query
   */
  function getFormQuery() {
    global $args;

    $cutOff = strtotime('September 1st');

    if( isset($args["cutOff"]) ) {
      $cutOff = $args["cutOff"];
    }

    // Check if cut off is a number
    if( !is_numeric($cutOff) ) exit();

    // Create query
    $query = "SELECT * FROM Form WHERE TimeSubmited<$cutOff";

    // Filter by days
    if( isset($args["day"]) ) {
      $query .= " AND Pickup".$args["day"]."=1";
    }

    return $query;
  }

  function grabData() {
    global $db_conn, $data, $args;

    $data["totalChildren"] = 0;

    // Load locations
    $data["locations"] = [];
    $query = "SELECT DISTINCT Location FROM Form";

    $result = $db_conn->query($query);
    while ($row = $result->fetch_assoc()) {
      $data["locations"][] = $row["Location"];
    }


    // List of form IDs to check later
    $formIds = "";
    $formIdArray = [];

    // Create query
    $query = getFormQuery();
    $result = $db_conn->query($query);
    while ($row = $result->fetch_assoc()) {
      $id = $row["FormId"];
      $formIdArray[] = $id;
      $formIds .= "$id,";

      // Create an array for the pickup days rather than individual varibles
      $pickupDays = [];
      if($row["PickupMonday"]    == 1) $pickupDays[] = "monday";
      if($row["PickupTuesday"]   == 1) $pickupDays[] = "tuesday";
      if($row["PickupWednesday"] == 1) $pickupDays[] = "wednesday";
      if($row["PickupThursday"]  == 1) $pickupDays[] = "thursday";

      unset($row["PickupMonday"]);
      unset($row["PickupTuesday"]);
      unset($row["PickupWednesday"]);
      unset($row["PickupThursday"]);


      $data["forms"][$id] = $row;
      $data["forms"][$id]["pickedUp"] = false;
      $data["forms"][$id]["hasAllergies"] = false;
      $data["forms"][$id]["pickupDays"] = $pickupDays;
    }

    $formIds = substr($formIds, 0, -1);

    // Fill in individuals
    $query = "SELECT * FROM Individual WHERE FormId IN ($formIds)";
    $result = $db_conn->query($query);
    while ($row = $result->fetch_assoc()) {
      $formId = $row["FormId"];
      unset($row["FormId"]);
      $data["forms"][$formId]["individuals"][] = $row;
      if( $row["Allergies"] != null )
        $data["forms"][$formId]["hasAllergies"] = true;
      if($row["IsAdult"] == 0) $data["totalChildren"] += 1;
    }

    // Check if they have pickuped today
    $today = strtotime('today');
    $query = "SELECT formId, pickupTime, amount FROM Pickup WHERE FormId IN ($formIds) AND pickupTime>$today";
    $result = $db_conn->query($query);
    while ($row = $result->fetch_assoc()) {
      $formId = $row["formId"];
      $data["forms"][$formId]["pickedUp"] = true;
    }


    // Count the amount of lunches they are going to pickup
    foreach($formIdArray as $formId) {
      $data["forms"][$formId]["amount"] = getLunchAmount($formId);
    }
    // echo getCount($formId);

    // Set code to success
    $data["code"] = 0;
  } // End of function

  // Runner Code
  $args = json_decode( $_POST["q"], true);
  $data = [];

  // Check to see if the user is valid
  checkUser();

  // Grab the data from the database
  grabData();

  // Count
  getPickupCounts();

  echo json_encode( $data );
?>
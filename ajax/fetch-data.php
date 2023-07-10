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
      $date = date('M j', $row["pickupTime"]);
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

  /**
   * Makes the form query of the forms that should be recived using the
   * arguments that were given
   *
   * @return string The query
   */
  function getFormQuery($args) {
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

  function fetchData($args) {
    $defaultArgs = [

    ];

    // Open SQL Connection
    $conn = connectDB("lunch");

    // Initialized Data Object
    $data = [];

    // Load locations
    $data["locations"] = []; // Initialize Array
    $result = $conn->query("SELECT DISTINCT Location FROM Form");
    while ($row = $result->fetch_assoc())
      $data["locations"][] = $row["Location"];

    // Load all forms that are valid
    $query = getFormQuery($args);
    $result = $conn->query($query);
    while ($row = $result->fetch_assoc()) {
      $formId = $row["FormId"];

      // Create an array for the pickup days rather than individual varibles
      $pickupDays = [];
      if($row["PickupMonday"]    == 1) $pickupDays[] = "monday";
      if($row["PickupTuesday"]   == 1) $pickupDays[] = "tuesday";
      if($row["PickupWednesday"] == 1) $pickupDays[] = "wednesday";
      if($row["PickupThursday"]  == 1) $pickupDays[] = "thursday";

      // Remove this data as we are using an array for the front end
      unset($row["PickupMonday"]);
      unset($row["PickupTuesday"]);
      unset($row["PickupWednesday"]);
      unset($row["PickupThursday"]);

      $data["forms"][$formId] = $row;
      $data["forms"][$formId]["pickupDays"] = $pickupDays;
      $data["forms"][$formId]["totalChildren"] = 0; // Temporary variable

      // Set these to null, they will be set later as we go though the individuals
      $data["forms"][$formId]["pickedUp"] = false;
      $data["forms"][$formId]["hasAllergies"] = false;
    }

    // Create a query with all the forms
    // This is faster than many smaller queries
    $multiselect = "";
    foreach($data["forms"] as $formId => $form)
      $multiselect .= "$formId, ";
    $multiselect = substr($multiselect, 0, -2); // Cut off the last ", "
    // echo $multiselect;

    // Run our query and loop though all inviduals in valid forms
    $result = $conn->query("SELECT * FROM Individual WHERE FormId IN ($multiselect)");
    while ($row = $result->fetch_assoc()) {
      $formId = $row["FormId"];

      // Remove unneeded data
      unset($row["FormId"]);

      // Append the row to the form in our data object
      $data["forms"][$formId]["individuals"][] = $row;

      // Set the form's allergy notice
      if($row["Allergies"] != null)
        $data["forms"][$formId]["hasAllergies"] = true;

      if($row["IsAdult"] == 0)
        $data["forms"][$formId]["totalChildren"] += 1;
    }

    // Clean up data

    foreach($data["forms"] as $formId => $form) {
      // Set the lunches needed to the correct value
      $lunchOveride = $form["lunchOverideAmount"];
      $lunchesNeeded = ($lunchOveride == null)? $form["totalChildren"]: $lunchOveride;
      $data["forms"][$formId]["lunchesNeeded"] = $lunchesNeeded;

      // Clear our temp varibles
      unset( $data["forms"][$formId]["totalChildren"] );
    }




    $data["code"] = 0;
    return $data;
  }

  // Runner Code
  $args = json_decode( $_POST["q"], true);
  $data = [];

  // Check to see if the user is valid
  checkUser();

  // Grab the data from the database
  $data = fetchData($args);

  // Count
  getPickupCounts();

  echo json_encode( $data );
?>
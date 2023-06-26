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


  function getCount($str) {
    global $db_conn;

    $min = strtotime($str);
    $max = strtotime("+1 day", $min);
    $query = "SELECT SUM(amount) AS count FROM Pickup WHERE pickupTime>$min AND pickupTime<$max";
    $result = $db_conn->query($query);
    while ($row = $result->fetch_assoc()) {
      return $row["count"];
    }
  }

  function mkCounts() {
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

    $data["counts"] = [];
    foreach($datesList as $date) {
      $count = getCount($date);

      $data["counts"][] = ["date"=>$date, "count"=>$count];
    }
  }

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

    // Create query
    $query = getFormQuery();
    $result = $db_conn->query($query);
    while ($row = $result->fetch_assoc()) {
      $id = $row["FormId"];
      $formIds .= "$id,";

      $data["forms"][$id] = $row;
      $data["forms"][$id]["pickedUp"] = false;
      $data["forms"][$id]["hasAllergies"] = false;
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

    // Check pickup
    $today = strtotime('today');
    $query = "SELECT formId, pickupTime, amount FROM Pickup WHERE FormId IN ($formIds) AND pickupTime>$today";
    $result = $db_conn->query($query);
    while ($row = $result->fetch_assoc()) {
      $formId = $row["formId"];
      $data["forms"][$formId]["pickedUp"] = true;
    }

    $data["code"] = 0;
  }

  // Runner Code
  $args = json_decode( $_POST["q"], true);
  $data = [];

  // Check to see if the user is valid
  checkUser();

  // Grab the data from the database
  grabData();

  // Count
  mkCounts();

  echo json_encode( $data );
?>
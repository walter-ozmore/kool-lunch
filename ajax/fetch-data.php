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

    // echo $query;
    return $query;
  }

  function grabData() {
    global $db_conn, $args;

    $data = [];
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
    }

    $formIds = substr($formIds, 0, -1);

    // Fill in individuals
    $query = "SELECT * FROM Individual WHERE FormId IN ($formIds)";
    $result = $db_conn->query($query);
    while ($row = $result->fetch_assoc()) {
      $formId = $row["FormId"];
      unset($row["FormId"]);
      $data["forms"][$formId]["individuals"][] = $row;
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
    echo json_encode( $data );
  }

  // Runner Code
  $args = $_POST["q"];

  // Check to see if the user is valid
  checkUser();

  // Grab the data from the database
  grabData();
?>
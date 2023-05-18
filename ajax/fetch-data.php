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

  function grabData() {
    global $db_conn, $args;

    $data = [];
    $data["totalChildren"] = 0;
    $formIds = "";

    // Create query
    $query = "SELECT * FROM Form";

    if( isset($args["day"]) ) {
      $query .= "WHERE Pickup".$args["day"]."=1";
    }

    $result = $db_conn->query($query);
    while ($row = $result->fetch_assoc()) {
      $id = $row["FormId"];
      // unset($row["FormId"]);
      $formIds .= "$id,";

      $data["forms"][$id] = $row;
      $data["forms"][$id]["pickedUp"] = false;
    }

    $formIds = substr($formIds, 0, -1);

    // Fill in individuals
    $query = "SELECT * FROM Individual WHERE FormId IN ($formIds)";
    // echo $query;
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
  checkUser();
  grabData();
?>
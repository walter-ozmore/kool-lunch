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

  function checkUser() {
    // TODO Check the user's
    $msg = "";
    $currentUser = getCurrentUser();

    if($currentUser == null) {
      echo json_encode([
        "message"=>"ERROR: User is not logged in",
        "code"=>2
      ]);
      exit();
    }

    $cuid = $currentUser["uid"];
    $validUsers = [8, 19]; // Users that are allowed TODO use database
    $isUserValid = false;
    foreach($validUsers as $validUserId) {
      if($cuid == $validUserId) {
        $isUserValid = true;
        break;
      }
    }

    if($isUserValid) return;

    // User is not valid return an error
    echo json_encode([
      "message"=>"ERROR: User is not vaid",
      "code"=>1
    ]);
    exit();
  }

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
      unset($row["FormId"]);
      $formIds .= "$id,";

      $data["forms"][$id] = $row;
    }

    $formIds = substr($formIds, 0, -1);

    $query = "SELECT * FROM Individual WHERE FormId IN ($formIds)";
    // echo $query;
    $result = $db_conn->query($query);
    while ($row = $result->fetch_assoc()) {
      $formId = $row["FormId"];
      unset($row["FormId"]);
      $data["forms"][$formId]["individuals"][] = $row;
      if($row["IsAdult"] == 0) $data["totalChildren"] += 1;
    }

    $data["code"] = 0;
    echo json_encode( $data );
  }



  // Runner Code
  $args = $_GET["q"];
  checkUser();
  grabData();
?>
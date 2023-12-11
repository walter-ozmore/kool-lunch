<?php
  /**
   * Signup form for users
   */
  require_once realpath($_SERVER["DOCUMENT_ROOT"])."/res/lib.php";

  /**
   * Formats the given phone number in to a more human readable format
   */
  function formatPhoneNumber($phoneNumber) {

    // Remove any non-numeric characters from the phone number
    $phoneNumber = preg_replace('/[^0-9]/', '', $phoneNumber);

    // Extract the area code, prefix, and line number
    $areaCode = substr($phoneNumber, 0, 3);
    $prefix = substr($phoneNumber, 3, 3);
    $lineNumber = substr($phoneNumber, 6, 4);

    $formattedNumber = "($areaCode) $prefix-$lineNumber";

    if(
      $formattedNumber === "() -" ||
      strlen($formattedNumber) != strlen("(xxx) xxx-xxxx") ||
      $formattedNumber === $phoneNumber
    ) return $phoneNumber;

    // Return the formatted phone number
    return $formattedNumber;
  }

  // Get POST data and sanitize
  $data = json_decode($_POST["q"], true);
  $pickupDays = array_map('strtolower', array_map('trim', $data["general"]["days"]));
  $location = $db_conn->real_escape_string(trim($data["general"]["location"]));

  // Insert form data into Form table
  $pickupMonday = in_array("monday", $pickupDays) ? 1 : 0;
  $pickupTuesday = in_array("tuesday", $pickupDays) ? 1 : 0;
  $pickupWednesday = in_array("wednesday", $pickupDays) ? 1 : 0;
  $pickupThursday = in_array("thursday", $pickupDays) ? 1 : 0;
  $timeSubmitted = time();

  $query = "INSERT INTO Form (PickupMonday, PickupTuesday, PickupWednesday, PickupThursday, TimeSubmited, Location)
          VALUES ($pickupMonday, $pickupTuesday, $pickupWednesday, $pickupThursday, $timeSubmitted, '$location')";
  if( !($result = $db_conn->query($query)) ) {
    echo "$query\n$db_conn->error\n";
    exit;
  }
  $formId = $db_conn->insert_id;

  // Insert adult data into Individual table
  foreach ($data["adults"] as $adult) {
    $name = $db_conn->real_escape_string(trim($adult["name"]));
    $phoneNumber = formatPhoneNumber( $adult["phoneNumber"] );
    $phoneNumber = $db_conn->real_escape_string(trim($phoneNumber));
    $isAdult = 1;
    $requestRemindAccess = 0;

    $query = "INSERT INTO Individual (IndividualName, PhoneNumber, IsAdult, FormId, RemindStatus)
            VALUES ('$name', '$phoneNumber', $isAdult, $formId, $requestRemindAccess)";
    if( !($result = $db_conn->query($query)) ) echo "$query\n$db_conn->error\n";
  }

  // Insert child data into Individual table
  foreach ($data["children"] as $child) {
    $name = $db_conn->real_escape_string(trim($child["name"]));
    $isAdult = 0;
    $allergies = $db_conn->real_escape_string(trim($child["allergies"]));
    $allowPhotos = (sizeof($child["allowPhotos"]) > 0)? 1 : 0;


    $query = "INSERT INTO Individual (IndividualName, IsAdult, FormId, Allergies, AllowPhotos)
            VALUES ('$name', $isAdult, $formId, '$allergies', $allowPhotos)";
    if( !($result = $db_conn->query($query)) ) echo "$query\n$db_conn->error\n";
  }

  // Close database db_connection
  $db_conn->close();
?>

<?php
  echo "Start\n";
  // Set up database connection
  $server = "localhost";
  $username = "everying_lunch";
  $password = "Ac6jNUH8W2eZpiN";
  $database = "everying_koolLunches";

  $conn = new mysqli($server, $username, $password, $database);
  if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
  }

  // Get POST data and sanitize
  $data = json_decode($_POST["q"], true);
  $pickupDays = array_map('strtolower', array_map('trim', $data["general"]["days"]));
  $location = $conn->real_escape_string(trim($data["general"]["location"]));

  // Insert form data into Form table
  $pickupMonday = in_array("monday", $pickupDays) ? 1 : 0;
  $pickupTuesday = in_array("tuesday", $pickupDays) ? 1 : 0;
  $pickupWednesday = in_array("wednesday", $pickupDays) ? 1 : 0;
  $pickupThursday = in_array("thursday", $pickupDays) ? 1 : 0;
  $timeSubmitted = time();

  $query = "INSERT INTO Form (PickupMonday, PickupTuesday, PickupWednesday, PickupThursday, TimeSubmited, Location)
          VALUES ($pickupMonday, $pickupTuesday, $pickupWednesday, $pickupThursday, $timeSubmitted, '$location')";
  if( !($result = $conn->query($query)) ) {
    echo "$query\n$conn->error\n";
    exit;
  }
  $formId = $conn->insert_id;

  // Insert adult data into Individual table
  foreach ($data["adults"] as $adult) {
    $name = $conn->real_escape_string(trim($adult["name"]));
    $phoneNumber = $conn->real_escape_string(trim($adult["phoneNumber"]));
    $isAdult = 1;
    $requestRemindAccess = 0;

    $query = "INSERT INTO Individual (IndividualName, PhoneNumber, IsAdult, FormId, RemindStatus)
            VALUES ('$name', '$phoneNumber', $isAdult, $formId, $requestRemindAccess)";
    if( !($result = $conn->query($query)) ) echo "$query\n$conn->error\n";
  }

  // Insert child data into Individual table
  foreach ($data["children"] as $child) {
    $name = $conn->real_escape_string(trim($child["name"]));
    $isAdult = 0;
    $allergies = $conn->real_escape_string(trim($child["allergies"]));
    $allowPhotos = (sizeof($child["allowPhotos"]) > 0)? 1 : 0;


    $query = "INSERT INTO Individual (IndividualName, IsAdult, FormId, Allergies, AllowPhotos)
            VALUES ('$name', $isAdult, $formId, '$allergies', $allowPhotos)";
    if( !($result = $conn->query($query)) ) echo "$query\n$conn->error\n";
  }

  // Close database connection
  $conn->close();
  echo "Done";
?>

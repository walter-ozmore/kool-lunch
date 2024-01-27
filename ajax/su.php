<?php
  error_reporting(E_ALL);
  ini_set('display_errors', '1');

  require_once realpath($_SERVER["DOCUMENT_ROOT"])."/res/lib.php";
  require_once realpath($_SERVER["DOCUMENT_ROOT"])."/account/lib.php";
  require_once realpath($_SERVER["DOCUMENT_ROOT"])."/res/secret.php";

  // Get all variables for first db query
  $args = [
    "lunchesNeeded" => $_POST["lunchesNeeded"],
    "allergies"     => $_POST["allergies"],
    "location"      => $_POST["pickupLocation"],
    "timeSubmitted" => time(),
    "isEnabled"     => 1
  ];

  $pickupDays = $_POST["pickupDays"];

  $args["pickupMon"] = $pickupDays["Mon"];
  $args["pickupTue"] = $pickupDays["Tue"];
  $args["pickupWed"] = $pickupDays["Wed"];
  $args["pickupThu"] = $pickupDays["Thu"];

  // Create Form entry
  $formID = Database::createForm($args);
  if ($formID < 1) {
    echo -1;
  }
  unset($args);

  // Create Pickup entry
  $args = [
    "formID" => $formID,
    "pickupTime" => time(),
    "amount" => $_POST["lunchesNeeded"]
  ];

  if(Database::createPickup($args) != 0) {
    echo -2;
  }
  unset($args);

  // Create Individual and FormLink entries as needed (based on # adults)
  $adults = $_POST["adults"];
  foreach ($adults as $adult => $adultValues) {
    // Get values for individuals
    $args = [
      "name"         => $adultValues["name"],
      "phoneNumber"  => $adultValues["phoneNumber"],
      "remindStatus" => $adultValues["wantsRemind"]
    ];

    $individualID = Database::createIndividual($args);
    if ($formID < 1) {
      echo -3;
    }
    unset($args);

    // Get values for the formlink entry
    $args = [
      "individualID" => $individualID,
      "formID"       => $formID
    ];

    if(Database::createFormLink($args) != 0) {
      echo -4;
    }
    unset($args);
  }

  echo 0;
?>
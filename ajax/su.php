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
    "allowPhotos"   => $_POST["allowPhotos"],
    "isEnabled"     => 1
  ];

  $pickupDays = $_POST["pickupDays"];

  $args["pickupMon"] = $pickupDays["Mon"];
  $args["pickupTue"] = $pickupDays["Tue"];
  $args["pickupWed"] = $pickupDays["Wed"];
  $args["pickupThu"] = $pickupDays["Thu"];

  // Create Form entry
  $result = Database::createForm($args);
  unset($args);

  // Verify the Form entry was successful
	if ($result["code"] != 110) {
		echo $result["message"];
		exit();
	}

  $formID = $result["entryID"];

  // Create Pickup entry
  $args = [
    "formID" => $formID,
    "pickupTime" => time(),
    "amount" => $_POST["lunchesNeeded"]
  ];

  $result = Database::createPickup($args);
  unset($args);

  // Verify the Pickup entry was successful
  if ($result["code"] != 110) {
    Database::deleteForm($formID);
		echo "Error submitting your form. Please try again later 1.";

    exit();
	}

  $pickupID = $result["entryID"];

  // Create Individual and FormLink entries as needed (based on # adults)
  $adults = $_POST["adults"];
  foreach ($adults as $adult => $adultValues) {
    // Get values for individuals
    $args = [
      "name"         => $adultValues["name"],
      "phoneNumber"  => $adultValues["phoneNumber"],
      "remindStatus" => $adultValues["wantsRemind"]
    ];

    $result = Database::createIndividual($args);
    unset($args);

    // Verify the Individual entry was successful
    if ($result["code"] != 110) {
      Database::deletePickup($pickupID);
      Database::deleteForm($formID);
      echo "Error submitting your form. Please try again later 2.";
      exit();
    }

    $individualID = $result["entryID"];

    // Get values for the FormLink entry
    $args = [
      "individualID" => $result["entryID"],
      "formID"       => $formID
    ];

    $result = Database::createFormLink($args);
    unset($args);

    if ($result["code"] != 110) {
      $pu = Database::deletePickup($pickupID);
      $in = Database::deleteIndividual($individualID);
      $fm = Database::deleteForm($formID);
      echo "Error submitting your form. Please try again later 3.";
      exit();
    }
  }

  echo 0;
?>
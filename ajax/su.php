<?php
  // error_reporting(E_ALL);
  // ini_set('display_errors', '1');

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

  // Debug messages to make sure that these boolean are coming over right
  // echo "pickupMon:". $args["pickupMon"] ."\n";
  // echo "pickupTue:". $args["pickupTue"] ."\n";
  // echo "pickupWed:". $args["pickupWed"] ."\n";
  // echo "pickupThu:". $args["pickupThu"] ."\n";

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

  // Create Individual and FormLink entries as needed (based on # adults)
  $adults = $_POST["adults"];
  foreach ($adults as $adult => $adultValues) {
    // Get values for individuals
    $args = [
      "name"             => $adultValues["name"],
      "phoneNumber"      => $adultValues["phoneNumber"],
      "remindStatus"     => $adultValues["wantsRemind"],
      "preferredContact" => "call"
    ];

    $result = Database::createIndividual($args);
    unset($args);

    // Verify the Individual entry was successful
    if ($result["code"] != 110) {
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
      $in = Database::deleteIndividual($individualID);
      $fm = Database::deleteForm($formID);
      echo "Error submitting your form. Please try again later 3.";
      exit();
    }
  }

  // Send an email to all the users that request emails
  try { // Put this all in a try catch to prevent errors
    require_once realpath($_SERVER["DOCUMENT_ROOT"])."/res/email.php";

    // Get the first adult's name
    $name = null;
    foreach ($adults as $adult => $adultValues) {
      $name = $adultValues["name"];
      break;
    }


    $users = Database::getEmailSettings();
    foreach($users as $uid => $user) {
      if($user["emailSignup"] != 1) continue;

      sendEmail(
        $user["email"],
        "A new signup has occurred. $name.",
        "New signup has occurred"
      );
    }
  } catch(Exception $e) {
    // echo "".$e->getMessage();
  }


  echo 0;
?>
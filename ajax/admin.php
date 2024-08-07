<?php
	// error_reporting(E_ALL);
	// ini_set('display_errors', '1');

  require_once realpath($_SERVER["DOCUMENT_ROOT"])."/res/lib.php";
  require_once realpath($_SERVER["DOCUMENT_ROOT"])."/account/lib.php";
  require_once realpath($_SERVER["DOCUMENT_ROOT"])."/res/secret.php";

  // Check if an admin is logged in
  $user = Account::getCurrentUser("uid, password");
  if($user == null) exit();
  if($user["password"] == null) exit();

  // Check if the user is a valid one
  $uid = $user["uid"];
  if($uid === "8" || $uid === "20" || $uid === "26" || $uid === "24")  {
    // Continue the code
  } else { exit(); }

  $function = $_POST["function"];
  unset($_POST["function"]);
  $args = $_POST;

  // The only thing left in $_POST at this point should be args
  // determineFunction($functionIndex, $args);

  // Actual code
	switch($function) {
		case 1: // Fetch volunteer forms
      if(isset($_POST["volunteerFormID"])) {
        $volunteerFormID = $_POST["volunteerFormID"];

        echo json_encode(Database::getVolunteer($volunteerFormID));
        break;
      }

      $startTime = (isset($_POST["startTime"]))? $_POST["startTime"]: 0;
      $endTime = (isset($_POST["endTime"  ]))? $_POST["endTime"  ]: 0;

			echo json_encode(Database::getVolunteers($startTime, $endTime));
			break;
    case 2: // Fetch individuals
      $startTime = (isset($_POST["startTime"]))? $_POST["startTime"]: 0;
      $endTime = (isset($_POST["endTime"  ]))? $_POST["endTime"  ]: 0;

      echo json_encode(Database::getIndividuals($startTime, $endTime));
      break;
    case 3: // Fetch Forms
      if(isset($_POST["formID"])) {
        $strID = $_POST["formID"];
        $formID = (int)$strID;

        echo json_encode(Database::getForm($formID));
        break;
      }

      $startTime = (isset($_POST["startTime"]))? $_POST["startTime"]: 0;
      $endTime = (isset($_POST["endTime"  ]))? $_POST["endTime"  ]: 0;

      echo json_encode(Database::getForms($startTime, $endTime));
      break;
    case 4: // Fetch orgs
      echo json_encode(Database::getOrganizations());
      break;
    case 5: // Fetch for tracker
      $data = [];
      $timestamp = (int)($_POST["date"]);
      $date = substr(date("l", $timestamp), 0, 3);

      $startTime = (isset($_POST["startTime"]))? $_POST["startTime"]: 0;
      $endTime = (isset($_POST["endTime"  ]))? $_POST["endTime"  ]: 0;

      $dateToCalcRange = date('Y-m-d', (int)$_POST["date"]);

      $rangeStartTime = strtotime($dateToCalcRange);
      $rangeEndTime = strtotime("$dateToCalcRange +1 Day") - 1;

      echo json_encode(Database::getDayMeals($date, $startTime, $endTime, $rangeStartTime, $rangeEndTime));
      break;
    case 6:
      // $code = Database::deleteFormVolunteer($_POST["formID"]);
      echo json_encode(Database::deleteFormVolunteer($_POST["formID"]));
      break;
    case 7: // Collect all links for a given user
      $individualID = $_POST["individualID"];

      echo json_encode(Database::getAllLinks($individualID));
      break;
    case 8: // Delete an individual
      $code = Database::deleteIndividual($_POST["individualID"]);
      echo json_encode($code);
      break;
    case 9: // Updates a pickup day for a specific form
      // Get all args for update
      $args["formID"] = $_POST["formID"];

      switch ($_POST["dateStr"]) {
        case "Mon":
          $args["pickupMon"] = $_POST["setValue"];
          break;
        case "Tue":
          $args["pickupTue"] = $_POST["setValue"];
          break;
        case "Wed":
          $args["pickupWed"] = $_POST["setValue"];
          break;
        case "Thu":
          $args["pickupThu"] = $_POST["setValue"];
          break;
        case "Fri":
          $args["pickupFri"] = $_POST["setValue"];
          break;
      }

      echo json_encode(Database::updatePickupDay($args));
      break;
    case 10: // Update is enabled for a specific form
      $args = [
        "formID"    => (int)($_POST["formID"]),
        "isEnabled" => $_POST["isEnabled"]
      ];

      echo json_encode(Database::updateIsEnabled($args));
      break;
    case 11: // Update lunches needed for a specific form
      $args = [
        "formID"    => $_POST["formID"],
        "lunchesNeeded" => (int)$_POST["lunchesNeeded"]
      ];

      echo json_encode(Database::updateLunchesNeeded($args));
      break;
    case 12: // Update allergies for a specific form
      $args = [
        "formID"    => $_POST["formID"],
        "allergies" => $_POST["allergies"]
      ];

      echo json_encode(Database::updateAllergies($args));
      break;
    case 13: // Update location for a specific form
      $args = [
        "formID"   => $_POST["formID"],
        "location" => $_POST["location"]
      ];

      echo json_encode(Database::updateLocation($args));
      break;
    case 14: // Get all distinct locations
      echo json_encode(Database::getLocations());
      break;
    case 15: // Delete a FormLink entry
      $args = [
        "formID"       => $_POST["formID"],
        "individualID" => $_POST["individualID"]
      ];

      echo json_encode(Database::deleteFormLink($args));
      break;
    case 16: // Delete specific form
      $formID = $_POST["formID"];

      echo json_encode(Database::deleteForm($formID));
      break;
    case 17: // Get an Individual entry
      $individualID = $_POST["individualID"];

      echo json_encode(Database::getIndividual($individualID));
      break;
    case 18: // updateWeekInTheSummer
      $args = [
        "volunteerFormID" => $_POST["volunteerFormID"],
        "weekInTheSummer" => $_POST["weekInTheSummer"]
      ];

      echo json_encode(Database::updateWeekInTheSummer($args));
      break;
    case 19: // updateBagDecoration
      $args = [
        "volunteerFormID" => $_POST["volunteerFormID"],
        "bagDecoration"   => $_POST["bagDecoration"]
      ];

      echo json_encode(Database::updateBagDecoration($args));
      break;
    case 20: // updateFundraising
      $args = [
        "volunteerFormID" => $_POST["volunteerFormID"],
        "fundraising"     => $_POST["fundraising"]
      ];

      echo json_encode(Database::updateFundraising($args));
      break;
    case 21: // updateSupplyGathering
      $args = [
        "volunteerFormID" => $_POST["volunteerFormID"],
        "supplyGathering" => $_POST["supplyGathering"]
      ];

      echo json_encode(Database::updateSupplyGathering($args));
      break;
    case 22: // updateVolunteerName
      $args = [
        "volunteerFormID" => $_POST["volunteerFormID"],
        "individualName"  => $_POST["individualName"]
      ];

      echo json_encode(Database::updateVolunteerName($args));
      break;
    case 23: // updateVolunteerPhoneNumber
      $args = [
        "volunteerFormID" => $_POST["volunteerFormID"],
        "phoneNumber"     => $_POST["phoneNumber"]
      ];

      echo json_encode(Database::updateVolunteerPhoneNumber($args));
      break;
    case 24: // updateIndividual
      $args = ["individualID" => (int)$_POST["individualID"]];
      if (isset($_POST["individualName"])) {
        $args["individualName"] = $_POST["individualName"];
      }
      if (isset($_POST["phoneNumber"])) {
        $args["phoneNumber"] = $_POST["phoneNumber"];
      }
      if (isset($_POST["email"])) {
        $args["email"] = $_POST["email"];
      }
      if (isset($_POST["remindStatus"])) {
        $args["remindStatus"] = $_POST["remindStatus"];
      }
      if (isset($_POST["facebookMessenger"])) {
        $args["facebookMessenger"] = $_POST["facebookMessenger"];
      }
      if (isset($_POST["preferredContact"])) {
        $args["preferredContact"] = $_POST["preferredContact"];
      }

      echo json_encode(Database::updateIndividual($args));
      break;
    case 25: // updateOrganization
      $args = ["orgID" => $_POST["orgID"]];

      if (isset($_POST["orgName"])) {
        $args["orgName"] = $_POST["orgName"];
      }
      if (isset($_POST["mainContact"])) {
        $args["mainContact"] = $_POST["mainContact"];
      }

      echo json_encode(Database::updateOrganization($args));
      break;
    case 26: // Search individuals
      $searchTerm = $_POST["searchTerm"];

      echo json_encode(Database::searchIndividuals($searchTerm));
      break;
    case 27: // Attach an individual to a form
      $individualID = $_POST["individualID"];
      $formID = $_POST["formID"];

      echo json_encode(Database::createFormLink([
        "individualID"=>$individualID,
        "formID"=>$formID
      ]));
      break;
    case 28: // Set individual on volunteer form
      $individualID = $_POST["individualID"];
      $volunteerFormID = $_POST["volunteerFormID"];
      $args = [
        "individualID"    => $_POST["individualID"],
        "volunteerFormID" => $_POST["volunteerFormID"]
      ];

      echo json_encode(Database::updateFormVolunteerLink($args));
      break;
    case 29: // Update allowPhotos
      $args = [
        "formID" => $_POST["formID"],
        "allowPhotos" => $_POST["allowPhotos"]
      ];

      echo json_encode(Database::updateAllowPhotos($args));
      break;
    case 30: // Delete/Create pickup

      $setTo = filter_var($_POST["setTo"], FILTER_VALIDATE_BOOLEAN);

      if ($setTo == True) {
        $args = [
          "formID"     => $_POST["formID"],
          "pickupTime" => $_POST["date"]
        ];

        if (isset($_POST["amount"])) {
          $args["amount"] = $_POST["amount"];
        }

        echo json_encode(Database::createPickup($args));
        break;
      }

      if ($setTo == False) {
        $formID = $_POST["formID"];

        // Get the start and end time for the date
        $date = date('Y-m-d', (int)$_POST["date"]);

        $startTime = strtotime($date);
        $endTime = strtotime("$date +1 Day") - 1;

        echo json_encode(Database::deletePickup($formID, $startTime, $endTime));
        break;
      }
      break;
    case "getSetting" :
      $key = $args;
      echo json_encode([ "data"=>Database::getSetting($key) ]);
      break;
    case "getSettings":
      echo json_encode([ "data"=>Database::getSettings() ]);
      break;
    case "setSetting":
      echo json_encode( Database::setSetting($args) );
      break;
    case "setUserSetting":
      echo json_encode(Database::setUserSetting($args));
      break;
    default:
      echo json_encode([
        "code"=>403,
        "message"=>"Function $function not found"
      ]);
	}
?>
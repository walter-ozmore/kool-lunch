<?php
	error_reporting(E_ALL);
	ini_set('display_errors', '1');

  require_once realpath($_SERVER["DOCUMENT_ROOT"])."/res/lib.php";
  require_once realpath($_SERVER["DOCUMENT_ROOT"])."/account/lib.php";
  require_once realpath($_SERVER["DOCUMENT_ROOT"])."/res/secret.php";

	/**
	 * All admin fetches start here, we check the function code then
	 * send it too the correct function. POST only.
	 */

  // Check if an admin is logged in
  $user = Account::getCurrentUser("uid, password");
  if($user == null) exit();
  if($user["password"] == null) exit();

  // Check if the user is a valid one
  $uid = $user["uid"];
  if($uid === "8" || $uid === "20" || $uid === "26" || $uid === "24")  {
    // Continue the code
  } else { exit(); }


  // Actual code
	switch($_POST["function"]) {
		case 1: // Fetch volunteer forms
      if(isset($_POST["volunteerFormID"])) {
        $volunteerFormID = $_POST["volunteerFormID"];

        echo json_encode(Database::getVolunteer($volunteerFormID));
        break;
      }

			echo json_encode(Database::getVolunteers());
			break;
    case 2: // Fetch individuals
      echo json_encode(Database::getIndividuals());
      break;
    case 3: // Fetch Forms
      if(isset($_POST["formID"])) {
        $strID = $_POST["formID"];
        $formID = (int)$strID;
        echo json_encode(Database::getForm($formID));
        break;
      }

      echo json_encode(Database::getForms());
      break;
    case 4: // Fetch Orgs
      echo json_encode(Database::getOrganizations());
      break;
    case 5: // Fetch for tracker
      $data = [];

      $timestamp = $_POST["date"];
      $date = substr(date("l", $timestamp), 0, 3);

      echo json_encode(Database::getDayMeals($date));
      break;
    case 5: // Check a checkbox for the tracker
      echo true;
      break;
    case 6:
      $code = Database::deleteFormVolunteer($_POST["formID"]);
      echo json_encode(["code"=>$code]);
      break;
    case 7: // Collect all links for a given user
      $individualID = $_POST["individualID"];

      echo json_encode(Database::getAllLinks($individualID));
      break;
    case 8:
      $code = Database::deleteIndividual($_POST["individualID"]);
      echo json_encode(["code"=>$code]);
      break;
    case 9:
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
    
      $code = Database::updatePickupDay($args);
      echo json_encode(["code"=>$code]);
      break;
	}
?>
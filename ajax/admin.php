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
  if($uid === "8" || $uid === "20" || $uid === "10")  {
    // Continue the code
  } else { exit(); }


  // Actual code
  $conn = Secret::connectDB("lunch");
	switch($_POST["function"]) {
		case 1: // Fetch volunteer forms
			$data = [];

			$query = "SELECT FormVolunteer.*, Individual.individualName, Individual.phoneNumber, Individual.email, Individual.facebookMessenger, Individual.preferredContact FROM FormVolunteer INNER JOIN Individual ON FormVolunteer.individualID = Individual.individualID;";
			$result = $conn->query($query);
			while ($row = $result->fetch_assoc()) {
				$data[] = $row;
			}

			echo json_encode($data);
			break;
    case 2: // Fetch users
      $data = [];

      $query = "SELECT * FROM Individual;";
      $result = $conn->query($query);
      while ($row = $result->fetch_assoc()) {
        $data[] = $row;
      }

      echo json_encode($data);
      break;
    case 3: // Fetch Forms
      $data = [];

      $query = "SELECT * FROM Form;";
      $result = $conn->query($query);
      while ($row = $result->fetch_assoc()) {
        $data[] = $row;
      }

      echo json_encode($data);
      break;
    case 4: // Fetch Orgs
      $data = [];

      $query = "SELECT * FROM Organization;";
      $result = $conn->query($query);
      while ($row = $result->fetch_assoc()) {
        $data[] = $row;
      }

      echo json_encode($data);
      break;
    case 5: // Fetch for tracker
      $data = [];

      $timestamp = $_POST["date"];
      $date = substr(date("l", $timestamp), 0, 3);

      $query = "SELECT Form.formID, Form.lunchesNeeded, Form.location, Form.allergies, Individual.individualName FROM Form INNER JOIN Individual on Individual.formID=Form.formID WHERE pickup$date=1 AND isEnabled=1 ORDER BY Form.location, Individual.individualName;";
      $result = $conn->query($query);
      while ($row = $result->fetch_assoc()) {
        $data[] = $row;
      }

      echo json_encode($data);
      break;
    case 5: // Check a checkbox
      echo true;
      break;
	}
?>
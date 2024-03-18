<?php
	error_reporting(E_ALL);
	ini_set('display_errors', '1');

	require_once realpath($_SERVER["DOCUMENT_ROOT"])."/res/lib.php";

	// Make sure opporunities were selected
	if(isset($_POST["opportunities"]) == false) {
		echo "No Opportunities Selected";
		exit();
	}
	// Start transaction
	$conn = Secret::connectDB("lunch");
	$conn->begin_transaction();

	try {
		// Create the individual
		$args = [
			"firstName"=> $_POST["firstName"],
			"lastName"=>$_POST["lastName"],
			"preferredContact"=>$_POST["preferredContact"]
		];

		// Add contact info to our args
		if(isset($_POST["contact"])) {
			$contact = $_POST["contact"];
			foreach ($contact as $key => $value) { $args[$key] = $value; }
		}

		$result = Database::createIndividual($args);
		unset($args);

		// Verify the Individual entry was successful
		if ($result["code"] != 110) {throw new Exception($result["message"]);}
		$individualID = $result["entryID"];

		// Create org if needed
		$orgID = -1;
		if (isset($_POST["org"])) {
			$org = $_POST["org"];
			$args = [
				"orgName" => $org["orgName"],
				"signupContact" => $individualID
			];

			if ($org["isMainContact"]) {
				$args["mainContact"] = $individualID;
			}
			
			$result = Database::createOrganization($args);
			unset($args);

			// Verify the Organization entry was successful
			if ($result["code"] != 110) {throw new Exception($result["message"]);}
			$orgID = $result["entryID"];
		}

		// Create the FormVolunteer entry
		$opportunities = $_POST["opportunities"];

		$args = [
			"timeSubmitted" => time()
		];

		foreach ($opportunities as $name) { $args[$name] = 1; }

		if ($orgID != -1) {
			$args["orgID"] = $orgID;
		}

		$result = Database::createFormVolunteer($args);
		unset($args);

		// Verify the FormVolunteer entry was successful
		if ($result["code"] != 110) {throw new Exception($result["message"]);}

		$volunteerFormID = $result["entryID"];

		// Create the FormVolunteerLink
		$args = [
			"individualID" => $individualID,
			"volunteerFormID" => $volunteerFormID
		];

		$result = Database::createFormVolunteerLink($args);
		if ($result["code"] != 110) {throw new Exception($result["message"]);}

		mysqli_commit($conn);
	} catch (Exception $e) {
		mysqli_rollback($conn);
		// echo $e;
		echo "Error submitting your form. Please try again later.";
		exit();
	}
	echo 0;
	// // Create the individual
	// $args = [
	// 	"firstName"=> $_POST["firstName"],
	// 	"lastName"=>$_POST["lastName"],
	// 	"preferredContact"=>$_POST["preferredContact"]
	// ];

	// // Add contact info to our args
	// if(isset($_POST["contact"])) {
	// 	$contact = $_POST["contact"];
	// 	foreach ($contact as $key => $value) { $args[$key] = $value; }
	// }

	// $result = Database::createIndividual($args);
	// unset($args);

	// // Verify the Individual entry was successful
	// if ($result["code"] != 110) {
	// 	echo $result["message"];
	// 	exit();
	// }
	// $individualID = $result["entryID"];

	// // Create org if needed
	// $orgID = -1;
	// if (isset($_POST["org"])) {
	// 	$org = $_POST["org"];
	// 	$args = [
	// 		"orgName" => $org["orgName"],
	// 		"signupContact" => $individualID
	// 	];

	// 	if ($org["isMainContact"]) {
	// 		$args["mainContact"] = $individualID;
	// 	}
		
	// 	$result = Database::createOrganization($args);
	// 	unset($args);

	// 	// Verify the Organization entry was successful
	// 	if ($result["code"] != 110) {
	// 		Database::deleteIndividual($individualID);
	// 		echo "Error submitting your form. Please try again later.";
	// 		exit();
	// 	}
	// 	$orgID = $result["entryID"];
	// }

	// // Create the FormVolunteer entry
	// $opportunities = $_POST["opportunities"];

	// $args = [
	// 	"timeSubmitted" => time(),
	// ];

	// foreach ($opportunities as $name) { $args[$name] = 1; }

	// if ($orgID != -1) {
	// 	$args["orgID"] = $orgID;
	// }

	// $result = Database::createFormVolunteer($args);
	// unset($args);

	// // Verify the FormVolunteer entry was successful
	// if ($result["code"] != 110) {
	// 	if ($orgID != -1) {Database::deleteOrganization($orgID);}
	// 	Database::deleteIndividual($individualID);
	// 	echo "Error submitting your form. Please try again later.";
	// 	exit();
	// }

	// $volunteerFormID = $result["entryID"];

	// // Create the FormVolunteerLink
	// $args = [
	// 	"individualID" => $individualID,
	// 	"volunteerFormID" => $volunteerFormID
	// ];

	// $result = Database::createFormVolunteerLink($args);
	// if ($result["code"] != 110) {
	// 	Database::deleteFormVolunteer($volunteerFormID);
	// 	if ($orgID != -1) {Database::deleteOrganization($orgID);}
	// 	Database::deleteIndividual($individualID);
	// 	echo "Error submitting your form. Please try again later.";
	// 	exit();
	// }
?>
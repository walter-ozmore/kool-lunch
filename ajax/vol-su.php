<?php
	error_reporting(E_ALL);
	ini_set('display_errors', '1');

	require_once realpath($_SERVER["DOCUMENT_ROOT"])."/res/lib.php";

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

	$individualID = Database::createIndividual($args);
	unset($args);

	if ($individualID <= 0) {
		echo "Error creating Individual";
		exit();
	}

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
		
		$orgID = Database::createOrg($args);
		unset($args);

		if ($orgID <= 0) {
			echo "Error creating Organization";
			exit();
		}
	}

	// Create the FormVolunteer entry
	if(isset($_POST["opportunities"]) == false) {
		echo "No Opportunities Selected";
		exit();
	}

	$opportunities = $_POST["opportunities"];

	$args = [
		"timeSubmitted" => time(),
	];

	foreach ($opportunities as $name) { $args[$name] = 1; }

	if ($orgID != -1) {
		$args["orgID"] = $orgID;
	}

	$volID = Database::createFormVolunteer($args);
	unset($args);

	if ($volID <= 0) {
		echo "Error creating Volunteer";
		exit();
	}

	// Create the FormVolunteerLink
	$args = [
		"individualID" => $individualID,
		"volunteerFormID" => $volID
	];

	if (Database::createFormVolunteerLink($args) != 0) {
		echo "Error creating Volunteer Link";
		exit();
	}

	echo 0;

	// if($index > 0) {
	// 	echo 0;
	// } else {
	// 	echo "An unknown database error has occurred";
	// }
?>
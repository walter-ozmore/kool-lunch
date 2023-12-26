<?php
	exit();
	error_reporting(E_ALL);
	ini_set('display_errors', '1');

	require_once realpath($_SERVER["DOCUMENT_ROOT"])."/res/lib.php";

	$conn = connectDB("lunch");

	$forms = [];

	// Grab all ids from the forms
	$query = "SELECT FormId FROM Form WHERE lunchOverideAmount IS NULL;";
	$result = $conn->query($query);
	while ($row = $result->fetch_assoc()) {
		$id = $row["FormId"];
		$forms[] = $id;
	}

	// Check all forms 
	foreach($forms as $formID) {
		$numOfKids = 0;
		$allergies = "";

		// Check how many kids are in each form
		$query = "SELECT IsAdult, Allergies FROM Individual WHERE FormId=$formID AND IsAdult=0;";
		$result = $conn->query($query);
		while ($row = $result->fetch_assoc()) {
			// Add allergies
			if(strlen($row["Allergies"]) > 0) $allergies = $row["Allergies"];

			$numOfKids += 1;
		}

		// Create update query for the form
		$allergies = (strlen($allergies) <= 0)? "NULL": "\"$allergies\"";
		$query = "UPDATE Form SET lunchOverideAmount=$numOfKids, allergies=$allergies WHERE FormId=$formID;";
		$conn->query($query);
		echo $query."<br>";
	}
?>
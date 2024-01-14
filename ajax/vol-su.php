<?php
  error_reporting(E_ALL);
  ini_set('display_errors', '1');

	require_once realpath($_SERVER["DOCUMENT_ROOT"])."/res/lib.php";

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

	$signupIndividual = Database::createIndividual($args);
	unset($args);

	if(isset($_POST["opportunities"]) == false) {
		echo "No Opportunities Selected";
		exit();
	}

	$opportunities = $_POST["opportunities"];

	$args = [
		"timeSubmitted" => time(),
		"individualID" => $signupIndividual
	];
	foreach ($opportunities as $name) { $args[$name] = 1; }

	$index = Database::createVolunteerForm($args);
  if($index > 0) {
    echo 0;
  } else {
    echo "An unknown database error has occurred";
  }
?>
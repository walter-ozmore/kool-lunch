<?php
  error_reporting(E_ALL);
  ini_set('display_errors', '1');

  require_once realpath($_SERVER["DOCUMENT_ROOT"])."/res/lib.php";
  require_once realpath($_SERVER["DOCUMENT_ROOT"])."/account/lib.php";
  require_once realpath($_SERVER["DOCUMENT_ROOT"])."/res/secret.php";

  // Get all variables
  $args = [
    "lunchesNeeded" => $_POST["lunchesNeeded"],
    "allergies" => $_POST["allergies"],
    "location" => $_POST["pickupLocation"],
    "isEnabled" => 1
  ];
  
  // Create Form entry

  // Create Pickup entry

  // Create Individual entries as needed (based on # adults)

  // Create FormLink entries as needed (based on # adults)
?>
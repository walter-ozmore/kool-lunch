<?php
  require_once realpath($_SERVER["DOCUMENT_ROOT"])."/res/lib.php";

  $inputObj = json_decode( $_POST["q"] );
  $returnObj = [];
  $returnObj["errors"] = [];

  function checkDays() {
    global $inputObj;

    $oneSelected = false;

    // Check if the user selected any day
    foreach( $inputObj->days as $key => $value ) {
      if( $value == true )
        $oneSelected = true;
    }

    return $oneSelected;
  }

  function isAnswered($var) {
    return $var == True || $var == False;
  }

  if(checkDays() == false) {
    $returnObj["errors"][] = ["key"=>"general.days", "message"=>"You must select at least one day to pick up lunches."];
  }

  if( !isset($inputObj->acceptPickupTime) || $inputObj->acceptPickupTime == True || $inputObj->acceptPickupTime == False ) {
    $returnObj["errors"][] = ["key"=>"general.pickupTime", "message"=>"An answer is required."];
  }

  if( !isAnswered($inputObj->pickupTime) )
    $returnObj["errors"][] = ["key"=>"general.pickupTime", "message"=>"An answer is required."];

  if( $inputObj->acceptPenalty != true )
    $returnObj["errors"][] = ["key"=>"general.acceptPenalty", "message"=>"You must accept."];

  if( $inputObj->pickupLocation == null )
    $returnObj["errors"][] = ["key"=>"general.location", "message"=>"A location must be selected"];

  // Check if adult info

  // echo json_encode($returnObj);
?>
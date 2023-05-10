<?php
  require_once realpath($_SERVER["DOCUMENT_ROOT"])."/res/lib.php";

  function formatPhoneNumber($phoneNumber) {
    // Remove any non-numeric characters from the phone number
    $phoneNumber = preg_replace('/[^0-9]/', '', $phoneNumber);

    // Extract the area code, prefix, and line number
    $areaCode = substr($phoneNumber, 0, 3);
    $prefix = substr($phoneNumber, 3, 3);
    $lineNumber = substr($phoneNumber, 6, 4);

    // Return the formatted phone number
    return "($areaCode) $prefix-$lineNumber";
  }

  $count = 0;
  $updateQuery = "";
  $query = "SELECT IndividualId, PhoneNumber FROM Individual";
  $result = $db_conn->query($query);
  while ($row = $result->fetch_assoc()) {
    $phoneNumber = $row["PhoneNumber"];
    $id = $row["IndividualId"];
    if($phoneNumber == null)
      continue;

    $formattedNumber = formatPhoneNumber($phoneNumber);
    if($formattedNumber === "() -" || strlen($formattedNumber) != strlen("(xxx) xxx-xxxx") || $formattedNumber === $phoneNumber)
      continue;
    $query = "UPDATE Individual SET PhoneNumber='$formattedNumber' WHERE IndividualId=$id;";
    // echo "$query<br>"; // Output: (903) 204-1934
    $updateQuery .= $query;
    $count += 1;
  }
  // echo $updateQuery = $updateQuery;
  $db_conn->multi_query($updateQuery);
  echo "$count rows updated";
?>
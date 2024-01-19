<?php
  /**
   * This function formats phone numbers like a sane person. No longer used, but
   * should be kept for refrence for when this is made a function is JS.
   *
   * If this is kept it should be moved to scripts
   */
  require_once realpath($_SERVER["DOCUMENT_ROOT"])."/res/lib.php";

  function formatPhoneNumber($phoneNumber) {

    // Remove any non-numeric characters from the phone number
    $phoneNumber = preg_replace('/[^0-9]/', '', $phoneNumber);

    // Extract the area code, prefix, and line number
    $areaCode = substr($phoneNumber, 0, 3);
    $prefix = substr($phoneNumber, 3, 3);
    $lineNumber = substr($phoneNumber, 6, 4);

    $formattedNumber = "($areaCode) $prefix-$lineNumber";

    // if(
    //   $formattedNumber === "() -" ||
    //   strlen($formattedNumber) != strlen("(xxx) xxx-xxxx") ||
    //   $formattedNumber === $phoneNumber
    // ) return $phoneNumber;

    // Return the formatted phone number
    return $formattedNumber;
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
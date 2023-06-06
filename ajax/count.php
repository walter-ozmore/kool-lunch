<?php
  require_once realpath($_SERVER["DOCUMENT_ROOT"])."/res/lib.php";
  require_once realpath($_SERVER["DOCUMENT_ROOT"])."/account/version-3/lib.php";

  // error_reporting(E_ALL);
  // ini_set('display_errors', '1');

  function getCount($str) {
    global $db_conn;

    $min = strtotime($str);
    $max = strtotime("+1 day", $min);
    $query = "SELECT SUM(amount) AS count FROM Pickup WHERE pickupTime>$min AND pickupTime<$max";
    $result = $db_conn->query($query);
    while ($row = $result->fetch_assoc()) {
      return $row["count"];
    }
  }

  // Grab unique dates
  $query = "SELECT pickupTime FROM Pickup";
  $statement = $db_conn->query($query);
  $result = $db_conn->query($query);

  $uniqueDates = array();

  while ($row = $result->fetch_assoc()) {
    $date = date('F d', $row["pickupTime"]);
    $uniqueDates[$date] = 1; // Using an associative array to store unique dates
  }

  $datesList = array_keys($uniqueDates);

  $obj = [];
  foreach($datesList as $date) {
    $count = getCount($date);

    $obj[] = ["date"=>$date, "count"=>$count];
  }

  echo json_encode($obj);
?>
<?php
  require_once realpath($_SERVER["DOCUMENT_ROOT"])."/res/lib.php";
  require_once realpath($_SERVER["DOCUMENT_ROOT"])."/account/version-3/lib.php";

  error_reporting(E_ALL);
  ini_set('display_errors', '1');

  function getCount($day, $location = null) {
    global $db_conn;

    $locationWhere = "";
    if($location != null)
      $locationWhere = "AND Location='$location'";

    $script = "
      SET @lunch_amount := (
        SELECT SUM(lunchOverideAmount) FROM Form
        WHERE Pickup$day = 1 AND isEnabled = 1 AND lunchOverideAmount IS NOT NULL $locationWhere
      );

      SET @total := (
        (SELECT COUNT(*) FROM Individual
        INNER JOIN Form ON Individual.FormId = Form.FormId
        WHERE Pickup$day = 1 $locationWhere AND IsAdult=0 AND isEnabled = 1 AND lunchOverideAmount IS NULL)
        + @lunch_amount
      );

      SELECT @total as total;
    ";

    if ($db_conn->multi_query($script)) {
      do {
        if ($result = $db_conn->store_result()) {
          while ($row = $result->fetch_assoc()) {
            return $row["total"];
          }
          $result->free();
        }
      } while ($db_conn->next_result());
    }
  }


  $days = ["Monday", "Tuesday", "Wednesday", "Thursday"];
  $locations = [];

  // Fetch locations
  $query = "SELECT DISTINCT Location FROM Form";
  $result = $db_conn->query($query);
  while ($row = $result->fetch_assoc()) {
    // return $row["count"];
    $locations[] = $row["Location"];
  }

  foreach($days as $day) {
    echo "$day<br>";
    foreach($locations as $location) {
      $amount = getCount( $day, $location );
      echo "$location => $amount<br>";
    }
  }

  // $day = "Monday";
  // echo $day ." => ". getCount( $day );

  // $day = "Tuesday";
  // echo $day ." => ". getCount( $day );

  // $day = "Wednesday";
  // echo $day ." => ". getCount( $day );

  // $day = "Thursday";
  // echo $day ." => ". getCount( $day );
?>
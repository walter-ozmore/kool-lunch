<?php
  require_once realpath($_SERVER["DOCUMENT_ROOT"])."/res/db.php";

  function checkUser() {
    require_once realpath($_SERVER["DOCUMENT_ROOT"])."/account/version-3/lib.php";

    // TODO Check the user's
    $msg = "";
    $currentUser = getCurrentUser();

    if($currentUser == null) {
      echo json_encode([
        "message"=>"ERROR: User is not logged in",
        "code"=>2
      ]);
      exit();
    }

    $cuid = $currentUser["uid"];
    $validUsers = [8, 20]; // Users that are allowed TODO use database
    $isUserValid = false;
    foreach($validUsers as $validUserId) {
      if($cuid == $validUserId) {
        $isUserValid = true;
        break;
      }
    }

    if($isUserValid) return;

    // User is not valid return an error
    echo json_encode([
      "message"=>"ERROR: User is not vaid",
      "code"=>1
    ]);
    exit();
  }


  /**
   * Draws an HTML table based on the given SQL query. The query is expected to
   * be a select statement.
   */
  function drawSQLTable($query, $head=null) {
    global $db_conn;

    function fetchHead($columnName) {
      global $head;

      echo $head;

      foreach($head as $key => $value) {
        if(strcmp($key, $columnName)) {
          return $value;
        }
      }

      return $columnName;
    }

    $str = "";

    // Run query
    $result = $db_conn->query($query);

    $str .= "<table>";

    // Fetch the column names from the table
    $columnNames = $result->fetch_fields();

    // Display table header
    $str .= "<tr>";
    foreach ($columnNames as $column) {
      $columnName = $column->name;
      if ($head && isset($head[$columnName])) {
        $columnName = $head[$columnName];
      }
      $str .= "<th>$columnName</th>";
    }
    $str .= "</tr>";

    // Display table data
    while ($row = $result->fetch_assoc()) {
      $str .= "<tr>";
      foreach ($row as $key => $value) {
        $str .= "<td>$value</td>";
      }
      $str .= "</tr>";
    }
    $str .= "</table>";

    return $str;
  }
?>
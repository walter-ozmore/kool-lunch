<?php
  require_once realpath($_SERVER["DOCUMENT_ROOT"])."/res/db.php";

  // Contains functions for easy database interactions
  class Database {
    /**
     * Creates an individual in the database with the given parameters, this
     * function should also check if all the arguments are valid before inputing
     * them in to the database.
     * 
     * firstName, lastName, or name is required. The database only support name
     * right now but should be expanded later
     * email
     * phoneNumber
     * fbm AKA. facebook messanger
     * 
     * returns the individuals ID if it was entered, otherwise returns -1
     */
    public static function createIndividual($args) {
      global $db_conn;

      $insertArgs = [];

      // Convert diffrent name formats to one name
      $name = "";
      if(isset($args["firstName" ])) $name = $args["firstName"];
      if(isset($args["lastName" ])) $name = $args["lastName"];
      if(isset($args["firstName"]) && isset($args["lastName" ]))
        $name = $args["firstName"] ." ". $args["lastName"];
      if(isset($args["name"     ])) $name = $args["name"];
      $insertArgs["IndividualName"] = $name;

      // Check for contact info
      if(isset($args["email"      ])) $insertArgs["Email"            ] = $args["email"      ];
      if(isset($args["phoneNumber"])) $insertArgs["PhoneNumber"      ] = $args["phoneNumber"];
      if(isset($args["fbm"        ])) $insertArgs["FacebookMessenger"] = $args["fbm"        ];
      if(isset($args["preferredContact"])) $insertArgs["PreferredContact"] = $args["preferredContact"];
      
      // Assume adult for now 
      $insertArgs["isAdult"] = 1;

      $insertStr = arrayToInsertString($insertArgs);
    
      $query = "INSERT INTO Individual $insertStr;";
      // echo $query; // Echo for testing
      $db_conn->query($query);
      return $db_conn->insert_id;
    }

    public static function createOrg($args) {
      global $db_conn;

      $insertArgs = [];

      if(isset($args["name"])) $insertArgs["orgName"] = $args["name"];
      if(isset($args["mainContact"])) $insertArgs["mainContact"] = $args["mainContact"];

      $query = "INSERT INTO Organization $insertStr;";
      echo $query; // Echo for testing
      return $db_conn->insert_id;
    }

    public static function createVolunteerForm($args) {
      global $db_conn;

      $insertArgs = $args;
      $insertStr = arrayToInsertString($insertArgs);
    
      $query = "INSERT INTO Volunteer $insertStr;";
      // echo $query; // Echo for testing
      $db_conn->query($query);
      return $db_conn->insert_id;
    }
  }

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

  function getLunchAmount($formId) {
    global $db_conn;

    // Check if there is a lunch override
    $query = "SELECT lunchOverideAmount FROM Form WHERE FormId=$formId";
    $result = $db_conn->query($query);
    while ($row = $result->fetch_assoc()) {
      if( $row["lunchOverideAmount"] != null )
        return $row["lunchOverideAmount"];
    }

    $query = "SELECT COUNT(IndividualId) AS lunches FROM Individual INNER JOIN Form ON Individual.FormId = Form.FormId WHERE Individual.FormId = $formId AND IsAdult = 0";
    // echo $query;

    $result = $db_conn->query($query);
    while ($row = $result->fetch_assoc()) {
      if( $row["lunches"] != null )
        return $row["lunches"];
    }
  }

  function arrayToInsertString($data) {
    $keys = array_keys($data);
    $values = array_values($data);

    $formattedValues = [];
    foreach ($values as $value) {
      if (is_numeric($value) || is_null($value)) {
        $formattedValues[] = $value;
        continue;
      }
      $formattedValues[] = "'".addslashes($value)."'";
    }

    $columnsString = implode(', ', $keys);
    $valuesString = implode(', ', $formattedValues);

    return "($columnsString) VALUES ($valuesString)";
  }
?>
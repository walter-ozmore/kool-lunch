<?php
  error_reporting(E_ALL);
  ini_set('display_errors', '1');

  require_once realpath($_SERVER["DOCUMENT_ROOT"])."/account/lib.php";
  require_once realpath($_SERVER["DOCUMENT_ROOT"])."/res/secret.php";

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
      $db_conn = Secret::connectDB("lunch");
      $insertArgs = [];

      // Convert different name formats to one name
      $name = "";
      if(isset($args["firstName" ])) $name = $args["firstName"];
      if(isset($args["lastName" ])) $name = $args["lastName"];
      if(isset($args["firstName"]) && isset($args["lastName" ]))
        $name = $args["firstName"] ." ". $args["lastName"];
      if(isset($args["name"     ])) $name = $args["name"];
      $insertArgs["individualName"] = $name;

      // Check for contact info
      if(isset($args["email"      ])) $insertArgs["email"            ] = $args["email"      ];
      if(isset($args["phoneNumber"])) $insertArgs["phoneNumber"      ] = $args["phoneNumber"];
      if(isset($args["fbm"        ])) $insertArgs["facebookMessenger"] = $args["fbm"        ];
      if(isset($args["preferredContact"])) $insertArgs["preferredContact"] = $args["preferredContact"];

      $insertStr = arrayToInsertString($insertArgs);

      $query = "INSERT INTO Individual $insertStr;";
      // echo $query; // Echo for testing
      $db_conn->query($query);
      return $db_conn->insert_id;
    }

    /**
     * Inserts a new row into Organization with the values provided.
     *
     * @param args The values to be inserted into the database.
     * @return The id for the newly generated Organization entry.
     */
    public static function createOrg($args) {
      $db_conn = Secret::connectDB("lunch");
      $insertArgs = [];

      if(isset($args["name"])) $insertArgs["orgName"] = $args["name"];
      if(isset($args["mainContact"]) && is_numeric($args["mainContact"])){
        $insertArgs["mainContact"] = $args["mainContact"];
      }

      if(isset($args["signupContact"]) && is_numeric($args["signupContact"])){
        $insertArgs["signupContact"] = $args["signupContact"];
      }

      $query = "INSERT INTO Organization (orgName, mainContact, signupContact) $insertStr;";
      // echo $query; // Echo for testing
      $db_conn->query($query);
      return $db_conn->insert_id;
    }

    /**
     * Inserts a new row into FormVolunteer with the values provided.
     *
     * @param args The values to be inserted into the database.
     * @return The id for the newly generated FormVolunteer entry.
     */
    public static function createVolunteerForm($args) {
      $db_conn = Secret::connectDB("lunch");
      $insertArgs = [];
      $insertStr = arrayToInsertString($insertArgs);

      if(isset($args["name"])) $insertArgs["orgName"] = $args["name"];
      if(isset($args["mainContact"])) $insertArgs["mainContact"] = $args["mainContact"];
      $query = "INSERT INTO FormVolunteer $insertStr;";
      $db_conn->query($query);
      return $db_conn->insert_id;
    }

    /**
     * Deletes the entry associated with provided id.
     *
     * @param formID The id for target entry.
     * @return 0 for success, 1 for query error, 2 for param error.
     */
    public static function deleteForm($formID) {
      $db_conn = Secret::connectDB("lunch");
      if (!is_numeric($formID)) { return 2; }

      $query = "DELETE FROM Form WHERE formID = $formID LIMIT 1;";
      $result = $db_conn->query($query);

      if ($result == FALSE) {return 1;}
      return 0;
    }

   /**
     * Deletes the entry associated with provided id.
     *
     * @param individualID The id for target entry.
     * @return 0 for success, 1 for query error, 2 for param error.
     */
    public static function deleteIndividual($individualID) {
      $db_conn = Secret::connectDB("lunch");
      if (!is_numeric($individualID)) { return 2; }

      $query = "DELETE FROM Individual WHERE individualID = $individualID LIMIT 1;";
      $result = $db_conn->query($query);

      if ($result == FALSE) {return 1;}
      return 0;
    }

   /**
     * Deletes the entry associated with provided id.
     *
     * @param volunteerFormID The id for target entry.
     * @return 0 for success, 1 for query error, and 2 for param error
     */
    public static function deleteFormVolunteer($volunteerFormID) {
      $db_conn = Secret::connectDB("lunch");
      if (!is_numeric($volunteerFormID)) { return 2; }

      $query = "DELETE FROM FormVolunteerLink WHERE volunteerFormID = $volunteerFormID LIMIT 1;";
      $result = $db_conn->query($query);
      if ($result == FALSE) {return 1;}
      
      $query = "DELETE FROM FormVolunteer WHERE volunteerFormID = $volunteerFormID LIMIT 1;";
      $result = $db_conn->query($query);

      if ($result == FALSE) {return 1;}
      return 0;
    }
    
    // TODO error checks for the two queries
    /**
     * Get all entires in FormVolunteer and Form for a given individualID.
     * 
     * @param individualID The id for a target individual.
     * @return An array with the data.
     */
    public static function getAllLinks($individualID) {
      if (!is_numeric($individualID)) {return 2;}
      $db_conn = Secret::connectDB("lunch");
      $data = [];

      $data["FormVolunteer"] = [];
      $query = "SELECT volunteerFormID, timeSubmitted FROM FormVolunteer"
              ." WHERE volunteerFormID ="
              ." (SELECT volunteerFormID FROM FormVolunteerLink WHERE individualID = $individualID);";
      $result = $db_conn->query($query);
      while ($row = $result->fetch_assoc())  {
        $data["FormVolunteer"][] = $row;
      }

      $data["Form"] = [];
      $query = "SELECT formID FROM FormLink WHERE individualID = $individualID;";
      $result = $db_conn->query($query);
      while ($row = $result->fetch_assoc())  {
        $data["Form"][] = $row;
      }

      return $data;
    }

    /**
     * Get the most recent donations.
     * 
     * @param Limit for query, defaults to 8.
     * @return An array with the donations.
     */
    public static function getDonations($limit = 8) {
      $db_conn = Secret::connectDB("lunch");
      $list = [];

      $query = "SELECT * FROM Donations ORDER BY year DESC LIMIT $limit;";
      $result = $db_conn->query($query);
      while ($row = $result->fetch_assoc()) { $data[] = $row; }

      return $data;
    }

    /**
     * Given an id, returns the associated entry from Form.
     *
     * @param int formID
     * @return The target row or a 2 for param error.
     */
    public static function getForm($formID) {
      $db_conn = Secret::connectDB("lunch");
      if (!is_numeric($formID)) { return 2; }

      $query = "SELECT * FROM Form WHERE FormID=$formID LIMIT 1;";
      $result = $db_conn->query($query);
      $data = $result->fetch_assoc();

      return $data;
    }

    /**
     * Grabs all forms.
     *
     * @return An array with all forms.
     */
    public static function getForms() {
      $db_conn = Secret::connectDB("lunch");
      $data = [];

      $query = "SELECT * FROM Form;";
      $result = $db_conn->query($query);
      while ($row = $result->fetch_assoc()) { $data[] = $row; }

      return $data;
    }

    /**
     * Grabs all individuals.
     *
     * @return Returns an array with the individuals.
     */
    public static function getIndividuals() {
      $db_conn = Secret::connectDB("lunch");
      $data = [];

      $query = "SELECT * FROM Individual ORDER BY individualID DESC;";
      $result = $db_conn->query($query);
      while ($row = $result->fetch_assoc()) { $data[] = $row; }

      return $data;
    }

    // TODO
    public static function getLunchAmount($formId) {
      $db_conn = Secret::connectDB("lunch");
      if (!is_numeric($formId)) { return 2; }

      $query = "SELECT lunchesNeeded FROM Form WHERE formID=$formId AND isEnabled = 1;";
      $result = $db_conn->query($query);
      while ($row = $result->fetch_assoc()) {
        if( $row["lunchesNeeded"] != null )
          return $row["lunchesNeeded"];
      }
    }

    // TODO
    public static function getOrganizations() {
      $db_conn = Secret::connectDB("lunch");
      $data = [];

      $query = "SELECT * FROM Organization ORDER BY orgName;";
      $result = $db_conn->query($query);
      while ($row = $result->fetch_assoc()) { $data[] = $row; }

      return $data;
    }

    // TODO
    public static function getDayMeals($date) {
      $db_conn = Secret::connectDB("lunch");
      $data = [];

      $query = "SELECT f.formID, f.lunchesNeeded, f.location, f.allergies, i.individualName"
              ." FROM FormLink fl"
              ." INNER JOIN Form f ON f.formID = fl.formID"
              ." INNER JOIN Individual i ON i.individualID = fl.individualID"
              ." WHERE isEnabled=1 AND pickup$date = 1"
              ." ORDER BY f.location, i.individualName;";
      
      $result = $db_conn->query($query);
      while ($row = $result->fetch_assoc()) {$data[] = $row;}

      return $data;
    }

    // TODO
    public static function getVolunteer($volunteerFormID) {
      $db_conn = Secret::connectDB("lunch");
      if (!is_numeric($formID)) { return 2; }

      $query = "SELECT fv.*, i.individualName, i.phoneNumber, i.email, i.facebookMessenger, i.preferredContact"
              ." FROM FormVolunteerLink as fvl"
              ." INNER JOIN FormVolunteer fv ON fv.volunteerFormID = fvl.volunteerFormID"
              ." INNER JOIN Individual i ON i.individualID = fvl.individualID"
              ." WHERE fv.volunteerFormID = $volunteerFormID;";
      
      $data = $db_conn->query($query)->fetch_assoc();

      return $data;
    }

    // TODO
    public static function getVolunteers() {
      $db_conn = Secret::connectDB("lunch");
      $data = [];

      $query = "SELECT fv.*, i.individualName, i.phoneNumber, i.email, i.facebookMessenger, i.preferredContact"
              ." FROM FormVolunteerLink as fvl"
              ." INNER JOIN FormVolunteer fv ON fv.volunteerFormID = fvl.volunteerFormID"
              ." INNER JOIN Individual i ON i.IndividualID = fvl.individualID"
              ." ORDER BY fv.volunteerFormID DESC;";
      
      $result = $db_conn->query($query);
      while ($row = $result->fetch_assoc()) { $data[] = $row; }

      return $data;
    }
  }

  function checkUser() {
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
    $db_conn = Secret::connectDB("lunch");

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
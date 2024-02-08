<?php
  error_reporting(E_ALL);
  ini_set('display_errors', '1');

  require_once realpath($_SERVER["DOCUMENT_ROOT"])."/account/lib.php";
  require_once realpath($_SERVER["DOCUMENT_ROOT"])."/res/secret.php";

  // Contains functions for easy database interactions
  class Database {

    /**
     * Creates a form in the database with the given values. It checks for data
     * validity before calling arrayToInsertString and making the query.
     *
     * @param args The values to be inserted into the database
     * @return The insert id of the newly created entry or -1
     */
    public static function createForm($args) {
      $conn = Secret::connectDB("lunch");
      $boolTypes = [0,1];
      $insertArgs = [];

      // Data verification checks
      if(isset($args["pickupMon"    ]) && in_array($args["pickupMon"], $boolTypes)) {
        $insertArgs["pickupMon"    ] = $args["pickupMon"];
      }
      if(isset($args["pickupTue"    ]) && in_array($args["pickupTue"], $boolTypes)) {
        $insertArgs["pickupTue"    ] = $args["pickupTue"];
      }
      if(isset($args["pickupWed"    ]) && in_array($args["pickupWed"], $boolTypes)) {
        $insertArgs["pickupWed"    ] = $args["pickupWed"];
      }
      if(isset($args["pickupThu"    ]) && in_array($args["pickupThu"], $boolTypes)) {
        $insertArgs["pickupThu"    ] = $args["pickupThu"];
      }
      if(isset($args["timeSubmitted"])) {
        $insertArgs["timeSubmitted"] = $args["timeSubmitted"];
      }
      if(isset($args["location"     ]) && is_string($args["location"])) {
        $insertArgs["location"     ] = $args["location"];
      }
      if(isset($args["isEnabled"    ]) && in_array($args["isEnabled"], $boolTypes)) {
        $insertArgs["isEnabled"    ] = $args["isEnabled"];
      }
      if(isset($args["lunchesNeeded"]) && (0 <= $args["lunchesNeeded"])) {
        $insertArgs["lunchesNeeded"] = $args["lunchesNeeded"];
      }
      if(isset($args["allergies"    ])) {
        $insertArgs["allergies"    ] = $args["allergies"];
      }

      // Get insert string
      $insertStr = arrayToInsertString($insertArgs);

      // Run query and return the insert id
      $query = "INSERT INTO Form $insertStr;";
      $conn->query($query);
      if ($conn->insert_id > 1) {return $conn->insert_id;}
      return -1;
    }

    /**
     * Inserts a new row into FormLink with the values provided.
     *
     * @param args The values to be inserted into the database.
     * @return -1 for error, 0 for success.
     */
    public static function createFormLink($args) {
      $conn = Secret::connectDB("lunch");
      $insertArgs = [];

      if (isset($args["individualID"]) && is_numeric($args["individualID"])) {
        $insertArgs["individualID"] = $args["individualID"];
      } else {return -1;}
      if (isset($args["formID"]) && is_numeric($args["formID"])) {
        $insertArgs["formID"] = $args["formID"];
      } else {return -1;}

      // Get insert string
      $insertStr = arrayToInsertString($insertArgs);

      // Run query and return 0
      $query = "INSERT INTO FormLink $insertStr;";
      $conn->query($query);
      return 0;
    }

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
      $conn = Secret::connectDB("lunch");
      $insertArgs = [];

      // Convert different name formats to one name
      $name = "";
      if(isset($args["firstName" ])) $name = $args["firstName"];
      if(isset($args["lastName"  ])) $name = $args["lastName"];
      if(isset($args["firstName" ]) && isset($args["lastName" ]))
        $name = $args["firstName"] ." ". $args["lastName"];
      if(isset($args["name"      ])) $name = $args["name"];

      $insertArgs["individualName"] = $name;

      // Check for contact info
      if(isset($args["email"      ])) $insertArgs["email"            ] = $args["email"      ];
      if(isset($args["phoneNumber"])) $insertArgs["phoneNumber"      ] = $args["phoneNumber"];
      if(isset($args["fbm"        ])) $insertArgs["facebookMessenger"] = $args["fbm"        ];
      if(isset($args["preferredContact"])) $insertArgs["preferredContact"] = $args["preferredContact"];

      $insertStr = arrayToInsertString($insertArgs);

      $query = "INSERT INTO Individual $insertStr;";
      // echo $query; // Echo for testing
      $conn->query($query);
      if ($conn->insert_id > 1) {return $conn->insert_id;}
      return -1;
    }

    /**
     * Inserts a new row into Organization with the values provided.
     *
     * @param args The values to be inserted into the database.
     * @return The id for the newly generated Organization, or -1 for error.
     */
    public static function createOrg($args) {
      $conn = Secret::connectDB("lunch");
      $insertArgs = [];

      // Data verification checks
      if(isset($args["orgName"])) $insertArgs["orgName"] = $args["orgName"];
      if(isset($args["mainContact"]) && is_numeric($args["mainContact"])){
        $insertArgs["mainContact"] = $args["mainContact"];
      } else {$insertArgs["mainContact"] = null;}

      if(isset($args["signupContact"]) && is_numeric($args["signupContact"])){
        $insertArgs["signupContact"] = $args["signupContact"];
      } else {return -1;}

      $insertStr = arrayToInsertString($insertArgs);

      $query = "INSERT INTO Organization $insertStr;";
      $conn->query($query);
      if ($conn->insert_id > 1) {return $conn->insert_id;}
      return -1;
    }

    /**
     * Inserts a new row into Pickup with the values provided.
     *
     * @param args The values to be inserted into the database.
     * @return -1 for error, 0 for success.
     */
    public static function createPickup($args) {
      $conn = Secret::connectDB("lunch");
      $insertArgs = [];

      // Data verification checks
      if (isset($args["formID"])) {$insertArgs["formID"] = $args["formID"];}
      else {return "formID not provided.";}
      if (isset($args["pickupTime"])) {$insertArgs["pickupTime"] = $args["pickupTime"];}
      if (isset($args["amount"]) && (0 <= $args["amount"])) {$insertArgs["amount"] = $args["amount"];}

      // Get insert string
      $insertStr = arrayToInsertString($insertArgs);

      // Run query and return the insert id
      $query = "INSERT INTO Pickup $insertStr;";
      $conn->query($query);
      if ($conn->insert_id > 1) {return 0;}
      return -1;
    }

    /**
     * Inserts a new row into FormVolunteer with the values provided.
     *
     * @param args The values to be inserted into the database.
     * @return The id for the newly generated FormVolunteer, or -1 for error.
     */
    public static function createFormVolunteer($args) {
      $conn = Secret::connectDB("lunch");
      $boolTypes = [0,1];
      $insertArgs = [];

      // Data verification checks
      if (isset($args["orgID"           ])) {
        $insertArgs["orgID"] = $args["orgID"];
      }
      if (isset($args["timeSubmitted"   ])) {
        $insertArgs["timeSubmitted"] = $args["timeSubmitted"];
      }
      if (isset($args["weekInTheSummer" ]) && in_array($args["weekInTheSummer"], $boolTypes))
      {
        $insertArgs["weekInTheSummer"] = $args["weekInTheSummer"];
      }
      if (isset($args["bagDecoration"   ]) && in_array($args["bagDecoration"], $boolTypes))
      {
        $insertArgs["bagDecoration"] = $args["bagDecoration"];
      }
      if (isset($args["fundraising"    ]) && in_array($args["fundraising"], $boolTypes))
      {
        $insertArgs["fundraising"] = $args["fundraising"];
      }
      if (isset($args["supplyGathering"]) && in_array($args["supplyGathering"], $boolTypes))
      {
        $insertArgs["supplyGathering"] = $args["supplyGathering"];
      }

      // Get insert string
      $insertStr = arrayToInsertString($insertArgs);

      // Run query
      $query = "INSERT INTO FormVolunteer $insertStr;";
      $conn->query($query);
      if ($conn->insert_id > 1) {return $conn->insert_id;}
      return -1;
    }

    /**
     * Inserts a new row into FormVolunteerLink with the values provided.
     *
     * @param args The values to be inserted into the database.
     * @return -1 for error, 0 for success.
     */
    public static function createFormVolunteerLink($args) {
      $conn = Secret::connectDB("lunch");
      $insertArgs = [];

      // Data verificiation checks
      if(isset($args["individualID"]) && (0 < $args["individualID"])) {
        $insertArgs["individualID"] = $args["individualID"];
      } else {return -1;}
      if (isset($args["volunteerFormID"]) && (0 < $args["volunteerFormID"])) {
        $insertArgs["volunteerFormID"] = $args["volunteerFormID"];
      } else {return -1;}

      // Get insert string
      $insertStr = arrayToInsertString($insertArgs);

      // Run query
      $query = "INSERT INTO FormVolunteerLink $insertStr;";
      $conn->query($query);
      return 0;
    }

    /**
     * Deletes the entry associated with provided id.
     *
     * @param formID The id for target entry.
     * @return 0 for success, 1 for query error, 2 for param error.
     */
    public static function deleteForm($formID) {
      $conn = Secret::connectDB("lunch");
      if (!is_numeric($formID)) { return 2; }

      $query = "DELETE FROM Form WHERE formID = $formID LIMIT 1;";
      $result = $conn->query($query);

      if ($result == FALSE) {return 1;}
      return 0;
    }

     /**
     * Deletes the entry associated with provided ids
     *
     * @param args[] An array containing the formID and individualID. 
     * @return 0 for success, 1 for query error, 2 for param error.
     */
    public static function deleteFormLink($args) {
      $conn = Secret::connectDB("lunch");

      // Data verification checks
      if (!is_numeric($args["formID"])) { return 2; }
      if (!is_numeric($args["individualID"])) { return 2; }
      $formID = $args["formID"];
      $individualID = $args["individualID"];

      $query = "DELETE FROM FormLink WHERE formID = $formID AND individualID = $individualID LIMIT 1;";
      $result = $conn->query($query);

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
      $conn = Secret::connectDB("lunch");
      if (!is_numeric($volunteerFormID)) { return 2; }

      $query = "DELETE FROM FormVolunteerLink WHERE volunteerFormID = $volunteerFormID LIMIT 1;";
      $result = $conn->query($query);
      if ($result == FALSE) {return 1;}

      $query = "DELETE FROM FormVolunteer WHERE volunteerFormID = $volunteerFormID LIMIT 1;";
      $result = $conn->query($query);

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
      $conn = Secret::connectDB("lunch");
      if (!is_numeric($individualID)) { return 2; }

      $query = "DELETE FROM Individual WHERE individualID = $individualID LIMIT 1;";
      $result = $conn->query($query);

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
      $conn = Secret::connectDB("lunch");
      $data = [];

      $data["FormVolunteer"] = [];
      $query = "SELECT volunteerFormID, timeSubmitted FROM FormVolunteer"
              ." WHERE volunteerFormID ="
              ." (SELECT volunteerFormID FROM FormVolunteerLink WHERE individualID = $individualID);";
      $result = $conn->query($query);
      while ($row = $result->fetch_assoc())  {
        $data["FormVolunteer"][] = $row;
      }

      $data["Form"] = [];
      $query = "SELECT formID FROM FormLink WHERE individualID = $individualID;";
      $result = $conn->query($query);
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
      $conn = Secret::connectDB("lunch");
      $list = [];

      $query = "SELECT * FROM Donations ORDER BY year DESC LIMIT $limit;";
      $result = $conn->query($query);
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
      $conn = Secret::connectDB("lunch");
      if (!is_numeric($formID)) { return 2; }

      $query = "SELECT * FROM Form WHERE FormID=$formID LIMIT 1;";
      $result = $conn->query($query);
      
      if (!$result) {return 1;}
      if ($result->lengths == 0) {return 1;}

      $data = $result->fetch_assoc();
      return $data;
    }

    /**
     * Grabs all forms and the individuals linked to those forms.
     *
     * @return An array with all forms.
     */
    public static function getForms() {
      $conn = Secret::connectDB("lunch");
      $data = [];
      $order = [];
      $rawData = [];

      // Query
      $query = "SELECT f.*, i.individualID, i.individualName"
               ." FROM FormLink fl"
               ." INNER JOIN Form f ON f.formID = fl.formID"
               ." INNER JOIN Individual i ON fl.individualID = i.individualID"
               ." ORDER BY f.timeSubmitted DESC;";

      $result = $conn->query($query);

      while ($row = $result->fetch_assoc()){
        $formID = $row["formID"];

        if (!in_array($formID, $order)) {$order[] = $formID;}

        // If the formID is not already in $data, add it
        if (!isset($rawData[$formID])) {
          $rawData[$formID] = [
            "formID"        => $row["formID"],
            "pickupMon"     => $row["pickupMon"],
            "pickupTue"     => $row["pickupTue"],
            "pickupWed"     => $row["pickupWed"],
            "pickupThu"     => $row["pickupThu"],
            "pickupFri"     => $row["pickupFri"],
            "timeSubmitted" => $row["timeSubmitted"],
            "location"      => $row["location"],
            "isEnabled"     => $row["isEnabled"],
            "lunchesNeeded" => $row["lunchesNeeded"],
            "allergies"     => $row["allergies"]
          ];
        }

        // Add the individual from this row to data
        $rawData[$formID]["individuals"][] = [
          "individualID"   => $row["individualID"],
          "individualName" => $row["individualName"]
        ];
      }

      // Place in final array with correct order
      foreach ($order as $formID) {
        $data[] = $rawData[$formID];
      }

      return $data;
    }

    /**
     * Grabs all individuals.
     *
     * @return Returns an array with the individuals.
     */
    public static function getIndividuals() {
      $conn = Secret::connectDB("lunch");
      $data = [];

      $query = "SELECT * FROM Individual ORDER BY individualID DESC;";
      $result = $conn->query($query);
      while ($row = $result->fetch_assoc()) { $data[] = $row; }

      return $data;
    }

    /**
     * Grab all distinct locations.
     * 
     * @return Returns an array with the locations.
     */
    public static function getLocations() {
      $conn = Secret::connectDB("lunch");
      $data = [];

      $query = "SELECT DISTINCT `location` FROM Form ORDER BY `location`;";
      $result = $conn->query($query);
      while ($row = $result->fetch_assoc()) { $data[] = $row; }

      return $data;
    }


    /**
     * Grab the lunchesNeeded column for a specific form.
     * 
     * @param formID The ID of the form.
     * 
     * @return Returns the lunchesNeeded value
     */
    public static function getLunchesNeeded($formId) {
      $conn = Secret::connectDB("lunch");
      if (!is_numeric($formId)) { return 2; }

      $query = "SELECT lunchesNeeded FROM Form WHERE formID=$formId AND isEnabled = 1;";
      $result = $conn->query($query);
      while ($row = $result->fetch_assoc()) {
        if( $row["lunchesNeeded"] != null )
          return $row["lunchesNeeded"];
      }
    }

    /**
     * Grab all organizations.
     * 
     * @return Returns an array with the organizations.
    */
    public static function getOrganizations() {
      $conn = Secret::connectDB("lunch");
      $data = [];

      $query = "SELECT * FROM Organization ORDER BY orgName;";
      $result = $conn->query($query);
      while ($row = $result->fetch_assoc()) { $data[] = $row; }

      return $data;
    }

    /**
     * Get all emails for a specific day.
     * 
     * @param date The day to get the meals from.
     * 
     * @return Returns an array with the resulting rows from the query.
     */
    public static function getDayMeals($date) {
      $conn = Secret::connectDB("lunch");
      $data = [];

      $query = "SELECT f.formID, f.lunchesNeeded, f.location, f.allergies, i.individualName"
              ." FROM FormLink fl"
              ." INNER JOIN Form f ON f.formID = fl.formID"
              ." INNER JOIN Individual i ON i.individualID = fl.individualID"
              ." WHERE isEnabled=1 AND pickup$date = 1"
              ." ORDER BY f.location, i.individualName;";

      $result = $conn->query($query);
      while ($row = $result->fetch_assoc()) {$data[] = $row;}

      return $data;
    }

    /**
     * Get a volunteer's information using a provided ID.
     * 
     * @param volunteerFormID The volunteerFormID.
     * 
     * @return Returns an array with the query results.
     */
    public static function getVolunteer($volunteerFormID) {
      $conn = Secret::connectDB("lunch");
      if (!is_numeric($volunteerFormID)) { return 2; }

      $query = "SELECT fv.*, i.individualName, i.phoneNumber, i.email, i.facebookMessenger, i.preferredContact"
              ." FROM FormVolunteerLink as fvl"
              ." INNER JOIN FormVolunteer fv ON fv.volunteerFormID = fvl.volunteerFormID"
              ." INNER JOIN Individual i ON i.individualID = fvl.individualID"
              ." WHERE fv.volunteerFormID = $volunteerFormID;";

      $data = $conn->query($query)->fetch_assoc();

      return $data;
    }

    /**
     * Get all volunteers.
     * 
     * @return Returns an array with the query results.
     */
    public static function getVolunteers() {
      $conn = Secret::connectDB("lunch");
      $data = [];

      $query = "SELECT fv.*, i.individualName, i.phoneNumber, i.email, i.facebookMessenger, i.preferredContact"
              ." FROM FormVolunteerLink as fvl"
              ." INNER JOIN FormVolunteer fv ON fv.volunteerFormID = fvl.volunteerFormID"
              ." INNER JOIN Individual i ON i.IndividualID = fvl.individualID"
              ." ORDER BY fv.volunteerFormID DESC;";

      $result = $conn->query($query);
      while ($row = $result->fetch_assoc()) { $data[] = $row; }

      return $data;
    }

    /**
     * Update the allergies field of a specific form with provided values.
     * 
     * @param args An array containing the formID and new allergies value.
     * 
     * @return 0 on success, 1 for query error, and 2 for param error.
     */
    public static function updateAllergies($args) {
      $conn = Secret::connectDB("lunch");

      // Data verification checks
      if (!is_numeric($args["formID"])) {return 2;}
      if (!is_string($args["allergies"])) {return 2;}
      $formID = $args["formID"];
      $allergies = $args["allergies"];

      $query = "UPDATE Form SET allergies = '$allergies' WHERE formID = $formID LIMIT 1;";

      $result = $conn->query($query);
      if ($result == FALSE) {return 1;}
      return 0;
    }

    /**
     * Update the isEnabled field of a specific form with provided values.
     * 
     * @param args An array containing the formID and new isEnabled value.
     * 
     * @return 0 on success, 1 for query error, and 2 for param error.
     */
    public static function updateIsEnabled($args) {
      $conn = Secret::connectDB("lunch");
      $boolTypes = [0,1];

      // Data verificiation checks
      if (!is_numeric($args["formID"])) {return 2;}
      if (!is_numeric($args["isEnabled"]) || !in_array($args["isEnabled"], $boolTypes)) {return 2;}
      $formID = $args["formID"];
      $isEnabled = $args["isEnabled"];

      $query = "UPDATE Form SET isEnabled = $isEnabled WHERE formID = $formID LIMIT 1;";

      $result = $conn->query($query);
      if ($result == FALSE) {return 1;}
      return 0;
    }

    /**
     * Update the location field of a specific form with provided values.
     * 
     * @param args An array containing the formID and new location value.
     * 
     * @return 0 on success, 1 for query error, and 2 for param error.
     */
    public static function updateLocation($args) {
      $conn = Secret::connectDB("lunch");

      // Data verificiation checks
      if (!is_numeric($args["formID"])) {return 2;}
      if (!is_string($args["location"])) {return 2;}
      $formID = $args["formID"];
      $location = $args["location"];

      $query = "UPDATE Form SET location = '$location' WHERE formID = $formID LIMIT 1;";

      $result = $conn->query($query);
      if ($result == FALSE) {return 1;}
      return 0;
    }

    /**
     * Update the lunchesNeeded field of a specific form with provided values.
     * 
     * @param args An array containing the formID and new lunchesNeeded value.
     * 
     * @return 0 on success, 1 for query error, and 2 for param error.
     */
    public static function updateLunchesNeeded($args) {
      $conn = Secret::connectDB("lunch");

      // Data verificiation checks
      if (!is_numeric($args["formID"])) {return 2;}
      if (!is_numeric($args["numLunches"])) {return 2;}
      $lunchesNeeded = $args["numLunches"];
      $formID = $args["formID"];

      $query = "UPDATE Form SET lunchesNeeded = $lunchesNeeded WHERE formID = $formID LIMIT 1;";

      $result = $conn->query($query);
      if ($result == FALSE) {return 1;}
      return 0;
    }

    /**
     * Update one of the pickupday fields of a specific form with provided values.
     * 
     * @param args An array containing the formID, column to be updated, and the
     * new value.
     * 
     * @return 0 on success, 1 for query error, and 2 for param error.
     */
    public static function updatePickupDay($args) {
      $conn = Secret::connectDB("lunch");
      $boolTypes = [0,1];

      // Check formID, return if invalid
      $formID = $args["formID"];
      if (!is_numeric($formID) || $formID < 1) { return 2; }


      // Query start
      $query = "UPDATE Form SET";

      // Parse through args to apply needed query segments
      if(isset($args["pickupMon"    ]) && in_array($args["pickupMon"], $boolTypes)) {
        $var = $args["pickupMon"];
        $query .= " pickupMon = $var";
      }
      if(isset($args["pickupTue"    ]) && in_array($args["pickupTue"], $boolTypes)) {
        $var = $args["pickupTue"];
        $query .= " pickupTue = $var";
      }
      if(isset($args["pickupWed"    ]) && in_array($args["pickupWed"], $boolTypes)) {
        $var = $args["pickupWed"];
        $query .= " pickupWed = $var";
      }
      if(isset($args["pickupThu"    ]) && in_array($args["pickupThu"], $boolTypes)) {
        $var = $args["pickupThu"];
        $query .= " pickupThu = $var";
      }
      if(isset($args["pickupFri"    ]) && in_array($args["pickupFri"], $boolTypes)) {
        $var = $args["pickupFri"];
        $query .= " pickupFri = $var";
      }
      unset($var);

      // Query end
      $query .= " WHERE formID = $formID LIMIT 1;";
      $result = $conn->query($query);

      if ($result == FALSE) {return 1;}
      return 0;
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
    $conn = Secret::connectDB("lunch");

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
    $result = $conn->query($query);

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
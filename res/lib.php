<?php
  // error_reporting(E_ALL);
  // ini_set('display_errors', '1');

  require_once realpath($_SERVER["DOCUMENT_ROOT"])."/account/lib.php";
  require_once realpath($_SERVER["DOCUMENT_ROOT"])."/res/secret.php";

  // Contains functions for easy database interactions
  class Database {

    /**
     * Creates a Form entry in the database with the given values. It checks for data
     * validity before calling arrayToInsertString and making the query.
     *
     * @param args The values to be inserted into the database.
     * @return returnData An array with code, message, and relevant metadata.
     */
    public static function createForm($args) {
      $conn = Secret::connectDB("lunch");
      $boolTypes = [0,1];
      $insertArgs = [];
      $returnData = [];

      // Data verification checks
      if(isset($args["pickupMon"    ]) && in_array($args["pickupMon"], $boolTypes)) {
        $insertArgs["pickupMon"    ] = $args["pickupMon"];
      } else {
        $returnData = [
          "code"    => 200,
          "message" => "Invalid pickupMon"
        ];

        return $returnData;
      }
      if(isset($args["pickupTue"    ]) && in_array($args["pickupTue"], $boolTypes)) {
        $insertArgs["pickupTue"    ] = $args["pickupTue"];
      } else {
        $returnData = [
          "code"    => 200,
          "message" => "Invalid pickupTue"
        ];

        return $returnData;
      }
      if(isset($args["pickupWed"    ]) && in_array($args["pickupWed"], $boolTypes)) {
        $insertArgs["pickupWed"    ] = $args["pickupWed"];
      } else {
        $returnData = [
          "code"    => 200,
          "message" => "Invalid pickupWed"
        ];

        return $returnData;
      }
      if(isset($args["pickupThu"    ]) && in_array($args["pickupThu"], $boolTypes)) {
        $insertArgs["pickupThu"    ] = $args["pickupThu"];
      } else {
        $returnData = [
          "code"    => 200,
          "message" => "Invalid pickupThu"
        ];

        return $returnData;
      }
      if(isset($args["location"     ]) && is_string($args["location"])) {
        $insertArgs["location"     ] = $args["location"];
      } else {
        $returnData = [
          "code"    => 200,
          "message" => "Invalid location"
        ];

        return $returnData;
      }
      if(isset($args["isEnabled"    ]) && in_array($args["isEnabled"], $boolTypes)) {
        $insertArgs["isEnabled"    ] = $args["isEnabled"];
      } else {
        $returnData = [
          "code"    => 200,
          "message" => "Invalid isEnabled"
        ];

        return $returnData;
      }
      if(isset($args["lunchesNeeded"]) && (0 <= $args["lunchesNeeded"])) {
        $insertArgs["lunchesNeeded"] = $args["lunchesNeeded"];
      } else {
        $returnData = [
          "code"    => 200,
          "message" => "Invalid lunchesNeeded"
        ];
        return $returnData;
      }
      if(isset($args["allergies"    ])) {
        $insertArgs["allergies"    ] = $args["allergies"];
      }
      if(isset($args["timeSubmitted"])) {
        $insertArgs["timeSubmitted"] = $args["timeSubmitted"];
      }

      // Get insert string
      $insertStr = arrayToInsertString($insertArgs);

      // Run query and return the insert id
      $query = "INSERT INTO Form $insertStr;";
      $result = $conn->query($query);
      if ($result == FALSE) {
        $returnData = [
          "code"    => 310,
          "message" => "Query error"
        ];
      }
      else if ($conn->affected_rows == 0) {
        $returnData = [
          "affectedRows" => $conn->affected_rows,
          "code"    => 120,
          "message" => "No inserts made"
        ];
      }
      else {
        $returnData = [
          "entryID" => $conn->insert_id,
          "affectedRows" => $conn->affected_rows,
          "code"    => 110,
          "message" => "Success" 
        ];
      }

      return $returnData;
    }

    /**
     * Creates a FormLink entry in the database with the given values. It checks
     * for data validity before calling arrayToInsertString and making the query.
     *
     * @param args The values to be inserted into the database.
     * @return returnData An array with code, message, and relevant metadata.
     */
    public static function createFormLink($args) {
      $conn = Secret::connectDB("lunch");
      $insertArgs = [];
      $returnData = [];

      if (isset($args["individualID"]) && is_numeric($args["individualID"])) {
        $insertArgs["individualID"] = $args["individualID"];
      } else {
        $returnData = [
          "code"    => 200,
          "message" => "Invalid individualID"
        ];

        return $returnData;
      }
      if (isset($args["formID"]) && is_numeric($args["formID"])) {
        $insertArgs["formID"] = $args["formID"];
      } else {
        $returnData = [
          "code"    => 200,
          "message" => "Invalid formID"
        ];

        return $returnData;
      }

      // Get insert string
      $insertStr = arrayToInsertString($insertArgs);

      // Run query
      $query = "INSERT INTO FormLink $insertStr;";
      $result = $conn->query($query);
      if ($result == FALSE) {
        $returnData = [
          "code"    => 310,
          "message" => "Query error"
        ];
      }
      else if ($conn->affected_rows == 0) {
        $returnData = [
          "affectedRows" => $conn->affected_rows,
          "code"    => 120,
          "message" => "No inserts made"
        ];
      }
      else {
        $returnData = [
          "affectedRows" => $conn->affected_rows,
          "code"    => 110,
          "message" => "Success" 
        ];
      }

      return $returnData;
    }

    /**
     * Creates an Individual entry in the database with the given values. It checks
     * for data validity before calling arrayToInsertString and making the query.
     *
     * @param args The values to be inserted into the database.
     * @return returnData An array with code, message, and relevant metadata. 
     */
    public static function createIndividual($args) {
      $conn = Secret::connectDB("lunch");
      $insertArgs = [];
      $returnData = [];

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

      // Data verification check


      $insertStr = arrayToInsertString($insertArgs);

      $query = "INSERT INTO Individual $insertStr;";
      // echo $query; // Echo for testing
      $result = $conn->query($query);
      if ($result == FALSE) {
        $returnData = [
          "code"    => 310,
          "message" => "Query error"
        ];
      }
      else if ($conn->affected_rows == 0) {
        $returnData = [
          "affectedRows" => $conn->affected_rows,
          "code"    => 120,
          "message" => "No inserts made"
        ];
      }
      else {
        $returnData = [
          "entryID" => $conn->insert_id,
          "affectedRows" => $conn->affected_rows,
          "code"    => 110,
          "message" => "Success" 
        ];
      }

      return $returnData;
    }

    /**
     * Creates an Organization entry in the database with the given values. It checks
     * for data validity before calling arrayToInsertString and making the query.
     *
     * @param args The values to be inserted into the database.
     * @return returnData An array with code, message, and relevant metadata. 
     */
    public static function createOrganization($args) {
      $conn = Secret::connectDB("lunch");
      $insertArgs = [];
      $returnData = [];

      // Data verification checks
      if(isset($args["orgName"])) {
        $insertArgs["orgName"] = $args["orgName"];
      } else {
        $returnData = [
          "code"    => 210,
          "message" => "orgName not set"
        ];
        return $returnData;
      }
      if(isset($args["mainContact"]) && is_numeric($args["mainContact"])){
        $insertArgs["mainContact"] = $args["mainContact"];
      } else {$insertArgs["mainContact"] = null;}

      if(isset($args["signupContact"]) && is_numeric($args["signupContact"])){
        $insertArgs["signupContact"] = $args["signupContact"];
      } else {
        $returnData = [
          "code"    => 200,
          "message" => "Invalid signupContact"
        ];
        return $returnData;
      }

      $insertStr = arrayToInsertString($insertArgs);

      $query = "INSERT INTO Organization $insertStr;";
      $result = $conn->query($query);
      if ($result == FALSE) {
        $returnData = [
          "code"    => 310,
          "message" => "Query error"
        ];
      }
      else if ($conn->affected_rows == 0) {
        $returnData = [
          "affectedRows" => $conn->affected_rows,
          "code"    => 120,
          "message" => "No inserts made"
        ];
      }
      else {
        $returnData = [
          "entryID" => $conn->insert_id,
          "affectedRows" => $conn->affected_rows,
          "code"    => 110,
          "message" => "Success" 
        ];
      }

      return $returnData;
    }

    /**
     * Creates a Pickup entry in the database with the given values. It checks
     * for data validity before calling arrayToInsertString and making the query.
     *
     * @param args The values to be inserted into the database.
     * @return returnData An array with code, message, and relevant metadata. 
     */
    public static function createPickup($args) {
      $conn = Secret::connectDB("lunch");
      $insertArgs = [];
      $returnData = [];

      // Data verification checks
      if (isset($args["formID"]) && is_numeric($args["formID"])) {
        $insertArgs["formID"] = $args["formID"];
      } else {
        $returnData = [
          "code"    => 200,
          "message" => "Invalid formID"
        ];

        return $returnData;
      }
      if (isset($args["pickupTime"])) {
        $insertArgs["pickupTime"] = $args["pickupTime"];
      } else {
        $returnData = [
          "code"    => 210,
          "message" => "Invalid pickupTime"
        ];

        return $returnData;
      }
      if (isset($args["amount"]) && (0 <= $args["amount"])) {
        $insertArgs["amount"] = $args["amount"];
      } else {
        $returnData = [
          "code"    => 200,
          "message" => "Invalid amount"
        ];
        
        return $returnData;
      }

      // Get insert string
      $insertStr = arrayToInsertString($insertArgs);

      // Run query and return the insert id
      $query = "INSERT INTO Pickup $insertStr;";
      $conn->query($query);

      $result = $conn->query($query);
      if ($result == FALSE) {
        $returnData = [
          "code"    => 310,
          "message" => "Query error"
        ];
      }
      else if ($conn->affected_rows == 0) {
        $returnData = [
          "affectedRows" => $conn->affected_rows,
          "code"    => 120,
          "message" => "No inserts made"
        ];
      }
      else {
        $returnData = [
          "entryID" => $conn->insert_id,
          "affectedRows" => $conn->affected_rows,
          "code"    => 110,
          "message" => "Success" 
        ];
      }

      return $returnData;
    }

    /**
     * Creates an FormVolunteer entry in the database with the given values. It checks
     * for data validity before calling arrayToInsertString and making the query.
     *
     * @param args The values to be inserted into the database.
     * @return returnData An array with code, message, and relevant metadata. 
     */
    public static function createFormVolunteer($args) {
      $conn = Secret::connectDB("lunch");
      $insertArgs = [];

      // Data verification checks
      if (isset($args["orgID"]) && is_numeric($args["orgID"])) {
        $insertArgs["orgID"] = $args["orgID"];
      } else if (isset($args["orgID"])){
        $returnData = [
          "code"    => 200,
          "message" => "Invalid orgID"
        ];

        return $returnData;
      }
      if (isset($args["weekInTheSummer" ]))
      {
        $insertArgs["weekInTheSummer"] = $args["weekInTheSummer"];
      }
      if (isset($args["bagDecoration"   ]))
      {
        $insertArgs["bagDecoration"] = $args["bagDecoration"];
      }
      if (isset($args["fundraising"    ]))
      {
        $insertArgs["fundraising"] = $args["fundraising"];
      }
      if (isset($args["supplyGathering"]))
      {
        $insertArgs["supplyGathering"] = $args["supplyGathering"];
      }
      if (isset($args["timeSubmitted"   ])) {
        $insertArgs["timeSubmitted"] = $args["timeSubmitted"];
      }

      // Get insert string
      $insertStr = arrayToInsertString($insertArgs);

      // Run query
      $query = "INSERT INTO FormVolunteer $insertStr;";
      $result = $conn->query($query);

      if ($result == FALSE) {
        $returnData = [
          "code"    => 310,
          "message" => "Query error"
        ];
      }
      else if ($conn->affected_rows == 0) {
        $returnData = [
          "affectedRows" => $conn->affected_rows,
          "code"    => 120,
          "message" => "No inserts made"
        ];
      }
      else {
        $returnData = [
          "entryID" => $conn->insert_id,
          "affectedRows" => $conn->affected_rows,
          "code"    => 110,
          "message" => "Success" 
        ];
      }

      return $returnData;
    }

    /**
     * Creates a FormVolunteerLink entry in the database with the given values. It checks
     * for data validity before calling arrayToInsertString and running the query.
     *
     * @param args The values to be inserted into the database.
     * @return returnData An array with code, message, and relevant metadata. 
     */
    public static function createFormVolunteerLink($args) {
      $conn = Secret::connectDB("lunch");
      $insertArgs = [];

      // Data verificiation checks
      if(isset($args["individualID"]) && (0 < $args["individualID"])) {
        $insertArgs["individualID"] = $args["individualID"];
      } else {
        $returnData = [
          "code"    => 200,
          "message" => "Invalid individualID"
        ];
        
        return $returnData;
      }
      if (isset($args["volunteerFormID"]) && (0 < $args["volunteerFormID"])) {
        $insertArgs["volunteerFormID"] = $args["volunteerFormID"];
      } else {
        $returnData = [
          "code"    => 200,
          "message" => "Invalid volunteerFormID"
        ];
        
        return $returnData;
      }

      // Get insert string
      $insertStr = arrayToInsertString($insertArgs);

      // Run query
      $query = "INSERT INTO FormVolunteerLink $insertStr;";

      $result = $conn->query($query);
      if ($result == FALSE) {
        $returnData = [
          "code"    => 310,
          "message" => "Query error"
        ];
      }
      else if ($conn->affected_rows == 0) {
        $returnData = [
          "affectedRows" => $conn->affected_rows,
          "code"    => 120,
          "message" => "No inserts made"
        ];
      }
      else {
        $returnData = [
          "affectedRows" => $conn->affected_rows,
          "code"    => 110,
          "message" => "Success" 
        ];
      }

      return $returnData;
    }

    /**
     * Deletes a Form entry based on the passed ID. Verifies the formID is numeric,
     * and limits the deletion to one entry.
     *
     * @param formID The ID of the target row.
     * @return returnData An array with code, message, and relevant metadata. 
     */
    public static function deleteForm($formID) {
      $conn = Secret::connectDB("lunch");
      if (!is_numeric($formID)) { 
        $returnData = [
          "code"    => 220,
          "message" => "Invalid formID"
        ];

        return $returnData;
      }

      $query = "DELETE FROM Form WHERE formID = $formID LIMIT 1;";
      $result = $conn->query($query);

      if ($result == FALSE) {
        $returnData = [
          "code"         => 310,
          "message"      => "Query error"
        ];
      } else if ($conn->affected_rows == 0) {
        $returnData = [
          "affectedRows" => $conn->affected_rows,
          "code"         => 120,
          "message"      => "No entries deleted"
        ];
      } else {
        $returnData = [
          "affectedRows" => $conn->affected_rows,
          "code"         => 110,
          "message"      => "Success"
        ];
      }

      return $returnData;
    }

    /**
     * Deletes a FormLink entry based on the passed IDs. Verifies the IDs are numeric,
     * and limits the deletion to one entry.
     *
     * @param args An array with the IDs of the target row.
     * @return returnData An array with code, message, and relevant metadata. 
     */
    public static function deleteFormLink($args) {
      $conn = Secret::connectDB("lunch");

      // Data verification checks
      if (!is_numeric($formID)) {
        $returnData = [
          "code"    => 220,
          "message" => "Invalid formID"
        ];

        return $returnData;
      }
      if (!is_numeric($args["individualID"])) {
        $returnData = [
          "code"    => 220,
          "message" => "Invalid individualID"
        ];

        return $returnData;
      }

      $formID = $args["formID"];
      $individualID = $args["individualID"];

      $query = "DELETE FROM FormLink WHERE formID = $formID AND individualID = $individualID LIMIT 1;";
      $result = $conn->query($query);

      if ($result == FALSE) {
        $returnData = [
          "code"         => 310,
          "message"      => "Query error"
        ];
      } else if ($conn->affected_rows == 0) {
        $returnData = [
          "affectedRows" => $conn->affected_rows,
          "code"         => 120,
          "message"      => "No entries deleted"
        ];
      } else {
        $returnData = [
          "affectedRows" => $conn->affected_rows,
          "code"         => 110,
          "message"      => "Success"
        ];
      }
      
      return $returnData;
    }

    /**
     * Deletes a FormVolunteer entry based on the passed ID. Verifies the
     * formID is numeric, and limits the deletion to one entry.
     *
     * @param volunteerFormID The ID of the target row.
     * @return returnData An array with code, message, and relevant metadata. 
     */
    public static function deleteFormVolunteer($volunteerFormID) {
      $conn = Secret::connectDB("lunch");
      if (!is_numeric($volunteerFormID)) {
        $returnData = [
          "code"    => 220,
          "message" => "Invalid volunteerFormID"
        ];

        return $returnData;
      }

      $query = "DELETE FROM FormVolunteer WHERE volunteerFormID = $volunteerFormID LIMIT 1;";
      $result = $conn->query($query);

      if ($result == FALSE) {
        $returnData = [
          "code"         => 310,
          "message"      => "Query error"
        ];
      } else if ($conn->affected_rows == 0) {
        $returnData = [
          "affectedRows" => $conn->affected_rows,
          "code"         => 120,
          "message"      => "No entries deleted"
        ];
      } else {
        $returnData = [
          "affectedRows" => $conn->affected_rows,
          "code"         => 110,
          "message"      => "Success"
        ];
      }
      
      return $returnData;
    }

    /**
     * Deletes an Individual entry based on the passed ID. Verifies the
     * formID is numeric, and limits the deletion to one entry.
     *
     * @param individualID The ID of the target row.
     * @return returnData An array with code, message, and relevant metadata. 
     */
    public static function deleteIndividual($individualID) {
      $conn = Secret::connectDB("lunch");
      if (!is_numeric($individualID)) {
        $returnData = [
          "code"    => 220,
          "message" => "Invalid individualID"
        ];

        return $returnData;
      }

      $query = "DELETE FROM Individual WHERE individualID = $individualID LIMIT 1;";
      $result = $conn->query($query);

      if ($result == FALSE) {
        $returnData = [
          "code"         => 310,
          "message"      => "Query error"
        ];
      } else if ($conn->affected_rows == 0) {
        $returnData = [
          "affectedRows" => $conn->affected_rows,
          "code"         => 120,
          "message"      => "No entries deleted"
        ];
      } else {
        $returnData = [
          "affectedRows" => $conn->affected_rows,
          "code"         => 110,
          "message"      => "Success"
        ];
      }
      
      return $returnData;
    }

    /**
     * Deletes an Organization entry based on the passed ID. Verifies the
     * ID is numeric, and limits the deletion to one entry.
     *
     * @param orgID The ID of the target row.
     * @return returnData An array with code, message, and relevant metadata. 
     */
    public static function deleteOrganization($orgID) {
      $conn = Secret::connectDB("lunch");
      if (!is_numeric($orgID)) {
        $returnData = [
          "code"    => 220,
          "message" => "Invalid orgID"
        ];

        return $returnData;
      }

      $query = "DELETE FROM Organization WHERE orgID = $orgID LIMIT 1;";
      $result = $conn->query($query);

      if ($result == FALSE) {
        $returnData = [
          "code"         => 310,
          "message"      => "Query error"
        ];
      } else if ($conn->affected_rows == 0) {
        $returnData = [
          "affectedRows" => $conn->affected_rows,
          "code"         => 120,
          "message"      => "No entries deleted"
        ];
      } else {
        $returnData = [
          "affectedRows" => $conn->affected_rows,
          "code"         => 110,
          "message"      => "Success"
        ];
      }
      
      return $returnData;
    }

    /**
     * Deletes a Pickup entry based on the passed ID. Verifies the
     * ID is numeric, and limits the deletion to one entry.
     *
     * @param pickupID The ID of the target row.
     * @return returnData An array with code, message, and relevant metadata. 
     */
    public static function deletePickup($pickupID) {
      $conn = Secret::connectDB("lunch");
      if (!is_numeric($pickupID)) {
        $returnData = [
          "code"    => 220,
          "message" => "Invalid pickupID"
        ];

        return $returnData;
      }

      $query = "DELETE FROM Pickup WHERE pickupID = $pickupID LIMIT 1;";
      $result = $conn->query($query);

      if ($result == FALSE) {
        $returnData = [
          "code"         => 310,
          "message"      => "Query error"
        ];
      } else if ($conn->affected_rows == 0) {
        $returnData = [
          "affectedRows" => $conn->affected_rows,
          "code"         => 120,
          "message"      => "No entries deleted"
        ];
      } else {
        $returnData = [
          "affectedRows" => $conn->affected_rows,
          "code"         => 110,
          "message"      => "Success"
        ];
      }
      
      return $returnData;
    }

    /**
     * Get all links for an individual matching the passed individualID.
     * Confirms the ID is numeric.
     *
     * @param individualID The ID of the target individual.
     * @return returnData An array with code, message, relevant metadata,
     *   and any data retrieved.
     */
    public static function getAllLinks($individualID) {
      $conn = Secret::connectDB("lunch");
      $data = [];
      $returnData = [];

      // Data verification checks
      if (!is_numeric($individualID)) {
        $returnData = [
          "code"    => 220,
          "message" => "Invalid individualID"
        ];

        return $returnData;
      }


      $data["FormVolunteer"] = [];
      $query = "SELECT volunteerFormID, timeSubmitted FROM FormVolunteer"
              ." WHERE volunteerFormID ="
              ." (SELECT volunteerFormID FROM FormVolunteerLink WHERE individualID = $individualID);";
      
      $result = $conn->query($query);

      if ($result == FALSE) {
        $returnData = [
          "code" => 310,
          "message" => "Query error"
        ];
        return $returnData;
      } else if ($result->num_rows == 0) {
        $returnData = [
          "code"    => 120,
          "message" => "No linked volunteer forms"
        ];
      } else {
        // Add data to data array
        while ($row = $result->fetch_assoc())  {
          $data["FormVolunteer"][] = $row;
        }
      }
      
      // Start second query
      $data["Form"] = [];
      $query = "SELECT formID FROM FormLink WHERE individualID = $individualID;";
      $result = $conn->query($query);

      // Check results for second query
      if ($result == FALSE) {
        $returnData = [
          "code" => 310,
          "message" => "Query error"
        ];

        return $returnData;
      } else if ($result->num_rows == 0) {
        // If no links were found for individual, set message/code
        if (isset($returnData["message"])) {
          $returnData = [
            "code"    => 120,
            "message" => "No links"
          ];
        } else {
          $returnData["code"]    = 130;
          $returnData["message"] = "No linked forms";
        }
      } else {
        while ($row = $result->fetch_assoc())  {
          $data["Form"][] = $row;
        }

        $returnData["code"]    = 110;
        $returnData["message"] = "Success";
      }

      $returnData["data"] = $data;

      return $returnData;
    }

    /**
     * Get the distinct collections from Donation.
     *
     * @return returnData An array with code, message, relevant metadata,
     *   and any data retrieved.
    */
    public static function getCollections() {
      $conn = Secret::connectDB("lunch");
      $data = [];
      $returnData = [];

      $query = "SELECT DISTINCT coll FROM Donation;";
      $result = $conn->query($query);

      if ($result == FALSE) {
        $returnData = [
          "code"    => 310,
          "message" => "Query error"
        ];
      } else if ($result->num_rows == 0) {
        $returnData = [
          "numRows" => $result->num_rows,
          "code"    => 120,
          "message" => "No entries found"
        ];
      } else {
        while ($row = $result->fetch_assoc()) { $data[] = $row; }

        $returnData = [
          "data"    => $data,
          "numRows" => $result->num_rows,
          "code"    => 110,
          "message" => "Success"
        ];
      }

      return $returnData;
    }

    /**
     * Get the most recent donations for a collection. Optional limit and collection
     * can be passed, will check whether the limit is valid.
     *
     * @param collection The collection to get donations for, defaults to NULL.
     * @param limit The limit for the query, defaults to 8.
     * @return returnData An array with code, message, relevant metadata,
     *   and any data retrieved.
     */
    public static function getCollectionDonations($collection = NULL, $limit = 8) {
      $conn = Secret::connectDB("lunch");
      $data = [];
      $returnData = [];

      if ($collection != NULL && !is_string($collection)) {
        $returnData = [
          "code"    => 220,
          "message" => "Invalid collection"
        ];

        return $returnData;
      }

      if ($limit <= 0) {
        $returnData = [
          "code"    => 230,
          "message" => "Invalid limit value" 
        ];
        
        return $returnData;
      }

      $query = "SELECT * FROM Donation WHERE";
      
      if ($collection == NULL) {
        $query .= " coll IS NULL"; 
      } else {
        $query .= " coll = '$collection'";
      }

      $query .= " ORDER BY year DESC LIMIT $limit;";

      $result = $conn->query($query);

      if ($result == FALSE) {
        $returnData = [
          "code"    => 310,
          "message" => "Query error"
        ];
      } else if ($result->num_rows == 0) {
        $returnData = [
          "numRows" => $result->num_rows,
          "code"    => 120,
          "message" => "No entries found"
        ];
      } else {
        while ($row = $result->fetch_assoc()) { $data[] = $row; }

        $returnData = [
          "data"    => $data,
          "numRows" => $result->num_rows,
          "code"    => 110,
          "message" => "Success"
        ];
      }

      return $returnData;
    }

    /**
     * Get the most recent donations. Optional limit can be passed, will
     * check whether the limit is valid.
     *
     * @param limit The limit for the query, defaults to 8.
     * @return returnData An array with code, message, relevant metadata,
     *   and any data retrieved.
     */
    public static function getDonations($limit = 8) {
      $conn = Secret::connectDB("lunch");
      $data = [];
      $returnData = [];

      if ($limit <= 0) {
        $returnData = [
          "code"    => 230,
          "message" => "Invalid limit value" 
        ];
        
        return $returnData;
      }

      $query = "SELECT * FROM Donation ORDER BY year DESC LIMIT $limit;";
      $result = $conn->query($query);

      if ($result == FALSE) {
        $returnData = [
          "code"    => 310,
          "message" => "Query error"
        ];
      } else if ($result->num_rows == 0) {
        $returnData = [
          "numRows" => $result->num_rows,
          "code"    => 120,
          "message" => "No entries found"
        ];
      } else {
        while ($row = $result->fetch_assoc()) { $data[] = $row; }

        $returnData = [
          "data"    => $data,
          "numRows" => $result->num_rows,
          "code"    => 110,
          "message" => "Success"
        ];
      }

      return $returnData;
    }

    /**
     * Get the Form entry matching the given ID. Checks whether the ID
     * is numeric.
     *
     * @param formID The ID of the target form.
     * @return returnData An array with code, message, relevant metadata,
     *   and any data retrieved.
     */
    public static function getForm($formID) {
      $conn = Secret::connectDB("lunch");
      $rawData = [];
      $resultData = [];

      if (!is_numeric($formID)) {
        $returnData = [
          "code"    => 220,
          "message" => "Invalid formID"
        ];

        return $returnData;
      }

      // Query
      $query = "SELECT f.*, i.individualID, i.individualName"
               ." FROM FormLink fl"
               ." INNER JOIN Form f ON f.formID = fl.formID"
               ." INNER JOIN Individual i ON fl.individualID = i.individualID"
               ." WHERE f.formID = $formID;";

      $result = $conn->query($query);

      if ($result == FALSE) {
        $returnData = [
          "code"    => 310,
          "message" => "Query error"
        ];
      } else if ($result->num_rows == 0){
        $returnData = [
          "numRows" => $result->num_rows,
          "code"    => 120,
          "message" => "No entries found"
        ];
      } else {
        while ($row = $result->fetch_assoc()){
          $formID = $row["formID"];
  
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

        // Set return data
        $returnData = [
          "data"    => $rawData[$formID],
          "numRows" => $result->num_rows,
          "code"    => 110,
          "message" => "Success"
        ];
      }

      return $returnData;
    }

    /**
     * Get all Form entries and all Individual entries linked to
     * those Form entries. 
     *
     * @return returnData An array with code, message, relevant metadata,
     *   and any data retrieved.
     */
    public static function getForms() {
      $conn = Secret::connectDB("lunch");
      $returnData = [];
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

      if ($result == FALSE) {
        $returnData = [
          "code"    => 310,
          "message" => "Query error"
        ];
      } else if ($result->num_rows == 0){
        $returnData = [
          "numRows" => $result->num_rows,
          "code"    => 120,
          "message" => "No entries found"
        ];
      } else {
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

        // Set return data
        $returnData = [
          "data"    => $data,
          "numRows" => $result->num_rows,
          "code"    => 110,
          "message" => "Success"
        ];
      }

      return $returnData;
    }

    /**
     * Get Individual entry matching passed ID.
     * 
     * @param individualID The ID of the target entry.
     * @return returnData An array with code, message, relevant metadata,
     *   and any data retrieved.
     */
    public static function getIndividual($individualID) {
      $conn = Secret::connectDB("lunch");
      $returnData = [];

      if (!is_numeric($individualID)) {
        $returnData = [
          "code"    => 220,
          "message" => "Invalid individualID"
        ];

        return $returnData;
      }

      $query = "SELECT * FROM Individual WHERE individualID = $individualID LIMIT 1;";
      $result = $conn->query($query);

      if ($result == FALSE) {
        $returnData = [
          "code"    => 310,
          "message" => "Query error"
        ];
      } else if ($result->num_rows == 0) {
        $returnData = [
          "numRows" => $result->num_rows,
          "code"    => 120,
          "message" => "No entries found"
        ];
      } else {

        $returnData = [
          "data"    => $result->fetch_assoc(),
          "numRows" => $result->num_rows,
          "code"    => 110,
          "message" => "Success"
        ];
      }
      
      return $returnData;
    }

    /**
     * Get all Individual entries.
     * 
     * @return returnData An array with code, message, relevant metadata,
     *   and any data retrieved.
     */
    public static function getIndividuals() {
      $conn = Secret::connectDB("lunch");
      $returnData = [];
      $data = [];

      $query = "SELECT * FROM Individual ORDER BY individualID DESC;";
      $result = $conn->query($query);

      if ($result == FALSE) {
        $returnData = [
          "code"    => 310,
          "message" => "Query error"
        ];
      } else if ($result->num_rows == 0) {
        $returnData = [
          "numRows" => $result->num_rows,
          "code"    => 120,
          "message" => "No entries found"
        ];
      } else {
        while ($row = $result->fetch_assoc()) { $data[] = $row; }

        $returnData = [
          "data"    => $data,
          "numRows" => $result->num_rows,
          "code"    => 110,
          "message" => "Success"
        ];
      }

      return $returnData;
    }

    /**
     * Get all distinct locations from Form.
     *
     * @return returnData An array with code, message, relevant metadata,
     *   and any data retrieved.
     */
    public static function getLocations() {
      $conn = Secret::connectDB("lunch");
      $returnData = [];
      $data = [];

      $query = "SELECT DISTINCT `location` FROM Form ORDER BY `location`;";
      $result = $conn->query($query);

      if ($result == FALSE) {
        $returnData = [
          "code"    => 310,
          "message" => "Query error"
        ];
      } else if ($result->num_rows == 0) {
        $returnData = [
          "numRows" => $result->num_rows,
          "code"    => 120,
          "message" => "No entries found"
        ];
      } else {
        while ($row = $result->fetch_assoc()) { $data[] = $row; }

        $returnData = [
          "data"    => $data,
          "numRows" => $result->num_rows,
          "code"    => 110,
          "message" => "Success"
        ];
      }

      return $returnData;
    }


    /**
     * Get the lunchesNeeded column from a Form entry matching the passed ID.
     *
     * @param formID The ID of target form.
     * @return returnData An array with code, message, relevant metadata,
     *   and any data retrieved.
     */
    public static function getLunchesNeeded($formId) {
      $conn = Secret::connectDB("lunch");
      $returnData = [];
      $data = [];

      if (!is_numeric($formId)) {
        $returnData = [
          "code"    => 220,
          "message" => "Invalid formID"
        ];

        return $returnData;
      }

      $query = "SELECT lunchesNeeded FROM Form WHERE formID=$formId AND isEnabled = 1;";
      $result = $conn->query($query);

      if ($result == FALSE) {
        $returnData = [
          "code"    => 310,
          "message" => "Query error"
        ];
      } else if ($result->num_rows == 0) {
        $returnData = [
          "numRows" => $result->num_rows,
          "code"    => 120,
          "message" => "No matching entries"
        ];
      } else {
        while ($row = $result->fetch_assoc()) {
          if( $row["lunchesNeeded"] != null )
            $data = $row["lunchesNeeded"];
        }

        $returnData = [
          "data"    => $data,
          "numRows" => $result->num_rows,
          "code"    => 110, 
          "message" => "Success"
        ];
      }

      return $returnData;
    }

    /**
     * Get all Organization entries.
     *
     * @return returnData An array with code, message, relevant metadata,
     *   and any data retrieved.
     */
    public static function getOrganizations() {
      $conn = Secret::connectDB("lunch");
      $returnData = [];
      $data = [];

      $query = "SELECT * FROM Organization ORDER BY orgName;";
      $result = $conn->query($query);

      if ($result == FALSE) {
        $returnData = [
          "code"    => 310,
          "message" => "Query error"
        ];
      } else if ($result->num_rows == 0) {
        $returnData = [
          "numRows" => $result->num_rows,
          "code"    => 120,
          "message" => "No entries found"
        ];
      } else {
        while ($row = $result->fetch_assoc()) { $data[] = $row; }

        $returnData = [
          "data"    => $data,
          "numRows" => $result->num_rows,
          "code"    => 110,
          "message" => "Success"
        ];
      }

      return $returnData;
    }

    /**
     * Get all meals for a specific pickup day.
     *
     * @param date The day of the week to check for in three letter format.
     * 
     * @return returnData An array with code, message, relevant metadata,
     *   and any data retrieved.
     */
    public static function getDayMeals($date) {
      $conn = Secret::connectDB("lunch");
      $returnData = [];
      $data = [];

      if (!isset($date)) {
        $returnData = [
          "code"    => 210,
          "message" => "date not set"
        ];

        return $returnData;
      }

      $query = "SELECT f.formID, f.lunchesNeeded, f.location, f.allergies, i.individualName"
              ." FROM FormLink fl"
              ." INNER JOIN Form f ON f.formID = fl.formID"
              ." INNER JOIN Individual i ON i.individualID = fl.individualID"
              ." WHERE isEnabled=1 AND pickup$date = 1"
              ." ORDER BY f.location, i.individualName;";

      $result = $conn->query($query);

      if ($result == FALSE) {
        $returnData = [
          "code"    => 310,
          "message" => "Query error"
        ];
      } else if ($result->num_rows == 0) {
        $returnData = [
          "numRows" => $result->num_rows,
          "code"    => 120,
          "message" => "No matching entries found"
        ];
      } else {
        while ($row = $result->fetch_assoc()) { $data[] = $row; }

        $returnData = [
          "data"    => $data,
          "numRows" => $result->num_rows,
          "code"    => 110,
          "message" => "Success"
        ];
      }

      return $returnData;
    }

    /**
     * Get all information for an volunteer from FormVolunteer and Individual given
     * a volunteerFormID.
     * 
     * @param volunteerFormID The ID of the target volunteer.
     *
     * @return returnData An array with code, message, relevant metadata,
     *   and any data retrieved.
     */
    public static function getVolunteer($volunteerFormID) {
      $conn = Secret::connectDB("lunch");
      $returnData = [];

      // Data verification checks
      if (!is_numeric($volunteerFormID)) {
        $returnData = [
          "code"    => 220,
          "message" => "Invalid volunteerFormID"
        ];

        return $returnData;
      }

      $query = "SELECT fv.*, i.individualName, i.phoneNumber, i.email, i.facebookMessenger, i.preferredContact"
              ." FROM FormVolunteerLink as fvl"
              ." INNER JOIN FormVolunteer fv ON fv.volunteerFormID = fvl.volunteerFormID"
              ." INNER JOIN Individual i ON i.individualID = fvl.individualID"
              ." WHERE fv.volunteerFormID = $volunteerFormID;";

      $result = $conn->query($query);

      if ($result == FALSE) {
        $returnData = [
          "code"    => 310,
          "message" => "Query error"
        ];
      } else if ($result->num_rows == 0) {
        $returnData = [
          "numRows" => $result->num_rows,
          "code"    => 120,
          "message" => "No matching entries found"
        ];
      } else {

        $returnData = [
          "data"    => $result->fetch_assoc(),
          "numRows" => $result->num_rows,
          "code"    => 110,
          "message" => "Success"
        ];
      }

      return $returnData;
    }

    /**
     * Get all Volunteer entries and the information in the linked Individual entries.
     *
     * @return returnData An array with code, message, relevant metadata,
     *   and any data retrieved.
     */
    public static function getVolunteers() {
      $conn = Secret::connectDB("lunch");
      $data = [];
      $returnData = [];

      $query = "SELECT fv.*, i.individualName, i.phoneNumber, i.email, i.facebookMessenger, i.preferredContact"
              ." FROM FormVolunteerLink as fvl"
              ." INNER JOIN FormVolunteer fv ON fv.volunteerFormID = fvl.volunteerFormID"
              ." INNER JOIN Individual i ON i.IndividualID = fvl.individualID"
              ." ORDER BY fv.volunteerFormID DESC;";

      $result = $conn->query($query);

      if ($result == FALSE) {
        $returnData = [
          "code"    => 310,
          "message" => "Query error"
        ];
      } else if ($result->num_rows == 0) {
        $returnData = [
          "numRows" => $result->num_rows,
          "code"    => 120,
          "message" => "No entries found"
        ];
      } else {
        while ($row = $result->fetch_assoc()) { $data[] = $row; }

        $returnData = [
          "data"    => $data,
          "numRows" => $result->num_rows,
          "code"    => 110,
          "message" => "Success"
        ];
      }

      return $returnData;
    }

    /**
     * Update the allergies field for target Form entry. Verifies all values
     * in args are valid.
     * 
     * @param args An array containing the new value and the ID for target entry.
     *
     * @return returnData An array with code, message, relevant metadata,
     *   and any data retrieved.
     */
    public static function updateAllergies($args) {
      $conn = Secret::connectDB("lunch");

      // Data verification checks
      if (!is_numeric($args["formID"])) {
        $returnData = [
          "code"    => 220,
          "message" => "Invalid formID"
        ];

        return $returnData;
      }
      if (!is_string($args["allergies"])) {
        $returnData = [
          "code"    => 220,
          "message" => "Invalid allergies"
        ];

        return $returnData;
      }
      $formID = $args["formID"];
      $allergies = $args["allergies"];

      $query = "UPDATE Form SET allergies = '$allergies' WHERE formID = $formID LIMIT 1;";

      $result = $conn->query($query);
      if ($result == FALSE) {
        $returnData = [
          "code" => 310,
          "message" => "Query error"
        ];
      } else if ($conn->affected_rows == 0){
        $returnData = [
          "affectedRows" => $conn->affected_rows,
          "code" => 120,
          "message" => "No matching entries found"
        ];
      } else {
        $returnData = [
          "affectedRows" => $conn->affected_rows,
          "code" => 110,
          "message" => "Success"
        ];
      }

      return $returnData;
    }

    /**
     * Update the isEnabled field for target Form entry. Verifies all values
     * in args are valid.
     * 
     * @param args An array containing the new value and the ID for target entry.
     *
     * @return returnData An array with code, message, relevant metadata,
     *   and any data retrieved.
     */
    public static function updateIsEnabled($args) {
      $conn = Secret::connectDB("lunch");

      // Data verificiation checks
      if (!is_numeric($args["formID"])) {
        $returnData = [
          "code"    => 220,
          "message" => "Invalid formID"
        ];
        return $returnData;
      }
      $formID = $args["formID"];
      $isEnabled = $args["isEnabled"];

      $query = "UPDATE Form SET isEnabled = $isEnabled WHERE formID = $formID LIMIT 1;";

      $result = $conn->query($query);
      if ($result == FALSE) {
        $returnData = [
          "code" => 310,
          "message" => "Query error"
        ];
      } else if ($conn->affected_rows == 0){
        $returnData = [
          "affectedRows" => $conn->affected_rows,
          "code" => 120,
          "message" => "No matching entries found"
        ];
      } else {
        $returnData = [
          "affectedRows" => $conn->affected_rows,
          "code" => 110,
          "message" => "Success"
        ];
      }
      
      return $returnData;
    }

    /**
     * Update the location field for target Form entry. Verifies all values in args
     * are valid.
     * 
     * @param args An array containing the new value and the ID for target entry.
     *
     * @return returnData An array with code, message, relevant metadata,
     *   and any data retrieved.
     */
    public static function updateLocation($args) {
      $conn = Secret::connectDB("lunch");

      // Data verificiation checks
      if (!is_numeric($args["formID"])) {
        $returnData = [
          "code"    => 220,
          "message" => "Invalid formID"
        ];
        return $returnData;
      }
      if (!is_string($args["location"])) {
        $returnData = [
          "code"    => 220,
          "message" => "Invalid location"
        ];
        return $returnData;
      }
      $formID = $args["formID"];
      $location = $args["location"];

      $query = "UPDATE Form SET location = '$location' WHERE formID = $formID LIMIT 1;";

      $result = $conn->query($query);

      if ($result == FALSE) {
        $returnData = [
          "code" => 310,
          "message" => "Query error"
        ];
      } else if ($conn->affected_rows == 0){
        $returnData = [
          "affectedRows" => $conn->affected_rows,
          "code" => 120,
          "message" => "No matching entries found"
        ];
      } else {
        $returnData = [
          "affectedRows" => $conn->affected_rows,
          "code" => 110,
          "message" => "Success"
        ];
      }
      
      return $returnData;
    }

    /**
     * Update the lunchesNeeded field for target Form entry. Verifies all values
     * in args are valid.
     * 
     * @param args An array containing the new value and the ID for target entry.
     *
     * @return returnData An array with code, message, relevant metadata,
     *   and any data retrieved.
     */
    public static function updateLunchesNeeded($args) {
      $conn = Secret::connectDB("lunch");
      $returnData = [];

      // Data verificiation checks
      if (!is_numeric($args["formID"])) {
        $returnData = [
          "code"    => 220,
          "message" => "Invalid formID"
        ];
        return $returnData;
      }
      if (!is_numeric($args["numLunches"]) || $args["numLunches"] < 0) {
        $returnData = [
          "code"    => 220,
          "message" => "Invalid number of lunches"
        ];
        return $returnData;
      }
      $lunchesNeeded = $args["numLunches"];
      $formID = $args["formID"];

      $query = "UPDATE Form SET lunchesNeeded = $lunchesNeeded WHERE formID = $formID LIMIT 1;";

      $result = $conn->query($query);
      if ($result == FALSE) {
        $returnData = [
          "code" => 310,
          "message" => "Query error"
        ];
      } else if ($conn->affected_rows == 0){
        $returnData = [
          "affectedRows" => $conn->affected_rows,
          "code" => 120,
          "message" => "No matching entries found"
        ];
      } else {
        $returnData = [
          "affectedRows" => $conn->affected_rows,
          "code" => 110,
          "message" => "Success"
        ];
      }
      
      return $returnData;
    }

     /**
     * Update one of the pickupday fields for target Form entry. Verifies all values in args
     * are valid.
     * 
     * @param args An array containing the new value, column to update, and the ID for target entry.
     *
     * @return returnData An array with code, message, relevant metadata,
     *   and any data retrieved.
     */
    public static function updatePickupDay($args) {
      $conn = Secret::connectDB("lunch");
      $returnData = [];

      // Check formID, return if invalid
      $formID = $args["formID"];
      if (!is_numeric($formID)) {
        $returnData = [
          "code"    => 220,
          "message" => "Invalid formID"
        ];

        return $returnData;
      } else if ($formID < 1) {
        $returnData = [
          "code"    => 230,
          "message" => "Invalid formID"
        ];

        return $returnData;
      }

      // Query start
      $query = "UPDATE Form SET";

      // Parse through args to apply needed query segments
      if(isset($args["pickupMon"    ])) {
        $var = $args["pickupMon"];
        $query .= " pickupMon = $var";
      }
      if(isset($args["pickupTue"    ])) {
        $var = $args["pickupTue"];
        $query .= " pickupTue = $var";
      }
      if(isset($args["pickupWed"    ])) {
        $var = $args["pickupWed"];
        $query .= " pickupWed = $var";
      }
      if(isset($args["pickupThu"    ])) {
        $var = $args["pickupThu"];
        $query .= " pickupThu = $var";
      }
      if(isset($args["pickupFri"    ])) {
        $var = $args["pickupFri"];
        $query .= " pickupFri = $var";
      }

      // Query end
      $query .= " WHERE formID = $formID LIMIT 1;";
      $result = $conn->query($query);

      if ($result == FALSE) {
        $returnData = [
          "code" => 310,
          "message" => "Query error"
        ];
      } else if ($conn->affected_rows == 0){
        $returnData = [
          "affectedRows" => $conn->affected_rows,
          "code" => 120,
          "message" => "No matching entries found"
        ];
      } else {
        $returnData = [
          "affectedRows" => $conn->affected_rows,
          "code" => 110,
          "message" => "Success"
        ];
      }
      
      return $returnData;
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
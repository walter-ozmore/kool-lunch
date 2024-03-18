<?php
  /**
   * All secrets go here. Passwords, database information, etc
   *
   * $db_conn = Secret::connectDB($db_name);
   */

  class Secret {
    // Informational variables, ie. passwords and such
    private static $databaseInfo = [
      "lunch"=>[
        "server"=>"162.144.12.171",
        "username"=>"everying_lunch",
        "password"=>"w4F4j69HdN9K7yfreTy%VUWszW88Wv4#xFdsYN+XY8FDW4qQswje_C%Z+g^5Yp!",
        "database"=>"everying_koolLunches_v2"
      ],
      "account"=>[
        "server"=>"162.144.12.171",
        "username"=>"everying_access",
        "password"=>"Ovtn8eEzwU.0GeH9{aW|x)w,3/}sj72hsj)]YH:uXUfbs-mGos]c^:3#(&;V`aj0",
        "database"=>"everying_accounts"
      ],
    ];


    // Other variables
    private static $openDatabases = [];


    /**
     * Connects to a database of the given name/id then returns the connection
     * var, if the database has already been connected then return the connection
     * that is already open.
     *
     * @param db_name The name/id of the database request
     * @return conn The connection to the database queried
     */
    public static function connectDB($db_name) {
      if( isset(self::$openDatabases[ $db_name ]) )
        return self::$openDatabases[ $db_name ];

      $database = self::$databaseInfo[ $db_name ];

      $db_conn = new mysqli($database["server"], $database["username"], $database["password"], $database["database"]);

      self::$openDatabases[ $db_name ] = $db_conn;

      if(!$db_conn)
        die("Connection failed: " . $db_conn->connect_error);
      return $db_conn;
    }
  }
?>
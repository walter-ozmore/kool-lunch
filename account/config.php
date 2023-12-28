<?php
  /**
   * Config file, this is made so that we can drag and drop the account stuff in to any project
   * to add easy account managment
   * 
   * Expected SQL tables, User
   */
  
  $account_conn = Secret::connectDB("account");
  // $cookie_domain = "walter-ozmore.dev";
  $cookie_domain = $_SERVER['HTTP_HOST'];
  $cookie_path = "/";
  $cookie_authentication = "9lJSNWquvlNkYhKXJ7";

  $allowNullPasswords = false;
?>
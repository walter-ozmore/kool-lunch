<?php
  error_reporting(E_ALL);
  ini_set('display_errors', '1');

  require realpath($_SERVER["DOCUMENT_ROOT"])."/res/Parsedown.php";
  require realpath($_SERVER["DOCUMENT_ROOT"])."/res/lib.php";

  // $markdown = file_get_contents('example.md'); // Load file, maybe use later

  $setting = Database::settingsGet(["key"=>"homePageText"]);
  // echo var_dump($obj);
  $markdown = $setting["value"];

  $Parsedown = new Parsedown();
  echo $Parsedown->text($markdown);
?>
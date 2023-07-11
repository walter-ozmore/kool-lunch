<!DOCTYPE html>
<html>
  <head>
    <title>Data | KoolLunches</title>
    <?php
      require realpath($_SERVER["DOCUMENT_ROOT"])."/res/head.php";
      require_once realpath($_SERVER["DOCUMENT_ROOT"])."/res/lib.php";
    ?>
    <style>
      table {
        width: 100%;
        border-collapse: collapse;
      }
      td {
        border: 1px solid;
      }

      .stats h2 {
        margin-top: 1em;
      }

      .content h2, h3 {
        margin-bottom: 0em;
      }

      .content p {
        margin: 0em;
        padding: 0em;
      }

      .big {
        font-size: 1.5em;
        padding: .01em 1em;
      }

      .sidebar {
        position: fixed;
        top: 10px;
        left: 10px;

        margin: 0px;
        padding: .5em;
      }

      .sidebar select {
        font-size: max(1em, 25%);
      }
    </style>

    <!-- Main page file -->
    <script src="/scripts/view-data.js"></script>

    <!-- Include script for pages -->
    <script src="/scripts/vd-overview.js"></script>
    <script src="/scripts/vd-forms.js"></script>
  </head>

  <header>
    <?php
      include realpath($_SERVER["DOCUMENT_ROOT"])."/res/header.php";
    ?>
  </header>

  <body>
    <div class="sidebar content">
      <select id="selector"></select>
    </div>

    <div id="pages"></div>
  </body>
</html>
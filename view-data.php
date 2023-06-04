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
    </style>

    <script src="/scripts/view-data.js"></script>
  </head>

  <header>
    <?php
      include realpath($_SERVER["DOCUMENT_ROOT"])."/res/header.php";
    ?>
  </header>

  <body>
    <div id="stats" class="content stats">
      <pre id="output"></pre>
    </div>
    <div id="form-area"></div>
  </body>
</html>
<!DOCTYPE html>
<html>
  <head>
    <title>Kool Lunches</title>

    <?php
      require realpath($_SERVER["DOCUMENT_ROOT"])."/res/head.php";
      require realpath($_SERVER["DOCUMENT_ROOT"])."/res/lib.php";
    ?>

    <style>
      .row {
        display: grid;
        grid-template-columns: 80% 20%;
        padding: .5em .25em;
        border-bottom: 1px solid black;
        font-size: 1.5em;
        /* min-height: 5em; */
      }

      .row p {
        margin: auto 0em;
        padding: 0em;
      }

      .row input {
        transform: scale(2);
        margin: auto;
        height: 100%;
      }

      .row > span {
        padding: 0em .5em; /* This is kind of a bandaid fix, need to be better */
        text-align: right;
      }

      #selectors {
        font-size: 1.5em;
      }
    </style>

    <script src="/scripts/tracker.js"></script>
  </head>

  <header>
    <?php require realpath($_SERVER["DOCUMENT_ROOT"])."/res/header.php"; ?>
  </header>

  <body>
    <div id="selectors" style="text-align: center">
      <select id="location-selector" onchange="checkSelector()" style="display: none"></select><br>
      <input type="date" id="date-selector">
    </div>


    <div id="display"></div>
  </body>

  <?php require realpath($_SERVER["DOCUMENT_ROOT"])."/res/footer.php"; ?>
</html>

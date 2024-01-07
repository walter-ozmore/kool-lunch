<!DOCTYPE html>
<html>
  <head>
    <title>Kool Lunches</title>

    <?php
      require realpath($_SERVER["DOCUMENT_ROOT"])."/res/head.php";
      require realpath($_SERVER["DOCUMENT_ROOT"])."/res/lib.php";
    ?>

    <style>
      /* Row/Form of information */
      .row {
        display: grid;
        grid-template-columns: 65% 25% 10%;
        padding: .5em .25em;
        border-bottom: 1px solid black;
        font-size: 1.5em;
        /* min-height: 5em; */
      }

      /* Name */
      .row p {
        margin: auto 0em;
        padding: 0em;
      }

      /* Check boxes */
      .row input {
        transform: scale(2);
        margin: auto;
        height: 100%;
      }

      /* Sets the right side of the data to align right */
      .row > span {
        padding: 0em .5em; /* This is kind of a bandaid fix, need to be better */
        text-align: right;
      }

      .quantity {
        padding-right: .75em;
        height: 100%;
        text-align: right;
      }

      #selectors input {
        font-size: 1.5em;
      }
    </style>

    <script src="/scripts/tracker.js"></script>
  </head>

  <header>
    <?php require realpath($_SERVER["DOCUMENT_ROOT"])."/res/header.php"; ?>
  </header>

  <body>
    <div id="selectors" style="text-align: center; display: none;">
      <!-- <select id="location-selector" onchange="checkSelector()"></select><br> -->
      <input type="date" id="date-selector">
    </div>


    <div id="display"></div>
  </body>

  <?php require realpath($_SERVER["DOCUMENT_ROOT"])."/res/footer.php"; ?>
</html>

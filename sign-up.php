<!DOCTYPE html>
<html>
  <head>
    <title>Sign Up | KoolLunches</title>

    <?php
      require realpath($_SERVER["DOCUMENT_ROOT"])."/res/head.php";
      require_once realpath($_SERVER["DOCUMENT_ROOT"])."/res/lib.php";
    ?>

    <script src="/sign-up.js"></script>

    <style>
      .subdiv {
        margin-left: 2em;
      }

      .error {
        color: red;
        margin: 0px;
        padding: 0px;
      }
    </style>
  </head>

  <header>
    <?php
      include realpath($_SERVER["DOCUMENT_ROOT"])."/res/header.php";
    ?>
  </header>

  <body>
    <!-- Top section of the signup form -->
    <div class="content topSec" id="topSec">
      <center><h1>Sign Up</h1></center>

      <center>
        <p><b><u>Dates served are May 31st - August 4th Monday-Thursdays</u></b></p>
      </center>

      <p>Sack lunches will be prepared for pick up at local parks and areas in Bonham. Lunches will be four days a week, Monday through Thursday, with the exception of July 4th in order to observe Independence Day.</p>
      <p>If you are interested in your child or children participating please fill out the questionnaire at the bottom and submit.</p>
      <p>If you have any questions, please contact The Kool Lunches Program at thekoollunchesprogram@gmail.com or message us on Facebook @ Kool Lunches.</p>

      <div id="understand">
        <p>I understand that I do not let the Kool Lunches Program know before 10:55 that I will not be picking up lunches that day, my name will removed until ontact the Kool Lunches Program to begin receiving lunches again.</p>
        <center>
          <button onclick="showForm()">I understand</button>
        </center>
      </div>
    </div>

    <div id="form" class="content" style="margin-top: 1em; display: none;">
      <div>
        <p># Of Adults That Will Pickup</p>
        <input type="number" id="adultNumber" min=1 max=5 value=1 onchange="renderAdults()">
      </div>
      <div id="adults"></div>

      <div>
        <p># Of Lunches Needed</p>
        <input type="number" id="childNumber" min=1 max=5 value=1 onchange="renderChildren()">
      </div>
      <div id="children"></div>

      <div id="questions"></div>

      <p id="generalError" style="display: none">ERROR, Form not submitted</p>
      <center>
        <button type="submit" style="width: 100%" onclick="send();">Sign Up</button>
      </center>
    </div> <!-- Form -->

    <div class="content" id="submission" style="display: none;">
      Thank you for your submission
    </div>
  </body>

  <?php require realpath($_SERVER["DOCUMENT_ROOT"])."/res/footer.php"; ?>
</html>